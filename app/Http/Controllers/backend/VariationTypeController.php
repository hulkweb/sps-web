<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\VariationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Artisan;

class VariationTypeController extends Controller
{


public function index(Request $request)
{
    // For Web request: Return Yajra DataTable for web
    if ($request->ajax()) {
        $variation_types = VariationType::select(['id', 'name','status'])->latest();
        return DataTables::of($variation_types)

            ->addColumn('actions', function($variation_type) {
                return '
                    <a href="'.route('variation_type.edit', $variation_type->id).'" class="btn btn-sm btn-warning">Edit</a>
                    <form action="'.route('variation_type.destroy', $variation_type->id).'" method="POST" style="display:inline-block;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                ';
            })
            ->addColumn('status', function($variation_type){
$status = '<div class="form-check form-switch form-check-md mb-0">
<input class="form-check-input" id="tab_'.$variation_type->id.'" type="checkbox" data-status="'.$variation_type->status.'" onclick="Toggle('. $variation_type->id .','. "'categories'" .')" ';
if($variation_type->status == 1){
    $status .='checked';
}

$status .='> </div>';
return $status;
            })

            ->rawColumns(['status', 'actions'])
            ->make(true);
    }



    return view('backend.variation_type.index');
}


public function create()
    {
        return view('backend.variation_type.add');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validation rules for web and API
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        // Handle image upload to public directory

          $variation_type = new VariationType;
           $variation_type->name = $request->name;
          $variation_type->save();


        // Handle response for web
        if($variation_type){
            Alert::toast('Successfully added variation_type', 'success');
        }else{
            Alert::toast('Something went wrong', 'error');
        }
        return redirect()->back();
    }

    public function edit(string $id)
    {
        $variation_type = VariationType::findOrFail($id);
        return view('backend.variation_type.add', compact('variation_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($id,$request->all());
        try {
            $variation_type = VariationType::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:variation_types,name,' . $id,
            ]);

            if ($validator->fails()) {

                    return redirect()->back()->withErrors($validator)->withInput();

            }
            $variation_type->name = $request->name;
            $variation_type->save();


            Alert::toast('Successfully updated variation type', 'success');
            return redirect()->back();

        } catch (\Exception $e) {

            // Failure toast alert for web
            Alert::toast('Something went wrong while updating the variation type', 'error');
            return redirect()->back()->withInput();
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        try {
            // Find the variation_type by ID
            $variation_type = VariationType::findOrFail($id);

            // Delete the associated image file if it exists

                @unlink(uploads($variation_type->image));


            // Delete the variation_type
            $variation_type->delete();
            // Success alert for web
            Alert::toast('Successfully deleted variation type', 'success');
            return redirect()->route('variation_type.index');

        } catch (\Exception $e) {

            Alert::toast('Something went wrong. Could not delete variation_type.', 'error');
            return redirect()->back();
        }
    }
}
