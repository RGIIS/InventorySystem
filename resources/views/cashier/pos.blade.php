<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>POS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
      .footer {
        height: 5%;
         position: fixed;
         left: 0;
         bottom: 0;
         width: 100%;
         background-color: rgb(113, 5, 122);
         color: white;
         text-align: right;
      }
      </style>
</head>
{{-- <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css"> --}}
<link rel="stylesheet" href={{asset('/css/font-awesome.min.css')}}>
<link rel="stylesheet" href={{ asset('/css/app.css') }}>
<link rel="stylesheet" href={{ asset('/css/overlay-loading.css') }}>
<link href={{ asset('/css/material-dashboard.css?v=2.1.2') }} rel="stylesheet" />
<script src={{ asset('/js/app.js') }}></script>
<script src={{ asset('/js/pos.js') }} defer></script>
<body style="background-color: white">
  
    <div class="container-fluid">
            {{-- OVERLAY LOADING --}}
<div class="overlay" id="overlay" hidden>
  <div class="overlay__inner" >
      <div class="overlay__content" ><span class="spinner"></span></div>
  </div>
</div>
        <div class="row">
            <div class="col-lg-6 col-md-12 border-right">
                
                <div class="input-group 'no-border col-lg-12 mt-5">
                    <input type="text" id="searchBox" value="" class="form-control" placeholder="Search item...">
                    <button type="submit" id='searchBtn' class="btn btn-white btn-round btn-just-icon">
                      <i class="fa fa-search"></i>
                      <div class="ripple-container"></div>
                    </button>
                  </div>
                  <div class="card">
                    <div class="card-header card-header-primary">
                      <h4 class="card-title ">Inventory List</h4>
                    </div>
                    <div class="card-body">
                      <div id='tableDiv' class="table-responsive" style="overflow-y:auto; height:50vh !important">
                        <table class="table table-hover table-striped" id="itemTable">
                          <thead class=" text-primary">
                            <th>
                              ID
                            </th>
                            <th>
                              Name
                            </th>
                            <th>
                              Supplier Price
                            </th>
                            <th>
                              Retail Price
                            </th>
                            <th>
                              Stocks
                            </th>
                            <th>
                                Add to cart
                            </th>
                          </thead>
                          <tbody>
                            @foreach ($inventory as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->supply_price}}</td>
                                    <td>{{$item->price}}</td>
                                    <td>{{$item->stock}}</td>
                                    <td>
    
                                        <span id="addToCart"class="fa fa-shopping-cart text-success" style="font-size:20px;" role="button" @if ($item->stock < 1)
                                            hidden
                                        @endif>
                                            {{-- shopping_cart --}}
                                        </span>
                                    </td>
                                </tr>

                            @endforeach
                            
                             
                          </tbody>
                        </table>
                       
                      </div>
                      <div class="d-flex flex-row bd-highlight mt-3 justify-content-center">
                        {{-- <div>{{$inventory->links()}}</div> --}}
                       </div>
                    </div>
                  </div>
            </div>

            {{-- TOTAL HERE --}}
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header card-header-success">
                      <h4 class="card-title ">Customer Cart</h4>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-hover table-striped" id="costumerTable">
                          <thead class=" text-primary">
                            <th>
                              ID
                            </th>
                            <th>
                              Name
                            </th>
                            <th>
                              Price
                            </th>
                            <th>
                              Quantity
                            </th>
                            <th>
                                Total
                            </th>
                            <th>
                                Option
                            </th>
                          </thead>
                          <tbody >
                            {{-- @foreach ($inventory as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->price}}</td>
                                    <td>{{$item->stock}}</td>
                                    <td>
                                        <span class="material-icons text-warning mr-3" role="button">
                                            border_color
                                        </span>
                                        
                                        <span class="material-icons text-danger" role="button">
                                            cancel
                                        </span>
                                    </td>
                                </tr>

                            @endforeach --}}
                            
                             
                          </tbody>
                        </table>
                       
                      </div>
                      <div class="d-flex flex-row bd-highlight mt-3 justify-content-center">
                        {{-- <div>{{$inventory->links()}}</div> --}}
                       </div>
                    </div>
                    <div class="col-6">
                      <input style="font-size:20px;" type="text" value="" id="receiptNumber" class="form-control" placeholder="Receipt Number" required autocomplete="off">
                    </div>
                    <div class="col-6">
                     <input style="font-size:20px;" type="text" value="" id="costumerName" class="form-control" placeholder="Customer Name" autocomplete="off">
                    </div>
                    <div class="col-12">
                        <h3 class="p-2">SubTotal : <strong><span id="totalAmount">0</span></strong></h3>
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex flex-row">
                           <div><h3 class="pl-2 m-0">Cash :</h3></div> 
                            <div class="flex-grow-1 ml-2">
                                <h1><input style="font-size:30px;" type="text" value="" id="cashBox" class="form-control" placeholder="ENTER CASH" autocomplete="off"></h1>
                            </div>
                            <div>
                                <button type="submit" id='calculateChange' class="btn btn-success btn-round btn-just-icon" data-toggle="modal" data-target="#exampleModal">
                                  <i class="fa fa-mail-forward"></i>
                                    <div class="ripple-container"></div>
                                  </button>
                            </div>
                        </div>
                    </div>
                    <div class="row" id='changeDiv' hidden>
                        <div class="col-6 ml-2">
                            <h2>Change : <strong><span id="change">0</span></strong></h2>
                        </div>
                        <button type="submit" id='save' class="btn btn-success " data-toggle="modal" data-target="#exampleModal">
                            <i class="fa fa-save" style="font-size:20px"></i>
                            <div class="ripple-container"></div>
                            &nbsp; Record Transaction and Proceed
                          </button>
                    </div>
                    
                  </div>
            </div>
        </div>
        <div class="footer" style="padding:10px;">
          <p style="font-size:14px;">Cashier: {{$loginCashier->cashier_id}} {{$loginCashier->name}}</p>
          <a href="/cashier/logout" style="margin-left:20px;color:white;text-decoration:underline;">Log Out</a>
        </div>
    </div>



    {{-- MODAL --}}

    <div class="modal fade" id="quantityModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" onfocus='quantityFocus()'>
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <div class="content"> 
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-header card-header-success">
                          <h4 class="card-title" id="quantityItemName">Item_name</h4>
                        </div>
                        <div class="card-body">
                          
                            <input type="text" name="id" id="idNum" hidden></p>
                            <div class="row">
                              <div class="col-12">
                                <div class="form-group">
                                  <label class="bmd-label-floating">Quantity</label>
                                  <input type="text" id="quantityBox" class="form-control" name="quantity" autocomplete="off">
                                </div>
                              </div>
                              
                            </div>
                          
                        </div>
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success" id="addToCartConfirm" >Add to cart</button>
            </div>
          
          </div>
        </div>
      </div>


      {{-- //REMOVECONFIRM modal --}}
      <div class="modal fade" id="removeConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Remove Item From Cart</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Are you sure you want to remove this item ?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" id="removeItemConfirm" class="btn btn-danger">Remove</button>
            </div>
          </div>
        </div>
      </div>

      {{-- UPDATE --}}
<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="content">
            <div class="container-fluid">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header card-header-warning">
                      <h4 class="card-title">Update Item</h4>
                    </div>
                    <div class="card-body">
                      
                        <input type="text" name="id" id="idNum" hidden></p>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="bmd-label-floating">Item Name</label>
                              <input type="text" id="iname" class="form-control" name="iname" disabled>
                            </div>
                          </div>
                          
                        </div>
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label class="bmd-label-floating">Price</label>
                              <input type="text" id="iprice" class="form-control" name="iprice">
                            </div>
                          </div>
                          <div class="col">
                            <div class="form-group">
                              <label class="bmd-label-floating">Quantity</label>
                              <input type="text" id="iquantity" class="form-control" name="istock" autocomplete="off">
                            </div>
                          </div>
                        </div>
                      
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button id='submitEdit' type="submit" class="btn btn-warning" >Update</button>
        </div>
    
      </div>
    </div>
    

  </div>



</body>
</html>