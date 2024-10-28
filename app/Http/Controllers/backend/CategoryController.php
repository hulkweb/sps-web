<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Artisan;

class CategoryController extends Controller
{


public function index(Request $request)
{
    // For Web request: Return Yajra DataTable for web
    if ($request->ajax()) {
        $categories = Category::select(['id', 'name', 'description','status','popular', 'image'])->latest();
        return DataTables::of($categories)
            ->addColumn('image', function($category) {
                $image = $category->image ? uploads($category->image) : 'No image';
                return '<img src="'.$image.'" width="50" height="50">';
            })
            ->addColumn('actions', function($category) {
                return '
                    <a href="'.route('category.edit', $category->id).'" class="btn btn-sm btn-warning">Edit</a>
                    <form action="'.route('category.destroy', $category->id).'" method="POST" style="display:inline-block;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                ';
            })
            ->addColumn('status', function($category){
$status = '<div class="form-check form-switch form-check-md mb-0">
<input class="form-check-input" id="tab_'.$category->id.'" type="checkbox" data-status="'.$category->status.'" onclick="Toggle('. $category->id .','. "'categories'" .')" ';
if($category->status == 1){
    $status .='checked';
}

$status .='> </div>';
return $status;
            })
            ->addColumn('popular', function($category){
                $popular = '<div class="form-check form-switch form-check-md mb-0">
                <input class="form-check-input" id="tab1_'.$category->id.'" type="checkbox" data-status="'.$category->popular.'" onclick="Toggle('. $category->id .','. "'categories','popular'" .')" ';
                if($category->popular == 1){
                    $popular .='checked';
                }

                $popular .='> </div>';
                return $popular;
                            })
            ->rawColumns(['popular','image','status', 'actions'])
            ->make(true);
    }



    return view('backend.category.index');
}


public function create()
    {
        return view('backend.category.add');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validation rules for web and API
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        // Handle image upload to public directory

          $category = new Category;
           $category->name = $request->name;
           $category->description = $request->description;
           $category->image = $request->file('image')->store('images/category','public');
          $category->save();


        // Handle response for web
        if($category){
            Alert::toast('Successfully added category', 'success');
        }else{
            Alert::toast('Something went wrong', 'error');
        }
        return redirect()->back();
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('backend.category.add', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($id,$request->all());
        try {
            $category = Category::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:categories,name,' . $id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {

                    return redirect()->back()->withErrors($validator)->withInput();

            }
            $category->name = $request->name;
            $category->description = $request->description;
            if ($request->hasFile('image')) {
                @unlink(uploads($category->image));
                 $category->image = $request->file('image')->store('images/category','public');
            }
            $category->save();


            Alert::toast('Successfully updated category', 'success');
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
            $category = Category::findOrFail($id);

            // Delete the associated image file if it exists

                @unlink(uploads($category->image));


            // Delete the category
            $category->delete();
            // Success alert for web
            Alert::toast('Successfully deleted category', 'success');
            return redirect()->route('category.index');

        } catch (\Exception $e) {

            Alert::toast('Something went wrong. Could not delete category.', 'error');
            return redirect()->back();
        }
    }
}
