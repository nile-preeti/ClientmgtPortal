<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Service;
use App\Models\User;
use App\Models\UserService;
use App\Models\JobSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->search;
        $users = User::when(request()->filled("search"), function ($query) {
            $keyword = trim(request("search"));
            return $query->where("name", "LIKE", "%$keyword%")->orWhere("designation", "LIKE", "%$keyword%")->orWhere("email", "LIKE", "%$keyword%")->orWhere("phone", "LIKE", "%$keyword%");
        })
            ->where("role_id", 2)
            ->when(request()->filled("status"), function ($query) {
                return $query->where("status", request("status"));
            })

            ->orderBy("id", "desc")->paginate(config("contant.paginatePerPage"));

        $title = "Employee Management";

        return view("pages.users.index", compact("title", 'users', 'search'));
    }

    public function create(Request $request)
    {
        $title = "Create Employee";
        $services = Service::all();
        return view("pages.users.create", compact('services', 'title'));
    }
    public function edit(Request $request, $id)
    {
        $title = "Edit Employee";
        $back_url = route('userss.index');

        $user = User::find($id);
        if (!$user) {
            return back()->with("error", 'User does not exists');
        }
        $services = Service::all();

        return view("pages.users.create", compact('user', 'services', 'title', 'back_url'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required| unique:users,email',
            'password' => 'required',

            'phone' => 'required',


        ]);

        $user = new User();
        $user->role_id = 2;
        $user->name = ucwords(strtolower($request->name));
        $user->email = $request->email;
        $user->image = $request->image;

        $user->phone = $request->phone;
        $user->emp_id = $request->emp_id;

        $user->status = $request->status;

        $user->password = Hash::make($request->password);

        if ($request->has("services") && is_array($request->services)) {
            foreach ($request->services as $i => $is_) {
                $service = new UserService();
                $service->user_id = $user->id;
                $service->service_id = $is_;
                $service->price_per_hour = $request->price_per_hour[$i];
                $service->save();
            }
        }
        $user->save();

        return response()->json(['success' => true, 'message' => "User Created Successfully", 'redirect' => true, 'route' => route('users.index')]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|exists:users,email',
            'phone' => 'required',


        ]);

        $user =  User::find($id);
        $user->name = ucwords(strtolower($request->name));
        $user->email = $request->email;
        $user->image = $request->image;
        $user->phone = $request->phone;
        $user->status = $request->status;
        $user->emp_id = $request->emp_id;

        $user->save();
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has("old_services") && is_array($request->old_services)) {
            foreach ($request->old_services as $i => $is) {
                $service =  UserService::find($request->service_user_id[$i]);
                if ($service) {
                    $service->user_id = $user->id;
                    $service->service_id = $is;
                    $service->price_per_hour = $request->old_price_per_hour[$i];
                    $service->save();
                }
            }
            // UserService::where("user_id", $id)->whereNotIn("id", $request->service_user_id)->delete();
        } else {
            UserService::where("user_id", $id)->delete();
        }
        if ($request->has("services") && is_array($request->services)) {
            foreach ($request->services as $i => $is) {
                $service = new UserService();
                $service->user_id = $user->id;
                $service->service_id = $is;
                $service->price_per_hour = $request->price_per_hour[$i];
                $service->save();
            }
        }




        return response()->json([
            'success' => true,
            'message' => "User Updated Successfully",
            'redirect' => true,
            'route' => route('userss.index') // Returning route for frontend redirection
        ]);
    }

    public function  destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => true, 'message' => "User deleted successfully"]);
        }
        return response()->json(['success' => false, 'message' => "User does not exists"]);
    }
    // user end routes
    public function userAttendance(Request $request, $id)
    {

        $month = request('month', date('m'));
        $year = request('year', date('Y'));
        $statusFilter = $request->query('status'); // Store status filter separately
        $user = User::where('id', $id)->first();

        // Fetch attendance records
        $attendanceRecords = Attendance::where('user_id', $id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->when($request->filled("date"), function ($query) {
                return $query->whereDate("date", request('date'));
            })
            ->when($statusFilter, function ($query) use ($statusFilter) {
                return $query->where('status', $statusFilter);
            })
            ->with('attendanceBreaks')
            ->get()
            ->keyBy('date'); // Store by date for quick lookup

        // dd($attendanceRecords);
        $title = "Employee Attendance Records";
        $back_url = route('userss.index');
        $holidays = Holiday::whereMonth('date', $month)->whereYear('date', $year)->pluck('date')->toArray();

        $allDays = [];
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $currentDate = Carbon::now()->format('Y-m-d');
        if ($request->has('date')) {
            $startDate = Carbon::parse(request('date'));
            $endDate =  Carbon::parse(request('date'));
        }
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');

            if (isset($attendanceRecords[$formattedDate])) {

                $record = $attendanceRecords[$formattedDate];
                // dd($record);

                $checkInTime = !empty($record->check_in_time) ? strtotime($record->check_in_time) : null;
                $checkOutTime = !empty($record->check_out_time) ? strtotime($record->check_out_time) : null;
                $netWorkedHours = 0;
                // $breaks = \App\Models\AttendanceBreak::where('user_id', 9)
                //     ->where('attendance_id', 19)
                //         ->get();
                if (is_null($checkInTime) || is_null($checkOutTime)) {
                    $recordStatus = 'Absent'; // No check-in or check-out means Absent
                } else {
                    // Calculate total worked time (before break deduction)
                    $workedHours = ($checkOutTime - $checkInTime) / 3600; // Convert to hours

                    // Fetch total break time for the user on the given date
                    $totalBreakSeconds = \App\Models\AttendanceBreak::where('user_id', $record->user_id)
                        ->where('attendance_id', $record->id)
                        ->whereNotNull('start_break')
                        ->whereNotNull('end_break')
                        ->get()
                        ->sum(function ($break) {
                            return strtotime($break->end_break) - strtotime($break->start_break);
                        });
                    // Fetch total break time for the user on the given date

                    // $breaks = \App\Models\AttendanceBreak::where('user_id', 9)
                    // ->where('attendance_id', 19)
                    //     ->get();

                    // Convert break time to hours and subtract from worked hours
                    $totalBreakHours = $totalBreakSeconds / 3600;
                    $netWorkedHours = $workedHours - $totalBreakHours;

                    // Determine attendance status after break deduction
                    if ($netWorkedHours < 4.5) {
                        $recordStatus = 'Absent';
                    } elseif ($netWorkedHours >= 4.5 && $netWorkedHours < 9) {
                        $recordStatus = 'Half-day';
                    } else {
                        $recordStatus = 'Present';
                    }
                }

                $allDays[] = [
                    'date' => $formattedDate,
                    'check_in_time' => $record->check_in_time,
                    'check_in_full_address' => $record->check_in_full_address,
                    'check_out_time' => $record->check_out_time,
                    'check_out_full_address' => $record->check_out_full_address,
                    'status' => $recordStatus,
                    'working_hours' => number_format($netWorkedHours, 1),
                    'breaks' => $record->attendanceBreaks->isNotEmpty() ? $record->attendanceBreaks : [],
                ];
            } else {
                if (in_array($formattedDate, $holidays)) {
                    $recordStatus = 'Holiday';
                } elseif ($date->isWeekend()) {
                    $recordStatus = 'Weekly Off';
                } elseif ($formattedDate > $currentDate) {
                    $recordStatus = 'N/A';
                } else {
                    $recordStatus = 'Absent';
                }

                $allDays[] = [
                    'date' => $formattedDate,
                    'check_in_time' => null,
                    'check_in_full_address' => null,
                    'check_out_time' => null,
                    'check_out_full_address' => null,
                    'status' => $recordStatus,
                    'working_hours' => 0,
                    'breaks' => []
                ];
            }
        }

        // Apply status filter after processing
        if ($statusFilter) {
            $allDays = array_filter($allDays, function ($day) use ($statusFilter) {
                return $day['status'] === $statusFilter;
            });
        }

        // Paginate the data
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $allDaysPaginated = new LengthAwarePaginator(
            collect($allDays)->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            count($allDays),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        // dd($allDaysPaginated);

        // Calculate attendance summary
        $totalWorkingDays = collect($allDays)->filter(function ($day) {
            return !in_array($day['status'], ['Holiday', 'Weekly Off', 'N/A']);
        })->count();

        $totalPresent = collect($allDays)->sum(function ($day) {
            return $day['status'] === 'Present' ? 1 : ($day['status'] === 'Half-day' ? 0.5 : 0);
        });

        $totalHalfDay = collect($allDays)->where('status', 'Half-day')->count();
        $totalAbsent = $totalWorkingDays - $totalPresent;

        return view("pages.users.attendance", compact(
            "allDaysPaginated",
            "totalWorkingDays",
            "totalPresent",
            "totalHalfDay",
            "totalAbsent",
            "title",
            'user',
            'back_url',
        ));
    }
    public function attendance()
    {

        $user = Auth::user();
        // Find today's check-in record
        return view("users.attendance", compact('user'));
    }

    public function login()
    {
        if (auth()->user()) {
            return redirect(route("user.dashboard"));
        }
        return view("users.login");
    }
    public function login_post(Request $request)
    {
        // Validate the input
        $request->validate([
            'password' => 'required',
            'emp_id' => 'required|exists:users,emp_id',
        ]);

        $credentials = $request->only('emp_id', 'password');
        $user = User::where('emp_id', $request->emp_id)->first();

        // Check if the user exists
        if ($user) {
            // If it's a user (role_id != 1)
            if ($user->role_id != 1) {
                // Attempt to log in as a regular user
                if (Auth::guard('web')->attempt($credentials)) {
                    return response()->json([
                        'status' => 'success',
                        'redirect' => route("user.dashboard"),
                        'message' => "User logged in successfully",
                        'user' => $user
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid credentials',
                    ]);
                }
            }
            // If it's an admin (role_id == 1), return an error
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access. Admins cannot log in via this portal.',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ]);
        }
    }

    public function attendance_records()
    {
        $user = Auth::user();
        return view("users.attendance_records", compact('user'));
    }

    public function holidays()
    {

        $holidays = \App\Models\Holiday::whereYear('date', now()->year)
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->date)->format('F Y'); // Group by month and year
            });
        // dd($holidays);
        return view("users.holidays", compact('holidays'));
    }
    public function dashboard()
    {
        $currentYear = Carbon::now()->year;
        $user = auth()->user();

        $holidaysCount = Holiday::whereYear('date', $currentYear)->count();
        $adminFeePercent = config('constant.admin_fee', 11);
        $weeklyData = [];

        $start_date = Carbon::parse($user->created_at)->startOfWeek();
        $end_date = Carbon::now()->endOfWeek();

        while ($start_date <= $end_date) {
            $weekKey = $start_date->format('Y-W');

            $totalEarnings = 0;

            for ($i = 0; $i < 7; $i++) {
                $currentDate = $start_date->copy()->addDays($i);

                $attendance = Attendance::where("user_id", $user->id)
                    ->whereDate('date', $currentDate)
                    ->first();

                if (!$attendance) continue;

                $job = JobSchedule::where('user_id', $user->id)
                    ->whereDate('start_date', '<=', $currentDate)
                    ->whereDate('end_date', '>=', $currentDate)
                    ->where('status', 2)
                    ->first();

                if (!$job) continue;

                $serviceRate = UserService::where('user_id', $user->id)
                    ->where('service_id', $job->service_id)
                    ->value('price_per_hour');

                if (!$serviceRate) continue;

                $jobStart = Carbon::parse($attendance->date . ' ' . $job->start_time);
                $jobEnd   = Carbon::parse($attendance->date . ' ' . $job->end_time);
                $totalHoursWorked = number_format($jobStart->diffInSeconds($jobEnd) / 3600, 2, '.', '');

                $breakHours = $attendance->attendanceBreaks->sum(function ($break) use ($jobStart, $jobEnd) {
                    $breakStart = Carbon::parse($break->start_break);
                    $breakEnd   = Carbon::parse($break->end_break);

                    if ($breakStart->between($jobStart, $jobEnd) && $breakEnd->between($jobStart, $jobEnd)) {
                        return $breakStart->diffInSeconds($breakEnd) / 3600;
                    }

                    return 0;
                });

                $actualWorkedHours = max(round($totalHoursWorked - $breakHours, 2), 0);
                $totalEarnings += round($actualWorkedHours * $serviceRate, 2);
            }

            $adminEarnings = round(($totalEarnings * $adminFeePercent) / 100, 2);
            $userEarnings = round($totalEarnings - $adminEarnings, 2);

            $weeklyData[] = [
                'week_key' => $weekKey,
                'total_earnings' => $totalEarnings,
                'admin_earnings' => $adminEarnings,
                'user_earnings' => $userEarnings
            ];

            $start_date->addWeek();
        }

        $grandTotalEarnings = array_sum(array_column($weeklyData, 'user_earnings'));

        return view("users.dashboard", compact('holidaysCount', 'grandTotalEarnings'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return response()->json(['success' => true]);
    }


    public function profile(Request $request)
    {
        $user = auth()->user();
        $services = UserService::where('user_id', $user->id)->with('service')->get();
        // dd($services);
        return view("users.profile", compact('user', 'services'));
    }


    public function payout(Request $request)
    {
        $title = "Payout Details";
        $back_url = route('payouts.index');
        $user = auth()->user();;

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

                // Fetch attendances for the current day.
                // Here, we assume that a record exists if the user was present.
                $attendances = Attendance::where("user_id", $user->id)
                    ->whereDate('date', $currentDate)
                    ->whereNotNull('check_in_time') // Ensures user is present.
                    ->with('attendanceBreaks')
                    ->get();

                $dayEarnings = 0;

                foreach ($attendances as $attendance) {
                    // Find a job schedule for this day that is marked completed (status=2)
                    $job = JobSchedule::where('user_id', $user->id)
                        ->whereDate('start_date', '<=', $attendance->date)
                        ->whereDate('end_date', '>=', $attendance->date)
                        ->where('status', 2)
                        ->first();

                    if (!$job) continue;

                    // Get the rate for the service assigned to the job.
                    $serviceRate = UserService::where('user_id', $user->id)
                        ->where('service_id', $job->service_id)
                        ->value('price_per_hour');

                    if (!$serviceRate) continue;

                    // Calculate the expected working period based on the job_schedule start_time and end_time.
                    // We use the attendance's date to construct the DateTime.
                    $jobStart = Carbon::parse($attendance->date . ' ' . $job->start_time);
                    $jobEnd   = Carbon::parse($attendance->date . ' ' . $job->end_time);
                    $totalHoursWorked = number_format($jobStart->diffInSeconds($jobEnd) / 3600, 2, '.', '');

                    // Sum break hours recorded during that attendance.
                    $breakHours = $attendance->attendanceBreaks->sum(function ($break) use ($jobStart, $jobEnd) {
                        $breakStart = Carbon::parse($break->start_break);
                        $breakEnd   = Carbon::parse($break->end_break);
    
                        if ($breakStart->between($jobStart, $jobEnd) && $breakEnd->between($jobStart, $jobEnd)) {
                            return $breakStart->diffInSeconds($breakEnd) / 3600;
                        }
    
                        return 0;
                    });

                    $actualWorkedHours = max(round($totalHoursWorked - $breakHours, 2), 0);
                   
                    // Calculate earnings for this attendance record.
                    $dayEarnings = number_format(($actualWorkedHours * $serviceRate * 100) / 100, 2, '.', '');
                    // dd($dayEarnings);
                }

                // Compute admin fee (in dollars) and the user's net earnings.
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

        return view('users.payout', compact('paginatedData', 'selectedMonth', 'selectedYear'));
    }





    // Pagination function
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


    public function directory(Request $request)
    {
        return view("users.directory");
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed'
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully!']);
    }

    public function Services(Request $request)
    {
        return view("users.directory");
    }

    public function job_schedule($id)
    {
        $title = "Scheduled Jobs";
        $back_url = route('userss.index');
        $job_schedules = JobSchedule::where("user_id", $id)->get();
        foreach ($job_schedules as $key => $value) {
            $charge = UserService::where("user_id", $value->user_id)->where("service_id", $value->service_id)->first();
            if ($charge) {
                $value->charge =  $charge->price_per_hour;
            } else {
                $value->charge = 0;
            }
        }
        $user = User::find($id);
        return view("pages.users.job_schedule", compact("job_schedules", 'user', 'title', 'back_url'));
    }
}
