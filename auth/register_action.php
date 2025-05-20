<?php 
  include '../db.php';


  if($_SERVER['REQUEST_METHOD'] =='POST'){
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
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if( $stmt->execute() ){
       header(("Location: ../views/login.php?registered=1"));
       exit();
    }else {
      header("Location: ../views/register.php?error=registration_error");
    }
  }
 
?>