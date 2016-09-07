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
					<th width="8%">Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody id = "onchange">
				<?php
					$counter = "SELECT count(*) as total FROM company as a,rebate as b where a.company_id = b.company_id group by b.rebate_id";
					$counter2 = $conn->query($counter)->fetch_assoc();
					$perpage = 15;
					$totalPages = ceil($counter2['total'] / $perpage);
					if(!isset($_GET['view'])){
					    $_GET['view'] = 0;
					}else{
					    $_GET['view'] = (int)$_GET['view'];
					}
					if($_GET['view'] < 1){
					    $_GET['view'] = 1;
					}else if($_GET['view'] > $totalPages){
					    $_GET['view'] = $totalPages;
					}
					$startArticle = ($_GET['view'] - 1) * $perpage;
					$list = "SELECT * ,sum(b.amount) as samount, sum(b.rebate) as sumrebate FROM company as a,rebate as b where a.company_id = b.company_id group by b.company_id LIMIT " . $startArticle . ', ' . $perpage;
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
							if($row['sumrebate'] == "0"){
								$row['sumrebate'] = ' - ';
							}else{
								$row['sumrebate'] = number_format($row['rebate'],2);
							}
							echo '<tr>';
							echo '<td>' . $num . '</td>';
							echo '<td>' . $row['company_name'] .'</td>';
							echo '<td>₱ ' . number_format($row['samount'],2) . '</td>';
							echo '<td>' . $row['sumrebate'] . '</td>';
							echo '<td><b>' . $status . '</b></td>';
							echo '<td>';
								echo ' <a href = "view/info/'.$row['rebate_id'].'" class = "btn btn-sm btn-primary" data-toggle="tooltip" title="View Details"><span class = "icon-search"></span></a>';
							echo '</td>';
							echo '</tr>';
						$total += $row['samount'];
						$totrebate += $row['sumrebate'];
						}
						echo '<tr><td colspan = "2" align = "center"><b><i>Total: </td><td colspan = ""><b><i>₱ ' . number_format($total,2) . '</td><td><b><i>₱ '.number_format($totrebate,2).'</td><td colspan = 2></td></tr>';
					}else{
						echo '<tr><td colspan = "6" align = "center"> <h5> No Record Found </h5></td></tr>';
					}
				?>
			</tbody>
		</table>
	</div>
</div>