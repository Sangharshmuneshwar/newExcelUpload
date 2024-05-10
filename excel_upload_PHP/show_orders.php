
<?php
// Start session
session_start();

// Connect to your database
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "excel_upload";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['id'])){
    $item_id = $_POST['id'];
    $statement  = "DELETE from table_item where Party_ID = ?";
    $statement = $conn->prepare($statement);
    $statement->bind_param("i", $item_id);
    $statement->execute();
    $statement->close();

    $statement2  = "DELETE from tbl_Party where id = ?";
    $statement2 = $conn->prepare($statement2);
    $statement2->bind_param("i", $item_id);
    $statement2->execute();
    $statement2->close();
}

// Select data from tbl_Party

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT * FROM tbl_Party";
    $result = $conn->query($sql);

    // Initialize an array to store the fetched data
    $data = array();

    // Fetch data and store it in the array
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        echo json_encode(array('error' => 'No results'));
        exit; // Stop further execution
    }

    // Send the data as JSON response
    echo json_encode($data);

    // Close connection
    $conn->close();
}


?>

