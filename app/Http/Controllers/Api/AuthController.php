<?php

namespace App\Http\Controllers\Api;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{

    public function home_api(){
        $data=[];
        $data['privacy_policy'] = route('privacy');
        $data['term_condition'] = route('term');
        $data['home_banner'] = getSetting('home_banner');
        $data['logo'] = getSetting('logo');
        return response([
            'message' => 'Data success',
            'success' => true,
            'data' =>  $data
        ], 200);
    }

    public function notification()
    {
        // @dd("hello");
        $notification = Notification::where('user_id', auth()->user()->id)->latest()->get();
        foreach ($notification as $key => $n) {
            $notification[$key]->data = json_decode($n->data);
        }
        $unread = Notification::where(['user_id' => auth()->user()->id, 'read' => 0])->count();
        if (count($notification) > 0) {
            return response([
                'message' => null,
                'success' => true,
                'data' =>  ['notifications' => $notification, 'unread' => $unread]
            ], 200);
        } else {
            return response([
                'message' => 'No notification',
                'success' => false,
                'data' =>  null
            ], 404);
        }
    }

    public function notificationRead(Request $request)
    {
        // @dd("hello");
        if (isset($request->type) && $request->type == 'all') {
            Notification::where('user_id', auth()->user()->id)->update(['read' => 1]);
        } else {
            Notification::where(['user_id' => auth()->user()->id, 'id' => $request->id])->update(['read' => 1]);
        }
        return response([
            'message' => "Read",
            'success' => true,
            'data' =>  null
        ], 200);
    }


    public function userAddress(Request $request)
    {
        if(auth()->user()->type == 'seller' && isset($request->user_id)){
            $address = UserAddress::where('user_id',$request->user_id)->with('state','city')->get();
        }else{
            $address = UserAddress::where('user_id',auth()->user()->id)->with('state','city')->get();

        }
        if(count($address)>0){
            return response([
                'message' => 'User Address Successfully',
                'success' => true,
                'data' => $address
            ], 200);
        }else{
            return response([
                'message' => 'Address not found',
                'success' => false,
                'data' => null
            ], 404);
        }
    }


