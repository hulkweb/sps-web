 <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
 <script src="{{ asset('backend_ui/src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
 <script src="{{ asset('backend_ui/src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
 <script src="{{ asset('backend_ui/src/plugins/src/mousetrap/mousetrap.min.js') }}"></script>
 <script src="{{ asset('backend_ui/layouts/vertical-light-menu/app.js') }}"></script>
 <!-- END GLOBAL MANDATORY SCRIPTS -->

 <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
 <script src="{{ asset('backend_ui/src/plugins/src/apex/apexcharts.min.js') }}"></script>
 <script src="{{ asset('backend_ui/src/assets/js/dashboard/dash_1.js') }}"></script>
 <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script>
function orderStatusChange(status, orderId, column) {
    $.ajax({
        url: "{{ route('orders.updateStatus') }}",  // Route to your update status method
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',  // Add CSRF token
            order_id: orderId,
            status: status,
            column: column,
        },
        success: function(response) {
            location.reload();
        },
        error: function(xhr, status, error) {
            alert('Failed to update status');
        }
    });
}

function orderAssignDriver(id, orderId) {
    $.ajax({
        url: "{{ route('orders.assignDriver') }}",  // Route to your update status method
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',  // Add CSRF token
            order_id: orderId,
            id: id,
        },
        success: function(response) {
            location.reload();
        },
        error: function(xhr, status, error) {
            alert('Failed to update status');
        }
    });
}

    function payment_detail(order_id){

        $.ajax({
				url: "{{ route('order.payment_detail') }}",
				method: "GET",
				data: {
					order_id: order_id,
					_token: "{{ csrf_token() }}"
				},
				success: function(res) {
                    $('#paymentTableData').html(res)
                    $('#paymentModal').modal('show');
                }
            });
    }


    function Toggle(id, table, column=null) {

        if(column == null){
                status = $('#tab_' + id).data("status");

                $('#tab_' + id).data("status", status)
            }else{
                status = $('#tab1_' + id).data("status");

                $('#tab1_' + id).data("status", status)
            }
            if(table == 'users'){
                if (status == 'active') {
                    status = 'inactive';
                } else {
                    status = 'active';
                }

              }else{
                if (status == 0) {
                    status = 1;
                } else {
                    status = 0;
                }

              }


			$.ajax({
				url: "{{ route('toggle') }}",
				method: "POST",
				data: {
					id: id,
					table: table,
					status: status,
                    column:column,
					_token: "{{ csrf_token() }}"
				},
				success: function(res) {

                }

			})

		}
</script>
