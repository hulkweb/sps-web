<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\VariationType;
use App\Models\VariationValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class VariationValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $variation_values = VariationValue::with('variation_type')->select(['id', 'name', 'status', 'variation_type_id'])->latest();

            return DataTables::of($variation_values)
                ->addColumn('variation_type', function ($variation_value) {
                    return $variation_value->variation_type ? $variation_value->variation_type->name : 'No Type';
                })

                ->addColumn('actions', function($variation_value) {
                    return '
                        <a href="'.route('variation_value.edit', $variation_value->id).'" class="btn btn-sm btn-warning">Edit</a>
                        <form action="'.route('variation_value.destroy', $variation_value->id).'" method="POST" style="display:inline-block;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    ';
                })
                ->addColumn('status', function($variation_value){
                    $status = '<div class="form-check form-switch form-check-md mb-0">
                    <input class="form-check-input" id="tab_'.$variation_value->id.'" type="checkbox" data-status="'.$variation_value->status.'" onclick="Toggle('. $variation_value->id .','. "'subcategories'" .')" ';
                    if($variation_value->status == 1){
                        $status .='checked';
                    }

                    $status .='> </div>';
                    return $status;
                                })
                ->rawColumns(['status','image', 'actions'])
                ->make(true);
        }
        $variation_types = VariationType::where('status',1)->get();
        return view('backend.variation_value.index',compact('variation_types'));
    }


    public function create()
    {
        $variation_types = VariationType::where('status',1)->get();
        return view('backend.variation_value.add',compact('variation_types'));
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:variation_values,name',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        $variation_value = new VariationValue;
        $variation_value->name = $request->name;
        $variation_value->variation_type_id = $request->variation_type_id;

        $variation_value->save();

        if($variation_value){
            Alert::toast('Successfully added sub category', 'success');
        }else{
            Alert::toast('Something went wrong', 'error');
        }
        return redirect()->back();
    }


    public function edit(string $id)
    {
        $variation_types = VariationType::where('status',1)->get();
        $variation_value = VariationValue::findOrFail($id);
        return view('backend.variation_value.add', compact('variation_value','variation_types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $variation_value = VariationValue::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:variation_values,name,' . $id,
            ]);

            if ($validator->fails()) {

                    return redirect()->back()->withErrors($validator)->withInput();
                }

            $variation_value->name = $request->name;
            $variation_value->variation_type_id = $request->variation_type_id;
            $variation_value->save();


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
            $variation_value = VariationValue::findOrFail($id);
            // Delete the variation variation_value
            $variation_value->delete();

            Alert::toast('Successfully deleted sub category', 'success');
            return redirect()->route('subcategory.index');

        } catch (\Exception $e) {

            Alert::toast('Something went wrong. Could not delete sub category.', 'error');
            return redirect()->back();
        }
    }
}
