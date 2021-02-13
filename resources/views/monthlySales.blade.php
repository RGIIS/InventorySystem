@extends('nav.headerNav')


@section('script')

    <script src={{asset('/js/plugins/chartist.min.js')}}></script>
    <link rel="stylesheet" href={{asset('/css/chartist.min.css')}}>
    <link rel="stylesheet" href={{asset('/css/bootstrap-datepicker.css')}}>

    <style>
      :root {
    --background-color: #fff;
    --border-color: #ccc;
    --text-color: #555;
    --selected-text-color: rgb(56, 241, 164);
    --hover-background-color: #eee;
}

.yearpicker-container {
    position: absolute;
    color: var(--text-color);
    width: 280px;
    border: 1px solid var(--border-color);
    border-radius: 3px;
    font-size: 1rem;
    box-shadow: 1px 1px 8px 0px rgba(0, 0, 0, 0.2);
    background-color: var(--background-color);
    z-index: 10;
    margin-top: 0.2rem;
}

.yearpicker-header {
    display: flex;
    width: 100%;
    height: 2.5rem;
    border-bottom: 1px solid var(--border-color);
    align-items: center;
    justify-content: space-around;
}

.yearpicker-prev,
.yearpicker-next {
    cursor: pointer;
    font-size: 2rem;
}

.yearpicker-prev:hover,
.yearpicker-next:hover {
    color: var(--selected-text-color);
}

.yearpicker-year {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 0.5rem;
}

.yearpicker-items {
    list-style: none;
    padding: 1rem 0.5rem;
    flex: 0 0 33.3%;
    width: 100%;
}

.yearpicker-items:hover {
    background-color: var(--hover-background-color);
    color: var(--selected-text-color);
    cursor: pointer;
}

.yearpicker-items.selected {
    color: var(--selected-text-color);
}

.hide {
    display: none;
}

.yearpicker-items.disabled {
    pointer-events: none;
    color: #bbb;
}
    </style>
    
@endsection

@section('pagetitle')
      <a class="navbar-brand" href="javascript:;">Monthly Sales</a>
@endsection


@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-chart">
                  <div class="card-header card-header-info">
                    <h4 class="card-title">Monthly Sales</h4>
                  </div>
                  <div class="card-body">
                   
                    <div class="d-flex flex-row justify-content-center">
                     
                      <div class="align-self-center mr-4">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <div class="align-self-center">
                        Select Year: <input id="yearPicker" type="text" class="yearpicker form-control" value="" />
                      </div>

                      

                      
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <table class="table table-hover">
                          <thead class="text-warning">
                            <th>Date</th>
                            <th>Items Sold</th>
                            <th>Amount</th>
                          </thead>
                          <tbody>
                            @foreach ($monthlyArray as $item)
                                <tr>
                                  <td>{{$item['monthName']}}</td>
                                  <td>{{$item['itemSoldQuantity']}}</td>
                                  <td>Php. {{number_format( $item['totalAmount'] , 0 , '.' , ',' )}}</td>
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
    <script src={{asset("/js/yearpicker.js")}}></script>
   
    <script>
      let d = new Date();
      let nowYear = d.getFullYear();
      let paramYear ;
      let url = window.location.href;

      if(url.includes('?')){
          let queryString = window.location.search;
          let urlParams = new URLSearchParams(queryString);
          paramYear = parseInt(urlParams.get('year'));
        
          
      }
      else
      {
          paramYear = nowYear;
      }

    $(document).ready(function() {
      $(".yearpicker").yearpicker({
        year: paramYear,
        startYear: 2015,
        endYear: nowYear
      });
      $('#yearPicker').change(function()
    {
      let searchString = '/sales/monthly?year='
    let searchQuery = document.getElementById('yearPicker').value;

    window.location = searchString+searchQuery;
    })
    });
    
    </script>
     <script src={{asset("/js/monthlySales.js")}}></script>
      
@endsection