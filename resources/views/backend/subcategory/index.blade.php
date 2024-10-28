@extends('backend.layouts.master')
@section('title', ' Sub Category')
@section('content')
    <div class="container mt-3">
       

        <!-- Categories Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>All Sub Categories</h3>
                <a href="{{ route('subcategory.create') }}" class="btn btn-info">Add Sub Category</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="subcategory-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Description</th>
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
            $('#subcategory-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url("/subcategory") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'category', name: 'category' },
                    { data: 'description', name: 'description' },
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
