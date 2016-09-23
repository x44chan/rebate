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
					<th width="12%">Rebate</th>
					<th width="30%">Description</th>
					<th width="10%">Status</th>
				<?php if($access->level > 1){ ?>
					<th>Action</th>
				<?php }	?>
				</tr>
			</thead>
			<tbody id = "onchange">
				<?php
					$counter = "SELECT count(*) as total FROM company as a,rebate as b where a.company_id = b.company_id and (b.refnum is null or b.refnum = '') group by b.rebate_id";
					$counter2 = $conn->query($counter)->fetch_assoc();
					$perpage = 100;
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
					$list = "SELECT * FROM company as a,rebate as b where a.company_id = b.company_id and (b.refnum is null or b.refnum = '') group by b.rebate_id  LIMIT " . $startArticle . ', ' . $perpage;
					$res = $conn->query($list);
					if($res->num_rows > 0){
						$num = 0;
						$total = 0;
						$totrebate = 0;
						$forpay = array();
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
							$rebate = $row['rebate'];
							if($row['ornum'] == ""){
								$row['ornum'] = ' - ';
							}
							if($row['rebate'] == "0" && $access->level > 1){
								$row['rebate'] = '<a onclick = "rebate('.$row['rebate_id'].');" class = "btn btn-sm btn-primary" data-toggle="tooltip" title="Add Rebate"><span class = "icon-plus"></span></a>';
							}else{
								$totrebate += $row['rebate'];
								$row['rebate'] = '₱ ' . number_format($row['rebate'],2);
							}
							
							echo '<tr>';
							echo '<td>' . $num . '</td>';
							echo '<td>' . $row['company_name'] .'</td>';
							echo '<td>' . date("M j, Y", strtotime($row['sidate'])) . '</td>';
							echo '<td>' . $row['sinum'] . '</td>';
							echo '<td>' . $row['ornum'] . '</td>';
							echo '<td>₱ ' . number_format($row['amount'],2) . '</td>';
							echo '<td>' . $row['rebate'] . '</td>';
							echo '<td>' . $row['descr'] . '</td>';
							echo '<td><b>' . $status . '</b></td>';							
								if($access->level > 1){
									echo '<td align = "center">';
									if($row['state'] < 2 && $rebate > 0 && $row['refnum'] == ""){
										echo '<a onclick = "deposit('.$row['rebate_id'].','.$rebate.');" class = "btn btn-sm btn-success" data-toggle="tooltip" title="Add Payment"><span>₱</span></a>';	
										$forpay[] = $row['rebate_id'];
									}else{
										echo ' - ';
									}		
									echo '</td>';
								}							
								//<a href = "rebate/view/'.$row['rebate_id'].'" class = "btn btn-sm btn-primary" data-toggle="tooltip" title="View"><span class = "icon-search"></span></a>
								//if($access->level >= 2){
								//	echo ' <a href = "rebate/edit/'.$row['rebate_id'].'" class = "btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><span class = "icon-quill"></span></a>';
								//	echo ' <a href = "rebate/delete/'.$row['rebate_id'].'" class = "btn btn-sm btn-danger" data-toggle="tooltip" title="Delete"><span class = "icon-bin"></span></a>';
								//}							
							echo '</tr>';
							$total += $row['amount'];
						}
						if(!empty($forpay)){
							$ids = "";
							for($i = 0; $i < count($forpay); $i++){
								if($i == count($forpay) - 1){
									$ids .= $forpay[$i];
								}else{
									$ids .= $forpay[$i] . ',';
								} 
							}
							$ids = '<a onclick = "deposit(\''.$ids.'\','.$totrebate.');" class = "btn btn-sm btn-success" data-toggle="tooltip" title="Pay All"><span>₱</span>ay All </a>';
						}else{
							$ids = "";
						}
						echo '<tr><td colspan = "4" align = "right"><b><i>Total: </td><td colspan = "1"></td><td><b><i>₱ ' . number_format($total,2) . '</td><td colspan = "3"><b><i>₱ '.number_format($totrebate,2).'</td><td>' . $ids . '</td></tr>';
					}else{
						echo '<tr><td colspan = "9" align = "center"> <h5> No Record Found </h5></td></tr>';
					}
				?>
			</tbody>
		</table>
	</div>
	<div class="modal fade" id="addrebate" role="dialog"></div>
	<?php
		if(isset($_POST['rebatesub'])){
			$rebate_id = mysqli_real_escape_string($conn, $_POST['rebate_id']);
			$rebate = $conn->prepare("UPDATE rebate set rebate = ? where rebate_id = ? and rebate = 0");
			$rebate->bind_param("si", $_POST['rebateamount'], $rebate_id);
			if($rebate->execute() == TRUE){
				savelogs("Update Rebate", "Rebate ID -> " . $rebate_id . ' , Add Rebate -> ₱ ' . number_format($_POST['rebateamount'],2) . ' , Status -> For Payment');
				echo '<script type = "text/javascript">alert("Update Rebate Successfull");window.location.replace("/rebate");</script>';
			}
		}
	?>
	<?php
		if(isset($_POST['deposit'])){
			$rebate_id = mysqli_real_escape_string($conn, $_POST['rebate_id']);
			$rebate = $conn->prepare("UPDATE rebate set refnum = ?, paydate = now() where rebate_id in ($rebate_id) and (refnum ='' or refnum is null)");
			$rebate->bind_param("s", $_POST['refnum']);
			if($rebate->execute() == TRUE){
				savelogs("Paid Rebate", "Rebate ID -> " . $rebate_id . ' , Amount -> ₱ '. number_format($_POST['amount'],2) . ', Reference Number -> ' . $_POST['refnum'] . ' , Status -> Completed');
				echo '<script type = "text/javascript">alert("Payment Successfull");window.location.replace("/rebate");</script>';
				
				$company = "SELECT company_id FROM rebate where rebate_id IN ($rebate_id) GROUP BY company_id";
				$companyres = $conn->query($company);
				if($companyres->num_rows > 0){
					$mail_To = 'chano.rocks@gmail.com';
			        $mail_Subject = "Rebate Payment Notification";
			        $headers = "From: donotreply@netlinkph.net" . "\r\n";
			        $headers .= 'Cc: c.aquino_programmer@yahoo.com' . "\r\n";
			        $mail_Body = "Helo Sir, \n\n".
					"Payment Sent for the Rebate #'s: " . $rebate_id . "\n\n".
					"Reference #: " . $_POST['refnum'] . "\n\n".
					"Amount: ₱ " .number_format($_POST['amount'],2) . "\n\n".
					"Click the link/s below to view the details." . "\n\n";
					while ($rowx = $companyres->fetch_object()){						
						 $mail_Body .= "http://uplinkph.net/rebate/view/info/". $rowx->company_id . "\n\n";
					}
					$mail_Body .= "(This is an automated email from Netlink Advance Solutions Inc.):  \n ";		         
			        mail($mail_To, $mail_Subject, $mail_Body,$headers);
				}					
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