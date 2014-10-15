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
	<h1>Import Database</h1>
</div> <!-- #content-header -->	

<div id="content-container">
	<form id="validate-enhanced" class="form parsley-form" method="post" action="<?=$basedomain?>export/import" enctype="multipart/form-data">

		<div class="row">
			<div class="col-md-9">
				<div class="portlet">
				
					<div class="portlet-header">

						<h3>
							<i class="fa fa-tasks"></i>
							Import database
						</h3>

					</div> <!-- /.portlet-header -->
				
					<div class="portlet-content">
				
						
						
						<div class="col-sm-6">
							
							<label for="text-input">Browse</label>
									<input type="file" name="db" class="" value="" data-required="true"/>
							
						</div>
						
						<div class="col-sm-6">
							<input type="hidden"  value="1" name="token"/>
							<input type="submit" class="btn btn-primary" value="Submit" id="submit"/>
						</div>
					</div>
				</div>
				
				
			</div>
		
		</div>
	
	</form>
</div>