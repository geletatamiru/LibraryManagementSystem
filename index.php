<?php 
  include('db.php');

  $sql = "SELECT * FROM books";
  $result = mysqli_query($conn,$sql);

  if(mysqli_num_rows($result)> 0){
    while($row = mysqli_fetch_assoc($result)){
      echo "ID: " . $row["id"] . " - Title: " . $row["title"] . " - Author: " . $row["author"] . "<br>";
    }
  }
?>