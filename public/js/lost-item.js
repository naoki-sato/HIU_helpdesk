$(function() {

    $('input[type=radio]').change(function() {
        $('#serial_number_contents,#history_contents').removeClass('invisible');
 
        if ($("input:radio[name='export']:checked").val() == "1") {
            $('#history_contents').addClass('invisible');
        } else if($("input:radio[name='export']:checked").val() == "2") {
            $('#serial_number_contents').addClass('invisible');
        }
    }).trigger('change');

    if ($('#lost_item_name').val().length == 0) {
        $('#submit').prop('disabled', true);
    }
    $('#lost_item_name').on('keydown keyup keypress change', function() {
        if ($(this).val().length > 0) {
            $('#submit').prop('disabled', false);
        } else {
            $('#submit').prop('disabled', true);
        }
    });

    if (($('#start_number').val().length == 0) || ($('#end_number').val().length == 0)) {
        $('#serial_submit').prop('disabled', true);
    }
    $('#start_number').change(function() {
        if (($('#start_number').val().length > 0) && ($('#end_number').val().length > 0)) {
            $('#serial_submit').prop('disabled', false);
        } else {
            $('#serial_submit').prop('disabled', true);
        }
    });
    $('#end_number').change(function() {
        if (($('#start_number').val().length > 0) && ($('#end_number').val().length > 0)) {
            $('#serial_submit').prop('disabled', false);
        } else {
            $('#serial_submit').prop('disabled', true);
        }
    });


});