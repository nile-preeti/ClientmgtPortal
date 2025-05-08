<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Exports\CustomerExport;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\AttendanceBreak;
use App\Models\Customer;
use App\Models\Holiday;
use App\Models\JobSchedule;
use App\Models\Service;
use App\Models\UserService;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Logo;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $users = User::where("role_id", 2)->orderBy('id', 'DESC')->get();
        $user_jobs = JobSchedule::count();
        $customers = Customer::count();
        $services = Service::count();

        // Fetch Top Services for the Services Chart
        $topServices = JobSchedule::select('service_id', DB::raw('COUNT(service_id) as service_count'))
            ->groupBy('service_id')
            ->orderByDesc('service_count')
            ->take(5)
            ->with('service')
            ->get();

        $allServices = Service::take(5)->get();
        $chartData = [];
        $addedServices = [];

        foreach ($topServices as $service) {
            $chartData[] = [
                'name' => $service->service->name ?? 'N/A',
                'y' => (int) $service->service_count,
            ];
            $addedServices[] = $service->service_id;
        }

        foreach ($allServices as $service) {
            if (count($chartData) >= 5) break;
            if (!in_array($service->id, $addedServices)) {
                $chartData[] = [
                    'name' => $service->name,
                    'y' => 0,
                ];
            }
        }

        // Admin Revenue Data with Year Filter
        $selectedYear = $request->input('year', now()->year);
        $monthlyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $start_date = Carbon::create($selectedYear, $month, 1)->startOfMonth();
            $end_date = Carbon::create($selectedYear, $month, 1)->endOfMonth();

            $totalEarnings = 0;
            $totalAdminEarnings = 0;
            $totalUserEarnings = 0;

            // Get job schedules within the current month
            $jobs = JobSchedule::where('status', 2)
                ->where(function ($query) use ($start_date, $end_date) {
                    $query->whereBetween('start_date', [$start_date, $end_date])
                        ->orWhereBetween('end_date', [$start_date, $end_date]);
                })
                ->get();

            foreach ($jobs as $job) {
                $user = User::find($job->user_id);
                if (!$user) continue;

                $userService = UserService::where('user_id', $job->user_id)
                    ->where('service_id', $job->service_id)
                    ->first();

                if (!$userService || !$userService->price_per_hour) continue;

                $serviceRate = $userService->price_per_hour;
                $adminFeePercent = $job->admin_fee_percent ?? $userService->admin_fee_percent ?? config('constant.admin_fee', 11);

                // Get attendances for this job in the current month
                $attendances = Attendance::where('job_id', $job->id)
                    ->whereBetween('date', [$start_date, $end_date])
                    ->whereNotNull('check_in_time')
                    ->whereNotNull('check_out_time')
                    ->with('attendanceBreaks')
                    ->get();

                foreach ($attendances as $attendance) {
                    $checkIn = Carbon::parse($attendance->check_in_time);
                    $checkOut = Carbon::parse($attendance->check_out_time);
                    $workedHours = $checkOut->diffInSeconds($checkIn) / 3600;

                    // Calculate total break hours
                    $breakHours = $attendance->attendanceBreaks->sum(function ($break) {
                        if (!$break->start_break || !$break->end_break) return 0;
                        return Carbon::parse($break->end_break)->diffInSeconds(Carbon::parse($break->start_break)) / 3600;
                    });

                    $actualHours = max(round($workedHours - $breakHours, 2), 0);
                    $earning = round($actualHours * $serviceRate, 2);
                    $adminEarning = round(($earning * $adminFeePercent) / 100, 2);
                    $userEarning = round($earning - $adminEarning, 2);

                    $totalEarnings += $earning;
                    $totalAdminEarnings += $adminEarning;
                    $totalUserEarnings += $userEarning;
                }
            }

            $monthlyData[] = [
                'month' => $start_date->format('F'),
                'total_earnings' => round($totalEarnings, 2),
                'admin_earnings' => round($totalAdminEarnings, 2),
                'user_earnings' => round($totalUserEarnings, 2),
            ];
        }

        return view("pages.dashboard", compact(
            "users",
            'user_jobs',
            'customers',
            'services',
            'chartData',
            "monthlyData",
            "selectedYear"
        ));
    }


    public function signin()
    {
        return view("pages.signin");
    }
    public function signin_post(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6',
        ], [
            'email.exists' => "This email is not registered with us."
        ]);

        try {
            // Attempt to authenticate the user
            if (Auth::guard("admin")->attempt($request->only(['email', 'password']))) {
                $user = User::where("email", $request->email)->first();

                Auth::guard("admin")->login($user);
                return response()->json(['message' => 'Logged In Successfully. ', 'redirect' => true, 'route' => route("dashboard"), 'status' => 200]);
            } else {
                return response()->json(['message' => 'Invalid credentials', 'status' => 201]);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'status' => 201]);
        }
    }
    public function profile()
    {
        $admin = auth()->guard("admin")->user();
        return view("pages.profile", compact("admin"));
    }

    public function profile_post(Request $request)
    {

        $request->validate(['name' => 'required', 'email' => 'required']);
        $admin = Admin::find(auth()->guard("admin")->user()->id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->image = $request->image;
        $admin->phone = $request->phone;
        $admin->save();


        return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
    }
    public function change_password(Request $request)
    {

        return view("pages.change_password");
    }
    public function change_password_post(Request $request)
    {

        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);
        $admin = User::find(auth()->user()->id);
        if (!Auth::guard("admin")->attempt(['email' => $admin->email, 'password' => $request->old_password])) {
            return response()->json(['success' => false, 'message' => 'Please enter correct old password']);
        }


        $admin->password = Hash::make($request->password);

        $admin->save();


        return response()->json(['success' => true, 'message' => 'Password updated successfully']);
    }
    public function logout()
    {
        Auth::guard("admin")->logout();
        return redirect(route("admin.login"));
    }

    // all functions related to orders listing and order details


    public function users()
    {
        $users = User::when(request()->filled("search"), function ($query) {
            $keyword = trim(request("search"));
            return $query->where("name", "LIKE", "%$keyword%")->orWhere("description", "LIKE", "%$keyword%");
        })
            ->where("role_id", 2)
            ->when(request()->filled("status"), function ($query) {
                return $query->where("status", request("status"));
            })

            ->orderBy("id", "desc")->paginate(config("contant.paginatePerPage"));

        $title = "User Management";

        return view("pages.users.index", compact("title", 'users'));
    }


    public function users_details($id)
    {
        $title = "User Management";
        $user = User::find($id);

        return view("pages.users.details", compact("title", 'user',));
    }
    public function userStatusUpdate($id)
    {
        $user = User::find($id);
        if ($user) {
            if ($user->status) {
                $user->status = 0;
            } else {

                $user->status = 1;
            }
            $user->save();
            return back()->with('success', "User Status Updated Successfully");
        }
        return back()->with('error', "User does not exists");
    }

    public function reports(Request $request)
    {
    
        $title = "Reports";
        $users =  $users = User::where("role_id", 2)->where('status',1)->count();
        $customer = Customer::where('status',1)->count();
        return view("pages.reports.index", compact("title",'users','customer'));
    }


    public function Employeereports(Request $request)
    {
        $search = $request->search;
        $month = $request->input("month", date("m"));
        $year = $request->input("year", date("Y"));
    
        $users = User::withCount([
            'jobSchedules as services_count' => function ($query) use ($month, $year) {
                $query->where('status', 2)
                      ->whereMonth('start_date', $month)
                      ->whereYear('start_date', $year);
            }
        ])
            ->when($request->filled("search"), function ($query) use ($search) {
                $keyword = trim($search);
                return $query->where(function ($q) use ($keyword) {
                    $q->where("name", "LIKE", "%$keyword%")
                      ->orWhere("designation", "LIKE", "%$keyword%")
                      ->orWhere("email", "LIKE", "%$keyword%")
                      ->orWhere("phone", "LIKE", "%$keyword%");
                });
            })
            ->where("role_id", 2)
            ->where("status", 1)
            ->when($request->filled("status"), function ($query) use ($request) {
                return $query->where("status", $request->status);
            });
    
        // Handle export
        if ($request->has("export")) {
            $users = $users->orderBy('name', 'asc')->get();
            foreach ($users as $user) {
                $user->working_hours = $this->getTotalWorkingHours($user->id, $month, $year);
            }
    
            return Excel::download(new ReportExport($users, $month, $year), 'employees_report_' . date("d-m-Y") . '.xlsx');
        }
    
        // Pagination
        $users = $users->orderBy('name', 'asc')->paginate(config("contant.paginatePerPage"));
    
        // Append working hours for each user
        foreach ($users as $user) {
            $user->working_hours = $this->getTotalWorkingHours($user->id, $month, $year);
        }
    
        $title = "Employee Reports";
        $back_url = route('reports');
    
        return view("pages.reports.reports_by_employee", compact("title", "users", "search",'back_url'));
    }


    public function monthlyreports(Request $request)
    {
        $search = $request->search;
        $month = $request->input("month", date("m"));
        $year = $request->input("year", date("Y"));
    
        $users = User::withCount([
            'jobSchedules as services_count' => function ($query) use ($month, $year) {
                $query->where('status', 2)
                      ->whereMonth('start_date', $month)
                      ->whereYear('start_date', $year);
            }
        ])
            ->when($request->filled("search"), function ($query) use ($search) {
                $keyword = trim($search);
                return $query->where(function ($q) use ($keyword) {
                    $q->where("name", "LIKE", "%$keyword%")
                      ->orWhere("designation", "LIKE", "%$keyword%")
                      ->orWhere("email", "LIKE", "%$keyword%")
                      ->orWhere("phone", "LIKE", "%$keyword%");
                });
            })
            ->where("role_id", 2)
            ->where("status", 1)
            ->when($request->filled("status"), function ($query) use ($request) {
                return $query->where("status", $request->status);
            });
    
        // Handle export
        if ($request->has("export")) {
            $users = $users->orderBy('name', 'asc')->get();
            foreach ($users as $user) {
                $user->working_hours = $this->getTotalWorkingHours($user->id, $month, $year);
            }
    
            return Excel::download(new ReportExport($users, $month, $year), 'employees_report_' . date("d-m-Y") . '.xlsx');
        }
    
        // Pagination
        $users = $users->orderBy('name', 'asc')->paginate(config("contant.paginatePerPage"));
    
        // Append working hours for each user
        foreach ($users as $user) {
            $user->working_hours = $this->getTotalWorkingHours($user->id, $month, $year);
        }
    
        $title = "Employee Reports";
        $back_url = route('reports');
    
        return view("pages.reports.reports_by_employee", compact("title", "users", "search",'back_url'));
    }



    


    public function Customerreports(Request $request)
    {
        $search = $request->search;
        $month = $request->input("month", date("m"));
        $year = $request->input("year", date("Y"));

        $users = Customer::withCount([
            'jobSchedules as services_count' => function ($query) use ($month, $year) {
                $query->where('status', 2)
                      ->whereMonth('start_date', $month)
                      ->whereYear('start_date', $year);
            }
        ])
            ->when($request->filled("search"), function ($query) use ($search) {
                $keyword = trim($search);
                return $query->where(function ($q) use ($keyword) {
                    $q->where("name", "LIKE", "%$keyword%")
                    ->orWhere("email", "LIKE", "%$keyword%")
                    ->orWhere("phone", "LIKE", "%$keyword%");
                });
            })
            ->where("status", 1)
            ->when($request->filled("status"), function ($query) use ($request) {
                return $query->where("status", $request->status);
            });

        // Handle export
        if ($request->has("export")) {
            $users = $users->orderBy('name', 'asc')->get();
            foreach ($users as $customer) {
                $customer->working_hours = $this->getTotalWorkingHoursCustomer($customer->id, $month, $year);
            }

            return Excel::download(new CustomerExport($users, $month, $year), 'customers_report_' . date("d-m-Y") . '.xlsx');
        }

        // Pagination
        $users = $users->orderBy('name', 'asc')->paginate(config("contant.paginatePerPage"));

        // Append working hours for each customer
        foreach ($users as $customer) {
            $customer->working_hours = $this->getTotalWorkingHoursCustomer($customer->id, $month, $year);
        }
        // dd($users);
        $title = "Customer Reports";
        $back_url = route('reports');

        return view("pages.reports.reports_by_customer", compact("title", "users", "search", 'back_url'));
    }


    function getTotalWorkingHours($userId, $month, $year)
    {
        // Step 1: Get job IDs with status = 2 (completed)
        $jobIdsWithStatus2 = JobSchedule::where('status', 2)->where('user_id',$userId)->pluck('id')->toArray();

        // Step 2: Fetch attendance records with breaks using eager loading
        $attendanceRecords = Attendance::with('attendanceBreaks') // 'breaks' relationship defined in model
            ->where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereIn('job_id', $jobIdsWithStatus2)
            ->whereNotNull('job_id')
            ->get()
            ->keyBy('id');

        // Step 3: Loop and calculate working hours
        $totalWorkingHours = 0;

        foreach ($attendanceRecords as $record) {
            $checkInTime = !empty($record->check_in_time) ? strtotime($record->check_in_time) : null;
            $checkOutTime = !empty($record->check_out_time) ? strtotime($record->check_out_time) : null;

            if (!is_null($checkInTime) && !is_null($checkOutTime)) {
                $workedHours = ($checkOutTime - $checkInTime) / 3600; // in hours

                // Calculate total break time
                $totalBreakSeconds = 0;
                foreach ($record->attendanceBreaks as $break) {
                    if (!empty($break->start_break) && !empty($break->end_break)) {
                        $totalBreakSeconds += strtotime($break->end_break) - strtotime($break->start_break);
                    }
                }

                // Subtract break time from worked time
                $netWorkedHours = $workedHours - ($totalBreakSeconds / 3600);
                $totalWorkingHours += max($netWorkedHours, 0); // avoid negative values
            }
        }

        // Return total working hours, rounded to 2 decimal places
        return number_format($totalWorkingHours, 2);
    }




    function getTotalWorkingHoursCustomer($userId, $month, $year)
    {
        // Step 1: Get job IDs with status = 2 (completed)
        $jobIdsWithStatus2 = JobSchedule::where('status', 2)->where('customer_id',$userId)->pluck('id')->toArray();

        // Step 2: Fetch attendance records with breaks using eager loading
        $attendanceRecords = Attendance::with('attendanceBreaks') // 'breaks' relationship defined in model
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereIn('job_id', $jobIdsWithStatus2)
            ->whereNotNull('job_id')
            ->get()
            ->keyBy('id');

        // Step 3: Loop and calculate working hours
        $totalWorkingHours = 0;

        foreach ($attendanceRecords as $record) {
            $checkInTime = !empty($record->check_in_time) ? strtotime($record->check_in_time) : null;
            $checkOutTime = !empty($record->check_out_time) ? strtotime($record->check_out_time) : null;

            if (!is_null($checkInTime) && !is_null($checkOutTime)) {
                $workedHours = ($checkOutTime - $checkInTime) / 3600; // in hours

                // Calculate total break time
                $totalBreakSeconds = 0;
                foreach ($record->attendanceBreaks as $break) {
                    if (!empty($break->start_break) && !empty($break->end_break)) {
                        $totalBreakSeconds += strtotime($break->end_break) - strtotime($break->start_break);
                    }
                }

                // Subtract break time from worked time
                $netWorkedHours = $workedHours - ($totalBreakSeconds / 3600);
                $totalWorkingHours += max($netWorkedHours, 0); // avoid negative values
            }
        }

        // Return total working hours, rounded to 2 decimal places
        return number_format($totalWorkingHours, 2);
    }



    public function getServiceDetails(Request $request)
    {
        $userId = $request->user_id;
        $month = $request->input("month", date("m"));
       // dd($month);
        $year = $request->input("year", date("Y"));

        // Fetch services with associated service and subCategory
        $services = JobSchedule::with(['service', 'subCategory','customer'])
            ->where('user_id', $userId)
            ->where('status', 2)
            ->orderby('id', 'desc') // assuming 2 is a valid status
            ->get();

        // Loop through services and calculate working hours for each service
        foreach ($services as $s) {
            $s->working_hours = $this->getTotalWorkingHoursPerService($userId, $s->id, $month, $year); // Pass job_id for each service
        }

        // Build raw HTML string
        if ($services->isEmpty()) {
            $html = '<p class="text-muted">No service data available.</p>';
        } else {
            $html = '<table class="table table-bordered">';
            $html .= '<thead><tr><th>Service Name</th><th>Customer Name</th><th>Sub Category</th><th>Working Hours</th></tr></thead><tbody>';

            foreach ($services as $service) {
                $html .= '<tr>';
                $html .= '<td>' . ($service->service->name ?? 'N/A') . '</td>';
                $html .= '<td>' . ($service->customer->name ?? 'N/A') . '</td>';
                $html .= '<td>' . ($service->subCategory->sub_category ?? 'N/A') . '</td>';
                $html .= '<td>' . ($service->working_hours ? $service->working_hours . ' hrs' : 'N/A') . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody></table>';
        }

        return response()->json(['html' => $html]);
    }

    public function getTotalWorkingHoursPerService($userId, $jobId, $month, $year)
    {
        // Get attendance records for the specific job ID
        $attendanceRecords = Attendance::with('attendanceBreaks')
            ->where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('job_id', $jobId) // Now using job_id to filter attendance records
            ->get();
        //dd($attendanceRecords);
        $totalWorkingHours = 0;

        // Calculate working hours for each attendance record
        foreach ($attendanceRecords as $record) {
            if ($record->check_in_time && $record->check_out_time) {
                $checkIn = strtotime($record->check_in_time);
                $checkOut = strtotime($record->check_out_time);

                $workedSeconds = $checkOut - $checkIn;
                $breakSeconds = 0;

                foreach ($record->attendanceBreaks as $break) {
                    if ($break->start_break && $break->end_break) {
                        $breakSeconds += strtotime($break->end_break) - strtotime($break->start_break);
                    }
                }

                $netSeconds = max(0, $workedSeconds - $breakSeconds);
                $totalWorkingHours += $netSeconds / 3600; // Convert to hours
               
            }
        }

        return number_format($totalWorkingHours, 2); // Round to 2 decimal places
    }




    public function getWeeklyWorkingHours($userId, Request $request)
    {
        $month = $request->get('month');
        $year = $request->get('year');

        // Get only job_ids with status = 2
        $jobIds = JobSchedule::where('status', 2)->where('user_id',$userId)->pluck('id')->toArray();

        // Fetch attendance with breaks, only for matching jobs
        $records = Attendance::with('attendanceBreaks')
            ->where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereIn('job_id', $jobIds)
            ->get();

        // Prepare weekly data
        $weeklyHours = [];

        foreach ($records as $record) {
            $date = Carbon::parse($record->date);
            $weekIndex = $date->copy()->startOfWeek(Carbon::SUNDAY)->diffInWeeks($date->copy()->startOfMonth());

            $checkIn = strtotime($record->check_in_time);
            $checkOut = strtotime($record->check_out_time);

            if ($checkIn && $checkOut) {
                $workedHours = ($checkOut - $checkIn) / 3600;

                $breakSeconds = 0;
                foreach ($record->attendanceBreaks as $break) {
                    if ($break->start_break && $break->end_break) {
                        $breakSeconds += strtotime($break->end_break) - strtotime($break->start_break);
                    }
                }

                $netHours = max($workedHours - ($breakSeconds / 3600), 0);

                if (!isset($weeklyHours[$weekIndex])) {
                    $weeklyHours[$weekIndex] = 0;
                }

                $weeklyHours[$weekIndex] += $netHours;
            }
        }

        // Handle empty weekly hours case
        if (empty($weeklyHours)) {
            return response()->json([0.00, 0.00, 0.00, 0.00, 0.00]);
        }

        // Format to 2 decimals
        $maxWeek = max(array_keys($weeklyHours));
        $weeklyHoursFormatted = [];
        
        for ($i = 0; $i <= $maxWeek; $i++) {
            $weeklyHoursFormatted[$i] = isset($weeklyHours[$i]) ? round($weeklyHours[$i], 2) : 0.00;
        }

        // Ensure we always return 5 weeks (0-4)
        while (count($weeklyHoursFormatted) < 5) {
            $weeklyHoursFormatted[] = 0.00;
        }

        return response()->json(array_slice($weeklyHoursFormatted, 0, 5));
    }




    public function getServiceDetailsCustomer(Request $request)
    {
        $customerId = $request->user_id; // still coming from JS as user_id
        $month = $request->input("month", date("m"));
        $year = $request->input("year", date("Y"));

        // Fetch services with associated service and subCategory based on customer_id
        $services = JobSchedule::with(['service', 'subCategory', 'user',]) // added 'user'
                    ->where('customer_id', $customerId)
                    ->where('status', 2)
                    ->orderBy('id', 'desc')
                    ->get();

        // Loop through services and calculate working hours
        foreach ($services as $s) {
            $s->working_hours = $this->getTotalWorkingHoursPerService($s->user_id, $s->id, $month, $year);
        }

        // Build HTML response
        if ($services->isEmpty()) {
            $html = '<p class="text-muted">No service data available.</p>';
        } else {
            $html = '<table class="table table-bordered">';
            $html .= '<thead><tr><th>Service Name</th><th>Employee Name</th><th>Sub Category</th><th>Working Hours</th></tr></thead><tbody>';

            foreach ($services as $service) {
                $html .= '<tr>';
                $html .= '<td>' . ($service->service->name ?? 'N/A') . '</td>';
                $html .= '<td>' . ($service->user->name ?? 'N/A') . '</td>';
                $html .= '<td>' . ($service->subCategory->sub_category ?? 'N/A') . '</td>';
                $html .= '<td>' . ($service->working_hours ? $service->working_hours . ' hrs' : 'N/A') . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody></table>';
        }

        return response()->json(['html' => $html]);
    }




    

    public function settings()
    {
        $title = 'Settings';
        return view("pages.settings", compact('title'));
    }


    public function addLogo()
    {
        $title = 'Add Logo';
        return view("pages.add-logo", compact('title'));
    }

    public function settings_store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'key' => 'required',
            'value' => 'required',

        ]);
        if ($validate->fails()) {

            return response()->json(['success' => false, ',message' => $validate->errors()->first()]);
        }

        $constantsFile = config_path('constant.php');

        // Get the current contents of the constants file
        $constants = include($constantsFile);

        // Update the specified key with the new value
        $constants[$request->key] = $request->value;

        // Export the updated constants array to PHP code
        $constantsContent = '<?php return ' . var_export($constants, true) . ';';

        // Write the updated constants back to the file
        file_put_contents($constantsFile, $constantsContent);




        return response()->json(['success' => true, ',message' => 'Settings updated successfully']);
    }


    public function logo_store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if there is an existing logo
        $existingLogo = Logo::first();
        if ($existingLogo) {
            $existingFilePath = public_path('uploads/logo/' . $existingLogo->name);

            // Delete the existing file if it exists
            if (file_exists($existingFilePath)) {
                unlink($existingFilePath);
            }

            // Delete the existing record from the database
            $existingLogo->delete();
        }

        // Store new file in "uploads/logo/" directory
        $file = $request->file('file');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/logo'), $fileName);

        // Save new file name in the "logo" table
        $logo = new Logo();
        $logo->name = $fileName;
        $logo->save();

        return response()->json([
            'file_path' => asset("uploads/logo/$fileName"),
            'message' => 'Logo uploaded and saved successfully!',
        ]);
    }


    public function logo_delete()
    {
        $logo = Logo::first();
        if ($logo) {
            $filePath = public_path('uploads/logo/' . $logo->name);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $logo->delete();
        }

        return response()->json(['success' => true, 'message' => 'Logo deleted successfully!']);
    }




    public function payouts(Request $request) {}
    public function payout_details(Request $request, $id) {}
}
