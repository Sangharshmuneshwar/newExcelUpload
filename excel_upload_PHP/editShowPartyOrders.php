<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Start session
session_start();

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "excel_upload";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if(isset($_POST['item_id']) && isset($_POST['field']) && isset($_POST['new_value'])) {
   
    //get the values
    $item_id = $_POST['item_id'];
    $field = $_POST['field'];
    $new_value = $_POST['new_value'];

    // Validate and sanitize input to prevent SQL injection
    $field = $conn->real_escape_string($field);
    $new_value = $conn->real_escape_string($new_value);


    //first get the data
    $sel = "SELECT * from table_item where Item_ID = ? ";
    $statement = $conn->prepare($sel);
    $statement->bind_param("i", $item_id);
    $statement->execute();
    $rslt = $statement->get_result();
    $singleRow= $rslt->fetch_assoc();

    if ($singleRow) {
        $qty = $singleRow['Qty'];
        $rate = $singleRow['Rate'];
        $amount = $singleRow['Amount'];

        if($field == "Qty"){
            $amount = $new_value * $rate;
        }else if($field == "Rate"){
            $amount = $new_value * $qty;
        }
        
    }





    // Construct the update  query
    $sql = "UPDATE table_item SET $field = ?, Amount = ? WHERE Item_ID = ?";

    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bind_param("sdi", $new_value, $amount, $item_id);

    if ($stmt->execute()) {
        // Update successful
        echo "Data updated successfully.";
    } else {
        // Update failed
        echo "Error updating data: " . $conn->error;
    }

    $stmt->close();
} 

if(isset($_POST['party_id'])) {
    // Connect to your database
    
   
    $party_id = $_POST['party_id'];
    $sql = "SELECT ti.*, tp.Name,tp.Address,tp.Phone,tp.GST_NO
    FROM table_item ti
    JOIN tbl_Party tp ON ti.party_id = tp.id
    WHERE ti.party_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $party_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $item_data = array();
    $party_data = array(); 

    while ($row = $result->fetch_assoc()) {
        $item_data[] = $row;

        if (empty($party_data)) {
            $party_data = array(
                'party_id' => $party_id,
                'Name' => $row['Name'],
                'Address' => $row['Address'],
                'Phone' => $row['Phone'],
                'GST_NO' => $row['GST_NO']
            );
        }
    }

    // $_SESSION['item_data'] = serialize($item_data);
    // $_SESSION['party_data'] = $party_data;

    $stmt->close();

    $response_data = array(
        'item_data' => $item_data,
        'party_data' => $party_data
    );
    
    // Encode the response data as JSON
    $json_response = json_encode($response_data);
    
    // Set Content-Type header to application/json
    header('Content-Type: application/json');
    
    echo $json_response;
}
?>


 
