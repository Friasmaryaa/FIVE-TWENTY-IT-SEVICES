<?php include'db_connect.php' ?>
<?php
session_start(); // ✅ REQUIRED to access $_SESSION variables
include 'db_connect.php';
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
	<div class="card card-outline card-info">
		<div class="card-body">
			<table class="table table-hover table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="15%">
					<col width="25%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Date Created</th>
						<th>Ticket</th>
						<th>Subject</th>
						<th>Description</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = '';
					if($_SESSION['login_type'] == 2)
						$where .= " where t.department_id = {$_SESSION['login_department_id']} ";
					if($_SESSION['login_type'] == 3)
						$where .= " where t.customer_id = {$_SESSION['login_id']} ";
					$qry = $conn->query("SELECT t.*,concat(c.lastname,', ',c.firstname,' ',c.middlename) as cname FROM tickets t inner join customers c on c.id= t.customer_id $where order by unix_timestamp(t.date_created) desc");
					while($row= $qry->fetch_assoc()):
						$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$desc = strtr(html_entity_decode($row['description']),$trans);
						$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo date("M d, Y",strtotime($row['date_created'])) ?></b></td>
						<td><b><?php echo ucwords($row['cname']) ?></b></td>
						<td><b><?php echo $row['subject'] ?></b></td>
						<td><b class="truncate"><?php echo strip_tags($desc) ?></b></td>
						<td>
							<?php if($row['status'] == 0): ?>
								<span class="badge badge-primary">Pending/Open</span>
							<?php elseif($row['status'] == 1): ?>
								<span class="badge badge-Info">Processing</span>
							<?php elseif($row['status'] == 2): ?>
								<span class="badge badge-success">Done</span>
							<?php else: ?>
								<span class="badge badge-secondary">Closed</span>
							<?php endif; ?>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item view_ticket" href="./index.php?page=view_ticket&id=<?php echo $row['id'] ?>" data-id="<?php echo $row['id'] ?>">View</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item" href="./index.php?page=edit_ticket&id=<?php echo $row['id'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_ticket" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
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
	$('.delete_ticket').click(function(){
	_conf("Are you sure to delete this ticket?","delete_ticket",[$(this).attr('data-id')])
	})
	})
	function delete_ticket($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_ticket',
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