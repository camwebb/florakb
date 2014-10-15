<?php //defined ('MICRODATA') or exit ( 'Forbidden Access' );?>

<script>
$(function () {	
	$('input:checkbox, input:radio').iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue',
		inheritClass: true
	})
})

function checkuser(){
		var new_user = $('#username').val();
		
		$.post(basedomain+"setting/checkuser", { new_user: new_user},  
			function(result){ 
				
				var newvar = result.status;
				console.log(result.status);
				//if the result is 1  
				if(newvar != true){ 
					$('#help-username').html('');
					$('#btnsubmit').removeAttr("disabled");
				}else{  
					$('#help-username').html('username arleady exist');
					$('#help-username').css("color","red");
					$('#btnsubmit').attr("disabled","disabled");
					
				}  
		}, "JSON");
}

function confirmpass(){
		var pass1 = $('#password').val();
		var pass2 = $('#repassword').val();
		
		if(pass1 != pass2){
			alert('Re-enter password salah');
			return false;
		}
	}
</script>

<div id="content-header">
	<h1>Input New User</h1>
</div> <!-- #content-header -->	

<div id="content-container">

	<div class="row">
		<div class="col-md-9 col-sm-8">

		  <div class="tab-content stacked-content">
			<div class="tab-pane fade in active" id="profile-tab">
			  
			  <h3 class="">User Form</h3>
			 <?php if(isset($data['status'])){ ?>
				<div class="alert alert-success">
					<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
					<strong>Data has been saved!</strong> You successfully save this page.
				</div>
			<?php } ?>
			  <hr />

			  <br />

			  <form id="validate-enhanced" class="form-horizontal parsley-form" method="post" action="<?=$basedomain?>setting/inputuser" enctype="multipart/form-data" onsubmit="return confirmpass()">

				

				<div class="form-group">

					<label class="col-md-3">Profil Image</label>

					<div class="col-md-7">
						<div class="fileupload fileupload-new" data-provides="fileupload">
						  <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="<?=(isset($data['item'][0]['image_profile']) ? ($data['item'][0]['image_profile']!='' ? $app_domain."upload/profile/".$data['item'][0]['image_profile']  : $app_domain.'assets/images/no-images.gif') : $app_domain.'assets/images/no-images.gif');?>" alt="Profile Avatar" /></div>
						  <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
						  <div>
							<span class="btn btn-default btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="file_image"/></span>
							<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
						  </div>
						</div>
					</div> <!-- /.col -->

				</div> <!-- /.form-group -->

				<div class="form-group">

					<label class="col-md-3">Username</label>

					<div class="col-md-7">
						<input type="text" name="username" id="username" value="<?=(isset($data['item'][0]['username']) ? $data['item'][0]['email'] : '');?>" class="form-control" data-required="true" onkeyup="checkuser()" <?=(isset($data['item'][0]['username']) ? 'disabled' : '');?>/>
					</div> <!-- /.col -->
				<span class="help-block" id="help-username"></span>
				</div> <!-- /.form-group -->
				
				<div class="form-group">

					<label class="col-md-3">Password</label>

					<div class="col-md-7">
						<input type="password" name="password" id="password" value="" class="form-control"  <?=(isset($data['item'][0]['password']) ? '' : 'data-required="true"');?>/>
					</div> <!-- /.col -->

				</div> <!-- /.form-group -->
				
				<div class="form-group">

					<label class="col-md-3">Re-enter Password</label>

					<div class="col-md-7">
						<input type="password" name="repassword" id="repassword" value="" class="form-control" <?=(isset($data['item'][0]['password']) ? '' : 'data-required="true"');?>/>
					</div> <!-- /.col -->

				</div> <!-- /.form-group -->
				
				 <div class="form-group">
				 
					<label class="col-md-3">User Type</label>

					<label class="radio-inline">
						<input type="radio" name="usertype" value="7" <?=$data['item'][0]['usertype']==7 ? 'checked' : '';?>> Company
					</label>
					<label class="radio-inline">
						<input type="radio" name="usertype" value="4" <?=$data['item'][0]['usertype']==4 ? 'checked' : '';?>> Alumni
					</label>
					<label class="radio-inline">
						<input type="radio" name="usertype" value="5" <?=$data['item'][0]['usertype']==5 ? 'checked' : '';?>> Mahasiswa
					</label>
					<label class="radio-inline">
						<input type="radio" name="usertype" value="8" <?=$data['item'][0]['usertype']==8 ? 'checked' : '';?>> Advertise
					</label>
					<label class="radio-inline">
						<input type="radio" name="usertype" value="6" <?=$data['item'][0]['usertype']==6 ? 'checked' : '';?>> Guest
					</label>

				</div> <!-- /.form-group -->

				<div class="form-group">

					<label class="col-md-3">Name</label>

					<div class="col-md-7">
						<input type="text" name="nickname" value="<?=(isset($data['item'][0]['name']) ? $data['item'][0]['name'] : '');?>" class="form-control" data-required="true" data-required="true"/>
					</div> <!-- /.col -->

				</div> <!-- /.form-group -->

				<div class="form-group">

					<label class="col-md-3">Email Address</label>

					<div class="col-md-7">
						<input type="text" name="email" value="<?=(isset($data['item'][0]['email']) ? $data['item'][0]['email'] : '');?>" class="form-control" data-required="true"/>
					</div> <!-- /.col -->

				</div> <!-- /.form-group -->

				<div class="form-group">

					<label class="col-md-3">About You</label>

					<div class="col-md-7">
						<textarea id="description" name="description" rows="6" class="form-control"><?=(isset($data['item'][0]['description']) ? $data['item'][0]['description'] : '');?></textarea>
					</div> <!-- /.col -->

				</div> <!-- /.form-group -->
				
				<div class="form-group">
				 
					<label class="col-md-3">Status</label>

					<label class="radio-inline">
						<input type="radio" name="n_status" class="" value="1" <?=isset($data['item'][0]['n_status']) ? ($data['item'][0]['n_status']==1 ? 'checked' : '') : ''?>> Approved
					</label>
					<label class="radio-inline">
						<input type="radio" name="n_status" class="" value="0" <?=isset($data['item'][0]['n_status']) ? ($data['item'][0]['n_status']==0 ? 'checked' : '') : 'checked'?>> Pending
					</label>

				</div> <!-- /.form-group -->

				<br />

				<div class="form-group">

					<div class="col-md-7 col-md-push-3">
						<button type="submit" class="btn btn-primary">Submit</button>
						&nbsp;
						<button type="reset" class="btn btn-default">Cancel</button>
					</div> <!-- /.col -->

				</div> <!-- /.form-group -->
				
				<!-- hidden -->
				<input type="hidden" name="id" value="<?=isset($data['item'][0]['id']) ? $data['item'][0]['id'] : ''?>" />
				<input type="hidden" name="image" value="<?=isset($data['item'][0]['image_profile']) ? $data['item'][0]['image_profile'] : ''?>" />
				<!-- hidden -->
			  </form>


			</div>
		  </div>

	</div> <!-- /.col -->
	</div>
</div>