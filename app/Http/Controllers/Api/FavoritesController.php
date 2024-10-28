<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavoritesController extends Controller
{

    public function favoriteList()
    {
        $favorites = Favorite::where('user_id', auth()->user()->id)->with('product')->get();
        if(count($favorites)>0){
            return response([
                'message' => 'Favorite List get successfully',
                'success' => true,
                'data' => $favorites
            ], 200);
        }else{
            return response([
                'message' => 'Favorite Empty!',
                'success' => false,
                'data' => NULL
            ], 404);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function addFavorite(Request $request)
    {
       // Validate incoming request data
       $validator = Validator::make($request->all(), [
        'product_id' => 'required|exists:products,id',
       ],[
        'product_id.required'=>'Product Id is required',
        'product_id.exists'=>'Product is not found',
         ]
        );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
        }

        $favorite = Favorite::where(['product_id'=>$request->product_id, 'user_id'=>auth()->user()->id])->first();


        if($favorite){
            Favorite::destroy($favorite->id);
             return response()->json([
                'message' => 'Remove to Favorite successfully!',
                'data' => null,
                'success' => true,
            ], 200);
        }else{
            $favorite = new Favorite;
            $favorite->product_id = $request->product_id;
            $favorite->user_id = auth()->user()->id;
            $favorite->save();
             return response()->json([
                'message' => 'Add to Favorite successfully!',
                'data' => null,
                'success' => true,
            ], 200);
        }

    }




}
