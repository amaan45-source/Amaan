<?php
session_start();
include 'db.php';

$error = ""; // Store error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];
  // if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['captcha']) || $_POST['captcha'] !== $_SESSION['captcha']) {
      echo "Incorrect CAPTCHA. Try again!";
      header("Location: login.php"); // Redirect back to login page
      exit();
    }
  
  //   // Continue with login verification (e.g., check email & password in database)
  //   echo "CAPTCHA Verified! Proceed with login.";
  // }
  // Query to get the user by email
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Compare the plain text password directly
        if ($password === $user['password']) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];

            // Redirect to the dashboard
            header("Location: foot.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Footcap</title>
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
    .login-container {
      width: 400px;
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
    .signup-link {
      font-size: 1rem;
      text-align: center;
      margin-top: 15px;
    }
    .signup-link a {
      color: #0073e6;
      text-decoration: none;
    }
    .signup-link a:hover {
      text-decoration: underline;
    }
    </style>
</head>

<body>


<!-- Alert at the Top -->
<?php if (!empty($error)): ?>
    <div class="alert alert-danger fixed-top text-center" role="alert">
        <?= $error; ?>
    </div>
<?php endif; ?>

  <div class="login-container">
    <h2>Login to Your Account</h2>
    <form action="login.php" method="post">
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" name="email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <div class="mb-3">
      <label class="form-label">Enter CAPTCHA:</label>
    <input type="text" class="form-control" name="captcha" required>
    <br>
    <img src="captcha.php" alt="CAPTCHA" width="200px" height="60px">
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p class="signup-link">Don't have an account? <a href="signup.php">Sign up</a></p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
