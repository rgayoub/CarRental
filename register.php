<?php
// Include database connection
include 'db_conn.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get registration data from form
  $firstName = $_POST['first_name'];
  $lastName = $_POST['last_name'];
  $email = $_POST['email'];
  $username = $_POST['username'];
  $phoneNumber = $_POST['phone_number'];
  $password = $_POST['password'];
  $userType = $_POST['user_type'];
  // Hash the password for security
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert data into users table
  $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
  $statement = $connection->prepare($query);
  $statement->bindParam(':username', $username);
  $statement->bindParam(':password', $hashedPassword);
  $statement->bindParam(':role', $userType);
  $statement->execute();

  // Get the ID of the inserted user
  $userId = $connection->lastInsertId();

  // Insert data into appropriate table based on user type
  if ($userType === 'car_owner') {
    $query = "INSERT INTO car_owners (user_id, email, first_name, last_name, phone_number) 
                  VALUES (:user_id, :email, :first_name, :last_name, :phone_number)";
  } else {
    $query = "INSERT INTO clients (user_id, email, first_name, last_name, phone_number) 
                  VALUES (:user_id, :email, :first_name, :last_name, :phone_number)";
  }

  // Prepare and execute the second query
  $statement = $connection->prepare($query);
  $statement->bindParam(':user_id', $userId);
  $statement->bindParam(':email', $email);
  $statement->bindParam(':first_name', $firstName);
  $statement->bindParam(':last_name', $lastName);
  $statement->bindParam(':phone_number', $phoneNumber);
  $statement->execute();

  // Redirect to login page after successful registration
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin 2 - Register</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.css" rel="stylesheet">
  <style>
    .bg-register-image {
      background-image: url(logos/logo-no-background.png);
      background-position: center;
      background-size: 400px;
      background-repeat: no-repeat;
    }

    .select-style {
      display: block;
      width: 100%;
      height: calc(1.5em + 0.75rem + 2px);
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      font-weight: 400;
      line-height: 1.5;
      color: #6e707e;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid #d1d3e2;
      border-radius: 0.35rem;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
  </style>

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
              </div>
              <form class="user" onsubmit="return validatePassword()" method="POST">
    <div class="form-group row">
        <div class="col-sm-6">
            <input type="text" class="form-control form-control-user" id="exampleFirstName" name="first_name"
                placeholder="First Name" required>
        </div>
        <div class="col-sm-6">
            <input type="text" class="form-control form-control-user" id="exampleLastName" name="last_name"
                placeholder="Last Name" required>
        </div>
    </div>
    <div class="form-group">
        <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email"
            placeholder="Email Address" required>
    </div>
    <div class="form-group">
        <input type="tel" name="phone_number" id="form3Example5" class="form-control form-control-user"
            placeholder="Enter phone number" pattern="[0-9]{10}" maxlength="10" required />
        <small id="form3Example5Help" class="form-text text-muted" style="font-size: 0.7rem; margin-left: 13px;">Format: 06-00-11-22-33</small>
    </div>
    <div class="form-group row">
        <div class="col-sm-6">
            <input type="text" class="form-control form-control-user" id="exampleFirstName" name="username"
                placeholder="Username" required>
        </div>
        <div class="col-sm-6">
            <select class="form-control-user form-control select-style " id="userType" name="user_type" required>
                <option value="" disabled selected hidden>Please select an option</option>
                <option value="Regular">Client</option>
                <option value="car_owner">Car owner</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-6">
            <input type="password" class="form-control form-control-user" id="exampleInputPassword" name="password"
                placeholder="Password" required>
        </div>
        <div class="col-sm-6">
            <input type="password" class="form-control form-control-user" id="exampleRepeatPassword"
                placeholder="Repeat Password" required>
            <small id="confirmPasswordError" class="text-danger"></small>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-user btn-block">Register Account</button>
</form>

              <hr>

              <div class="text-center">
                <a class="small" href="login.php">Already have an account? Login!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
  <script>
    // Function to validate password and confirm password fields
    function validatePassword() {
      var password = document.getElementById("exampleInputPassword").value;
      var confirmPassword = document.getElementById("exampleRepeatPassword").value;
      var confirmPasswordError = document.getElementById("confirmPasswordError");

      if (password !== confirmPassword) {
        confirmPasswordError.innerText = "Passwords do not match";
        return false;
      } else {
        confirmPasswordError.innerText = "";
        return true;
      }
    }
  </script>


  <!-- Styles -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
  <!-- Or for RTL support -->
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
</body>

</html>