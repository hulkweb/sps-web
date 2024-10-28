<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

<link rel="icon" type="image/x-icon" href="{{ uploads(getSetting('favicon')) }}" />
<link href="{{ asset('backend_ui/layouts/vertical-light-menu/css/light/loader.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{ asset('backend_ui/layouts/vertical-light-menu/css/dark/loader.css') }}" rel="stylesheet"
    type="text/css" />
<script src="{{ asset('backend_ui/layouts/vertical-light-menu/loader.js') }}"></script>

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
<link href="{{ asset('backend_ui/src/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('backend_ui/layouts/vertical-light-menu/css/light/plugins.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{ asset('backend_ui/layouts/vertical-light-menu/css/dark/plugins.css') }}" rel="stylesheet"
    type="text/css" />
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<link href="{{ asset('backend_ui/src/plugins/src/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('backend_ui/src/assets/css/light/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('backend_ui/src/assets/css/dark/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

<style>
    .widget.widget-card-four{
	background: #FDEDF8;
	border: 1px solid #8d18724a;
}
.widget-card-four .w-content .w-info p.value{
	color: #580A46;
}
.header-container{
	background: #8D1872;
}
.navbar .navbar-item .nav-item.dropdown.notification-dropdown .nav-link svg{
	color : #ffffff;
}

.menu-categories li:nth-child(1){
	background-color: #fdedf8;
}
li.menu.first-child a {
    color: #8d1872 !important;
}

.form-control{
	border: 1px solid #8d1872;
	color: #580a46;
}
.form-group label, label{
	color: #580a46;
}

.card .card-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: #580a46;
}
.card .card-header {
    color: #3b3f5c;
    border-bottom: 1px solid #e0e6ed;
    padding: 12px 20px;
    background: #FDEDF8;
}
.dataTables_paginate {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
}

.dataTables_paginate .paginate_button {
    display: inline-block;
    padding: 8px 15px;
    margin: 0 2px;
    border: 1px solid #ddd;
    background: #f9f9f9;
    color: #333;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.2s ease-in-out;
}

.dataTables_paginate .paginate_button:hover {
    background-color: #e9e9e9;
    border-color: #ccc;
}

.dataTables_paginate .paginate_button.current {
    background: #bf43a2;
    color: #fff;
    border-color: #bf43a2;
}

.dataTables_paginate .paginate_button.disabled {
    pointer-events: none;
    opacity: 0.6;
}

.dataTables_paginate .paginate_button.previous,
.dataTables_paginate .paginate_button.next {
    font-weight: bold;
}


.dataTables_filter {
    max-width: 50%;
    display: inline;
    float: right;
}
.dataTables_length {
    max-width: 50%;
    display: inline;
}
.dataTables_filter input {
    margin-left: 12px;
    border: 1px solid #8d1872;
    border-radius: 4px;
    height: 38px;
}
.btn-warning{
	background-color: #580A46;
    border-color: #580A46;
    border-radius: 30px;
    font-size: 14px;
}
.table tbody tr td select {
    padding: 5px 12px;
    border-radius: 4px;
    border: 1px solid #f5cee9;
}
.table thead {
    color: #ffffff;
    background: #bf43a2;
}
</style>
