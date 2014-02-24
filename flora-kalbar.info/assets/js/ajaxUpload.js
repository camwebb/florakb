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
        /*console.log(response.responseText);
        var message = JSON.parse(response.responseText);
        progressbar.progressbar({value: 100});
        output.html(message.msg); //update element with received data                                              
        statustxt.html('Done');
        report.html(message.report)
        uploadform.resetForm();  // reset form
        input.prop('disabled', false);
        button.slideDown();
        if(message.status != '0')
  		{
            alert('Succeed!')
        }*/
    },
});

});

function validateFormUpload()
{
    if( $('#username').val() == ''){
        $(".errorbox").html('Username must be filled');
        return false;
    }else{
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: success,
            dataType: json
        });
    }
}