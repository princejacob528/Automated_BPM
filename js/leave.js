$(document).ready(function() {
    $('#example').DataTable();
    $('#example1').DataTable({
        "searching": false,
        "bPaginate": false,
        "lengthChange": false,
        "bInfo": false,
        "bAutoWidth": false,
    });
} );