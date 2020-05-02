app.service('fileUploadService1', function ($http, $q) {
    this.uploadFileToUrl = function (file, uploadUrl,type, data) {
        //FormData, object of key/value pair for form fields and values
        var fileFormData = new FormData();
        fileFormData.append('file', file);
        if(data){
          $.each(data, function(i, v){
            fileFormData.append(i, v);
          })
        }

        var deffered = $q.defer();
        fileFormData.append('type', type);

        
        return $http({

          url: uploadUrl,
          method: 'POST',
          data: fileFormData,
          //assign content-type as undefined, the browser
          //will assign the correct boundary for us
          headers: { 'Content-Type': undefined},
          //prevents serializing payload.  don't do it.
          transformRequest: angular.identity
        });
    }
});

//footer controller
app.controller('driver_footer', ['$scope', '$http','$rootScope', function($scope, $http,$rootScope) {
  
  $('#driver_language_footer').change(function() {
    console.log('driver_language_footer');
        $http.post(APP_URL + "/set_session", {
            language: $(this).val()
        }).then(function(data) {
            location.reload();
        });
    });

}]);

app.controller('driver_signup', ['$scope', '$http', '$timeout','fileUploadService1', function ($scope, $http, $timeout,fileUploadService1) {



initAutocomplete(); // Call Google Autocomplete Initialize Function

// Google Place Autocomplete Code
$scope.location_found = false;
$scope.autocomplete_used = false;
var autocomplete;


function initAutocomplete()
{
  autocomplete = new google.maps.places.Autocomplete(document.getElementById('driver_address'));
    autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() 
{
    $scope.autocomplete_used = true;
    fetchMapAddress(autocomplete.getPlace());
}

function fetchMapAddress(data)
{ 
  if(data['types'] == 'street_address')
    $scope.location_found = true;
  var componentForm = {
    street_number: 'short_name',
      route: 'long_name',
      sublocality_level_1: 'long_name',
      sublocality: 'long_name',
      locality: 'long_name',
      administrative_area_level_1: 'long_name',
      country: 'short_name',
      postal_code: 'short_name'

  };


    $('#address_line1').val('');
    $('#city').val('');
    $('#state').val('');    
    $('#postal_code').val('');

    var place = data;
    $scope.street_number = '';
    for (var i = 0; i < place.address_components.length; i++) 
    {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) 
      {
        var val = place.address_components[i][componentForm[addressType]];        
      if(addressType       == 'street_number')
        $scope.street_number = val;
      if(addressType       == 'route')
        var street_address = $scope.street_number+' '+val;
        $('#address_line1').val($.trim(street_address));
      if(addressType == 'sublocality_level_1' &&  $('#address_line1').val()=='')
        $('#address_line1').val(val);
      if(addressType       == 'postal_code')
        $('#postal_code').val(val);
      if(addressType       == 'locality')
        $('#city').val(val);
      if(addressType       == 'administrative_area_level_1')
        $('#state').val(val);
      if(addressType       == 'country')
        $('#country').val(val);
      }
    }


  var latitude  = place.geometry.location.lat();
  var longitude = place.geometry.location.lng();


  $('#latitude').val(latitude);
  $('#longitude').val(longitude);
}   





//profile upload

$scope.selectFile = function(){
   $("#file").click();
}


$scope.fileNameChanged = function(element)
{
  
  files = element.files; 
    if(files)
    {

      file = files[0];
      if(file)
      {
        $('.profile').addClass('loading');
        var url = getUrls('profile_pic_upload');
        var type = 'image';
        upload = fileUploadService1.uploadFileToUrl(file, url,type);
        upload.then(
          function(response){

            if(response.data.status_code == '1')
            {

              $('.profile_picture').attr('src',response.data.document_url);
              $('.flash-container').html('<div class="alert alert-success text-center col-sm-12">' + response.data.status_message + '</div>');
              $(".flash-container").fadeIn(3000);
              $(".flash-container").fadeOut(3000);
              $('.profile').removeClass('loading');
              
            }
            else
            {
              $('.flash-container').html('<div class="alert alert-danger text-center col-sm-12">' + response.data.status_message + '</div>');
              $(".flash-container").fadeIn(3000);
              $(".flash-container").fadeOut(3000);
              $('.profile').removeClass('loading');
            }
            
          }
        );
      }
    }
}


}]);


