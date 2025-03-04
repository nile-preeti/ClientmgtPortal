<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceBreak;
use App\Models\Customer;
use App\Models\EventName;
use App\Models\EventType;
use App\Models\Holiday;
use App\Models\Service;
use App\Models\User;
use App\Models\UserChatCount;
use App\Models\UserService;
use App\Models\JobSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AjaxController extends Controller
{
    public function uploadImage(Request $request)
    {
        // dd($request->all());
        if ($request->has('file')) {
            $file = $request->file('file');
            $image = $file->getClientOriginalName();
            $file->move('public/uploads/images/', $image);
            return $image;
        }
        // return response()->json(['message' => "Something went wrong"]);
    }

    public function deleteImage(Request $request)
    {
        // dd($request->all());
        if (File::exists("public/uploads/images/$request->filename")) {
            // File::delete("uploads/products/$request->filename");
            return $request->filename;
        }
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'check_in_full_address' => 'required|string',
            'check_in_latitude' => 'required|string',
            'check_in_longitude' => 'required|string',
            'user_id' => "required"
        ]);

        $userId = $request->user_id;
        $today = Carbon::today();
        $todayDate = $today->toDateString();
        $dayOfWeek = $today->format('l'); // Get the full day name (e.g., "Saturday", "Sunday")

        // Check if today is a weekend (Saturday or Sunday)
        if (in_array($dayOfWeek, ['Saturday', 'Sunday'])) {
            return response()->json([
                'status' => 'error',
                'message' => "Check-in is not allowed on weekends.",
            ], 400);
        }

        // Check if today is a holiday
        $isHoliday = Holiday::where('date', $todayDate)->exists();
        if ($isHoliday) {
            return response()->json([
                'status' => 'error',
                'message' => "Check-in is not allowed on holidays.",
            ], 400);
        }

        // Check if the user has already checked in today
        $existingAttendance = Attendance::where('user_id', $userId)
            ->where('date', $todayDate)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'status' => 'error',
                'message' => "You have already checked in today.",
            ], 400);
        }

        // Create new attendance record
        $attendance = new Attendance();
        $attendance->date = $todayDate;
        $attendance->user_id = $userId;
        $attendance->check_in_full_address = $request->check_in_full_address;
        $attendance->check_in_latitude = $request->check_in_latitude;
        $attendance->check_in_longitude = $request->check_in_longitude;
        $attendance->check_in_time = Carbon::now()->format('H:i:s');
        $attendance->status = "Present";
        $attendance->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in recorded successfully.',
            'data' => [
                'date' => $attendance->date,
                'check_in_time' => date("H:i", strtotime($attendance->check_in_time)),
                'status' => $attendance->status
            ]
        ]);
    }
    /**
     * Update Check-out Record
     */
    public function updateAttendance(Request $request)
    {
        $request->validate([
            'check_out_full_address' => 'required|string',
            'check_out_latitude' => 'required|string',
            'check_out_longitude' => 'required|string',
            'user_id' => 'required|string',
        ]);
        $userId = $request->user_id;
        $today = Carbon::today()->toDateString();
        // Find today's attendance record
        $attendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();
        if (!$attendance) {
            return response()->json([
                'status' => 'error',
                'message' => "No check-in record found for today.",
            ], 400);
        }
        // If both check-in and check-out times are NULL, update status to "Absent"
        if (!$attendance->check_in_time && !$attendance->check_out_time) {
            $attendance->status = "Absent";
            $attendance->save();
            return response()->json([
                'status' => 'error',
                'message' => "No check-in and check-out recorded, marked as Absent.",
            ], 400);
        }
        // If check-in time is present but check-out time is missing, mark as "Absent"
        // if ($attendance->check_in_time && !$attendance->check_out_time) {
        //     $attendance->status = "Absent";
        //     $attendance->save();
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => "Check-in recorded but no check-out found, marked as Absent.",
        //     ], 400);
        // }
        // If both check-in and check-out times are present, calculate worked hours
        $checkInTime = Carbon::parse($attendance->check_in_time);
        $checkOutTime = Carbon::now();  // Use current time for check-out if not provided
        // Calculate worked hours
        $totalMinutes = $checkInTime->diffInMinutes($checkOutTime);  // Get the difference in minutes for more precision
        $totalHours = $totalMinutes / 60;  // Convert minutes to hours
        // Determine status based on hours worked
        if ($totalHours < 4.5) {
            $status = "Absent";  // Set status as "Absent" if worked hours are less than 4.5
        } elseif ($totalHours < 9) {
            $status = "Half-day";  // Set status as "Half-day" if worked hours are less than 9
        } else {
            $status = "Present";  // Otherwise, set status as "Present"
        }
        // Update check-out details (only if check-out time is provided)
        if ($request->check_out_full_address && $request->check_out_latitude && $request->check_out_longitude) {
            $attendance->check_out_full_address = $request->check_out_full_address;
            $attendance->check_out_latitude = $request->check_out_latitude;
            $attendance->check_out_longitude = $request->check_out_longitude;
            $attendance->check_out_time = $checkOutTime->format('H:i:s');
        }
        $attendance->status = $status;
        $attendance->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Attendance updated successfully.',
            'data' => [
                'check_in_time' => $checkInTime->format('H:i'),
                'check_out_time' => $checkOutTime->format('H:i'),
                'total_hours_worked' => $totalHours,
                'status' => $status
            ]
        ]);
    }
    /**
     * Update Check-out Record
     */
    function handleBreak(Request $request)
    {
        $user_id = $request->user_id;
        $today = now()->toDateString();
        $currentTime = now()->format('H:i:s');

        // Get user's attendance for today
        $attendance = Attendance::where('user_id', $user_id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['message' => 'No attendance record found for today.'], 400);
        }

        // Check if workday has started and not ended
        if (is_null($attendance->check_in_time)) {
            return response()->json(['message' => 'You must start your workday before taking a break.'], 400);
        }

        if (!is_null($attendance->check_out_time)) {
            return response()->json(['message' => 'You cannot take a break after ending your workday.'], 400);
        }

        // Check if there is an ongoing break (i.e., a break without an end_break)
        $ongoingBreak = AttendanceBreak::where('user_id', $user_id)
            ->where('date', $today)
            ->whereNull('end_break')
            ->first();

        if ($ongoingBreak) {
            // End the ongoing break
            if ($request->type == "start") {
                return response()->json(['message' => 'Break already started.', 'success' => false, 'end_break' => date("H:i", strtotime($currentTime))], 200);
            }
            $ongoingBreak->update(['end_break' => $currentTime]);

            return response()->json(['message' => 'Break ended successfully.', 'success' => true, 'end_break' => date("H:i", strtotime($currentTime))], 200);
        } else {
            // Ensure there are no unfinished breaks before starting a new one
            $unfinishedBreak = AttendanceBreak::where('user_id', $user_id)
                ->where('date', $today)
                ->whereNull('end_break')
                ->exists();

            if ($unfinishedBreak) {
                return response()->json(['message' => 'Complete the previous break before starting a new one.', 'success' => true], 400);
            }

            // Start a new break
            AttendanceBreak::create([
                'attendance_id' => $attendance->id,
                'start_break' => $currentTime,
                'end_break' => null, // Setting end_break as null since the break just started
                'date' => $today,
                'user_id' => $user_id,
            ]);

            return response()->json(['message' => 'Break started successfully.', 'success' => true, 'start_break' =>  date("H:i", strtotime($currentTime))], 200);
        }
    }

    public function fetchAttendance(Request $request)
    {
        if ($request->has("id")) {
            $id = $request->id;
            $user_id = Auth::user()->id;
            $user = User::find($user_id);

            if ($user) {
                $selectedMonth = $request->input('month', now()->format('m'));
                $selectedYear = $request->input('year', now()->format('Y'));

                $selectedDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
                $daysInMonth = $selectedDate->daysInMonth;
                $today = now()->format('Y-m-d');

                // Get holidays for the selected month
                $holidays = Holiday::whereYear('date', $selectedYear)
                    ->whereMonth('date', $selectedMonth)
                    ->pluck('date')
                    ->toArray();

                // Fetch user attendance for the selected month
                $attendances = Attendance::where("user_id", $user_id)
                    ->whereYear("date", $selectedYear)
                    ->whereMonth("date", $selectedMonth)
                    ->orderBy("date", "asc")
                    ->get()
                    ->keyBy('date');

                $records = [];

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = $selectedYear . '-' . str_pad($selectedMonth, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                    $dayOfWeek = date('N', strtotime($date));

                    // Default Status
                    $status = ["key" => "absent", "label" => "Absent"];

                    if (in_array($date, $holidays)) {
                        $status = ["key" => "holiday", "label" => "Holiday"];
                    } elseif ($date > $today) {
                        $status = ["key" => "na", "label" => "N/A"];
                    } elseif ($dayOfWeek == 6 || $dayOfWeek == 7) {
                        $status = ["key" => "weekly_off", "label" => "Weekly Off"];
                    } elseif (isset($attendances[$date])) {
                        $attendance = $attendances[$date];

                        if (empty($attendance->check_in_time)) {
                            $status = ["key" => "absent", "label" => "Absent"];
                        } else {
                            $checkInTime = strtotime($attendance->check_in_time);
                            $checkOutTime = $attendance->check_out_time ? strtotime($attendance->check_out_time) : null;

                            if ($date == $today) {
                                $status = ["key" => "present", "label" => "Present"];
                            } elseif ($date < $today && is_null($checkOutTime)) {
                                $status = ["key" => "absent", "label" => "Absent"];
                            } elseif (!is_null($checkOutTime)) {
                                $workedHours = ($checkOutTime - $checkInTime) / 3600; // Convert seconds to hours

                                if ($workedHours < 4.5) {
                                    $status = ["key" => "absent", "label" => "Absent"];
                                } elseif ($workedHours >= 4.5 && $workedHours < 9) {
                                    $status = ["key" => "half_day", "label" => "Half Day"];
                                } else {
                                    $status = ["key" => "present", "label" => "Present"];
                                }
                            } else {
                                $status = ["key" => "absent", "label" => "Absent"];
                            }
                        }

                        $status["check_in_time"] = $attendance->check_in_time ? date("H:i", strtotime($attendance->check_in_time)) : "N/A";
                        $status["check_out_time"] = $attendance->check_out_time ? date("H:i", strtotime($attendance->check_out_time)) : "N/A";
                        $status["check_in_address"] = !empty($attendance->check_in_full_address) ? $attendance->check_in_full_address : "N/A";
                        $status["check_out_address"] = !empty($attendance->check_out_full_address) ? $attendance->check_out_full_address : "N/A";
                    }

                    $records[] = [
                        "date" => $date,
                        "status" => $status,
                        'id' => $id,
                    ];
                }

                // **Pagination**
                $perPage = 15;
                $page = $request->input('page', 1);
                $offset = ($page - 1) * $perPage;
                $paginatedRecords = new LengthAwarePaginator(
                    array_slice($records, $offset, $perPage),
                    count($records),
                    $perPage,
                    $page
                );

                return response()->json([
                    'success' => true,
                    'records' => $paginatedRecords->items(),
                    'current_page' => $paginatedRecords->currentPage(),
                    'last_page' => $paginatedRecords->lastPage(),
                    'total' => $paginatedRecords->total(),
                ]);
            }
        }

        return response()->json(['success' => false, 'message' => 'User does not exist']);
    }



    public function getService(Request $request)
    {
        if ($request->has("id")) {
            $customer_services = UserService::where("user_id", $request->id)->pluck("service_id")->toArray();
            $services = Service::whereIn("id", $customer_services)->get();
            return response()->json(["success" => true, 'services' => $services]);
        }
        return response()->json(['success' => false]);
    }
    public function fetchAttendancetoday(Request $request)
    {
        if ($request->has("id")) {
            $id = $request->id;
            $user = User::find($id);
            if ($user) {
                $records = Attendance::where("user_id", $id)->orderBy("id", "desc")->get();
                foreach ($records as $item) {
                    $item->check_in_time = date("H:i", strtotime($item->check_in_time));
                    $item->check_out_time = date("H:i", strtotime($item->check_out_time));
                }
                $today = Attendance::where("user_id", $id)->whereDate("date", now())->orderBy("id", "desc")->first();
                if ($today) {
                    $today->check_in_time = !empty($today->check_in_time)
                        ? date("H:i", strtotime($today->check_in_time))
                        : 'N/A';

                    $today->check_out_time = !empty($today->check_out_time)
                        ? date("H:i", strtotime($today->check_out_time))
                        : 'N/A';
                }

                $breaks = AttendanceBreak::where("user_id", $id)->whereDate("date", now())->orderBy("id", "asc")->get();
                foreach ($breaks as $key => $break) {
                    if ($break) {
                        $break->break_start = !empty($break->start_break)
                            ? date("H:i", strtotime($break->start_break))
                            : false;

                        $break->break_end = !empty($break->end_break)
                            ? date("H:i", strtotime($break->end_break))
                            : false;
                    }
                }


                return response()->json(['success' => true, 'records' => $records, 'breaks' => $breaks, 'today' => $today]);
            }
        }
        return response()->json(['success' => false, 'message' => 'user does not exists']);
    }

    public function Employeedirectory(Request $request)
    {
        $perPage = 10; // Number of records per page
        $page = $request->input('page', 1);

        $employees = User::where('role_id', 2)
            ->where('status', 1)
            ->select('id', 'name', 'email', 'emp_id', 'designation', 'phone')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'employees' => $employees->items(),
            'current_page' => $employees->currentPage(),
            'last_page' => $employees->lastPage(),
            'total' => $employees->total(),
        ]);
    }

    public function getCustomer()
    {
        if (request()->has("id")) {
            $customer = Customer::find(request("id"));
            if ($customer) {
                return response()->json($customer);
            } else {
                return response()->json(['success' => false, 'message' => 'Custome does not exists']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Please provide id']);
        }
    }



    public function userServices(Request $request)
{
    $perPage = 10; // Number of records per page
    $page = $request->input('page', 1);
    $authUserId = Auth::id(); // Get the logged-in user ID
    $search = $request->input('search');
    $selectedDate = $request->input('date'); // Get selected date

    // Fetch job schedules where user_id matches the logged-in user
    $query = JobSchedule::where('user_id', $authUserId)
        ->with(['service:id,name', 'customer:id,name']) // Load service and customer details
        ->select('id', 'service_id', 'customer_id', 'start_time', 'end_time', 'description', 'start_date', 'end_date');

    // Apply search filter
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhereHas('service', function ($q) use ($search) {
                  $q->where('name', 'LIKE', "%{$search}%");
              })
              ->orWhereHas('customer', function ($q) use ($search) {
                  $q->where('name', 'LIKE', "%{$search}%");
              });
        });
    }

    // Apply date filter if a date is selected
    if (!empty($selectedDate)) {
        $query->where(function ($q) use ($selectedDate) {
            $q->whereDate('start_date', $selectedDate)
              ->orWhereDate('end_date', $selectedDate);
        });
    }

    // Paginate results
    $jobSchedules = $query->paginate($perPage, ['*'], 'page', $page);

    return response()->json([
        'success' => true,
        'job_schedules' => $jobSchedules->items(),
        'current_page' => $jobSchedules->currentPage(),
        'last_page' => $jobSchedules->lastPage(),
        'total' => $jobSchedules->total(),
    ]);
}



    


}
