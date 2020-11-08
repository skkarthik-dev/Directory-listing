function custom(){
    this.appUrl = $('body').data('app-url') + '/';
    this.validate();
    this.data = {};
}

custom.prototype.validate = function(){
    var English = {
        badAlphaNumeric: 'This field is not valid ',
        badAlphaNumericExtra: ' ',
        andSpaces: '  ',
        badInt: 'This field is not valid ',
        requiredField: 'This field is required.',
    };
    $.validate({
            language : English,
            modules : 'location,date,security,file,logic',
            scrollToTopOnError : true,
            
    });
}

custom.prototype.resetAppUrl = function(){
    custom.appUrl = $('body').data('app-url') + '/';
}

custom.prototype.ajaxRequest = function(url,requestType,data,successCallback,failureCallback,processData){

    custom.resetAppUrl();
    if(url !== undefined && url.indexOf('http') == -1){
        if(url.substring(0,1) != '/')
            url = custom.appUrl + url
        else
             url = custom.appUrl + url.substring(1);
    }
    $.ajax({
        url : url,
        method : requestType,
        data : data,
        headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
        processData : (processData !== undefined) ? processData : false,
        contentType: false,
        success : function(response){

            
            if(successCallback != null){
                successCallback(response);
            }
        },
        error : function(err){
            if(err != null){
                failureCallback(err);
            }
        }

    });
}

custom.prototype.getFiles = function(){
	custom.filterData();
	custom.ajaxRequest('/get-files' ,'get',custom.data,custom.getFilesSuccess,custom.getFilesFailure,true)
}

custom.prototype.getFilesSuccess = function(response){
	 $(".pagination").html(response.links);
    var pagination_current = "Page" +  response.files.current_page + " of " + response.files.last_page;
    $(".pagination-current").text('');
    if( response.files.last_page > 1 )
        $(".pagination-current").text(pagination_current);
    $('#myTable #listing-directory-files').html('');
    var j = parseInt(response.files.from);
 	$.each(response.files.data, function( i , files ) {
 		$("#myTable #listing-directory-files").append($('<tr class = "table-row">')
            .append($('<td class="text-center">')
                .text(j)
                )
            .append($('<td class="text-center">')
                .text(files)
                )
            .append($('<td class="text-center">')
                .html("<a href='#'>Delete</a>")
                )
            );
            j++;
 	});   
}

custom.prototype.filterData = function(){
	if(custom.data === undefined)
            custom.data = {};
                
    if( $('.search-files').val() != '' ){ 
        custom.data['file_search'] =  $('.search-files').val();
    }else{
        delete custom.data.file;
    }
}

custom.prototype.uploadSucces = function(response)
{
     $('#myModal').find('.close-btn').trigger('click');
     custom.getFiles();
}

var custom = new custom();
$(document).ready(function(){

	if($('#myTable #listing-directory-files').length == 1)
	{
		custom.getFiles();
	}
	$('.search-files').blur(function() {
		custom.getFiles();
	});
    $('#myfile').change(function(e) {
        let upload = {};
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.readAsDataURL(this.files[0]);
            
                upload.document_form = new FormData();
                upload.document_form.append('file',this.files[0]);
                custom.ajaxRequest('/upload-file','post',upload.document_form,custom.uploadSucces,custom.uploadFailur);
         
            //reader.readAsDataURL(this.files[0]);
        }
        else {
            console.log("error");
        }
    })
});