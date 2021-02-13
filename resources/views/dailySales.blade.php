@extends('nav.headerNav')

@section('script')

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
                   
                    <div class="d-flex flex-row justify-content-center">
                      <div class="align-self-center mr-4">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <div class="align-self-center w-25">
                          <div class="form-group">
                            <input class= "form-control form-control-lg" id="dateSelection" type="text" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" value="{{$fromDate.' - ' .$toDate}}">
                            <div class="dropdown-menu" >
                                    <a class="dropdown-item" href="./daily">this day</a>
                                    <a class="dropdown-item" href="?days=7">last 7 days</a>
                                    <a class="dropdown-item" href="?days=30">last 30 days</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" id="for-custom">custom range</a>
                            </div>
                          </div>
                      </div>
                    </div>
                    <div class="container" id="custom" style="display: none;">
                    <div class="d-flex flex-row justify-content-center" style="display: none;">
                      <div><span>Custom Range : </span></div>
                      <div>
                        <input type="text" name="daterange" value="" />
                      </div>
                     
                      
    
                    </div>
                  </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <h3>Search Total Amount : Php. {{number_format( $overAllTotal , 0 , '.' , ',' )}} </h3>
                        <table class="table table-hover">
                          <thead class="text-warning">
                            <th>Date</th>
                            <th>Items Sold</th>
                            <th>Amount</th>
                          </thead>
                          <tbody>
                            @foreach ($dailySales as $item)
                            <tr>
                              <td>{{$item['date']}}</td>
                              <td>{{$item['itemSoldQuantity']}}</td>
                              <td>{{number_format( $item['totalAmount'] , 0 , '.' , ',' )}}</td>
                            </tr>
                            @endforeach
                           
                          </tbody>
                        </table>
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>
        </div>
        
    </div>
</div>
@endsection

@section('footerscript')
{{-- <script src={{asset('/js/jquery-ui.js')}}></script> --}}
<script src={{asset('/js/plugins/daterangepicker.min.js')}}></script>
    
<script>
  $(function() {

  $('input[name="daterange"]').daterangepicker({
    opens: 'center'
  }, function(start, end, label) {
    let startDate = start.format('YYYY-MM-DD');
    let endDate = end.format('YYYY-MM-DD');
    let url = new URL(window.location.href);

    let searchString = '/sales/daily?startdate='+startDate+"&enddate="+endDate;
    
    
    window.location = searchString;
   
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });

 $('input[name="daterange"]').on('cancel.daterangepicker',function(ev,picker){
  $('#dateSelection').prop("disabled",false);
  $('#custom').css("display","none");
 })

 $('#for-custom').click(function(){
  console.log('clicked');
  $('#dateSelection').prop("disabled",true);
   $('#custom').css("display","block");
 })


});

// document.getElementById('for-custom').addEventListener('click',function(){

//   document.getElementById('dateSelection').disabled = true;


// });

  </script>
@endsection