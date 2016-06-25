$(function() {
     $('input[type=radio]').change(function() {
        $('#export,#import').removeClass('invisible');
 
        if ($("input:radio[name='select']:checked").val() == "1") {
            $('#export').addClass('invisible');
        } else if($("input:radio[name='select']:checked").val() == "2") {
            $('#import').addClass('invisible');
        }
    }).trigger('change');

    $('#file_input').change(function() {
        $('#dummy_file').val($(this).val());
    });
});