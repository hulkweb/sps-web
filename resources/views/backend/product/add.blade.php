@extends('backend.layouts.master')
@section('title', 'Add Product')
@section('content')
    <div class="container mt-3">
        <!-- Product Form -->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <h3 class="text-light">Add Product</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Product Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter product name"
                                name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="category_id">Category</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value='' selected disabled>...Select Category...</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="subcategory_id">Subcategory</label>
                            <select class="form-control" id="subcategory_id" name="subcategory_id" required>
                                <option value='' selected disabled>...Select Subcategory...</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" id="price" placeholder="Enter product price"
                                name="price" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock">Stock</label>
                            <input type="number" class="form-control" id="stock" placeholder="Enter stock"
                                name="stock" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="discount_type">Discount Type</label>
                            <select class="form-control" id="discount_type" name="discount_type">
                                <option value="" selected>None</option>
                                <option value="percentage">Percentage</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="product_type">Product Type</label>
                            <select class="form-control" id="product_type" name="product_type" required>
                                <option value='' selected disabled>...Select Variation Type...</option>
                                <option value='single' selected>Single</option>
                                <option value='variation' >Variation</option>

                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="discount_value">Discount Value</label>
                            <input type="number" class="form-control" id="discount_value"
                                placeholder="Enter discount value" name="discount_value">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="multi_image">Product Multi Image</label>

                            <input required type="file" id="multi_image" name="multi_image[]" class="form-control"
                                multiple>

                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" placeholder="Enter description" name="description" rows="3"
                                required></textarea>
                        </div>

                        <!-- Product Variations Section -->
                        <div class="col-md-12 " id="variation-section">
                            <h4>Product Variations </h4>
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
                            <div id="variation-container">
                                <!-- Initial Variation Row -->
                                <div class="variation-row">
                                    <div class="row mt-2">
                                        <div class="col-md-2 mt-2">
                                            <input type="text" class="form-control" name="variation_name[]"
                                                placeholder="Variation Name" >
                                        </div>

                                        <div class="col-md-2 mt-2">
                                            <select name="type[]" class="form-control typeSelect" >
                                                <option value="">Select Type</option>
                                                @foreach ($types as $type)
                                                 <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <select name="value[]" class="form-control valueSelect hy">
                                                <option value="">Select Value</option>
                                                @foreach ($values as $value)
                                                 <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <input type="number" class="form-control" name="variation_price[]"
                                                placeholder="Price" >
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <input type="number" class="form-control" name="variation_stock[]"
                                                placeholder="Stock" >
                                        </div>


                                        <div class="col-md-2 mt-2">
                                            <input type="file"   class="form-control" name="variation_image[0][]" multiple>
                                        </div>

                                        <div class="col-md-1 mt-2">
                                            <button type="button" class="btn btn-danger remove-variation">-</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success mt-3" id="add-variation">Add Variation</button>
                        </div>

                        <div class="col-md-12 mt-3">
                            <button class="btn btn-primary" type="submit">Save Product</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            let variationIndex = $('.variation-row').length;
            $('#variation-section').hide();
            // Fetch subcategories when a category is selected
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


            // Toggle variation section based on product type selection
            $('#product_type').on('change', function() {
                let productType = $(this).val();
                if (productType === 'variation') {
                    $('#variation-section').show();
                } else {
                    $('#variation-section').hide();
                }
            });

            // Add more variations
            $('#add-variation').on('click', function() {
                let newVariation = `
                    <div class="variation-row mt-3">
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
