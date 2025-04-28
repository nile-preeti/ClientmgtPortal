<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\JobSchedule;
use App\Models\Service;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;

class JobScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $job_schedules = JobSchedule::when(request()->filled("search"), function ($query) {
            $keyword = trim(request("search"));
            $user_ids = User::where("name", "LIKE", "%$keyword%")->orWhere("designation", "LIKE", "%$keyword%")->orWhere("email", "LIKE", "%$keyword%")->orWhere("phone", "LIKE", "%$keyword%")->pluck("id")->toArray();

            return $query->whereIn("user_id", $user_ids);
        })

            ->when(request()->filled("status"), function ($query) {
                return $query->where("status", request("status"));
            })

            ->orderBy("id", "desc")->paginate(config("contant.paginatePerPage"));

        $title = "Job Scheduling";


        return view("pages.job_schedules.index", compact("title", 'job_schedules', 'search',));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $title = "Schedule Job";
        $back_url = route('job_schedules.index');
        $users = User::where("role_id", 2)->orderBy("name", "asc")->get();
        $customers = Customer::orderBy("name", "asc")->get();
        $states=State::where("country_id",233)->get();
        $services=Service::all();
        return view("pages.job_schedules.create", compact('title','services','states', 'users', 'customers','back_url'));
    }
    public function edit(Request $request, $id)
    {
        $title = "Edit Job Schedule";
        $back_url = route('job_schedules.index');

        $job_schedule = JobSchedule::find($id);
        if (!$job_schedule) {
            return back()->with("error", 'Schedule does not exists');
        }
        $users = User::where("role_id", 2)->orderBy("name", "asc")->get();
        $customers = Customer::orderBy("name", "asc")->get();
        $states=State::where("country_id",233)->get();
        $services=Service::all();
        return view("pages.job_schedules.create", compact('job_schedule','states','services', 'users', 'customers', 'title','back_url'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'customer_id' => 'required| exists:customers,id',
            'service_id' => 'required',




        ]);
        //dd($request->all());
        $user = new JobSchedule();
        $user->user_id = $request->user_id;
        $user->customer_id = $request->customer_id;
        $user->service_id = $request->service_id;
        $user->sub_category_id = $request->sub_category_id;
        $user->description = $request->description;
        $user->status = $request->status;
        $user->start_time = $request->start_time;
        $user->end_time = $request->end_time;
        $user->location = $request->location;
        $user->start_date = $request->start_date;
        $user->end_date = $request->end_date;
        $user->save();

        return response()->json(['success' => true, 'message' => "Job Scheduled Successfully", 'redirect' => true,'route' => route('job_schedules.index')]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([]);

        $user =  JobSchedule::find($id);

        $user->user_id = $request->user_id;
        $user->customer_id = $request->customer_id;
        $user->service_id = $request->service_id;
        $user->sub_category_id = $request->sub_category_id;
        $user->description = $request->description;
        $user->status = $request->status;
        $user->start_time = $request->start_time;
        $user->end_time = $request->end_time;
        $user->location = $request->location;
        $user->start_date = $request->start_date;
        $user->end_date = $request->end_date;
        $user->save();


        return response()->json(['success' => true, 'message' => "Job Updated Successfully",'redirect' => true,'route' => route('job_schedules.index')]);
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
}
