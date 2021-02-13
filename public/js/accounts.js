let editCashier = document.querySelectorAll('#editCashierBtn');
let removeCashier = document.querySelectorAll('#removeCashierBtn');
let editAdmin = document.querySelectorAll('#editAdminBtn');
let removeAdminBtn = document.querySelectorAll('#removeAdminBtn');


editCashier.forEach(element => {

    element.addEventListener('click',function(e){
        let td = element.parentElement;
        let tr = td.parentElement;
        let id = tr.children[0].innerHTML.trim();
        let cashierID = tr.children[1].innerHTML.trim();
        let cashierName = tr.children[2].innerHTML.trim();

        document.getElementById('updateID').value=id;
        document.getElementById('updateCashierID').value=cashierID;
        document.getElementById('updateCashierName').value=cashierName;
        
    });
});

   
removeCashier.forEach(element => {
    element.addEventListener('click',function(e)
    {
        remove(element);
        // console.log
        // let td = element.parentElement;
        // let tr = td.parentElement;
        // let id = tr.children[0].innerHTML.trim();
        // let cashierID = tr.children[1].innerHTML.trim();
        // let cashierName = tr.children[2].innerHTML.trim();

        // if(confirm('You are about to remove Cashier :'+ cashierName +" with cashier ID: "+cashierID+". PROCEED?"))
        // {
        //     console.log(location.pathname);
        // }
        // else
        // {
        //     location.reload();
        // }
    });
});


   function remove(element)
   {
    {
        
        let td = element.parentElement;
        let tr = td.parentElement;
        let id = tr.children[0].innerHTML.trim();
        let cashierID = tr.children[1].innerHTML.trim();
        let cashierName = tr.children[2].innerHTML.trim();
      
        if(confirm('You are about to remove Cashier :'+ cashierName +" with cashier ID: "+cashierID+". PROCEED?"))
        {
            window.location = location.pathname+"/remove/cashier?id="+id;
            console.log(location.pathname+"/remove/cashier/"+id);

        }

    }
   }

editAdmin.forEach(element => {
    element.addEventListener('click',function(){
        let td = element.parentElement;
        let tr = td.parentElement;
        let id = tr.children[0].innerHTML.trim();
        let adminName = tr.children[1].innerHTML.trim();
        let adminUsername = tr.children[2].innerHTML.trim();

        document.querySelector('#uadminID').value = id;
        document.querySelector('#uadminName').value = adminName;
        document.querySelector('#uadminUsername').value = adminUsername;

    });
});

var removeID ;

removeAdminBtn.forEach(element => {
    element.addEventListener('click',function(){
        let td = element.parentElement;
        let tr = td.parentElement;
        let id = tr.children[0].innerHTML.trim();
        removeID = id;
    });
});

document.querySelector('#confirmRemoveBtn').addEventListener('click',function(){
   removeAdmin(removeID);
});

function removeAdmin(id)
{
    
    let pwd = document.querySelector('#currentPassword').value;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $.ajax({
        type:'POST',
        url:'/removeAdmin',
        data:{
            
            'id': id, 
             'password':pwd,
            },
        success:function(response){
            $('removeAdminModal').modal('toggle');
            location.reload();
        },
        error:function(err)
        {
            document.getElementById('error').innerHTML = err.responseText;
        },
    });
   
}
