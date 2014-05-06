function zipExtract()
{
    var email = $('#email').val();
    var filename = $('#zip_file').val();
    var first_error = '<div class="messages erroren"><a href="#" class="closeMessage"></a><p>';
    var first_info = '<div class="messages info"><a href="#" class="closeMessage"></a><p>';
    var first_success = '<div class="messages success"><a href="#" class="closeMessage"></a><p>';
    var first_warning = '<div class="messages warning"><a href="#" class="closeMessage"></a><p>';
    var end = '</p></div>';
    
    $(".errorbox").html('');
    $(".message").html('');
    
    if( email == ''){
        $(".errorbox").html(first_error + 'Email must be filled' + end);
    }else{
        if ( filename == '') {
            $(".errorbox").html(first_error + 'Filename can not be empty' + end);
        }else{
            $(".errorbox").html('');
            $(".message").html(first_info + 'Fetching files ...' + end);
            var data = { 'email' : email, 'imagezip' : filename };
            
            var extract_file = 
                $.ajax({
                    type: "POST",
                    url: baseUrl+"zip/extract",
                    data: data,
                    async: false,
                    success: function(response){}
                }).responseText;
                
            var resultExtract = JSON.parse(extract_file);

            if(resultExtract.status != 'error'){
                $('#extract_zip').resetForm();
                $(".errorbox").html('');
                $(".message").html(first_success + resultExtract.message + end);
            }else{
                $(".message").html('');
                $(".errorbox").html(first_error + resultExtract.message + end);
            }
            
            if(resultExtract.data){
                $(".errorbox").append(first_warning +
                    'The following file(s) is not associated with any data <br /><table id="data">' +
                    '<tr><td>Filename</td><td>Directory</td><td>Mimetype</td></tr></table>' +
                    end
                );
                var dataResult = resultExtract.data;
                var dataNotExist = dataResult.dataNotExist;
                dataNotExist.forEach(function(entry) {
                    console.log(entry);
                    $("#data").append(
                        '<tr><td>'+ entry.filename +'</td><td>'+ entry.directory +'</td><td>'+ entry.mimetype +'</td></tr>'
                    );
                });
            }
        }
    }
    
    return false;

}