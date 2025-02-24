<?php
session_start();
include 'db.php';
$error=false;
$register=false;
$duplicate=false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Hash password
    $cpassword = $_POST['confirmPassword']; // Hash password

    if($password==$cpassword) {
    $query = "INSERT INTO users (name, mobile, email, password) VALUES ('$name', '$mobile', '$email', '$password')";
    
    if (mysqli_query($conn, $query)) {
        $register= "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        $duplicate= "Error: " . mysqli_error($conn);
    }
  }
  else{
    $error= "Password Does not match...";
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Footcap</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- <link rel="stylesheet" href="./assets/css/style.css"> -->
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      background-color: #f3f3f3;
      font-family: "Arial", sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .signup-container {
      width: 500px;
      background: white;
      padding: 30px;
      border-radius: 8px;
      border: 1px solid #ddd;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .btn-primary {
      background-color: #ff9900;
      border: none;
      font-size: 1.1rem;
      padding: 12px;
      width: 100%;
      border-radius: 5px;
      font-weight: bold;
    }
    .btn-primary:hover {
      background-color: #e68a00;
    }
    h2 {
      font-size: 1.8rem;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
      color: #ff9900;
    }
    label {
      font-size: 1rem;
      font-weight: bold;
      color: #333;
    }
    input {
      font-size: 1rem;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      width: 100%;
    }
    .login-link {
      font-size: 1rem;
      text-align: center;
      margin-top: 15px;
    }
    .login-link a {
      color: #0073e6;
      text-decoration: none;
    }
    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <?php
  if($error){
    echo '<div class="alert alert-danger fixed-top text-left" role="alert">
         '.$error.'
    </div>';
  }
  
  ?>
  <?php
  if($register){
    echo '<div class="alert alert-success fixed-top text-left" role="alert">
         '.$register.'
    </div>';
  }
  
  ?>
  <?php
  if($duplicate){
    echo '<div class="alert alert-danger fixed-top text-left" role="alert">
         '.$duplicate.'
    </div>';
  }
  
  ?>
  <div class="signup-container">
    <h2>Create Your Account</h2>
    <form action="signup.php" method="post">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" class="form-control" name="name" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="mobile" class="form-label">Mobile Number</label>
          <input type="tel" class="form-control" name="mobile" required>
        </div>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" name="email" required>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="confirmPassword" class="form-label">Confirm Password</label>
          <input type="password" class="form-control" name="confirmPassword" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Sign Up</button>
    </form>
    <p class="login-link">Already have an account? <a href="login.php">login</a></p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
