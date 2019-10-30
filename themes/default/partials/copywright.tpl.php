<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

	<p>
		&copy; 2016-<?php echo date("Y");?> <a target="_blank" href="https://opendesa.id">OpenDesa</a> <i class="fa fa-circle" style="font-size: smaller;"></i>
		<a target="_blank" href="https://github.com/OpenSID/OpenSID">OpenSID</a> <?php echo AmbilVersi()?>
		<?php if ($this->db->table_exists('users')): ?>
                <br>
	  <a target="_blank" href="<?php echo base_url()?>index.php/auth/login">Aplikasi Sistem Informasi Desa (SID)</a> ini dikembangkan oleh <a target="_blank" href="https://www.facebook.com/groups/OpenSID/">Komunitas OpenSID</a></br>
		<?php else: ?>
		<br>
	  <a target="_blank" href="<?php echo base_url()?>index.php/migrate_rbac">Aplikasi Sistem Informasi Desa (SID)</a> ini dikembangkan oleh <a target="_blank" href="https://www.facebook.com/groups/OpenSID/">Komunitas OpenSID</a></br>
		<?php endif; ?>

	  <?php if (file_exists('mitra')): ?>
	  	Hosting didukung <a target="_blank" href="https://idcloudhost.com"><img src="<?= base_url('/assets/images/Logo-IDcloudhost.png')?>" height='15px' alt="Logo-IDCloudHost" title="Logo-IDCloudHost"></a>
	  <?php endif; ?>
	</p>
