<div class="left_col scroll-view">
	<br />
	<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
		<div class="col-lg-12" style="width:230px;"></div>
		<div class="menu_section">
			<br />
			<br />			
			
			<!--
			<ul class="nav side-menu">
			  <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu">
				  <li><a href="index.html">Dashboard</a></li>
				  <li><a href="index2.html">Dashboard2</a></li>
				  <li><a href="index3.html">Dashboard3</a></li>
				</ul>
			  </li>
			</ul>
			-->
			
			<ul class="nav side-menu">
				<li <?php if($pages == "pengajuan") echo 'class="active"';?> ><a href="<?php echo site_url(); ?>/pengajuan"><i class="fa fa-edit"></i> Pengajuan Cuti </a></li>				
				<li <?php if($pages == "history") echo 'class="active"';?>><a href="<?php echo site_url(); ?>/history"><i class="fa fa-list"></i> History Cuti </a></li>
				
				<?php if($this->session->userdata('aksesid_eservices') == '12' or $this->session->userdata('aksesid_eservices') == '13') : ?>
					<li <?php if($pages == "listapprove") echo 'class="active"';?>><a href="<?php echo site_url(); ?>/listapprove"><i class="fa fa-check-square-o"></i> Approval Cuti </a></li>
				<?php endif; ?>
				<!--<li><a href="<?php echo config_item('url_logout'); ?>"><i class="fa fa-sign-out"></i> Logout </a></li>-->
				<li><a href="<?php echo base_url(); ?>"><i class="fa fa-sign-out"></i> Kembali </a></li>
			</ul>
		</div>						
	</div>
</div>
