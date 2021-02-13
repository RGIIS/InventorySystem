

let addToCart = document.querySelectorAll('#addToCart');
let addToCartConfirm = document.getElementById('addToCartConfirm');
var globalItem;
var totalAmount = 0;
var totalCash=0;
let cashBox = document.getElementById('cashBox');
var quantityBox = document.getElementById("quantityBox");
var removeFromCart = document.querySelectorAll('#removeFromCart');
var editItem = document.querySelectorAll('#editItem');
var finalRemove = 0;
var removeItemConfirm = document.querySelector('#removeItemConfirm');
var removeParent ;
var calculateChange = document.querySelector('#calculateChange');
var editSelected;
var updatedTotalAmount=0;
 var currentPage = 1;
// EVENTLISTENERS
addToCart.forEach(element => {
    element.addEventListener('click',function(){
        quantityModal(element);
    });
});

addToCartConfirm.addEventListener('click',function(){
  addToCostumerCart();
});

cashBox.addEventListener('keyup',function(e){
    
   if(e.keyCode >= 48 && e.keyCode <=57 || e.keyCode == 46 || e.keyCode==8)
   {
    cashBox.value = parseFloat(cashBox.value.replace(/,/g, ''));
    cashBox.value = numberWithCommas(cashBox.value);
   }
   if(e.keyCode == 13)
   {
       showChange();
   }
   if(cashBox.value == "NaN")
   {
       cashBox.value = 0;
   }
});

quantityBox.addEventListener('keyup',function(e){
    if(e.keyCode == 13){ 
        addToCostumerCart();
    }
});

removeItemConfirm.addEventListener('click',function(){
    let subTotal = document.getElementById('totalAmount');
    totalAmount = (parseInt(totalAmount) - parseInt(finalRemove));
    subTotal.innerHTML = numberWithCommas(totalAmount);
    removeParent.remove();
    $('#removeConfirm').modal('toggle')
});

calculateChange.addEventListener('click',function(){
    
    showChange();
});

//Record Transaction
document.getElementById('save').addEventListener('click',function(){
recordTransaction();
});


//EDIT PER ITEM
document.querySelector('#submitEdit').addEventListener('click',function(){

editSelected[2].innerText = document.querySelector('#iprice').value;
editSelected[3].innerText = document.querySelector('#iquantity').value;
editSelected[4].innerText = editSelected[2].innerText * editSelected[3].innerText

$('#editItemModal').modal('toggle');
updateTotalAmount();


});

//SearchButton
document.getElementById('searchBtn').addEventListener('click',function(){
    let searchBox = document.getElementById('searchBox');
    search(searchBox.value);
})

// MAIN FUNCTIONS


function getItemInfo(e)
{
    let subParent = e.parentElement;
    let mainParent = subParent.parentElement;
    
    let idNumber = mainParent.childNodes[3].nextSibling.innerText;
    let td = mainParent.childNodes;
    // console.log(td[3].innerText);
    let item = {
      id:  td[1].innerText,
    name: td[3].innerText,
    price: td[7].innerText,
    stock: td[9].innerText
    }
    return item;
    
}
function getItemInfoSearch(e)
{
    let subParent = e.parentElement;
    let mainParent = subParent.parentElement;
    let td = mainParent.childNodes;
    console.log(td[1].innerText);
    let item = {
        id: td[0].innerText,
        name: td[1].innerText,
        price: td[3].innerText,
        stock: td[4].innerText
    }
    return item;
}

function quantityModal(e,isSearch = false)
{
    let item ;
    if(isSearch==true)
    {
        
         item = getItemInfoSearch(e);
    }
    else{
        item =  getItemInfo(e);
    }
    
    document.getElementById('quantityBox').value = null;
    globalItem = item;
    let modalTitle = document.getElementById('quantityItemName');
    modalTitle.innerHTML = item.name;

    $('#quantityModal').modal({
        show: true,
        focus:true
    })
    
    

}



