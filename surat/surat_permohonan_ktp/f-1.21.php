<style type="text/css">

table.disdukcapil {
  width: 100%;
  /*border-collapse: collapse;*/
}
table.disdukcapil td {
  padding: 1px 1px 1px 3px;
}
table.disdukcapil td.padat {
  padding: 0px;
  margin: 0px;
}
table.disdukcapil td.anggota {
  border-left: solid 1px #000000;
  border-right: solid 1px #000000;
  border-top: dashed 1px #000000;
  border-bottom: dashed 1px #000000;
}
table.disdukcapil td.judul {
  border-left: solid 1px #000000;
  border-right: solid 1px #000000;
  border-top: double 1px #000000;
  border-bottom: double 1px #000000;
}
table.disdukcapil td.bawah {border-bottom: solid 1px #000000;}
table.disdukcapil td.atas {border-top: solid 1px #000000;}
table.disdukcapil td.tengah_blank {
  border-left: solid 1px #000000;
  border-right: solid 1px #000000;
}
table.disdukcapil td.pinggir_kiri {border-left: solid 1px #000000;}
table.disdukcapil td.pinggir_kanan {border-right: solid 1px #000000;}
table.disdukcapil td.kotak {border: solid 1px #000000;}
table.disdukcapil td.abu {background-color: lightgrey;}
table.disdukcapil td.kode {background-color: lightgrey;}
table.disdukcapil td.kode div {
  margin: 0px 15px 0px 15px;
  border: solid 1px black;
  background-color: white;
  text-align: center;
}
table.disdukcapil td.pakai-padding {
  padding-left: 20px;
  padding-right: 2px;
}
table.disdukcapil td.kanan { text-align: right; }
table.disdukcapil td.tengah { text-align: center; }

</style>

<page orientation="portrait" format="210x330" style="font-size: 8pt">

  <table align="right" style="padding: 5px 20px; border: solid 1px black;">
    <tr><td><strong style="font-size: 14pt;">F-1.21</strong></td></tr>
  </table>
  <p style="text-align: center; margin-top: 40px;">
      <strong style="font-size: 12pt;">FORMULIR PERMOHONAN KARTU TANDA PENDUDUK (KTP) WARGA NEGARA INDONESIA</strong>
  </p>
  <table class="disdukcapil" style="margin-top: 0px; border: 0px;">
    <col style="width: 15.2%;">
    <col style="width: 0.8%;">
    <col span="33" style="width: 2.54%;">
    <tr>
      <?php for($i=0; $i<35; $i++): ?>
        <td class="kotak">&nbsp;</td>
      <?php endfor; ?>
    </tr>

    <tr>
      <td class="kotak" colspan=48>
        <strong>Perhatian:</strong><br>
        1. Harap diisi dengan huruf cetak dan menggunakan tinta hitam<br>
        2. Untuk kolom pilihan, harap memberi tanda silang (X) pada kotak pilihan.<br>
        3. Setelah formulir ini diisi dan ditandatangani, harap diserahkan kembali ke Kantor Desa/Kelurahan
      </td>
    </tr>
    <tr>
      <td colspan="35">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="8" class="left"><strong>PEMERINTAH PROPINSI</strong></td>
      <td>:</td>
      <?php for($i=0; $i<2; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($config['kode_propinsi'][$i]))
            echo $config['kode_propinsi'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan=3>&nbsp;</td>
      <td colspan="17" class="kotak"><?php echo $config['nama_propinsi'];?></td>
      <td colspan=4>&nbsp;</td>
    </tr>

    <tr>
      <td colspan="8" class="left"><strong>PEMERINTAH KABUPATEN/KOTA</strong></td>
      <td>:</td>
      <?php for($i=0; $i<2; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($config['kode_kabupaten'][$i]))
            echo $config['kode_kabupaten'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan=3>&nbsp;</td>
      <td colspan="17" class="kotak"><?php echo $config['nama_kabupaten'];?></td>
      <td colspan=4>&nbsp;</td>
    </tr>

     <tr>
      <td colspan="8" class=" left"><strong>KECAMATAN</strong></td>
      <td>:</td>
      <?php for($i=0; $i<2; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($config['kode_kecamatan'][$i]))
            echo $config['kode_kecamatan'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan=3>&nbsp;</td>
      <td colspan="17" class="kotak"><?php echo $config['nama_kecamatan'];?></td>
      <td colspan=4>&nbsp;</td>
    </tr>

    <tr>
      <td colspan="8" class="left"><strong>KELURAHAN/DESA</strong></td>
      <td>:</td>
      <?php for($i=0; $i<4; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($config['kode_desa'][$i]))
            echo $config['kode_desa'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td>&nbsp;</td>
      <td colspan="17" class="kotak"><?php echo $config['nama_desa'];?></td>
      <td colspan=4>&nbsp;</td>
    </tr>
    <tr style="font-size: 2pt;">
      <td colspan="35">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4"><em><strong><u>PERMOHONAN KTP</u></strong></em></td>
      <td class="kotak"><?php echo ($input["alasan_permohonan_id"]=="A") ? "X" : ""?></td>
      <td colspan="4" class="tengah kotak">A. Baru</td>
      <td>&nbsp;</td>
      <td class="kotak"><?php echo ($input["alasan_permohonan_id"]=="B") ? "X" : ""?></td>
      <td colspan="6" class="tengah kotak">B. Perpanjangan</td>
      <td>&nbsp;</td>
      <td class="kotak"><?php echo ($input["alasan_permohonan_id"]=="C") ? "X" : ""?></td>
      <td colspan="6" class="tengah kotak">C. Penggantian</td>
      <td colspan="10">&nbsp;</td>
    </tr>

    <tr><td colspan=35>&nbsp;</td></tr>

    <tr>
      <td class="kotak">1. Nama Lengkap</td>
      <td>&nbsp;</td>
      <?php for($i=0; $i<33; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($individu['nama'][$i]))
            echo $individu['nama'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
    </tr>
    <tr>
      <td class="kotak">2. No. KK</td>
      <td>&nbsp;</td>
      <?php for($i=0; $i<16; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($individu['no_kk'][$i]))
            echo $individu['no_kk'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan="17">&nbsp;</td>
    </tr>
    <tr>
      <td class="kotak">3. NIK</td>
      <td>&nbsp;</td>
      <?php for($i=0; $i<16; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($individu['nik'][$i]))
            echo $individu['nik'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan="17">&nbsp;</td>
    </tr>
    <tr>
      <td class="kotak">4. Alamat</td>
      <td>&nbsp;</td>
      <td colspan="33" class="kotak"><?php echo $individu['alamat']?></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      <td colspan="33" class="kotak">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      <td colspan="2" class="tengah kotak">RT:</td>
      <td>&nbsp;</td>
      <?php for($i=0; $i<3; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($individu['rt'][$i]))
            echo $individu['rt'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td>&nbsp;</td>
      <td colspan="3" class="tengah kotak">RW:</td>
      <?php for($i=0; $i<3; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($individu['rw'][$i]))
            echo $individu['rw'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan="3">&nbsp;</td>
      <td colspan="4" class="tengah kotak">Kode Pos:</td>
      <td>&nbsp;</td>
      <?php for($i=0; $i<5; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($config['kode_pos'][$i]))
            echo $config['kode_pos'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan="7">&nbsp;</td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
      <td colspan="46" style="text-align: right">
        <?php echo str_pad(".",40,".",STR_PAD_LEFT);?>,<?php echo str_pad(".",60,".",STR_PAD_LEFT);?>
      </td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr><td colspan="48">&nbsp;</td></tr>
    <tr>
      <td colspan="4">&nbsp;</td>
      <td colspan="16" style="text-align: center;">Mengetahui</td>
      <td colspan="15">&nbsp;</td>
      <td colspan="10" style="text-align: center;">Pemohon</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr><td colspan="48">&nbsp;</td></tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="11" style="text-align: center;">Camat</td>
      <td>&nbsp;</td>
      <td colspan="13" style="text-align: center;">Kepala Desa/Lurah</td>
      <td colspan="22">&nbsp;</td>
    </tr>
    <tr><td colspan="48">&nbsp;</td></tr>
    <tr><td colspan="48">&nbsp;</td></tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="11"><?php echo str_pad(".",50,".",STR_PAD_LEFT);?></td>
      <td>&nbsp;</td>
      <td colspan="13" style="text-align: center;">(&nbsp;<?php echo padded_string_center(strtoupper($kepala_desa['pamong_nama']),30)?>&nbsp;)</td>
      <td colspan="9">&nbsp;</td>
      <td colspan="10" style="text-align: center;">(&nbsp;<?php echo padded_string_center(strtoupper($individu['nama']),30)?>&nbsp;)</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="11" style="text-align: center;"><?php echo "NIP&nbsp;&nbsp;:&nbsp;".str_pad("",40*6,"&nbsp;",STR_PAD_LEFT)?></td>
      <td>&nbsp;</td>
      <td colspan="13" style="text-align: center;"><?php echo "NIP&nbsp;&nbsp;:&nbsp;".$kepala_desa['pamong_nip']?></td>
      <td colspan="22">&nbsp;</td>
    </tr>
    <tr><td colspan="48">&nbsp;</td></tr>
    <tr><td colspan="48">&nbsp;</td></tr>
    <tr>
      <td colspan="26">&nbsp;</td>
      <td colspan="22"><strong>Tanggal Pemasukan Data</strong></td>
    </tr>
    <tr>
      <td colspan="26">&nbsp;</td>
      <td colspan="2" style="text-align: left;">Tgl.</td>
      <?php for($j=0; $j<2; $j++) : ?>
        <td class="kotak padat tengah">&nbsp;</td>
      <?php endfor; ?>
      <td colspan="2" style="text-align: center;">Bln.</td>
      <?php for($j=0; $j<2; $j++) : ?>
        <td class="kotak padat tengah">&nbsp;</td>
      <?php endfor; ?>
      <td colspan="2" style="text-align: center;">Thn.</td>
      <?php for($j=0; $j<2; $j++) : ?>
        <td class="kotak padat tengah">&nbsp;</td>
      <?php endfor; ?>
      <td colspan="12">&nbsp;</td>
    </tr>
    <tr><td colspan="48">&nbsp;</td></tr>
    <tr>
      <td colspan="26">&nbsp;</td>
      <td colspan="6" style="text-align: left;"><strong>Paraf Petugas</strong></td>
      <td colspan="6" class="kotak">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>

    <tr><td colspan="48">&nbsp;</td></tr>
    <tr><td colspan="48">&nbsp;</td></tr>
    <tr><td colspan="48">&nbsp;</td></tr>
    <tr><td colspan="48">&nbsp;</td></tr>
    <tr>
      <?php for($i=0; $i<48; $i++): ?>
        <td>&nbsp;</td>
      <?php endfor; ?>
    </tr>
  </table>

</page>