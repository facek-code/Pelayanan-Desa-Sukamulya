<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Cetak Laporan Bulanan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
  </head>
  <style type="text/css">
    .underline { text-decoration: underline; }
    td.judul {font-size: 14pt; font-weight: bold;};
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
            <td colspan="2">&nbsp;</td>
            <td colspan="10" class="judul"><span style="border-bottom: 2px solid">LAPORAN BULANAN DESA/KELURAHAN</span></td>
          <tr>
          <tr>
            <td colspan="12" class="judul">&nbsp;</td>
          <tr>
          <?php foreach ($config as $data): ?>
            <tr>
              <td colspan="2" width="32%">&nbsp;</td>
              <td colspan="3" width="15%" class="judul">Desa/Kelurahan</td>
              <td colspan="7" width="53%">: <?= strtoupper($data['nama_desa'])?></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
              <td colspan="3" class="judul">Kecamatan</td>
              <td colspan="7">: <?= strtoupper($data['nama_kecamatan'])?></td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <td colspan="2">&nbsp;</td>
            <td colspan="3" class="judul">Laporan Bulan</td>
            <td colspan="7">: <?= $bln?> <?= $tahun?></td>
          </tr>
        </table>
        <br>
        <?php include ("donjo-app/views/laporan/tabel_bulanan.php"); ?>
        <table>
          <tr><td colspan="12">&nbsp;</td></tr>
          <tr><td colspan="12">&nbsp;</td></tr>
          <tr>
            <td colspan="9" width="70%">&nbsp;</td>
            <td colspan="3"><?= ucwords($this->setting->sebutan_desa)?> <?= $data['nama_desa']?>, <?= tgl_indo(date("Y m d"))?></td>
          </tr>
          <tr>
            <td colspan="9">&nbsp;</td>
            <td colspan="3">KEPALA DESA/LURAH <?= $data['nama_desa']?></td>
          </tr>
          <tr><td colspan="12">&nbsp;</td></tr>
          <tr><td colspan="12">&nbsp;</td></tr>
          <tr><td colspan="12">&nbsp;</td></tr>
          <tr><td colspan="12">&nbsp;</td></tr>
          <tr>
            <td colspan="9">&nbsp;</td>
            <td colspan="3">( <?= $pamong_ttd['pamong_nama']?> )</td>
          </tr>
          <tr>
            <td colspan="9">&nbsp;</td>
            <td colspan="3">NIP/NIAP <?= $pamong_ttd['pamong_niap_nip']?> </td>
          </tr>
        </table>

      </div>
    </div>
  </body>
</html>
