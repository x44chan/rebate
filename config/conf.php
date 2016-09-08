<?php
	$host = "127.0.0.1";
	$uname = "root";
	$pword = "";
	$db = "rebate";
	
	$conn = new mysqli($host, $uname, $pword, $db);
	
	if($conn->connect_error){
		die("Connection error:". $conn->connect_error);
	}
	function savelogs($transaction,$transdetails){
		$host = "127.0.0.1";
		$uname = "root";
		$pword = "";
		$db = "rebate";
		$conn = mysqli_connect($host, $uname, $pword, $db);
		if (mysqli_connect_errno()){
			die ('Unable to connect to database '. mysqli_connect_error());
		}
		$pcname = gethostname();
		
	    $username = $_SESSION['username'];
		$realname = $_SESSION['name'];
	    $sqllogs = "insert into audit_trail(username,realname,transaction,datetrans,transdetail,pcname) values ('$username','$realname','$transaction',now(),'$transdetails','$pcname')";            
	    $result = mysqli_query($conn, $sqllogs);
	}
?>
