<?php
if(!isset($conn)){
	include 'db_connect.php' ;
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
			<form action="" id="manage_staff">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<b class="text-muted">Personal Information</b>
						<div class="form-group">
							<label for="" class="control-label">First Name</label>
							<input type="text" name="firstname" class="form-control form-control-sm" required value="<?php echo isset($firstname) ? $firstname : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Middle Name</label>
							<input type="text" name="middlename" class="form-control form-control-sm"  value="<?php echo isset($middlename) ? $middlename : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Last Name</label>
							<input type="text" name="lastname" class="form-control form-control-sm" required value="<?php echo isset($lastname) ? $lastname : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Contact No.</label>
							<input type="text" name="contact" class="form-control form-control-sm" required value="<?php echo isset($contact) ? $contact : '' ?>">
						</div>
						<div class="form-group">
							<label class="control-label">Address</label>
							<textarea name="address" id="" cols="30" rows="4" class="form-control" required><?php echo isset($address) ? $address : '' ?></textarea>
						</div>
					</div>
					<div class="col-md-6">
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
						<b class="text-muted">System Credentials</b>
						<div class="form-group">
							<label class="control-label">Email</label>
							<input type="email" class="form-control form-control-sm" name="email" required value="<?php echo isset($email) ? $email : '' ?>">
							<small id="#msg"></small>
						</div>
						<div class="form-group">
							<label class="control-label">Password</label>
							<input type="password" class="form-control form-control-sm" name="password" <?php echo isset($id) ? "":'required' ?>>
							<small><i><?php echo isset($id) ? "Leave this blank if you dont want to change you password":'' ?></i></small>
						</div>
						<div class="form-group">
							<label class="label control-label">Confirm Password</label>
							<input type="password" class="form-control form-control-sm" name="cpass" <?php echo isset($id) ? 'required' : '' ?>>
							<small id="pass_match" data-status=''></small>
						</div>
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
	$('[name="password"],[name="cpass"]').keyup(function(){
		var pass = $('[name="password"]').val()
		var cpass = $('[name="cpass"]').val()
		if(cpass == '' ||pass == ''){
			$('#pass_match').attr('data-status','')
		}else{
			if(cpass == pass){
				$('#pass_match').attr('data-status','1').html('<i class="text-success">Password Matched.</i>')
			}else{
				$('#pass_match').attr('data-status','2').html('<i class="text-danger">Password does not match.</i>')
			}
		}
	})
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$('#manage_staff').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		if($('#pass_match').attr('data-status') != 1){
			if($("[name='password']").val() !=''){
				$('[name="password"],[name="cpass"]').addClass("border-danger")
				end_load()
				return false;
			}
		}
		$.ajax({
			url:'ajax.php?action=save_staff',
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
						location.replace('index.php?page=staff_list')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Email already exist.</div>");
					$('[name="email"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>