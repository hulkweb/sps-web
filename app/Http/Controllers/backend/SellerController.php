<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Artisan;

class SellerController extends Controller
{

    public function index(Request $request)
    {
        // For Web request: Return Yajra DataTable for web
        if ($request->ajax()) {
            $users = User::select('*')->where('type','seller')->latest();

            return DataTables::of($users)
                ->addColumn('image', function($user) {
                    $image = $user->image ? uploads($user->image) : 'No image';
                    return '<img src="'.$image.'" width="50" height="50">';
                })
                ->addColumn('order', function($user){
                    return $user->sellerOrder->count();
                })
                ->addColumn('customer', function($user){
                    return $user->customerData->count();
                })
                ->addColumn('actions', function($user) {
                    return '
                     <a href="'.route('seller.show', $user->id).'" class="btn btn-sm btn-warning">View</a>
                        <a href="'.route('seller.edit', $user->id).'" class="btn btn-sm btn-warning">Edit</a>
                        <form action="'.route('seller.destroy', $user->id).'" method="POST" style="display:inline-block;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    ';
                })
                ->addColumn('status', function($user){
                    $status = '<div class="form-check form-switch form-check-md mb-0">
                    <input class="form-check-input" id="tab_'.$user->id.'" type="checkbox" data-status="'.$user->status.'" onclick="Toggle('. $user->id .','. "'users'" .')" ';
                    if($user->status == 'active'){
                        $status .='checked';
                    }

                    $status .='> </div>';
                    return $status;
                                })
                ->rawColumns(['customer','order','status','image', 'actions'])
                ->make(true);
        }
        return view('backend.seller.index');
    }

    public function create()
    {
        return view('backend.seller.add');
    }

    public function show($id)
    {
       $user = User::find($id);
       return view('backend.seller.detail',compact('user'));
    }


        public function store(Request $request)
        {
            // dd($request->all());
            // Validation rules for web and API
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email',
                'mobile' => 'required|unique:users,mobile',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                    // For web response
                    return redirect()->back()->withErrors($validator)->withInput();

            }


            // Handle response for web
             $user = new User;
             $user->name = $request->name;
             $user->email = $request->email;
             $user->mobile = $request->mobile;
             $user->type = 'seller';
             $user->address = $request->address;
             $user->password = Hash::make($request->password);
             $user->image = $request->file('image')->store('images/user','public');
             $user->save();
            Alert::toast('Successfully added Seller', 'success');
            return redirect()->back();
        }

        public function edit(string $id)
        {
            $user = User::findOrFail($id);
            return view('backend.seller.add', compact('user'));
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, $id)
        {
            // dd($id,$request->all());
            try {
                $user = User::findOrFail($id);
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|unique:users,email,' . $id,
                    'mobile' => 'required|unique:users,mobile,' . $id,
                    'password'=>  'min:6'
                ]);

                if ($validator->fails()) {
                        // For web response
                        return redirect()->back()->withErrors($validator)->withInput();

                }



                    if(isset($request->name)){
                        $user->name = $request->name;
                    }

                    if(isset($request->email)){
                        $user->email = $request->email;
                    }

                    if(isset($request->mobile)){
                        $user->mobile = $request->mobile;
                    }

                    if(isset($request->password)){
                        $user->address = Hash::make($request->password);
                    }


                    if ($request->hasFile('image')) {
                        @unlink('storage/app/'.$user->image);
                        $user->image = $request->file('image')->store('images/user','public');
                    }

                    if(isset($request->address)){
                        $user->address = $request->address;
                    }

                    $user->save();
                     // Web response

                // Success toast alert for web
                Alert::toast('Successfully updated delivery boy', 'success');
                return redirect()->route('seller.index');

            } catch (\Exception $e) {


                // Failure toast alert for web
                Alert::toast('Something went wrong while updating the delivery boy', 'error');
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
                $user = User::findOrFail($id);
                    @unlink(uploads($user->image));
                $user->delete();

                // Success alert for web
                Alert::toast('Successfully deleted category', 'success');
                return redirect()->route('seller.index');

            } catch (\Exception $e) {

                // Error alert for web
                Alert::toast('Something went wrong. Could not delete delivery boy.', 'error');
                return redirect()->back();
            }
        }
}
