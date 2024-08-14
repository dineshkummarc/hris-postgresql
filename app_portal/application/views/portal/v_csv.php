<html>
<head>
    <head>
    <title>Upload Data Pegawai</title>
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
    
	<link rel="shortcut icon" href="<?php echo config_item('url_image'); ?>old_logo.png" />
    <link rel="stylesheet" type="text/css" href="<?php echo config_item('url_template'); ?>themes_portal/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo config_item('url_template'); ?>themes_portal/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo config_item('url_template'); ?>themes_portal/css/webstyle.css">

    <script src="<?php echo config_item('url_template'); ?>themes_portal/js/jquery.min.js"></script> 
    <script src="<?php echo config_item('url_template'); ?>themes_portal/js/bootstrap.min.js"></script>     
    <script type="text/javascript">
        var BASE_URL = '<?php echo base_url(); ?>';             
        var SITE_URL = '<?php echo site_url(); ?>';
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
									<?php echo $this->session->userdata('nama'); ?>
									<span class=" fa fa-angle-down"></span>
								</a>
								<ul class="dropdown-menu dropdown-usermenu pull-right">
									<li><a href="<?php echo site_url(''); ?>/app/changePassword"><i class="fa fa-pencil pull-right"></i> Ubah Password</a></li>
									<?php if ( ($this->session->userdata('nik') == '14110806') || ($this->session->userdata('nik') == '13121215') || ($this->session->userdata('nik') == '16030068') || ($this->session->userdata('nik') == '1003049') || ($this->session->userdata('nik') == '16050122') ) { ?>
                  					<li><a href="<?php echo site_url(''); ?>/app/uploadDataPegawai"><i class="fa fa-file-excel-o pull-right"></i> Upload Data</a></li>
                  					<li><a href="<?php echo site_url(''); ?>/app/strukturOrganisasi"><i class="fa fa-user pull-right"></i> Struktur Organisasi</a></li>
									<?php if ($this->session->userdata('nik') == '1003049') { ?>
									<li><a href="<?php echo site_url(''); ?>/app/dataKaryawan"><i class="fa fa-file-word-o pull-right"></i> Data Kehadiran </a></li>
									<?php } ?>
									<?php } ?>
									<li><a href="<?php echo config_item('url_logout'); ?>/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
								</ul>
							</li>	                             
                        </ul>
                    </nav>
                </div>      
            </div>          
        </header>
<aside>
<div class="panel-body">
<div class="col-lg-12" style="margin-top:20px">
<?php if(!empty($status)){
        echo '<div class="alert alert-danger">Data Pegawai '.$status.'</div>';
        header("Refresh:3; url=./uploadDataPegawai");
    } ?>
</div>
<div class='col-md-12'>
    <div class='col-md-6'>
    <form action="<?php echo base_url();?>portal.php/app/upload/" method="post" enctype="multipart/form-data">
        <div class='col-md-12'>
            <input class='col-md-8' type="file" id="myFile" name="file" required/>
            <input class='col-md-4' type="submit" value="Upload file"/>
        </div>
    </form>
    </div>
    <div class='col-md-6'>
        <input type="button" class="btn btn-success" value=" Kembali " onclick="window.location.href='<?php echo site_url(); ?>'" style="float: right;"> 
    </div>
</div>
<div class='col-md-12' style='height:15px;'></div>
<div class='col-md-12'>
  <div class='col-md-6'></div>
  <div class='col-md-6'>
    <!--<input type="button" class="btn btn-default" value=" Download Template " onclick="window.location.href='download?filename=upload_data_pegawai.xls'" style="float: right;">--> 
</div>
</div>
<div class='col-md-12' style='height:20px;'></div>
<div class='col-md-12'>
<table class="table table-striped">
    <thead>
        <tr>
             <th>No</th>
             <th>Nik</th>
             <th>Nama</th>
             <th>Direktorat</th>
             <th>Divisi</th>
             <th>Departemen</th>
             <th>Seksi</th>
             <th>Sub Seksi</th>
             <th>Jabatan</th>
             <th>Tgl Masuk</th>
             <th>Status Pegawai</th>
             <th>Jenis Kelamin</th>
             <th>Lokasi</th>
        </tr>
     </thead>
    <tbody>
        <?php 
        $no = 1;
        foreach($show as $row): ?>
        <tr>
          <td><?php echo $no++; ?></td>
          <td><?php echo $row->nik;?></td>
          <td><?php $f = $row->namadepan; echo $f ; ?></td>
          <td><?php echo $row->direktorat; ?></td>
          <td><?php echo $row->divisi; ?></td>
          <td><?php echo $row->departemen; ?></td>
          <td><?php echo $row->seksi; ?></td>
          <td><?php echo $row->subseksi; ?></td>
          <td><?php echo $row->jabatan; ?></td>
          <td><?php echo $row->tglmulai; ?></td>
          <td><?php echo $row->statuspegawai; ?></td>
          <td><?php if ($row->jeniskelamin == 'L') { echo 'Laki-laki'; } else { echo 'Perempuan'; } ?></td>
          <td><?php echo $row->lokasi; ?></td>
        </tr>
        <?php endforeach; ?>
        <?php echo $links; ?>
    </tbody>
</table>
</div>
</div>
<script>
function myFunction() {
    var x = document.getElementById("myFile").required;
    document.getElementById("demo").innerHTML = x;
}
</script>
<style type="text/css">
h3 {
    font-size: 24px;
    color: #73879C
}
.readtext{
    border: none;
    background: none;
    -webkit-box-shadow: none;
}
.btn-success {
    background: #26B99A;
    border: 1px solid #169F85
}
thead {
    background: #26B99A;
    border: 1px solid #F5F5F5;
    font-size:14px;
    color: #F5F5F5;
     font-family: "Arial", Times, serif;
}
td {
  font-size:14px; 
  border: none; 
  background: none;
  -webkit-box-shadow: none;
}
</style>
</body>
</html>