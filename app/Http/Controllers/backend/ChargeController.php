<?php

namespace App\Http\Controllers\backend;

use App\Models\Charge;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Artisan;

class ChargeController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $charge = Charge::select(['id', 'name', 'value','status', 'type'])->latest();
            return DataTables::of($charge)
                ->addColumn('actions', function($charge) {
                    return '
                        <a href="'.route('charge.edit', $charge->id).'" class="btn btn-sm btn-warning">Edit</a>
                        <form action="'.route('charge.destroy', $charge->id).'" method="POST" style="display:inline-block;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    ';
                })
                ->addColumn('status', function($charge){
                    $status = '<div class="form-check form-switch form-check-md mb-0">
                    <input class="form-check-input" id="tab_'.$charge->id.'" type="checkbox" data-status="'.$charge->status.'" onclick="Toggle('. $charge->id .','. "'charges'" .')" ';
                    if($charge->status == 1){
                        $status .='checked';
                    }

                    $status .='> </div>';
                    return $status;
                                })
                ->rawColumns(['status','actions'])
                ->make(true);
        }
        return view('backend.charge.index');
    }

    public function create()
    {
        return view('backend.charge.add');
    }

        public function store(Request $request)
        {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'value' => 'required',
                'type' => 'nullable',
            ]);

            if ($validator->fails()) {

                return redirect()->back()->withErrors($validator)->withInput();

            }

            $charge = Charge::create([
                'name' => $request->input('name'),
                'type' => $request->input('type'),
                'value' => $request->input('value'),
            ]);


            if($charge){
                Alert::toast('Successfully added charge', 'success');
            }else{
                Alert::toast('Something went wrong', 'error');
            }
            return redirect()->route('charge.index');
        }


        public function edit(string $id)
        {
            $charge = Charge::findOrFail($id);
            return view('backend.charge.add', compact('charge'));
        }

        public function update(Request $request, $id)
        {

            try {
                $charge = Charge::findOrFail($id);
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                'value' => 'required',
                'type' => 'nullable',
                ]);

                if ($validator->fails()) {

                        return redirect()->back()->withErrors($validator)->withInput();

                }


                $charge->update([
                    'name' => $request->input('name') ?? $charge->name,
                    'type' => $request->input('type') ?? $charge->type,
                    'value' => $request->input('value') ?? $charge->value,
                ]);
                // Success toast alert for web
                Alert::toast('Successfully updated charge', 'success');
                return redirect()->route('charge.index');

            } catch (\Exception $e) {

                Alert::toast('Something went wrong while updating the charge', 'error');
                return redirect()->back()->withInput();
            }
        }



        public function destroy($id, Request $request)
        {
            try {
                // Find the charge by ID
                $charge = Charge::findOrFail($id);
                $charge->delete();

               // Success alert for web
                Alert::toast('Successfully deleted charge', 'success');
                return redirect()->route('charge.index');

            } catch (\Exception $e) {

                // Error alert for web
                Alert::toast('Something went wrong. Could not delete charge.', 'error');
                return redirect()->back();
            }
        }
}
