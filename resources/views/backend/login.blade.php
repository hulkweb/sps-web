<!DOCTYPE html>
<html>

<head>
	<title>Login</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
	<style>
    	/* Coded with love by Mutiullah Samim */
		body, html {
			margin: 0;
			padding: 0;
			height: 100%;
			background: white !important;
		}
		.user_card {
			height: 400px;
			width: 350px;
			margin-top: auto;
			margin-bottom: auto;
			background: hsl(30, 1%, 72%);
			position: relative;
			display: flex;
			justify-content: center;
			flex-direction: column;
			padding: 10px;
             box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px !important;
			/* box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); */
			-webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			-moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			border-radius: 5px;
		}
		.brand_logo_container {
			position: absolute;
			height: 170px;
			width: 170px;
			top: -75px;
			border-radius: 50%;
			background: #60a3bc;
			padding: 10px;
			text-align: center;
		}
		.brand_logo {
			height: 150px;
			width: 150px;
			border-radius: 50%;
			border: 2px solid white;
		}
		.form_container {
			margin-top: 100px;
		}
		.login_btn {
			width: 100%;
			background: #089131 !important;
			color: white !important;
		}
		.login_btn:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.login_container {
			padding: 0 2rem;
		}
		.input-group-text {
			background: #089131 !important;
			color: white !important;
			border: 0 !important;
			border-radius: 0.25rem 0 0 0.25rem !important;
		}
		.input_user, .input_pass:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.custom-checkbox .custom-control-input:checked~.custom-control-label::before {
			background-color: #089131 !important;
		}
	</style>
</head>

<body>
	<div class="container h-100">
		<div class="d-flex justify-content-center h-100">
			<div class="user_card shadow-lg bg-light rounded">
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="{{ uploads(getSetting('logo')) }}" class="brand_logo" alt="Logo">
					</div>
				</div>
                <!-- Buttons for switching between email/password and mobile/password -->
				<div class="mt-4">
					<button id="emailLogin" class="btn btn-success active float-left">Email</button>
					<button id="mobileLogin" class="btn btn-secondary float-right">Mobile</button>
				</div>

				<div class="d-flex justify-content-center form_container">
					<form method="post" action="{{route('postlogin')}}" id="loginForm">
                        @csrf
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="email" id="username" name="email" class="form-control input_user" value="" placeholder="Email">
						</div>
						<div class="input-group mb-2">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" name="password" class="form-control input_pass" value="" placeholder="Password">
							{{-- <input type="number" name="otp" class="form-control" style="display: none" id="otp" placeholder="otp"> --}}
						</div>

						<div class="d-flex justify-content-center mt-3 login_container">
				 			<button type="submit" name="button" class="btn login_btn">Login</button>
						</div>
					</form>
				</div>


			</div>
		</div>
	</div>

    @include('sweetalert::alert')

	<!-- jQuery Script for Toggling and Active Button -->
	<script>
		$(document).ready(function() {
			$('#emailLogin').on('click', function() {
				// Set input field for email
				$('#username').attr('type', 'email').attr('name', 'email').attr('placeholder', 'Email');
                $('#loginForm').attr('action',`{{route('postlogin')}}`);
                $('#otp').css('display','none');

				// Toggle active class between buttons
				$('#emailLogin').addClass('active').removeClass('btn-secondary').addClass('btn-success');
				$('#mobileLogin').removeClass('active').removeClass('btn-success').addClass('btn-secondary');
			});

			$('#mobileLogin').on('click', function() {
				// Set input field for mobile
				$('#username').attr('type', 'number').attr('name', 'mobile').attr('placeholder', 'Mobile');
                $('#loginForm').attr('action',`{{route('postlogin')}}`);
                $('#otp').css('display','block');

				// Toggle active class between buttons
				$('#mobileLogin').addClass('active').removeClass('btn-secondary').addClass('btn-success');
				$('#emailLogin').removeClass('active').removeClass('btn-success').addClass('btn-secondary');
			});
		});
	</script>

</body>

</html>
