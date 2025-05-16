<?php
if(!isset($conn)){
	include 'db_connect.php';
}
?>
<style>
  /* Update header color from blue to black with yellow-green accents */
  .main-header.navbar-primary.navbar-dark {
    background-color: #000000 !important;
  }
  
  /* Style card with better shadows and borders */
  .card {
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border: none;
  }
  
  .card-body {
    padding: 25px;
  }
  
  /* Better looking headings */
  .text-muted {
    color: #333 !important;
    font-size: 18px;
    border-bottom: 2px solid #9ACD32;
    padding-bottom: 8px;
    margin-bottom: 15px;
    display: inline-block;
  }
  
  /* Better form controls */
  .form-control {
    border-radius: 4px;
    border: 1px solid #ddd;
    padding: 8px 12px;
    transition: all 0.3s;
  }
  
  .form-control:focus {
    border-color: #9ACD32;
    box-shadow: 0 0 0 0.2rem rgba(154, 205, 50, 0.25);
  }
  
  /* Better buttons */
  .btn-primary {
    background-color: #9ACD32 !important;
    border-color: #9ACD32 !important;
    color: #000 !important;
    font-weight: 600;
  }
  
  .btn-primary:hover {
    background-color: #8BB42D !important;
    border-color: #8BB42D !important;
  }
  
  .btn-secondary {
    background-color: #f8f9fa;
    border-color: #ddd;
    color: #333;
  }
  
  /* Form group spacing */
  .form-group {
    margin-bottom: 18px;
  }
  
  /* Label styling */
  .control-label {
    font-weight: 500;
    color: #555;
    margin-bottom: 5px;
  }
</style>

<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_ticket">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Subject</label>
							<input type="text" name="subject" class="form-control form-control-sm" required value="<?php echo isset($subject) ? $subject : '' ?>">
						</div>
					<?php if($_SESSION['login_type'] != 3): ?>
						<div class="form-group">
							<label for="" class="control-label">Customer</label>
							<select name="customer_id" id="customer_id" class="custom-select custom-select-sm select2">
								<option value=""></option>
							<?php
								$department = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM customers order by concat(lastname,', ',firstname,' ',middlename) asc");
								while($row = $department->fetch_assoc()):
							?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($customer_id) && $customer_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
							<?php endwhile; ?>
							</select>
						</div>
					<?php endif; ?>
						<div class="form-group">
							<label for="" class="control-label">Department</label>
							<select name="department_id" id="department_id" class="custom-select custom-select-sm select2">
								<option value=""></option>
							<?php
								$department = $conn->query("SELECT * FROM departments order by name asc");
								while($row = $department->fetch_assoc()):
							?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($department_id) && $department_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
							<?php endwhile; ?>
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">Description</label>
							<textarea name="description" id="" cols="30" rows="10" class="form-control summernote"><?php echo isset($description) ? $description : '' ?></textarea>
						</div>
					</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="reset">Clear</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$('#manage_ticket').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_ticket',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved.',"success");
					setTimeout(function(){
						location.replace('index.php?page=ticket_list')
					},750)
				}
			}
		})
	})
</script>