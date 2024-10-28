@extends('backend.layouts.master')
@section('title', 'Edit Product')
@section('content')
    <div class="container mt-3">
        <!-- Product Edit Form -->
        <div class="card mb-3">
            <div class="card-header bg-info">
                <h3 class="text-light">Edit Product</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('product.update', $product->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Product Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter product name"
                                name="name" value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="category_id">Category</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value='' disabled>...Select Category...</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="subcategory_id">Subcategory</label>
                            <select class="form-control" id="subcategory_id" name="subcategory_id" required>
                                <option value='' disabled>...Select Subcategory...</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}"
                                        {{ old('subcategory_id', $product->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" id="price" placeholder="Enter product price"
                                name="price" value="{{ old('price', $product->price) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="discount_type">Discount Type</label>
                            <select class="form-control" id="discount_type" name="discount_type">
                                <option value=""
                                    {{ old('discount_type', $product->discount_type) == '' ? 'selected' : '' }}>None
                                </option>
                                <option value="percentage"
                                    {{ old('discount_type', $product->discount_type) == 'percentage' ? 'selected' : '' }}>
                                    Percentage</option>
                                <option value="fixed"
                                    {{ old('discount_type', $product->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="discount_value">Discount Value</label>
                            <input type="number" class="form-control" id="discount_value"
                                placeholder="Enter discount value" name="discount_value"
                                value="{{ old('discount_value', $product->discount_value) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            @if ($product->image)
                                <img src="{{ uploads($product->image) }}" height="60px" width="60px" alt="Product Image"
                                    class="mt-2">
                            @endif
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="multi_image">Product Multi Image</label>
                            <input type="hidden" value='<?php if (isset($product->multi_image)) {
                                echo $product->multi_image;
                            } ?>' name="multpledocs">
                            <input @if (!isset($product)) required @endif type="file" id="multi_image"
                                name="multi_image[]" class="form-control" multiple>
                            <?php
                            if(isset($product->multi_image)){
                                $multi_images = json_decode($product->multi_image);
                                foreach($multi_images as $keyy => $img){
                            ?>
                            @if (isset($img))
                                <img src="{{ uploads($img) }}" alt="multi_image" class="img-thumbnail" width="10%">
                                <a href="{{ route('product.destroy_image', [$product->id, $keyy]) }}"
                                    class="btn btn-danger btn-sm">&times;</a>
                            @endif
                            <?php }
                            } ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="product_type">Product Type</label>
                            <select class="form-control" id="product_type" name="product_type" required>
                                <option value="single"
                                    {{ old('product_type', $product->product_type) == 'single' ? 'selected' : '' }}>Single
                                </option>
                                <option value="variation"
                                    {{ old('product_type', $product->product_type) == 'variation' ? 'selected' : '' }}>
                                    Variation</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" placeholder="Enter description" name="description" rows="3"
                                required>{{ old('description', $product->description) }}</textarea>
                        </div>

                        <!-- Product Variations Section -->
                        <div class="col-md-12" id="variation-section">
                            <h4>Product Variations</h4>
                            <div id="variation-container">
                                <div class="row">
                                    <div class="col-md-2">
                                        <h6>Variation name</h6>
                                    </div>
                                    <div class="col-md-2">
                                        <h6>Type</h6>
                                    </div>
                                    <div class="col-md-2">
                                        <h6>Value</h6>
                                    </div>
                                    <div class="col-md-2">
                                        <h6>Price</h6>
                                    </div>
                                    <div class="col-md-2">
                                        <h6>Stock</h6>
                                    </div>
                                    <div class="col-md-2">
                                        <h6>Image</h6>
                                    </div>
                                </div>
                                @if (count($product->variations) > 0)
                                    @foreach ($product->variations as $variation)
                                        <div class="variation-row mt-3">
                                            <input type="hidden" class="form-control" name="variation_id[]"
                                                value="{{ $variation->id }}">

                                            <div class="row">
                                                <div class="col-md-2 mt-2">
                                                    <input type="text" class="form-control" name="variation_name[]"
                                                        value="{{ old('variation_name.' . $loop->index, $variation->variation_name) }}"
                                                        placeholder="Variation Name">
                                                </div>

                                                <div class="col-md-2 mt-2">
                                                    <select class="form-control typeSelect" id="type" name="type[]" required>
                                                        <option value="">..Type</option>
                                                        @foreach ($types as $type)

                                                            <option value="{{ $type->id }}"
                                                                {{ old('type.' . $loop->index, $variation->type) == $type->id ? 'selected' : '' }}>
                                                                {{ $type->name }}</option>

                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2 mt-2">
                                                    <select class="form-control valueSelect" id="type" name="value[]" required>
                                                        <option value="">...Value</option>
                                                        @foreach ($values as $value)
                                                        @if($value->variation_type_id == $variation->type)
                                                            <option value="{{ $value->id }}"
                                                                {{ old('value.' . $loop->index, $variation->value) == $value->id ? 'selected' : '' }}>
                                                                {{ $value->name }}</option>
                                                                @endif
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2 mt-2">
                                                    <input type="number" class="form-control" name="variation_price[]"
                                                        value="{{ old('variation_price.' . $loop->index, $variation->price) }}"
                                                        placeholder="Price">
                                                </div>
                                                <div class="col-md-2 mt-2">
                                                    <input type="number" class="form-control" name="variation_stock[]"
                                                        value="{{ old('variation_stock.' . $loop->index, $variation->stock) }}"
                                                        placeholder="Stock">
                                                </div>
                                                <div class="col-md-2 mt-2">
                                                    <input type="file"  class="form-control"
                                                    name="variation_image[{{ $loop->index }}][]" multiple>
                                                </div>
                                              @if($variation->image && count(json_decode($variation->image))>0)
                                                <div class="col-md-6 mt-2">
                                                    @foreach (json_decode($variation->image) as $i=>$image)
                                                       <img src="{{ uploads($image) }}" alt="" style="height: 50px; width:50px;">
                                                       <a href="{{ route('variation.destroy_image', [$variation->id, $i]) }}"
                                                        class="btn btn-danger btn-sm">&times;</a>
                                                    @endforeach
                                                </div>
                                                @endif


                                                <div class="col-md-1 mt-2">
                                                    <button type="button"
                                                        class="btn btn-danger remove-variation">-</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                <div class="variation-row mt-3">
                                    <input type="hidden" class="form-control" name="variation_id[]"
                                    value="0">
                                    <div class="row mt-2">
                                        <div class="col-md-2 mt-2">
                                            <input type="text" class="form-control" name="variation_name[]"
                                                placeholder="Variation Name" required>
                                        </div>

                                        <div class="col-md-2 mt-2">
                                            <select name="type[]" class="form-control typeSelect" required>
                                                <option value="">Select Type</option>
                                                @foreach ($types as $type)
                                                 <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <select name="value[]" class="form-control valueSelect" required>
                                                <option value="">Select Value</option>
                                                @foreach ($values as $value)
                                                 <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <input type="number" class="form-control" name="variation_price[]"
                                                placeholder="Price" required>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <input type="number" class="form-control" name="variation_stock[]"
                                                placeholder="Stock" required>
                                        </div>


                                        <div class="col-md-2 mt-2">
                                            <input type="file" required  class="form-control" name="variation_image[0][]" multiple>
                                        </div>

                                        <div class="col-md-1 mt-2">
                                            <button type="button" class="btn btn-danger remove-variation">-</button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-success mt-3" id="add-variation">Add Variation</button>
                        </div>

                        <div class="col-md-12 mt-3">
                            <button class="btn btn-primary" type="submit">Update Product</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            let variationIndex = $('.variation-row').length;

            let productType = "{{ $product->product_type }}";
            if (productType === 'variation') {
                $('#variation-section').show();
            } else {
                $('#variation-section').hide();
            }

            $('#product_type').on('change', function() {
                let productType = $(this).val();
                if (productType === 'variation') {
                    $('#variation-section').show();
                } else {
                    $('#variation-section').hide();
                }
            });

            $('#category_id').on('change', function() {
                let categoryId = $(this).val();
                $.ajax({
                    url: '{{ route('get_subcat') }}',
                    method: 'GET',
                    data: {
                        category_id: categoryId
                    },
                    success: function(response) {
                        let subcategories = response.subcategories;
                        let subcategorySelect = $('#subcategory_id');
                        subcategorySelect.empty();
                        subcategorySelect.append(
                            '<option value="" disabled selected>...Select Subcategory...</option>'
                        );
                        $.each(subcategories, function(key, subcategory) {
                            subcategorySelect.append('<option value="' + subcategory
                                .id + '">' + subcategory.name + '</option>');
                        });
                    }
                });
            });
            // Toggle variation section based on product type selection





            $(document).on('change', '.typeSelect', function() {
    let type_id = $(this).val();
    let valueselect = $(this).closest('.row').find('.valueSelect');

    $.ajax({
        url: "{{ route('get_value') }}",
        method: 'GET',
        data: {
            type_id: type_id
        },
        success: function(response) {
            if (response) {
                let values = response.values;
                valueselect.empty();
                valueselect.append('<option value="" disabled selected>...Select value...</option>');
                $.each(values, function(key, value) {
                    valueselect.append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            }
        },
        error: function() {
            alert('Failed to fetch values.');
        }
    });
});




            // Add more variations
            $('#add-variation').on('click', function() {
                let newVariation = `
                   <div class="variation-row mt-3">
                     <input type="hidden" class="form-control" name="variation_id[]"
                                    value="0">
                                    <div class="row mt-2">
                                        <div class="col-md-2 mt-2">
                                            <input type="text" class="form-control" name="variation_name[]"
                                                placeholder="Variation Name" required>
                                        </div>

                                        <div class="col-md-2 mt-2">
                                            <select name="type[]" class="form-control typeSelect" required>
                                                <option value="">Select Type</option>
                                                @foreach ($types as $type)
                                                 <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <select name="value[]" class="form-control valueSelect" required>
                                                <option value="">Select Value</option>
                                                @foreach ($values as $value)
                                                 <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <input type="number" class="form-control" name="variation_price[]"
                                                placeholder="Price" required>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <input type="number" class="form-control" name="variation_stock[]"
                                                placeholder="Stock" required>
                                        </div>


                                        <div class="col-md-2 mt-2">
                                            <input type="file" required  class="form-control" name="variation_image[${variationIndex}][]" multiple>
                                        </div>

                                        <div class="col-md-1 mt-2">
                                            <button type="button" class="btn btn-danger remove-variation">-</button>
                                        </div>
                                    </div>
                                </div>`;
                $('#variation-container').append(newVariation);
                variationIndex++;
            });

            // Remove variation row
            $(document).on('click', '.remove-variation', function() {
                $(this).closest('.variation-row').remove();
            });
        });
    </script>
@endsection
