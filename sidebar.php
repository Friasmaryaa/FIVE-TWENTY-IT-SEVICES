<aside class="main-sidebar elevation-4" style="background: #000000; box-shadow: 4px 4px 10px rgba(85, 190, 107, 0.4);">
  <div class="dropdown"><br>
  <div class="user-panel mt-2 pb-3 mb-3 d-flex align-items-center">
  <div class="image">
    <img src="assets/dist/img/five_twenty.jpg" 
         class="img-circle elevation-2" 
         alt="Company Logo"
         style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #C0FF00;">
  </div>
  <div class="info">
  <span class="brand-text font-weight-light text-white d-block" style="font-size: 18px; white-space: normal;">
  <a><large><b>FIVE TWENTY IT SERVICES</b></large></a>
    </span>
  </div>
</div>

    </a>
    <div class="dropdown-menu">
      <a class="dropdown-item manage_account" href="javascript:void(0)" data-id="<?php echo $_SESSION['login_id'] ?>">Manage Account</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="ajax.php?action=logout">Logout</a>
    </div>
  </div>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item dropdown">
          <a href="./" class="nav-link nav-home text-white">
            <i class="nav-icon fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <?php if ($_SESSION['login_type'] == 1): ?>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_customer text-white">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Customer
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_customer" class="nav-link nav-new_customer tree-item text-white">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=customer_list" class="nav-link nav-customer_list tree-item text-white">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_staff text-white">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>
                Staff
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_staff" class="nav-link nav-new_staff tree-item text-white">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=staff_list" class="nav-link nav-staff_list tree-item text-white">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="./index.php?page=department_list" class="nav-link nav-department_list text-white">
              <i class="nav-icon fas fa-sitemap"></i>
              <p>Department</p>
            </a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a href="#" class="nav-link nav-edit_ticket nav-view_ticket text-white">
            <i class="nav-icon fas fa-ticket-alt"></i>
            <p>
              Ticket
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="./index.php?page=new_ticket" class="nav-link nav-new_ticket tree-item text-white">
                <i class="fas fa-plus nav-icon"></i>
                <p>Add New</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./index.php?page=ticket_list" class="nav-link nav-ticket_list tree-item text-white">
                <i class="fas fa-list nav-icon"></i>
                <p>List</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</aside>

<style>
  .nav-sidebar .nav-link {
    color: white !important;
    font-size: 20px; /* default is usually 14px */
    padding: 15px 20px; /* adjust spacing for touch-friendliness */
  }

  .nav-sidebar .nav-link:hover, .nav-sidebar .nav-link.active {
    background-color: #C0FF00 !important;
    color: black !important;
  }
  .nav-sidebar .nav-treeview .nav-link {
    padding-left: 30px;
  }
</style>

<script>
  $(document).ready(function(){
    var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    if($('.nav-link.nav-'+page).length > 0){
      $('.nav-link.nav-'+page).addClass('active');
      if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
        $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active');
        $('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open');
      }
    }

    // Manage Account Modal
    $('.manage_account').click(function(){
      uni_modal('Manage Account', 'manage_user.php?id='+$(this).attr('data-id'))
    });
  });

  
</script>