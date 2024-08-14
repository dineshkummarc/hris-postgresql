<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title><?php echo $config['app_name']; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="shortcut icon" href="<?php echo config_item('url_image'); ?>old_logo.png" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo config_item('url_logo'); ?>" />
	<link rel="stylesheet" href="<?php echo $config['url_ext']; ?>css/ext-all.css" />
	<link rel="stylesheet" href="<?php echo $config['url_css']; ?>siap/style.css" />
	<link rel="stylesheet" href="<?php echo $config['url_css']; ?>siap/icons.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $config['url_css']; ?>font-awesome/css/font-awesome.min.css">
	<link href="<?= config_item('url_template'); ?>siap/app.css" rel="stylesheet">

	<script type="text/javascript" src="<?php echo $config['url_ext']; ?>ext-all.js"></script>
	<script type="text/javascript" src="<?php echo $config['url_ext']; ?>helper.js"></script>

	<script type='text/javascript' src="<?php echo $config['view_siap']; ?>siap.js"></script>
	<script type='text/javascript' src="<?php echo $config['view_siap']; ?>packages/packages.js"></script>

	<script type='text/javascript' src="<?php echo $config['url_js']; ?>jquery-1.9.1.js"></script>
	<script type="text/javascript" src="<?php echo $config['url_js']; ?>moment/min/moment.min.js"></script>

	<script type="text/javascript">
		Settings = Ext.decode('<?php echo json_encode($config); ?>');
		// console.log(Settings);
		var hak = '';
		var first_menu = '';

		// Admin GA
		if (Settings.userid == false) {
			// nothing
		} else {
			if (Settings.userid == '1235' || Settings.userid == '108' || Settings.userid == '139' || Settings.userid == '93' || Settings.userid == '5' || Settings.userid == '244') {
				first_menu = 'perjalanandinas';
			} else {
				first_menu = 'pegawai';
			}
		}

		function logoutapp() {
			window.location = "<?php echo config_item('url_logout'); ?>/logout";
		}

		function myFunction() {
			document.getElementById("myDropdown").classList.toggle("show");
		}
		window.onclick = function(event) {
			if (!event.target.matches('.dropbtn')) {
				var dropdowns = document.getElementsByClassName("dropdown-content");
				var i;
				for (i = 0; i < dropdowns.length; i++) {
					var openDropdown = dropdowns[i];
					if (openDropdown.classList.contains('show')) {
						openDropdown.classList.remove('show');
					}
				}
			}
		}

		$(function() {
			$.fn.loadShortNotif = function() {
				if (Settings.userid == '108' || Settings.userid == '1' || Settings.userid == '1235' || Settings.userid == '139' || Settings.userid == '93' || Settings.userid == '244') {
					$.getJSON(
						Settings.SITE_URL + '/notifikasi/getShortNotificationDinas',
						'',
						function(response) {
							var html = '';
							$.each(response.data, function(index, record) {
								html += '<ul class="msg-list">';
								html += '<li>';
								html += '<a onclick=$(this).dinas();>';
								html += '<span>' + record.nama + '</span>' + '<br>';
								html += '<span>' + record.jenisnotif + '</span>' + '<br>';
								html += '<span>' + record.tglnotif + '</span>' + '<br>';
								html += '</a>';
								html += '</li>';
								html += '</ul>';
							});
							html += '<ul class="msg-list">';
							html += '<li>';
							html += '<div class="text-center">';
							html += '<a onclick=$(this).dinas();><strong>Lihat Semua</strong><i class="fa fa-angle-right"></i></a>';
							html += '</div>';
							html += '<li>';
							html += '</ul>';
							$(".mypanel").html(html);
							if (response.count > 0) {
								$('#id_count_readnotif').text(response.count);
							} else {
								$('#id_count_readnotif').hide();
								$('#id_count_readnotif').text(response.count);
							}
						}
					);
				} else if (Settings.userid == '147' || Settings.userid == '1' || Settings.userid == '121' || Settings.userid == '1900') {
					$.getJSON(
						Settings.SITE_URL + '/notifikasi/getShortNotificationCuti',
						'',
						function(response) {
							var html = '';
							$.each(response.data, function(index, record) {
								html += '<ul class="msg-list">';
								html += '<li>';
								html += '<a onclick=$(this).cuti();>';
								html += '<span>' + record.nama + '</span>' + '<br>';
								html += '<span>' + record.jenisnotif + '</span>' + '<br>';
								html += '<span>' + record.tglnotif + '</span>' + '<br>';
								html += '</a>';
								html += '</li>';
								html += '</ul>';
							});
							html += '<ul class="msg-list">';
							html += '<li>';
							html += '<div class="text-center">';
							html += '<a onclick=$(this).cuti();><strong>Lihat Semua</strong><i class="fa fa-angle-right"></i></a>';
							html += '</div>';
							html += '<li>';
							html += '</ul>';
							$(".mypanel").html(html);
							if (response.count > 0) {
								$('#id_count_readnotif').text(response.count);
							} else {
								$('#id_count_readnotif').hide();
								$('#id_count_readnotif').text(response.count);
							}
						}
					);
				}
			};
			$.fn.loadShortNotif();
		});
		$.fn.cuti = function() {
			window.location = Settings.SITE_URL + '#cuti';
		};

		$.fn.dinas = function() {
			window.location = Settings.SITE_URL + '#perjalanandinas';
		};

		$(document).ready(function() {
			$(document).on('click', '#link', function(e) {
				e.preventDefault();
				$.ajax({
					type: 'POST',
					url: Settings.SITE_URL + '/notifikasi/updateReadNotif',
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

		packages();
		Ext.onReady(function() {
			Ext.QuickTips.init();

			// var halpjdinas = '';
			var halpegawai = '';
			var halcuti = '';
			var halreport = '';
			var halmaster = '';
			// var halakomodasi = '';
			var halunit = '';
			var haljabatan = '';
			var hallevel = '';
			var halharilibur = '';
			var hallokasi = '';
			var halbudget = '';
			// var halticket = '';
			var halschedule = '';
			var halkehadiran = '';
			// var haldailyreport = '';
			// var halkursdollar = '';
			// var halakomodasiln = '';
			// var halbudgetkhusus = '';
			// var halabsensi = '';
			var halreportcuti = '';

			// Jika Head GA untuk Perjalanan dinas
			if ( //Settings.userid == '1' || /*Developer*/
				Settings.userid == '1235' || Settings.userid == '108' || Settings.userid == '244' || /*GA*/
				Settings.userid == '139' || Settings.userid == '93' /*FA*/ ) {
				// halpjdinas = '<li><a href="#perjalanandinas">Perjalanan Dinas</a></li>'
				if (Settings.userid == '1' || Settings.userid == '1235' || Settings.userid == '139') {
					if (Settings.userid == '1235') {
						// halmaster = '<li><a href="#master&MasterAkomodasi">Master</a></li>';
						// halakomodasi = '<li><a href="#master&MasterAkomodasi">Akomodasi</a></li>';
						// halakomodasiln = '<li><a href="#master&MasterAkomodasiLn">Akomodasi Luar Negeri</a></li>';
						// halkursdollar = '<li><a href="#master&MasterKursDollar">Kurs Dollar</a></li>';
						// halticket = '<li><a href="#ticket">Ticket</a></li>';
					} else if (Settings.userid == '1') {
						// halmaster = '<li><a href="#master&MasterAkomodasi">Master</a></li>';
						// halakomodasi = '<li><a href="#master&MasterAkomodasi">Akomodasi</a></li>';
						// halakomodasiln = '<li><a href="#master&MasterAkomodasiLn">Akomodasi Luar Negeri</a></li>';
						// halkursdollar = '<li><a href="#master&MasterKursDollar">Kurs Dollar</a></li>';
						// halmaster = '<li><a href="#master&MasterBudget">Master</a></li>';
						// halbudget = '<li><a href="#master&MasterBudget">Budget</a></li>';
						// halbudgetkhusus = '<li><a href="#master&MasterBudgetKhusus">Budget Khusus</a></li>';
						halreport = '<li><a href="#report">Report</a></li>';
						// halticket = '<li><a href="#ticket">Ticket</a></li>';
					} else {
						halkursdollar = '<li><a href="#master&MasterKursDollar">Kurs Dollar</a></li>';
						// halmaster = '<li><a href="#master&MasterBudget">Master</a></li>';
						halbudget = '<li><a href="#master&MasterBudget">Budget</a></li>';
						halbudgetkhusus = '<li><a href="#master&MasterBudgetKhusus">Budget Khusus</a></li>';
						halreport = '<li><a href="#report">Report</a></li>';
						// halticket = '<li><a href="#ticket">Ticket</a></li>';
					}
				}
			} else if (Settings.userid == '1900') {
				/*HR adm 2*/
				halpegawai = '<li><a href="#pegawai">Pegawai</a></li>';
				halcuti = '<li><a href="#cuti">Cuti</a></li>';
				halkehadiran = '<li><a href="#kehadiran">Kehadiran</a></li>';
			} else {
				// Admin HRIS HRD
				halpegawai = '<li><a href="#pegawai">Pegawai</a></li>';
				halcuti = '<li><a href="#cuti">Cuti</a></li>';
				halreport = '<li><a href="#report">Report</a></li>';
				halreportcuti = '<li><a href="#reportcuti">Report Cuti</a></li>';
				halmaster = '<li><a href="#master&MasterUnit">Master</a></li>';
				halkehadiran = '<li><a href="#kehadiran">Kehadiran</a></li>';
				// haldailyreport = '<li><a href="#dailyreport">Daily Report</a></li>';
				// halabsensi = '<li><a href="#absensi">Absensi</a></li>';
				// PAK RAHMAN
				if (Settings.userid == '5') {
					// halpjdinas = '<li><a href="#perjalanandinas">Perjalanan Dinas</a></li>'
				}
				// DEVELOPER
				else if (Settings.userid == '1') {
					halpegawai = '<li><a href="#pegawai">Pegawai</a></li>';
					halcuti = '<li><a href="#cuti">Cuti</a></li>';
					halreport = '<li><a href="#report">Report</a></li>';
					halreportcuti = '<li><a href="#reportcuti">Report Cuti</a></li>';
					halkehadiran = '<li><a href="#kehadiran">Kehadiran</a></li>';
					// haldailyreport = '<li><a href="#dailyreport">Daily Report</a></li>';
					// halabsensi = '<li><a href="#absensi">Absensi</a></li>';
					// halpjdinas = '<li><a href="#perjalanandinas">Perjalanan Dinas</a></li>'
					halunit = '<li><a href="#master&MasterUnit">Unit</a></li>';
					haljabatan = '<li><a href="#master&MasterJabatan">Jabatan</a></li>';
					hallevel = '<li><a href="#master&MasterLevel">Level</a></li>';
					halharilibur = '<li><a href="#master&MasterHariLibur">Hari Libur</a></li>';
					hallokasi = '<li><a href="#master&MasterLokasi">Lokasi</a></li>';
					// halakomodasi = '<li><a href="#master&MasterAkomodasi">Akomodasi</a></li>';
					// halakomodasiln = '<li><a href="#master&MasterAkomodasiLn">Akomodasi Luar Negeri</a></li>';
					// halkursdollar = '<li><a href="#master&MasterKursDollar">Kurs Dollar</a></li>';
					// halbudget = '<li><a href="#master&MasterBudget">Budget</a></li>';
					// halbudgetkhusus = '<li><a href="#master&MasterBudgetKhusus">Budget Khusus</a></li>';
					// halticket = '<li><a href="#ticket">Ticket Perdin</a></li>';
				} else if (Settings.userid == '121') {
					/*HR adm 2*/
					halkehadiran = '<li><a href="#kehadiran">Kehadiran</a></li>';
					// haldailyreport = '<li><a href="#dailyreport">Daily Report</a></li>';
					// halabsensi = '<li><a href="#absensi">Absensi</a></li>';
				}
				// Master File
				halunit = '<li><a href="#master&MasterUnit">Unit</a></li>';
				haljabatan = '<li><a href="#master&MasterJabatan">Jabatan</a></li>';
				hallevel = '<li><a href="#master&MasterLevel">Level</a></li>';
				halharilibur = '<li><a href="#master&MasterHariLibur">Hari Libur</a></li>';
				hallokasi = '<li><a href="#master&MasterLokasi">Lokasi</a></li>';
			}

			Ext.create('Ext.container.Viewport', {
				layout: 'border',
				padding: '0 0 0 0',
				renderTo: Ext.getBody(),
				items: [{
						region: 'north',
						layout: 'anchor',
						border: false,
						bodyPadding: 0,
						items: [{
							xtype: 'panel',
							border: 0,
							layout: 'fit',
							height: 95,
							html: '<div class="header">' +
								'<div class="div_center">' +
								'<div class="logoleft"><a href="' + Settings.BASE_URL + '"><img src="' + Settings.BASE_URL + 'setting/eci/old_logo.png' + '"></a></div>' +
								'<div class="left">' +
								'<ul id="menu" class="menudropdown">' +
								halpegawai +
								halcuti +
								halreportcuti +
								halkehadiran +
								// haldailyreport +
								// halabsensi +
								// halpjdinas +
								halreport +
								// halticket +
								halmaster +
								'<li></li>' +
								'</ul>' +
								'</div>' +
								'<div class="right">' +
								'<ul class="eastmenu">' +
								'<li>' +
								'<div class="notification">' +
								'<div id="link" class="dropdown"><i onclick="myFunction()" class="fa fa-envelope-o dropbtn"><div id="myDropdown" class="dropdown-content"><div class="mypanel"></div></div></i><span id="id_count_readnotif" class="badge bg-green">0</span></div>' +
								'</div>' +
								'</li>' +
								'<li>' +
								'<div class="listuser">' +
								Settings.usergroup + '<br>' +
								Settings.nama + ' | <span class="cllogout" onclick="logoutapp()" style="cursor:pointer;">logout</span>' +
								'</div>' +
								'</li>' +
								'</ul>' +
								'</div>' +
								'</div>' +
								'<div class="div_bottom">' +
								'<ul id="id_submenu" class="submenu">' +
								halunit +
								haljabatan +
								hallevel +
								halharilibur +
								hallokasi +
								// halakomodasi +
								// halakomodasiln +
								// halbudget +
								// halbudgetkhusus +
								// halkursdollar +
								'</ul>' +
								'</div>' +
								'</div>',
						}]
					},
					{
						id: 'center',
						layout: 'fit',
						region: 'center',
						bodyPadding: 0,
						padding: 0,
						border: false,
						loader: Ext.create('Ext.Component', {
							loader: {},
							border: false,
							renderTo: Ext.getBody()
						})
					},
					{
						region: 'south',
						bodyPadding: 3,
						border: false,
						minHeight: 30,
						style: 'background-color:#ededed;text-align: right;',
						html: Settings.footer
					}
				],
				listeners: {
					afterrender: function() {
						dispatch = function(token) {
							var tokens = token.split('&');
							var m = tokens[0];
							var act = tokens[1];
							var params = tokens[2];

							if (Ext.isEmpty(m)) {
								m = first_menu;
							}

							var type = '';
							if (!Ext.isEmpty(act)) {
								type = act.toLowerCase();
								var require = 'SIAP.modules.' + m + '.' + act;
							} else {
								type = m;
								var require = 'SIAP.modules.' + type + '.App';
							}

							Ext.require(require, function() {
								Ext.getCmp('center').removeAll();
								Ext.getCmp('center').add({
									xtype: type,
									layout: 'fit',
									menu: m,
									params: params,
								});
								Ext.getCmp('center').doLayout();
							});
							Ext.getCmp('center').doLayout();
						}

						Ext.History.init(function() {
							var hashTag = document.location.hash;
							var tag = hashTag.replace("#", "");
							dispatch(tag);
						});
						Ext.History.on('change', dispatch);
					}
				}
			});
		});
	</script>

</head>

<body>
</body>

</html>