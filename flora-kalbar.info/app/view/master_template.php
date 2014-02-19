<?php
include 'meta.php';
?>
<body>
	<!-- RUNNING GLOBAL VAR -->
	<script>
		var basedomain = "<?=$basedomain?>";
	</script>
	
	<!-- HEADER -->
    <header>
		<?php include 'header.php';?>
	</header>
    
    <!-- NAVIGATION MENU -->
    <nav>
		<?php include 'menu.php';?>
	</nav>
	
	
	<!-- CONTENT -->
	<main>
		<?=$content?>
	</main>
    
    <!-- MODAL -->
    <?php include 'modal.php';?>
	
	
	<!-- FOOTER -->
    <footer>
		<?php include 'footer.php';?>
	</footer>
	
</body> 
</html>