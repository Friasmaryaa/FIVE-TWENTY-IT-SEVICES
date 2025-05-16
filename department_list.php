<?php include'db_connect.php' ?>
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
  
  /* Pagination button styling */
  .pagination .page-item.active .page-link,
  .pagination .active,
  .page-item.active .page-link {
    background-color: #9ACD32 !important;
    border-color: #9ACD32 !important;
    color: #000 !important;
  }

  /* Standard pagination link styling */
  .pagination .page-link,
  .pagination a {
    color: #333 !important;
  }

  /* Hover effect for pagination links */
  .pagination .page-link:hover,
  .pagination a:hover {
    background-color: #f9fcf5;
    border-color: #9ACD32;
  }

  /* Specifically target the numerical active page indicator */
  .pagination li.active a,
  .pagination .active a,
  .pagination .active span {
    background-color: #9ACD32 !important;
    border-color: #9ACD32 !important;
    color: #000 !important;
  }
  
  /* For DataTables pagination specifically */
  .dataTables_wrapper .dataTables_paginate .paginate_button.current,
  .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #9ACD32 !important;
    border-color: #9ACD32 !important;
    color: #000 !important;
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
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<button class="btn btn-sm btn-primary btn-block" type='button' id="new_department"><i class="fa fa-plus"></i> New Department</button>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Description</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * FROM departments order by  name asc");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo ucwords($row['name']) ?></b></td>
						<td><b><?php echo $row['description'] ?></b></td>
						<td class="text-center ">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item edit_department" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_department" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
		                    </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	$('#new_department').click(function(){
		uni_modal("New Department","manage_department.php")
	})
	$('.edit_department').click(function(){
		uni_modal("Edit Department","manage_department.php?id="+$(this).attr('data-id'))
	})
	$('.delete_department').click(function(){
	_conf("Are you sure to delete this department?","delete_department",[$(this).attr('data-id')])
	})
	
	})
	function delete_department($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_department',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>