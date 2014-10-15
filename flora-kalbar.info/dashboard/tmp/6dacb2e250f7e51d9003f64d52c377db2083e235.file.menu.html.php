<?php /* Smarty version Smarty-3.1.15, created on 2014-04-03 07:54:11
         compiled from ".\view\menu.html" */ ?>
<?php /*%%SmartyHeaderCode:20189533cf7839335d8-21548952%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6dacb2e250f7e51d9003f64d52c377db2083e235' => 
    array (
      0 => '.\\view\\menu.html',
      1 => 1396504449,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20189533cf7839335d8-21548952',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_533cf7839e02a1_52209955',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_533cf7839e02a1_52209955')) {function content_533cf7839e02a1_52209955($_smarty_tpl) {?>
<div id="sidebar-wrapper" class="collapse sidebar-collapse">
	
	<div id="search">
		<form>
			<input class="form-control input-sm" type="text" name="search" placeholder="Search..." />

			<button type="submit" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
		</form>		
	</div> <!-- #search -->

	<nav id="sidebar">		
		
		<ul id="main-nav" class="open-active">			

			<li class="">				
				<a href="./home.php">
					<i class="fa fa-dashboard"></i>
					Dashboard
				</a>				
			</li>
						
			<li class="dropdown ">
				<a href="javascript:;">
					<i class="fa fa-file-text"></i>
					Example Pages
					<span class="caret"></span>
				</a>				
				
				<ul class="sub-nav">
					<li>
						<a href="./page-profile.php">
							<i class="fa fa-user"></i> 
							Profile
						</a>
					</li>
					<li>
						<a href="./page-invoice.php">
							<i class="fa fa-money"></i> 
							Invoice
						</a>
					</li>
					<li>
						<a href="./page-pricing.php">
							<i class="fa fa-dollar"></i> 
							Pricing Plans
						</a>
					</li>
					<li>
						<a href="./page-support.php">
							<i class="fa fa-question"></i> 
							Support Page
						</a>
					</li>
					<li>
						<a href="./page-gallery.php">
							<i class="fa fa-picture-o"></i> 
							Gallery
						</a>
					</li>
					<li>
						<a href="./page-settings.php">
							<i class="fa fa-cogs"></i> 
							Settings
						</a>
					</li>
					<li>
						<a href="./page-calendar.php">
							<i class="fa fa-calendar"></i> 
							Calendar
						</a>
					</li>
				</ul>						
				
			</li>	
			
			<li class="dropdown ">
				<a href="javascript:;">
					<i class="fa fa-tasks"></i>
					Form Elements
					<span class="caret"></span>
				</a>
				
				<ul class="sub-nav">
					<li>
						<a href="./form-regular.php">
							<i class="fa fa-location-arrow"></i>
							Regular Elements
						</a>
					</li>
					<li>
						<a href="./form-extended.php">
							<i class="fa fa-magic"></i>
							Extended Elements
						</a>
					</li>	
					<li>
						<a href="./form-validation.php">
							<i class="fa fa-check"></i>
							Validation
						</a>
					</li>			
				</ul>	
								
			</li>
			
			<li class="dropdown ">
				<a href="javascript:;">
					<i class="fa fa-desktop"></i>
					UI Features
					<span class="caret"></span>
				</a>	

				<ul class="sub-nav">
					<li>
						<a href="./ui-buttons.php">
							<i class="fa fa-hand-o-up"></i>
							Buttons
						</a>
					</li>
					<li>
						<a href="./ui-tabs.php">
							<i class="fa fa-reorder"></i>
							Tabs & Accordions
						</a>
					</li>

					<li>
						<a href="./ui-popups.php">
							<i class="fa fa-asterisk"></i>
							Popups / Notifications
						</a>
					</li>	

					<li>
						<a href="./ui-sliders.php">
							<i class="fa fa-tasks"></i>
							Sliders
						</a>
					</li>	
			
					<li class="">
						<a href="./ui-typography.php">
							<i class="fa fa-font"></i>
							Typography
						</a>
					</li>	
			
					<li class="">
						<a href="./ui-icons.php">
							<i class="fa fa-star-o"></i>
							Icons
						</a>
					</li>	
				</ul>
			</li>
			
			<li class="dropdown ">
				<a href="javascript:;">
					<i class="fa fa-table"></i>
					Tables
					<span class="caret"></span>
				</a>
				
				<ul class="sub-nav">
					<li>
						<a href="./table-basic.php">
							<i class="fa fa-table"></i> 
							Basic Tables
						</a>
					</li>		
					<li>
						<a href="./table-advanced.php">
							<i class="fa fa-table"></i> 
							Advanced Tables
						</a>
					</li>
					<li>
						<a href="./table-responsive.php">
							<i class="fa fa-table"></i> 
							Responsive Tables
						</a>
					</li>	
				</ul>	
								
			</li>

			<li class="">
				<a href="./ui-portlets.php">
					<i class="fa fa-list-alt"></i>
					Portlets
				</a>
			</li>
			
			<li class="dropdown ">
				<a href="javascript:;">
					<i class="fa fa-bar-chart-o"></i>
					Charts & Graphs
					<span class="caret"></span>
				</a>
				
				<ul class="sub-nav">
					<li>
						<a href="./chart-flot.php">
							<i class="fa fa-bar-chart-o"></i> 
							jQuery Flot
						</a>
					</li>
					<li>
						<a href="./chart-morris.php">
							<i class="fa fa-bar-chart-o"></i> 
							Morris.js
						</a>
					</li>
				</ul>
			</li>
			
			<li class="dropdown ">
				<a href="javascript:;">
					<i class="fa fa-file-text-o"></i>
					Extra Pages
					<span class="caret"></span>
				</a>
				
				<ul class="sub-nav">
					<li>
						<a href="./page-login.php">
							<i class="fa fa-unlock"></i> 
							Login Basic
						</a>
					</li>
					<li>
						<a href="./page-login-social.php">
							<i class="fa fa-unlock"></i> 
							Login Social
						</a>
					</li>
					<li>
						<a href="./page-404.php">
							<i class="fa fa-ban"></i> 
							404 Error
						</a>
					</li>
					<li>
						<a href="./page-500.php">
							<i class="fa fa-ban"></i> 
							500 Error
						</a>
					</li>
					<li>
						<a href="./page-blank.php">
							<i class="fa fa-file-text-o"></i> 
							Blank Page
						</a>
					</li>
				</ul>
			</li>

		</ul>
				
	</nav> <!-- #sidebar -->

</div> <!-- /#sidebar-wrapper --><?php }} ?>
