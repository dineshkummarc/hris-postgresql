<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Portal Modul</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="index, follow">
	<meta name="description" content="portal modul">
	<meta name="keywords" content="portal modul">
	<meta http-equiv="Copyright" content="">
	<meta name="author" content="">
	<meta http-equiv="imagetoolbar" content="no">
	<meta name="language" content="Indonesia">
	<meta name="revisit-after" content="7">
	<meta name="webcrawlers" content="all">
	<meta name="rating" content="general">
	<meta name="spiders" content="all">

	<link rel="shortcut icon" href="<?= config_item('url_image'); ?>old_logo.png" />
	<link rel="stylesheet" type="text/css" href="<?= config_item('url_template'); ?>themes_portal/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?= config_item('url_template'); ?>themes_portal/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= config_item('url_template'); ?>themes_portal/css/webstyle.css">

	<script src="<?= config_item('url_template'); ?>themes_portal/js/jquery.min.js"></script>
	<script src="<?= config_item('url_template'); ?>themes_portal/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		var BASE_URL = '<?= base_url(); ?>';
		var SITE_URL = '<?= site_url(); ?>';
	</script>

</head>

<body>
	<div class="container-full">
		<header>
			<div class="top_nav">
				<div class="nav_menu">
					<nav>
						<ul class="nav navbar-nav navbar-right">
							<li class="">
								<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<?= $this->session->userdata('nama'); ?>
									<span class=" fa fa-angle-down"></span>
								</a>
								<ul class="dropdown-menu dropdown-usermenu pull-right">
									<li><a href="<?= site_url(''); ?>/app/changePassword"><i class="fa fa-pencil pull-right"></i> Ubah Password</a></li>
									<?php if (($this->session->userdata('admin') == true)) { ?>
										<li><a href="<?= site_url(''); ?>/app/uploadDataPegawai"><i class="fa fa-file-excel-o pull-right"></i> Upload Data</a></li>
									<?php } ?>
									<li><a href="<?= config_item('url_logout'); ?>/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
								</ul>
							</li>
							<li role="presentation" class="dropdown">
								<a href="javascript;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
									<i class="fa fa-envelope-o"></i>
									<span id="id_count_readnotif" class="badge bg-green">0</span>
								</a>
								<ul id="id_panelnotifikasi" class="dropdown-menu list-unstyled msg_list" role="menu"></ul>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</header>
		<aside>
			<div class="container">
				<button class="btn btn-success" style="background: #26B99A; border: 1px solid #169F85" onclick="window.location.href='<?= site_url(''); ?>/app/download?filename=PP_2021-2023.pdf'">
					<span class="icons icons-class"><i class="fa fa-file-text-o"></i></span>
					<span class="glyphicon-class"><span style="font-size:11px;"> Peraturan Perusahaan </span></span>
				</button>
			</div>
			</br>
			<div class="container">
				<button class="btn btn-success" style="background: #26B99A; border: 1px solid #169F85" onclick="window.location.href='<?= site_url(''); ?>/app/liatprofil?filename=DIRKOM ECI.pdf'" target="_blank">
					<span class="icons icons-class"><i class="fa fa-photo"></i></span>
					<span class="glyphicon-class"><span style="font-size:11px;"> Jajaran Direksi & Komisaris </span></span>
				</button>
			</div>
	</div>
	<div class="container" style="min-height:500px; margin-top:-50px;">
		<div class="box-north">
			<div class="box-image">
				<img alt="" src="<?php echo base_url(); ?>setting/eci/old_logo.png" height="248" width="350">
			</div>
		</div>
		<div class="box-module">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<ul class="ul-box">
						<?php foreach ($modul['data'] as $r) : ?>
							<li class="green" style="margin-top:25px">
								<a href="<?php echo config_item('base_url') . $r['modul'] . '.php'; ?>">
									<span class="icons icons-class">
										<i class="<?= $r['iconid']; ?>"></i>
									</span>
									<span class="glyphicon-class"><span style="font-size:11px;"><?= $r['moduldesc']; ?></span></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	</aside>
	<footer>
	</footer>
	</div>
	<script type="text/javascript">
		$(function() {
			$.fn.loadShortNotif = function() {
				var link = '';
				if (<?= $this->session->userdata('admin'); ?> == true
				) {
					link = '/notifikasi/getShortNotificationHR';
				} else {
					link = '/notifikasi/getShortNotification';
				}
				$.getJSON(
					SITE_URL + link,
					'',
					function(response) {
						var html = '';
						$.each(response.data, function(index, record) {
							html += '<li>';
							if (record.labelid == '2') {
								html += '<a onclick=$(this).detailApp1();>';
							} else if (record.labelid == '3') {
								html += '<a onclick=$(this).detailApp2();>';
							} else if (record.labelid == '4') {
								html += '<a onclick=$(this).detailApp3();>';
							} else if (record.labelid == '10') {
								html += '<a onclick=$(this).detailApp10();>';
							} else if (record.labelid == '98') {
								html += '<a onclick=$(this).detailApp98();>';
							} else {
								html += '<a onclick=$(this).detailApp4();>';
							}
							html += '<span class="message">' + record.label + '</span>';
							html += '<span class="time_notif">' + 'Jumlah : ' + record.jml + '</span>';
							html += '</a>';
							html += '</li>';
						});

						html += '<li>';
						html += '<div class="text-center">';
						html += '<i class="fa fa-envelope-o"></i>';
						html += '</div>';
						html += '</li>';

						$('#id_panelnotifikasi').html(html);

						if (response.count > 0) {
							$('#id_count_readnotif').text(response.count);
						} else {
							$('#id_count_readnotif').hide();
							$('#id_count_readnotif').text(response.count);
						}
					}
				);
			};
			$.fn.loadShortNotif();
			$.fn.detailApp1 = function() {
				window.location = BASE_URL + '/eservices.php/';
			};
			$.fn.detailApp2 = function() {
				window.location = BASE_URL + '/dinas.php/';
			};
			$.fn.detailApp3 = function() {
				window.location = BASE_URL + '/policies.php/';
			};
			$.fn.detailApp4 = function() {
				window.location = BASE_URL + '/contract.php/';
			};
			$.fn.detailApp10 = function() {
				window.location = BASE_URL + '/absensi.php/';
			};
			$.fn.detailApp98 = function() {
				window.location = BASE_URL + '/dailyreport.php/';
			};

			$('#id_btnnotif').on('click', function(e) {
				e.preventDefault();
				$.ajax({
					type: 'POST',
					url: SITE_URL + '/notifikasi/updateReadNotif',
					data: '',
					cache: false,
					dataType: 'json',
					success: function(response) {
						if (response.success) {
							$.fn.loadShortNotif();
						}
					}
				});

			});
		});
	</script>
</body>

</html>