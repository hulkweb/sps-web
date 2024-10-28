<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subcategories = SubCategory::with('category')->select(['id', 'name', 'description','status', 'image', 'category_id'])->latest();

            return DataTables::of($subcategories)
                ->addColumn('category', function ($subcategory) {
                    return $subcategory->category ? $subcategory->category->name : 'No Category';
                })
                ->addColumn('image', function($subcategory) {
                    $image = $subcategory->image ? uploads($subcategory->image) : 'No image';
                    return '<img src="'.$image.'" width="50" height="50">';
                })
                ->addColumn('actions', function($subcategory) {
                    return '
                        <a href="'.route('subcategory.edit', $subcategory->id).'" class="btn btn-sm btn-warning">Edit</a>
                        <form action="'.route('subcategory.destroy', $subcategory->id).'" method="POST" style="display:inline-block;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    ';
                })
                ->addColumn('status', function($subcategory){
                    $status = '<div class="form-check form-switch form-check-md mb-0">
                    <input class="form-check-input" id="tab_'.$subcategory->id.'" type="checkbox" data-status="'.$subcategory->status.'" onclick="Toggle('. $subcategory->id .','. "'subcategories'" .')" ';
                    if($subcategory->status == 1){
                        $status .='checked';
                    }

                    $status .='> </div>';
                    return $status;
                                })
                ->rawColumns(['status','image', 'actions'])
                ->make(true);
        }
        $categories = Category::where('status',1)->get();
        return view('backend.subcategory.index',compact('categories'));
    }


    public function create()
    {
        $categories = Category::where('status',1)->get();
        return view('backend.subcategory.add',compact('categories'));
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sub_categories,name',
            'category_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        $subcategory = new SubCategory;
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;
        $subcategory->description = $request->description;
        $subcategory->image = $request->file('image')->store('images/category','public');
       $subcategory->save();

        if($subcategory){
            Alert::toast('Successfully added sub category', 'success');
        }else{
            Alert::toast('Something went wrong', 'error');
        }
        return redirect()->back();
    }


    public function edit(string $id)
    {
        $categories = Category::where('status',1)->get();
        $subcategory = SubCategory::findOrFail($id);
        return view('backend.subcategory.add', compact('subcategory','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $subcategory = SubCategory::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:sub_categories,name,' . $id,
                'category_id' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {

                    return redirect()->back()->withErrors($validator)->withInput();
                }

            $subcategory->name = $request->name;
            $subcategory->category_id = $request->category_id;
            $subcategory->description = $request->description;
            if ($request->hasFile('image')) {
                @unlink(uploads($subcategory->image));
                 $subcategory->image = $request->file('image')->store('images/category','public');
            }
            $subcategory->save();


            // Success toast alert for web
            Alert::toast('Successfully updated sub category', 'success');
            return redirect()->back();

        } catch (\Exception $e) {

            // Failure toast alert for web
            Alert::toast('Something went wrong while updating the category', 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        try {
            // Find the category by ID
            $category = SubCategory::findOrFail($id);

                @unlink(uploads($category->image));


            // Delete the category
            $category->delete();

            Alert::toast('Successfully deleted sub category', 'success');
            return redirect()->route('subcategory.index');

        } catch (\Exception $e) {

            Alert::toast('Something went wrong. Could not delete sub category.', 'error');
            return redirect()->back();
        }
    }
}
