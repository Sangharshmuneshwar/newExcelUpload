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

    // var editableCells = $('td[contenteditable=true]');
    // console.log('Number of editable cells:', editableCells.length);

    $(document).on('blur', 'td[contenteditable=true]', function() {
        // Event handler code here
        console.log('Blur event triggered!');
        var cell = $(this);
        var cell_id = cell.attr('id');
        var id_parts = cell_id.split('_');
        var field = id_parts[0];
        var item_id = id_parts[1];
        var party_id = id_parts[2];
        var new_value = cell.text();
    
        console.log("comming here");
    
        $.ajax({
            method: 'POST',
            url: 'editShowPartyOrders.php',
            data: {
                item_id: item_id,
                field: field,
                new_value: new_value,
               
            },
            success: function(response) {
                // Handle success
                console.log("edited successfully");
                loadEditShowPartyOrders(party_id);
                // window.location.href = 'editShowPartyOrders.php';
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(error);
            }
        });
    });
    
    });
   




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
        '<td contenteditable="true" id="Description_' + row.Item_ID + '_' + response.party_data.party_id + '">' + row.Description + '</td>' +
        '<td contenteditable="true" id="Qty_' + row.Item_ID + '_' + response.party_data.party_id + '">' + row.Qty + '</td>' +
        '<td contenteditable="true" id="Rate_' + row.Item_ID + '_' + response.party_data.party_id + '">' + row.Rate + '</td>' +
        '<td contenteditable="true" id="Amount_' + row.Item_ID + '_' + response.party_data.party_id + '">' + row.Amount + '</td>' +
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

