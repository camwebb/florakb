$(document).ready(function() {

$('#formSignup').submit(function(event) {
    event.preventDefault();

    $.ajax({
        url: baseUrl+"home/signup",
        type: 'POST',
        data: $(this).serialize(),
        //dataType: 'jsonp',
        success: function(data) {
            //var a = data.responseText;
            var b = $.parseJSON(data);            
            //console.log(a);
            console.log(b);
        }
    });
});

});