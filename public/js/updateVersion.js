overlayLoading = document.getElementById('overlay');

function checkUpdate(){
    overlayLoading.hidden=false;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $.ajax({
        type:'GET',
        url:'/updateVersion',
        data:{},

        success: function(response){
            overlayLoading.hidden=true;
            console.log('success');
            console.log(response);
        },
        error: function(response)
        {
            overlayLoading.hidden=true;
            console.log('error');
            console.log(response);
        }
    });


        


}