<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    /**
     * Show the login form for web.
     */
    public function showLoginForm()
    {
        return view('backend.login');
    }

    public function privacy()
    {
         $data = getSetting('privacy_policy');
        return view('privacy',compact('data'));
    }

    public function term()
    {
       $data = getSetting('term_condition');
        return view('term',compact('data'));
    }

    /**
     * Handle a login request for both web and API.
     */
    public function login(Request $request)
    {

        $request->validate([
           'password' => 'required|min:6',  // Ensure password is confirmed (password_confirmation field required)
        ],[
            'password.required'=>'Password is required',
        ]);
        if(isset($request->email)){
            $user =  User::where(['email'=>$request->email,'type'=>'admin','status'=>'active'])->first();
        }else{
            $user =  User::where(['mobile'=>$request->mobile, 'type'=>'admin','status'=>'active'])->first();
        }
        if($user){
            if($user->type == 'admin'){

            if(Hash::check($request->password, $user->password)){
                Auth::login($user);
                Alert::toast('Login Successful!', 'success');
                return redirect()->intended('dashboard');
            }else{
                Alert::toast('Password not match!', 'error');
                return redirect()->back();
            }
        }else{
            Alert::toast('Login only Admin!', 'error');
                return redirect()->back();
        }
        }else{
            Alert::toast('User email or mobile not exist!', 'error');
            return redirect()->back();
        }

    }




    // Dummy OTP verification function (replace with actual logic)
    private function verifyOtp($mobile, $otp)
    {
        // Example logic, replace with actual OTP service verification
        return $otp === '123456'; // Assume the OTP is '123456' for testing
    }

    public function register(Request $request)
    {

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'mobile' => 'required|numeric|digits:10|unique:users,mobile',
            'country_code' => 'nullable|string|max:3', // Validation for country_code
            'password' => 'required|min:8',  // Ensure password is confirmed (password_confirmation field required)
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
             $user->country_code = $request->country_code;
             $user->password = bcrypt($request->password);
             $user->save();


        // For API response
        if ($request->expectsJson()) {
            // Automatically login user for API and generate a token
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'User registered successfully!',
                'token' => $token,
                'user' => $user,
            ], 201); // 201 Created
        } else {
            // Web response - log the user in and redirect to dashboard
            Auth::login($user);
            Alert::toast('Registration Successful!', 'success');
            return redirect()->intended('dashboard');
        }
    }



    /**
     * Handle a logout request for both web and API.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        if ($request->expectsJson()) {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Logged out successfully']);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Alert::toast('Logout Successfully', 'error');
        return redirect()->route('login');
    }

    public function dashboard()
    {
        return view('backend.dashboard');
    }

    public function userProfile()
    {
        return view('backend.setting.profile');
    }

    public function updateProfile(Request $request)
    {

        $user = auth()->user();
        if($user){
            if(isset($request->name)){
                $user->name = $request->name;
            }

            if(isset($request->email)){
                $user->name = $request->email;
            }

            if(isset($request->mobile)){
                $user->name = $request->mobile;
            }

            if(isset($request->password)){
                $user->name = Hash::make($request->password);
            }


            if ($request->hasFile('image')) {
                @unlink(uploads($user->image));
                $user->image = $request->file('image')->store('images/user','public');
            }

            if(isset($request->address)){
                $user->name = $request->address;
            }

            $user->save();
             // Web response
             Alert::toast('Profile update successfully!', 'success');
             return redirect()->back();
        }else{
            // Web response
            Alert::toast('Account not found!', 'error');
            return redirect()->back();
        }
    }


}
