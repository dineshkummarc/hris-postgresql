<html>
<head>
    <head>
    <title>Change Password</title>
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
<div class="col-md-6 col-md-push-3">
    <div class="col-lg-12" style="height:50px;"></div>
        <div class="col-md-12 well" >
                <?php if($status == "berhasil") {
                    echo '<div class="col-lg-12 alert alert-dismissable alert-success">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          <strong>berhasil </strong> ubah password 
                          </div>'; 
                    header("Refresh:3; url=index"); 
                } ?>
            <center><div style="color:red"><?php echo validation_errors('<div class="col-lg-12 alert alert-dismissable alert-danger">','</div>'); ?></div></center>
            <?php echo form_open(''); ?>
                <center><h3> Ubah Password </h3></center>
                <div class="col-lg-12" style="height:50px;"></div>
                <!--  username. -->         
                <div class="form-group">                        
                    <label for="username" class="col-lg-2 control-label"> Password Lama </label>
                    <div class="col-lg-10">
                    <input type="password" class="form-control" placeholder="Password Lama" name="oldpassword" value="" required autofocues id="id1"><br>                
                    </div>
                </div>
                <div class="form-group">                        
                    <label for="username" class="col-lg-2 control-label"> Password Baru </label>
                    <div class="col-lg-10">
                    <input type="password" class="form-control" placeholder="Password Baru" name="password" value="" required autofocues id="id2"><br>               
                    </div>
                </div>
                <div class="form-group">                        
                    <label for="username" class="col-lg-2 control-label"> Konfirmasi Password </label>
                    <div class="col-lg-10">
                    <input type="password" class="form-control" placeholder="Konfirmasi Password" name="passconf" value="" required autofocues id="id3"><br>               
                    </div>
                </div>      
                <!--  button. -->
                <div class="form-group">
                      <div class="col-lg-10 col-lg-offset-2">
                        <button class="btn btn-default" type="submit" onClick="return doconfirm();"> Simpan </button>
                        <input type="button" class="btn btn-success" value=" Kembali " onclick="window.location.href='<?php echo site_url(); ?>'">  
                       </div>
                </div>
             </form>
             <div class="col-lg-12" style="height:50px;"></div>
        </div>
</div>
</aside>

<script type="text/javascript">
function doconfirm()
        {
            var inpObj1 = document.getElementById("id1");
            var inpObj2 = document.getElementById("id2");
            var inpObj3 = document.getElementById("id3");

            if (!inpObj1.checkValidity()) {
                document.getElementById("demo").innerHTML = inpObj1.validationMessage;
            } else if (!inpObj2.checkValidity()) {
                document.getElementById("demo").innerHTML = inpObj2.validationMessage;
            } else if (!inpObj3.checkValidity()) {
                document.getElementById("demo").innerHTML = inpObj3.validationMessage;
            } else {
                job=confirm("Apakah anda yakin ingin mengubah password ?");
                    if(job!=true)
                    {
                        return false;
                    } else {
                        function validateForm() {
                            var x = document.forms["myForm"]["fname"].value;
                            if (x == "") {
                                return false;
                            }
                        }
                    }
            } 
        }
</script>

<style type="text/css">
h3 {
    font-size: 24px;
    color: #73879C
}
label {
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
</style>
</body>