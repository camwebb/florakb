<script>
	$(function(){
	$('#createdate').datetimepicker({
		dateFormat:'yy-mm-dd',
		timeFormat:'HH:mm:ss',
		stepSecond:10});
	});
	
	function myFunction(){
		// alert("Page is loaded");
		$('#btn-primary').attr('disabled','disabled');
		$('#btn-primary').css('background','grey');
		$('#createdate').attr('disabled','disabled');
		$('#content').attr('disabled','disabled');
		$('#imgInpPlay').attr('disabled','disabled');
	} 
	
	function check_edit(){
		var checked=false;
		var elements = document.getElementsByName("check_strutur");
		if(elements[0].checked){
				checked = true;
				$('#btn-primary').removeAttr('disabled');
				$('#btn-primary').css("background","#006DCC");
				$('#createdate').removeAttr('disabled');
				$('#content').removeAttr('disabled');
				$('#imgInpPlay').removeAttr('disabled');
		}
		else if (!checked){
				$('#btn-primary').attr('disabled','disabled');
				$('#btn-primary').css('background','grey');
				$('#createdate').attr('disabled','disabled');
				$('#content').attr('disabled','disabled');
				$('#imgInpPlay').attr('disabled','disabled');
		}
		return checked;
	}
</script>
				<?=$menu?>
				<h4 class="title_sub_menu_in">Kontak</h4>
				<div class="new_input">
					<form method="post" action="<?=$basedomain?>kontak/inputData" enctype="multipart/form-data">
					<table>
						<tr>
							<?php echo"<td><input type=\"checkbox\" name=\"check_strutur\" id=\"check_strutur\" value=\"Edit\" onchange=\"return check_edit(this)\" >&nbsp;&nbsp;Edit ";?>
						</td>
						</tr>
						<tr>
							<td>Tanggal dibuat</td>
							<td>&nbsp;</td>
							<td><input type="text" id="createdate" name="createdate" /></td>
						</tr>
						<tr>
							<td>Unggah Gambar</td>
							<td>&nbsp;</td>
							<td rowspan="6" >
							<img src="<?=$app_domain?>public_assets/kontak/<?=(isset($data['isi'][0]['image']) ? $data['isi'][0]['image'] : 'images/1_b.jpg');?>" style="height:200px;margin-bottom:10px;" class="img-polaroid" id="previewplay"/><br/>
							<input type="file" name="image" id="imgInpPlay"/>
						</td>
					</table>
					<p><b>Isi Kontak :</b></p>
					<!--better diganti Editor text-->
					<textarea style="width:100%;height:200px;" name="content" id="content">
						<?
							// pr($data);
							if($isi){
								foreach ($isi as $key=>$val){
								echo $val['content'];
								}
							}
						?>
					
					</textarea>
					<input type="submit" class="btn btn-primary" id="btn-primary" value=" Posting "/>
					</form>
				</div>
				
				<script>
					function readURLplay(input) {
						if (input.files && input.files[0]) {
							var reader = new FileReader();
							reader.onload = function (e) {
								$('#previewplay').attr('src', e.target.result);
								$('#previewplay').attr('width', '200px');
								$('#previewplay').attr('height', '200px');
							}
							 reader.readAsDataURL(input.files[0]);
						}
					}
					
					$("#imgInpPlay").change(function(){
						readURLplay(this);
					});
				</script>
			