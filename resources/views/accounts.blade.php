@extends('nav.headerNav')

@section('script')
  <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('pagetitle')
    <a class="navbar-brand" href="javascript:;">Accounts</a>
@endsection

@section('content')


<div class="content">
    <div class="container-fluid">
      
      @if (session('msg'))
      <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          {{-- <span aria-hidden="true">&times;</span> --}}
          <i class="material-icons">close</i>
        </button>
        <span>
          <b>{{session('msg')}}</b>
        </span>
        
      </div>
      @endif
      
<div class="row">
<div class="col-md-12">
  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif



{{-- ADMIN AND INVENTORY ACCOUNT TABLE --}}
    <div class="card">
      <div class="card-header card-header-success">
        <h3 class="card-title ">Admin and Inventory Accounts Table</h3>
        <button type="submit" class="btn btn-success btn-round btn-just-icon" data-toggle="modal" data-target="#addAdminModal">
          <i class="fa fa-plus"></i>
          <div class="ripple-container"></div>
        </button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead class=" text-primary">
              <th hidden>
                ID
              </th>
              <th>
                Name
              </th>
              <th>
                User Name
              </th>
              <th>
                Account Role
              </th>
              <th>
                Created At
              </th>
              <th>
                Options
              </th>
            </thead>
            <tbody>
              
                @foreach ($accounts as $account)
                <tr>
                    <td hidden>
                      {{$account->id}}
                    </td>
                    <td>
                      {{$account->name}}
                    </td>
                    <td>
                     {{$account->username}}
                    </td>
                      <td>
                        {{$account->role}}
                      </td>
                   <td>
                     {{$account->created_at}}
                   </td>
                   <td>
                     @if ($account->role == 'inventory')
                     <button type="button" class="btn btn-outline-warning" id="editAdminBtn" data-toggle="modal" data-target="#updateAdminModal">Edit</button>
                     @endif
                    
                  
                     @if ($account->id != session('User')->id)
                     <button type="submit" class="btn btn-outline-danger" id="removeAdminBtn" data-toggle="modal" data-target="#removeAdminModal">Remove</button>
                    @else
                      Current Account
                     @endif
                      
                   
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
</div>








{{-- CASHIER ACCOUNT TABLE --}}
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-header-warning">
              <h3 class="card-title ">Cashier Accounts Table</h3>
              <button type="submit" class="btn btn-warning btn-round btn-just-icon" data-toggle="modal" data-target="#addCashierModal">
                <i class="fa fa-plus"></i>
                <div class="ripple-container"></div>
              </button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table">
                  <thead class=" text-primary">
                    <th hidden>
                      ID
                    </th>
                    <th>
                        Cashier ID
                    </th>
                    <th>
                      Name
                    </th>
                    <th>
                      Created At
                    </th>
                    <th>
                      Options
                    </th>
                  </thead>
                  <tbody>
                    
                      @foreach ($cashiers as $cashier)
                      <tr>
                          <td hidden>
                            {{$cashier->id}}
                          </td>
                          <td>
                            {{$cashier->cashier_id}}
                          </td>
                          <td>
                           {{$cashier->name}}
                          </td>
                         <td>
                           {{$cashier->created_at}}
                         </td>
                         <td>
                          <button type="button" class="btn btn-outline-warning" id="editCashierBtn" data-toggle="modal" data-target="#updateCashierModal">Edit</button>
                        
                           
                            <button type="submit" class="btn btn-outline-danger" id="removeCashierBtn">Remove</button>
                         
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
</div>



{{-- ADD ADMIN MODAL --}}

<div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="content">
            <div class="container-fluid">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header card-header-success">
                      <h4 class="card-title">Add Admin/Inventory Account</h4>
                    </div>
                    <div class="card-body">
                      <form action="/addAdmin" method="POST">
                        @csrf
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="bmd-label-floating">Name</label>
                              <input type="text" id="adminName" class="form-control" name="adminName" autocomplete="off">
                            </div>
                          </div>
                          
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="bmd-label-floating">User Name</label>
                              <input type="text" id="adminUsername" class="form-control" name="adminUsername" autocomplete="off">
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="bmd-label-floating">Password</label>
                                    <input type="password" id="adminPassword" class="form-control" name="adminPassword" autocomplete="off">
                                    <div class="input-group-append">
                                        <button id="showPassword" type="button" class="btn btn-link"><i class="fa fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                          </div>
                          <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label class="bmd-label-floating">Account Role: &nbsp;</label>
                              <input type="radio" class="btn-check" value="admin" name="role" id="adminCheck" autocomplete="off" checked>
                              <label for="#adminCheck" class="mr-2"> Admin </label>

                            <input type="radio"  class="btn-check" value="inventory" name="role" id="inventoryCheck" autocomplete="off">
                            <label for="#inventoryCheck">Inventory</label>
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
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success" >Add</button>
        </div>
      </form>
      </div>
    </div>
  </div>

