$(function() {
    $('#user_cd').change(function() {
        $.ajax({
            url: '/registration-user-api/'+$('#user_cd').val(),
            type: "get",
            dataType: "json",
            success: function(data){
                $('#user_name').val(data['user_name']);
                $('#phone_no').val(data['phone_no']);
                $('#submit_lend').prop('disabled', false);
            }
        });      
    });


    
    
    $("#lent_item").EnterTab({Enter:true,Tab:true});
    $("#return_item").EnterTab({Enter:true,Tab:true});
});