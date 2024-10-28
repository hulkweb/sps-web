@extends('backend.layouts.master')
@section('title', ' Sub Category')
@section('content')
    <div class="container mt-3">
        <!-- Sub Category Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h3>{{ isset($subcategory) ? 'Edit' : 'Add' }}  Sub Category</h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($subcategory) ? route('subcategory.update', $subcategory->id) : route('subcategory.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if(isset($subcategory))
                    @method('PUT')
                   @endif
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter category name" name="name"
                            value="{{ isset($subcategory) ? $subcategory->name : '' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="category_id">Category</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value='' selected disabled>...Select Category...</option>
                                @isset($categories)
                                @foreach ($categories as $cat_item)
                                <option value="{{$cat_item->id}}" {{ isset($subcategory) && ($subcategory->category_id == $cat_item->id) ? 'selected' : '' }}>{{$cat_item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" id="image" name="image" {{ isset($subcategory) ? '' : 'required' }}>
                            @if(isset($subcategory) && $subcategory->image)
                            <img src="{{ uploads($subcategory->image) }}" alt="Category Image" width="60"height="60px">
                        @endif
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" placeholder="Enter description" name="description" rows="3" required>{{ isset($subcategory) ? $subcategory->description : '' }}</textarea>
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
