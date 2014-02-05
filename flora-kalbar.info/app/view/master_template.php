<?php
include 'meta.php';
?>
<body>
	<!-- RUNNING GLOBAL VAR -->
	<script>
		var basedomain = "<?=$basedomain?>";
	</script>
	
	<!-- HEADER -->
    <div id="header">
		<?php include 'menu.php';?>
	</div>
	
	
	<!-- CONTENT -->
	<div id="body" class="home">
		<?=$content?>
	</div>
	
	
	<!-- FOOTER -->
    <footer id="footer">
		<?php include 'footer.php';?>
	</footer>
	
</body> 
</html>