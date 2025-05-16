<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Login | Customer Support System</title>

  <!-- Google Fonts and Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <?php include('./header.php'); ?>
  <?php 
  if(isset($_SESSION['login_id']))
    header("location:index.php?page=home");
  ?>
</head>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Roboto', sans-serif;
    background: #000;
    color: #fff;
    height: 100vh;
    overflow: hidden;
    position: relative;
  }

  body::before {
    content: "";
    background: url('assets/dist/img/five_twenty.jpg') no-repeat center center fixed;
    background-size: cover;
    filter: blur(6px);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -2;
  }

  body::after {
    content: "";
    background: rgba(0, 0, 0, 0.6);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
  }

  main#main {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .card {
    background: transparent;
    border-radius: 12px;
    max-width: 380px;
    width: 100%;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(80, 72, 72, 0.6);
    animation: fadeIn 1s ease-in-out;
  }

  .card-body {
    padding: 20px;
  }

  h4.text-dark {
    font-size: 30px;
    font-weight: 700;
    text-align: center;
    margin-bottom: 30px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-control, .custom-select {
    border-radius: 6px;
    border: 1px solid #00bcd4;
    background-color:rgb(140, 150, 140);
    color: #fff;
    padding: 12px;
    font-size: 16px;
    transition: all 0.3s ease-in-out;
    padding-left: 35px;
    padding-right: 35px;
  }

  .form-control:focus, .custom-select:focus {
    border-color:rgb(163, 238, 93);
    background-color:rgb(172, 175, 172);
    box-shadow: 0 0 5px rgba(11, 12, 11, 0.5);
  }

  .btn-primary {
    background-color: rgb(64, 168, 90);
    border: none;
    color: #fff;
    padding: 12px;
    width: 100%;
    border-radius: 6px;
    font-size: 16px;
    transition: background-color 0.3s ease;
  }

  .btn-primary:hover {
    background-color:rgb(0, 122, 37);
  }

  .alert-danger {
    background-color: #ff5722;
    color: white;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 15px;
  }

  .text-dark {
    color: #fff !important;
  }

  .form-group label {
    font-size: 14px;
    margin-bottom: 5px;
  }

  .form-control::placeholder {
    color: #aaa;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .input-icon {
    position: relative;
  }

  .input-icon i.fas {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #4caf50;
    font-size: 16px;
  }

  .input-icon i.fa-user {
    left: 12px;
  }

  .input-icon i.fa-lock {
    left: 12px;
  }

  .input-icon i.fa-user-tag {
    left: 12px;
  }

  .toggle-password {
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);
    cursor: pointer;
    color: #aaa;
    font-size: 16px;
    z-index: 2;
  }
</style>

<body>
  <main id="main">
    <div class="card">
      <h4 class="text-dark">Customer Support System</h4>
      <div id="login-center" class="row justify-content-center">
        <div class="card-body">
          <form id="login-form">
            <div class="form-group">
              <label for="username" class="control-label text-dark">Username</label>
              <div class="input-icon">
                <i class="fas fa-user"></i>
                <input type="text" id="username" name="username" class="form-control form-control-sm" placeholder="Enter your username" required>
              </div>
            </div>
            <div class="form-group">
              <label for="password" class="control-label text-dark">Password</label>
              <div class="input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" class="form-control form-control-sm" placeholder="Enter your password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword()" id="togglePass" title="Show/Hide Password"></i>
              </div>
            </div>
            <div class="form-group">
              <label for="type" class="control-label text-dark">Login As</label>
              <div class="input-icon">
                <i class="fas fa-user-tag"></i>
                <select class="custom-select custom-select-sm" name="type" required>
                  <option value="3">Customer</option>
                  <option value="2">Staff</option>
                  <option value="1">Admin</option>
                </select>
              </div>
            </div>
            <center>
              <button type="submit" class="btn btn-primary">Login</button>
            </center>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $('#login-form').submit(function(e){
      e.preventDefault();
      $('#login-form button').attr('disabled', true).html('Logging in...');
      if($(this).find('.alert-danger').length > 0)
        $(this).find('.alert-danger').remove();

      $.ajax({
        url: 'ajax.php?action=login',
        method: 'POST',
        data: $(this).serialize(),
        error: err => {
          console.log(err);
          $('#login-form button').removeAttr('disabled').html('Login');
        },
        success: function(resp){
          if(resp == 1){
            location.href = 'index.php?page=home';
          } else {
            $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
            $('#login-form button').removeAttr('disabled').html('Login');
          }
        }
      });
    });

    function togglePassword() {
      var passInput = document.getElementById("password");
      var toggleIcon = document.getElementById("togglePass");

      if (passInput.type === "password") {
        passInput.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
      } else {
        passInput.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
      }
    }
  </script>
</body>
</html>
