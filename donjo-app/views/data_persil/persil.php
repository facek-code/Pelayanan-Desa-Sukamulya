<script>
	$( function() {
		$( "#cari" ).autocomplete({
			source: function( request, response ) {
				$.ajax( {
					type: "POST",
					url: '<?= site_url("data_persil/autocomplete")?>',
					dataType: "json",
					data: {
						cari: request.term
					},
					success: function( data ) {
						response( JSON.parse( data ));
					}
				} );
			},
			minLength: 2,
		} );
	} );
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Daftar Persil <?= ucwords($this->setting->sebutan_desa)?> <?= $desa["nama_desa"];?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Daftar Persil</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<?php $this->load->view('data_persil/menu_kiri.php')?>
				</div>
				<div class="col-md-8 col-lg-9">
					<div class="box box-info">
						<div class="box-header">
							<h4 class="text-center"><strong>DAFTAR PERSIL</strong></h4>
						</div>							
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<a href="<?= site_url("data_persil/cetak_persil/$o")?>" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank">
											<i class="fa fa-print"></i>Cetak
										</a>
										<a href="<?= site_url("data_persil/excel/persil/$o")?>" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" target="_blank">
											<i class="fa fa-download"></i>Unduh
										</a>
										<a href="<?= site_url("data_persil/persil_clear")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("data_persil/search")?>');$('#'+'mainform').submit();}">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("data_persil/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th>No</th>
																	<th>Aksi</th>
																	<th>Nomor Persil</th>
																	<th>Kelas Tanah</th>
																	<th>Lokasi</th>
																	<th>Jumlah Bidang</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($persil as $item): ?>
																	<tr>
																		<td><?= $item['no']?></td>
																		<td nowrap>
																			<?php if ($item['jml_bidang'] > 0): ?>
																				<a href="<?= site_url("data_persil/rincian/".$item["id"])?>" class="btn bg-purple btn-flat btn-sm" title="Rincian"><i class="fa fa-bars"></i></a>
																			<?php else: ?>
																				<a class="btn bg-purple btn-flat btn-sm" disabled title="Rincian"><i class="fa fa-bars"></i></a>
																			<?php endif ?>
																			<a href="<?= site_url("data_persil/form/".$item["id"])?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a>
																			<?php if ($item['jml_bidang'] == 0): ?>
																				<a href="#" data-href="<?= site_url("data_persil/hapus/".$item["id"])?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																			<?php else: ?>
																				<a class="btn bg-maroon btn-flat btn-sm" disabled><i class="fa fa-trash-o"></i></a>
																			<?php endif ?>
																			</td>
																			<td><?= $item['nomor']?></td>
																			<td><?= $persil_kelas[$item["kelas"]]['kode']?></td>
																			<td><?= $item['alamat'] ?: $item['lokasi']?></td>
																			<td><?= $item['jml_bidang']?></td>
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
														<form id="paging" action="<?= site_url("data_persil/persil/$kat/$mana")?>" method="post" class="form-horizontal">
															<label>
																Tampilkan
																<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
																	<option value="20" <?php selected($per_page, 20); ?> >20</option>
																	<option value="50" <?php selected($per_page, 50); ?> >50</option>
																	<option value="100" <?php selected($per_page, 100); ?> >100</option>
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
																<li><a href="<?= site_url("data_persil/persil/$kat/$mana/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
															<?php endif; ?>
															<?php if ($paging->prev): ?>
																<li><a href="<?= site_url("data_persil/persil/$kat/$mana/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
															<?php endif; ?>
															<?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
																<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("data_persil/persil/$kat/$mana/$i/$o")?>"><?= $i?></a></li>
															<?php endfor; ?>
															<?php if ($paging->next): ?>
																<li><a href="<?= site_url("data_persil/persil/$kat/$mana/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
															<?php endif; ?>
															<?php if ($paging->end_link): ?>
																<li><a href="<?= site_url("data_persil/persil/$kat/$mana/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
															<?php endif; ?>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>
