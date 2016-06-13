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

    if (($('#lost-item-owner').val().length == 0) || ($('#student_name').val().length == 0) || ($('#phone').val().length == 0)) {
        $('#submit_delete').prop('disabled', true);
    }
    $('#lost-item-owner').change(function() {
        if (($('#lost-item-owner').val().length > 0) && ($('#student_name').val().length > 0) && ($('#phone').val().length > 0)) {
            $('#submit_delete').prop('disabled', false);
        } else {
            $('#submit_delete').prop('disabled', true);
        }
    });
    $('#student_name').change(function() {
        if (($('#lost-item-owner').val().length > 0) && ($('#student_name').val().length > 0) && ($('#phone').val().length > 0)) {
            $('#submit_delete').prop('disabled', false);
        } else {
            $('#submit_delete').prop('disabled', true);
        }
    });
    $('#phone').change(function() {
        if (($('#lost-item-owner').val().length > 0) && ($('#student_name').val().length > 0) && ($('#phone').val().length > 0)) {
            $('#submit_delete').prop('disabled', false);
        } else {
            $('#submit_delete').prop('disabled', true);
        }
    });

    $('#lost-item-owner').change(function() {
        $.ajax({
            url: '/registration-student-api/'+$('#lost-item-owner').val(),
            type: "get",
            dataType: "json",
            success: function(data){
                $('#student_name').val(data['student_name']);
                $('#phone').val(data['phone_no']);
                $('#submit_delete').prop('disabled', false);
            }
        });      
    });
});