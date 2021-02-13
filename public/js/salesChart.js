$(document).ready(function(){

 //AJAX
 $.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});


$.ajax({
  type:'POST',
  url:'/loadDailySales',
  success:function(response){
     
    //Daily Sales Chart
     new Chartist.Line('#dailyChart', {
      labels: ['M','T','W','TH','F','S','S'],
      series: [
        [response[0], response[1], response[2], response[3], response[4], response[5],response[6]]
      ]
    }, {
      low: 0,
      showArea: true,
      width: '400px',
      height: '200px'
    });

    //Monthly Sales Chart

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


//AJAX FOR MONTHLY SALES

$.ajax({
  type:'POST',
  url:'/loadMonthlySales',
  success:function(response){
     
    //Monthly Sales Chart
console.log(response);
    var data = {
      // A labels array that can contain any sort of values
      labels: ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'],
      // Our series array that contains series objects or in this case series data arrays
      series: [
        [response[0], response[1], response[2], response[3], response[4], response[5] ,response[6], response[7], response[8] ,response[9], response[10], response[11]]
      ]
    };
    
    // As options we currently only set a static size of 300x200 px
    var options = {
      width: '400px',
      height: '200px',
      low: 0
    };
    
    // In the global name space Chartist we call the Line function to initialize a line chart. As a first parameter we pass in a selector where we would like to get our chart created. Second parameter is the actual data object and as a third parameter we pass in our options
    new Chartist.Bar('#monthlyChart', data, options);

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






    });






  
  

  
