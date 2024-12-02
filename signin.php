
<?php
require('connect.php');

// Start the session to use session variables
session_start();

// Initialize error message
$error_message = '';

// When login form is submitted, validate the credentials.
if (isset($_POST['signin'])) {
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  if (empty($email) || empty($password)) {
      $error_message = "Error: Please fill in both email and password.";
  } else {
      $query = "SELECT * FROM users WHERE email = '$email'";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
          $user_data = mysqli_fetch_assoc($result);
          if (password_verify($password, $user_data['password'])) {
              $_SESSION['user_id'] = $user_data['user_id'];
              $_SESSION['email'] = $user_data['email'];
              header("Location: index.php");
              exit();
          } else {
              $error_message = "Error: Incorrect email or password.";
          }
      } else {
          $error_message = "Error: Incorrect email or password.";
      }
  }

  if (!empty($error_message)) {
      $_SESSION['error_message'] = $error_message;
  }
}

// Check if the 'email' key exists in the $_SESSION['error_message'] array
if (!empty($error_message) && isset($_SESSION['error_message']['email'])) {
  // The 'email' key exists, so there is an error related to the email field
  // You can handle the error specific to the email field here
  // For example, you can set a class for the email input to indicate the error
  $email_error = true;
} else {
  // The 'email' key does not exist, so there is no error related to the email field
  $email_error = false;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    
  <!-- Required meta tags -->

  <link rel="stylesheet" href="main.css">

<meta charset="utf-8">
<meta name="viewport" content="width=device-width,  initial-scale=1.0">

<title>RK Furniture</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style>
   /* CSS for positioning the error message */
   .error-message-overlay {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            background-color: #fff; /* White background */
            color: #ff0000; /* Red text color */
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); /* Box shadow for a 3D effect */
            max-width: 300px; /* Limit the width of the error message box */
        }

        /* CSS to hide the error message when not needed */
        .hidden {
            display: none;
        }
  </style>


  </head>
  <body>
  <?php if (isset($_SESSION['error_message'])) : ?>
        <div class="error-message-overlay">
            <span onclick="closeErrorMessage()" style="cursor: pointer; float: right; padding: 5px;">&#10006;</span>
            <?php echo $_SESSION['error_message']; ?>
        </div>
        <?php unset($_SESSION['error_message']); // Clear the error message after displaying it ?>
    <?php endif; ?>

  <section class="vh-100" style="
        background: url('image/15.jpg') no-repeat center center / cover, #FFB3B3;
        height: 100%;
        ">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">
          <div class="new" style="margin-top: 20px;">
    <!-- This div adds a gap of 20 pixels between the rows -->
  </div>
       
          <div class="title text-center pt-6 pb-23">
    <h2 class="position-relative d-inline-block ms-4">Sign in</h2>
  </div>
            <div class="new" style="margin-top: 50px;">
    <!-- This div adds a gap of 20 pixels between the rows -->
  </div>
  <form action="signin.php" method="post">

  <!-- Email input -->
  <div class="form-outline mb-4">
  <input type="email" class="form-control" name="email" id="email" placeholder="email" />
  
  </div>

  <!-- Password input -->
  <div class="form-outline mb-4">
  <input type="Password" class="form-control" name="password" id="password" placeholder="Password"/>
  
  </div>

  <div class="new" style="margin-top: 50px;">
    <!-- This div adds a gap of 20 pixels between the rows -->
  </div>

  <!-- Submit button -->
  <button type="submit" value="signin" name="signin" class="btn btn-s btn-tertiary">Sign in</button>

  <div class="new" style="margin-top: 40px;">
    <!-- This div adds a gap of 20 pixels between the rows -->
  </div>


            <p>Don't have an account? <a href="signup.php" class="link-info">Register here</a></p>

   
  <!-- Register buttons -->
  <div class="text-center">
    <p>or sign in with:</p>

    <div class="new" style="margin-top: 40px;">
    <!-- This div adds a gap of 20 pixels between the rows -->
  </div>


    <button type="button" class="btn btn-secondary btn-floating mx-1">
      <i class="fab fa-facebook-f"></i>
    </button>

    <button type="button" class="btn btn-secondary btn-floating mx-1">
      <i class="fab fa-google"></i>
    </button>

    <button type="button" class="btn btn-secondary btn-floating mx-1">
      <i class="fab fa-twitter"></i>
    </button>

  </div>
</form>


<div class="new" style="margin-top: 20px;">
    <!-- This div adds a gap of 20 pixels between the rows -->
  </div>
           



          </div>
        </div>
      </div>
    </div>
  </div>
</section>
    <!-- JavaScript to close the error message when the close button is clicked -->
    <script>
        function closeErrorMessage() {
            document.querySelector('.error-message-overlay').classList.add('hidden');
        }
    </script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    

  </body>
</html>
