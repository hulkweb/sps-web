@extends('backend.layouts.master')
@section('title', 'Category')
@section('content')
    <div class="container mt-3">
        <!-- Category Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h3>{{ isset($category) ? 'Edit' : 'Add' }} Category</h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($category) ? route('category.update', $category->id) : route('category.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if(isset($category))
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter category name" name="name"
                                   value="{{ isset($category) ? $category->name : '' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" id="image" name="image" {{ isset($category) ? '' : 'required' }}>
                            @if(isset($category) && $category->image)
                                <img src="{{ uploads($category->image) }}" alt="Category Image" width="60"height="60px">
                            @endif
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" placeholder="Enter description" name="description" rows="3" required>{{ isset($category) ? $category->description : '' }}</textarea>
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
