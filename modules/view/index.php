<div class="container-fluid" style="padding-left: 30px; padding-right: 30px;">
	<div class="row">
		<div class="col-xs-6">
			<i><h4><span class="icon-ticket"></span><u> Rebates </u></h4></i>
		</div>
	</div>
	<div style="border: 1px solid #eee; padding: 0px 10px 10px 10px; border-radius: 5px;">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th width="25%">Company Name</th>
					<th width="25%">Total Invoice Amount</th>
					<th width="10%">Rebate</th>
					<th width="15%">Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody id = "onchange">
				<?php
					$list = "SELECT * ,sum(b.amount) as samount, sum(b.rebate) as sumrebate FROM company as a,rebate as b where a.company_id = b.company_id and (b.refnum = '' or b.refnum is null) group by b.company_id";
					$res = $conn->query($list);
					$total = 0;
					$totrebate = 0;
					$nopen = "";
					$num = 0;
					if($res->num_rows > 0){
						while ($row = $res->fetch_assoc()) {
							$num += 1;	
							if($num == $res->num_rows){
								$nopen .= $row['company_id']; 
							}else{
								$nopen .= $row['company_id'] . ',';
							}
							if($row['state'] <= 1){
								$status = "<font color = 'red'>Pending</font>";
							}elseif($row['state'] > 1){
								$status = "<font color = 'green'>Completed</font>";
							}
							if($row['sumrebate'] == "0"){
								$row['sumrebate'] = ' - ';
							}else{
								$totrebate += $row['sumrebate'];
								$row['sumrebate'] = '₱ ' .number_format($row['sumrebate'],2);
							}
							echo '<tr>';
							echo '<td>' . $num . '</td>';
							echo '<td>' . $row['company_name'] .'</td>';
							echo '<td>₱ ' . number_format($row['samount'],2) . '</td>';
							echo '<td>' . $row['sumrebate'] . '</td>';
							echo '<td><b>' . $status . '</b></td>';
							echo '<td>';
								echo ' <a href = "view/info/'.$row['company_id'].'" class = "btn btn-sm btn-primary" data-toggle="tooltip" title="View Details"><span class = "icon-search"></span></a>';
							echo '</td>';
							echo '</tr>';
						$total += $row['samount'];
						}
					}
					if($nopen != ""){
						$where = " where company_id NOT IN ($nopen) ";
					}else{
						$where = "";
					}
					$comp = "SELECT * FROM company " . $where;
					$res = $conn->query($comp);
					if($res->num_rows > 0){
						while ($row = $res->fetch_object()) {
							$num += 1;	
							echo '<tr>';
							echo '<td>' . $num . '</td>';
							echo '<td>' . $row->company_name .'</td>';
							echo '<td>₱ 0.00 </td>';
							echo '<td>₱ 0.00 </td>';
							echo '<td><b><font color = "red"> No Pending Rebate </font></b></td>';
							echo '<td>';
								echo ' <a href = "view/info/'.$row->company_id.'" class = "btn btn-sm btn-primary" data-toggle="tooltip" title="View Details"><span class = "icon-search"></span></a>';
							echo '</td>';
							echo '</tr>';
						}
					}
					echo '<tr><td colspan = "2" align = "center"><b><i>Total: </td><td colspan = ""><b><i>₱ ' . number_format($total,2) . '</td><td><b><i>₱ '.number_format($totrebate,2).'</td><td colspan = 2></td></tr>';
				?>
			</tbody>
		</table>
	</div>
</div>