<?php
    session_start();
    include 'config/title.php';
    include 'config/header.php';
    include 'config/conf.php';
    if(!isset($_GET['module'])){
      $_GET['module'] = 'main';
    }
    if(isset($_SESSION['rebate_acc'])){
      $access = "SELECT * FROM user where account_id = '$_SESSION[rebate_acc]'";
      $access = $conn->query($access)->fetch_object();
      include('config/menu.php');
?>
  <div class="modal fade" id="changepass" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Update Password</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "" method = "post">
            <div class="form-group">
              <label for="usrname"><span class="icon-eye"></span> Password</label>
              <input type="password" class="form-control" required id="psw" name = "pword" autocomplete="off"placeholder="Enter password">
            </div>
            <div class="form-group">
              <label for="psw"><span class="icon-eye"></span> Confirm Password</label>
              <input type="password" class="form-control" required id="psw1" name = "pword2" autocomplete="off"placeholder="Enter password">
            </div>
              <button type="submit" id = "submitss" name = "submitpw" class="btn btn-success btn-block"><span class="icon-switch"></span> Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>
    <div id="loader"></div>
    <!-- Page Content -->
    <div class = "container-fluid animate-bottom" id = "tohide" <?php if(!isset($_GET['print'])){ echo ' style="margin-top: 60px; display: none;"'; }else{ echo ' style = "visibility: hidden" '; }?>>
      <?php
        include 'ajax/func.php';
        if(!isset($_GET['action'])){
            $acc = 'index.php';
        }else{
            $acc = $_GET['action'].'.php';
        }
        if($access->level == '0' && $_GET['module'] != 'logout'){
          $_GET['module'] = 'view';
        }

        if(!isset($_GET['module'])){
          include 'modules/main/index.php';
        }elseif($_GET['module'] == 'logout'){
            include 'modules/logout.php';
        }elseif(!file_exists('modules/'.$_GET['module'].'/'.$acc)){
            include 'config/404.php';
        }else{
            include 'modules/'.$_GET['module'].'/'.$acc;
        }
     }elseif((isset($_GET['module']) && $_GET['module'] == 'login' && !isset($_SESSION['rebate_acc'])) || (!isset($_SESSION['rebate_acc']))){
      ?>
<style type="text/css">
	.table {border-bottom:0px !important;}
	.table th, .table td {border: 0px !important;}
</style>
		<h3 align="center"><i><span class="icon-lock"></span><i class="fa fa-desktop"></i> Rebate Login Form</i></h3>
		<form role = "form" action = "" method = "post" id = "tohide" style="display: none;">	
			<table align = "center" class = "table form-horizontal" style = "margin-top: 0px; width: 800px;" >
				<tr>
					<td><label for = "uname"><span class="icon-user"></span>  Username: </label><input <?php if(isset($_POST['uname'])){ echo 'value ="' . $_POST['uname'] . '"'; }else{ echo 'autofocus ';}?>placeholder = "Enter Username" id = "uname" title = "Input your username." type = "text" class = "form-control input-sm" required name = "uname"/></td>
				
					<td><label for = "pword"><span class="icon-eye"></span>  Password: </label><input <?php if(isset($_POST['uname'])){ echo 'autofocus '; }?> placeholder = "Enter Password" id = "pword" title = "Input your password." type = "password" class = "form-control  input-sm" required name = "password"/></td>
				</tr>
				<tr >
					<td colspan = 4 align = "center" ><button style = "width: 150px; margin: auto;" type="submit" name = "submit" class="btn btn-success btn-block btn-sm"><span class="icon-switch"></span> Login</button></td>
				</tr>
			</table>
		</form>
<?php
	if(isset($_SESSION['logout']) && $_SESSION['logout'] != null){
		echo  '<div class="alert alert-warning" align = "center">						
			<strong>You\'ve been logged out.</strong>
			</div>';
		$_SESSION['logout'] = null;
	}
?>
<?php
	if(isset($_POST['submit'])){
		$uname = mysqli_real_escape_string($conn, $_POST['uname']);
		$password =  mysqli_real_escape_string($conn, $_POST['password']);
		
		$sql = "SELECT * FROM `user` where uname = '$uname' and pword = '$password'";
		$result = $conn->query($sql);		
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){								
				$_SESSION['rebate_acc'] = $row['account_id'];
				$_SESSION['name'] = $row['fname'] . ' ' . $row['mname'] . ', ' . $row['lname'];
        $_SESSION['username'] = $row['uname'];
				//$_SESSION['level']=$row['level'];
			  	echo  '<div class="alert alert-success" align = "center">						
						<strong>Logging in ~!</strong>
						</div>';
			  	echo '<script type="text/javascript">setTimeout(function() {window.location.href = "/rebate"},1000);; </script>';	
			}				
		}else{
	echo  '<div class="alert alert-warning" align = "center">						
				<strong>Warning!</strong> Incorrect Login.
			</div>';
			}
		$conn->close();
	}
}
include('config/footer.php');
?>