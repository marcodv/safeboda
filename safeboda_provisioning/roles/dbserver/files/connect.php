<?php
$servername = "10.0.22.63";
$username = "marco";
$password = "marco";
$db = 'safeboda';
$table = 'safebodatable';

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully\n";

$sql = "INSERT INTO $table (LastName, FirstName) VALUES ('John', 'Doe')";
echo mysql_error();
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>\n" . $conn->error;
    $conn->error;
}

$conn->close(); 
?>