function addToCostumerCart()
{
    let quantityBox = document.getElementById('quantityBox').value;
    let subTotal = document.getElementById('totalAmount');
    var table = document.getElementById("costumerTable");
    var row = table.insertRow();
    var id = row.insertCell(0);
    var name = row.insertCell(1);
    var price = row.insertCell(2);
    var quantity = row.insertCell(3);
    var perItemTotal = row.insertCell(4);
    var options = row.insertCell(5);
    id.innerHTML = globalItem.id
    name.innerHTML = globalItem.name;
    price.innerHTML = globalItem.price
    quantity.innerHTML = quantityBox;
    perItemTotal.innerHTML = globalItem.price*quantityBox;
    options.innerHTML = '<span class="fa fa-edit text-warning mr-3" role="button" id= "editItem" style="font-size:20px;"></span><span class="fa fa-trash text-danger" role="button" id="removeFromCart" style="font-size:20px;"></span>';
    removeFromCart = null;
    removeFromCart = document.querySelectorAll('#removeFromCart');
    editItem = document.querySelectorAll('#editItem');
    console.log(removeFromCart);


    removeFromCart.forEach(element => {
        element.addEventListener('click',function(){
            removeFromCartFunc(element);
        });
       
    });

    editItem.forEach(element => {
        element.addEventListener('click',function(){
            editItemFunc(element);
        });
    });

    console.log(globalItem.name);
    totalAmount = parseInt(totalAmount) + parseInt(perItemTotal.innerHTML);
    $('#quantityModal').modal('toggle')
    // 
    console.log(totalAmount);
    subTotal.innerHTML = numberWithCommas(totalAmount);
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function quantityFocus()
{
    document.getElementById("quantityBox").focus();
}

function removeFromCartFunc(e)
{
   
    let item = e.parentElement;
    let main_parent = item.parentElement;
    let children = main_parent.childNodes;
    
    
   removeParent = main_parent;
    finalRemove = children[4].innerHTML;
    console.log(finalRemove);
    // console.log("TOTAL AMOUNT :"+totalAmount);
    // main_parent.remove();
    $('#removeConfirm').modal('toggle');
}

function showChange(){
    let editItem = document.querySelectorAll('#editItem');
    let removeCart = document.querySelectorAll('#removeFromCart');
    editItem.forEach(element => {
        element.hidden = true;
    });
    removeCart.forEach(element => {
        element.hidden = true;
    });
    let change = document.querySelector('#change');
    let amount=document.querySelector('#totalAmount').innerHTML;
    let cash = document.querySelector('#cashBox').value;
    changeUnparse= parseInt(cash.replace(/,/g, '')) - parseInt(amount.replace(/,/g, ''));
    change.innerHTML = numberWithCommas(changeUnparse);
    cashBox.disabled = true;
    document.querySelector('#changeDiv').hidden = false;
}

function editItemFunc(e)
{
    let item = e.parentElement;
    let main_parent = item.parentElement;
    let children = main_parent.childNodes;
    
    document.querySelector('#iname').value = children[1].innerText;
    document.querySelector('#iprice').value = children[2].innerText;
    document.querySelector('#iquantity').value = children[3].innerText;
    editSelected = main_parent.childNodes;
    $('#editItemModal').modal('toggle');
}

function updateTotalAmount(){
    let subTotal = document.getElementById('totalAmount');
    let rows = document.querySelector('#costumerTable').rows;
    let updatedTotal=0;
 for(let i = 1; i<rows.length;i++)
 {
     updatedTotal= parseInt(updatedTotal)+parseInt(rows[i].childNodes[4].innerText);
     console.log(updatedTotal);
 }
 updatedTotalAmount = updatedTotal;
 totalAmount = updatedTotalAmount;
 subTotal.innerHTML = numberWithCommas(updatedTotalAmount);
}


function recordTransaction()
{
    let isSuccess = false;
    let loadingOverlay = document.getElementById('overlay');
    loadingOverlay.hidden = false;
    let costumerName = document.getElementById('costumerName').value;
     let receiptNumber = document.getElementById('receiptNumber').value;
    let rows = document.querySelector('#costumerTable').rows;
    if(receiptNumber!="")
{
   
    for(let i = 1; i<rows.length;i++)
 {
     let id = rows[i].childNodes[0].innerText;
     let name = rows[i].childNodes[1].innerText;
     let price = rows[i].childNodes[2].innerText;
     let quantity = rows[i].childNodes[3].innerText;
     let total = rows[i].childNodes[4].innerText;
     
    if(costumerName==="")
    {
        costumerName = 'N/A'
    }
    //  updatedTotal= parseInt(updatedTotal)+parseInt(rows[i].childNodes[4].innerText);
     console.log(name + price + quantity + total);

     //AJAX
     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $.ajax({
        type:'POST',
        url:'/recordtransaction',
        data:{
            'id':id,
            'name':name, 
              'price':price, 
              'quantity':quantity,
              'total':total,
              'costumerName':costumerName,
              'receiptNumber':receiptNumber
            },
        success:function(response){
           isSuccess = true;
            location.reload();
            console.log(response);

        },
        error: function (jqXHR, exception) {
           
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            
            alert(msg+"error");
            location.reload();
            
        },

    });

 
 if(isSuccess)
 {
    loadingOverlay.hidden = true;
 }
}
}
else
{
    loadingOverlay.hidden = true;
    alert('Receipt Number Required')
}





}


function search(searchValue)
{
    //AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $.ajax({
        type:'GET',
        url:'/pos/search',
        data:{
            
            'name': searchValue, 
             
            },
        success:function(response){
            // location.reload();
            let table = document.getElementById("itemTable");
            for(let i = table.rows.length-1; i>0 ;i--)
 {
     table.deleteRow(i);
 }
            response.forEach(element => {
              
                var table = document.getElementById("itemTable");
    var row = table.insertRow();
    var id = row.insertCell(0);
    var name = row.insertCell(1);
    var supply_price = row.insertCell(2);
    var price = row.insertCell(3)
    var stock = row.insertCell(4);
    var option = row.insertCell(5);
    
    
    id.innerHTML = element.id;
    name.innerHTML = element.name;
    supply_price.innerHTML = element.supply_price;
    price.innerHTML = element.price;
    stock.innerHTML = element.stock;
    if(element.stock>0)
    {
    option.innerHTML ='<span id="addToCart"class="fa fa-shopping-cart text-success" role="button" style="font-size:20px;"></span>'
    }
    addToCart = document.querySelectorAll('#addToCart');
    
    addToCart.forEach(element => {
        element.addEventListener('click',function(){
            quantityModal(element,true);
        });
    });
   
            });
            

        },
        error: function (jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            console.log(msg+"error");
            
        },

    });
}

