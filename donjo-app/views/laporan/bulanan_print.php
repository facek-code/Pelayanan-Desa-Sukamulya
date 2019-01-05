<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Cetak Laporan Bulanan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
  </head>
  <style type="text/css">
    .underline { text-decoration: underline; }
    td.judul {font-size: 14pt; font-weight: bold;}
    td.judul2 {font-size: 12pt; font-weight: bold;}
    td.text-bold {font-weight: bold;}
    table.tftable td.no-border {
      border: 0px;
      border-style: hidden;
    }
    table.tftable
    {
      margin-top: 5px;
      font-size:12px;
      color:<?= (isset($warna_font) ? $warna_font : "");?>;
      width:100%;
      border-width: 1px;
      border-style: solid;
      border-color: <?= (isset($warna_border) ? $warna_border : "");?>;
      border-collapse: collapse;
    }
    table.tftable.lap-bulanan
    {
      border-width: 3px;
    }
    table.tftable tr.thick
    {
      border-width: 3px; border-style: solid;
    }
    table.tftable th.thick
    {
      border-width: 3px;
    }
    table.tftable th.thick-kiri
    {
      border-left: 3px solid <?= (isset($warna_border) ? $warna_border : "");?>;
    }
    table.tftable td.thick-kanan
    {
      border-right: 3px solid <?= (isset($warna_border) ? $warna_border : "");?>;
    }
    table.tftable td.angka
    {
      text-align: right;
      }
    table.tftable th
    {
      background-color:<?= (isset($warna_background) ? $warna_background : "");?>;padding: 3px;border: 1px solid <?= (isset($warna_border) ? $warna_border : "");?>;text-align:center;
    }
    /*table.tftable tr {background-color:#ffffff;}*/
    table.tftable td
    {
      padding: 8px;border: 1px solid <?= (isset($warna_border) ? $warna_border : "");?>;
    }
  </style>

  <body>
    <div id="container">
      <!-- Print Body -->
      <div id="body">
        <table>
          <tr>
            <td colspan="10" class='text-bold'>PEMERINTAH KABUPATEN/KOTA</td>
            <td colspan="2" class="text-bold"><span style="float: right; border: solid 1px black; font-size: 12pt; text-align: center; padding: 5px 20px;">LAMPIRAN A-9</span></td>
          </tr>
          <tr>
            <td colspan="2" class="text"><span style="border-bottom: 2px solid;"><?= strtoupper($config[0]['nama_kabupaten'])?></span></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
            <td colspan="10" class="judul" style="padding-bottom: 10px;"><span style="border-bottom: 2px solid;">LAPORAN BULANAN DESA/KELURAHAN</span></td>
          <tr>
<!--           <tr>
            <td colspan="12" class="judul">&nbsp;</td>
          <tr> -->
          <?php foreach ($config as $data): ?>
            <tr>
              <td colspan="2" width="32%">&nbsp;</td>
              <td colspan="3" width="15%" class="text-bold">Desa/Kelurahan</td>
              <td colspan="7" width="53%">: <?= strtoupper($data['nama_desa'])?></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
              <td colspan="3" class="text-bold">Kecamatan</td>
              <td colspan="7">: <?= strtoupper($data['nama_kecamatan'])?></td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <td colspan="2">&nbsp;</td>
            <td colspan="3" class="text-bold">Laporan Bulan</td>
            <td colspan="7">: <?= $bln?> <?= $tahun?></td>
          </tr>
        </table>
        <br>
        <?php include ("donjo-app/views/laporan/tabel_bulanan.php"); ?>
        <table class="tftable">
          <tr><td colspan="13" class="no-border">&nbsp;</td></tr>
          <tr>
            <td colspan="13" class="judul2" style="padding-bottom: 10px;"><span style="border-bottom: 2px solid;">PERINCIAN PINDAH</span></td>
          </tr>
          <tr>
            <th rowspan="2" width='2%' class="text-center">NO</th>
            <th rowspan="2" width='20%' class="text-center">KETERANGAN</th>
            <th colspan="3" class="text-center">PENDUDUK</th>
            <th colspan="3" class="text-center">KELUARGA (KK)</th>
            <td colspan="2" width="10%">&nbsp;</td>
            <td colspan="3" class="no-border"><?= ucwords($this->setting->sebutan_desa)?> <?= $data['nama_desa']?>, <?= tgl_indo(date("Y m d"))?></td>
          </tr>
          <tr>
            <th class="text-center">L</th>
            <th class="text-center">P</th>
            <th class="text-center">L+P</th>
            <th class="text-center">L</th>
            <th class="text-center">P</th>
            <th class="text-center">L+P</th>
            <td colspan="2">&nbsp;</td>
            <td colspan="3" class="no-border">KEPALA DESA/LURAH <?= $data['nama_desa']?></td>
          </tr>
          <tr>
            <td class="text-center">1</td>
            <td>Pindah keluar Desa/Kelurahan</td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L']),'-')?></td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L'])+($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK_L'],'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK_P'],'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK'],'-')?></td>
            <td colspan="5">&nbsp;</td>
          </tr>
          <tr>
            <td class="text-center">2</td>
            <td>Pindah keluar Kecamatan</td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L']),'-')?></td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L'])+($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK_L'],'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK_P'],'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK'],'-')?></td>
            <td colspan="5">&nbsp;</td>
          </tr>
          <tr>
            <td class="text-center">3</td>
            <td>Pindah keluar Kabupaten/Kota</td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L']),'-')?></td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L'])+($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK_L'],'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK_P'],'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK'],'-')?></td>
            <td colspan="5">&nbsp;</td>
          </tr>
          <tr>
            <td class="text-center">4</td>
            <td>Pindah keluar Provinsi</td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L']),'-')?></td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L'])+($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK_L'],'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK_P'],'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK'],'-')?></td>
            <td colspan="5">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" class="text-center text-bold">JUMLAH:</td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L']),'-')?></td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
            <td class="text-right"><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L'])+($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK_L'],'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK_P'],'-')?></td>
            <td class="text-right"><?= show_zero_as($penduduk_awal['KK'],'-')?></td>
            <td colspan="2">&nbsp;</td>
            <td colspan="3" class="no-border">( <?= $pamong_ttd['pamong_nama']?> )</td>
          </tr>
          <tr>
            <td colspan="10" class="no-border">&nbsp;</td>
            <td colspan="3" class="no-border">NIP/NIAP <?= $pamong_ttd['pamong_niap_nip']?> </td>
          </tr>
        </table>
      </div>
    </div>
  </body>
</html>
