var first_error = '<div class="messages erroren"><a href="#" class="closeMessage"></a><p>';
var first_info = '<div class="messages info"><a href="#" class="closeMessage"></a><p>';
var first_success = '<div class="messages success"><a href="#" class="closeMessage"></a><p>';
var first_warning = '<div class="messages warning"><a href="#" class="closeMessage"></a><p>';
var end = '</p></div>';

/**
* checking extention from input file
* @param inputID = id of input type file
* @param exts = array extention allowed => ['.zip', '.rar']
* */
function hasExtension(inputID, exts) {
    var fileName = document.getElementById(inputID).value;
    return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
}

function checkName(){
    var name = $('#name').val();
    var data = { 'name' : name };
    var check = 
        $.ajax({
            type: "POST",
            url: baseUrl+"onebyone/check_Name",
            data: data,
            async: false,
            success: function(response){}
        }).responseText;
        
    var result = JSON.parse(check);
    if(result.status == 'error'){
        // TAMBAH CLASS ERROR DI INPUT BOX
        $('#nameGroup').append('<label for="name" class="error"> '+ result.message +'</label>');
    }
}