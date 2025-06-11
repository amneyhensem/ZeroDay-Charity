<?php
$servername = "localhost"; $username = "root";
$password = "";
$database = "subscribe";

$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
}

$email = $_POST['email'];



 //INSERT DATA INTO THE DATABASE

 $sql = "INSERT INTO subrek (email) 
 VALUES ('$email')";

 

 if ($conn->query($sql) === TRUE) {
 echo "<script>alert('Checkout complete');</script>";
 echo "<script>window.setTimeout(function(){ window.location.href = 'index.html'; }, 1000);</script>";
} else {
echo "Error: ". $sql, "<br>".$conn->error;
}
?>