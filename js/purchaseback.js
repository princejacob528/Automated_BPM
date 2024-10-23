$(document).ready(function() {

    
    $('#selectTable').DataTable( {
        "searching": false,
        "bPaginate": false,
        "bInfo": false,       
        dom: 'Bfrtip',        
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                buttons: [{
                    extend: 'excel',
                    header: true
            
                }, {
                    extend: 'print',
                    header: true
                }]
            }
        ]
    });
   
});
