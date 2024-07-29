<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "volunteer_system";
$port = 3307;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected Succesfully";
// Query to fetch data
$sql = "SELECT  first_name FROM TBLADMIN";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Member ID</th>
                <th>Name</th>
                <th>Email Address</th>
            </tr>";
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["memberApplicationID"]. "</td>
                <td>" . $row["name"]. "</td>
                <td>" . $row["email_address"]. "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
?>
