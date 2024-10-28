@extends('backend.layouts.master')
@section('title', ' Setting')
@section('content')
    <div class="container mt-3">
        <!-- Sub Category Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h3>Setting Update</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('setting.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Site Name</label>
                            <input type="text" class="form-control" id="sitename" placeholder="Enter site name" name="sitename"
                            value="{{ getSetting('sitename') }}" >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="value">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter value name" name="email"
                            value="{{ getSetting('email') }}" >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="value">Phone</label>
                            <input type="number" class="form-control" id="phone" placeholder="Enter value name" name="phone"
                            value="{{ getSetting('phone') }}" >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="value">Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo">
                            @if(getSetting('logo'))<img src="{{ uploads(getSetting('logo'))}}" alt="" style="width:100px;">@endif
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="value">Fav Icon</label>
                            <input type="file" class="form-control" id="favicon" name="favicon">
                            @if(getSetting('favicon'))<img src="{{ uploads(getSetting('favicon'))}}" alt="" style="width:100px;">@endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="value">Home Banner</label>
                            <input type="file" class="form-control" id="home_banner" name="home_banner">
                            @if(getSetting('home_banner'))<img src="{{ uploads(getSetting('home_banner'))}}" alt="" style="width:100px;">@endif
                        </div>

                        <div class="col-md-12 mb-5">
                            <label for="value">Privacy Policy</label>
                            <textarea  id="privacy_policy" name="privacy_policy">{{ getSetting('privacy_policy') }}</textarea>
                        </div>

                        <div class="col-md-12 mb-5">
                            <label for="value">Term & Condiion</label>
                            <textarea  id="term_condition" name="term_condition">{{ getSetting('term_condition') }}</textarea>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#term_condition'))
            .catch(error => {
                console.error(error);
            });

            ClassicEditor
            .create(document.querySelector('#privacy_policy'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
