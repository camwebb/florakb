<?php //defined ('MICRODATA') or exit ( 'Forbidden Access' );?>
<script>
$(function () {	
	
	$('#postdate').datepicker ();
	$('#expiredate').datepicker ();
	$('input:checkbox, input:radio').iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue',
		inheritClass: true
	})
	
	
})

$(document).on ('click','.editpayment',function(){
	
	$('.payment').prop("disabled", false);
	$('.editpayment').css("display", 'none');
	$('.cancelpayment').css("display", 'block');
});
$(document).on ('click','.cancelpayment',function(){
	
	$('.payment').prop("disabled", true);
	$('.editpayment').css("display", 'block');
	$('.cancelpayment').css("display", 'none');
});
</script>
<div id="content-header">
	<h1>New Ads</h1>
</div> <!-- #content-header -->	

<div id="content-container">

		<div class="row">
		<div class="col-md-9 col-sm-8">

		  <div class="tab-content stacked-content">
			<div class="tab-pane fade in active" id="profile-tab">
			  
			  <h3 class="">Ads Form</h3>
			  <hr />

			  <br />

			  <form id="validate-enhanced" class="form-horizontal parsley-form" method="post" action="<?=$basedomain?>page/inputads" enctype="multipart/form-data">

				

				<div class="form-group">

					<label class="col-md-3">Advertise Image</label>

					<div class="col-md-7">
						<div class="fileupload fileupload-new" data-provides="fileupload">
						  <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="<?=(isset($data['item'][0]['image']) ? ($data['item'][0]['image']!='' ? $app_domain."assets/images/upload/".$data['item'][0]['image']  : $app_domain.'assets/images/no-images.gif') : $app_domain.'assets/images/no-images.gif');?>" alt="Profile Avatar" /></div>
						  <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
						  <div>
							<span class="btn btn-default btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="file_image"/></span>
							<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
						  </div>
						</div>
					</div> <!-- /.col -->

				</div> <!-- /.form-group -->

				<div class="form-group">

					<label class="col-md-3">Title</label>

					<div class="col-md-7">
						<input type="text" name="title" id="title" value="<?=(isset($data['item'][0]['title']) ? $data['item'][0]['title'] : '');?>" class="form-control" data-required="true" />
					</div> <!-- /.col -->
				</div> <!-- /.form-group -->
				
				<div class="form-group">

					<label class="col-md-3">Owner</label>

					<div class="col-md-7">
						<input type="text" name="source" id="brief" value="<?=(isset($data['item'][0]['source']) ? $data['item'][0]['source'] : '');?>" class="form-control" data-required="true" />
					</div> <!-- /.col -->

				</div> <!-- /.form-group -->
				
				<div class="form-group">

					<label class="col-md-3">Post date</label>

					<div class="col-md-4">
						<div id="postdate" class="input-group date" data-auto-close="true" data-date-format="dd-mm-yyyy" data-date-autoclose="true">
						<input class="form-control" type="text" name="postdate" value="<?=(isset($data['item'][0]['post_date']) ? date('d-m-Y',strtotime($data['item'][0]['post_date'])) : '');?>" data-required="true">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
					</div> <!-- /.col -->

				</div> <!-- /.form-group -->
				<div class="form-group">

					<label class="col-md-3">Expire Date</label>

					<div class="col-md-4">
						<div id="expiredate" class="input-group date" data-auto-close="true" data-date-format="dd-mm-yyyy" data-date-autoclose="true">
						<input class="form-control" type="text" name="expiredate" value="<?=(isset($data['item'][0]['expire_date']) ? date('d-m-Y',strtotime($data['item'][0]['expire_date'])) : '');?>" data-required="true">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
					</div> <!-- /.col -->

				</div> <!-- /.form-group -->
				<?php
				// pr($data['ads_type']);
				?>
				<div class="form-group">
					<label class="col-md-3">Category</label>
						<div class="col-md-4">
							<select name="articletype" class="form-control">
								
								<option value='11' <?php if (isset($data['item'][0]['type'])){if ($data['item'][0]['type']==11) {echo 'selected';}}?>>Small</option>
								<option value='12' <?php if (isset($data['item'][0]['type'])){if ($data['item'][0]['type']==12) {echo 'selected';}}?>>Big</option>
								<?php
								// if(isset($data['item'][0]['articletype'])) {
									// echo '<option value="'.$data['item'][0]['articletype'].'" selected>'.$data['item'][0]['value'].'</option>';
								// }
								
								// foreach($ads_type as $val){
								?>
									<!--<option value='<?=$val['type']?>'><?=$val['value']?></option>-->
								<?php
								// }
								?>
							</select>
						</div>
				</div>
				
				<div class="form-group">

					<label class="col-md-3">Link Ads</label>

					<div class="col-md-7">
						<input type="text" name="tags" class="form-control" value="<?=(isset($data['item'][0]['link']) ? $data['item'][0]['link'] : '');?>" data-required="true" placeholder="http://"/>
					</div> <!-- /.col -->

				</div> <!-- /.form-group -->
				<div class="form-group">

					<label class="col-md-3">Payment</label>
					<?php //echo rupiahConvert($data['item'][0]['payment']);?>
					<div class="col-md-7">
						<div class="input-group">
						<span class="input-group-addon">Rp</span>
						<input class="form-control payment" id="appendedPrependedInput" name="payment" type="text" value="<?php if (isset($data['item'][0]['payment'])){echo $data['item'][0]['payment'];}?>" <?php if (isset($data['item'][0]['payment'])){echo 'disabled="disabled"';}?> data-required="true">
						<span class="input-group-addon">.00</span>
					</div>
						<a href="javascript:void(0)" class="editpayment" style="display:block">edit</a>
						<a href="javascript:void(0)" class="cancelpayment" style="display:none">cancel</a>
					</div> <!-- /.col -->
					
				</div> <!-- /.form-group -->
				<div class="form-group">
				 
					<label class="col-md-3">Status</label>

					<label class="radio-inline">
						<input type="radio" name="n_status" class="" value="1" <?=isset($data['item'][0]['n_status']) ? ($data['item'][0]['n_status']==1 ? 'checked' : '') : ''?>> Active
					</label>
					<label class="radio-inline">
						<input type="radio" name="n_status" class="" value="0" <?=isset($data['item'][0]['n_status']) ? ($data['item'][0]['n_status']==0 ? 'checked' : '') : 'checked'?>> Inactive
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
				<input type="hidden" name="image" value="<?=isset($data['item'][0]['image']) ? $data['item'][0]['image'] : ''?>" />
				<input type="hidden" name="articletype_old" value="<?=isset($data['item'][0]['articletype']) ? $data['item'][0]['articletype'] : '0'?>" />
				<!-- hidden -->
			  </form>


			</div>
		  </div>

	</div> <!-- /.col -->
	</div>

</div>