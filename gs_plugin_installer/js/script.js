$(document).ready(function () {
    $('#plugin_table').DataTable({
        "columnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": true
            },
            {
                "targets": [3],
                "visible": true,
                "searchable": false // exclude "install" column from search
            },
            {
                "targets": [4],
                "visible": true,
                "searchable": false // checkbox
            }
        ]
    });
});