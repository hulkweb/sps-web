@extends('backend.layouts.master')
@section('title', 'Orders')
@section('content')
    <div class="container mt-3">
        <!-- Orders Table -->
        <div class="card">
            <div class="card-header">
                <h3>All Orders</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="orders-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Create By</th>
                                <th>Order Number</th>
                                <th>Total Amount</th>
                                <th>Due Amount</th>
                                <th>Total Tax</th>
                                <th>Payment Type</th>
                                <th>Payment Status</th>
                                <th>Status</th>
                                <th>Ordered At</th>
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
            $('#orders-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url("/orders") }}', // Replace this with your API route
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'create', name: 'create' },
                    { data: 'order_number', name: 'order_number' },
                    { data: 'total_amount', name: 'total_amount' },
                    { data: 'due_amount', name: 'due_amount' },
                    { data: 'tax', name: 'tax' },
                    { data: 'payment_type', name: 'payment_type' },
                    { data: 'payment_status', name: 'payment_status' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at' },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,

                    }
                ]
            });
        });
    </script>
@endsection
