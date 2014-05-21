$().ready(function() {
    /**
    * checking name exist or not in database
    */
    $.validator.addMethod("checkNameExist",function(value,element){
        var data_input = $('#name').val();
        var data = { 'name' : data_input };
        var check = $.ajax({
                        url: baseUrl+"onebyone/check_Name",
                        type: "POST",
                        async: false,
                        data: data,
                        success: function(output) {}
                    }).responseText;
        return check;
    }," Name already exist");
    
    /**
    * checking email exist or not in database
    */
    $.validator.addMethod("checkEmailExist",function(value,element){
        var data_input = $('#email').val();
        var data = { 'email' : data_input };
        var check = $.ajax({
                        url: baseUrl+"onebyone/check_Email",
                        type: "POST",
                        async: false,
                        data: data,
                        success: function(output) {}
                    }).responseText;
        return check;
    }," Email already registered");
    
    /**
    * checking email exist or not in database
    */
    $.validator.addMethod("checkEmailNotExist",function(value,element){
        var data_input = $('#email').val();
        var data = { 'email' : data_input };
        var check = $.ajax({
                        url: baseUrl+"onebyone/check_Email",
                        type: "POST",
                        async: false,
                        data: data,
                        success: function(output) {}
                    }).responseText;
        if(check){
            return false;
        }else{
            return true;
        }
    }," Email Not Exist");
    
    /**
    * checking twitter exist or not in database
    */
    $.validator.addMethod("checkTwitterExist",function(value,element){
        var data_input = $('#twitter').val();
        var data = { 'twitter' : data_input };
        var check = $.ajax({
                        url: baseUrl+"onebyone/check_Twitter",
                        type: "POST",
                        async: false,
                        data: data,
                        success: function(output) {}
                    }).responseText;
        return check;
    }," Twitter name already exist");
    
    /**
    * checking username exist or not in database
    */
    $.validator.addMethod("checkUsernameExist",function(value,element){
        var data_input = $('#username').val();
        var data = { 'username' : data_input };
        var check = $.ajax({
                        url: baseUrl+"user/checkUsername",
                        type: "POST",
                        async: false,
                        data: data,
                        success: function(output) {}
                    }).responseText;
        return check;
    }," Username name already exist");
    
    /**
    * checking alphanumeric
    */
    $.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[A-Za-z\d_]+$/.test(value);
    }, "Must contain only letters, numbers, or underscore.");
    
    /**
    * checking password
    */
    $.validator.addMethod("checkPassword",function(value,element){
        var data_input = $('#password').val();
        var data = { 'password' : data_input };
        var check = $.ajax({
                        url: baseUrl+"user/checkPassword",
                        type: "POST",
                        async: false,
                        data: data,
                        success: function(output) {}
                    }).responseText;
        return check;
    }," Invalid password or incorrect password");
    
    /**
    * checking decimal input
    */
    $.validator.addMethod('Decimal', function(value, element) {
        return this.optional(element) || /^\d+(\.\d{0,3})?$/.test(value); 
    }, "Please enter a correct number, only dot permitted");
                
});

/**
* checking extention from input file
* @param inputID = id of input type file
* @param exts = array extention allowed => ['.zip', '.rar']
* */
function hasExtension(inputID, exts) {
    var fileName = document.getElementById(inputID).value;
    return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
}

function do_ajax(form, formID){
    var first_error = '<div class="messages erroren"><a href="#" class="closeMessage"></a><p>';
    var first_info = '<div class="messages info"><a href="#" class="closeMessage"></a><p>';
    var first_success = '<div class="messages success"><a href="#" class="closeMessage"></a><p>';
    var first_warning = '<div class="messages warning"><a href="#" class="closeMessage"></a><p>';
    var end = '</p></div>';
    var msg = ".msg";
    
    console.log(form);
    $(form).ajaxSubmit(function(output){
        var data = JSON.parse(output);
        console.log(data);
        $(".message").html('');
        if(data != 'error'){
            $(msg).html(first_success + 'Update Location Success' + end);
            $("#modal-location").fadeOut();
            
            if(formID == 'formLocation'){
                $("#locnID").find('option').removeAttr('selected');
                $("#locnID").append('<option value="'+ data.id +'" selected>'+ data.locality +'</option>');
            }
            
        }else{
            $("#modal-location").fadeOut();
            $(msg).html(first_error + 'Update Location Failed' + end);
        }
    });
}