{{-- ADD CASHIER MODAL --}}

  <div class="modal fade" id="addCashierModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="content">
            <div class="container-fluid">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header card-header-warning">
                      <h4 class="card-title">Add Cashier Account</h4>
                    </div>
                    <div class="card-body">
                      <form action="/addCashier" method="POST">
                        @csrf
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="bmd-label-floating">Cashier ID</label>
                              <input type="text" id="cashierID" class="form-control" name="cashierId" autocomplete="off">
                            </div>
                          </div>
                          
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="bmd-label-floating">Cashier Name</label>
                              <input type="text" id="cashierName" class="form-control" name="cashierName" autocomplete="off">
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
          <button type="submit" class="btn btn-warning" >Add</button>
        </div>
      </form>
      </div>
    </div>
  </div>

{{-- EDIT CASHIER MODAL --}}
  <div class="modal fade" id="updateCashierModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="content">
            <div class="container-fluid">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header card-header-warning">
                      <h4 class="card-title">Update Cashier Account</h4>
                    </div>
                    <div class="card-body">
                      <form action="/updateCashier" method="POST">
                        @csrf
                        <div class="row">
                          <div class="col-12">
                            <input type="text" id="updateID" class="form-control" name="id" autocomplete="off" hidden>
                            <div class="form-group">
                              {{-- <label class="bmd-label-floating">Cashier ID</label> --}}
                              <input type="text" id="updateCashierID" class="form-control" name="cashierId" autocomplete="off">
                            </div>
                          </div>
                          
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              {{-- <label class="bmd-label-floating">Cashier Name</label> --}}
                              <input type="text" id="updateCashierName" class="form-control" name="cashierName" autocomplete="off">
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
          <button type="submit" class="btn btn-warning" >Update</button>
        </div>
      </form>
      </div>
    </div>
  </div>







{{-- Edit Inventory Account --}}
<div class="modal fade" id="updateAdminModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header card-header-success">
                    <h4 class="card-title">Update Inventory Account</h4>
                  </div>
                  <div class="card-body">
                    <form action="/updateAdmin" method="POST">
                      @csrf
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <input type="text" id="uadminID" class="form-control" name="adminID" autocomplete="off" hidden>
                            <label class="bmd-label-floating">Name</label>
                            <input type="text" id="uadminName" class="form-control" name="adminName" autocomplete="off">
                          </div>
                        </div>
                        
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">User Name</label>
                            <input type="text" id="uadminUsername" class="form-control" name="adminUsername" autocomplete="off">
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="form-group">
                              <div class="input-group">
                                  <label class="bmd-label-floating">Password (Leave empty if you dont want to change password)</label>
                                  <input type="password" id="adminPassword" class="form-control" name="adminPassword" autocomplete="off">
                                  <div class="input-group-append">
                                      <button id="showPassword" type="button" class="btn btn-link"><i class="fa fa-eye"></i></button>
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
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" >Update</button>
      </div>
    </form>
    </div>
  </div>
</div>
  
{{-- REMOVE CONFIRM Modal --}}
<div class="modal fade" id="removeAdminModal" tabindex="-1" role="dialog" aria-labelledby="clearSales" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Remove Admin/Inventory Account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>You are about to permanently delete <strong>ADMIN/INVENTORY ACCOUNT</strong> You will not be able to recover once deleted. 
        <strong>This operation cannot be undone.</strong></p>

        <p>Enter current password to proceed</p>
        <input type="password" id='currentPassword'>
        <span class="text-danger" id='error' style="font-size: 12px;"></span>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="confirmRemoveBtn">Remove Account</button>
      </div>
    </div>
  </div>
</div>




</div>
</div>


@section('footerscript')
    <script src={{asset('/js/accounts.js')}}></script>

<script>
    let role;
    let showPassword = document.querySelector('#showPassword');
    let adminCheck = 

    showPassword.addEventListener('mousedown',function(){
        document.querySelector('#adminPassword').setAttribute('type','text');
    });

    showPassword.addEventListener('mouseup',function(){
        document.querySelector('#adminPassword').setAttribute('type','password');
    });

    showPassword.addEventListener('mouseleave',function(){
        document.querySelector('#adminPassword').setAttribute('type','password');
    });


    
</script>



@endsection











@endsection