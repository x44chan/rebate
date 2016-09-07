<?php
	unset($_SESSION['rebate_acc']);
	unset($_SESSION['name']);
	unset($_SESSION['level']);
	$_SESSION['logout'] = "1";
?>	
	<script type="text/javascript"> 
		window.location.replace('<?php echo "$_SERVER[REQUEST_URI]";?>');		
	</script>	
	