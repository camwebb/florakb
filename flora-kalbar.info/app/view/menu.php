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

<div class="wrapper">
<div id="nav-box">
	<div id="menu">
        <ul>
    		<li class="<?php if (!isset($page)) echo 'selected'?>">
    			<a href="<?=$basedomain?>">Home</a>
    		</li>
    		<li class="<?php if ($page=='specimen') echo 'selected'?>">
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
            <li class="<?php if ($page == 'upload') echo 'selected'?>">
    			<a href="<?=$basedomain?>upload">Upload</a>
    		</li>
            <li class="<?php if ($page == 'upload') echo 'selected'?>">
    			<a href="<?=$basedomain?>zip">Zip</a>
    		</li>
    	</ul>
    </div>
    
    <div id="login"> 
        <a href="#signup-modal" id="btn-signup" class="btn signup">Sign Up</a> 
        <a href="#login-modal" id="btn-login" class="btn login">Login</a> 
    </div>
    <div class="clear"></div>
</div>
</div>