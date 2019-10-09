<div class="content-wrapper">
<div class="static-content">
   <div class="page-content">
     <section class="content-header">
		<h1>Pengaturan Hak Akses</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('user_groups')?>"><i class="fa fa-home"></i> Manajemen Group</a></li>
			<li class="active">Pengaturan Hak Akses</li>
		</ol>
	</section>
      <section class="content" id="maincontent">
         <div data-widget-group="group1">
            <div class="row">
               <div class="col-md-12">
                  <div class="panel panel-default">
                     <div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url()?>user_groups" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Manajemen Group</a>
							
					</div>
						
                  <div class="panel-body">
                     <div class="col-md-4">
                        <p><?php echo lang('edit_group_subheading');?></p>
                        <?php echo form_open(current_url());?>
                        <p>
                           <?php echo lang('edit_group_name_label', 'group_name');?> <br />
                           <?php echo form_input($group_name);?>
                           <span id="err_msg" style="color:red"></span>
                        </p>
                        <p>
                           <?php echo lang('edit_group_desc_label', 'description');?> <br />
                           <?php echo form_input($group_description);?>
                        </p>
                        <p><?php echo form_submit('submit', lang('edit_group_submit_btn'),'class="btn btn-success"');?></p>
                     </div>
				

<!-- #level 1 table -->			
												
<div class="row">
<div class="col-sm-12">
<div id="message"></div>
<div class="table-responsive">
<table class="table table-bordered table-striped dataTable table-hover">
<thead class="bg-gray disabled color-palette">
<tr>
<th><center>Id</center></th>
<th width="10%"><center>Modul</center></th>
<th><center><label><input type="checkbox" id="checkall0[]" onclick="myFunction0()"/> Lihat</label></center></th>
<th><center>Sub Modul - Hak Akses</center></th>
</tr>
</thead>
<tbody>

<?php foreach ($privileges as $privilege): ?>

<?php
$pID = $privilege[id];
$checked = null;
$item = null;
foreach($crtPrivilege as $pri)
{
if ($pID == $pri->id)
{
$checked= ' checked="checked"';
break;
}
}
?>

<tr>															
<td><?=$privilege['id']?></td>
<td><?=$privilege['modul']?></td>
<td><center><input type="checkbox" name="privlg[]" value="<?=$privilege['id']?>"<?php echo $checked;?>></center></td>

<!-- #level 2 table -->	

<td>
									
<div class="row">
<div class="col-sm-12">
<div class="table-responsive">
<table class="table table-bordered table-striped dataTable table-hover">
<?php if (count($privilege['submodul'])>0): ?>
<thead class="bg-gray disabled color-palette">
<tr>
<th><center>Id</center></th>
<th width="20%"><center>Modul</center></th>
<th><center><label><input type="checkbox" id="checkall5[]" onclick="myFunction5()"/> Lihat </label></center></th>
<th><center><label><input type="checkbox" id="checkall6[]" onclick="myFunction6()"/> Tambah </label></center></th>
<th><center><label><input type="checkbox" id="checkall7[]" onclick="myFunction7()"/> Ubah </label></center></th>
<th><center><label><input type="checkbox" id="checkall8[]" onclick="myFunction8()"/> Hapus </label></center></th>
<th><center><label><input type="checkbox" id="checkall9[]" onclick="myFunction9()"/> Cetak </label></center></th>
</tr>
</thead>
<?php endif; ?>
<tbody>

<?php foreach ($privilege['submodul'] as $privilege1): ?>

<?php
$pID1 = $privilege1[id];
$checked1 = null;
$item = null;
foreach($crtPrivilege1 as $pri1)
{
if ($pID1 == $pri1->id)
{
$checked1= ' checked="checked"';
break;
}
}
?>

<?php
$pID_create1 = $privilege1[id];
$checked_create1 = null;
$item = null;
foreach($crtPrivilege1_create as $pri_create1)
{
if ($pID_create1 == $pri_create1->id)
{
$checked_create1= ' checked="checked"';
break;
}
}
?>

<?php
$pID_update1 = $privilege1[id];
$checked_update1 = null;
$item = null;
foreach($crtPrivilege1_update as $pri_update1)
{
if ($pID_update1 == $pri_update1->id)
{
$checked_update1= ' checked="checked"';
break;
}
}
?>

<?php
$pID_delete1 = $privilege1[id];
$checked_delete1 = null;
$item = null;
foreach($crtPrivilege1_delete as $pri_delete1)
{
if ($pID_delete1 == $pri_delete1->id)
{
$checked_delete1= ' checked="checked"';
break;
}
}
?>

<?php
$pID_print1 = $privilege1[id];
$checked_print1 = null;
$item = null;
foreach($crtPrivilege1_print as $pri_print1)
{
if ($pID_print1 == $pri_print1->id)
{
$checked_print1= ' checked="checked"';
break;
}
}
?>

															
<td><?=$privilege1['id']?></td>
<td><?=$privilege1['modul']?></td>
<td><center><input type="checkbox" name="privlg5[]" value="<?=$privilege1['id']?>"<?php echo $checked1;?>></center></td>
<td><center><input type="checkbox" name="privlg6[]" value="<?=$privilege1['id']?>"<?php echo $checked_create1;?>></center></td>
<td><center><input type="checkbox" name="privlg7[]" value="<?=$privilege1['id']?>"<?php echo $checked_update1;?>></center></td>
<td><center><input type="checkbox" name="privlg8[]" value="<?=$privilege1['id']?>"<?php echo $checked_delete1;?>></center></td>
<td><center><input type="checkbox" name="privlg9[]" value="<?=$privilege1['id']?>"<?php echo $checked_print1;?>></center></td>

