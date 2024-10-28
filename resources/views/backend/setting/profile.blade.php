@extends('backend.layouts.master')
@section('title', ' Profile Update')
@section('content')
    <div class="container mt-3">
        <!-- Sub Category Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h3>Profile Update</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('updateProfile') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="sitename" placeholder="Enter site name" name="sitename"
                            value="{{ auth()->user()->name }}" >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="value">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter value email" name="email"
                            value="{{ auth()->user()->email }}" >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="value">Mobile Number</label>
                            <input type="number" class="form-control" id="mobile" placeholder="Enter mobile" name="mobile"
                            value="{{ auth()->user()->mobile }}" >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="value">Password</label>
                            <input type="text" class="form-control" id="logo" name="password" minlength="6">
                          </div>


                        <div class="col-md-6 mb-3">
                            <label for="value">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            @if(auth()->user()->image)<img src="{{ uploads(auth()->user()->image) }}" alt="" style="width:100px;">@endif
                        </div>


                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Categories Table -->

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>


@endsection
