<?php
// global $DATA;
// pr($DATA);

$page = @$DATA['default']['uri']['page'];
// if(isset($page)){
	// switch ($page){
		// case 'specimen':{$selected = 'selected'}break;
		// case 'services':{$selected = 'selected'}break;
		// case 'contact':{$selected = 'selected'}break;
		// case default:{$selected = ''}
	// }
// }

?>

<div>
	<a href="<?=$basedomain?>" id="logo"></a>
	<ul>
		<li class="<?php if (!isset($page)) echo 'selected'?>">
			<a href="<?=$basedomain?>">Home</a>
		</li>
		<li class="
		<?php if ($page=='specimen') echo 'selected'?>">
			<a href="<?=$basedomain?>specimen">Specimen</a>
		</li>
		<li class="<?php if ($page == 'services') echo 'selected'?>">
			<a href="<?=$basedomain?>services/">Services</a>
		</li>
		<li class="<?php if ($page == 'contact') echo 'selected'?>">
			<a href="<?=$basedomain?>contact">Contact</a>
		</li>
		<li class="<?php if ($page == 'news') echo 'selected'?>">
			<a href="<?=$basedomain?>news">News</a>
		</li>
	</ul>
</div>