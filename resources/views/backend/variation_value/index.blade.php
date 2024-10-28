@extends('backend.layouts.master')
@section('title', ' Variation Value')
@section('content')
    <div class="container mt-3">


        <!-- Categories Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>All Variation Values</h3>
                <a href="{{ route('variation_value.create') }}" class="btn btn-info">Add Variation Value</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="subcategory-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Variation Type</th>
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
                ajax: '{{ url("/variation_value") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'variation_type', name: 'variation_type' },
                    { data:'status',name:'status'},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endsection
