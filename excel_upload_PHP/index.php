<?php
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED);

// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "excel_upload";
$conn = new mysqli($servername, $username, $password, $dbname);

require('library/php-excel-reader/excel_reader2.php');
require('library/SpreadsheetReader.php');

if(isset($_POST['Submit'])) {
    $mimes = array('application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.oasis.opendocument.spreadsheet','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    if(in_array($_FILES["file"]["type"], $mimes)) {
        $uploadFilePath = 'uploads/' . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);
        $Reader = new SpreadsheetReader($uploadFilePath);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare item insert query
        $itemSql = "INSERT INTO table_item (Party_ID, Description, Qty, Rate, Amount) VALUES (?, ?, ?, ?, ?)";
        $itemStmt = $conn->prepare($itemSql);

        // Extract party details
        $partyDetails = array();
        $cnt = 0;
        foreach ($Reader as $row) {
            if ($cnt < 4) {
                $partyDetails[] = $row[1]; // Assuming party details are in the second column
            } else {
                break;
            }
            $cnt++;
        }

        // Insert party details into tbl_Party if not already existing
        $checkPartySql = "SELECT id FROM tbl_Party WHERE Phone = ?";
        $checkPartyStmt = $conn->prepare($checkPartySql);
        $checkPartyStmt->bind_param("s", $partyDetails[2]); 
        $checkPartyStmt->execute();
        $checkPartyResult = $checkPartyStmt->get_result();

        if ($checkPartyResult->num_rows == 0) {
            $partySql = "INSERT INTO tbl_Party (Name, Address, Phone, GST_NO) VALUES (?, ?, ?, ?)";
            $partyStmt = $conn->prepare($partySql);
            $partyStmt->bind_param("ssss", $partyDetails[0], $partyDetails[1], $partyDetails[2], $partyDetails[3]);
            $partyStmt->execute();
            $partyId = $conn->insert_id;
            $partyStmt->close();
        } else {
            $existingParty = $checkPartyResult->fetch_assoc();
            $partyId = $existingParty['id'];
        }

        // Insert data into table_item
        $cnt = 0;
        foreach ($Reader as $row) {
            if ($cnt >= 5) {
                $amt = $row[2] * $row[1];
                $itemStmt->bind_param("isidd", $partyId, $row[0], $row[1], $row[2], $amt);
                $itemStmt->execute();
            }
            $cnt++;
        }

        // Close statements
        $checkPartyStmt->close();
        $itemStmt->close();

        echo ("<script LANGUAGE='JavaScript'>
            window.alert('Data Successfully Uploaded to Database');
        </script>");

        // Delete uploaded file
        if (unlink($uploadFilePath)) {
            // echo ("<script LANGUAGE='JavaScript'>
            // window.alert('Succesfully Uploaded');
            // </script>");
        }
    } else {
        die("<br/>Sorry, File type is not allowed. Only Excel file.");
    }
}
?>


<html>  
<head>  
    <title>Excel Upload</title>  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script src="https://use.fontawesome.com/1016ea7b4c.js"></script>
    <!-- Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />  
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>  
</head>  
<body>
<div class="container-fluid">
    <h1>Upload Excel Sheet</h1>
    <div class="col-md-4 offset-md-4"></div>
    <div class="col-md-4 offset-md-4">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label>Upload Order File</label>
                <input type="file" name="file" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />  
            </div>
            <div class="form-group">
                <button type="submit" name="Submit" class="btn btn-success">Upload</button>
            </div>
        </form>

       
            <button type="submit" name="Submit" class="btn btn-success"><a href="seeAllParty.php">See Party</a></button>
      
    </div>
</div>
</body>
</html>
