@extends('backend.layouts.master')
@section('title', 'Seller Detail')
@section('content')
    <div class="container mt-3">
        <!-- Category Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h3>Seller Detail</h3>
            </div>
            <div class="card-body">
                <div class="card-body">

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="name">Name</label>
                                <input  readonly class="form-control text-dark"
                                       value="{{ isset($user) ? $user->name : '' }}" >
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="value">Email</label>
                                <input  readonly class="form-control text-dark"
                                value="{{ isset($user) ? $user->email : '' }}" >
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="value">Mobile Number</label>
                                <input  readonly class="form-control text-dark"
                                value="{{ isset($user) ? $user->mobile : '' }}" >
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="value">Total Order</label>
                                <input  readonly class="form-control text-dark"
                                value="{{ isset($user) ? $user->sellerOrder->count() : 0 }}" >
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="value">Total Customer</label>
                                <input  readonly class="form-control text-dark"
                                value="{{ isset($user) ? $user->customerData->count() : 0 }}" >
                            </div>



                            <div class="col-md-4 mb-3">
                                <label for="image">Image</label>
                               @if(isset($user) && $user->image)
                                    <img src="{{ uploads($user->image) }}" class="mt-4 " alt="Category Image" width="60px"height="60px">
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="description">Address</label>
                                <textarea  readonly class="form-control text-dark" >{{ isset($user) ? $user->address : '' }}</textarea>
                            </div>


                        </div>

                </div>
            </div>
        </div>


        <!-- Categories Table -->
        <div class="card">
            <div class="card-header">
                <h3>All Customers</h3>
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
                                <th>Orders</th>
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
            @if(isset($user->id))
            user_id = '{{ $user->id }}';
            @endif
            $('#category-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url("/customer") }}?sale_id='+user_id,
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'mobile', name: 'mobile' },
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
