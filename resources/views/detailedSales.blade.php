@extends('nav.headerNav')

@section('script')
<meta name="csrf-token" content="{{ csrf_token() }}" />
{{-- <link rel="stylesheet" href={{asset('/css/jquery-ui.css')}}> --}}
<link rel="stylesheet" href={{asset('/css/daterangepicker.css')}}>


@endsection

@section('pagetitle')
    <a class="navbar-brand" href="javascript:;">Daily Sales</a>
@endsection

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-chart">
                  <div class="card-header card-header-info">
                    <h4 class="card-title">Daily Sales</h4>
                  </div>
                  <div class="card-body">
                   
                    <div class="container">
                        <div class="d-flex flex-row justify-content-center">
                            <div><span>Custom Range : </span></div>
                            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 10px; padding-top:10px; border: 1px solid #ccc; width: 100%">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2 justify-content-md-center">
                      <div class="col-md-auto pr-0 mx-auto">
                        <div class="input-group">
                          <div class="dropdown">
                            
                              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Filter By
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#" id="sortCustomer">Customer Name</a>
                                <a class="dropdown-item" href="#" id="sortReceipt">Receipt Number</a>
                                <a class="dropdown-item" href="#" id="sortCashier">Cashier</a>
                              </div>
                            
                          </div>
                          <div class="input-group-append">
                          <input type="text" class="form-control  mt-2 p-3" id="inputSort" placeholder="Input Filter">
                        </div>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-primary ml-0"  id="sortBtn" disabled>Submit</button>
                        </div>
                        </div>
                      </div>

                    </div>


                    <div class="row mt-5">
                      <div class="col-xl-2 col-lg-3 pr-0">
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#clearSalesModal" id="clearSales"><i class="fa fa-trash"></i> Clear Sales</button>
                      </div>
                      <div class="col-xl-2 col-lg-3 pl-0">
                        <button type="button" class="btn btn-warning"  id="removeSelected"><i class="fa fa-remove"></i> Remove Selected</button>
                      </div>
                      <div class="col-xl-2 col-lg-3">
                        <button type="button" class="btn btn-success" id="importExcel" onclick="exportSales()"><i class="fa fa-download"></i> Export as excel</button>
                      </div>
                    </div>

                    
                    <div class="row">
                      <div class="col-xl-1 pr-0"><span>Filters: </span></div>
                      <div class="col-xl-11">
                        <div class="input-group">
                          <button type="button" id="customerTag" class="btn btn-secondary" onclick="removeParam('customer')" hidden>
                            Customer &nbsp;<a class="badge badge-light"><i class="fa fa-close"></i></a>
                          </button>
                          <div class="input-group-append">
                            <button type="button" id="receiptTag" class="btn btn-secondary" id="badgeCustomer" onclick="removeParam('receipt')" hidden>
                              Receipt &nbsp;<a class="badge badge-light"><i class="fa fa-close"></i></a>
                            </button>
                          </div>
                          <div class="input-group-append">
                            <button type="button" id="cashierTag" class="btn btn-secondary" id="badgeCustomer" onclick="removeParam('cashier')" hidden>
                              Cashier &nbsp;<a class="badge badge-light"><i class="fa fa-close"></i></a>
                            </button>
                          </div>
                        </div>
                        
                        </div>
                     
                    </div>


                    <div class="row">
                      <div class="col-lg-12">
                        {{-- <h3>Search Total Amount : Php. {{number_format( $overAllTotal , 0 , '.' , ',' )}} </h3> --}}
                        <table class="table table-hover">
                          <thead class="text-warning">
                            <th><button id="markAll" type="button" class="btn btn-outline-info p-2" value="Mark All">Mark All</button></th>
                            <th hidden>ID</th>
                            <th>Date</th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Customer Name</th>
                            <th>Receipt Number</th>
                            <th>Cashier</th>
                          </thead>
                          <tbody>
                          @foreach ($itemSold as $item)
                              <tr>
                                <td><input type="checkbox"></td>
                                <td hidden>{{$item->id}}</td>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->item_name}}</td>
                                <td>{{$item->price}}</td>
                                <td>{{$item->quantity}}</td>
                                <td>{{$item->total}}</td>
                                <td>{{$item->costumer_name}}</td>
                                <td>{{$item->receipt_number}}</td>
                                <td>{{$item->cashier}}</td>
                              </tr>
                          @endforeach
                           
                          </tbody>
                        </table>
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
</div>

<!-- Modal -->
<div class="modal fade" id="clearSalesModal" tabindex="-1" role="dialog" aria-labelledby="clearSales" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Clear Sales</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>You are about to permanently delete <strong>ALL OF SALES DATA.</strong> You will not be able to recover once deleted. 
        <strong>This operation cannot be undone.</strong></p>

        <p>Type <strong class="bg-danger text-white">CONFIRM</strong> to proceed</p>
        <input type="text" id="confirmBox">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="clearSaleBtn" disabled>Clear Sales</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('footerscript')
{{-- <script src={{asset('/js/jquery-ui.js')}}></script> --}}
<script src={{asset('/js/plugins/daterangepicker.min.js')}}></script>
<script src={{asset('/js/detailed-sales.js')}}></script>
<script type="text/javascript">
    $(function() {
    
      var url_string = window.location.href;
      var url = new URL(url_string);
      var startDate = url.searchParams.get("startDate");
      var endDate = url.searchParams.get("endDate");
      var start = moment();
      var end = moment();
     if(startDate!=null || endDate!=null)
     {
       start = moment(startDate);
       end = moment(endDate);
     }
     
      
    
        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
    
        cb(start, end);

        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
            if(picker.startDate.format('YYYY-MM-DD')==moment().format('YYYY-MM-DD')&&picker.endDate.format('YYYY-MM-DD')==moment().format('YYYY-MM-DD')){
                window.location = window.location.href.split("?")[0];
            }
            else
            {
                console.log(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));

                let searchString = '/sales/detailed?startDate='+picker.startDate.format('YYYY-MM-DD')+'&endDate='+picker.endDate.format('YYYY-MM-DD');
               
    
                window.location = searchString;
            }
      
  });

  if(location.href.includes('customer='))
  {
  $('#customerTag').prop('hidden',false);
  }

  if(location.href.includes('receipt='))
  {
  $('#receiptTag').prop('hidden',false);
  }

  if(location.href.includes('cashier='))
  {
  $('#cashierTag').prop('hidden',false);
  }
    
    });

    document.querySelector('#markAll').addEventListener('click',function(ev)
    {
      var checkBoxes = document.querySelectorAll('input[type=checkbox]');
      if(checkBoxes.length > 0)
      {
      if(this.value==='Mark All')
      {
      this.innerHTML = 'Unmark All';
      this.value = 'Unmark All'
      
      checkBoxes.forEach(element => {
        element.checked = true;
      });
      }
      else
      {
        this.value = 'Mark All';
        this.innerHTML = 'Mark All';
        checkBoxes.forEach(element => {
        element.checked = false;
      });
      }
    }
      
    });

function exportSales()
{
  let url = window.location.href.toString();
  let index = url.indexOf('?');
  let parameter = '';
  if(index>0)
  {
 parameter = url.slice(index,url.length);
  }

  let path = '/download'+parameter;
  location.replace(path);
}

    </script>
@endsection