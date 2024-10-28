<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\AssignOrder;
use App\Models\DeliveryStatus;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    // List orders (API and web)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function($row){
                 return $row->created_at->format('d M Y h:i a');
                })
                ->addColumn('create', function($row){

                    return $row->createBy ? 'Seller' : 'Customer';
                   })
                   ->addColumn('due_amount',function($row){
                    return $row->total_amount - $row->payment->sum('amount');
                   })
                ->addColumn('status', function($row){
                    $status = '<select name="order_status" onchange="orderStatusChange(this.value, '.$row->id.', \'status\')">
                    <option value="pending" '.($row->status == 'pending' ? 'selected' : '').'>Pending</option>
                    <option value="confirm" '.($row->status == 'confirm' ? 'selected' : '').'>Confirm</option>
                    <option value="delivered" '.($row->status == 'delivered' ? 'selected' : '').'>Delivered</option>
                    <option value="cancelled" '.($row->status == 'cancelled' ? 'selected' : '').'>Cancelled</option>
                </select>';
                return $status;
                                    })
                                    ->addColumn('payment_status', function($row){
                                        $status = '<select name="order_status" onchange="orderStatusChange(this.value, '.$row->id.', \'payment_status\')">
                                        <option value="paid" '.($row->payment_status == 'paid' ? 'selected' : '').'>Paid</option>
                                        <option value="unpaid" '.($row->payment_status == 'unpaid' ? 'selected' : '').'>Unpaid</option>
                                    </select>';
                                    return $status;
                                                        })
                ->addColumn('actions', function($row){
                    $editUrl = route('orders.show', $row->id);
                    return '
                        <a href="'.$editUrl.'" class="btn btn-sm btn-warning">view</a>

                    ';
                })
                ->rawColumns(['due_amount','create','created_at','actions','status','payment_status'])
                ->make(true);
        }

        return view('backend.orders.index');
    }

    public function show(Request $request,$id)
    {
        $order = Order::find($id);
        if ($request->ajax()) {
            $data = OrderItem::where('order_id',$id)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function($row){
                   return $row->created_at->format('d M Y h:i a');
                })
                ->addColumn('product', function($row){
                    return $row->product ? $row->product->name : '-';
                 })

                 ->addColumn('variation', function($row){
                    return $row->variation ? $row->variation->variation_name : '-';
                 })

                ->rawColumns(['created_at','product','variation'])
                ->make(true);
        }
             $users = User::where(['status'=>'active','type'=>'driver'])->get();
        return view('backend.orders.detail',compact('order','users'));


    }

    public function updateOrderStatus(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order) {
            $order->update([$request->column=>$request->status]);
                $status = new DeliveryStatus;
                $status->order_id = $request->order_id;
                $status->driver_id = 0;
                $status->status = $request->status;
                $status->save();
                if($request->column == 'status'){
                  $type = 'Order status';
                }elseif($request->column == 'payment_status'){
                    $type = 'Payment status';
                }elseif($request->column == 'payment_type'){
                    $type = 'Payment type';
                }else{
                    $type = 'Delivery status';
                }
                 $data = [
                'title' => $type ." has been updated ".ucFirst($request->status) ." order #".$order->order_number,
                'description' => 'Thank you for using our application' ,
                'type' => 'order',
                'type_id' => $order->id,
                'image' => null
            ];
                send_push_notif_to_device($order->user_id, $data);
                if($order->createBy){
                    send_push_notif_to_device($order->createBy, $data);
                }
            return response()->json(['success' => true, 'message' => 'Order status updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Order not found'], 404);
    }

    public function updateOrder(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order) {
            if(isset($request->pickup_date)){
                $order->pickup_date = $request->pickup_date;
                $data = [
                    'title' => "A pickup date has been assigned  order # ".$order->order_number,
                    'description' => 'Thank you for using our application' ,
                    'type' => 'order',
                    'type_id' => $request->order_id,
                    'image' => null
                ];
                send_push_notif_to_device($order->user_id, $data);
                if($order->driver && $order->driver>0){
                    send_push_notif_to_device($order->driver, $data);
                }

                if($order->createBy){
                    send_push_notif_to_device($order->createBy, $data);
                }

            }

            if(isset($request->delivery_date)){
                $order->delivery_date = $request->delivery_date;
                $data = [
                    'title' => "A delivery date has been assigned  order # ".$order->order_number,
                    'description' => 'Thank you for using our application' ,
                    'type' => 'order',
                    'type_id' => $request->order_id,
                    'image' => null
                ];
                send_push_notif_to_device($order->user_id, $data);
                if($order->driver && $order->driver>0){
                    send_push_notif_to_device($order->driver, $data);
                }

                if($order->createBy){
                    send_push_notif_to_device($order->createBy, $data);
                }

            }
            $order->save();
            Alert::toast('Successfully order update', 'success');
            return redirect()->back();
        }else{
            Alert::toast('Something went wrong', 'error');
            return redirect()->back();
        }
    }

    public function assignDriver(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order) {
                $status = AssignOrder::where(['order_id'=>$request->order_id])->update(['status'=>'cancel']);
                    $status = new AssignOrder;
                    $order->driver_status = 'pending';
                    $order->save();
                    $status->order_id = $request->order_id;
                    $status->driver_id = $request->id;
                    $status->status = 'pending';
                    $status->save();
                    $data = [
                        'title' => "You have been assigned a new order # ".$order->order_number,
                        'description' => 'Thank you for using our application' ,
                        'type' => 'order',
                        'type_id' => $request->order_id,
                        'image' => null
                    ];
                        send_push_notif_to_device($request->id, $data);
            return response()->json(['success' => true, 'message' => 'Order Assign to driver successfully']);
        }
        return response()->json(['success' => false, 'message' => 'Order not found'], 404);
    }

    public function paymentDetail(Request $request)
    {
        $payments = Payment::where('order_id',$request->order_id)->latest()->get();
        $html = '';
        if(count($payments)>0){
            foreach($payments as $key=>$payment){
                $i = $key+1;
                $html .= ' <tr>
                        <th>'.$i.'</th>
                        <th>'.$payment->amount.'</th>
                        <th>'.$payment->transaction_id.'</th>
                        <th>'.optional($payment->user)->name .'</th>
                        <th>'.$payment->created_at->format('d F Y').'</th>
                    </tr>';
            }
        }else{
            $html .= ' <tr><th colspan="5" class="text-center"
            >No Data Found</th></tr>';
        }

        echo $html;

    }


    public function transaction(Request $request){
        if ($request->ajax()) {
            $payments = Payment::select('*')->latest();
            return DataTables::of($payments)
            ->addIndexColumn()
            ->addColumn('created_at', function($row){
               return $row->created_at->format('d M Y h:i a');
            })
            ->addColumn('payby', function($row){
                return $row->user ? $row->user->name : '-';
             })

             ->addColumn('typeby', function($row){
                return $row->user ? ucwords($row->user->type) : '-';
             })
             ->addColumn('order_number', function($row){
                return $row->order ? $row->order->order_number : '-';
             })

             ->addColumn('total_amount', function($row){
                return $row->amount ? $row->amount.' Rs' : '-';
             })

             ->addColumn('typeby', function($row){
                return $row->user ? ucwords($row->user->type) : '-';
             })
             ->rawColumns(['created_at','payby','typeby','order_number', 'total_amount'])
                ->make(true);
        }
        return view('backend.orders.transaction');
    }


}
