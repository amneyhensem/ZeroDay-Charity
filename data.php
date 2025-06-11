<?php
// Retrieve POST data safely
$name = $_POST['name'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$amount = $_POST['amount'];
$date = $_POST['donation_date']; // Retrieve the date

// Database connection parameters
$servername = 'localhost';
$username = 'root';
$passwordDB = ''; // Assuming no password for local dev; change as needed
$dbname = 'donasi';

// Create connection
$conn = new mysqli($servername, $username, $passwordDB, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO bayaran (name, email, gender, amount, date) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    die('Prepare failed: ' . $conn->error);
}

$stmt->bind_param("sssds", $name, $email, $gender, $amount, $date);

// Execute statement
if ($stmt->execute()) {
    echo "Donation recorded successfully.";
} else {
    echo "Error executing query: " . $stmt->error;
}


// Close resources
$stmt->close();
$conn->close();
?>
<a href="index.html">Go back to donation form<
</a>
</html>
// Redirect to a thank you page or back to the form
