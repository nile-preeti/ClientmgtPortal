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

        $attendances = Attendance::whereBetween('check_in_time', [$start_date, $end_date])
            ->whereNotNull('check_out_time')
            ->get();

        $payouts = $attendances->groupBy('user_id')->map(function ($records, $user_id) use ($start_date, $end_date) {
            $total_earnings = 0;

            foreach ($records as $attendance) {
                $job = JobSchedule::where('user_id', $user_id)
                    ->whereDate('start_date', '<=', $attendance->check_in_time)
                    ->whereDate('end_date', '>=', $attendance->check_out_time)
                    ->first();

                if (!$job) continue;

                $serviceRate = UserService::where('user_id', $user_id)
                    ->where('service_id', $job->service_id)
                    ->value('price_per_hour');

                if (!$serviceRate) continue;

                $hoursWorked = Carbon::parse($attendance->check_in_time)->diffInHours(Carbon::parse($attendance->check_out_time));
                $total_earnings += $hoursWorked * $serviceRate;
            }

            return [
                'user_id' => $user_id,
                'total_earnings' => $total_earnings,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => 'Pending'
            ];
        });

        foreach ($payouts as $payout) {
            Payout::updateOrCreate(
                ['user_id' => $payout['user_id'], 'start_date' => $payout['start_date'], 'end_date' => $payout['end_date']],
                ['total_amount' => $payout['total_earnings'], 'status' => 'Pending']
            );
        }
    }

    // Show User Payout Details
    public function show($id)
    {
        $payouts = Payout::where("user_id", $id)->get();

        foreach ($payouts as $payout) {
            $payout->details = Attendance::whereBetween('check_in_time', [$payout->start_date, $payout->end_date])
                ->where("user_id", $id)
                ->get();
        }

        $user = User::find($id);
        return view("admin.payouts.details", compact("payouts", 'user'));
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
