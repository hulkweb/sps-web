@extends('backend.layouts.master')
@section('title', 'Variation Type')
@section('content')
    <div class="container mt-3">
        <!-- variation_type Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h3>{{ isset($variation_type) ? 'Edit' : 'Add' }} Variation Type</h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($variation_type) ? route('variation_type.update', $variation_type->id) : route('variation_type.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if(isset($variation_type))
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter variation_type name" name="name"
                                   value="{{ isset($variation_type) ? $variation_type->name : '' }}" required>
                        </div>

                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">{{ isset($variation_type) ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endsection
