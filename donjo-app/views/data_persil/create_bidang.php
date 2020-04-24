
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengelolaan Data C-Desa <?=ucwords($this->setting->sebutan_desa)?> <?= $desa["nama_desa"];?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?=site_url('cdesa/clear')?>"> Daftar C-Desa</a></li>
			<li><a href="<?=site_url('cdesa/rincian/'. $cdesa[id])?>"> Rincian C-Desa</a></li>
			<li class="active">Pengelolaan Data C-Desa</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<?php $this->load->view('data_persil/menu_kiri.php')?>
			</div>
			<div class="col-md-9">
				<div class="box box-info">
					<div class="box-body">
						<div class="box-header with-border">
							<a href="<?= site_url('cdesa/rincian/'. $cdesa[id])?>" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Rincian C-Desa"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Rincian C-Desa</a>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="box-header with-border">
									<h3 class="box-title">Rincian C-Desa</h3>
								</div>
								<?php if ($pemilik): ?>
									<div class="form-group">
										<label for="nama" class="col-sm-3 control-label">Pemilik 1</label>
										<div class="col-sm-9">
											<div class="form-group">
												<label class="col-sm-3 control-label">Nama Penduduk</label>
												<div class="col-sm-8">
													<input  class="form-control input-sm" type="text" placeholder="Nama Pemilik" value="<?= $pemilik["nama"] ?>" disabled >
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">NIK Pemilik</label>
												<div class="col-sm-8">
													<input  class="form-control input-sm" type="text" placeholder="NIK Pemilik" value="<?= $pemilik["nik"] ?>" disabled >
												</div>
											</div>
											<div class="form-group">
												<label for="alamat"  class="col-sm-3 control-label">Alamat Pemilik</label>
												<div class="col-sm-8">
													<textarea  class="form-control input-sm" placeholder="Alamat Pemilik" disabled><?= "RT ".$pemilik["rt"]." / RT ".$pemilik["rw"]." - ".strtoupper($pemilik["dusun"]) ?></textarea>
												</div>
											</div>
										</div>
									</div>
								<?php endif; ?>

								<form name='mainform' action="<?= site_url('cdesa/simpan_bidang/'.$cdesa['id'].'/'.$bidang['id'])?>" method="POST"  id="validasi" class="form-horizontal">
									<div class="box-body">
										<input name="jenis_pemilik" type="hidden" value="1">
										<input type="hidden" name="nik_lama" value="<?= $pemilik["nik_lama"] ?>"/>
										<input type="hidden" name="nik" value="<?= $pemilik["nik"] ?>"/>
										<input type="hidden" name="id_pend" value="<?= $pemilik["id"] ?>"/>

										<div class="form-group">
											<label for="c_desa" class="col-sm-3 control-label">Nomor C-DESA</label>
											<div class="col-sm-8">
												<input class="form-control input-sm angka required" type="text" placeholder="Nomor Surat C-DESA" name="c_desa" value="<?= ($cdesa['nomor'])?sprintf("%04s", $cdesa["nomor"]): NULL ?>" disabled >
											</div>
										</div>

										<div class="form-group">
											<label for="nama_kepemilikan" class="col-sm-3 control-label">Nama Kepemilikan</label>
											<div class="col-sm-8">
												<input class="form-control input-sm nama required" type="text" placeholder="Nama pemilik di Surat C-DESA" name="nama_kepemilikan" value="<?= ($cdesa["nama_kepemilikan"])?sprintf("%04s", $cdesa["nama_kepemilikan"]): NULL ?>" disabled >
											</div>
										</div>
									</div>

									<div class="box-header with-border">
										<h3 class="box-title">Tambah Bidang Persil</h3>
									</div>
									<div class="panel box box-default">
										<div class="box-header with-border">
											<h4 class="box-title">
												<a data-toggle="collapse" data-parent="#accordion" href="#persil">Persil</a>
											</h4>
										</div>
										<div id="persil" class="panel-collapse">
											<div class="box-body">
												<div class="form-group">
													<label for="no_persil" class="col-sm-3 control-label">Nomor Persil</label>
													<div class="col-sm-8">
														<input name="no_persil" class="form-control input-sm angka required" type="text" placeholder="Nomor Surat Persil" name="nama" value="<?= $persil["nomor"] ?>">
													</div>
												</div>
												<div class="form-group">
													<label for="kelas"  class="col-sm-3 control-label">Tipe Tanah</label>
													<div class="col-sm-4">
														<select class="form-control input-sm" id="tipe" name="tipe" type="text" placeholder="Tuliskan Kelas Tanah" >
															<option value>-- Pilih Tipe Tanah--</option>
															<option value="BASAH" <?php selected('BASAH', $persil_kelas[$persil['kelas']]["tipe"]) ?>>Tanah Basah</option>
															<option value="KERING" <?php selected('KERING', $persil_kelas[$persil['kelas']]["tipe"]) ?>>Tanah Kering</option>
															</select>
													</div>
												</div>
												<div class="form-group">
													<label for="kelas" class="col-sm-3 control-label">Kelas Tanah</label>
													<div class="col-sm-4">
														<select class="form-control input-sm required" id="kelas" name="kelas" type="text" placeholder="Tuliskan Kelas Tanah" >
															<option value="">-- Pilih Jenis Kelas--</option>
															<?php foreach ($persil_kelas  as $item): ?>
																<option value="<?= $item['id'] ?>" <?php selected($item['id'], $persil["kelas"]); ?>><?= $item['kode'].' '.$item['ndesc']?></option>
															<?php endforeach;?>
														</select>
													</div>
												</div>
												<div class="form-group ">
													<label for="pid" class="col-sm-3 control-label">Lokasi Tanah</label>
													<div class="btn-group col-sm-8 kiri" data-toggle="buttons">
														<label  class="btn btn-info btn-flat btn-sm col-sm-3 form-check-label <?= $persil_detail["lokasi"]?NULL : 'active'  ?>">
															<input type="radio" name="pilihan" class="form-check-input" type="radio" value="1" autocomplete="off" onchange="pilih_lokasi(this.value);"> Pilih Lokasi
														</label>
														<label  class="btn btn-info btn-flat btn-sm col-sm-3 form-check-label  <?= $persil_detail["lokasi"]?'active' : NULL  ?>">
															<input type="radio" name="pilihan" class="form-check-input" type="radio" value="2" autocomplete="off" onchange="pilih_lokasi(this.value);"> Tulis Manual
														</label>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label"></label>
													<div id= "pilih" <?= $persil_detail["lokasi"]?'style="display:none"' : NULL  ?>>
														<div class="col-sm-4" >
															<select class="form-control input-sm select2 required" name="id_wilayah" >
																<option width="100%" value >-- Pilih Lokasi Tanah--</option>
																<?php foreach ($persil_lokasi as $key=>$item): ?>
																	<option value="<?= $item["id"] ?>" <?php selected($item["id"], $persil["id_wilayah"]) ?>><?= strtoupper($item["dusun"])." - RW ".$item["rw"]." / RT ".$item["rt"] ?></option>
																<?php endforeach;?>
															</select>
														</div>
													</div>
													<div id= "manual" <?= $persil_detail["lokasi"]?NULL: 'style="display:none"' ?> >
														<div class="col-sm-8">
															<textarea  id="lokasi" class="form-control input-sm" type="text" placeholder="Lokasi" name="lokasi" ><?= $persil_detail["lokasi"] ?></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel box box-default">
										<div class="box-header with-border">
											<h4 class="box-title">
												<a data-toggle="collapse" data-parent="#accordion" href="#bidang_persil">Bidang Persil</a>
											</h4>
										</div>
										<div id="bidang_persil" class="panel-collapse">
											<div class="box-body">
												<div class="form-group">
													<label for="jenis_bidang_persil" class="col-sm-3 control-label">Jenis Tanah</label>
													<div class="col-sm-4">
														<select class="form-control input-sm required" name="jenis_bidang_persil" >
															<option value>-- Pilih Jenis Tanah--</option>
															<?php foreach ($persil_jenis as $key => $item): ?>
																<option value="<?= $item['id'] ?>" <?php selected($key, $bidang["jenis_bidang_persil"]) ?>><?= $item['nama']?></option>
															<?php endforeach;?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label for="no_bidang_persil" class="col-sm-3 control-label">Nomor Bidang Persil</label>
													<div class="col-sm-4">
														<input name="no_bidang_persil" type="text" class="form-control input-sm digits required" placeholder="Nomor Bidang Persil" maxlength="2" value="<?= $bidang["no_bidang_persil"] ?>">
													</div>
												</div>												
												<div class="form-group">
													<label for="sid" class="col-sm-3 control-label">Peruntukan</label>
													<div class="col-sm-4">
														<select class="form-control input-sm select2 required" id="peruntukan" name="peruntukan">
															<option value >-- Pilih Peruntukan--</option>
															<?php foreach ($persil_peruntukan as $key => $item): ?>
																<option value="<?= $key?>" <?php selected($key, $bidang["peruntukan"]) ?>><?= $item['nama']?></option>
															<?php endforeach;?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label for="no_objek_pajak" class="col-sm-3 control-label">Nomor Objek Pajak</label>
													<div class="col-sm-8">
														<input class="form-control input-sm angka" type="text" placeholder="Nomor Objek Pajak" name="no_objek_pajak" value="<?= $bidang["no_objek_pajak"] ?>">
													</div>
												</div>
												<div class="form-group">
													<label for="no_sppt_pbb" class="col-sm-3 control-label">Nomor SPPT PBB</label>
													<div class="col-sm-8">
														<input name="no_sppt_pbb" type="text" class="form-control input-sm required" placeholder="Tuliskan Nomor SPPT PBB" value="<?= $bidang["no_sppt_pbb"] ?>">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel box box-default">
										<div class="box-header with-border">
											<h4 class="box-title">
												<a data-toggle="collapse" data-parent="#accordion" href="#mutasi">Mutasi</a>
											</h4>
										</div>
										<div id="mutasi" class="panel-collapse">
											<div class="box-body">										
												<div class="form-group">
													<label for="perubahan" class="col-sm-3 control-label">Sebab Dan Tanggal Perubahan</label>
													<div class="col-sm-8">
														<div class="form-group">
															<label for="tanggal_mutasi" class="col-sm-3 control-label">Tanggal Perubahan</label>
															<div class="col-sm-4">
																<div class="input-group input-group-sm date">
																	<div class="input-group-addon">
																		<i class="fa fa-calendar"></i>
																	</div>
																	<input class="form-control input-sm pull-right tgl_indo" name="tanggal_mutasi" type="text" value="<?= tgl_indo_out($bidang['tanggal_mutasi'])?>">
																</div>
															</div>
														</div>
														<div class="form-group">
															<label for="jenis_mutasi" class="col-sm-3 control-label">Sebab Mutasi</label>
															<div class="col-sm-4">
																<select class="form-control input-sm" name="jenis_mutasi" >
																	<option value>-- Pilih Sebab Mutasi--</option>
																	<?php foreach ($persil_sebab_mutasi as $key => $item): ?>
																		<option value="<?= $item['id'] ?>" <?php selected($key, $bidang['jenis_mutasi'])?>><?= $item['nama']?></option>
																	<?php endforeach;?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label for="luas" class="col-sm-3 control-label">Luas Mutasi (M2)</label>
															<div class="col-sm-9">
																<input name="luas" type="text" class="form-control input-sm luas" placeholder="Luas Mutasi (M2)" value="<?= $bidang['luas']?>">
															</div>
														</div>
														<div class="form-group">
															<label for="" class="col-sm-3 control-label"></label>
															<div class="col-sm-8">
																<p class="help-block"><code>Gunakan tanda titik (.) untuk bilangan pecahan</code></p>
															</div>
														</div>
														<div class="form-group">
															<label for="id_cdesa_keluar" class="col-sm-3 control-label">Perolehan Dari</label>
															<div class="col-sm-9">
																<input name="id_cdesa_keluar" type="text" class="form-control input-sm angka" placeholder="Nomor C-DESA dari mana bidang persil ini dimutasikan" value="<?= $bidang['cdesa_keluar'] || ''?>">
															</div>
														</div>
														<div class="form-group">
															<label for="keterangan" class="col-sm-3 control-label">Keterangan</label>
															<div class="col-sm-9">
																<textarea  name="keterangan" class="form-control input-sm" type="text" placeholder="Keterangan" name="ket" ><?= $bidang['keterangan']?></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="box-footer">
										<div class="col-xs-12">
											<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
											<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
</div>
<script>
	$(document).ready(function(){
		$('#tipe').change(function(){ 
			var id=$(this).val();
			if (!id) return;
			$.ajax({
				url : "<?=site_url('data_persil/kelasid')?>",
				method : "POST",
				data : {id: id},
				async : true,
				dataType : 'json',
				success: function(data){
					var html = '';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].id+'>'+data[i].kode+' '+data[i].ndesc+'</option>';
					}
					$('#kelas').html(html);
				}
			});
			return false;
		}); 
	});

	function pilih_lokasi(pilih)
	{
		if (pilih == 1)
		{
			$("#manual").hide();
			$("#pilih").show();
		}
		else
		{
			$("#manual").removeClass('hidden');
			$("#manual").show();
			$("#pilih").hide();
		}
	}
</script>

