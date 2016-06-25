$('#delete').click(function(){
    if(!confirm('本当に削除しますか？')){
        return false;
    }
});

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

    if (($('#lost-item-owner').val().length == 0) || ($('#user_name').val().length == 0) || ($('#phone_no').val().length == 0)) {
        $('#submit_delete').prop('disabled', true);
    }
    $('#lost-item-owner').change(function() {
        if (($('#lost-item-owner').val().length > 0) && ($('#user_name').val().length > 0) && ($('#phone_no').val().length > 0)) {
            $('#submit_delete').prop('disabled', false);
        } else {
            $('#submit_delete').prop('disabled', true);
        }
    });
    $('#user_name').change(function() {
        if (($('#lost-item-owner').val().length > 0) && ($('#user_name').val().length > 0) && ($('#phone_no').val().length > 0)) {
            $('#submit_delete').prop('disabled', false);
        } else {
            $('#submit_delete').prop('disabled', true);
        }
    });
    $('#phone_no').change(function() {
        if (($('#lost-item-owner').val().length > 0) && ($('#user_name').val().length > 0) && ($('#phone_no').val().length > 0)) {
            $('#submit_delete').prop('disabled', false);
        } else {
            $('#submit_delete').prop('disabled', true);
        }
    });

    $('#lost-item-owner').change(function() {
        $.ajax({
            url: '/registration-user-api/'+$('#lost-item-owner').val(),
            type: "get",
            dataType: "json",
            success: function(data){
                $('#user_name').val(data['user_name']);
                $('#phone_no').val(data['phone_no']);
                $('#submit_delete').prop('disabled', false);
            }
        });      
    });
});