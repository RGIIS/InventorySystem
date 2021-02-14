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
            
               document.getElementById('updateAlert').hidden=false;
               document.getElementById('updateMessage').innerHTML = response.message;
               
           }
        },
        error: function(response)
        {
            overlayLoading.hidden=true;
            console.log('error');
            console.log(response);
        }
    });

    setTimeout(function(){document.getElementById('updateAlert').hidden=true},5000);
        


}

function getUpdate()
{
    updateContainer.hidden=true;
    overlayLoading.hidden=false;
    loadingMsg = document.getElementById('loadingMsg');
    loadingMsg.innerHTML = 'Getting Update .... Please Wait';
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
        document.getElementById('updateAlert').hidden=false;
        document.getElementById('updateMessage').innerHTML = response;
        },
        error: function(response)
        {
            overlayLoading.hidden=true;
            alert('Something Went Wrong')
        }
    });
    setTimeout(function(){document.getElementById('updateAlert').hidden=true},5000);
}