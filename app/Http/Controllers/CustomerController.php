<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\JobSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->search;
        $customers = Customer::when(request()->filled("search"), function ($query) {
            $keyword = trim(request("search"));
            return $query->where("name", "LIKE", "%$keyword%")->orWhere("full_address", "LIKE", "%$keyword%")->orWhere("email", "LIKE", "%$keyword%")->orWhere("phone", "LIKE", "%$keyword%");
        })

            ->when(request()->filled("status"), function ($query) {
                return $query->where("status", request("status"));
            })

            ->orderBy("id", "desc")->paginate(config("contant.paginatePerPage"));

        $title = "Customer Management";
        $states = DB::table('states')->where("country_code", "US")->orderBy("name")->get();
        return view("pages.customers.index", compact("title", 'customers', 'search', 'states'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required| unique:customers,email',

            'full_address' => 'required',
            'phone' => 'required',


        ]);

        $user = new Customer();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = $request->image;
        $user->phone = $request->phone;
        $user->full_address = $request->full_address;
        $user->city = $request->city;
        $user->state_id = $request->state_id;
        $user->zipcode = $request->zipcode;
        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => true, 'message' => "Customer Created Successfully"]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|exists:customers,email',
            'full_address' => 'required',
            'phone' => 'required',


        ]);

        $user =  Customer::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = $request->image;
        $user->phone = $request->phone;
        $user->full_address = $request->full_address;
        $user->city = $request->city;
        $user->state_id = $request->state_id;
        $user->zipcode = $request->zipcode;
        $user->status = $request->status;
        if ($request->has("password")) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return response()->json(['success' => true, 'message' => "Customer Updated Successfully"]);
    }
    public function show($id)
    {
        $id =  decrypt($id);

        $customer = Customer::find($id);
        $job_schedules = JobSchedule::where("customer_id", $id)->get();
        $title = "Customer Details";
        $back_url = route('customers.index');
        return view("pages.customers.details", compact("customer", 'job_schedules', 'title','back_url'));
    }
    public function  destroy($id)
    {
        $user = Customer::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => true, 'message' => "Customer deleted successfully"]);
        }
        return response()->json(['success' => false, 'message' => "Customer does not exists"]);
    }
}
