@extends('backend.layouts.master')
@section('title', ' Variation Value')
@section('content')
    <div class="container mt-3">
        <!-- Variation Value Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h3>{{ isset($variation_value) ? 'Edit' : 'Add' }}  Variation Value</h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($variation_value) ? route('variation_value.update', $variation_value->id) : route('variation_value.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if(isset($variation_value))
                    @method('PUT')
                   @endif
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter category name" name="name"
                            value="{{ isset($variation_value) ? $variation_value->name : '' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="variation_type_id">Variation Type</label>
                            <select class="form-control" id="variation_type_id" name="variation_type_id" required>
                                <option value='' selected disabled>...Select variation_type...</option>
                                @isset($variation_types)
                                @foreach ($variation_types as $type)
                                <option value="{{$type->id}}" {{ isset($variation_value) && ($variation_value->variation_type_id == $type->id) ? 'selected' : '' }}>{{$type->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>

                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">{{ isset($category) ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    @endsection
