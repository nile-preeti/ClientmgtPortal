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
use DateTime;

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
            'user_id' => "required",
            'job_id' => "required"
        ]);

        $userId = $request->user_id;
        $job_id = $request->job_id;
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
        ->where('job_id',$job_id)
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
        $attendance->job_id = $request->job_id;
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
            'job_id' => 'required|',
        ]);
        $userId = $request->user_id;
        $today = Carbon::today()->toDateString();
        // Find today's attendance record
        $attendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->where('job_id',$request->job_id)
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
        $job_id = $request->job_id;
        $today = now()->toDateString();
        $currentTime = now()->format('H:i:s');

        // Get user's attendance for today
        $attendance = Attendance::where('user_id', $user_id)
            ->where('date', $today)
            ->where('job_id',$job_id)
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
                'job_id'=>$job_id,
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
                $today = now()->format('Y-m-d');
    
                // Fetch only present attendance records with pagination
                $attendances = Attendance::where("user_id", $user_id)
                    ->whereYear("date", $selectedYear)
                    ->whereMonth("date", $selectedMonth)
                    ->whereNotNull("check_in_time") // Ensure check-in exists
                    ->whereNotNull("check_out_time") // Optional: Ensure full presence
                    ->whereNotNull("job_id")
                    ->orderBy("date", "asc")
                    ->with(['attendanceBreaks', 'jobSchedule.service'])
                    ->paginate(10); // Adjust pagination size here (10 per page)
    
                $records = [];
    
                foreach ($attendances as $attendance) {
                    $checkInTime = $attendance->check_in_time ? Carbon::parse($attendance->check_in_time) : null;
                    $checkOutTime = $attendance->check_out_time ? Carbon::parse($attendance->check_out_time) : null;
    
                    $workedHours = $checkInTime && $checkOutTime
                        ? $checkInTime->diff($checkOutTime)->format('%h hours %i minutes')
                        : "N/A";
    
                    // Calculate break time
                    $totalBreakSeconds = 0;
                    foreach ($attendance->attendanceBreaks as $break) {
                        if ($break->start_break && $break->end_break) {
                            $start = Carbon::parse($break->start_break);
                            $end = Carbon::parse($break->end_break);
                            $totalBreakSeconds += $start->diffInSeconds($end);
                        }
                    }
    
                    $breakTime = $totalBreakSeconds > 0 ? gmdate("H:i", $totalBreakSeconds) : "00:00";
    
                    $jobName = $attendance->jobSchedule && $attendance->jobSchedule->service
                        ? $attendance->jobSchedule->service->name
                        : "N/A";
    
                    $records[] = [
                        "id" => $attendance->id,
                        "date" => $attendance->date,
                        "status" => [
                            "key" => "present",
                            "label" => "Present",
                            "check_in_time" => $checkInTime ? $checkInTime->format("H:i") : "N/A",
                            "check_out_time" => $checkOutTime ? $checkOutTime->format("H:i") : "N/A",
                            "check_in_address" => $attendance->check_in_full_address ?? "N/A",
                            "check_out_address" => $attendance->check_out_full_address ?? "N/A",
                            "working_hours" => $workedHours,
                            "break_time" => $breakTime,
                        ],
                        "job_name" => $jobName,
                    ];
                }
    
                return response()->json([
                    'success' => true,
                    'records' => $records,
                    'total' => $attendances->total(), // Return the total number of records for pagination
                    'current_page' => $attendances->currentPage(), // Current page number
                    'last_page' => $attendances->lastPage(), // Total number of pages
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
            $job_id = $request->service_id;
            $user = User::find($id);
            if ($user) {
                $records = Attendance::where("user_id", $id)->where('job_id',$job_id)->orderBy("id", "desc")->get();
                foreach ($records as $item) {
                    $item->check_in_time = date("H:i", strtotime($item->check_in_time));
                    $item->check_out_time = date("H:i", strtotime($item->check_out_time));
                }
                $today = Attendance::where("user_id", $id)->where('job_id',$job_id)->whereDate("date", now())->orderBy("id", "desc")->first();
                if ($today) {
                    $today->check_in_time = !empty($today->check_in_time)
                        ? date("H:i", strtotime($today->check_in_time))
                        : 'N/A';

                    $today->check_out_time = !empty($today->check_out_time)
                        ? date("H:i", strtotime($today->check_out_time))
                        : 'N/A';
                }

                $breaks = AttendanceBreak::where("user_id", $id)->where('job_id',$job_id)->whereDate("date", now())->orderBy("id", "asc")->get();
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
        $perPage = 10;
        $page = $request->input('page', 1);
        $authUserId = Auth::id();
        $search = $request->input('search');
        $adminFeePercent = 11;
        $today = now()->format('Y-m-d');
        
        $query = JobSchedule::with([
            'service:id,name',
            'subCategory:id,category_id,sub_category',
            'customer:id,name',
            'userService:id,service_id,price_per_hour',
            'attendance' => function($q) use ($authUserId) {
                $q->where('user_id', $authUserId)
                    ->with(['attendanceBreaks' => function($q) {
                        $q->orderBy('created_at', 'desc'); // Get most recent breaks first
                    }]);
            }
        ])
        ->where('user_id', $authUserId)
        ->select('id', 'service_id', 'customer_id', 'start_time', 'end_time', 'description', 'start_date', 'end_date', 'status', 'sub_category_id', 'location')
        ->orderby('id', 'desc');
        
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
        
        $jobSchedules = $query->paginate($perPage, ['*'], 'page', $page);
        
        foreach ($jobSchedules as $job) {
            $job->total_hours = 0;
            $job->total_earning = 0;
            $job->admin_fee = 0;
            $job->net_earning = 0;
            $job->is_on_break = false;
            $job->is_checked_in = false;
    
            if ($job->attendance && $job->attendance->isNotEmpty()) {
                $totalJobHours = 0;
                
                foreach ($job->attendance as $attendance) {
                    // Check if currently checked in (has check_in_time but no check_out_time)
                    if ($attendance->check_in_time && !$attendance->check_out_time) {
                        $job->is_checked_in = true;
                        
                        // Check for active break (most recent break without end_break)
                        foreach ($attendance->attendanceBreaks as $break) {
                            if ($break->start_break && !$break->end_break) {
                                $job->is_on_break = true;
                                break;
                            }
                        }
                    }
    
                    if ($attendance->check_in_time && $attendance->check_out_time) {
                        try {
                            $checkIn = Carbon::parse($attendance->check_in_time);
                            $checkOut = Carbon::parse($attendance->check_out_time);
    
                            $totalWorkedSeconds = $checkOut->diffInSeconds($checkIn);
    
                            $breakSeconds = 0;
                            foreach ($attendance->attendanceBreaks as $break) {
                                if ($break->start_break && $break->end_break) {
                                    $breakStart = Carbon::parse($break->start_break);
                                    $breakEnd = Carbon::parse($break->end_break);
                                    
                                    // Only count breaks that happened between check-in and check-out
                                    if ($breakStart->between($checkIn, $checkOut) && $breakEnd->between($checkIn, $checkOut)) {
                                        $breakSeconds += $breakEnd->diffInSeconds($breakStart);
                                    }
                                }
                            }
    
                            $netSeconds = max(0, $totalWorkedSeconds - $breakSeconds);
                            $hours = $netSeconds / 3600;
                            $totalJobHours += $hours;
                        } catch (\Exception $e) {
                            // Log error if needed
                        }
                    }
                }
    
                if ($job->userService) {
                    $job->total_hours = round($totalJobHours, 2);
                    $job->total_earning = round($job->total_hours * $job->userService->price_per_hour, 2);
                    $job->admin_fee = round(($job->total_earning * $adminFeePercent) / 100, 2);
                    $job->net_earning = round($job->total_earning - $job->admin_fee, 2);
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'job_schedules' => $jobSchedules->items(),
            'current_page' => $jobSchedules->currentPage(),
            'last_page' => $jobSchedules->lastPage(),
            'total' => $jobSchedules->total(),
        ]);
    }


    

    








    public function fetchBreakDetails(Request $request)
    {
        $attendance = Attendance::with('attendanceBreaks')->find($request->id);

        if (!$attendance) {
            return response()->json(["success" => false, "message" => "Attendance not found"]);
        }

        $breaks = $attendance->attendanceBreaks->map(function ($break) {
            return [
                "start_break" => $break->start_break ? Carbon::parse($break->start_break)->format('H:i') : "N/A",
                "end_break" => $break->end_break ? Carbon::parse($break->end_break)->format('H:i') : "N/A"
            ];
        });

        return response()->json(["success" => true, "breaks" => $breaks]);
    }


    public function markComplete(Request $request)
    {
        $job = JobSchedule::where('id', $request->job_id)->first();
        if ($job) {
            $job->status = 2; // Completed
            $job->completed_date = now();
            $job->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
