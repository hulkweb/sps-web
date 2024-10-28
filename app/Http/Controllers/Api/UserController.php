<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\State;
use App\Models\Order;
use App\Models\AssignOrder;
use App\Models\City;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function transaction(Request $request){
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
        }

        if($request->type == 'monthly'){

            $year = Date('Y');
            $month = Date('m');

        $monthlyTotals = [
            'Jan' => 0,
            'Feb' => 0,
            'Mar' => 0,
            'Apr' => 0,
            'May' => 0,
            'Jun' => 0,
            'Jul' => 0,
            'Aug' => 0,
            'Sep' => 0,
            'Oct' => 0,
            'Nov' => 0,
            'Dec' => 0,
        ];

        $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];

        // Fetch all payments

            $i = 0;
            foreach ($monthlyTotals as  $key=>$total) {
                $payments = Payment::where(['payment_status'=>'paid','provider_reference_id'=>auth()->user()->id])->whereMonth('created_at', $months[$i])->whereYear('created_at', $year)->sum('amount');
                $monthlyTotals[$key] = (float)$payments;
                $i++;
            }

            return response()->json([
                'message' => 'Transaction get successfully',
                'data' => $monthlyTotals,
                'success' => true,
            ], 200);

        }elseif($request->type == 'weekly'){

            $weeklyTotals = [
            'Mon' => 0,
            'Tue' => 0,
            'Wed' => 0,
            'Thu' => 0,
            'Fri' => 0,
            'Sat' => 0,
            'Sun' => 0,
        ];

        // Get the start and end of the current week (Monday to Sunday)
        $startOfWeek = Carbon::now()->startOfWeek();  // Monday
        $endOfWeek = Carbon::now()->endOfWeek();      // Sunday

        $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        foreach ($daysOfWeek as $day) {
            $currentDay = $startOfWeek->copy();  // Clone the startOfWeek object

            // Loop through each day of the current week
            foreach ($weeklyTotals as $key => $total) {
                // For each day, get the total sum of 'amount' from the payments table
                $payments = Payment::where(['payment_status'=>'paid','provider_reference_id'=>auth()->user()->id])
                    ->whereDate('created_at', $currentDay)
                    ->sum('amount');

                // Store the sum in the respective day of the week
                $weeklyTotals[$key] = (float)$payments;

                // Move to the next day
                $currentDay->addDay();
            }
        }

        return response()->json([
            'message' => 'Transaction get successfully',
            'data' => $weeklyTotals,
            'success' => true,
        ], 200);

        }else{


            // Initialize totals for the last 5 years including the current year
            $currentYear = Carbon::now()->year;

            // Initialize an empty array for yearly totals
            $yearlyTotals = [];

            // Get the years from the current year back to the previous 4 years
            $years = range($currentYear - 4, $currentYear);  // This generates [2020, 2021, 2022, 2023, 2024] dynamically

            foreach ($years as $year) {
                // Fetch the total sum of 'amount' for each year
                $payments = Payment::where(['payment_status'=>'paid','provider_reference_id'=>auth()->user()->id])
                    ->whereYear('created_at', $year)
                    ->sum('amount');

                // Dynamically add the year as the key and the total as the value
                $yearlyTotals[$year] = (float)$payments;
            }

            return response()->json([
                'message' => 'Transaction get successfully',
                'data' => $yearlyTotals,
                'success' => true,
            ], 200);
        }
    }


   public function users(){
      $users = User::where('createBy',auth()->user()->id)->latest()->get();
      if(count($users)>0){
        return response()->json([
            'message' => 'Users get successfully',
            'data' => $users,
            'success' => true,
        ], 200);
      }else{
        return response()->json([
            'message' => 'Users not found',
            'data' => null,
            'success' => false,
        ], 404);
      }
   }

   public function getState(){
    $states = State::where('status',1)->orderBy('name')->get();
    if(count($states)>0){
      return response()->json([
          'message' => 'States get successfully',
          'data' => $states,
          'success' => true,
      ], 200);
    }else{
      return response()->json([
          'message' => 'State not found',
          'data' => null,
          'success' => false,
      ], 404);
    }
   }


   public function getCity($id){
    $cities = City::where(['status'=>1, 'state_id'=>$id])->orderBy('name')->get();
    if(count($cities)>0){
      return response()->json([
          'message' => 'Cities get successfully',
          'data' => $cities,
          'success' => true,
      ], 200);
    }else{
      return response()->json([
          'message' => 'City not found',
          'data' => null,
          'success' => false,
      ], 404);
    }
   }

   public function userAdd(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'mobile' => 'required|unique:users,mobile',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->createBy = auth()->user()->id;
        $user->type = 'user';
        if (isset($request->address)) {
         $user->address = $request->address;
        }
        $user->password = Hash::make($request->password);
        if ($request->hasFile('image')) {
            @unlink(uploads($user->image));
            $user->image = $request->file('image')->store('images/user','public');
        }

        $user->save();

        return response()->json([
            'message' => 'Create User Successfully',
            'data' => $user,
            'success' => true,
        ], 200);
   }

   public function assignOrder(Request $request){
       $orders = AssignOrder::where(['driver_id'=>auth()->user()->id,'status'=>'pending'])->with('order','order.address','order.orderItems','order.user','order.orderItems.product','order.orderItems.variation')->get();
       if(count($orders)>0){

        return response([
            'message' => 'Order List get successfully',
            'success' => true,
            'data' => $orders
        ], 200);
    }else{
        return response([
            'message' => 'Order not found',
            'success' => true,
            'data' => null
        ], 404);
    }
   }

   public function updateStatusOrder(Request $request){
    $validator = Validator::make($request->all(), [
        'request_id' => 'required',
        'status' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
    }

    $a_order = AssignOrder::find($request->request_id);
    $order = Order::find($a_order->order_id);
    if($request->status == 'accept'){
        $a_order->status = 'accept';
        $a_order->save();

        $order->driver = $a_order->driver_id;


    }else{
        $a_order->status = $request->status;
        if(isset($request->comment)){
            $a_order->comment = $request->comment;
        }
        $a_order->save();
    }
    $order->driver_status = $request->status;
    $order->save();
    return response([
        'message' => 'Order Delivery Status update successfully',
        'success' => true,
        'data' => null
    ], 200);

   }

}
