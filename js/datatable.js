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
        order: [[1, "asc"]],
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

    $('#employTable').DataTable({
        order: [[0, "desc"]]
    });
});
