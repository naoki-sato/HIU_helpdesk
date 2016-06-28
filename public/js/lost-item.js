$(function() {

    $('input[type=radio]').change(function() {
        $('#serial_number_contents,#history_contents').removeClass('invisible');
 
        if ($("input:radio[name='export']:checked").val() == "1") {
            $('#history_contents').addClass('invisible');
        } else if($("input:radio[name='export']:checked").val() == "2") {
            $('#serial_number_contents').addClass('invisible');
        }
    }).trigger('change');
});