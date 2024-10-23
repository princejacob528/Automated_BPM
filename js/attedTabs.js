/*=========================================================================================
    File Name: datatables-basic.js
    Description: Basic Datatable
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(document).ready(function() {
    

    /**************************************************************
    * js of Tab for COLUMN SELECTORS WITH EXPORT AND PRINT OPTIONS *
    ***************************************************************/

    $('#mangerTable').DataTable( {
        order: [[7, "asc"]],
        dom: 'Bfrtip',
        "bInfo": false,        
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
        ],
        "columnDefs": [
            { "width": "20%", "targets": 0 },
            { "width": "100%", "targets": 7 },
            { "width": "5%", "targets": 2 },
            { "width": "15%", "targets": 3 },
            { "width": "15%", "targets": 4 },
            { "width": "5%", "targets": 5 },
            { "width": "5%", "targets": 6 }
            
          ]
    });

    $('#employTable').DataTable({
        order: [[0, "desc"]]
    });
});
