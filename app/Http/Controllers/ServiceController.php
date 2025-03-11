<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->search;
        $services = Service::when(request()->filled("search"), function ($query) {
            $keyword = trim(request("search"));
            return $query->where("name", "LIKE", "%$keyword%");
        })
           
            ->when(request()->filled("status"), function ($query) {
                return $query->where("status", request("status"));
            })

            ->orderBy("id", "desc")->paginate(config("contant.paginatePerPage"));

        $title = "Service Management";
        $all_services = Service::where('status',1)->get();
        return view("pages.master.services", compact("title", 'services','search','all_services'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
          


        ]);

        $user = new Service();
   
        $user->name = $request->name;
      
        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => true, 'message' => "Service Created Successfully"]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|exists:services,email',
            'full_address'=> 'required',
            'phone' => 'required',


        ]);

        $user =  Service::find($id);
        $user->name = $request->name;
       
        $user->status = $request->status;
      

        $user->save();
        return response()->json(['success' => true, 'message' => "Service Updated Successfully"]);
    }

    public function  destroy($id)
    {
        $user = Service::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => true, 'message' => "Service deleted successfully"]);
        }
        return response()->json(['success' => false, 'message' => "Service does not exists"]);
    }



    public function subCategory(Request $request)
    {
        $request->validate([
            'service_id' => 'required',
            'sub_category' => 'required|string|max:255', // Ensure category name is provided
        ]);

        // Find the service by ID
        $service = Service::find($request->service_id);

        if (!$service) {
            return response()->json(['success' => false, 'message' => "Service not found"], 404);
        }

        // Update only the category (sub-category name)
        $service->sub_category = $request->sub_category;
        $service->save();

        return response()->json(['success' => true, 'message' => "Subcategory updated successfully"]);
    }

}
