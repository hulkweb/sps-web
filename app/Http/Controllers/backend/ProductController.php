<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\SubCategory;
use App\Models\VariationType;
use App\Models\VariationValue;
use App\Models\VariationProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{


    public function index(Request $request)
    {

        // Handle Ajax or API request
        if ($request->ajax() || $request->expectsJson()) {
            $products = Product::with('category', 'subcategory')->select('products.*')->latest();

            // Use DataTables for web-based DataTables requests
            if ($request->ajax()) {
                return DataTables::of($products)

                    ->addColumn('category', function ($product) {
                        return $product->category ? $product->category->name : 'N/A';
                    })
                    ->addColumn('subcategory', function ($product) {
                        return $product->subcategory ? $product->subcategory->name : 'N/A';
                    })
                    ->addColumn('image', function ($product) {
                        $image = $product->image ? uploads($product->image) : 'No image';
                        return '<img src="'.$image.'" width="50" height="50">';
                    })

                    ->addColumn('status', function($product){
                        $status = '<div class="form-check form-switch form-check-md mb-0">
                        <input class="form-check-input" id="tab_'.$product->id.'" type="checkbox" data-status="'.$product->status.'" onclick="Toggle('. $product->id .','. "'products'" .')" ';
                        if($product->status == 1){
                            $status .='checked';
                        }

                        $status .='> </div>';
                        return $status;
                                    })
                    ->addColumn('popular', function($product){
                        $popular = '<div class="form-check form-switch form-check-md mb-0">
                        <input class="form-check-input" id="tab1_'.$product->id.'" type="checkbox" data-status="'.$product->popular.'" onclick="Toggle('. $product->id .','. "'products','popular'" .')" ';
                        if($product->popular == 1){
                            $popular .='checked';
                        }

                        $popular .='> </div>';
                        return $popular;
                                    })
                    ->addColumn('actions', function ($product) {
                        return '<a href="'.route('product.edit', $product->id).'" class="btn btn-sm btn-primary">Edit</a>
                               <form action="'.route('product.destroy', $product->id).'" method="POST" style="display:inline-block;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>';          })
                    ->rawColumns(['image','popular','status','actions'])
                    ->make(true);
            }

            // Return JSON response for API requests

        }



        return view('backend.product.index');
    }

    public function create()
    {
         // Handle the web-based request
            $categories    = Category::where('status',1)->get();
            $subcategories = SubCategory::where('status',1)->get();
            $types = VariationType::where('status',1)->get();
            $values = VariationValue::where('status',1)->get();
            return view('backend.product.add', compact('categories', 'subcategories','types', 'values'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'price' => 'required|numeric',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount_value' => 'nullable|numeric',
            'image' => 'nullable|image',
            'description' => 'required|string',
            'variation_name.*' => 'nullable|string',
            'type.*' => 'nullable|integer',
            'value.*' => 'nullable|integer',
            'variation_price.*' => 'nullable|numeric',
            'margin.*' => 'nullable|numeric',
            'variation_stock.*' => 'nullable|integer',
          'variation_image.*' => 'nullable|image',
        ]);

        // If validation fails, return response for both API and Web
        if ($validator->fails()) {

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        try {
           $product = new Product;

               $product->name= $request->name;
               $product->category_id= $request->category_id;
               $product->subcategory_id= $request->subcategory_id;
               $product->price= $request->price;
               $product->stock= 0;
               $product->discount_type= $request->discount_type;
               $product->discount_value= $request->discount_value;
               $product->description= $request->description;
               $product->product_type= $request->product_type;
               $product->image= $request->file('image')->store('images/product','public');
               if($request->hasfile('multi_image'))
            {

                foreach($request->file('multi_image') as $key => $file)
                {
                    $path = $file->store('images/product','public');
                    $insert[$key] = $path;
                }
                $add_img=json_encode($insert);
            }
              $product->multi_image = $add_img;
               $product->save();
            $var_arr =array();

            if($product->product_type == 'variation'){
                foreach ($request->variation_name as $key => $variationName) {
                    if (!empty($variationName)) {

                        $var_arr = new VariationProduct;
                           $var_arr->product_id = $product->id;
                           $var_arr->variation_name = $variationName;
                           $var_arr->type = $request->type[$key] ?? null;
                           $var_arr->value = $request->value[$key] ?? null;
                           $var_arr->price = $request->variation_price[$key] ?? null;
                           $var_arr->stock = $request->variation_stock[$key] ?? null;
                           if ($request->hasfile("variation_image.$key")) {
                            $uploadedImages = $request->file("variation_image.$key");
                            $v_insert = [];

                            foreach ($uploadedImages as $v_key => $v_file) {
                                $v_path = $v_file->store('images/product', 'public');
                                $v_insert[$v_key] = $v_path;
                            }
                            $var_arr->image = json_encode($v_insert);
                        }
                          $var_arr->save();
                    }
                }
            }





            // Web response
            Alert::toast('Product created successfully!', 'success');
            return redirect()->back();

        } catch (\Exception $e) {
                Alert::toast('Product creation failed: ' . $e->getMessage(), 'error');
                return redirect()->back()->withInput();

        }
    }




    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        // dd($product->category_id);
        $categories = Category::where('status',1)->get();
        $subcategories = SubCategory::where(['category_id'=> $product->category_id, 'status'=>1])->get();
        $types = VariationType::where('status',1)->get();
        $values = VariationValue::where(['status'=>1])->get();
        $variations = $product->variations;

        return view('backend.product.edit', compact('product', 'categories', 'subcategories', 'variations','types', 'values'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'price' => 'required|numeric',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount_value' => 'nullable|numeric',
            'image' => 'nullable|image',
            'description' => 'required|string',
            'variation_name.*' => 'nullable|string',
            'type.*' => 'nullable|integer',
            'value.*' => 'nullable|integer',
            'variation_price.*' => 'nullable|numeric',
            'margin.*' => 'nullable|numeric',
            'variation_stock.*' => 'nullable|integer',
        ]);
// dd($request->variation_price);
        // If validation fails, return response for both API and Web
        if ($validator->fails()) {

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();

        }

        try {

            $old_img=[];
            $product = Product::findOrFail($id);
            $product->name= $request->name;
            $product->category_id= $request->category_id;
            $product->subcategory_id= $request->subcategory_id;
            $product->price= $request->price;
            $product->stock= 0;
            $product->discount_type= $request->discount_type;
            $product->discount_value= $request->discount_value;
            $product->description= $request->description;
            $product->product_type= $request->product_type;


            if ($request->hasFile('image')) {
                @unlink(uploads($product->image));
                $product->image = $request->file('image')->store('images/product','public');
            }
            if($request->hasfile('multi_image'))
            {
                //@unlink('storage/app/'.$product->multi_image);
                foreach($request->file('multi_image') as $key => $file)
                {
                    $path = $file->store('images/product','public');
                    $insert[$key] = $path;
                }
                if($product->multi_image){
                    $old_img = json_decode($product->multi_image);

                }
                $new_arr = array_merge($old_img,$insert);
                $product->multi_image =json_encode($new_arr);
            }
            $product->save();


            if($product->product_type == 'variation'){



                VariationProduct::where('product_id', $product->id)->whereNotIn('id', $request->variation_id)->delete();

                // Step 2: Loop through the new variations and add them
                foreach ($request->variation_name as $key => $variationName) {
                    if (!empty($variationName)) {
                        if($request->variation_id[$key] == 0){
                            $var_arr = new VariationProduct;
                            $var_arr->product_id = $product->id;
                        }else{
                            $var_arr = VariationProduct::find($request->variation_id[$key]);
                        }

                           $var_arr->variation_name = $variationName ?? null;
                           $var_arr->type = $request->type[$key] ?? null;
                           $var_arr->value = $request->value[$key] ?? null;
                           $var_arr->price = $request->variation_price[$key] ?? null;
                           $var_arr->stock = $request->variation_stock[$key] ?? null;
                           $v_insert = [];
                           if ($request->hasfile("variation_image.$key")) {
                            $uploadedImages = $request->file("variation_image.$key");


                                foreach ($uploadedImages as $v_key => $v_file) {
                                    $v_path = $v_file->store('images/product', 'public');
                                    $v_insert[$v_key] = $v_path;
                                }

                            }
                            $old_image = [];
                            if($var_arr->image){
                                $old_img = json_decode($var_arr->image);
                            }
                            $new_arr = array_merge($old_img,$v_insert);
                            $var_arr->image =json_encode($new_arr);
                        $var_arr->save();
                    }
                }
            }


            // Web response
            Alert::toast('Product updated successfully!', 'success');
            return redirect()->back();

        } catch (\Exception $e) {

                Alert::toast('Product update failed: ' . $e->getMessage(), 'error');
                return redirect()->back()->withInput();

        }
    }

 // Product Image Delete
 public function destroyImage($id,$multi_image)
 {
    try {
     $product= Product::find($id);
     $multi_images = json_decode($product->multi_image);

@unlink(uploads($multi_images[$multi_image]));
     unset($multi_images[$multi_image]);

     $multi_images = array_values($multi_images);
     $product->multi_image = json_encode($multi_images);
     $product->save();
     Alert::toast('Product Image deleted successfully!', 'success');
     return redirect()->back();
    }

    catch (\Exception $e) {

        Alert::toast('Something went wrong. Could not delete customer', 'error');
        return redirect()->back();
    }
 }


  public function variationdestroyImage($id,$multi_image)
  {
     try {
      $product= VariationProduct::find($id);
      $multi_images = json_decode($product->image);

 @unlink(uploads($multi_images[$multi_image]));
      unset($multi_images[$multi_image]);

      $multi_images = array_values($multi_images);
      $product->image = json_encode($multi_images);
      $product->save();
      Alert::toast('Variation Image deleted successfully!', 'success');
      return redirect()->back();
     }

     catch (\Exception $e) {

         Alert::toast('Something went wrong. Could not deleted', 'error');
         return redirect()->back();
     }
  }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the category by ID
            $product = Product::findOrFail($id);
@unlink(uploads($product->image));
            $images = json_decode($product->multi_image, true);
            if ($images && count($images)>0) {
                // Loop through the image paths and delete each one
                foreach ($images as $imagePath) {
@unlink(uploads($imagePath));
                }
            }
            // Delete the category
            $product->delete();
            VariationProduct::where('product_id',$id)->delete();


            // Success alert for web
            Alert::toast('Successfully deleted customer', 'success');
            return redirect()->back();

        } catch (\Exception $e) {

            Alert::toast('Something went wrong. Could not delete customer', 'error');
            return redirect()->back();
        }
    }

    public function getSubcategoriesByCategory(Request $request)
    {
        $subcategories = SubCategory::where(['category_id'=>$request->category_id, 'status'=>1])->get();
        return response()->json(['subcategories' => $subcategories]);
    }

    public function getValuesByType(Request $request){
        $values = Variationvalue::where(['variation_type_id'=>$request->type_id, 'status'=>1])->get();
        return response()->json(['values' => $values]);
    }



}
