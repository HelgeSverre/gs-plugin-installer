$(document).ready(function () {
  
    var fromMarkdown = null;
    
    $('.more-info').on('click', function(e) {
        e.preventDefault();
        // on first click, init markdown parser
        if (!fromMarkdown)
            fromMarkdown = new showdown.Converter({
              openLinksInNewWindow : true,
              literalMidWordUnderscores: true,
              literalMidWordAsterisks: true,
              noHeaderId: true,
              headerLevelStart: 4
            });
        
        var $this = $(this), html,
            $fullDesc = $this.closest('tr').find('.full_description'),
            title = '<h3>' + $this.closest('tr').find('a').eq(0).text() + '</h3>';
            
        // data-html attr keeps track of whether the markdown has already been parsed
        if (!$fullDesc[0].hasAttribute('data-html')) {
            html = $fullDesc.html()
              .replace(/&lt;(?!(script|iframe))/g, '<')
              .replace(/&amp;(?!(script|iframe))/g, '&')
              .replace(/&gt;/g, '>');
            $fullDesc.html(fromMarkdown.makeHtml(html));
            $fullDesc[0].setAttribute('data-html','');
        }
        
        $.fancybox({ 
            type: 'html',
            maxWidth: 960,
            minHeight: 80,
            content: title + $fullDesc.html(),
            // fixes rendering glitch on Chrome
            closeEffect: 'none' 
        });
    });

    // Confirm when uninstalling plugins
    $("#uninstall").click(function() { return confirm(i18n('CONFIRM_UNINSTALL_ALL')); });
    $("a.cancel").click(function() { return confirm(i18n('CONFIRM_UNINSTALL')); });

    // Initialize DataTable
    $('#plugin_table').DataTable({
        "columnDefs": [
            {
                "targets": [1],
                "visible": true,
                "searchable": false // exclude date from search
            },
            {
                "targets": [2, 3, 4],
                "visible": true,
                "searchable": false, // exclude "install" & description column from search
                "orderable": false
            }
        ],
        initComplete: function() {
            // fixes table overflow before DataTable finishes rendering
            $('#plugin_table').css('display', 'table');
        }
    });
});
