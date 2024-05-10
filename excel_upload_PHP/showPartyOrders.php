<html>  
<head>  
    <title>Excel Upload</title>  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <!-- Datatables -->

</head>  
<body>
    <div style="width: 100%; display : flex; justify-content :center;"><h3>Order Details</h3></div>
    <div id="partyDataContainer">


    </div>
    
    <div id="orderDetailsContainer">

    </div>

    

    <script>
    function loadEditShowPartyOrders(party_id){
    console.log(party_id);
    $.ajax({
        method: 'POST',
        url: 'editShowPartyOrders.php',
        data: {
            party_id : party_id,
           
        },
        success: function(response) {
            console.log(response);
            console.log("table loader");
            addTable(response);
        // window.location.href = 'editPartyOrders.php';
        },
        error: function(xhr, status, error) {
            // Handle error
            console.log("in error");
            console.error(error);
        }
    });
}
function addTable(response){
    var partyDataHtml = '<div style="margin-left: 112px; margin-top: 30px">' +
    '<div><span style="font-weight: bold;">Party Name :</span> ' + response.party_data.Name + '</div>' +
    '<br/>' +
    '<div><span style="font-weight: bold;">Address :</span> ' + response.party_data.Address + '</div>' +
    '<br/>' +
    '<div><span style="font-weight: bold;">Phone :</span> ' + response.party_data.Phone + '</div>' +
    '<br/>' +
    '<div><span style="font-weight: bold;">GST NO :</span> ' + response.party_data.GST_NO + '</div>' +
    '</div>';

$('#partyDataContainer').html(partyDataHtml);

// Display order details
var orderDetailsHtml = '<div style="margin-top: 50px">' +
    '<div style="width: 100%; display: flex; justify-content: center; align-items: center; flex-direction: column">' +
    // '<h4 style="margin-top: 30px">Order Details</h4>' +
    '<div class="col-md-10 table-responsive">' +
    '<table class="table table-striped" id="dataTable">' +
    '<tr>' +
    '<th>Description</th>' +
    '<th>Quantity</th>' +
    '<th>Rate</th>' +
    '<th>Amount</th>' +
    '</tr>';

var totalAmount = 0;
$.each(response.item_data, function(index, row) {
    totalAmount += parseFloat(row.Amount);
    orderDetailsHtml += '<tr>' +
        '<td id="Description_' + row.Item_ID + '_' + response.party_data.party_id + '">' + row.Description + '</td>' +
        '<td id="Qty_' + row.Item_ID + '_' + response.party_data.party_id + '">' + row.Qty + '</td>' +
        '<td id="Rate_' + row.Item_ID + '_' + response.party_data.party_id + '">' + row.Rate + '</td>' +
        '<td id="Amount_' + row.Item_ID + '_' + response.party_data.party_id + '">' + row.Amount + '</td>' +
        '</tr>';
});

orderDetailsHtml += '<tr>' +
    '<td colspan="3" align="right"><strong>Total Amount:</strong></td>' +
    '<td>' + totalAmount + '</td>' +
    '</tr>' +
    '</table>' +
    '</div>' +
    '</div>' +
    '</div>';

$('#orderDetailsContainer').html(orderDetailsHtml);
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}


$(document).ready(function (event) {
    // Function to get URL parameter by name
    var id = getParameterByName('id');
    loadEditShowPartyOrders(id);
    });
    </script>
</body>
</html>
