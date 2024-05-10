<html>  
<head>  
    <title>Excel Upload</title>  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />    <!-- Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />  

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" integrity="sha512-u3fPA7V8qQmhBPNT5quvaXVa1mnnLSXUep5PS1qo5NRzHwG19aHmNJnj1Q8hpA/nBWZtZD4r4AX6YOt5ynLN2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>  
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

   

</head>  

<body>
<div style="display: flex; flex-direction: column; align-items: center;">
    <h3 style="margin-bottom: 50px;">All Parties</h3>
    <div id="container" style="width: 80%;">
        <!-- Your content here -->
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

function submitForm(form) {
    form.submit();
}
function addData(response){
    if (response.length > 0) {
        var tableHtml = '<div class="col-md-12 table-responsive">' +
            '<table class="table table-striped">' +
            '<tr>' +
            
            '<th>Name</th>' +
            '<th>Address</th>' +
            '<th>Phone</th>' +
            '<th>GST NO</th>' +
            '<th>Action</th>' +
            '</tr>';

        // Iterate through each row in the response and generate table rows
        $.each(response, function(index, row) {
            tableHtml += '<tr>' +
                
                '<td>' + row.Name + '</td>' +
                '<td>' + row.Address + '</td>' +
                '<td>' + row.Phone + '</td>' +
                '<td>' + row.GST_NO + '</td>' +
                '<td>' +
                '<div class="button-group">' +
                           
              
                '<a href="showPartyOrders.php?id=' + row.id + '" style="margin-left: 10px;"> <i class="fa-solid fa-eye"></i></a>'
                     +
                '<a href="editPartyOrders.php?id=' + row.id + '" style="margin-left: 10px;"><i class="fa-solid fa-pen-to-square"></i></a>' +
                '<a href = "#" onclick="deleteParty(' + row.id + ')" style="margin-left: 10px;"><i class="fa-solid fa-trash"></i></a>' +
                '</tr>';
        });
        
        tableHtml += '</table></div>';
       
        // Append the generated table HTML to a container element
        $('#container').html(tableHtml);
    } else {
        // Handle case when no data is available
        $('#container').html('<p>No data available</p>');
    }
}

function loadData(){
    $.ajax({
        method: 'GET',
        url: 'show_orders.php',
        success: function(response) {
            response = JSON.parse(response);
            addData(response);
        },
        error: function(xhr, status, error) {
            // Handle error
            console.log("in error");
            console.error(error);
        }
    });
}

$(document).ready(function (event) {
    
    loadData();
});

function deleteParty(id){
    $.ajax({
        method: 'POST',
        url: 'show_orders.php',
        data: {
            id : id,  
        },
        success: function(response) {
           loadData();
        // window.location.href = 'show_orders.php';
        },
        error: function(xhr, status, error) {
            // Handle error
            console.log("in error");
            console.error(error);
        }
    });
}

</script>

</body>
</html>