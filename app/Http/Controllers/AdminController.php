<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\JobSchedule;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $users = User::where("role_id", 2)->orderBy('id', 'DESC')->get();

        $jobs = JobSchedule::count();
        $customers = Customer::count();
        $services = Service::count();

        return view("pages.dashboard", compact("users", 'jobs', 'customers', 'services'));
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
}
