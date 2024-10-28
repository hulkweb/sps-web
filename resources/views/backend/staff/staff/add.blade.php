@extends('backend.layouts.master')
@section('title', ' Staff')
@section('content')
<div class="container mt-3">
    <!-- Sub Category Form -->
    <div class="card mb-3">
        <div class="card-header">
            <h3>{{ isset($staff) ? 'Edit' : 'Add' }} Staff</h3>
        </div>
        <div class="card-body">
            <form action="{{ isset($staff) ? route('staff.update', $staff->id) : route('staff.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                @if(isset($staff))
                 @method('PUT')
                @endif
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter  name" name="name"
                               value="{{ isset($staff) ? $staff->name : '' }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="value">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter value email" name="email"
                        value="{{ isset($staff) ? $staff->email : '' }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="value">Mobile Number</label>
                        <input type="number" class="form-control" id="mobile" placeholder="Enter mobile" name="mobile"
                        value="{{ isset($staff) ? $staff->mobile : '' }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="value">Password</label>
                        <input type="text" class="form-control" value="" id="logo" name="password" minlength="6">
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="role_id">Role</label>
                        <select class="form-control" id="role_id" name="role_id" required >
                              <option value='' selected disabled>...Select role...</option>

                              @foreach ($roles as $role)
                                    @if (isset($staff->role_id))
                                    <option @if ($staff->role_id != null) @if ($staff->role_id == $role->id) selected @endif
                                        @endif value="{{ $role->id }}">{{ $role->name }}</option>
                                    @else
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endif
                                @endforeach

                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" id="image" name="image" {{ isset($staff) ? '' : 'required' }}>
                        @if(isset($staff) && $staff->image)
                            <img src="{{ uploads($staff->image) }}" alt="Category Image" width="60"height="60px">
                        @endif
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description">Address</label>
                        <textarea class="form-control" id="description" placeholder="Enter description" name="address" rows="3" required>{{ isset($staff) ? $staff->address : '' }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <button class="btn btn-primary" type="submit">{{ isset($staff) ? 'Update' : 'Save' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
