<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-primary navbar-dark ">
<link rel="stylesheet" href="assets/css/custom.css">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li>
        <a class="nav-link text-white"  href="./" role="button"> <large><b>Customer Support System</b></large></a>
      </li>
    </ul>
    
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      
      <!-- User Profile Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-toggle="dropdown">
        <i class="fas fa-user-circle"></i> <span class="ml-1">
          <?php 
            // Get user role from session
            if(isset($_SESSION['login_type'])) {
              $user_type = $_SESSION['login_type'];
              // Convert numerical type to text (adjust based on your system's user types)
              switch($user_type) {
                case 1:
                  echo "ADMIN";
                  break;
                case 2:
                  echo "STAFF";
                  break;
                case 3:
                  echo "CUSTOMER";
                  break;
                default:
                  echo "USER";
              }
            } else {
              echo "GUEST";
            }
          ?>
        </span>
      </a>
      
      <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="manage_user.php?id=<?php echo $_SESSION['login_id'] ?>">Manage Account</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-danger" href="ajax.php?action=logout">Logout</a>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->