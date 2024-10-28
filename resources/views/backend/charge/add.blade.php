@extends('backend.layouts.master')
@section('title', ' Charge')
@section('content')
    <div class="container mt-3">
        <!-- Sub Category Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h3>{{ isset($charge) ? 'Edit' : 'Add' }} Charge</h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($charge) ? route('charge.update', $charge->id) : route('charge.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if(isset($charge))
                    @method('PUT')
                   @endif
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter category name" name="name"
                            value="{{ isset($charge) ? $charge->name : '' }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="value">Value</label>
                            <input type="text" class="form-control" id="value" placeholder="Enter value name" name="value"
                            value="{{ isset($charge) ? $charge->value : '' }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="type">Type</label>
                            <select class="form-control" id="type" name="type" required>
                                  <option value='' selected disabled>...Select Type...</option>
                                  <option value="percent" {{ isset($charge) && ($charge->type == 'percent') ? 'selected' : '' }}>Percentage</option>
                                  <option value="flat" {{ isset($charge) && ($charge->type == 'flat') ? 'selected' : '' }}>Flat</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">{{ isset($charge) ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
