$(function() {
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

    if ($('#lost-item-owner').val().length == 0) {
        $('#submit_delete').prop('disabled', true);
    }
    $('#lost-item-owner').on('keydown keyup keypress change', function() {
        if ($(this).val().length > 0) {
            $('#submit_delete').prop('disabled', false);
        } else {
            $('#submit_delete').prop('disabled', true);
        }
    });
});