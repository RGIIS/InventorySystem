


 //CONFIRM BOX FOR CLEAR SALES KEY TRAPPING

 
 document.querySelector('#confirmBox').addEventListener('input',function(e){
   if(e.target.value==='CONFIRM'){
    document.querySelector('#clearSaleBtn').disabled = false;
   }
   else{
    document.querySelector('#clearSaleBtn').disabled = true;
   }
 });




 //CLEAR SALES BUTTON

document.querySelector('#clearSaleBtn').addEventListener('click',function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $.ajax({
        type:'POST',
        url:'/sales/clear',
        data:{
            
             
             
            },success:function(){

                    



            },error:function(){
                alert('Something Went Wrong Please Reload');
            },
        });
        location.reload();

});


//REMOVE SELECTED WITH ALERT

document.querySelector('#removeSelected').addEventListener('click',function(){
   
      var checkBoxes = document.querySelectorAll('input[type=checkbox]:checked');
      var successCount = 0;
      if(checkBoxes.length>0)
      {
          console.log(checkBoxes.length);
      if(confirm("Are you sure you want to remove selected items ? ","Remove Selected"))
      {

      checkBoxes.forEach(element => {
        let removeId = element.parentElement.nextElementSibling.innerHTML;

        //AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $.ajax({
        type:'POST',
        url:'/sales/remove',
        data:{
            
            'id': removeId, 
             
            },success:function(){

                    



            },error:function(){
                alert('Something Went Wrong Please Reload');
            },
        });



      });
      location.reload();
      }
    }

    });

let sortType;
let sortBtn = document.querySelector('#sortBtn');
let sortText = document.querySelector('#inputSort');

//SORT BY CUSTOMER
document.querySelector('#sortCustomer').addEventListener('click',function(e){
    e.preventDefault();
    sortType = 'Customer';
    document.querySelector('#inputSort').placeholder = "Sort By Customer Name";
    sortBtn.disabled = false;
});

//SORT BY RECEIPT

document.querySelector('#sortReceipt').addEventListener('click',function(e){
    e.preventDefault();
    sortType = 'Receipt';
    document.querySelector('#inputSort').placeholder = "Sort By Receipt Number";
    sortBtn.disabled = false;
});

//SORT BY CASHIER

document.querySelector('#sortCashier').addEventListener('click',function(e){
    e.preventDefault();
    sortType = 'Cashier'
    document.querySelector('#inputSort').placeholder = "Sort By Cashier Name";
    sortBtn.disabled = false;
});

document.querySelector('#sortBtn').addEventListener('click',function(e){
    e.preventDefault();
    console.log(sortText.value==="");
    if(sortText.value!="")
    {
    var queryParams = new URLSearchParams(window.location.search);
    var url = window.location.href;
    // console.log(queryParams.toString());
    let queryString;
    if(sortType=='Customer')
    {
        queryParams.set('customer',sortText.value);
    }
    if(sortType=='Receipt')
    {
        queryParams.set('receipt',sortText.value);
    }
    if(sortType=='Cashier')
    {
        queryParams.set('cashier',sortText.value);
    }
   
    
    window.location = window.location.pathname+'?'+queryParams;
}
    
});

function removeParam(param)
{
    var url = window.location.href;
    var queryParams = new URLSearchParams(window.location.search);
    queryParams.delete(param);
    window.location = window.location.pathname+'?'+queryParams;
}




    
   
 