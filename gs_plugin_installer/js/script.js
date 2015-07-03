$(document).ready(function () {

    // Confirm when uninstalling plugins
    $("#uninstall").click(function() { return confirm("Are you sure you want to uninstall the selected plugins?"); });
    $("a.cancel").click(function() { return confirm("Are you sure you want to uninstall this plugin?"); });

    // Initialize DataTable
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