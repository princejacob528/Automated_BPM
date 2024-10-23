$(document).ready(function() {

    $('#vendorTable').DataTable({
        "searching": true,        
        "bPaginate": false,
        "lengthChange": false,
        "bInfo": false,
        "bAutoWidth": false        
    });

    $('#ctodoTab').DataTable({
        order: [[5, "asc"]]
    });

    $('#cctodoTab').DataTable({
        order: [[5, "desc"]]
    });

    $('#dctodoTab').DataTable({
        order: [[5, "desc"]]
    });

    $('#btodoTab').DataTable({
        order: [[4, "asc"]]
    });

    $('#cbtodoTab').DataTable({
        order: [[4, "desc"]]
    });
    
    $('#dbtodoTab').DataTable({
        order: [[4, "desc"]]
    });

    $('#etodoTab').DataTable({
        order: [[3, "asc"]]
    });

    $('#CpendingTab').DataTable({
        "searching": false,        
        "bPaginate": true,
        "lengthChange": false,
        "bInfo": true,
        "bAutoWidth": false,
        order: [[0, "asc"]]
    });

    $('#rpendingTab').DataTable({
        "searching": false,        
        "bPaginate": true,
        "lengthChange": false,
        "bInfo": true,
        "bAutoWidth": false,
        order: [[0, "asc"]]
    });
    
    $('#IpendingTab').DataTable({
        "searching": false,        
        "bPaginate": true,
        "lengthChange": false,
        "bInfo": true,
        "bAutoWidth": false,
        order: [[0, "asc"]]
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
});