function paginate(currentPage){
    let returnItem = new Array();
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$.ajax({
    type:'GET',
    url:'/pos',
    async:false,
    data:{
        
        'page': currentPage, 
         
        },
    success:function(succ){
        item = succ['data'];
        
       
        
    }
    
});
return item;
}


// document.querySelector('#tableDiv').addEventListener('scroll',function(event){
//     event.preventDefault();
//     var element = event.target;
    
//     if (element.scrollHeight - element.scrollTop === element.clientHeight)
//     {
        
//         element.scrollTop = 50;
//         currentPage++;
//       let item =  paginate(currentPage);
//     //  item.forEach(element => {
//     //      console.log(element['id']);
//     //      createNewTbody(element);
//     //  });
//     createNewTbody(item);
     
     
//     }
//     if(element.scrollTop === 0)
//     {
//         element.scrollTop = 50;
//         currentPage--;
//         let item =  paginate(currentPage);
//         item.forEach(element => {
//             console.log(element['id']);
//         });
//     }

    
    
// });

// function createNewTbody(item)
// {
//     var old_tbody = document.querySelector('#itemTable tbody');
//     var new_tbody = document.createElement('tbody');
//     item.forEach(element => {
//         var newRow = new_tbody.insertRow();

//     // Insert a cell at the end of the row
// var id = newRow.insertCell(0);
// var name = newRow.insertCell(1);
// var supp_price = newRow.insertCell(2);
// var ret_price = newRow.insertCell(3);
// var stocks = newRow.insertCell(4);
// var option = newRow.insertCell(5);

//     id.innerHTML = element['id'];
//     name.innerHTML = element['name'];
//     supp_price.innerHTML = element['supply_price'];
//     ret_price.innerHTML = element['price'];
//     stocks.innerHTML = element['stock'];
//     option.innerHTML = '<span id="addToCart"class="fa fa-shopping-cart text-success" role="button" style="font-size:20px;"></span>';

//     });
    
// // Append a text node to the cell
// // var id = document.createTextNode(item['id']);
// // newCell.appendChild(id);
// // newCell.appendChild(id);

// old_tbody.parentNode.replaceChild(new_tbody, old_tbody);


// }