//document upload

app.controller('document_upload', ['$scope', '$http', '$timeout','fileUploadService1', function ($scope, $http, $timeout,fileUploadService1) {

//document upload

function document_upload(file,url,type){

  upload = fileUploadService1.uploadFileToUrl(file, url,type);
  upload.then(
    function(response){

      if(response.data.status_code == 1)
      {

        $('.close').trigger('click');

        if(type=='licence_back'){
          $('#doc_back').attr('src',response.data.document_url);
        }

        if(type=='licence_front'){
          $('#doc_front').attr('src',response.data.document_url);
        }

        if(type=='insurance'){
          $('#doc_insurance').attr('src',response.data.document_url);
        }

        if(type=='registeration_certificate'){
          $('#doc_registration').attr('src',response.data.document_url);
        }

        if(type=='motor_certiticate'){
          $('#doc_certificate').attr('src',response.data.document_url);
        }
        $('.flash-container').html('<div class="alert alert-success text-center col-sm-12">' + response.data.status_message + '</div>');
        $(".flash-container").fadeIn(3000);
        $(".flash-container").fadeOut(3000);
        $('.container').removeClass('loading');
        
      }
      else
      {
        $('.close').trigger('click');
        $('.flash-container').html('<div class="alert alert-danger text-center col-sm-12">' + response.data.status_message + '</div>');
        $(".flash-container").fadeIn(3000);
        $(".flash-container").fadeOut(3000);
        $('.container').removeClass('loading');
      }
      
    }
  );

}

  //license back upload

  $scope.selectDocument = function(){

    $("#document").click();

  }


  $scope.documentNameChanged = function(element)
  {
    
    files = element.files; 
      if(files)
      {

        file = files[0];
        if(file)
        {
          
          $('.container').addClass('loading');
          var url = getUrls('profile_pic_upload');
          var type = 'licence_back';
          document_upload(file,url,type);
        }
      }
  }

  //license front upload

  $scope.selectDocument1 = function(){

    $("#document_front").click();

  }


  $scope.documentNameChanged1 = function(element)
  {
    
    files = element.files; 
      if(files)
      {

        file = files[0];
        if(file)
        {
          
          $('.container').addClass('loading');
          var url = getUrls('profile_pic_upload');
          var type = 'licence_front';
          document_upload(file,url,type);
        }
      }
  }

  //license insurance upload

  $scope.selectDocument2 = function(){

    $("#document_insurance").click();

  }


  $scope.documentNameChanged2 = function(element)
  {
    
    files = element.files; 
      if(files)
      {

        file = files[0];
        if(file)
        {
          
          $('.container').addClass('loading');
          var url = getUrls('profile_pic_upload');
          var type = 'insurance';
          document_upload(file,url,type);
        }
      }
  }

  //license registration upload

  $scope.selectDocument3 = function(){

    $("#document_register").click();

  }


  $scope.documentNameChanged3 = function(element)
  {
    
    files = element.files; 
      if(files)
      {

        file = files[0];
        if(file)
        {
          
          $('.container').addClass('loading');
          var url = getUrls('profile_pic_upload');
          var type = 'registeration_certificate';
          document_upload(file,url,type);
        }
      }
  }

  //license certificate upload

  $scope.selectDocument4 = function(){
;
    $("#document_certificate").click();

  }


  $scope.documentNameChanged4 = function(element)
  {
    
    files = element.files; 
      if(files)
      {

        file = files[0];
        if(file)
        {
          
          $('.container').addClass('loading');
          var url = getUrls('profile_pic_upload');
          var type = 'motor_certiticate';
          document_upload(file,url,type);
        }
      }
  }



}]);


