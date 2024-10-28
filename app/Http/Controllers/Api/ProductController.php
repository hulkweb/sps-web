<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\VariationProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function getCategory(Request $request)
    {
        $category = Category::where('status',1);
        if(isset($request->popular)){
            $category->where('popular',1);
        }
        $categories = $category->get();
        if(count($categories)>0){
            return response()->json([
                'message' => 'Get Categories successfully',
                'data' => $categories,
                'success' => true,
            ], 200);
        }else{
            return response()->json([
                'message' => 'Categories not found',
                'data' => null,
                'success' => false,
            ], 404);
        }

    }

    public function getSubCategory(Request $request)
    {
        $categories = SubCategory::where('status',1)->get();
        if(count($categories)>0){
            return response()->json([
                'message' => 'Get Sub Categories successfully',
                'data' => $categories,
                'success' => true,
            ], 200);
        }else{
            return response()->json([
                'message' => 'Sub Categories not found',
                'data' => null,
                'success' => false,
            ], 404);
        }

    }

    public function productDetail(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'product_id' => 'required',
            ],[
                'product_id.required'=>'Product Id is required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->all(), 'success' => false], 400);
        }

        $product = Product::with('variations')->findOrFail($request->product_id);
        if($product){
            $product->multi_image = $product->multi_image ? json_decode($product->multi_image) : null;
            $data = [];

                $related_products = Product::with('variations')->whereNot('id',$product->id)->where('subcategory_id',$product->subcategory_id)->get();
                    if(count($related_products)>0){
                        foreach($related_products as $key=>$rel){
                            $related_products[$key]->isCart = isCart($rel->id, auth()->user()->id);
                            $related_products[$key]->isFavorite = isFavorite($rel->id, auth()->user()->id);

                        }
                    }
                $product->isCart = isCart($product->id, auth()->user()->id);
                $product->isFavorite = isFavorite($product->id, auth()->user()->id);
                $data['product'] = $product;
                $data['related_products'] = $related_products;
            return response()->json([
                'message' => 'Get Product detail successfully',
                'data' => $data,
                'success' => true,
            ], 200);
        }else{
            return response()->json([
                'message' => 'Product not found',
                'data' => null,
                'success' => false,
            ], 404);
        }

    }


    public function getProduct(Request $request)
    {
        $products = Product::query()->where('products.status',1)->with('type','value','variations','category','subcategory');
        if(isset($request->popular)){
            $products->where('products.popular',1);
        }
        if(isset($request->category_id)){
            $products->where('products.category_id',$request->category_id);
        }

        if(isset($request->subcategory_id)){
            $products->where('products.subcategory_id',$request->subcategory_id);
        }

        if(isset($request->min_price) && isset($request->max_price)){
            $products->whereNot('products.price',0)->whereBetween('products.price',[$request->min_price, $request->max_price]);

        }

        // if(isset($request->stock)){
        //     $products->orderBy('variation_products.stock','Desc');

        // }

        if(isset($request->search)){
            $search = explode(" ",$request->search);
            $products->where(function($query) use ($search){
                $query->where('products.name', 'LIKE', '%'.$search[0].'%');
                foreach($search as $key  => $val){
                    if($key==0){
                        continue;
                    }
                    $query->orWhere('products.name', 'LIKE', '%'.$val.'%');
                }
            });
        }

        $product = $products->paginate(10);

        if(count($product)>0){

                foreach($product as $key=>$prod){
                       $product[$key]->multi_image = $prod->multi_image ? json_decode($prod->multi_image) : null;
                        $product[$key]->isCart = isCart($prod->id, auth()->user()->id);
                        $product[$key]->isFavorite = isFavorite($prod->id, auth()->user()->id);

                }


            return response()->json([
                'message' => 'Get Products successfully',
                'data' => $product,
                'success' => true,
            ], 200);
        }else{
            return response()->json([
                'message' => 'Product not found',
                'data' => null,
                'success' => false,
            ], 404);
        }

    }

}
