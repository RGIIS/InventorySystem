let editBtn = document.querySelectorAll("#editBtn");
let removeBtn = document.querySelectorAll("#removeBtn");
let searchBtn = document.getElementById('searchBtn');
let searchBox = document.getElementById('searchBox');

editBtn.forEach(element => {
   element.addEventListener('click',function(){
       update(element)});
});

removeBtn.forEach(element => {
    element.addEventListener('click',function(){
        remove(element)});
});

searchBtn.addEventListener('click',function(){
search();
});

searchBox.addEventListener('keyup',function(e){

    if(e.keyCode == 13)
    {
        search();
    }
    if(e.keyCode==8 && searchBox.value=='')
    {
        console.log('reload');
       search();
    }

});


function idNumber(e)
{
    let subParent = e.parentElement;
    let mainParent = subParent.parentElement;
    let idNumber = mainParent.childNodes[3].nextSibling.innerText;
    let td = mainParent.childNodes;
    let item = {
      id:  td[1].innerText,
    name: td[3].innerText,
    supply_price: td[5].innerText,
    price: td[7].innerText,
    stock: td[9].innerText
    }
    return item
    
}


function update(e)
{
    let id = document.getElementById('idNum');
    let name = document.getElementById('iname');
    let price = document.getElementById('iprice');
    let stock = document.getElementById('istock');
    let supplyPrice = document.getElementById('isupply_price');
    let item = idNumber(e);
    
    id.value = item.id;
    name.value = item.name;
    supplyPrice.value = item.supply_price.substring(4,item.supply_price.length);
    price.value = item.price.substring(4,item.price.length);
    stock.value = item.stock;


    $('#updateModal').modal('show')
}

function remove(e)
{
    let item = idNumber(e);
   document.getElementById('remId').value = item.id;
   document.getElementById('removeTitle').innerHTML = item.name;
   $('#removeModal').modal('show')
   
}

function search(e)
{

    let hostname = window.location.hostname;
    let searchString = '/inventory?search='
    let searchQuery = document.getElementById('searchBox').value;
    
    window.location = searchString+searchQuery;
    
}

