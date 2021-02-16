@extends('nav.headerNav')

@section('script')
  <script src={{asset('/js/inventory.js')}} defer></script>
@endsection

@section('pagetitle')
<a class="navbar-brand" href="javascript:;">Inventory</a>
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
      @if (session('error'))
      <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          {{-- <span aria-hidden="true">&times;</span> --}}
          <i class="material-icons">close</i>
        </button>
        <span>
          <b>{{session('error')}}</b>
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
<div class="input-group 'no-border col-lg-12 mt-5">
  <input type="text" id="searchBox" value='{{$search}}' class="form-control" placeholder="Search item...">
  <button type="submit" id='searchBtn' class="btn btn-white btn-round btn-just-icon">
    <i class="fa fa-search"></i>
    <div class="ripple-container"></div>
  </button>
</div>
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title ">Inventory List</h4>
        <p class="card-category">For more organize item list follow standard naming convention :<strong> (BRAND NAME) ITEM NAME</strong></p>
        <button type="submit" class="btn btn-primary btn-round btn-just-icon" data-toggle="modal" data-target="#exampleModal">
          <i class="fa fa-plus"></i>
          <div class="ripple-container"></div>
        </button>
      </div>
      <div class="col-xl-2 col-lg-3">
        <a role="button" class="btn btn-success" href="/export/inventory" id="exportExcel"><i class="fa fa-download"></i> Export INVENTORY as excel</a>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
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
                Options
              </th>
            </thead>
            <tbody>
              
                @foreach ($inventory as $item)
                <tr>
                    <td>
                      {{$item->id}}
                    </td>
                    <td>
                      {{$item->name}}
                    </td>
                    <td>
                      Php. {{$item->supply_price}}
                    </td>
                      <td>
                        Php. {{$item->price}}
                      </td>
                   <td>
                     {{$item->stock}}
                   </td>
                   @if (session('User')->role == 'admin')
                   <td>
                    <button type="button" class="btn btn-outline-warning" id="editBtn">Edit</button>
                  
                     
                      <button type="submit" class="btn btn-outline-danger" id="removeBtn">Remove</button>
                   
                </td>
                   @endif
                   
              </tr>
                @endforeach
               
            </tbody>
          </table>
         
        </div>
        <div class="d-flex flex-row bd-highlight mt-3 justify-content-center">
          <div>{{$inventory->links()}}</div>
         </div>
      </div>
    </div>
  </div>
</div>
    </div>
</div>

    
 

{{--ADD ITEM MODAL --}}

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header card-header-primary">
                    <h4 class="card-title">Add Item To Inventory</h4>
                  </div>
                  <div class="card-body">
                    <form action="/additem" method="POST">
                      @csrf
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Item Name</label>
                            <input type="text" id="name" class="form-control" name="name" autocomplete="off">
                          </div>
                        </div>
                        
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <label class="bmd-label-floating">Supplier Price</label>
                            <input type="text" id="price" class="form-control" name="supply_price" autocomplete="off">
                          </div>
                        </div>
                        <div class="col">
                          <div class="form-group">
                            <label class="bmd-label-floating">Retail Price</label>
                            <input type="text" id="price" class="form-control" name="price" autocomplete="off">
                          </div>
                        </div>
                        <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <label class="bmd-label-floating">Initial Stock Quantity</label>
                            <input type="text" id="stock" class="form-control" name="stock" autocomplete="off">
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
        <button type="submit" class="btn btn-primary" >Add</button>
      </div>
    </form>
    </div>
  </div>
</div>




{{-- UPDATE --}}
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <form action="/updateitem" method="POST">
                      @csrf
                      <input type="text" name="id" id="idNum" hidden></p>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Item Name</label>
                            <input type="text" id="iname" class="form-control" name="iname" autocomplete="off">
                          </div>
                        </div>
                        
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <label class="bmd-label-floating">Supplier Price</label>
                            <input type="text" id="isupply_price" class="form-control" name="isupply_price" autocomplete="off">
                          </div>
                        </div>
                        <div class="col">
                          <div class="form-group">
                            <label class="bmd-label-floating">Price</label>
                            <input type="text" id="iprice" class="form-control" name="iprice" autocomplete="off">
                          </div>
                        </div>
                        <div class="col">
                          <div class="form-group">
                            <label class="bmd-label-floating">Initial Stock Quantity</label>
                            <input type="text" id="istock" class="form-control" name="istock" autocomplete="off">
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


{{-- REMOVE --}}
<div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Remove Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/removeitem" method="POST">
        @csrf
      <div class="modal-body">
        <h4> Are you sure you want to remove <strong><p id="removeTitle">Item  ? </p> </strong></h4>
      </div>
      <input type="text" name="remId" id="remId" hidden>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Remove</button>
      </div>
      </form>
    </div>
  </div>
</div>


@endsection