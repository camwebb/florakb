$(document).ready(function() {

$('#formSignup').submit(function(event) {
    event.preventDefault();

    $.ajax({
        url: baseUrl+"login/doSignup",
        type: 'POST',
        data: $(this).serialize(),
        success: function(data) {
            var result = $.parseJSON(data);            
            if(result.statusName == 'exist' || result.statusEmail == 'exist' || result.statusUsername == 'exist' || result.statusTwitter == 'exist'){
                $('#signup-password,#signup-re_password').val('');
                if(result.statusName == 'exist'){
                    $('#nameGroup').append('<span class="florakb-error">'+result.msgName+'</span>');
                }
                if(result.statusEmail == 'exist'){
                    $('#emailGroup').append('<span class="florakb-error">'+result.msgEmail+'</span>');
                } 
                if(result.statusUsername == 'exist'){
                    $('#usernameGroup').append('<span class="florakb-error">'+result.msgUsername+'</span>');
                }
                if(result.statusTwitter == 'exist'){
                    $('#twitterGroup').append('<span class="florakb-error">'+result.msgTwitter+'</span>');
                }
            }
            else{
                alert('User created, do login for enter the site.');
                location.reload();
            } 
        }
    });
    $('#signup-name').keyup(function(){
       $('#nameGroup > span').remove();
    });
    $('#signup-email').keyup(function(){
       $('#emailGroup > span').remove();
    });
    $('#signup-username').keyup(function(){
       $('#usernameGroup > span').remove();
    });
    $('#signup-twitter').keyup(function(){
       $('#twitterGroup > span').remove();
    });
});

$('#formLogin').submit(function(event){
    event.preventDefault();
    
    $.ajax({
        url: baseUrl+"login/doLogin",
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            var returnedData = JSON.parse(response);
            if(returnedData == 'success'){
                location.reload();
            }
            else{
                alert('You have entered an invalid username or password');
                $('#login-password').val('');
            }
        }
    });
});

});