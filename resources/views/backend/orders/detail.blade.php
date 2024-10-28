@extends('backend.layouts.master')
@section('title', 'Orders Detail')
@section('content')
    <div class="container mt-3">
        <!-- Orders Table -->
        <div class="card">
            <div class="card-header">
                <h3>Order  </h3>
                <div class="row">
                    <div class="col-md-6">
                      <h6><strong>Order Code :</strong> {{ $order->order_number }}</h6>
                      <h6><strong>Customer :</strong> {{ $order->address ? $order->address->name : '-' }}</h6>
                      <h6><strong>Contact :</strong> {{ $order->address ? $order->address->phone : '-' }}</h6>
                      <h6><strong>Address :</strong>  {{ $order->address ? $order->address->name.', '.$order->address->address.' '.$order->address->city.' '.$order->address->state.' '.$order->address->country.', '.$order->address->postal_code : ''}}</h6>
                      <h6><strong>Pickup Date :</strong> {{ $order->pickup_date ? $order->pickup_date : '-' }}</h6>
                      <h6><strong>Delivery Date :</strong> {{ $order->delivery_date ? $order->delivery_date : '-' }}</h6>
                      <h6><strong>Assign Delivery  :</strong> <a href="javascript:void(0)" data-bs-target="#assignModal" data-bs-toggle="modal" class="mx-2 btn btn-info">Assign </a>                    </h6>
                        <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog ">
                              <div class="modal-content bg-light">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Assign Delivery Boy</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('orders.update.date') }}" method="POST">
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    @csrf
                                <div class="modal-body">

                                    <select  onchange="orderAssignDriver(this.value, '{{ $order->id }}')" class="form-control">
                                        <option value="">Select Delivery Boy</option>
                                        @foreach ($users as $user)
                                          <option value="{{ $user->id }}" {{ $order->assign ? $order->assign->driver_id == $user->id ? 'selected' : '' : ''}}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                    <div class="form-group mt-3">
                                        <label for="">Pickup Date</label>
                                        <input type="date" name="pickup_date" class="form-control" value="{{$order->pickup_date}}">
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="">Delivery Date</label>
                                        <input type="date" name="delivery_date" class="form-control" value="{{$order->delivery_date}}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>

                              </div>
                            </div>
                          </div>


                    </div>
                    <div class="col-md-6">
                        <h5><strong>Order Status :</strong> @if($order->status == 'pending')<span class="badge bg-warning">Pending</span> @elseif($order->status == 'confirm') <span class="badge bg-success">Confirm</span> @elseif($order->status == 'delivered') <span class="badge bg-success">Delivered </span> @else <span class="badge bg-danger">Cancelled</span> @endif </h5>
                        <h5><strong>Payment Status :</strong> @if($order->payment_status == 'paid')<span class="badge bg-success">Paid</span> @elseif($order->payment_status == 'unpaid') <span class="badge bg-danger">Unpaid</span> @else <span class="badge bg-warning">{{ ucFirst($order->payment_status) }}</span> @endif </h5>

                        <h6><strong>Sub Total :</strong> Rs. {{ $order->total_amount - $order->tax }}</h6>
                        <h6><strong>Tax :</strong> Rs. {{ $order->tax }}</h6>
                        <h6 ><strong>Total Amount :</strong> <span class="text-success">Rs. {{ $order->total_amount }}</span></h6>
                        <h6 ><strong>Due Amount :</strong> <span class="text-warning">Rs. {{ $order->total_amount - $order->payment->sum('amount') }}</span></h6>
                        <a href="#" onclick="payment_detail({{ $order->id }})" class="btn btn-outline-dark">Payment</a>

                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="orders-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Variation</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total Amount</th>
                                <th>Ordered At</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content" >
            <div class="modal-header bg-light">
              <h5 class="modal-title" id="exampleModalLabel">Payment Detail</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="table-responsive">
               <table class="table table-bordered bg-light">
                <thead>
                    <tr >
                        <th>#</th>
                        <th>Amount</th>
                        <th>Transaction Id</th>
                        <th>Pay By</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody id="paymentTableData">

                </tbody>


               </table>
                </div>
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
            @if(isset($order->id))
            order_id = "{{ $order->id }}";
            @endif
            $('#orders-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url("/orders/") }}'+'/'+order_id, // Replace this with your API route
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'product', name: 'product' },
                    { data: 'variation', name: 'variation' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'price', name: 'price' },
                    { data: 'total_amount', name: 'total_amount' },
                    { data: 'created_at', name: 'created_at' },

                ]
            });
        });
    </script>
@endsection