    public function updateProfile(Request $request)
    {
        if(auth()->user()->type == 'seller' && isset($request->user_id)){
            $user = User::find($request->user_id);
        }else{
            $user = auth()->user();
        }

        if($user){
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
                $user->password = Hash::make($request->password);
            }


            if ($request->hasFile('image')) {
                @unlink(uploads($user->image));
                $user->image = $request->file('image')->store('images/user', 'public');
            }

            if(isset($request->address)){
                $user->address = $request->address;
            }

            if(isset($request->status)){
                $user->status = $request->status;
            }

            $user->save();
            return response([
                'message' => 'Update profile Successfully',
                'success' => true,
                'data' => $user
            ], 200);
        }else{
            return response([
                'message' => 'User not found',
                'success' => false,
                'data' => null
            ], 404);
        }
    }

    public function createShippingAddress(Request $request)
    {
        if(isset($request->address_id)){
            $address = UserAddress::find($request->address_id);
            $msg = "Address update successfully";
        }else{
            $address = new UserAddress;
            $msg = "Address add successfully";
        }


		$address->name = $request->name;
        if(auth()->user()->type == 'seller' && isset($request->user_id)){
            $address->user_id = $request->user_id;
        }else{

            $address->user_id = auth()->user()->id;
        }
        $address->address = $request->address;
        $address->country = $request->country;
		$address->state = $request->state;
        $address->city = $request->city;
        $address->type = $request->type;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->phone;
        $address->save();

        return response([
            'message' => $msg,
            'success' => true,
            'data' => $address
        ], 200);
    }



    public function deleteShippingAddress(Request $request)
    {
        $address = UserAddress::find($request->address_id);
        if($address){
            $address->delete();
            return response([
                'message' => 'Address delete successfully',
                'success' => false,
                'data' => null
            ], 200);
        }else{
            return response([
                'message' => 'Address not Found',
                'success' => false,
                'data' => null
            ], 404);
        }

    }

    public function makeShippingAddressDefault(Request $request)
    {
        UserAddress::where('user_id', auth()->user()->id)->update(['is_default' => 0]); //make all user addressed non default first

        $address = UserAddress::find($request->address_id);
        $address->is_default = 1;
        $address->save();
        return response([
            'message' => 'Default Address successfully',
            'success' => true,
            'data' => null
        ], 200);
    }

    public function login(Request $request)
    {
          // Validate incoming request data
          $validator = Validator::make($request->all(), [
                'type' => 'required',
                'user_type' => 'required'
            ],[
                'type.required'=>'Type is required',
                'user_type.required'=>'User Type is required',
                ]
          );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
        }
        if($request->type == 'email'){
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email:rfc,dns|max:255',
                'password' => 'required|min:8',
              ],[
                'email.required'=>'Email is required',
                'password.required'=>'Password is required',
                ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
            }

            $user = User::where(['email'=>$request->email, 'type'=>$request->user_type])->first();
            if($user){
                if(Hash::check($request->password, $user->password)){
                    if (isset($request->fcm_token)) {
                        $user->fcm_token = $request->fcm_token;
                    }

                    $user->save();
                    $user->token = $user->createToken($user->mobile)->plainTextToken;

                        return response([
                            'message' => 'Login Successfully',
                            'success' => true,
                            'data' => $user
                        ], 200);

                }else{
                    return response()->json([
                        'message' => 'Password not match',
                        'success' => false,
                        'data' => null,
                    ], 400);
                }

            }else{
                return response()->json([
                    'message' => 'User Not Found',
                    'data' => null,
                    'success' => false,
                ], 404);
            }

        }else{
            $validator = Validator::make(
                $request->all(),
                [
                    'mobile' => 'required|numeric|digits:10',
                ],[
                    'mobile.required'=>'Mobile No. is required',
                    'mobile.digits'=>'Mobile No. only 10 digit accept',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
            }
            $user = User::where(['mobile'=>$request->mobile, 'type'=>$request->user_type])->first();
            $otp = rand(1111,9999);
            $msg = 'Your Otp is '.$otp.'. Do not share Otp';
            if($user){
               $user->otp = $otp;
               $user->save();
               sendSMS($user->mobile,$msg);
               return response()->json([
                   'message' => 'Otp Send Successfully!',
                   'data' => $user,
                   'success' => true,
               ], 200);
            }else{
               return response()->json([
                   'message' => 'Mobile not Exist',
                   'data' => null,
                   'success' => false,
               ], 400);
            }
        }
    }


    public function changePassword(Request $request)
    {
          // Validate incoming request data
          $validator = Validator::make($request->all(), [
                'mobile' => 'required'
            ],[
                'mobile.required'=>'Phone no. is required',
                ]
          );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
        }


            $user = User::where(['mobile'=>$request->mobile,'status'=>'active'])->first();
            $otp = rand(1111,9999);
            $msg = 'Your Otp is '.$otp.'. Do not share Otp';
            if($user){
               $user->otp = $otp;
               $user->save();
               sendSMS($user->mobile,$msg);
               return response()->json([
                   'message' => 'Otp Send Successfully!',
                   'data' => $otp,
                   'success' => true,
               ], 200);
            }else{
               return response()->json([
                   'message' => 'Mobile not Exist',
                   'data' => null,
                   'success' => false,
               ], 400);
            }
    }



    public  function resetPassword(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'mobile' => 'required|numeric|digits:10',
                'password' => 'required|min:6',
                'otp' => 'required|digits:4',
            ],[
                'password.required'=>'Password is required',
                'password.min'=>'Password atleast 6 character',
                'mobile.required'=>'Mobile No. is required',
                'otp.required'=>'Otp is required',
                'otp.digits'=>'Otp must be 4 digit',
                'mobile.digits'=>'Mobile No. only 10 digit accept',
            ]
        );

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->messages()->all(), 'success' => false], 400);
        }

        $user = User::where(['mobile' => $request->mobile, 'otp' => $request->otp])->first();
        if ($user) {
            $user->otp = null;
            $user->password = Hash::make($request->password);
            $user->save();
            $user->token = $user->createToken($user->mobile)->plainTextToken;
            if ($user) {
                return response([
                    'message' => 'Password change successfully',
                    'success' => true,
                    'data' => $user
                ], 200);
            }
        } else {
            return response([
                'message' => 'Otp incorret',
                'success' => false,
                'data' => NULL
            ], 400);
        }
    }


    public  function verifyOtp(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'mobile' => 'required|numeric|digits:10',
                'otp' => 'required|digits:4',
            ],[
                'mobile.required'=>'Mobile No. is required',
                'otp.required'=>'Otp is required',
                'otp.digits'=>'Otp must be 4 digit',
                'mobile.digits'=>'Mobile No. only 10 digit accept',
            ]
        );

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->messages()->all(), 'success' => false], 400);
        }

        $user = User::where(['mobile' => $request->mobile, 'otp' => $request->otp])->first();
        if ($user) {
            if (isset($request->fcm_token)) {
                $user->fcm_token = $request->fcm_token;
            }

            $user->otp = null;
            $user->save();
            $user->token = $user->createToken($user->mobile)->plainTextToken;
            if ($user) {
                return response([
                    'message' => 'Login successfully',
                    'success' => true,
                    'data' => $user
                ], 200);
            }
        } else {
            return response([
                'message' => 'Otp incorret',
                'success' => false,
                'data' => NULL
            ], 400);
        }
    }

    public function register(Request $request)
    {

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'mobile' => 'required|numeric|digits:10|unique:users,mobile',
            'country_code' => 'nullable|string|max:3', // Validation for country_code
            'password' => ['required',Rules\Password::defaults()],  // Ensure password is confirmed (password_confirmation field required)
        ],[
            'name.required'=>'Name is required',
            'email.required'=>'Email is required',
            'mobile.required'=>'Mobile No. is required',
            'password.required'=>'Password is required',
            'mobile.unique'=>'This mobile no.already exist!',
            'mobile.digits'=>'Mobile No. only 10 digit accept',
        ]
    );

    if ($validator->fails()) {
        return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
    }
             $user = new User;
             $user->name = $request->name;
             $user->email = $request->email;
             $user->mobile = $request->mobile;
             $user->type = 'user';
             $user->country_code = $request->country_code;
             $user->password = Hash::make($request->password);
             $user->save();
             $otp = rand(1111, 9999);
            $msg = 'Your Otp is '.$otp.'. Do not share Otp';
             if($user){
                $user->otp = $otp;
                $user->save();
                sendSMS($user->mobile,$msg);
                return response()->json([
                    'message' => 'User registered successfully!',
                    'data' => $user,
                    'success' => true,
                ], 201);
             }else{
                return response()->json([
                    'message' => 'Something went wrong',
                    'data' => null,
                    'success' => false,
                ], 400);
             }
         }



    public function  userDetail(Request $request){
        if(isset($request->user_id)){
            $user =  User::find($request->user_id);
        }else{
            $user =  auth()->user();
        }
        if($user){
            return response([
                'message' => 'User Detail Successfully',
                'success' => true,
                'data' => $user
            ], 200);
        }else{
            return response([
                'message' => 'User not found',
                'success' => false,
                'data' => NULL
            ], 404);
        }

    }



    public function logout(Request $request)
    {

        auth()->user()->tokens()->delete();
        return response([
            'message' => 'User Logout successfully',
            'success' => true,
            'data' => NULL
        ], 200);

    }



}
