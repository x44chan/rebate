<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<i><h4  style="margin-left: -40px;"><span class="icon-coin-dollar"></span> <u>New Rebate</u></h4></i>
		</div>
	</div>
	<form action = "" method="post">
		<div style="border: 1px solid #eee; padding: 0px 10px 10px 10px; border-radius: 5px;">
			<div class="row">
				<div class="col-xs-12">
					<h5><b><u><i><span class="icon-library"></span> Company Information</i></u></b></h5>
				</div>
			</div>
			<div  id = "new" style="display: none;">
				<div class="row" style="margin-left: 20px;">
					<div class="col-md-6 col-xs-12">
						<label>Company Name<font color = "red"> * </font></label>
						<input type = "text" name = "company" class="form-control input-sm" placeholder = "Enter First Name" autocomplete = "off">
					</div>
				</div>
			</div>
			<div class="row" style="margin-left: 20px;" id = "select">
				<div class="col-md-6 col-xs-12">
					<label>Select Company <font color = "red">*</font></label>
					<select class="form-control input-sm" name = "company_id" required>
						<option value=""> - - - - - - </option>
						<?php
							$company = "SELECT * FROM company";
							$company = $conn->query($company);
							if($company->num_rows > 0){
								while ($row = $company->fetch_object()) {
									echo '<option value = "' . $row->company_id . '"> ' . $row->company_name . '</option>';
								}
							}

						?>
					</select>
				</div>
			</div>
			<div class="row" style="margin-left: 20px;">
				<div class="col-md-6 col-xs-12">
					<label><input type = "checkbox" name = "checkbox" id = "checkbox"/> Add new company </label>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<hr>
					<h5><b><u><i><span class="icon-coin-dollar"></span> Rebate Information</i></u></b></h5>
				</div>
			</div>
			<div class="row" style="margin-left: 20px;">
				<div class="col-md-2 col-xs-12">
					<label>Sales Invoice Date<font color = "red"> *</font></label>
					<input type = "date" name = "sidate" class="form-control input-sm" required autocomplete = "off">
				</div>
				<div class="col-md-2 col-xs-12">
					<label>Sales Invoice # <font color = "red"> * </font></label>
					<input type = "text" name = "sinum" class="form-control input-sm" placeholder = "Enter Sales Invoice Number" required pattern = "[0-9]*" autocomplete = "off">
				</div>
				<div class="col-md-2 col-xs-12">
					<label>Amount <font color = "red"> * </font></label>
					<input type = "text" name = "amount" class="form-control input-sm" placeholder = "Enter Amount">					
				</div>
				<div class="col-md-6 col-xs-12">
					<label>Description <font color = "red">*</font></label>
					<textarea name="descr" class="form-control input-sm" placeholder = "Enter description"></textarea>
				</div>
			</div>
			<div class="row" style="margin-top: 20px;">
				<div class="col-xs-12" align="center">
					<hr>
					<button class="btn btn-primary btn-sm" name = "rebatesub" onclick = "return confirm('Are you sure?');"><span class = "icon-floppy-disk"></span> Save </button>
				</div>
			</div>
		</div>		
	</form>
</div>
<?php
	if(isset($_POST['rebatesub'])){
		if(!isset($_POST['company_id'])){
			$cust = $conn->prepare("INSERT INTO company (company_name) VALUES (?)");
			$cust->bind_param("s", $_POST['company']);
			if($cust->execute() == TRUE){	
				$cust_id = 	$conn->insert_id;
			}
			savelogs("Add new company", 'Company ID -> ' . $cust_id . ', Company Name -> ' . $_POST['company']);
		}else{
			$cust_id = mysqli_real_escape_string($conn, $_POST['company_id']);
		}
		$rebate = $conn->prepare("INSERT INTO rebate (company_id, sidate, sinum, amount, descr) VALUES (?, ?, ?, ?, ?)");
		$rebate->bind_param("issss", $cust_id, $_POST['sidate'], $_POST['sinum'], $_POST['amount'], $_POST['descr']);
		if($rebate->execute() == TRUE){
			savelogs("Add new rebate", 'Rebate ID -> ' . $conn->insert_id . ", Company ID -> " . $cust_id . " Sales Invoice Date -> " . $_POST['sidate'] . ', Sales Invoice # -> ' . $_POST['sinum'] . ', Amount -> ' . $_POST['amount'] . ', Description -> ' . $_POST['descr']);
			echo '
				<script type = "text/javascript">alert("Adding Record Successful");window.location.replace("rebate/list");</script>';
		}
	}
	
?>