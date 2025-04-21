<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\JobSchedule;
use App\Models\User;
use App\Models\Payout;
use App\Models\Attendance;
use App\Models\UserService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;


class PayoutController extends Controller
{

    public function index()
    {
        $users = User::where("role_id", 2)
            ->when(request()->has('search'), function ($query) {
                $keyword = request('search');
                return $query->where("name", "LIKE", "%$keyword%")
                    ->orWhere("email", "LIKE", "%$keyword%")
                    ->orWhere("mobile_phone", "LIKE", "%$keyword%");
            })
            ->when(request()->has('status'), function ($query) {
                $keyword = request('status');
                return $keyword == "Active"
                    ? $query->where("status",  request("status"))
                    : $query->whereNull("status")->orWhere("status", "Inactive");
            })
            ->orderBy("id", 'desc')
            ->paginate(request()->has('search') ? 100 : 10);

        foreach ($users as $user) {
            // Check if payout is pending for the current or previous week
            $pendingPayout = Payout::where("user_id", $user->id)
                ->where("status", "Pending")
                ->whereBetween("start_date", [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->exists();

            $user->available = !$pendingPayout;
        }

        // Generate payouts for the past 4 weeks (for testing)
        // for ($i = 0; $i < 4; $i++) {
        //     $start_date = Carbon::now()->startOfWeek()->subWeeks($i);
        //     $end_date = Carbon::now()->endOfWeek()->subWeeks($i);
        //     $this->calculateWeeklyPayout($start_date, $end_date);
        // }
        $title="Payouts";
        return view("pages.payouts.index", compact("users","title"));
    }

    // Calculate Weekly Payouts
    public function calculateWeeklyPayout($start_date, $end_date)
    {
        $attendances = Attendance::whereBetween('attendance_date', [$start_date, $end_date])
            ->whereNotNull('check_out_time')
            ->get();
    
        $payouts = $attendances->groupBy('user_id')->map(function ($records, $user_id) use ($start_date, $end_date) {
            $total_earnings = 0;
    
            foreach ($records as $attendance) {
                // Match job based on attendance date
                $job = JobSchedule::where('user_id', $user_id)
                    ->whereDate('start_date', '<=', $attendance->attendance_date)
                    ->whereDate('end_date', '>=', $attendance->attendance_date)
                    ->first();
    
                if (!$job) continue;
    
                $serviceRate = UserService::where('user_id', $user_id)
                    ->where('service_id', $job->service_id)
                    ->value('price_per_hour');
    
                if (!$serviceRate) continue;
    
                // Calculate total hours worked (including minutes)
                $hoursWorked = Carbon::parse($attendance->check_in_time)->diffInMinutes(Carbon::parse($attendance->check_out_time)) / 60;
                $totalEarnings = round($hoursWorked * $serviceRate, 2); // Rounded to 2 decimal places
    
                $total_earnings += $totalEarnings;
            }
    
            return [
                'user_id' => $user_id,
                'total_earnings' => $total_earnings,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => 'Pending'
            ];
        });
    
        // Save the weekly payouts
        foreach ($payouts as $payout) {
            Payout::updateOrCreate(
                [
                    'user_id' => $payout['user_id'],
                    'start_date' => $payout['start_date'],
                    'end_date' => $payout['end_date']
                ],
                [
                    'total_amount' => $payout['total_earnings'],
                    'status' => 'Pending'
                ]
            );
        }
    }
    

    // Show User Payout Details
    public function show(Request $request, $id)
    {
        $title = "Payout Details";
        $back_url = route('payouts.index');
        $user = User::findOrFail($id);
    
        $adminFeePercent = config('constant.admin_fee', 11);
        $weeklyData = [];
    
        // Get the year the user was created, to avoid calculating before that year.
        $userCreatedYear = Carbon::parse($user->created_at)->year;
    
        // Get filter values from request, defaulting to current month/year.
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedYear = $request->input('year', Carbon::now()->year);
    
        // Determine the start and end dates for the selected month (extending to full weeks)
        $start_date = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth()->startOfWeek();
        $end_date = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth()->endOfWeek();
    
        // Loop week by week
        while ($start_date <= $end_date) {
            $year = $start_date->year;
            if ($year < $userCreatedYear) {
                $start_date->addWeek();
                continue;
            }
    
            $weekKey = $start_date->format('Y-W');
            $weekNumber = $start_date->weekOfYear;
    
            $totalEarnings = 0;
            $totalAdminEarnings = 0;
            $totalUserEarnings = 0;
            $dailyEarnings = [];
    
            // Loop over 7 days of the week
            for ($i = 0; $i < 7; $i++) {
                $currentDate = $start_date->copy()->addDays($i);
    
                if ($currentDate->month != $selectedMonth) {
                    continue; // Skip days outside the selected month.
                }
    
                // Fetch attendances for the current day based on job_id (both attendance and attendance_breaks)
                $attendances = Attendance::where("user_id", $user->id)
                    ->whereDate('date', $currentDate)
                    ->whereNotNull('check_in_time') // Ensures user is present.
                    ->whereNotNull('check_out_time') // Ensures checkout is present.
                    ->with('attendanceBreaks')
                    ->get();
    
                $dayEarnings = 0;
    
                foreach ($attendances as $attendance) {
                    // Fetch the job_id from the attendance record
                    $job_id = $attendance->job_id;
    
                    // Calculate the total worked hours based on check-in and check-out times
                    $checkInTime = Carbon::parse($attendance->check_in_time);
                    $checkOutTime = Carbon::parse($attendance->check_out_time);
                    $totalWorkedHours = $checkInTime->diffInSeconds($checkOutTime) / 3600; // Calculate total hours worked
                    
                    // Sum break hours recorded during the attendance for the specific job_id
                    // dd($attendance);
                    $breakHours = $attendance->attendanceBreaks->sum(function ($break) {
                        // dd($break);  // Debug the break data
                        if (!$break->start_break || !$break->end_break) {
                            return 0;  // Return 0 if either break start or end is missing
                        }
                    
                        // Parse break start and end times
                        $breakStart = Carbon::parse($break->start_break);
                        $breakEnd = Carbon::parse($break->end_break);
                    
                        // Calculate the break duration in hours
                        return $breakStart->diffInSeconds($breakEnd) / 3600;  // Convert seconds to hours
                    });
                    
                    
                    // Adjust worked hours by subtracting break hours
                    $actualWorkedHours = max(round($totalWorkedHours - $breakHours, 2), 0);
                     
    
                    // Now calculate the earnings for this attendance based on the actual worked hours
                    $jobs = JobSchedule::where('user_id', $user->id)
                        ->where('id', $job_id) // Match the specific job_id
                        ->whereDate('start_date', '<=', $attendance->date)
                        ->whereDate('end_date', '>=', $attendance->date)
                        ->where('status', 2)
                        ->get();
    
                    foreach ($jobs as $job) {
                        // Get the rate for the job based on the service_id
                        $serviceRate = UserService::where('user_id', $user->id)
                            ->where('service_id', $job->service_id)
                            ->value('price_per_hour');
    
                        if (!$serviceRate) continue;
    
                        // Calculate earnings based on the worked hours
                        $jobEarning = number_format(($actualWorkedHours * $serviceRate * 100) / 100, 2, '.', '');
                        $dayEarnings += $jobEarning;
                    }
                }
    
                // Compute admin fee (in dollars) and the user's net earnings for the day.
                $adminEarnings = number_format(($dayEarnings * $adminFeePercent) / 100, 2, '.', '');
                $userEarnings  = number_format($dayEarnings - $adminEarnings, 2, '.', '');
    
                $totalEarnings       += $dayEarnings;
                $totalAdminEarnings  += $adminEarnings;
                $totalUserEarnings   += $userEarnings;
    
                $dailyEarnings[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'earnings' => round($dayEarnings, 2),
                    'admin_fee' => $adminEarnings,
                    'user_earnings' => $userEarnings
                ];
            }
            // dd($dailyEarnings);
    
            $weeklyData[] = [
                'week_key' => $weekKey,
                'week_label' => "Week $weekNumber/$year",
                'total_earnings' => $totalEarnings,
                'admin_earnings' => $totalAdminEarnings,
                'user_earnings' => $totalUserEarnings,
                'daily_earnings' => $dailyEarnings,
                'is_current_week' => ($weekNumber === Carbon::now()->weekOfYear),
            ];
    
            $start_date->addWeek();
        }
    
        // Sort the weeks in descending order.
        usort($weeklyData, function ($a, $b) {
            [$yearA, $weekA] = explode('-', $a['week_key']);
            [$yearB, $weekB] = explode('-', $b['week_key']);
            return ($yearB <=> $yearA) ?: ($weekB <=> $weekA);
        });
    
        $paginatedData = $this->paginate($weeklyData, 3);
    
        return view("pages.payouts.details", compact("user", 'title', 'back_url', 'paginatedData', 'selectedMonth', 'selectedYear'));
    }
    


    private function paginate($items, $perPage)
    {
        $currentPage = Paginator::resolveCurrentPage();
        $collection = new Collection($items);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
    
        return new LengthAwarePaginator(
            $currentPageItems,
            count($collection),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }



    

    



    // Weekly Cron Job
    public function weeklyCron()
    {
        $start_date = Carbon::now()->startOfWeek();
        $end_date = Carbon::now()->endOfWeek();
        $this->calculateWeeklyPayout($start_date, $end_date);
    }

    // Update Payment Status
    public function update_payment(Request $request, $id)
    {
        $payout = Payout::find($id);
        $payout->payment_date = Carbon::createFromFormat("m-d-Y", $request->payment_date);
        $payout->payment_mode = $request->payment_mode;
        $payout->status = 'Completed';
        $payout->save();

        return back()->with('success', 'Payment updated successfully');
    }
}
