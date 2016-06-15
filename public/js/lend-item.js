$(function() {
    $('#student_no').change(function() {
        $.ajax({
            url: '/registration-student-api/'+$('#student_no').val(),
            type: "get",
            dataType: "json",
            success: function(data){
                $('#student_name').val(data['student_name']);
                $('#phone').val(data['phone_no']);
                $('#submit_lend').prop('disabled', false);
            }
        });      
    });


    
    
    $("#lent_item").EnterTab({Enter:true,Tab:true});
    $("#return_item").EnterTab({Enter:true,Tab:true});
});