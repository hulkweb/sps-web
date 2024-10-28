@extends('backend.layouts.master')
@section('title', 'Product')
@section('content')
    <div class="container mt-3">


        <!-- Products Table -->
        <div class="card">
            <div class="card-header bg-success d-flex justify-content-between">
                <h3 class="text-light">All Products</h3>
                <a href="{{ route('product.create') }}" class="btn btn-info">Add Product</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="product-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Category</th>
                                <th>Subcategory</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Popular</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

   
<script>
    $(document).ready(function() {
        $('#product-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('product.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'image', name: 'image' },
                { data: 'category', name: 'category' },
                { data: 'subcategory', name: 'subcategory' },
                { data: 'price', name: 'price' },
                { data:'status',name:'status'},
                { data:'popular',name:'popular'},
                { data: 'actions', name: 'actions', orderable: false, searchable: false },
            ]
        });
    });
</script>
@endsection
