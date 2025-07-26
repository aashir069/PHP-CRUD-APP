<?php

include 'connection.php';

$id = $_GET['updateid'] ?? 0;

if (!$id) {
    die("Invalid ID.");
}

// Fetch existing user data
$sql = "SELECT * FROM `usersdata`.`users` WHERE id = $id";
$result = mysqli_query($connect, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("User not found.");
}

$row = mysqli_fetch_assoc($result);

$username = $row['username'];
$email = $row['email'];
$password = $row['password'];

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = mysqli_real_escape_string($connect, $_POST['password']);

    $sql = "UPDATE `usersdata`.`users` 
            SET username='$username', email='$email', password='$password'
            WHERE id = $id";

    $result = mysqli_query($connect, $sql);

    if ($result) {
        echo "
            <script>
                alert('Form has been submitted');
                window.location.href = 'display.php';
            </script>
        ";
        exit;
    } else {
        die("Query failed: " . mysqli_error($connect));
    }

    mysqli_close($connect);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      background-color: #f8f9fa;
    }
    .profile-header {
      background: linear-gradient(to right, #0d6efd, #6610f2);
      color: white;
      border-radius: 0.5rem 0.5rem 0 0;
      padding: 2rem;
    }
    .form-control:focus {
      border-color: #6610f2;
      box-shadow: 0 0 0 0.25rem rgba(102, 16, 242, 0.25);
    }
    .alert {
      display: none;
    }
    .is-invalid {
      border-color: #dc3545;
    }
    .invalid-feedback {
      display: none;
      color: #dc3545;
    }
    .is-invalid + .invalid-feedback {
      display: block;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="card shadow">
    <div class="profile-header d-flex justify-content-between align-items-center">
      <h3 class="mb-0">Welcome, <span id="profileName"><?php echo htmlspecialchars($username); ?></span></h3>
      <button class="btn btn-outline-light">Logout</button>
    </div>

    <div class="card-body">
      <div class="alert alert-success" id="successMsg">
        ✅ Your profile was updated successfully!
      </div>

      <form id="profileForm" method="post" action="" onsubmit="return validateForm();">
        <div class="mb-3">
          <label for="username" class="form-label">Full Name</label>
          <input
            type="text"
            class="form-control"
            name="username"
            id="username"
            value="<?php echo htmlspecialchars($username); ?>"
          />
          <div class="invalid-feedback">This field is required.</div>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input
            type="email"
            class="form-control"
            name="email"
            id="email"
            value="<?php echo htmlspecialchars($email); ?>"
          />
          <div class="invalid-feedback" id="emailError">Please enter a valid email address.</div>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input
            type="password"
            class="form-control"
            name="password"
            id="password"
            value="<?php echo htmlspecialchars($password); ?>"
          />
          <div class="invalid-feedback">This field is required.</div>
        </div>

        <button type="submit" name="submit" class="btn btn-primary w-100">💾 Save Changes</button>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function validateForm() {
    const username = document.getElementById('username');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const successMsg = document.getElementById('successMsg');

    let valid = true;

    // Clear previous errors
    [username, email, password].forEach(input => {
      input.classList.remove('is-invalid');
    });

    if (username.value.trim() === '') {
      username.classList.add('is-invalid');
      valid = false;
    }

    const emailRegex = /^[^ ]+@[^ ]+\.[a-z]{2,}$/i;
    if (email.value.trim() === '' || !emailRegex.test(email.value.trim())) {
      email.classList.add('is-invalid');
      valid = false;
    }

    if (password.value.trim() === '') {
      password.classList.add('is-invalid');
      valid = false;
    }

    if (!valid) {
      successMsg.style.display = 'none';
      return false; // prevent form submission if invalid
    }

    return true; // allow form submission if valid
  }
</script>

</body>
</html>
