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
use Illuminate\Support\Facades\DB;

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
        $services = Service::with('subCategories')->get();
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
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/images'), $filename); // moves to public/uploads/images
            $user->image = 'uploads/images/' . $filename; // Save relative path
        }

        $user->phone = $request->phone;
        $user->emp_id = $request->emp_id;

        $user->status = $request->status;

        $user->password = Hash::make($request->password);
        if ($request->has("services") && is_array($request->services)) {
            foreach ($request->services as $i => $is_) {
                $service = new UserService();
                $service->user_id = $user->id;
                $service->service_id = $is_;
                $service->service_sub_category = $request->sub_categories[$i] ?? null;
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
        $user->phone = $request->phone;
        $user->status = $request->status;
        $user->emp_id = $request->emp_id;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/images'), $filename); // moves to public/uploads/images
            $user->image = 'uploads/images/' . $filename; // Save relative path
        }

        $user->save();
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has("old_services") && is_array($request->old_services)) {
            foreach ($request->old_services as $i => $is) {
                $service = UserService::find($request->service_user_id[$i]);
                if ($service) {
                    $service->user_id = $user->id;
                    $service->service_id = $is;
                    $service->service_sub_category = $request->old_sub_category[$i] ?? null; // Store subcategory ID
                    $service->price_per_hour = $request->old_price_per_hour[$i];
                    $service->save();
                }
            }
        } else {
            UserService::where("user_id", $id)->delete();
        }
        
        if ($request->has("services") && is_array($request->services)) {
            foreach ($request->services as $i => $is) {
                $service = new UserService();
                $service->user_id = $user->id;
                $service->service_id = $is;
                $service->service_sub_category = $request->sub_categories[$i] ?? null; // Store subcategory ID
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
    { // Store status filter separately
        $user = User::where('id', $id)->first();// Store by date for quick lookup

        // dd($attendanceRecords);
        $title = "Employee Attendance Records";
        $back_url = route('userss.index');

        // Get month and year from request, fallback to current
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendanceRecords = Attendance::with(['attendanceBreaks', 'jobSchedule.service'])
            ->where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        $totalMinutes = 0;
        foreach ($attendanceRecords as $record) {
            if ($record->check_in_time && $record->check_out_time) {
                $in = \Carbon\Carbon::parse($record->check_in_time);
                $out = \Carbon\Carbon::parse($record->check_out_time);
                $duration = $out->diffInMinutes($in);

                $breakMinutes = $record->attendanceBreaks->sum(function ($break) {
                    return \Carbon\Carbon::parse($break->end_break)->diffInMinutes(\Carbon\Carbon::parse($break->start_break));
                });

                $totalMinutes += ($duration - $breakMinutes);
            }
        }

        $totalHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;
        


        return view("pages.users.attendance", compact(
            "attendanceRecords",
            "totalHours",
            "remainingMinutes",
            "title",
            'user',
            'back_url',
        ));
    }
    public function attendance(Request $request)
    {
        $user = Auth::user();
        $service_id = $request->get('service_id'); // fetch the service_id from query param
    
        return view("users.attendance", compact('user', 'service_id'));
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

            if ($user->status == 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your account has been deactivated by the admin.',
                ]);
            }
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

    public function attendance_records(Request $request)
    {
        $user = Auth::user();

        // Get month and year from request, fallback to current
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendanceRecords = Attendance::with(['attendanceBreaks', 'jobSchedule.service'])
            ->where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        $totalMinutes = 0;
        foreach ($attendanceRecords as $record) {
            if ($record->check_in_time && $record->check_out_time) {
                $in = \Carbon\Carbon::parse($record->check_in_time);
                $out = \Carbon\Carbon::parse($record->check_out_time);
                $duration = $out->diffInMinutes($in);

                $breakMinutes = $record->attendanceBreaks->sum(function ($break) {
                    return \Carbon\Carbon::parse($break->end_break)->diffInMinutes(\Carbon\Carbon::parse($break->start_break));
                });

                $totalMinutes += ($duration - $breakMinutes);
            }
        }

        $totalHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;

        return view("users.attendance_records", [
            'user' => $user,
            'attendanceRecords' => $attendanceRecords,
            'currentMonth' => $month,
            'currentYear' => $year,
            'totalHours' => $totalHours,
            'remainingMinutes' => $remainingMinutes
        ]);
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
    // public function dashboard(Request $request)
    // {
    //     $title = "Dashboard";
    //     $user = auth()->user();
        
    //     $adminFeePercent = config('constant.admin_fee', 11);
        
    //     // Get the year the user was created, to avoid calculating before that year.
    //     $userCreatedYear = Carbon::parse($user->created_at)->year;
        
    //     // Get filter values from the request, default to current year.
    //     $selectedYear = $request->input('year', Carbon::now()->year);
        
    //     // Determine the start and end dates for the selected year.
    //     $start_date = Carbon::create($selectedYear, 1, 1)->startOfYear();
    //     $end_date = Carbon::create($selectedYear, 12, 31)->endOfYear();
        
    //     // Initialize totals for earnings, admin earnings, and user earnings
    //     $totalEarnings = 0;
    //     $totalAdminEarnings = 0;
    //     $totalUserEarnings = 0;
    //     $grandTotalEarnings = 0; // For storing total earnings
    //     $holidaysCount = 0; // For counting holidays
    
    //     // Loop through each day of the selected year to calculate earnings
    //     for ($currentDate = $start_date; $currentDate <= $end_date; $currentDate->addDay()) {
    //         if ($currentDate->year < $userCreatedYear) {
    //             continue; // Skip if the year is before the user's creation year
    //         }
    
    //         // Fetch attendances for the current day based on the user's attendance data
    //         $attendances = Attendance::where("user_id", $user->id)
    //             ->whereDate('date', $currentDate)
    //             ->whereNotNull('check_in_time')
    //             ->whereNotNull('check_out_time')
    //             ->with('attendanceBreaks')
    //             ->get();
    
    //         // Calculate earnings for the current day
    //         $dayEarnings = 0;
    
    //         foreach ($attendances as $attendance) {
    //             $job_id = $attendance->job_id;
    //             $checkInTime = Carbon::parse($attendance->check_in_time);
    //             $checkOutTime = Carbon::parse($attendance->check_out_time);
    //             $totalWorkedHours = $checkInTime->diffInSeconds($checkOutTime) / 3600; // Total hours worked
    
    //             // Sum break hours recorded during the attendance
    //             $breakHours = $attendance->attendanceBreaks->sum(function ($break) {
    //                 if (!$break->start_break || !$break->end_break) {
    //                     return 0;  // Return 0 if either break start or end is missing
    //                 }
    //                 $breakStart = Carbon::parse($break->start_break);
    //                 $breakEnd = Carbon::parse($break->end_break);
    //                 return $breakStart->diffInSeconds($breakEnd) / 3600;  // Break duration in hours
    //             });
    
    //             // Adjust worked hours by subtracting break hours
    //             $actualWorkedHours = max(round($totalWorkedHours - $breakHours, 2), 0);
    
    //             // Fetch job rate for the job
    //             $job = JobSchedule::where('user_id', $user->id)
    //                 ->where('id', $job_id)
    //                 ->whereDate('start_date', '<=', $attendance->date)
    //                 ->whereDate('end_date', '>=', $attendance->date)
    //                 ->where('status', 2)
    //                 ->first();
    
    //             if ($job) {
    //                 $serviceRate = UserService::where('user_id', $user->id)
    //                     ->where('service_id', $job->service_id)
    //                     ->value('price_per_hour');
    
    //                 if ($serviceRate) {
    //                     // Calculate the earnings based on the worked hours and service rate
    //                     $dayEarnings += $actualWorkedHours * $serviceRate;
    //                 }
    //             }
    //         }
    
    //         // Compute admin fee (in dollars) and the user's net earnings for the day
    //         $adminEarnings = ($dayEarnings * $adminFeePercent) / 100;
    //         $userEarnings = $dayEarnings - $adminEarnings;
    
    //         // Add daily earnings to the totals
    //         $totalEarnings += $dayEarnings;
    //         $totalAdminEarnings += $adminEarnings;
    //         $totalUserEarnings += $userEarnings;
    //     }
    
    //     // Calculate the grand total earnings and holidays count
    //     $grandTotalEarnings = round($totalUserEarnings, 2);
    
    //     // Pass the calculated earnings to the dashboard view
    //     return view('users.dashboard', compact('grandTotalEarnings', 'totalAdminEarnings', 'totalUserEarnings', 'holidaysCount', 'selectedYear'));
    // }

    public function dashboard(Request $request)
{
    $user = Auth::user();
    $authUserId = $user->id;
    $adminFeePercentage = 11; // 11% admin fee

    $year = now()->year;
    $month = now()->month;
    $daysInMonth = now()->daysInMonth;

    $calendarDays = [];
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = \Carbon\Carbon::create($year, $month, $day);
        $calendarDays[] = [
            'day' => $date->format('D'),          // Mon, Tue, etc.
            'date' => $date->format('d'),         // 01, 02, etc.
            'full_date' => $date->toDateString()  // YYYY-MM-DD
        ];
    }

    // Get selected date or default to today
    $selectedDate = $request->input('selected_date');

    $today = $selectedDate ? Carbon::parse($selectedDate)->toDateString()
                           : Carbon::today()->toDateString();
    $now = Carbon::now();

    // **Assigned jobs (status = 1)**
    $assignedSchedules = JobSchedule::with([
        'service',
        'customer',
        'subCategory',
        'userService',
        'attendance' => function ($query) use ($today, $authUserId) {
            $query->where('user_id', $authUserId)
                  ->whereDate('created_at', $today);
        }
    ])
    ->where('status', 1)
    ->where('user_id', $authUserId)
    ->where(function ($query) use ($today, $authUserId) {
        $query->where(function ($q) use ($today, $authUserId) {
            // Case 1: Today is within job range and attendance doesn't exist
            $q->whereDate('start_date', '<=', $today)
              ->whereDate('end_date', '>=', $today)
              ->whereDoesntHave('attendance', function ($subQuery) use ($authUserId) {
                  $subQuery->where('user_id', $authUserId)
                           ->whereRaw('DATE(created_at) BETWEEN start_date AND end_date');
              });
        })->orWhere(function ($q) use ($today) {
            // Case 2: Job has already passed (end_date < today)
            $q->whereDate('end_date', '<', $today);
        });
    })
    ->orderBy('id', 'desc')
    ->get();
    

    // **Ongoing jobs (status = 1 and attendance exists between start and end dates)**
    $ongoingSchedules = JobSchedule::with([
        'service',
        'customer',
        'subCategory',
        'userService',
        'attendance' => function ($query) use ($authUserId) {
            $query->where('user_id', $authUserId);
        }
    ])
    ->where('status', 1)
    ->where('user_id', $authUserId)
    ->whereHas('attendance', function ($query) use ($authUserId) {
        $query->where('user_id', $authUserId)
              ->whereBetween('created_at', [
                  DB::raw('start_date'),  // start_date from JobSchedule
                  DB::raw('end_date')     // end_date from JobSchedule
              ]);
    })
    ->get();

    // Completed jobs (status = 2 or 1 that are ended)
    if ($selectedDate) {
        try {
            $parsedDate = Carbon::parse($selectedDate)->toDateString();
        } catch (\Exception $e) {
            $parsedDate = null; // fallback if invalid
        }
    }
    $completedSchedulesQuery = JobSchedule::with([
        'service',
        'subCategory',
        'userService',
        'attendance' => function($query) use ($authUserId) {
            $query->where('user_id', $authUserId)
                  ->with('attendanceBreaks');
        }
    ])
    ->where('status', 2)
    ->where('user_id', $authUserId);

    // Apply exact date match if selected
    if (!empty($parsedDate)) {
        $completedSchedulesQuery->whereDate('end_date', '=', $parsedDate);
    } 

    $completedSchedules = $completedSchedulesQuery
        ->latest('end_time')
        ->get();
    //dd($completedSchedules);

    // Calculate earnings
    foreach ($completedSchedules as $job) {
        $totalJobHours = 0;

        if ($job->attendance->isNotEmpty() && $job->userService) {
            foreach ($job->attendance as $attendance) {
                if ($attendance->check_in_time && $attendance->check_out_time) {
                    $checkIn = Carbon::parse($attendance->check_in_time);
                    $checkOut = Carbon::parse($attendance->check_out_time);
                    $totalWorkedSeconds = $checkOut->diffInSeconds($checkIn);

                    $breakSeconds = 0;
                    foreach ($attendance->attendanceBreaks as $break) {
                        if ($break->start_break && $break->end_break) {
                            $breakStart = Carbon::parse($break->start_break);
                            $breakEnd = Carbon::parse($break->end_break);

                            if ($breakStart->between($checkIn, $checkOut) && $breakEnd->between($checkIn, $checkOut)) {
                                $breakSeconds += $breakEnd->diffInSeconds($breakStart);
                            }
                        }
                    }

                    $netSeconds = max(0, $totalWorkedSeconds - $breakSeconds);
                    $hours = $netSeconds / 3600;
                    $totalJobHours += $hours;
                }
            }

            $job->total_hours = round($totalJobHours, 2);
            $job->total_earning = round($job->total_hours * $job->userService->price_per_hour, 2);
            $job->admin_fee = round(($job->total_earning * $adminFeePercentage) / 100, 2);
            $job->net_earning = round($job->total_earning - $job->admin_fee, 2);
        } else {
            $job->total_hours = 0;
            $job->total_earning = 0;
            $job->admin_fee = 0;
            $job->net_earning = 0;
        }
    }

    return view('users.dashboard', compact(
        'user',
        'calendarDays',
        'month',
        'year',
        'assignedSchedules',     // Passing assigned schedules to the view
        'ongoingSchedules',      // Passing ongoing schedules to the view
        'completedSchedules'     // Passing completed schedules to the view
    ));
}



    


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return response()->json(['success' => true]);
    }


    public function profile(Request $request)
    {
        $user = auth()->user();
        $services = UserService::where('user_id', $user->id)
        ->with(['service', 'subCategory']) // Correct relationship
        ->get();
        //dd($services);
        return view("users.profile", compact('user', 'services'));
    }


    public function payout(Request $request)
    {
        $title = "Payout Details";
        $back_url = route('payouts.index');
        $user = auth()->user();
    
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
