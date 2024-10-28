@extends('backend.layouts.master')
@section('title', 'All Transactions')
@section('content')
    <div class="container mt-3">
        <!-- Orders Table -->
        <div class="card">
            <div class="card-header">
                <h3>All Transactions</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="orders-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pay By</th>
                                <th>User Type</th>
                                <th>Order Code</th>
                                <th>Total Amount</th>
                                <th>Tax</th>
                                <th>Gst</th>
                                <th>Payment Status</th>
                                <th>Payment Type</th>
                                <th>Message</th>
                                <th>Created At</th>
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
                ajax: '{{ url("/transaction") }}', // Replace this with your API route
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'payby', name: 'payby' },
                    { data: 'typeby', name: 'typeby' },
                    { data: 'order_number', name: 'order_number' },
                    { data: 'total_amount', name: 'total_amount' },
                    { data: 'tax', name: 'tax' },
                    { data: 'gst', name: 'gst' },
                    { data: 'payment_status', name: 'payment_status' },
                    { data: 'payment_method', name: 'payment_method' },
                    { data: 'response_msg', name: 'response_msg' },
                    { data: 'created_at', name: 'created_at' },

                ]
            });
        });
    </script>
@endsection
