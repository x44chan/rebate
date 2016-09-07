<script type="text/javascript">	
	<?php if($access->level >= 1){ ?>
	function rebateList(str) {
	   if (window.XMLHttpRequest) {
	        // code for IE7+, Firefox, Chrome, Opera, Safari
	        xmlhttp = new XMLHttpRequest();
	    } else {
	        // code for IE6, IE5
	        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	    }
	    xmlhttp.onreadystatechange = function() {
	        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	            document.getElementById("onchange").innerHTML = xmlhttp.responseText;
	        }
	    };
	    xmlhttp.open("GET","ajax/ajaxowner.php?rebateList="+str,true);
	    xmlhttp.send();		
	}
	function payment(str) {
		if (str == "") {
		    document.getElementById("payment").innerHTML = "";
		    return;
		} else { 
		    if (window.XMLHttpRequest) {
		        // code for IE7+, Firefox, Chrome, Opera, Safari
		        xmlhttp = new XMLHttpRequest();
		    } else {
		        // code for IE6, IE5
		        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		    }
		    xmlhttp.onreadystatechange = function() {
		        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		            document.getElementById("payment").innerHTML = xmlhttp.responseText;
		        }
		    };
		    xmlhttp.open("GET","ajax/ajaxowner.php?payment="+str,true);
		    xmlhttp.send();
		    $("#payment").modal();
		}
	}
	<?php } ?>
	<?php if($access->level > 1){ ?>
		
	function rebate(str) {
		if (str == "") {
		    document.getElementById("addrebate").innerHTML = "";
		    return;
		} else { 
		    if (window.XMLHttpRequest) {
		        // code for IE7+, Firefox, Chrome, Opera, Safari
		        xmlhttp = new XMLHttpRequest();
		    } else {
		        // code for IE6, IE5
		        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		    }
		    xmlhttp.onreadystatechange = function() {
		        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		            document.getElementById("addrebate").innerHTML = xmlhttp.responseText;
		        }
		    };
		    xmlhttp.open("GET","ajax/ajaxowner.php?addrebate="+str,true);
		    xmlhttp.send();
		    $("#addrebate").modal();
		}
	}
	function deposit(str,amount) {
		if (str == "" || amount == "") {
		    document.getElementById("addrebate").innerHTML = "";
		    return;
		} else { 
		    if (window.XMLHttpRequest) {
		        // code for IE7+, Firefox, Chrome, Opera, Safari
		        xmlhttp = new XMLHttpRequest();
		    } else {
		        // code for IE6, IE5
		        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		    }
		    xmlhttp.onreadystatechange = function() {
		        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		            document.getElementById("addrebate").innerHTML = xmlhttp.responseText;
		        }
		    };
		    xmlhttp.open("GET","ajax/ajaxowner.php?deposit="+str+"&amount="+amount,true);
		    xmlhttp.send();
		    $("#addrebate").modal();
		}
	}
	<?php } ?>
</script>