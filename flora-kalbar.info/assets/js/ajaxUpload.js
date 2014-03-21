function zipExtract()
{
    var username = $('#username').val();
    var filename = $('#zip_file').val();

    if( username == ''){
        $(".errorbox").html('Username must be filled');
    }else{
        if ( filename == '') {
            $(".errorbox").html('Filename can not be empty');
        }else{
            $(".message").html('Fetching files ...');
            var data = { 'username' : username, 'imagezip' : filename };
            
            var extract_file = 
                $.ajax({
                    type: "POST",
                    url: baseUrl+"zip/extract",
                    data: data,
                    async: false,
                    success: function(response){}
                }).responseText;
                
            var resultExtract = JSON.parse(extract_file);
            console.log(resultExtract);
            if(resultExtract.status != 'error'){
                $('#extract_zip').resetForm();
                $(".message").html(resultExtract.message);
            }else{
                $(".errorbox").html(resultExtract.message);
            }
        }
    }
    
    return false;

}