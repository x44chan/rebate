<?php
	$c_id = mysqli_real_escape_string($conn, $_GET['view']);
	$list = "SELECT * FROM company as a,rebate as b where a.company_id = '$c_id' and b.company_id = '$c_id' group by b.rebate_id";
	$res = $conn->query($list)->fetch_assoc();
	if($conn->query($list)->num_rows <= 0){
		echo '<script type = "text/javascript">alert("No record found.");window.location.replace("/loan/?module=loan&action=list");</script>';
	}
?>
<div class="container" id = "reportg">
	<div class="row">
		<div class="col-xs-6">
			<i><h4  style="margin-left: -40px;"><span class="icon-ticket"></span><u> Rebate Informations</u></h4></i>
		</div>
		<div class="col-xs-6">
			<a href = "javascript:javascript:history.go(-1)" class="btn btn-danger btn-sm pull-right" data-toggle="tooltip" title="Back"><span class = " icon-exit"></span> Back to List </a>
		</div>
	</div>
	<div style="border: 1px solid #eee; padding: 0px 10px 10px 10px; border-radius: 5px;">
		<div class="row">
			<div class="col-xs-12">
				<h5><b><u><i><span class="icon-library"></span> Company Information</i></u></b></h5>
			</div>
		</div>	
		<div class="row" style="margin-left: 20px;">
			<div class="col-xs-6">
				<label>Company Name</label>
				<p style="margin-left: 10px;"><i><?php echo $res['company_name']; ?></i></p>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<hr>
				<h5><b><u><i><span class="icon-ticket"></span> Rebate Details</i></u></b></h5>
			</div>
		</div>
		<div style="border: 1px solid #eee; padding: 0px 10px 10px 10px; border-radius: 5px;">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th width="25%">Company Name</th>
					<th width="12%">Amount</th>
					<th width="12%">Rebate</th>
					<th width="30%">Description</th>
					<th width="15%">Status</th>
				</tr>
			</thead>
			<tbody id = "onchange">
				<?php
					$list = "SELECT * FROM company as a,rebate as b where a.company_id = b.company_id group by b.rebate_id";
					$res = $conn->query($list);
					if($res->num_rows > 0){
						$num = 0;
						$total = 0;
						$totrebate = 0;
						while ($row = $res->fetch_assoc()) {
							$num += 1;	
							if($row['state'] <= 1){
								$status = "<font color = 'red'>Pending</font>";
							}elseif($row['state'] > 1){
								$status = "<font color = 'green'>Completed</font>";
							}
							if($row['refnum'] != ""){
								$status = "<font color = 'green'>Paid (Ref: " . $row['refnum'] . " )</font>";
							}
							if($row['rebate'] == "0"){
								$row['rebate'] = ' - ';
							}else{
								$row['rebate'] = number_format($row['rebate'],2);
							}
							echo '<tr>';
							echo '<td>' . $num . '</td>';
							echo '<td>' . $row['company_name'] .'</td>';
							echo '<td>₱ ' . number_format($row['amount'],2) . '</td>';
							echo '<td>' . $row['rebate'] . '</td>';
							echo '<td>' . $row['descr'] . '</td>';
							echo '<td><b>' . $status . '</b></td>';
							echo '</tr>';						
						$total += $row['amount'];
						$totrebate += $row['rebate'];
						}
						echo '<tr><td colspan = "2" align = "right"><b><i>Total: </td><td><b><i>₱ ' . number_format($total,2) . '</td><td><b><i>₱ '.number_format($totrebate,2).'</td><td colspan = 2></td></tr>';
					}else{
						echo '<tr><td colspan = "9" align = "center"> <h5> No Record Found </h5></td></tr>';
					}
				?>
			</tbody>
		</table>
	</div>
	</div>
</div>
<script type="text/javascript">
	document.title = "View Loan -> <?php echo $res['fname'] . ' ' . $res['mname'] . ' ' . $res['lname']?>"; 
</script>