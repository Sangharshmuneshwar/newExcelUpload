<?php

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
    
    // Echo the JSON response
    echo $json_response;
}
?>