<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
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
        $adminFeePercent = config('constant.admin_fee', 11);
        $selectedYear = $request->input('year', now()->year);
        $monthlyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $start_date = Carbon::create($selectedYear, $month, 1)->startOfMonth();
            $end_date = Carbon::create($selectedYear, $month, 1)->endOfMonth();

            $totalEarnings = 0;
            $totalAdminEarnings = 0;
            $totalUserEarnings = 0;

            $jobs = JobSchedule::whereBetween('start_date', [$start_date, $end_date])
                ->where('status', 2)
                ->get();

            foreach ($jobs as $job) {
                $user = User::find($job->user_id);
                if (!$user) continue;

                $serviceRate = UserService::where('user_id', $job->user_id)
                    ->where('service_id', $job->service_id)
                    ->value('price_per_hour');

                if (!$serviceRate) continue;

                $jobStart = Carbon::parse($job->start_date . ' ' . $job->start_time);
                $jobEnd = Carbon::parse($job->end_date . ' ' . $job->end_time);
                $totalHoursWorked = $jobStart->diffInSeconds($jobEnd) / 3600;

                $breakHours = AttendanceBreak::where('user_id', $job->user_id)
                    ->whereBetween('start_break', [$jobStart, $jobEnd])
                    ->whereBetween('end_break', [$jobStart, $jobEnd])
                    ->sum(DB::raw('TIMESTAMPDIFF(SECOND, start_break, end_break)')) / 3600;

                $actualWorkedHours = max(round($totalHoursWorked - $breakHours, 2), 0);

                $monthlyEarnings = round($actualWorkedHours * $serviceRate, 2);
                $adminEarnings = round(($monthlyEarnings * $adminFeePercent) / 100, 2);
                $userEarnings = round($monthlyEarnings - $adminEarnings, 2);

                $totalEarnings += $monthlyEarnings;
                $totalAdminEarnings += $adminEarnings;
                $totalUserEarnings += $userEarnings;
            }

            $monthlyData[] = [
                'month' => $start_date->format('F'),
                'total_earnings' => $totalEarnings,
                'admin_earnings' => $totalAdminEarnings,
                'user_earnings' => $totalUserEarnings,
            ];
        }

        return view("pages.dashboard", compact("users", 'user_jobs', 'customers', 'services', 'chartData', "monthlyData", "selectedYear"));
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
        $search = $request->search;
        $users = User::when(request()->filled("search"), function ($query) {
            $keyword = trim(request("search"));
            return $query->where("name", "LIKE", "%$keyword%")->orWhere("designation", "LIKE", "%$keyword%")->orWhere("email", "LIKE", "%$keyword%")->orWhere("phone", "LIKE", "%$keyword%");
        })
            ->where("role_id", 2)
            ->when(request()->filled("status"), function ($query) {
                return $query->where("status", request("status"));
            });

        if (request()->has("export")) {

            $users =  $users->get();
            foreach ($users as $user) {
                $month = request("month", date("m"));
                $year = request("year", date("Y"));
                $user->working_hours = $this->getTotalWorkingHours($user->id, $month, $year);
            }
            return Excel::download(new ReportExport($users),  'employees_' . date("d-m-Y", time()) . '.xlsx');
        }
        $users =      $users->orderBy("id", "desc")->paginate(config("contant.paginatePerPage"));

        foreach ($users as $user) {
            $month = request("month", date("m"));
            $year = request("year", date("Y"));
            $user->working_hours = $this->getTotalWorkingHours($user->id, $month, $year);
        }
        $title = "Reports ";

        return view("pages.reports.index", compact("title", 'users', 'search'));
    }
    function getTotalWorkingHours($userId, $month, $year)
    {
        $attendanceRecords = Attendance::where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy('date'); // Store by date for quick lookup

        $holidays = Holiday::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->pluck('date')
            ->toArray();

        $totalWorkingHours = 0;
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');

            if (isset($attendanceRecords[$formattedDate])) {
                $record = $attendanceRecords[$formattedDate];

                $checkInTime = !empty($record->check_in_time) ? strtotime($record->check_in_time) : null;
                $checkOutTime = !empty($record->check_out_time) ? strtotime($record->check_out_time) : null;

                if (!is_null($checkInTime) && !is_null($checkOutTime)) {
                    $workedHours = ($checkOutTime - $checkInTime) / 3600; // Convert to hours

                    // Fetch total break time for the user on the given date
                    $totalBreakSeconds = AttendanceBreak::where('user_id', $record->user_id)
                        ->where('date', $record->date)
                        ->whereNotNull('start_break')
                        ->whereNotNull('end_break')
                        ->get()
                        ->sum(fn($break) => strtotime($break->end_break) - strtotime($break->start_break));

                    // Subtract break time from worked hours
                    $netWorkedHours = $workedHours - ($totalBreakSeconds / 3600);

                    // Ensure non-negative working hours
                    $totalWorkingHours += max($netWorkedHours, 0);
                }
            }
        }

        return number_format($totalWorkingHours, 1); // Return formatted total hours
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
