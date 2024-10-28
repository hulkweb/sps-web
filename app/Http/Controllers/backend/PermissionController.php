<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $permission = Permission::select(['id', 'name', 'status'])->latest();
            return DataTables::of($permission)
                ->make(true);
        }
        return view('backend.staff.permission.index');
    }



}
