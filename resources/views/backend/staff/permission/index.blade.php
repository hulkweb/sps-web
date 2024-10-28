@extends('backend.layouts.master')
@section('title', ' Permission')
@section('content')
    <div class="container mt-3">


        <!-- Categories Table -->
        <div class="card">
            <div class="card-header">
                <h3>All Permission</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="permission-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#permission-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url("/permission") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    ]
            });
        });
    </script>
@endsection
