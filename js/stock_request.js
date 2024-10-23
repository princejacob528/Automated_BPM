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
        order: [[0, "desc"]],
    });
    $('#internalTransfer').DataTable( {
        order: [[0, "desc"]],
    });
    $('#itorders').DataTable( {
        order: [[0, "desc"]],
    });
    $('#itrequest').DataTable( {
        order: [[0, "desc"]],
    });
    $('#strequest').DataTable( {
        order: [[0, "desc"]],
    });
});
