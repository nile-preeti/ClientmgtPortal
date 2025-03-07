<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\JobSchedule;
use App\Models\User;
use App\Models\Payout;
use App\Models\Attendance;
use App\Models\UserService;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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
    public function show($id)
    {
        $title = "Payout Details";
        $back_url = route('payouts.index');
        $user = User::findOrFail($id);

        $adminFeePercent = config('constant.admin_fee', 11);
        $weeklyData = [];

        // Start from the user's creation date
        $start_date = Carbon::parse($user->created_at)->startOfWeek();
        $end_date = Carbon::now()->endOfWeek(); // Current week

        while ($start_date <= $end_date) {
            $weekKey = $start_date->format('Y-W'); // Year-Week format

            // Fetch attendance records for the user in this week
            $attendances = Attendance::where("user_id", $id)
                ->whereBetween('date', [$start_date, $start_date->copy()->endOfWeek()])
                ->whereNotNull('check_out_time')
                ->with('attendanceBreaks')
                ->get();

            $totalEarnings = 0;

            foreach ($attendances as $attendance) {
                // Match job based on attendance date
                $job = JobSchedule::where('user_id', $id)
                    ->whereDate('start_date', '<=', $attendance->date)
                    ->whereDate('end_date', '>=', $attendance->date)
                    ->first();

                if (!$job) continue;

                // Get service rate for the user and service
                $serviceRate = UserService::where('user_id', $id)
                    ->where('service_id', $job->service_id)
                    ->value('price_per_hour');

                if (!$serviceRate) continue;

                // Calculate total hours worked
                $checkInTime = Carbon::parse($attendance->check_in_time);
                $checkOutTime = Carbon::parse($attendance->check_out_time);
                $totalHoursWorked = $checkInTime->diffInSeconds($checkOutTime) / 3600; // Convert to hours

                // Subtract break time from total worked hours
                $breakHours = $attendance->attendanceBreaks->sum(function ($break) {
                    $breakStart = Carbon::parse($break->start_break);
                    $breakEnd = Carbon::parse($break->end_break);
                    return $breakStart->diffInSeconds($breakEnd) / 3600;
                });

                $actualWorkedHours = max($totalHoursWorked - $breakHours, 0); // Ensure no negative values

                // Calculate earnings
                $totalEarnings += floor($actualWorkedHours * $serviceRate * 100) / 100; // Floor to 2 decimal places
            }

            // Calculate admin fee and user's final earnings
            $adminEarnings = round(($totalEarnings * $adminFeePercent) / 100, 2);
            $userEarnings = round($totalEarnings - $adminEarnings, 2);

            $weeklyData[] = [
                'week_key' => $weekKey,
                'week_label' => $start_date->format('M d, Y') . ' - ' . $start_date->copy()->endOfWeek()->format('M d, Y'),
                'total_earnings' => $totalEarnings, // Total before admin cut
                'admin_earnings' => $adminEarnings, // Admin fee
                'user_earnings' => $userEarnings  // Final earnings for the user
            ];

            // Move to the next week
            $start_date->addWeek();
        }

        // Reverse the array so the most recent week appears first
        $weeklyData = array_reverse($weeklyData);

        // Implement pagination (10 per page)
        $currentPage = request()->input('page', 1); // Get current page from query string
        $perPage = 10; // Number of records per page
        $offset = ($currentPage - 1) * $perPage;
        
        $weeks = new LengthAwarePaginator(
            array_slice($weeklyData, $offset, $perPage), // Slice data for current page
            count($weeklyData), // Total items
            $perPage, // Items per page
            $currentPage, // Current page
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query params
        );

        return view("pages.payouts.details", compact("user", "weeks", 'title', 'back_url'));
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
