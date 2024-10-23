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
        order: [[8, "asc"]],
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
        ],
        "columnDefs": [
            { "width": "10%", "targets": 3 },
            { "width": "4%", "targets": 4 },
            { "width": "4%", "targets": 5 },
            { "width": "5%", "targets": 6 },
            { "width": "10%", "targets": 7 }
          ]
    });

    $('#employTable').DataTable({
        order: [[0, "desc"]]
    });
});
