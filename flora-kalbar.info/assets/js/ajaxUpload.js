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
            $(".errorbox").html('');
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

            if(resultExtract.status != 'error'){
                $('#extract_zip').resetForm();
                $(".errorbox").html('');
                $(".message").html(resultExtract.message);
            }else{
                $(".message").html('');
                $(".errorbox").html(resultExtract.message);
            }
            
            if(resultExtract.data){
                $(".errorbox").append('The following file(s) is not associated with any data <br /><table id="data"></table>');
                var dataResult = resultExtract.data;
                var dataNotExist = dataResult.dataNotExist;
                dataNotExist.forEach(function(entry) {
                    console.log(entry);
                    $("#data").append(
                        '<tr><td>Filename</td><td>Directory</td><td>Mimetype</td></tr>' +
                        '<tr><td>'+ entry.filename +'</td><td>'+ entry.directory +'</td><td>'+ entry.mimetype +'</td></tr>'
                    );
                });
            }
        }
    }
    
    return false;

}