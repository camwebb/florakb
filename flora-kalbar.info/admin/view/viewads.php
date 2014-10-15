<?php //defined ('MICRODATA') or exit ( 'Forbidden Access' ); ?>

<div id="content-header">
	<h1>Advertisement</h1>
</div> <!-- #content-header -->	

<div id="content-container">

<h3 class="heading">Ads List</h3>

<div class="row">

	<div class="col-md-12">

		<div class="table-responsive">
		
		<a href="<?=$basedomain?>page/addads"><button class="btn btn-default ui-tooltip btn-info" data-toggle="tooltip" data-placement="top" title="Add New Ads">New Ads</button></a>
		
		<table class="table table-striped table-bordered media-table" data-provide="datatable" 
								data-display-rows="10"
								data-info="true"
								data-search="true"
								data-length-change="true"
								data-paginate="true">
			<thead>
			
			<tr>
				<th style="width: 80px">Image</th>
				<th data-sortable="true">Owner</th>
				<th data-sortable="true">Title</th>
				<th data-sortable="true">Post Date</th>
				<th data-sortable="true">Type</th>
				<th data-sortable="true">Payment</th>
				<th data-sortable="true">Expire Date</th>
				<th data-sortable="true">Status</th>
				<th class="text-center">Actions</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$no = 1;
			if($data['item']){
				foreach($data['item'] as $key => $value){
			?>
			<tr>
				<td>
				<div class="thumbnail">
					<div class="thumbnail-view">
						<a class="thumbnail-view-hover ui-lightbox" href="<?=$app_domain."assets/images/upload/".$value['image']?>">
						</a>

						<img src="<?=$app_domain."assets/images/upload/".$value['image']?>" width="85" height="85" alt="Gallery Image" />
					</div>
				</div> <!-- /.thumbnail -->
				</td>
				<td><?=$value['source']?></td>
				<td><?=$value['title']?></td>
				<td><?= changeDate($value['post_date']);?></td>
				<td>
				<?php 
				// foreach($data['ads_type'] as $type){
					// if($type['type']==$value['type']) {
					// $trim = explode(" ",$type['value']);
					// echo "<strong>Size</strong> : ".$trim[1]." px<br>";
					// echo "<strong>Position</strong> : ".$trim[2]."<br>";
					// echo "<strong>Alignment</strong> : ".$trim[3];
					// }
				// }
				if ($value['type']==11) echo 'Small';
				if ($value['type']==12) echo 'Big';
				?>
				</td>
				<td><?php echo rupiahConvert($value['payment']);?></td>
				<td><?php echo changeDate($value['expire_date']);?></td>
				<td><?=$value['n_status']==1 ? '<label style="color:green">Publish</label>' : '<label style="color:red">Unpublish</label>';?></td>
				<!--<td><?=($value['authorid']==$_SESSION['user']['id'] ? $_SESSION['user']['nickname'] : '')?></td>-->

				<td class="text-center" width="15%">
					<a href="<?=$basedomain?>page/ads_upd/?id=<?=$value['id']?>" ><button class="btn btn-xs ui-tooltip btn-warning" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil"></i></button></a>&nbsp;
					<a href="<?=$basedomain?>page/ads_del/?id=<?=$value['id']?>" onclick="return confirm('Hapus iklan ini?');"><button class="btn btn-xs ui-tooltip btn-primary" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-times"></i></button></a>
					</td>
			</tr>
			<?php }} ?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="8"></td>
			</tr>
			</tfoot>
		</table>
		</div>
	</div>
</div>
</div> <!-- /#content-container -->