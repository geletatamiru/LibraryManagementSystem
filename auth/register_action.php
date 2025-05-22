<?php 
  include '../db.php';


  if($_SERVER['REQUEST_METHOD'] =='POST'){
    echo "hello";
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if( $password !== $confirmPassword ){
      echo 'Passwords do not match';
      exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt,"sss",$name, $email, $hashedPassword);

    if( mysqli_stmt_execute($stmt) ){
       header(("Location: ../views/login.php?registered=1"));
    }else {
      header("Location: ../views/register.php?error=registration_error");  
    }
  }
  mysqli_close($conn);
  exit();
?>