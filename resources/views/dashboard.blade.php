@extends('nav.headerNav')
@section('script')
<script src={{asset('/js/plugins/chartist.min.js')}}></script>
<link rel="stylesheet" href={{asset('/css/chartist.min.css')}}>
<style>
  ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: white !important;
  opacity: 1; /* Firefox */
}
</style>
@endsection

@section('pagetitle')
<a class="navbar-brand" href="javascript:;">Dashboard</a>
@endsection


@section('content')
    
        <div class="content">
          <div class="container-fluid">
            @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{session('message')}}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            @endif
            <div class="row">
              <div class="col-lg-4 col-md-8 col-sm-8">
                <div class="card card-stats">
                  <div class="card-header card-header-warning card-header-icon">
                    <div class="card-icon">
                      <i class="fa fa-dropbox"></i>
                    </div>
                    <p class="card-category">Inventory</p>
                    <h3 class="card-title">{{$itemCount}}
                      <small>Items</small>
                    </h3>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                      Today
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-8 col-sm-8">
                <div class="card card-stats">
                  <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                      <i class="fa fa-shopping-basket"></i>
                    </div>
                    <p class="card-category">Sold Items</p>
                    <h3 class="card-title">{{$itemSoldCount}}</h3>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                      Today
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-8 col-sm-8">
                <div class="card card-stats">
                  <div class="card-header card-header-danger card-header-icon">
                    <div class="card-icon">
                      <i class="fa fa-exclamation-circle"></i>
                    </div>
                    <p class="card-category">Out of stock</p>
                    <h3 class="card-title">{{$outOfStockCount}}</h3>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                      Today
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 col-md-12">
                <div class="card card-chart">
                  <div class="card-header card-header-success">
                    <div class="ct-chart" id="dailyChart"></div>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title">Today's Sales</h4>
                    <p class="card-category">
                      <h2 class="text-success">Php. {{number_format( $dailySales , 0 , '.' , ',' )}}</h2></p>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                      <p>Yesterdays sales : Php. {{number_format( $yesterdaySales , 0 , '.' , ',' )}}</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-md-12">
                <div class="card card-chart">
                  <div class="card-header card-header-warning">
                    <div class="ct-chart" id="monthlyChart"></div>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title">Monthly Sales</h4>
                    <p class="card-category">
                    <h2 class="text-warning">Php. {{number_format( $thisMonthSales , 0 , '.' , ',' )}}</h2></p>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                     <p>Last Month Sales : Php. {{number_format( $lastMonthSales , 0 , '.' , ',' )}}</p>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-lg-4 col-md-12">
                <div class="card card-chart">
                  <div class="card-header card-header-danger">
                    
                  </div>
                  <div class="card-body">
                    <h4 class="card-title">Out of stock items</h4>
                    <p class="card-category">
                      <ul class="list-group">
                        @if ($outOfStockItem->count() > 0)
                          @foreach ($outOfStockItem as $item)          
                            <li class="list-group-item text-danger">{{$item->name}}</li>
                          @endforeach
                        @else
                          <li class="list-group-item">No Data Found </li>
                        @endif
                        
                     
                    </ul>
                  </p>
                  </div>
                  {{-- <div class="card-footer">
                    <div class="stats">
                      <i class="material-icons">access_time</i> campaign sent 2 days ago
                    </div>
                  </div> --}}
                </div>
              </div>
              <div class="col-lg-8 col-md-12">
                <div class="card">
                  <div class="card-header card-header-warning">
                    <h4 class="card-title">Sold Items</h4>
                    <p class="card-category">quick view of sold items</p>
                    <form action="" class="form-inline">
                    <div class="row">
                      <div class="col mt-2 pr-0"><h4>Sort By : </h4></div>
                      <div class="col pl-1">
                        <select id="sortSelect" class="form-control text-light px-2 py-0" style="font-size:15px">
                          <option class="text-dark" value="customer">Customer Name</option>
                          <option class="text-dark" value="receipt">Receipt #</option>
                        </select>
                      </div>
                    </div>
                  </form>
                  <div class="row mt-2">
                    <div class="col">
                      <input type="text" id="searchSold" class="form-control text-light" name="searchSold" autocomplete="off" style="font-size:15px" placeholder="Enter Receipt #/Customer Name">
                    </div>
                    <div class="col">
                      <button id="sortBtn" type="button" class="btn btn-success btn-sm ml-0">Sort</button>
                    
                      <a href="/dashboard" id="clearSortBtn" type="button" class="btn btn-danger btn-sm ml-0">Clear sort</a>
                    </div>
                    

                  </div>
                    
                  
                  </div>
                 
                  <div class="card-body table-responsive">
                    <table class="table table-hover">
                      <thead class="text-warning">
                        <th>Sold ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Customer</th>
                        <th>Receipt #</th>
                        <th>Cashier</th>
                      </thead>
                      <tbody>
                        @foreach ($itemSold as $item)
                        <tr>
                            <td>
                              {{$item->id}}
                            </td>
                            <td>
                              {{$item->item_name}}
                            </td>
                            <td>
                              {{number_format( $item->price , 0 , '.' , ',' )}}
                            </td>
                            <td>
                              {{$item->quantity}}
                            </td>
                            <td>
                              {{number_format( $item->total , 0 , '.' , ',' )}}
                            </td>
                            <td>
                              {{$item->costumer_name}}
                            </td>
                            <td>
                              {{$item->receipt_number}}
                            </td>
                            <td>
                              {{$item->cashier}}
                            </td>
                            
                        </tr>
                            
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <div class="d-flex flex-row bd-highlight mt-3 justify-content-center">
                    <div>{{$itemSold->links()}}</div>
                   </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--   Core JS Files   -->
   
@endsection

@section('footerscript')
        <script src={{asset('/js/salesChart.js')}}></script>

<script>
  let sortSelect = document.getElementById('sortSelect');
  document.getElementById('sortBtn').addEventListener('click',function(){

    console.log(sortSelect.value);
    if(sortSelect.value==='receipt')
    {
      let searchString = '/dashboard?receipt='
      let searchQuery = document.getElementById('searchSold').value;
    window.location = searchString+searchQuery;
    }
    else{
      let searchString = '/dashboard?customer='
      let searchQuery = document.getElementById('searchSold').value;
    window.location = searchString+searchQuery;
    }

  })
</script>


@endsection