<script>
	function checkpass(){
			var old_pass = $('#old-password').val();
			$.post(basedomain+"setting/checkpass", { old_pass: old_pass},  
				function(result){ 
					
					var newvar = result.status;
					console.log(result.status);
					//if the result is 1  
					if(newvar == true){ 
						$('#help-old').html('');
						$('#btnsubmit').removeAttr("disabled");
					}else{  
						$('#help-old').html('wrong password');
						$('#help-old').css("color","red");
						$('#btnsubmit').attr("disabled","disabled");
						
					}  
			}, "JSON");
	}
	
	function confirmpass(){
		var pass1 = $('#new-password-1').val();
		var pass2 = $('#new-password-2').val();
		
		if(pass1 != pass2){
			alert('Confirmasi password salah');
			return false;
		}
	}
	
</script>
		<div id="content-header">
			<h1>Settings</h1>
		</div> <!-- #content-header -->	


		<div id="content-container">

			<div class="row">

		    	<div class="col-md-3 col-sm-4">
		    
				    <ul id="myTab" class="nav nav-pills nav-stacked">
				        <li class="active">
				        	<a href="#profile-tab" data-toggle="tab">
				        		<i class="fa fa-user"></i> 
				        		&nbsp;&nbsp;Profile Settings
				        	</a>
				        </li>
				        <li>
				        	<a href="#password-tab" data-toggle="tab">
				        		<i class="fa fa-lock"></i> 
				        		&nbsp;&nbsp;Change Password
				        	</a>
				        </li>
				      </ul>

				</div> <!-- /.col -->

				<div class="col-md-9 col-sm-8">

				      <div class="tab-content stacked-content">
				        <div class="tab-pane fade in active" id="profile-tab">
				          
				          <h3 class="">Edit Profile Settings</h3>
						 <?php if(isset($data['status'])){ ?>
							<div class="alert alert-success">
								<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
								<strong>Data has been saved!</strong> You successfully save this page.
							</div>
						<?php } ?>
				          <hr />

				          <br />

						  <form id="validate-enhanced" class="form-horizontal parsley-form" method="post" action="<?=$basedomain?>setting/usr_profileupd" enctype="multipart/form-data">

				          	

				          	<div class="form-group">

				          		<label class="col-md-3">Profil Image</label>

				          		<div class="col-md-7">
				          			<div class="fileupload fileupload-new" data-provides="fileupload">
									  <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="<?=(isset($data['item'][0]['image_profile']) ? ($data['item'][0]['image_profile']!='' ? $app_domain."assets/images/upload/".$data['item'][0]['image_profile']  : $app_domain.'assets/images/no-images.gif') : $app_domain.'assets/images/no-images.gif');?>" alt="Profile Avatar" /></div>
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
				          			<input type="text" name="username" value="<?=(isset($data['item'][0]['username']) ? $data['item'][0]['username'] : '');?>" class="form-control" disabled />
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          	<div class="form-group">

				          		<label class="col-md-3">Nick Name</label>

				          		<div class="col-md-7">
				          			<input type="text" name="nickname" value="<?=(isset($data['item'][0]['nickname']) ? $data['item'][0]['nickname'] : '');?>" class="form-control" data-required="true"/>
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

				          	<br />

				          	<div class="form-group">

				          		<div class="col-md-7 col-md-push-3">
				          			<button type="submit" class="btn btn-primary">Save Changes</button>
				          			&nbsp;
				          			<button type="reset" class="btn btn-default">Cancel</button>
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->
							
							<!-- hidden -->
							<input type="hidden" name="id" value="<?=(isset($data['item'][0]['id']) ? $data['item'][0]['id'] : '');?>" />
							<input type="hidden" name="image" value="<?=(isset($data['item'][0]['image_profile']) ? $data['item'][0]['image_profile'] : '');?>" />
							<input type="hidden" name="password" id="password" value="<?=(isset($data['item'][0]['password']) ? $data['item'][0]['password'] : '');?>" />
							<!-- hidden -->
							
				          </form>


				        </div>
				        <div class="tab-pane fade" id="password-tab">

				          <h3 class="">Change Your Password</h3>
					
				          <br />

						  <form id="validate-enhanced" class="form-horizontal parsley-form" method="post" action="<?=$basedomain?>setting/usr_passupd" onsubmit="return confirmpass()">

				          	<div class="form-group">

				          		<label class="col-md-3">Old Password</label>

				          		<div class="col-md-7">
				          			<input type="password" id="old-password" name="old-password" class="form-control" data-required="true" onkeyup="checkpass()"/>
				          		</div> <!-- /.col -->
								<span class="help-block" id="help-old"></span>
				          	</div> <!-- /.form-group -->
							
				          	<hr />


				          	<div class="form-group">

				          		<label class="col-md-3">New Password</label>

				          		<div class="col-md-7">
				          			<input type="password" name="new-password-1" id="new-password-1" class="form-control" data-required="true" />
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          	<div class="form-group">

				          		<label class="col-md-3">New Password Confirm</label>

				          		<div class="col-md-7">
				          			<input type="password" name="new-password-2" id="new-password-2" class="form-control" data-required="true" />
									<span class="help-block" id="help-new"></span>
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          	<br />

				          	<div class="form-group">

				          		<div class="col-md-7 col-md-push-3">
				          			<button type="submit" class="btn btn-primary" id="btnsubmit">Save Changes</button>
				          			&nbsp;
				          			<button type="reset" class="btn btn-default">Cancel</button>
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          </form>
				        </div>
				      </div>

				</div> <!-- /.col -->

			</div> <!-- /.row -->


		</div> <!-- /#content-container -->