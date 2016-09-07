<div class="container-fluid" style="padding: 20px 30px;">
	<div class="row">
		<div class="col-xs-6">
			<i><h4><span class="icon-list"></span><u> Rebate List</u></h4></i>
		</div>
		<div class="col-xs-4 pull-right">
			<input type = "text" placeholder = "Search Box" onkeyup = "rebateList(this.value)" class="form-control input-sm">
		</div>
	</div>
	<div style="border: 1px solid #eee; padding: 0px 10px 10px 10px; border-radius: 5px;">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th width="25%">Company Name</th>
					<th width="10%">S.I. Date</th>
					<th width="10%">S.I. Number</th>
					<th width="8%">OR #</th>
					<th width="12%">Amount</th>
					<th width="30%">Description</th>
					<th width="10%">Status</th>
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
					$list = "SELECT * FROM company as a,rebate as b where a.company_id = b.company_id group by b.rebate_id LIMIT " . $startArticle . ', ' . $perpage;
					$res = $conn->query($list);
					if($res->num_rows > 0){
						$num = 0;
						$total = 0;
						while ($row = $res->fetch_assoc()) {
							$num += 1;	
							if($row['state'] == 0){
								$status = "<font color = 'red'>Pending</font>";
							}elseif($row['state'] == 1){
								$status = "<font color = 'green'>Collected</font>";
							}
							if($row['refnum'] != ""){
								$status = "<font color = 'green'> Paid </font>";
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
							echo '<td>' . $row['descr'] . '</td>';
							echo '<td><b>' . $status . '</b></td>';
							echo '<td align = "center">';
								if($row['state'] == "0"){
									echo '<a onclick = "payment('.$row['rebate_id'].');" class = "btn btn-sm btn-success" data-toggle="tooltip" title="Mark as Collected"><span class = "icon-checkmark"></span></a>';	
								}else{
									echo ' - ';
								}									
								//<a href = "rebate/view/'.$row['rebate_id'].'" class = "btn btn-sm btn-primary" data-toggle="tooltip" title="View"><span class = "icon-search"></span></a>
								//if($access->level >= 2){
								//	echo ' <a href = "rebate/edit/'.$row['rebate_id'].'" class = "btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><span class = "icon-quill"></span></a>';
								//	echo ' <a href = "rebate/delete/'.$row['rebate_id'].'" class = "btn btn-sm btn-danger" data-toggle="tooltip" title="Delete"><span class = "icon-bin"></span></a>';
								//}
							echo '</td>';
							echo '</tr>';
						$total += $row['amount'];
						}
						echo '<tr><td colspan = "5" align = "right"><b><i>Total: </td><td colspan = "4"><b><i>₱ ' . number_format($total,2) . '</td></tr>';
					}else{
						echo '<tr><td colspan = "9" align = "center"> <h5> No Record Found </h5></td></tr>';
					}
				?>
			</tbody>
		</table>
	</div>
	<div class="modal fade" id="payment" role="dialog"></div>
	<?php
		if(isset($_POST['paysub'])){
			$rebate_id = mysqli_real_escape_string($conn, $_POST['rebate_id']);
			$rebate = $conn->prepare("UPDATE rebate set ornum = ?, state = 1 where rebate_id = ?");
			$rebate->bind_param("si", $_POST['ornum'], $rebate_id);
			if($rebate->execute() == TRUE){
				savelogs("Update Rebate", "Rebate ID -> " . $rebate_id . ' , OR Number -> ' . $_POST['ornum'] . ' , Status -> Completed');
				echo '<script type = "text/javascript">alert("Update Rebate Successful");window.location.replace("rebate/list");</script>';
			}
		}
	?>
	<div class="row" style="margin-top: 10px;">
		<div class="col-xs-12" align="center">
			<!--<label>Records <?php $startArticlex = $startArticle + 1; $perpagex = $perpage * $_GET['view']; if($perpagex > $counter2['total']){ $perpagex = $counter2['total'];} echo $startArticlex . ' - ' . $perpagex ?> </label><br>-->
			<label> Pages </label><br>
			<?php
				$prev = intval($_GET['view'])-1;					
				if($prev > 0){ echo '<a data-toggle="tooltip" title="Previous" class = "btn btn-default btn-sm" style = "margin: 5px;" href="loan/list/' . $prev . '"> < </a>'; }
				foreach(range(1, $totalPages) as $page){
				    if($page == $_GET['view']){
				        echo '<b><span class="currentpage" style = "margin: 5px;">' . $page . '</span></b>';
				    }else if($page == 1 || $page == $totalPages || ($page >= $_GET['view'] - 2 && $page <= $_GET['view'] + 2)){
				    	if($page == 0){
				    		continue;
				    	}
				        echo '<a class = "btn btn-default btn-sm" data-toggle="tooltip" title="Page ' . $page . '" style = "margin: 5px;" href="loan/list/' . $page . '">' . $page . '</a>';
				    }
				}
				$nxt = intval($_GET['view'])+1;
				if($nxt <= $totalPages){ echo '<a class = "btn btn-default btn-sm" data-toggle="tooltip" title="Next" style = "margin: 5px;" href="loan/list/' . $nxt . '"> > </a>'; }
			?>
		</div>
	</div>
</div>