//payment details

app.controller('payment_detail', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {


$('.payment_detail').click(function(){

  var id    = $(this).attr('data-order_id');
  var key   = $(this).attr('data-val');
  var count = $(this).attr('data-count');

  for(var i = 0; i<count; i++){

    if(i!=key){

      $('#trip-info-'+i).removeClass('show');
    }
  }

if ($( this ).hasClass( "collapsed" ) ) 
{
   
  var url = getUrls('particular_order');

  $http.post(url,{

    order_id : id,

  }).then(function(response){

    if(response.data.status_code==1){
      $scope.trip_details = response.data.trip_details;
    }

  })
}

})


}]);

  
//invoice detail

app.controller('invoice_detail', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {

  $("#begin_date").datepicker(
    {
        dateFormat: 'yy-mm-dd',
        maxDate:-1,
        beforeShow: function(input, inst) {
        setTimeout(function() {
                inst.dpDiv.find('a.ui-state-highlight').removeClass('ui-state-highlight');
                $('.ui-state-disabled').removeAttr('title');
                $("#ui-datepicker-div td.ui-datepicker-today a.ui-state-highlight").removeClass('ui-state-highlight');
                $('.highlight').not('.ui-state-disabled').tooltip({container:'body'});
            }, 100);
      },
        onSelect: function (date) 
      {   
        
          
          var checkout = $('#begin_date').datepicker('getDate');
            checkout.setDate(checkout.getDate() + 1);
            $('#end_date').datepicker('option', 'minDate',checkout );
            $('#end_date').datepicker('setDate', checkout);  
            setTimeout(function(){
                $("#end_date").datepicker("show");
            },20);              
      },
      onChangeMonthYear: function(){
          setTimeout(function(){
              $('.highlight').not('.ui-state-disabled').tooltip({container:'body'});
          },100);  
      }
    });
    $("#end_date").datepicker(
    {
        dateFormat: 'yy-mm-dd',
        minDate:$('#begin_date').val(),
        maxDate:0,
        beforeShow: function(input, inst) {
        setTimeout(function() {
            $("#ui-datepicker-div td.ui-datepicker-today a.ui-state-highlight").removeClass('ui-state-highlight');
                $('.ui-state-disabled').removeAttr('title');
                $('.highlight').not('.ui-state-disabled').tooltip({container:'body'});
            }, 100);
      },
        onSelect: function (date) 
      {       
        
        if($('#begin_date').val() == '')
        {
          var checkout = $('#end_date').datepicker('getDate');
            checkout.setDate(checkout.getDate() - 1);

            $('#begin_date').datepicker('setDate', checkout); 
            setTimeout(function(){
                $("#begin_date").datepicker("show");
            },20);   
          }  
          getPayment();
      },
      onChangeMonthYear: function(){
          setTimeout(function(){
              $('.highlight').not('.ui-state-disabled').tooltip({container:'body'});
          },100);  
      }
    });

$('#trip_select').change(function(){
  getPayment();
})

function getPayment()
{
  
  var data = $('#trip_select').val();
  var begin_date = '';
  var end_date = '';
  begin_date = $('#begin_date').val();
  end_date = $('#end_date').val();


  var url = getUrls('invoice_filter');

  $http.post(url,{
    data        : data,
    begin_date  : begin_date,
    end_date    : end_date 

  }).then(function(response){

    if(response.data.data!='')
    {
      $('#invoice_data').find('tr').remove().end();
      $scope.trip_details = response.data.data;
      
    }
    else{
      $('#invoice_data').find('tr').remove().end();
      $('#invoice_data').append('<tr><td colspan="3"><center>No Details Found</center></td></tr>');
    }
     
  }); 

}

}]);