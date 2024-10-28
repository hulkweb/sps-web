@extends('backend.layouts.master')
@section('title', 'Category')
@section('content')
    <div class="container mt-3">



        <!-- Categories Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>All Variation Types</h3>
                <a href="{{ route('variation_type.create') }}" class="btn btn-info">Add Variation Type</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="variation_type-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
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
            $('#variation_type-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url("/variation_type") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data:'status',name:'status'},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endsection
