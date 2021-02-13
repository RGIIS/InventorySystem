overlayLoading = document.getElementById('overlay');
updateContainer = document.getElementById('newUpdate');
newVersionNumber = document.getElementById('newVersion');
getUpdateBtn = document.getElementById('getUpdate');

function checkUpdate(){
    overlayLoading.hidden=false;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $.ajax({
        type:'GET',
        url:'/checkUpdate',
        data:{},

        success: function(response){
            overlayLoading.hidden=true;
           if(response.hasNewVersion)
           {
            getUpdateBtn.addEventListener('click',function(){
                getUpdate();
            })
            updateContainer.hidden = false;
            newVersionNumber.innerHTML = response.newVersion;
           }
           else{
               console.log('no new version')
           }
        },
        error: function(response)
        {
            overlayLoading.hidden=true;
            console.log('error');
            console.log(response);
        }
    });


        


}

function getUpdate()
{
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