</tr>

</td>

<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
</div>	



<!-- #End level 2 table -->


<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
</div>		

                     <?php echo form_close();?>
                  </div>
                     <div class="panel-footer"></div>
                  </div>
               </div>
            </div>
         </section>
      </div>
   </div>
</div>


<script>
   $(document).ready(function() {
   
      //This script is to check email validity
      $("body").on('change', '#group_name', function()
      {
         var group_name = $("#group_name").val();
   
         if (group_name.length === 0) 
         {
           $('#err_msg').text('Group Name is required');
           return false;
         }
   
         $.ajax({
           url: '<?= base_url("index.php/User_groups/check_group_name") ?>',
           method: 'POST',
           dataType: 'TEXT',
           data: {group_name: group_name},
           success: function(result) 
           {
             var msg = result.split("::");
   
             if (msg[0] == "ok")
             {
               $("#err_msg").fadeIn();
               $("#err_msg").text("Nama Group ini sudah terdaftar. Masukan Nama Group lainnya.");
             }  
             else
             {
               console.log('Success');
               $("#err_msg").fadeIn();
               $("#err_msg").html("<span class='fa fa-check-circle text-success'> Success</span>");
               $("#err_msg").delay(3000).fadeOut();
             }
           },
           error:function(result) 
           {
             // body...
             console.log(result);
           }
         })
      });

   $('.actionButton').click(function() {
   var beanId = $(this).data('beanId');
   $('#UserName').val(beanId); 
  });

   $('#subid[]').click(function() {
   var beanId = $(this).data('beanId');
   $('#UserName').val(beanId); 
  });
	
   });
     
</script>

<script type="text/javascript">
			
  function myFunction0() {
  var checkBox = document.getElementById("checkall0[]");
  if (checkBox.checked == true){
    var items=document.getElementsByName('privlg[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
					        items[i].checked=true;
                                      enable_cb6_9();
                                      
                                      
				}
  } else {
     var items=document.getElementsByName('privlg[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=false;
				       disable_cb5();
                                       
  				}
 }
 }

 function myFunction5() {
  var checkBox = document.getElementById("checkall5[]");
  if (checkBox.checked == true){
    var items=document.getElementsByName('privlg5[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=true;
                                    enable_cb6_9();
                                    enable_main_cb();
				}
  } else {
     var items=document.getElementsByName('privlg5[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=false;
                                   disable_cb6_9();
				}
  }
  }

  function myFunction6() {
  var checkBox = document.getElementById("checkall6[]");
  if (checkBox.checked == true){
    var items=document.getElementsByName('privlg6[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=true;
                                    enable_cb5();
                                    enable_main_cb();            
				}
  } else {
     var items=document.getElementsByName('privlg6[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=false;
                                    
				}
  }
  }

  function myFunction7() {
  var checkBox = document.getElementById("checkall7[]");
  if (checkBox.checked == true){
    var items=document.getElementsByName('privlg7[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=true;
                                    enable_cb5();
                                    enable_main_cb(); 
				}
  } else {
     var items=document.getElementsByName('privlg7[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=false;
                                     
				}
  }
  }

  function myFunction8() {
  var checkBox = document.getElementById("checkall8[]");
  if (checkBox.checked == true){
    var items=document.getElementsByName('privlg8[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=true;
                                     enable_cb5();
                                     enable_main_cb(); 
				}
  } else {
     var items=document.getElementsByName('privlg8[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=false;
                                     
				}
  }
  }

  function myFunction9() {
  var checkBox = document.getElementById("checkall9[]");
  if (checkBox.checked == true){
    var items=document.getElementsByName('privlg9[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=true;
                                     enable_cb5();
                                     enable_main_cb();            
				}
  } else {
     var items=document.getElementsByName('privlg9[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=false;
                                                 
				}
  }
  }

   function enable_cb6_9() {
    document.getElementById("checkall6[]").checked=true;
    myFunction6()
    document.getElementById("checkall7[]").checked=true;
    myFunction7()
    document.getElementById("checkall8[]").checked=true;
    myFunction8()
    document.getElementById("checkall9[]").checked=true;
    myFunction9()
  }

  function disable_cb6_9() {
    document.getElementById("checkall6[]").checked=false;
    myFunction6()
    document.getElementById("checkall7[]").checked=false;
    myFunction7()
    document.getElementById("checkall8[]").checked=false;
    myFunction8()
    document.getElementById("checkall9[]").checked=false;
    myFunction9()
  }

  function enable_cb5() {
    document.getElementById("checkall5[]").checked=true;
    myFunction10()
    
  }

  function disable_cb5() {
    document.getElementById("checkall5[]").checked=false;
    myFunction10()
    disable_cb6_9()
  }

  function enable_main_cb() {
    document.getElementById("checkall0[]").checked=true;
    myFunction11()
  }

  function myFunction10() {
  var checkBox = document.getElementById("checkall5[]");
  if (checkBox.checked == true){
    var items=document.getElementsByName('privlg5[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=true; 
				}
  } else {
     var items=document.getElementsByName('privlg5[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=false;                             
				}
  }
  }

  function myFunction11() {
  var checkBox = document.getElementById("checkall0[]");
  if (checkBox.checked == true){
    var items=document.getElementsByName('privlg[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=true;
                                      
				}
  } else {
     var items=document.getElementsByName('privlg[]');
				for(var i=0; i<items.length; i++){
					if(items[i].type=='checkbox')
						items[i].checked=false;   
  				}
 }
 }
 
</script>


