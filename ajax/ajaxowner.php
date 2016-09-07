<?php
	include '../config/conf.php';
	if(!isset($_GET['module'])){
		echo '<script type = "text/javascript">window.location.replace("/loan/");</script>';
	}
?>
<?php
	if(isset($_GET['rebateList'])){
?>
	<?php
		$search = mysqli_real_escape_string($conn, $_GET['rebateList']);
		$search = str_replace(",", "", $search);
		$where = " and (a.company_name like '%$search%' or b.amount like '%$search%' or b.sinum like '%$search%' or b.sidate like '%$search%' or b.ornum like '%$search%' or b.descr like '%$search%' or b.state like '%$search%') ";
		if($search == ""){
			$where = "";
		}
		$counter = "SELECT count(*) as total FROM company as a,rebate as b where a.company_id = b.company_id " . $where . " group by b.rebate_id ";
		$counter2 = $conn->query($counter)->fetch_assoc();
		$perpage = 15;
		$totalPages = ceil($counter2['total'] / $perpage);
		if(!isset($_GET['page'])){
		    $_GET['page'] = 0;
		}else{
		    $_GET['page'] = (int)$_GET['page'];
		}
		if($_GET['page'] < 1){
		    $_GET['page'] = 1;
		}else if($_GET['page'] > $totalPages){
		    $_GET['page'] = $totalPages;
		}
		$startArticle = ($_GET['page'] - 1) * $perpage;
		$list = "SELECT * FROM company as a,rebate as b where a.company_id = b.company_id " . $where . " group by b.rebate_id LIMIT " . $startArticle . ', ' . $perpage;
		$res = $conn->query($list);
		if($res->num_rows > 0){
			$num = 0;
			while ($row = $res->fetch_assoc()) {
				$num += 1;
				if($row['state'] == 0){
					$status = "<font color = 'red'>Pending</font>";
				}elseif($row['state'] == 1){
					$status = "<font color = 'green'>Collected</font>";
				}
				if($row['ornum'] == ""){
					$row['ornum'] = ' - ';
				}
				echo '<tr>';
				echo '<td>' . $num . '</td>';
				echo '<td>' . $row['company_name'] .'</td>';
				echo '<td>' . date("M j, Y", strtotime($row['sidate'])) . '</td>';
				echo '<td>' . $row['sinum'] . '</td>';
				echo '<td>' . $row['ornum'] . '</td>';
				echo '<td>₱ ' . number_format($row['amount'],2) . '</td>';
				echo '<td>' . $row['descr'] . $row['descr'] . $row['descr']. $row['descr'] . '</td>';
				echo '<td><b>' . $status . '</b></td>';
				echo 
					'<td>
						
						<a onclick = "payment('.$row['rebate_id'].');" class = "btn btn-sm btn-success" data-toggle="tooltip" title="Mark as Collected"><span class = "icon-checkmark"></span></a>';
					//<a href = "rebate/view/'.$row['rebate_id'].'" class = "btn btn-sm btn-primary" data-toggle="tooltip" title="View"><span class = "icon-search"></span></a>
					//if($access->level >= 2){
					//	echo ' <a href = "rebate/edit/'.$row['rebate_id'].'" class = "btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><span class = "icon-quill"></span></a>';
					//	echo ' <a href = "rebate/delete/'.$row['rebate_id'].'" class = "btn btn-sm btn-danger" data-toggle="tooltip" title="Delete"><span class = "icon-bin"></span></a>';
					//}
				echo '</td>';
				echo '</tr>';
			}
		}else{
			echo '<tr><td colspan = "9" align = "center"> <h5> No Record Found </h5></td></tr>';
		}
	?>
<?php
	}
