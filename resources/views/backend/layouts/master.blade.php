@php $currentRouteName = request()->route()->getName(); @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <title>
        @yield('title')
    </title>

    @include('backend.layouts.head')
</head>
<body class="layout-boxed">
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    @include('backend.layouts.navbar')
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        @include('backend.layouts.sidebar')
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
        @yield('content')
        </div>
        <!--  END CONTENT AREA  -->
        <!--  BEGIN FOOTER  -->
        @include('backend.layouts.footer')
        <!--  END FOOTER  -->
    </div>
    <!-- END MAIN CONTAINER -->

   @include('backend.layouts.foot')
   @include('sweetalert::alert')

</body>
</html>
