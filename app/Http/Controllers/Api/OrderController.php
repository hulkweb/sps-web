<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\DeliveryStatus;
use App\Models\DeliveryImage;
use App\Models\VariationProduct;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
class OrderController extends Controller
{


    public function createInvoice(Order $order)
{
    // Define the directory and file name
    $invoiceDirectory = public_path('invoices');
    $fileName = 'invoice_'.$order->id.'.pdf';
    $filePath = $invoiceDirectory.'/'.$fileName;

    // Check if the 'invoices' directory exists, if not create it
    if (!File::exists($invoiceDirectory)) {
        File::makeDirectory($invoiceDirectory, 0755, true); // Create the directory with proper permissions
    }

    // Generate the PDF from the Blade view
    $pdf = PDF::loadView('invoice', ['order' => $order]);

    // Save the PDF to the invoices folder
    $pdf->save($filePath);

    // Save the path to the 'invoice' column in the orders table
    $order->update(['invoice' => 'invoices/'.$fileName]);

    // You can return the PDF if needed
    return $pdf->download($fileName);
}
    // List orders (API and web)
    public function orderList(Request $request)
    {
        $data=[];
        if(isset($request->user_id)){
            $orders = Order::where('user_id',$request->user_id)->with('address','orderItems','user','orderItems.product','orderItems.variation')->latest()->get();
        }else{

            $orders = Order::where('user_id',auth()->user()->id)->with('address','orderItems','user','orderItems.product','orderItems.variation')->latest()->get();
        }
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

    public function orderSellerList(Request $request)
    {
        $data=[];
        $orders = Order::where('createBy',auth()->user()->id)->with('address','orderItems','user','orderItems.product','orderItems.variation')->latest()->get();
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


    public function orderDriverList(Request $request)
    {
        $data=[];

        $order = Order::where('driver',auth()->user()->id)->with('address','orderItems','user','orderItems.product','orderItems.variation')->latest();
        if(isset($request->type)){
            $order->where('status',$request->type);
        }
        $orders = $order->get();
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

    public function orderItemList(Request $request)
    {
        $orders = Order::where('id',$request->order_id)->with('address','orderItems','user','orderItems.product','orderItems.variation')->first();
        if($orders){
            return response([
                'message' => 'Order Item  List get successfully',
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

    public function paymentUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required',
            'payment_type' => 'required',
            'order_id' => 'required',
            'amount' => 'required',
            'tax' => 'required',
            'transaction_id' => 'required',
           ],[
            'payment_status.required'=>'Address Id is required',
            'payment_type.required'=>'Payment type is required',
            'order_id.required'=>'Payment Status is required',
            'transaction_id.required'=>'Payment Status is required',
            'amount.required'=>'Amount is required',
            'tax.required'=>'Tax is required',
             ]
            );

            if ($validator->fails()) {
                return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
            }

            $order = Order::find($request->order_id);

            if($order){
                $order->payment_type = $request->payment_type;
                $order->payment_status = $request->payment_status;
                $order->transaction_id = $request->transaction_id;
                $order->save();

                $payment = new Payment;
                $payment->order_id = $order->id;
                $payment->transaction_id = $order->transaction_id;
                $payment->provider_reference_id = auth()->user()->id;
                if(isset($request->comment)){
                    $payment->response_msg = $request->comment;
                }
                $payment->amount = $request->amount;
                $payment->tax = $request->tax;
                $payment->payment_method = $order->payment_type;
                $payment->payment_status = $order->payment_status;
                $payment->save();
                $data = [
                    'title' => "Your order payment  status has been updated order #".$order->order_number,
                    'description' => 'Your payment Rs.' .$payment->amount.' has been paid ',
                    'type' => 'payment',
                    'type_id' => $order->id,
                    'image' => null
                ];
                    send_push_notif_to_device($order->user_id, $data);
                    if($order->createBy){
                        send_push_notif_to_device($order->createBy, $data);
                    }
                    if(auth()->user()->id != $order->user_id){
                        $data2 = [
                            'title' => "Your payment   has been paid",
                            'description' => 'Your payment Rs.' .$payment->amount.' has been paid ',
                            'type' => 'payment',
                            'type_id' => $order->id,
                            'image' => null
                        ];
                            send_push_notif_to_device(auth()->user()->id, $data2);
                    }

                return response([
                    'message' => 'Payment Status changed',
                    'success' => true,
                    'data' => null
                ], 200);
            }
    }


    public function orderPlace(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
            'payment_type' => 'required',
            'payment_status' => 'required',
           ],[
            'address_id.required'=>'Address Id is required',
            'payment_type.required'=>'Payment type is required',
            'payment_status.required'=>'Payment Status is required',
             ]
            );

            if ($validator->fails()) {
                return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
            }
        $cartItems = Cart::where('user_id', auth()->user()->id)->with('product', 'variation')->get();
        if(count($cartItems)>0){
            foreach($cartItems as $cart){
                if((int)$cart->variation->stock < (int)$cart->quantity){
                    return response([
                        'message' => 'Your Item Out of stock',
                        'success' => false,
                        'data' => null
                    ], 400);
                }
            }
            $order = new Order;
            if(isset($request->user_id) && auth()->user()->type == 'seller'){
                $order->user_id = $request->user_id;
                $order->createBy = auth()->user()->id;
            }else{
                $order->user_id = auth()->user()->id;
            }

            $order->order_number = 'ORDER'.rand(1111,9999).date('YmdH');
            $order->total_amount = cartTotal();
            $order->tax = taxTotal();
            $order->shipping_address = $request->address_id;
            $order->payment_type = $request->payment_type;
            $order->payment_status = $request->payment_status;
            $order->save();
            if($order){
                foreach($cartItems as $cart){
                    $item =  new OrderItem;
                    $item->order_id = $order->id;
                    $item->product_id = $cart->product_id;
                    $item->variation_id = $cart->variation_id;
                    $item->quantity = $cart->quantity;
                    $item->total_amount = $cart->quantity*optional($cart->variation)->price;
                    $item->price = optional($cart->variation)->price;
                    $item->save();
                           $variation = VariationProduct::find($cart->variation_id);
                           if($variation){
                            $variation->stock = $variation->stock - $cart->quantity;
                            $variation->save();
                           }
                           $cart->delete();
                }
                $data = [
                    'title' => "Your order placed successfully",
                    'description' => 'Thank you for using our application',
                    'type' => 'order',
                    'type_id' => $order->id,
                    'image' => null
                ];
                    send_push_notif_to_device($order->user_id, $data);
                    if($order->createBy){
                        send_push_notif_to_device($order->createBy, $data);
                    }
                // $this->createInvoice($order);
                return response([
                    'message' => 'Order Successfully!',
                    'success' => true,
                    'data' => $order->id
                ],200);

            }else{
                return response([
                    'message' => 'Something went wrong!',
                    'success' => false,
                    'data' => null
                ], 400);
            }
        }else{
            return response([
                'message' => 'Cart empty!',
                'success' => false,
                'data' => null
            ], 404);
        }
    }



    public function deliveryStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'status' => 'required'
           ],[
            'order_id.required'=>'Order Id is required',
            'status.required'=>'Status  is required',
             ]
            );

