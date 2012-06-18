/**
 * File contains ajax-search javascript (uses jQuery).
 */

/* Search fields. */
var element_content = new Array("","","");

/* Timeout for search. */
var timeout;

$(document).ready(function(){

    /* Hide search button in Ajax-search. */
    $('#search_submit').hide();

    /* Initial search values */
    element_content[0] = $('#search_text').val();
    element_content[1] = $('#search_pg').val();
    element_content[2] = $('#search_year').val();

    /* Initial keyup-event for form-fields. */
    $('#ajaxsearch input').keyup(function() {
        var id, val = $(this).val(), elem_id = $(this).attr('id');
        if (elem_id == 'search_text') {
            id = 0;
        } else if (elem_id == 'search_pg') {
            id = 1;
        } else if (elem_id == 'search_year') {
            id = 2;
        }

        if (element_content[id] == val) {
            return;
        } else {
            element_content[id] = val;
            timeout = setTimeout("checkSearch('" + id + "','" + val + "');", 1000, "javascript");
        }
    });

});

function checkSearch(id, val) {
    if (element_content[id] == val && (element_content[0] || element_content[1] || element_content[2])) {
        $.getJSON(html_url + 'json/get/', {'data': 'search', 'text': element_content[0], 'pg': element_content[1], 'year': element_content[2]}, function(json) {
            var data = json.data;
            if (!$('#searchcontent').html())
                $('#ajaxsearch').after(
                    $('<div />').attr('id', 'searchcontent').hide()
                );
            $('#searchcontent').fadeOut(function() {
                var searchcontent = $(this);
                $(searchcontent).html('');
                if (data[0]) {
                    $.each(data, function(i, pic) {
                        $('<a />').attr('href', html_url + 'images/?id=' + pic.pid).append(
                            $('<img />').attr('src', html_url + 'images/file/?size=s&id=' + pic.pid).attr('alt', pic.desc)
                        ).appendTo(searchcontent);
                    });
                } else {
                    $(searchcontent).append(
                        $('<p />').text('No matches.')
                    );
                }
                $(searchcontent).fadeIn();
            });
        });
    } else if (!element_content[0] && !element_content[1] && !element_content[2]){
        if ($('#searchcontent').html())
            $('#searchcontent').fadeOut(function() {
                $(this).remove();
            });
    } else {
        return;
    }
}
