$(document).ready(function() {

    $('#vendorTable').DataTable({
        "searching": false,
        "bPaginate": false,
        "lengthChange": false,
        "bInfo": false,
        "bAutoWidth": false
    });

    $('#creditTable').DataTable( {        
        dom: 'Bfrtip',        
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                buttons: [                    
                    'excel',                    
                    'pdf',
                    'print'
                ]
            }
        ]
    });

    $('#selectTable').DataTable( {        
        dom: 'Bfrtip',        
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                buttons: [                    
                    'excel',                    
                    'pdf',
                    'print'
                ]
            }
        ]
    });

    $('#purchaseTable').DataTable( { 
        order: [[0, "desc"]],       
        dom: 'Bfrtip',        
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                buttons: [                    
                    'excel',                    
                    'pdf',
                    'print'
                ]
            }
        ]
    });
});
