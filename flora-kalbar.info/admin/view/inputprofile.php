<?php //defined ('MICRODATA') or exit ( 'Forbidden Access' );?>

<script>
$(function () {
	$('#postdate').datepicker ();
	$('#expiredate').datepicker ();
	
	$("#isi").jqte();
	
	$('input:checkbox, input:radio').iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue',
		inheritClass: true
	})
})
</script>

<div id="content-header">
	<h1><?=$data['title']?></h1>
</div> <!-- #content-header -->	

<div id="content-container">
	<form id="validate-enhanced" class="form parsley-form" method="post" action="<?=$basedomain?>page/profileinp" enctype="multipart/form-data">
		
		<?php if(isset($data['status'])){ ?>
		<div class="alert alert-success">
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
				<strong>Data has been saved!</strong> You successfully save this page.
			</div>
		<?php } ?>
		<div class="row">
			<div class="col-md-9">
				<div class="portlet">
				
					<div class="portlet-header">

						<h3>
							<i class="fa fa-tasks"></i>
							Form Article
						</h3>

					</div> <!-- /.portlet-header -->
				
					<div class="portlet-content">
				
						
						
						<div class="col-sm-12">
							<div class="form-group">
							<label for="text-input">Title</label>
									<input type="text" name="title" class="form-control" value="<?=(isset($data['item'][0]['title']) ? $data['item'][0]['title'] : '');?>" data-required="true"/>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							<label for="text-input">Brief</label>
									<input type="text" name="brief" class="form-control" value="<?=(isset($data['item'][0]['brief']) ? $data['item'][0]['brief'] : '');?>" />
							</div>
						</div>
					
						<div class="col-sm-12">
							<div class="form-group">
							<label for="textarea-input">Deskripsi</label>
									<textarea name="content" id="isi"  cols="10" rows="20" class="form-control"><?=(isset($data['item'][0]['content']) ? $data['item'][0]['content'] : '');?></textarea>
							</div>
							
							
									<!-- hidden -->
									<input type="hidden" name="id" value="<?=(isset($data['item'][0]['id']) ? $data['item'][0]['id'] : '');?>" />
									<input type="hidden" name="expiredate" value="" />
									<input type="hidden" name="categoryid" value="<?=(isset($data['item'][0]['categoryid']) ? $data['item'][0]['categoryid'] : $data['catid']);?>" />
									<input type="hidden" name="page" value="<?=(isset($_GET['page']) ? $_GET['page'] : $data['url']);?>" />
									<input type="hidden" name="image" value="<?=(isset($data['item'][0]['image']) ? $data['item'][0]['image'] : '');?>" />
									<input type="hidden" name="thumbnailimage" value="<?=(isset($data['item'][0]['thumbnailimage']) ? $data['item'][0]['thumbnailimage'] : '');?>" />
									<input type="hidden" name="fromwho" value="<?=(isset($data['item'][0]['fromwho']) ? $data['item'][0]['fromwho'] : '0');?>" />
									<input type="hidden" name="authorid" value="<?=(isset($data['item'][0]['authorid']) ? $data['item'][0]['authorid'] : $_SESSION['user']['id']);?>" />
									<!-- hidden -->
							
						</div>
					</div>
				</div>
				
				<div class="portlet">
					<div class="portlet-header">

						<h3>
							<i class="fa fa-tags"></i>
							Tags
						</h3>

					</div> <!-- /.portlet-header -->
					<div class="portlet-content">
						<div class="form-group">
								<textarea name="tags" cols="10" rows="3" class="form-control"><?=(isset($data['item'][0]['tags']) ? $data['item'][0]['tags'] : '');?></textarea>
						</div>
					</div>
				</div>
				
			</div>
		
		
			<div class="col-md-3">
				<div class="portlet">
					<div class="portlet-header">

						<h3>
							<i class="fa fa-calendar"></i>
							Publish Date
						</h3>

					</div> <!-- /.portlet-header -->
					<div class="portlet-content">
						<div class="form-group">
							<label for="select-input">Postdate</label>
							<div id="postdate" class="input-group date" data-auto-close="true" data-date-format="dd-mm-yyyy" data-date-autoclose="true">
								<input class="form-control" type="text" name="postdate" value="<?=(isset($data['item'][0]['postdate']) ? $data['item'][0]['postdate'] : date('d-m-Y'));?>" data-required="true">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							
						</div>
						
						<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="status" class="" <?=(isset($data['item'][0]['n_status']) ? ($data['item'][0]['n_status']==1 ? 'checked': '') : '')?>>
										Publish
									</label>
								</div>
						</div>
						
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="articletype" class="" <?=(isset($data['item'][0]['articletype']) ? ($data['item'][0]['articletype']==1 ? 'checked': '') : '')?>>
									Put on Slideshow
								</label>
							</div>
						</div>
						<input type="submit" class="btn btn-primary" value="Submit" id="submit" />
					</div>
				</div>
				
				<div class="portlet">
					<div class="portlet-header">

						<h3>
							<i class="fa fa-picture-o"></i>
							Cover Image
						</h3>

					</div> <!-- /.portlet-header -->
					<div class="portlet-content">
						<div class="fileupload fileupload-new" data-provides="fileupload">
						  <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="<?=(isset($data['item'][0]['image']) ? ($data['item'][0]['image']!='' ? $app_domain."assets/images/upload/".$data['item'][0]['image']  : $app_domain.'assets/images/no-images.gif') : $app_domain.'assets/images/no-images.gif');?>" alt="<?=(isset($data['item'][0]['image']) ? $data['item'][0]['image'] : '');?>" /></div>
						  <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
						  <div>
							<span class="btn btn-default btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="file_image"/></span>
							<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
						  </div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	
	</form>
</div>