<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="<?php echo config_item('url_image'); ?>old_logo.png" />
	<title>Login | <?php echo config_item('instansi_long_name'); ?></title>
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/build/css/custom.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/nprogress/nprogress.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/animate.css/animate.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>login/login.css" rel="stylesheet">
	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/jquery/dist/jquery.min.js"></script>
	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
</head>

<body class="login">
	<div class="north-layout">
		<div class="col-md-2 col-md-push-10 col-sm-6 col-xs-12 left-panel">
			<img src="<?php echo config_item('url_image'); ?>old_logo.png" width="115">
		</div>
		<div class="col-md-10 col-md-pull-2 col-sm-6 col-xs-12 right-panel">
			<span style="font-size:30px;color:#0E7BBE;">
				<?= config_item('app_long_name'); ?>
			</span>
		</div>
	</div>
	<div class="center-layout">
		<div class="col-md-8 col-sm-6 hidden-xs left-panel">
			<div class="panel-logo">
				<img src="<?php echo config_item('url_image'); ?>electronic_city.png" width="35%" height="35%">
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12 right-panel">
			<form id="form_login" data-parsley-validate class="form-login">
				<label for="fullname">Username :</label>
				<input type="text" id="id_username" class="form-control" name="username" />

				<label for="email">Password :</label>
				<input type="password" id="id_password" name="password" class="form-control">

				<span id="id_pesan" class="pesan"></span>
				<br>
				<span class="btn btn-primary" onClick='do_login();'>Login</span>
			</form>
		</div>
	</div>
	<div class="south-layout">
		<p><span class="span-footer"><?php echo config_item('footer'); ?></span></p>
	</div>
</body>

<script type="text/javascript">
	$(document).ready(function() {
		$('#id_username').bind('keyup', function(event) {
			if (event.keyCode == 13) {
				document.getElementById('id_password').focus();
			}
		});
		$('#id_password').bind('keyup', function(event) {
			if (event.keyCode == 13) {
				do_login();
			}
		});
	});

	function do_login() {
		$('#id_pesan').removeClass();
		$('#id_pesan').addClass('pesan-wait');
		$('#id_pesan').html("Sedang Verifikasi Username dan Password ...");


		$.post('<?php echo site_url("login/check_login"); ?>', $("#form_login").serialize(), function(data, status) {
			if (status == 'success') {
				var obj = jQuery.parseJSON(data);
				if (obj.success == true) {
					$('#id_pesan').addClass('pesan-success');
					$('#id_pesan').html("Login Sukses, Loading Aplikasi...");
					document.location.href = "<?php echo config_item('url_portal'); ?>";
				} else if (obj.payload == "masalah") {
					alert('Mohon hubungi HRD, Approval anda kosong!');
					window.location.reload();
				} else {
					$('#id_pesan').addClass('pesan-failure');
					$('#id_pesan').html("Username dan Password Tidak Sesuai.");
				}
			} else {
				$('#id_pesan').addClass('pesan-failure');
				$('#id_pesan').html("Maaf, ada kesalahan dalam pengiriman data.");
			}
		});
	}
</script>

</html>