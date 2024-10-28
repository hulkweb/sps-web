<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Charge;
use App\Models\VariationProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function cartList()
    {
       
        $cartItems = Cart::where('user_id', auth()->user()->id)->with('product', 'variation')->get();
        if(count($cartItems)>0){
            return response([
                'message' => 'Cart List get successfully',
                'success' => true,
                'data' => $cartItems
            ], 200);
        }else{
            return response([
                'message' => 'Cart Empty!',
                'success' => false,
                'data' => NULL
            ], 404);
        }

    }


    public function chargeList()
    {
        $charges = Charge::where(['status'=>1])->get();
        if(count($charges)>0){
            return response([
                'message' => 'Charge List get successfully',
                'success' => true,
                'data' => $charges
            ], 200);
        }else{
            return response([
                'message' => 'Charge Empty!',
                'success' => false,
                'data' => NULL
            ], 404);
        }

    }
    /**
     * Show the form for creating a new resource.
     */
    public function addCart(Request $request)
    {
       // Validate incoming request data
       $validator = Validator::make($request->all(), [
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required',
        'variation_id'=> 'required'
       ],[
        'product_id.required'=>'Product Id is required',
        'quantity.required'=>'Quantity is required',
        'variation_id.required'=>'Variation is required',
         ]
        );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
        }
         $product = VariationProduct::find($request->variation_id);
        if(!$product && $product->stock == 0 && $product->stock < $request->quantity){
            return response()->json([
                'message' => 'Stock not available!',
                'data' => null,
                'success' => false,
            ], 400);
        }
        $cart = Cart::updateOrCreate([
            'user_id' => auth()->user()->id,
            'product_id' => $request->product_id,
            'variation_id' => $request->variation_id
        ], [
            'quantity' => DB::raw("quantity + $request->quantity")
        ]);

        if($cart){
            return response()->json([
                'message' => 'Add to Cart successfully!',
                'data' => $cart,
                'success' => true,
            ], 200);
        }else{
            return response()->json([
                'message' => 'Something Wen wrong!',
                'data' => null,
                'success' => false,
            ], 400);
        }

    }



    public function removeCart(Request $request)
    {
       // Validate incoming request data
       $validator = Validator::make($request->all(), [
        'cart_id' => 'required',
       ],[
        'cart_id.required'=>'Cart Id is required',
         ]
        );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
        }
        $card=Cart::find($request->cart_id);
		if($card!=null){
			 Cart::destroy($request->cart_id);
             return response()->json([
                'message' => 'Remove to Cart successfully!',
                'data' => null,
                'success' => true,
            ], 200);
		}else{
            return response()->json([
                'message' => 'Something Went wrong!',
                'data' => null,
                'success' => false,
            ], 400);
        }
    }

    public function cartSummary(Request $request){
        $cartItems = Cart::where('user_id', auth()->user()->id)->with('product', 'variation')->get();
        $data = [];
        $subtotal = 0;
        $total = 0;
        $flattax = 0;
        $percenttax = 0;
        if(count($cartItems)>0){
            $taxes = Charge::where('status',1)->get();

            foreach($cartItems as $cart){
               $subtotal +=  (int)$cart->quantity * (float)optional($cart->variation)->price;
            }

            foreach($taxes as $tax){
                if($tax->type == 'flat'){
                    $flattax +=  (float)$tax->value;
                }else{
                    if($tax->value != 0){
                        $percenttax +=  ($subtotal*(float)$tax->value)/100;
                    }
                }
             }
             $alltax = $percenttax + $flattax;
          $data['subtotal'] = cartSubTotal();
          $data['total'] = cartTotal();
          $data['tax'] = taxTotal();

          return response([
            'message' => 'Cart Summary!',
            'success' => true,
            'data' => $data
        ], 200);

        }else{
            return response([
                'message' => 'Cart Empty!',
                'success' => false,
                'data' => NULL
            ], 404);
        }
    }

    public function changeQuantity(Request $request)
    {
        $cart = Cart::find($request->cart_id);
        if ($cart != null) {

                $cart->update([
                    'quantity' => $request->quantity
                ]);
                return response()->json([
                    'message' => 'Quantity update successfully!',
                    'data' => null,
                    'success' => true,
                ], 200);

        }

        return response()->json([
            'message' => 'Something Went wrong!',
            'data' => null,
            'success' => false,
        ], 400);
    }


}
