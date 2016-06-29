$(function() {
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
    $('#file_input').change(function() {
        $('#dummy_file').val($(this).val());
    });
});