@extends('backend.layouts.master')
@section('title', 'Sales Person')
@section('content')
    <div class="container mt-3">
        <!-- Category Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h3>{{ isset($user) ? 'Edit' : 'Add' }} Sales Person</h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($user) ? route('seller.update', $user->id) : route('seller.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if(isset($user))
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter  name" name="name"
                                   value="{{ isset($user) ? $user->name : '' }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="value">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter value email" name="email"
                            value="{{ isset($user) ? $user->email : '' }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="value">Mobile Number</label>
                            <input type="number" class="form-control" id="mobile" placeholder="Enter mobile" name="mobile"
                            value="{{ isset($user) ? $user->mobile : '' }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="value">Password</label>
                            <input type="text" class="form-control" value="" id="logo" name="password" minlength="6">
                          </div>

                        <div class="col-md-6 mb-3">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" id="image" name="image" {{ isset($user) ? '' : 'required' }}>
                            @if(isset($user) && $user->image)
                                <img src="{{ uploads($user->image) }}" alt="Category Image" width="60"height="60px">
                            @endif
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="description">Address</label>
                            <textarea class="form-control" id="description" placeholder="Enter description" name="address" rows="3" required>{{ isset($user) ? $user->address : '' }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">{{ isset($user) ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    @endsection
