<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $roles = Role::select(['id', 'name','status', 'permission'])->latest();
            return DataTables::of($roles)
                ->addColumn('actions', function($role) {
                    return '
                        <a href="'.route('role.edit', $role->id).'" class="btn btn-sm btn-warning">Edit</a>
                        <form action="'.route('role.destroy', $role->id).'" method="POST" style="display:inline-block;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    ';
                })
                ->addColumn('permission', function($role){
                    $permission = json_decode($role->permission);
                    $data_pm = [];
                    foreach($permission as $id){
                        $perm = Permission::find($id);
                        if(isset($perm)){
                            array_push($data_pm,$perm->name);
                        }
                    }
                    return implode(", ",$data_pm);
                })
                ->addColumn('status', function($role){
                    $status = '<div class="form-check form-switch form-check-md mb-0">
                    <input class="form-check-input" id="tab_'.$role->id.'" type="checkbox" data-status="'.$role->status.'" onclick="Toggle('. $role->id .','. "'roles'" .')" ';
                    if($role->status == 1){
                        $status .='checked';
                    }

                    $status .='> </div>';
                    return $status;
                                })
                ->rawColumns(['status','permission','actions'])
                ->make(true);
        }

        return view('backend.staff.role.index');
    }

    public function create()
    {
        $permissions = Permission::latest()->get();
        return view('backend.staff.role.add',compact('permissions'));
    }


        public function store(Request $request)
        {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'permission.*' => 'required'
            ]);

            if ($validator->fails()) {

                return redirect()->back()->withErrors($validator)->withInput();

            }

            $role = new Role;
            $role->name = $request->name;
            $role->permission = json_encode($request->permission);
            $role->save();


            if($role){
                Alert::toast('Successfully added Role', 'success');
            }else{
                Alert::toast('Something went wrong', 'error');
            }
            return redirect()->route('role.index');
        }


        public function edit(string $id)
        {
            $role = Role::findOrFail($id);
            $permissions = Permission::latest()->get();
            return view('backend.staff.role.add', compact('role','permissions'));
        }

        public function update(Request $request, $id)
        {

            try {

                $validator = Validator::make($request->all(), [
                 'name' => 'required',
                'permission.*' => 'required',
                ]);

                if ($validator->fails()) {

                        return redirect()->back()->withErrors($validator)->withInput();

                }

                $role = Role::findOrFail($id);
                $role->name = $request->name;
            $role->permission = json_encode($request->permission);
            $role->save();


            if($role){
                Alert::toast('Successfully updated Role', 'success');
            }else{
                Alert::toast('Something went wrong', 'error');
            }
            return redirect()->route('role.index');

            } catch (\Exception $e) {

                Alert::toast('Something went wrong while updating the role', 'error');
                return redirect()->back()->withInput();
            }
        }



        public function destroy($id, Request $request)
        {
            try {
                // Find the charge by ID
                $charge = Role::findOrFail($id);
                $charge->delete();

               // Success alert for web
                Alert::toast('Successfully deleted role', 'success');
                return redirect()->route('role.index');

            } catch (\Exception $e) {

                // Error alert for web
                Alert::toast('Something went wrong. Could not delete role.', 'error');
                return redirect()->back();
            }
        }
}
