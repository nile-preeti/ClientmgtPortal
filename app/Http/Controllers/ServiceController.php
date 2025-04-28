<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceSubCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $services = Service::with('subCategories') // Load subcategories
            ->when($request->filled("search"), function ($query) use ($request) {
                $keyword = trim($request->search);
                return $query->where("name", "LIKE", "%$keyword%");
            })
            ->when($request->filled("status"), function ($query) use ($request) {
                return $query->where("status", $request->status);
            })
            ->orderBy("id", "desc")
            ->paginate(config("contant.paginatePerPage"));

        $title = "Service Management";
        $all_services = Service::where('status', 1)->with('subCategories')->orderby('name','asc')->get();

        return view("pages.master.services", compact("title", 'services', 'search', 'all_services'));
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
            'status' => 'required|in:0,1',
            'sub_category' => 'nullable|string|max:255',
            'sub_category_id' => 'nullable|exists:service_sub_categories,id', // Ensure valid subcategory ID
        ]);

        // Find the existing service
        $service = Service::findOrFail($id);
        $service->name = $request->name;
        $service->status = $request->status;
        $service->save();

        // Update or Create Subcategory
        if ($request->filled('sub_category_id')) {
            // Update existing subcategory
            ServiceSubCategory::where('id', $request->sub_category_id)
                ->update(['sub_category' => $request->sub_category, 'category_id' => $service->id]);
        } elseif ($request->filled('sub_category')) {
            // Create new subcategory if no ID exists
            ServiceSubCategory::create([
                'category_id' => $service->id,
                'sub_category' => $request->sub_category,
            ]);
        }

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
            'service_id' => 'required|exists:services,id',
            'sub_category' => 'required|string|max:255',
        ]);

        // Directly create a new subcategory without checking for duplicates
        ServiceSubCategory::create([
            'category_id' => $request->service_id,
            'sub_category' => $request->sub_category,
        ]);

        return response()->json(['success' => true, 'message' => "Subcategory added successfully"]);
    }

}
