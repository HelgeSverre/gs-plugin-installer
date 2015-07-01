$(document).ready(function () {

    // Source: https://gist.github.com/DelvarWorld/3784055
    $.fn.shiftSelectable = function() {
        var lastChecked,
            $boxes = this;

        $boxes.click(function(evt) {
            if(!lastChecked) {
                lastChecked = this;
                return;
            }

            if(evt.shiftKey) {
                var start = $boxes.index(this),
                    end = $boxes.index(lastChecked);
                $boxes.slice(Math.min(start, end), Math.max(start, end) + 1)
                    .attr('checked', lastChecked.checked)
                    .trigger('change');
            }

            lastChecked = this;
        });
    };

    $("#gs_plugin_form").find('input[type="checkbox"]').shiftSelectable();

    $('#plugin_table').DataTable({
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": true
            },
            {
                "targets": [ 3 ],
                "visible": true,
                "searchable": false // exclude "install" column from search
            },
            {
                "targets": [ 4 ],
                "visible": true,
                "searchable": false // checkbox
            }
        ]
    });
});