<?php
	$page = array(
				"rebate" => "New Rebate Application",
				"list" => "Rebate List",
				"due" => "Due List",
				"audit" => "Audit Logs",
				"view" => "View Rebate",
				);

	foreach($page as $x => $tag) {
	    if(isset($_GET['action']) && $_GET['action'] == $x){
			$title = $tag;
	    }elseif(isset($_GET['module']) && $_GET['module'] == $x){
			$title = $tag;
	    }elseif(isset($_SESSION['rebate_acc']) && isset($_GET['module']) != $x){
			$title = "Dashboard";
		}elseif(!isset($_SESSION['rebate_acc'])){
			$title = "Login Page";
		}
	}
?>