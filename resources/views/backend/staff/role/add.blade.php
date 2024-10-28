@extends('backend.layouts.master')
@section('title', ' Role')
@section('content')
    <div class="container mt-3">
        <!-- Sub Category Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h3>{{ isset($role) ? 'Edit' : 'Add' }} Role</h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($role) ? route('role.update', $role->id) : route('role.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if(isset($role))
                    @method('PUT')
                   @endif
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter  name" name="name"
                            value="{{ isset($role) ? $role->name : '' }}" required>
                        </div>



                        <div class="col-md-6 mb-3">
                            <label for="permission">Permission</label>
                            <select class="form-control" id="permission" name="permission[]" required multiple>
                                  <option value='' selected disabled>...Select permission...</option>
                                  @foreach ($permissions as $permission)
                                  @if (isset($role->permission))
                                      <option
                                          @if ($role->permission != null) @if (in_array($permission->id, json_decode($role->permission))) selected @endif
                                          @endif
                                          value="{{ $permission->id }}">{{ $permission->name }}
                                      </option>
                                  @else
                                      <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                  @endif
                              @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">{{ isset($role) ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div></div>
        @endsection
