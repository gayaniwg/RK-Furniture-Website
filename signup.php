<?php
    require('connect.php');
    // When form submitted, insert values into the database.

    // Start the session to use session variables
session_start();

// Initialize error message
$error_message = '';

    if (isset($_REQUEST['email'])) {
        // removes backslashes
        $fname = stripslashes($_REQUEST['fname']);
        //escapes special characters in a string
        $fname = mysqli_real_escape_string($conn, $fname);

        $lname = stripslashes($_REQUEST['lname']);

        //escapes special characters in a string
        $lname = mysqli_real_escape_string($conn, $lname);

        $contact_no = stripslashes($_REQUEST['contact_no']);
        //escapes special characters in a string
        $contact_no= mysqli_real_escape_string($conn, $contact_no);

        $address = stripslashes($_REQUEST['address']);
        //escapes special characters in a string
        $address= mysqli_real_escape_string($conn, $address);

        $email    = stripslashes($_REQUEST['email']);
        $email    = mysqli_real_escape_string($conn, $email);

        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($conn, $password);

        $create_datetime = date("Y-m-d H:i:s");

         // Check if any field is empty
    if (empty($fname) || empty($lname) || empty($contact_no) || empty($address) || empty($email) || empty($password)) {
      $error_message = "Error: Please fill in all fields.";
  } else {
      // Hash the password for secure storage
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Check if the email already exists in the database
      $query_check_email = "SELECT * FROM users WHERE email = '$email'";
      $result_check_email = mysqli_query($conn, $query_check_email);
      if (mysqli_num_rows($result_check_email) > 0) {
        $error_message = "Error: This email is already registered.";
    } else {
        // Insert the user data into the database
        $query = "INSERT INTO users (fname, lname, contact_no, Address, email, password, create_datetime) 
                  VALUES ('$fname', '$lname', '$contact_no', '$address', '$email', '$password', '$create_datetime')";
        $result = mysqli_query($conn, $query);
        if ($result) {
            echo "Registration successful. You can now log in.";
            // Redirect to the login page after successful registration
            header("Location: index.php");
            exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
}
}

// Display the error message if present
if (!empty($error_message)) {
$_SESSION['error_message'] = $error_message;
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

 <!-- Display the error message if it is not empty -->
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

          <div class="title text-center pt-6 pb-23">
    <h2 class="position-relative d-inline-block ms-4">Sign up</h2>
  </div>
            <div class="new" style="margin-top: 30px;">
    <!-- This div adds a gap of 20 pixels between the rows -->
  </div>
            <form action="signup.php" method="post">
  <!-- 2 column grid layout with text inputs for the first and last names -->
  <div class="row mb-4">
    <div class="col">
      <div class="form-outline">
      <input type="text" name="fname" class="form-control" id="fname" placeholder="First name"/>
      </div>
    </div>
    <div class="col">
      <div class="form-outline">
        <input type="text" name="lname" class="form-control" id="lname" placeholder="Last name"/>
        
      </div>
    </div>
  </div>


  <div class="form-outline mb-4">
  <input type="contact_no" name="contact_no" class="form-control" id="contact_no" placeholder="Contact No"/>

  </div>

  <div class="form-outline mb-4">
  <input type="address" name="address" class="form-control" id="address" placeholder="Address"/>

  </div>

  <!-- Email input -->
  <div class="form-outline mb-4">
  <input type="email" name="email" class="form-control" id="email" placeholder="Email address"/>

  </div>

  <!-- Password input -->
  <div class="form-outline mb-4">
  <input type="password" name="password"  class="form-control" id="password" placeholder="Password"/>
  
  </div>

  <div class="new" style="margin-top: 30px;">
    <!-- This div adds a gap of 20 pixels between the rows -->
  </div>

  <!-- Submit button -->
  <button type="submit" name="submit" value="signup" class="btn btn-s btn-tertiary">Sign up</button>

  <div class="new" style="margin-top: 30px;">
    <!-- This div adds a gap of 20 pixels between the rows -->
  </div>

  <!-- Register buttons -->
  <div class="text-center">
    <p>or sign up with:</p>
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

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
    // JavaScript to close the error message after a few seconds (optional)
    setTimeout(function() {
        document.querySelector('.error-message-overlay').classList.add('hidden');
    }, 3000); // 3000 milliseconds (3 seconds) - Adjust this time as needed

    // JavaScript to close the error message when the close button is clicked
    function closeErrorMessage() {
        document.querySelector('.error-message-overlay').classList.add('hidden');
    }
</script>
    
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    

  </body>
</html>