?>
<?php	if(isset($_GET['payment'])){ 	?>
	<div class="modal-dialog">    
    	<!-- Modal content-->
    	<div class="modal-content">
	        <div class="modal-header" style="padding:35px 50px;">
	        	<button type="button" class="close" data-dismiss="modal">&times;</button>
	        	<h4><span class = "icon-coin-dollar"></span> Payment</h4>
	        </div>
	        <div class="modal-body" style="padding:40px 50px;">
	        	<form role="form" action = "" method = "post">
	        		<div class="form-group">
	            		<label>OR Number <font color = "red">*</font> </label>
	            		<input type = "text" name = "ornum" class="form-control input-sm" placeholder = "Enter ornumber" pattern = "[.0-9]*" required>
		            </div>
	            	<div class="col-xs-12" align="center">
	            		<button type="submit" style = "width: 200px;" name = "paysub" class="btn btn-success btn-block btn-sm" onclick="return confirm('Are you sure?');"><span class ="icon-checkmark"></span> Update Rebate</button>
	            	</div>
	            	<input type = "hidden" value = "<?php echo mysqli_real_escape_string($conn, $_GET['payment']);?>" name = "rebate_id"/>
	          	</form>
	    	</div>
    	</div>
    </div>
<?php
	}
?>
<?php	if(isset($_GET['deposit'])){ 	?>
	<div class="modal-dialog">    
    	<!-- Modal content-->
    	<div class="modal-content">
	        <div class="modal-header" style="padding:35px 50px;">
	        	<button type="button" class="close" data-dismiss="modal">&times;</button>
	        	<h4><span class = "icon-coin-dollar"></span> Deposit Rebate</h4>
	        </div>
	        <div class="modal-body" style="padding:40px 50px;">
	        	<form role="form" action = "" method = "post">
	        		<div class="form-group">
	            		<label>Rebate Amount <font color = "red">*</font> </label>
	            		<input value = "<?php echo $_GET['amount'];?>" type = "text" name = "amount" class="form-control input-sm" placeholder = "Enter ornumber" pattern = "[.0-9]*" required>
		            </div>
		            <div class="form-group">
	            		<label>Reference #: <font color = "red">*</font> </label>
	            		<input type = "text" name = "refnum" class="form-control input-sm" placeholder = "Enter ornumber" required>
		            </div>
	            	<div class="col-xs-12" align="center">
	            		<button type="submit" style = "width: 200px;" name = "deposit" class="btn btn-success btn-block btn-sm" onclick="return confirm('Are you sure?');"><span class ="icon-checkmark"></span> Update Rebate</button>
	            	</div>
	            	<input type = "hidden" value = "<?php echo mysqli_real_escape_string($conn, $_GET['deposit']);?>" name = "rebate_id"/>
	          	</form>
	    	</div>
    	</div>
    </div>
<?php
	}
?>
<?php	if(isset($_GET['addrebate'])){ 	?>
	<div class="modal-dialog">    
    	<!-- Modal content-->
    	<div class="modal-content">
	        <div class="modal-header" style="padding:35px 50px;">
	        	<button type="button" class="close" data-dismiss="modal">&times;</button>
	        	<h4><span class = "icon-ticket"></span> Add Rebate </h4>
	        </div>
	        <div class="modal-body" style="padding:40px 50px;">
	        	<form role="form" action = "" method = "post">
	        		<div class="form-group">
	            		<label>Rebate Amount <font color = "red">*</font> </label>
	            		<input type = "text" name = "rebateamount" class="form-control input-sm" placeholder = "Enter ornumber" pattern = "[.0-9]*" required>
		            </div>
	            	<div class="col-xs-12" align="center">
	            		<button type="submit" style = "width: 200px;" name = "rebatesub" class="btn btn-success btn-block btn-sm" onclick="return confirm('Are you sure?');"><span class ="icon-checkmark"></span> Update Rebate</button>
	            	</div>
	            	<input type = "hidden" value = "<?php echo mysqli_real_escape_string($conn, $_GET['addrebate']);?>" name = "rebate_id"/>
	          	</form>
	    	</div>
    	</div>
    </div>
<?php
	}
?>