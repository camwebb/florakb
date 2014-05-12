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
    }, "Must contain only letters, numbers, or dashes.");
    
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
