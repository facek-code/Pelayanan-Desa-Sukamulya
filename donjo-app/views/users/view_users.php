<script>
	$(function()
	{
		var keyword = <?= $keyword?> ;
		$( "#cari" ).autocomplete(
		{
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Manajemen Pengguna</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Manajemen Pengguna</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('users/create_user')?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Pengguna Baru</a>
						<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("users/delete_user_all/")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
                                                                                        <div class="col-sm-6">
											
<select class="form-control input-sm" name="filter" onchange="formAction('mainform','<?=site_url('users/filter')?>')">
														<option value="">Semua</option>
														<?php foreach ($list_group AS $list): ?>
															<option value="<?= $list['id']?>" <?php if ($filter == $list['id']): ?>selected<?php endif ?>><?= ucfirst($list['name'])?></option>
														<?php endforeach;?>
													</select>                                                                                        </div>
											<div class="col-sm-6">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url('users/search')?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action','<?=site_url("users/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table  class="table table-bordered table-striped dataTable table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
<th><input type="checkbox" id="checkall"/></th>
<th>No</th>
																<th>Aksi</th>
<?php if ($o==2): ?>
<th width='20%'><a href="<?= site_url("users/index/$cat/$p/1")?>">Username <i class='fa fa-sort-asc fa-sm'></i></a></th>
<?php elseif ($o==1): ?>
<th width='20%'><a href="<?= site_url("users/index/$cat/$p/2")?>">Username <i class='fa fa-sort-desc fa-sm'></i></a></th>
<?php else: ?>
<th width='20%'><a href="<?= site_url("users/index/$cat/$p/1")?>">Username <i class='fa fa-sort fa-sm'></i></a></th>
<?php endif; ?>

<?php if ($o==4): ?>
<th width='15%'><a href="<?= site_url("users/index/$cat/$p/3")?>">Nama <i class='fa fa-sort-asc fa-sm'></i></a></th>
<?php elseif ($o==3): ?>
<th width='15%'><a href="<?= site_url("users/index/$cat/$p/4")?>">Nama <i class='fa fa-sort-desc fa-sm'></i></a></th>
<?php else: ?>
<th width='15%'><a href="<?= site_url("users/index/$cat/$p/3")?>">Nama <i class='fa fa-sort fa-sm'></i></a></th>
<?php endif; ?>

<?php if ($o==6): ?>
<th width='15%'><a href="<?= site_url("users/index/$cat/$p/5")?>">Email <i class='fa fa-sort-asc fa-sm'></i></a></th>
<?php elseif ($o==5): ?>
<th width='15%'><a href="<?= site_url("users/index/$cat/$p/6")?>">Email <i class='fa fa-sort-desc fa-sm'></i></a></th>
<?php else: ?>
<th width='15%'><a href="<?= site_url("users/index/$cat/$p/5")?>">Email <i class='fa fa-sort fa-sm'></i></a></th>
<?php endif; ?>

<?php if ($o==8): ?>
<th width='15%'><a href="<?= site_url("users/index/$cat/$p/7")?>">Group <i class='fa fa-sort-asc fa-sm'></i></a></th>
<?php elseif ($o==7): ?>
<th width='15%'><a href="<?= site_url("users/index/$cat/$p/8")?>">Group <i class='fa fa-sort-desc fa-sm'></i></a></th>
<?php else: ?>
<th width='15%'><a href="<?= site_url("users/index/$cat/$p/7")?>">Group <i class='fa fa-sort fa-sm'></i></a></th>
<?php endif; ?>

<th>Login Terakhir</th>
															</tr>
														</thead>
														<tbody>
<?php foreach ($main as $data): ?>
<tr>
<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
<td><?=$data['no']?></td>

<td nowrap>
<a href="<?= site_url('users/edit_user')?>/<?=$data['id']?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a>

<?php if ($data['grup']!='admin'): ?>

<?php if ($data['active'] == '0'): ?>
<a href="<?=site_url('users/activate/'.$data['id'])?>" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan Pengguna"><i class="fa fa-lock">&nbsp;</i></a>
<?php elseif ($data['active'] == '1'): ?>														<a href="<?=site_url('users/deactivate/'.$data['id'])?>" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan Pengguna"><i class="fa fa-unlock"></i></a>
<?php endif; ?>

<a href="#" data-href="<?=site_url("users/delete_user/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>

<?php endif; ?>

</td>

<td><?=$data['username']?></td>
<td><?=$data['first_name']?></td>
<td><?=$data['email']?></td>
<td><?=$data['grup']?></td>
<td><?php echo date('d M Y', $data['last_login'])?></td>
</tr>

<?php endforeach; ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</form>
									<div class="row">
										<div class="col-sm-6">
											<div class="dataTables_length">
												<form id="paging" action="<?= site_url("users")?>" method="post" class="form-horizontal">
													<label>
														Tampilkan
														<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
															<option value="20" <?php selected($per_page,20); ?> >20</option>
															<option value="50" <?php selected($per_page,50); ?> >50</option>
															<option value="100" <?php selected($per_page,100); ?> >100</option>
														</select>
														Dari
														<strong><?= $paging->num_rows?></strong>
														Total Data
													</label>
												</form>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="dataTables_paginate paging_simple_numbers">
												<ul class="pagination">
													<?php if ($paging->start_link): ?>
														<li><a href="<?=site_url("users/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
													<?php endif; ?>
													<?php if ($paging->prev): ?>
														<li><a href="<?=site_url("users/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
													<?php endif; ?>
													<?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
														<li><a href="<?= site_url("users/index/$cat/$i/$o")?>"><?= $i?></a></li>
													<?php endfor; ?>
													<?php if ($paging->next): ?>
														<li><a href="<?=site_url("users/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
													<?php endif; ?>
													<?php if ($paging->end_link): ?>
														<li><a href="<?=site_url("users/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
													<?php endif; ?>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class='modal fade' id='confirm-delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
										<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
									</div>
									<div class='modal-body btn-info'>
										Apakah Anda yakin ingin menghapus data ini?
									</div>
									<div class='modal-footer'>
										<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
										<a class='btn-ok'>
											<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" id="ok-delete"><i class='fa fa-trash-o'></i> Hapus</button>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

