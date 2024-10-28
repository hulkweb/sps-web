@extends('backend.layouts.master')
@section('title', 'Sales Person')
@section('content')
    <div class="container mt-3">
       

        <!-- Categories Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>All Sales Person</h3>
                <a href="{{ route('seller.create') }}" class="btn btn-info">Add Sale Person</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="category-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Customer</th>
                                <th>Order</th>
                                <th>Address</th>
                                <th>Image</th>
                                <th>Status</th>
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
            $('#category-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url("/seller") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'mobile', name: 'mobile' },
                    { data: 'customer', name: 'customer' },
                    { data: 'order', name: 'order' },
                    { data: 'address', name: 'address' },
                    {
                data: 'image',
                name: 'image',
                orderable: false,
                searchable: false,
                render: function(data) {
                    // Check if the data (image path) exists and is not empty
                    if (data) {
                        return data;
                    } else {
                        return 'No Image';
                    }
                    }},
                    { data:'status',name:'status'},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endsection