            if ($validator->fails()) {
                return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
            }

           $status = new DeliveryStatus;
           $status->order_id = $request->order_id;
           $status->driver_id = auth()->user()->id;
           $status->status = $request->status;
           if(isset($request->address)){
            $status->address = $request->address;
           }
           if(isset($request->comment)){
            $status->comment = $request->comment;
           }
           $status->save();
           $order = Order::find($request->order_id);
           $order->driver_status = $request->status;
           $order->save();
           $data = [
            'title' => "Delivery status has been updated order #".$order->order_number,
            'description' => 'Thank you for using our application' ,
            'type' => 'order',
            'type_id' => $order->id,
            'image' => null
        ];
            send_push_notif_to_device($order->user_id, $data);
            if($order->createBy){
                send_push_notif_to_device($order->createBy, $data);
            }
           return response([
            'message' => 'Status Update SuccessFully!',
            'success' => true,
            'data' => null
        ],200);


    }



    public function deliveryImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'image' => 'required',
            'address' => 'required',
           ],[
            'order_id.required'=>'Order Id is required',
            'image.required'=>'Image  is required',
            'address.required'=>'Address  is required',
             ]
            );

            if ($validator->fails()) {
                return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
            }

           $status = new DeliveryImage;
           $status->order_id = $request->order_id;
           $status->driver_id = auth()->user()->id;
           $status->address = $request->address;
           if ($request->hasFile('image')) {
            $status->image = $request->file('image')->store('images/delivery','public');
        }
           if(isset($request->status)){
            $status->status = $request->status;
           }
           if(isset($request->address2)){
            $status->address2 = $request->address2;
           }
           $status->save();
           return response([
            'message' => 'Image Update SuccessFully!',
            'success' => true,
            'data' => null
        ],200);


    }


    public function orderByStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
           ],[
            'order_id.required'=>'Order Id is required',
             ]
            );

            if ($validator->fails()) {
                return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
            }

            $status= DeliveryStatus::where('order_id',$request->order_id)->with('user')->latest()->get();

            return response([
                'message' => 'Status Get Successfully!',
                'success' => true,
                'data' => $status
            ],200);

        }


        public function orderByPayment(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'order_id' => 'required',
               ],[
                'order_id.required'=>'Order Id is required',
                 ]
                );

                if ($validator->fails()) {
                    return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
                }

                $status= Payment::where('order_id',$request->order_id)->with('user')->latest()->get();

                return response([
                    'message' => 'Payment Get Successfully!',
                    'success' => true,
                    'data' => $status
                ],200);

            }



            public function orderByImage(Request $request)
            {
                $validator = Validator::make($request->all(), [
                    'order_id' => 'required',
                   ],[
                    'order_id.required'=>'Order Id is required',
                     ]
                    );

                    if ($validator->fails()) {
                        return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
                    }

                    $status= DeliveryImage::where('order_id',$request->order_id)->with('user')->latest()->get();

                    return response([
                        'message' => 'Image Get Successfully!',
                        'success' => true,
                        'data' => $status
                    ],200);

                }


}



