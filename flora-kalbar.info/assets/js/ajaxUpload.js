$(function() {

//elements Upload Config

var progressbox     = $('#progressbox');
var progressbar     = $('#progressbar');
var statustxt       = $('#statustxt p');
var uploadform      = $("#upload_file");
var output          = $(".errorbox");
var input           = $("input");
var button          = $('#box_button');
var report          = $('#report');
var completed       = '0%';
progressbar.progressbar({value: 0});

//elements Upload Config

$(uploadform).ajaxForm({
    dataType:  'json',
    beforeSend: function() { //brfore sending form
        output.empty();
        report.empty();
        statustxt.empty();
        progressbox.slideDown();
        statustxt.html(completed); //set status text
        statustxt.css('color','#000'); //initial color of status text
        input.prop('disabled', true);
        button.hide();
    },
    uploadProgress: function(event, position, total, percentComplete) { //on progress
        progressbar.progressbar({value: percentComplete});
        statustxt.html(percentComplete + '%'); //update status text
        if(percentComplete>50)
        {
            statustxt.css('color','#fff'); //change status text to white after 50%
        }
        if(percentComplete==100)
        {
            statustxt.html('Processing image..');
        }
    },
    complete: function(response) { // on complete
        var message = JSON.parse(response.responseText);
        progressbar.progressbar({value: 100});
        output.html(message.message); //update element with received data                                              
        statustxt.html('Done');
        report.html(message.report)
        uploadform.resetForm();  // reset form
        input.prop('disabled', false);
        button.slideDown();
        if(message.status != 'error')
  		{
            output.html(message.message);
        }
    },
});

});

function validateFormUpload()
{
    var username = $('#username').val();
    var file_zip = $('#zip_file').val();
    if( username == ''){
        $(".errorbox").html('Username must be filled');
        return false;
    }else{
        if (!hasExtension('zip_file', ['.zip'])) {
            $(".errorbox").html('File type is not allowed');
            return false;
        }else{
            var data = { 'username' : username };
            var validateUsername = 
                $.ajax({
                    type: "POST",
                    url: baseUrl+"upload/validateUsername",
                    data: data,
                    async: false,
                    success: function(response){}
                }).responseText;
            
            var resultUsername = JSON.parse(validateUsername);

            if (resultUsername.status != 'error'){
                return true;
            }else{
                return false;
            }
        }
    }
}

function validateFormExtract()
{
    var username = $('#username').val();
    var filename = $('#zip_file').val();
    if( username == ''){
        $(".errorbox").html('Username must be filled');
        return false;
    }else{
        if ( filename == '') {
            $(".errorbox").html('Filename can not be empty');
            return false;
        }else{
            var data = { 'username' : username };
            var validateUsername = 
                $.ajax({
                    type: "POST",
                    url: baseUrl+"upload/validateUsername",
                    data: data,
                    async: false,
                    success: function(response){}
                }).responseText;
            
            var resultUsername = JSON.parse(validateUsername);

            if (resultUsername.status != 'error'){
                return true;
            }else{
                return false;
            }
        }
    }
}

/*$( "#extract_zip" ).on( "submit", function( event ) {
    //var data = { 'username' : username };
    var validateUsername = 
        $.ajax({
            type: "POST",
            url: baseUrl+"zip/extract",
            //data: data,
            async: false,
            success: function(response){}
        }).responseText;
    
    var resultUsername = JSON.parse(validateUsername);

    console.log(resultUsername);
}*/