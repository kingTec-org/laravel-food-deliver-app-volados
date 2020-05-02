lang = $("html").attr('lang');
rtl = false;
if(lang  == 'ar') {
 rtl = true;
}
$('#choose_file').click(function(){
  $('#profile_photo').trigger('click');
});

$(window).on('load', function() {
  $('.rest_prof').removeClass('loading');
});

function refreshSelect() {
  setTimeout(function(){ 
    $('select').selectpicker('refresh');
  }, 1);
}

$(document).ready(function () {
  $('#schedule_button').css('display','none');
  $('#count_card').addClass('text-hide');
  $('.scroll-top').hide();
  var banner_height = $('.main-banner').outerHeight();
  var header_height = $('header').outerHeight();

  $(window).scroll(function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 120) {
      $("header").addClass("active");
      $('.scroll-top').show();
    } 

    else {
      $("header").removeClass("active");
      $('.scroll-top').hide();
    }

    if (scroll >= banner_height - header_height - 100) {
      $("header").addClass("show-header-search");
    }

    else {
      $("header").removeClass("show-header-search");
    }

    $('.pac-container').hide();
  });

  function header_fixed() {
    var a = $('header').outerHeight();
    $('main').css({ "margin-top": a + "px" });
    $('main').css({ "opacity": 1 });
  }

  header_fixed();

  $(window).scroll(function () {
    header_fixed();
  });

  $(window).resize(function () {
    header_fixed();
  });  
});

//country code drop down value set
var select = document.getElementById('phone_code');
if($('#phone_code').length) {
  select.onchange = function() {
   $('.phone_code').text('+'+select.value);
 }
}

$(document).ready(function() {
  setTimeout(function() {
    $('#schedule_button').css('display','block');
    $('#count_card').removeClass('text-hide');
    setTimeout(function(){ 
      $('select').selectpicker('refresh');
    }, 100);
  },100);
})

$(document).mouseup(function(e) {
  var container = $(".tooltip-content");
  var container1 = $(".schedule-dropdown");

      // if the target of the click isn't the container nor a descendant of the container
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();
      }
       // if the target of the click isn't the container nor a descendant of the container
       if (!container1.is(e.target) && container1.has(e.target).length === 0) {
        container1.hide();
        $('.schedule-popup').removeClass('d-flex');
      }
    });

app.filter('checkTimeInDay', ["$filter", function($filter) {
  return function(time, date) {

    var date1 = new Date();
    var current_date = $filter('date')(date1, "yyyy-MM-dd");
    var current_time = $filter('date')(date1, "HH:mm:ss");
    if(current_date==date)
    {
      if(time > current_time)
        return true;
      else
        return false;
    }
    return true;
  };

}])

//location not found

app.controller('location_not_found', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {
  history.pushState(null, null, location.href);
  window.onpopstate = function () {
    history.go(1);
  };
  $(document).ready(function(){

    $('.toogle_modal1').trigger('click');

  });

}]);

//footer controller
app.controller('footer', ['$scope', '$http','$rootScope', function($scope, $http,$rootScope) {

  $('#language_footer').change(function() {

    $http.post(APP_URL + "/set_session", {
      language: $(this).val()
    }).then(function(data) {
      location.reload();
    });
  });

}]);

app.controller('store_dashboard', ['$scope','$http','$timeout','$filter',function($scope,$http,$timeout,$filter) {

  $scope.show_comments = function(id){ 
    var url= getUrls('show_comments');
    var comments = $('#comments_'+id).val();
    $http.post(url,{
      comments  : comments
    }).then(function(response){
      $('.comment_list').html('');
      $('#comments_modal').modal();
      $('.comment_list').html(response.data);
    });
  }
}]);


app.controller('store_side_bar', ['$scope','$http','$timeout','$filter',function($scope,$http,$timeout,$filter) {

 $(document).on('change', ".status_check", function(){

  var status;
  if($(this).is(":checked")){
    status = 1;
  }
  else{
    status = 0;
  }
  if(status==1){
    $('.store_status_avai').removeClass('d-none');
    $('.store_status_unavai').addClass('d-none');
  } else {
    $('.store_status_avai').addClass('d-none');
    $('.store_status_unavai').removeClass('d-none');
  }
  var url= getUrls('status_update');

  $http.post(url,{
    status : status
  }).then(function(response){

  });
})
}]);


//header controller

app.controller('header_controller', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {

  var data1 = JSON.parse($('#orderdata').val());
  $scope.$watch('order_data', function() {
    if($scope.other_store == 'no') {
      if($scope.order_data.total_item_count > 0) {
        $('.icon-shopping-bag-1').addClass('active');
      }
      else {
        $('.icon-shopping-bag-1').removeClass('active');
      }
      $('#count_card').text($scope.order_data ? $scope.order_data.total_item_count:'');
    }
  });

  $('.schedule-option').click(function(){
    var status = $(this).attr('data-val');

    $scope.schedule_status_clone = status;
    $scope.schedule_status = Lang.get('js_messages.store.'+status);
    $scope.$apply();
    if($scope.schedule_status_clone=='ASAP'){

      var url = getUrls('schedule_store');
      date ='';
      time = '';
      $http.post(url,{
        status   : $scope.schedule_status_clone,
        date     : date,
        time     : time,
      }).then(function(response){
          // $('#schedule_button').trigger('click');
          location.reload();
        });
    }
  });

  $('#card_page').click(function(){

    var count = $('#count_card').text();

    if(count=='' || count==null){

      event.preventDefault();
    }

  });


  $('#set_time').click(function(){
    if($('#count_card').text() > 0)
      $('#schedule_modal').modal();
    else
      $('.schedule_modal').trigger("click");
  });
  $('.schedule_modal').click(function(){

    var date = $('#schedule_date').val();
    var time = $('#schedule_time').val();
    var schedule_session=$('#schedule_status_session').val();
    var url = getUrls('schedule_store');
    $http.post(url,{
      status   : $scope.schedule_status_clone ? $scope.schedule_status_clone : schedule_session,
      date     : date,
      time     : time,
    }).then(function(response){

      // $('#schedule_button').trigger('click');
      location.reload();

    });
  });
  $('#set_time1').click(function(){
    $('#schedule-modal').modal('hide');
    if($('#count_card').text() > 0)
      $('#schedule_modal1').modal();
    else
      $('.schedule_modal1').trigger("click");
  });

  $('.schedule_modal1').click(function(){

    var status = 'Schedule';
    var date = $('#schedule_date').val();
    var time = $('#schedule_time').val();   
    var url = getUrls('schedule_store');
    $http.post(url,{
      status   : status,
      date     : date,
      time     : time,
    }).then(function(response){

      if(response.data.schedule_data.status=='Schedule'){
        $scope.status = response.data.schedule_data.status;
        $scope.date= response.data.schedule_data.date;
        $scope.time= response.data.schedule_data.time;
        var data2 = $scope.date+' '+$scope.time;
        $('#possible').css('display','none');
          //$('#schedule2').text(data2);
          $('#date1').text($scope.date);
          $('#time1').text($scope.time);

        }
        $('.icon-close-2').trigger('click');
        location.reload();

      });
  });



  //search page google search

  var autocompletes;
  initAutocompletes();

  var autocompletes_mob;
  initAutocompletesMob();

  function initAutocompletes() {
    autocompletes = new google.maps.places.Autocomplete(document.getElementById('location_search'),{ types: ['geocode'] });
    autocompletes.addListener('place_changed', fillInAddress1);
  }
  function initAutocompletesMob() {
    autocompletes_mob = new google.maps.places.Autocomplete(document.getElementById('location_search_mob'),{ types: ['geocode'] });
    autocompletes_mob.addListener('place_changed', fillInAddress2);
  }

  function fillInAddress1() {
    fetchMapAddress1(autocompletes.getPlace());
  }
  function fillInAddress2() {
    fetchMapAddress1(autocompletes_mob.getPlace());
  }

  function fetchMapAddress1(data) {

   var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    sublocality_level_1: 'long_name',
    sublocality_level_2: 'long_name',
    sublocality_level_3: 'long_name',
    sublocality: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'long_name',
    country: 'short_name',
    postal_code: 'short_name'
  };

  $scope.postal_code = '';
  $scope.city = '';
  $scope.latitude = '';
  $scope.longitude = '';
  $scope.street_address = '';
  $scope.locality = '';

  var place = data;
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm[addressType]) {
      var val = place.address_components[i][componentForm[addressType]];
      if (addressType == 'postal_code') $scope.postal_code = val;
      if (addressType == 'locality') $scope.city = val;

      if (addressType == 'locality') $scope.city = val;

      if (addressType == 'sublocality_level_1' && $scope.locality == '') 
        $scope.locality = val;
      else if (addressType == 'sublocality' && $scope.locality == '') 
        $scope.locality = val;
      else if (addressType == 'locality' && $scope.locality == '') 
        $scope.locality = val;
      else if(addressType == 'administrative_area_level_1' && $scope.locality == '') 
        $scope.locality = val;
      else if(addressType  == 'country' && $scope.locality == '') 
        $scope.locality = place.address_components[i]['long_name'];


      if(addressType       == 'street_number')
        $scope.street_address = val;
      if(addressType       == 'route')
        $scope.street_address = $scope.street_address+' '+val;
      if(addressType       == 'country')
        $scope.country = val;
      if(addressType       == 'administrative_area_level_1')
        $scope.state = val;

      $('#city_change').text($scope.city);
    }
  }

  $scope.latitude = place.geometry.location.lat();
  $scope.longitude = place.geometry.location.lng();
  $scope.is_auto_complete = 1;

        //store session data
        var url_search = getUrls('store_location');
        var location_val = $('#location_search').val();
        $scope.location = location_val;
        $scope.street_address = ($scope.street_address)?$scope.street_address:$scope.city;
        $http.post(url_search,{
          postal_code: $scope.postal_code,
          city       : $scope.city,
          address1   : $scope.street_address,
          latitude   : $scope.latitude,
          longitude  : $scope.longitude,
          state      : $scope.state,
          country    : $scope.country,
          location   : $scope.location,
          locality   : $scope.locality,
        }).then(function(response){
          $('#city_change').text($scope.city);
          $('#location_search').val($scope.locality);
          $('.location_error_msg').addClass('d-none');
          var url = getUrls('search');
          window.location.href = url ;
        });
      }

    }]);

//forget password for eater

app.controller('forget_password', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {

  $('#password_change').click(function(event){
    event.preventDefault();

    var password          = $('#password').val();
    var confirm_password  = $('#confirm_password').val();
    var id                = $('#user_id').val();

    if(password==confirm_password){

      $('#password_error').css('display','none');

      var url = getUrls('password_change');

      $http.post(url,{
        id        : id,
        password  : password,
      }).then(function(response){

        if(response.data.success=='true'){

          var url1 = getUrls('search');
          window.location.href = url1;
        }
      })
    }

    else
    {
      $('#password_error').css('display','block');
    }

  });
}]);

//forget password for store
app.controller('forgot_password', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {
  $('#password_change').click(function(event){
    event.preventDefault();

    var password          = $('#password').val();
    var confirm_password  = $('#confirm_password').val();
    var id                = $('#user_id').val();

    if(password.length < 6 && password!=''){
      $('#password_count').css('display','block');
      $('#password_required').css('display','none');
      return false;
    }

    if(password==''){
      $('#password_required').css('display','block');
      $('#password_count').css('display','none');
      $('#password_error').css('display','none');
      return false;
    }

    if(password==confirm_password){

      $('#password_error').css('display','none');
      $('#password_required').css('display','none');
      $('#password_count').css('display','none');

      var url = getUrls('change_password');

      $http.post(url,{
        id        : id,
        password  : password,
      }).then(function(response){
        if(response.data.success=='true'){

          var url1 = getUrls('dasboard');
          var user_id = '?user_details='+response.data.data.id;
          window.location.href = url1+user_id;
        }
      })

    }
    else{
     $('#password_error').css('display','block');
     $('#password_required').css('display','none');
     $('#password_count').css('display','none');
   }
 });
}]);

//user details controller

app.controller('user_data', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {
  //user address

  var autocompletes;
  initAutocompletes();

  function initAutocompletes() {
    autocompletes = new google.maps.places.Autocomplete(document.getElementById('user_address'),{ types: ['geocode'] });
    autocompletes.addListener('place_changed', fillInAddress1);
  }

  function fillInAddress1() {
    fetchMapAddress1(autocompletes.getPlace());
  }

  function fetchMapAddress1(data) {

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

  $scope.postal_code = '';
  $scope.city = '';
  $scope.latitude = '';
  $scope.longitude = '';

  var place = data;
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm[addressType]) {
      var val = place.address_components[i][componentForm[addressType]];
      if (addressType == 'postal_code') $scope.postal_code = val;
      if (addressType == 'locality') $scope.city = val;
      if(addressType       == 'street_number')
        $scope.street_address = val;
      if(addressType       == 'route')
        $scope.street_address = $scope.street_address+' '+val;
      if(addressType       == 'country')
        $scope.country = val;
      if(addressType       == 'administrative_area_level_1')
        $scope.state = val;
    }
  }

  $scope.latitude = place.geometry.location.lat();
  $scope.longitude = place.geometry.location.lng();
  $scope.is_auto_complete = 1;
  $('#user_city').val($scope.city);
  $('#user_street').val($scope.street_address);
  $('#user_state').val($scope.state);
  $('#user_country').val($scope.country);
  $('#user_postal_code').val($scope.postal_code);
  $('#user_latitude').val($scope.latitude);
  $('#user_longitude').val($scope.longitude);
}
}]);

// home page controller
app.controller('home_page', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {
    //header search

    var autocompletes;
    initAutocompletes();

    function initAutocompletes() {
      autocompletes = new google.maps.places.Autocomplete(document.getElementById('header_location_val'),{ types: ['geocode'] });
      autocompletes.addListener('place_changed', fillInAddress1);
    }

    function fillInAddress1() {
      fetchMapAddress1(autocompletes.getPlace());
    }

    function fetchMapAddress1(data) {
     var componentForm = {
      street_number: 'short_name',
      route: 'long_name',
      sublocality_level_1: 'long_name',
      sublocality_level_2: 'long_name',
      sublocality_level_3: 'long_name',
      sublocality: 'long_name',
      locality: 'long_name',
      administrative_area_level_1: 'long_name',
      country: 'short_name',
      postal_code: 'short_name'
    };

    $scope.postal_code = '';
    $scope.city = '';
    $scope.latitude = '';
    $scope.longitude = '';
    $scope.street_address = '';
    $scope.locality = '';

    var place = data;
    for (var i = 0; i < place.address_components.length; i++) {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) {
        var val = place.address_components[i][componentForm[addressType]];
        if (addressType == 'postal_code') $scope.postal_code = val;
        if (addressType == 'locality') $scope.city = val;

        if (addressType == 'sublocality_level_1' && $scope.locality == '') 
          $scope.locality = val;
        else if (addressType == 'sublocality' && $scope.locality == '') 
          $scope.locality = val;
        else if (addressType == 'locality' && $scope.locality == '') 
          $scope.locality = val;
        else if(addressType == 'administrative_area_level_1' && $scope.locality == '') 
          $scope.locality = val;
        else if(addressType  == 'country' && $scope.locality == '') 
          scope.locality = place.address_components[i]['long_name'];

        if(addressType       == 'street_number')
          $scope.street_address = val;
        if(addressType       == 'route')
          $scope.street_address = $scope.street_address+' '+val;
        if(addressType       == 'country')
          $scope.country = val;
        if(addressType       == 'administrative_area_level_1')
          $scope.state = val;
      }
    }

    $scope.latitude = place.geometry.location.lat();
    $scope.longitude = place.geometry.location.lng();
    $scope.is_auto_complete = 1;

        //store session data
        var url_search = getUrls('store_location');
        var location_val = $('#header_location_val').val();
        $scope.street_address = ($scope.street_address)?$scope.street_address:$scope.city;
        $http.post(url_search,{
          postal_code: $scope.postal_code,
          city: $scope.city,
          address : $scope.street_address,
          latitude: $scope.latitude,
          longitude: $scope.longitude,
          state : $scope.state,
          country : $scope.country,
          location: location_val,
          locality: $scope.locality,
        }).then(function(response){
          var url = getUrls('search');
          window.location.href = url ;
        });
      }

      $('#header_location_val').keyup(function () {
        $scope.is_auto_complete = '';
      });

    // find food button


    //find food search

    //Google Place Autocomplete Code
    var autocomplete;
    initAutocomplete();

    function initAutocomplete() {
      autocomplete = new google.maps.places.Autocomplete(document.getElementById('head_location_val'),{ types: ['geocode'] });
      autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
      fetchMapAddress(autocomplete.getPlace());
    }

    function fetchMapAddress(data) {
     var componentForm = {
      street_number: 'short_name',
      route: 'long_name',
      sublocality_level_1: 'long_name',
      sublocality_level_2: 'long_name',
      sublocality_level_3: 'long_name',
      sublocality: 'long_name',
      locality: 'long_name',
      administrative_area_level_1: 'long_name',
      country: 'short_name',
      postal_code: 'short_name'
    };

    $scope.postal_code = '';
    $scope.city = '';
    $scope.latitude = '';
    $scope.longitude = '';
    $scope.street_address = '';
    $scope.locality = '';

    var place = data;
    for (var i = 0; i < place.address_components.length; i++) {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) {
        var val = place.address_components[i][componentForm[addressType]];
        if (addressType == 'postal_code') $scope.postal_code = val;
        if (addressType == 'locality') $scope.city = val;

        if (addressType == 'sublocality_level_1' && $scope.locality == '') 
          $scope.locality = val;
        else if (addressType == 'sublocality' && $scope.locality == '') 
          $scope.locality = val;
        else if (addressType == 'locality' && $scope.locality == '') 
          $scope.locality = val;
        else if(addressType == 'administrative_area_level_1' && $scope.locality == '') 
          $scope.locality = val;
        else if(addressType  == 'country' && $scope.locality == '') 
          $scope.locality = place.address_components[i]['long_name'];

        if(addressType       == 'street_number')
          $scope.street_address = val;
        if(addressType       == 'route')
          $scope.street_address = $scope.street_address+' '+val;
        if(addressType       == 'country')
          $scope.country = val;
        if(addressType       == 'administrative_area_level_1')
          $scope.state = val;
      }
    }

    $scope.latitude = place.geometry.location.lat();
    $scope.longitude = place.geometry.location.lng();
    $scope.is_auto_complete = 1;

        //store session data
        var url_search = getUrls('store_location');
        var location_val = $('#head_location_val').val();
        $scope.street_address = ($scope.street_address)?$scope.street_address:$scope.city;
        $http.post(url_search,{
          postal_code: $scope.postal_code,
          city: $scope.city,
          address: $scope.street_address,
          latitude: $scope.latitude,
          longitude: $scope.longitude,
          state : $scope.state,
          country : $scope.country,
          location: location_val,
          locality: $scope.locality,
        }).then(function(response){
         window.location = ajax_url_list['search'];
       });
      }

      $('#head_location_val').keyup(function () {
        $scope.is_auto_complete = '';
      });

    // find food button

    $('#find_food').click(function () {
     return false;
     var location = $('#head_location_val').val();
     if (location == ''){
       return false;
     }
     else{
          //console.log($scope.latitude,$scope.longitude);
          var locations = location.replace(/\s/g, '+');
          // var url = getUrl('search', { 'location': locations });



          //search page redirect

          var url = getUrls('search');
          //window.location.href = url + '?postal_code=' + $scope.postal_code + '&city=' + $scope.city + '&latitude=' + $scope.latitude + '&longitude=' + $scope.longitude;
          window.location.href = url ;


        }
      });

    //signup submit form


    var store_valitAate = $("#eater_signup_form").validate({
      ignore: ':hidden:not(.do-not-ignore)',
      rules: {
       first_name:{ required:true },
       last_name:{ required:true },
       password:{ required:true,minlength:6 },
       phone_number:{ required:true,minlength:6,number:true},
       email_address:{ required:true,email:true },
     },

     messages: {
      'first_name' : {  required :  Lang.get('js_messages.store.field_required')},
      'last_name' : {  required :  Lang.get('js_messages.store.field_required')},
      'password' : {  required : Lang.get('js_messages.store.field_required') , minlength : Lang.get('js_messages.store.please_enter_at_least_characters')},
      'email_address' : {  required : Lang.get('js_messages.store.field_required') , email : Lang.get('js_messages.store.valid_email_address')},
      'phone_number' : {  required : Lang.get('js_messages.store.field_required') , minlength : Lang.get('js_messages.store.mobile_number_must_have_digits') ,number:Lang.get('js_messages.store.please_enter_valid_number')},
    },
    errorElement: "span",
    errorClass: "text-danger",
    errorPlacement: function( label, element ) {
      if(element.attr( "data-error-placement" ) === "parent" ){
        element.parent().append( label ); 
      } else if(element.attr( "data-error-placement" ) === "next2" ){
        label.insertAfter( element.next() ); 
      } else if(element.attr( "data-error-placement" ) === "container" ){
        container = element.attr('data-error-container');
        $(container).append(label);
      } else {
        label.insertAfter( element ); 
      }
    }

  });


    $('#signup_form_submit').click(function(){

      var $valid = $('#eater_signup_form').valid();
      if (!$valid) {
        $store_valitAate.focusInvalid();
        return false;
      }
      var phone_number = $('#phone_number').val();
      var country_code      = $('#phone_code').val();
      var email_address = $('#email_address').val();
      var password      = $('#password').val();
      var first_name      = $('#first_name').val();
      var last_name      = $('#last_name').val();

      var signup_data = getUrls('signup_data');

      $http.post(signup_data,{
        first_name    : first_name,
        last_name     : last_name,
        phone_number  : phone_number,
        country_code  : country_code,
        email_address : email_address,
        password      : password,
      }).then(function(response){
        if(response.data.success=='true'){
          var url = getUrls('signup2');
          window.location.href = url ;
        }
        else{
          $('.phone_error').text(response.data.data.message);
          return false;
        }
      });
    });

    //verification_code_confirm

    $('#code_confirm_submit').click(function(){
      var code_entered   = $('#code_confirm').val();
      var code_generated = $('#code_session').val();

      if(code_entered != code_generated){
        //console.log('wrong');
        $('#code_check').css('display','block');
        return false;
      }
      else{
        //console.log('correct');
        $('#code_check').css('display','none');

        return true;
      }
    });
  }]);

app.filter('translations', ["$filter", function($filter) {
  return function(value) {
    return Lang.get('js_messages.store.'+value);
  };
}])

//payout preference controller
app.controller('payout_preferences1', ['$scope', '$http', function($scope, $http) {

  $scope.coutry_change = function(country) {
    $scope.$apply(function(){
      $scope.payout_country = country;
    });
  }   
  $(document).ready(function() {
    $scope.change_currency();
    if($('#payout_info_payout_country').val() == 'OT'){

      $('#currency_payout').css('display','none');

    }
  })
  $(document).on('change', '#payout_info_payout_country', function() {
    $scope.change_currency();
    $('.routing_number_cls').css('display','block');
    if($('#payout_info_payout_country').val() == 'GB' && $('[name="currency"]').val() == 'EUR')
    {
     $('.routing_number_cls').addClass('hide');
     $('.account_number_cls').html('IBAN');

   }
   else
   {
    $('.routing_number_cls').removeClass('hide');
    $('.account_number_cls').html('Account Number');
  }

  if($('#payout_info_payout_country').val() == 'OT'){
    $('#currency_payout').css('display','none');

    $('#routing_number_smbl').text(' ');
    $('#document_smbl').text(' ');
  }
  else{

    $('#currency_payout').css('display','block');
    $('#routing_number_smbl').text('*');
    $('#document_smbl').text('*');
    $('#branch_code').val('');
    $('#branch_name').val('');
    $('#bank_name').val('');

  }
  $scope.payout_currency = $('[name="currency"]').val();
  $('[name="currency"]').val($('[name="currency"] option:first').val());

  setTimeout(function(){ 
    $('select').selectpicker('refresh');
  }, 10);

});
  $(document).on('change', '[name="currency"]', function() {
    $scope.payout_currency = $('[name="currency"]').val()
    if($('#payout_info_payout_country').val() == 'GB' && $('[name="currency"]').val() == 'EUR')
    {
     $('.routing_number_cls').addClass('hide');
     $('.account_number_cls').html('IBAN');

   }
   else
   {
    $('.routing_number_cls').removeClass('hide');
    $('.account_number_cls').html('Account Number');


  }

});
  $scope.edit_payout = function($id)
  {
    $("body").addClass("pos-fix3");
    $('.add_payout_mtd').addClass('hide');
    $('.edit_payout_mtd').removeClass('hide');

    $('#payout_popup1').removeClass('hide').attr("aria-hidden", "false");
    $('#payout_preference_submit').addClass('loading');
        //console.log('sa');
        var user_id    = $('#user_id_data').val();
        var url_search = getUrls('get_payout_preference');

        $http.post(url_search,{
          id : user_id,

        }).then(function(response) 
        {
          $scope.payout_responce = response.data; 
          $('#payout_info_payout_country').val(response.data.country);
          $scope.payout_country = response.data.country;
          $scope.payout_currency = response.data.currency_code;
          if(response.data.country == 'OT'){
            $('#currency_payout').css('display','none');
            $('.routing_number_cls').css('display','block');

            $('#routing_number_smbl').text(' ');
            $('#document_smbl').text(' ');
          }



          $('[name="currency"]').val(response.data.currency_code);
          $('#address1').val(response.data.address1);
          $('#address2').val(response.data.address2);
          $('#city').val(response.data.city);
          $('#state').val(response.data.state);
          $('#postal_code').val(response.data.postal_code);
          $('#phone_number').val(response.data.phone_number);

          if(response.data.address_kanji)
          {
            $('#kanji_address1').val(response.data.address_kanji.line1);
            $('#kanji_address2').val(response.data.address_kanji.town);
            $('#kanji_city').val(response.data.address_kanji.city);
            $('#kanji_state').val(response.data.address_kanji.state);
            $('#kanji_postal_code').val(response.data.address_kanji.postal_code);

          }

          var selected_country = [];
          $scope.change_currency();
          $('#legal_document span').addClass('hide');
          $('#payout_preference_submit').removeClass('loading');

          setTimeout(function(){ 
            $('select').selectpicker('refresh');
          }, 10);

        });
      }
      $scope.change_currency = function()
      {        
        var selected_country = [];
        angular.forEach($scope.country_currency, function(value, key) {          
          if($('#payout_info_payout_country').val() == key)
           selected_country = value;
       });

        if(selected_country)
        {
          var $el = $('[name="currency"]');
                    $el.empty(); // remove old options
                    $.each(selected_country, function(key,value) {
                      $el.append($("<option></option>")
                       .attr("value", value).text(value));
                      if($scope.old_currency != '')
                      {

                        $('[name="currency"]').val($scope.payout_currency);
                      }
                      else
                      {

                        $('[name="currency"]').val(selected_country[0]);
                      }


                    });
                    
                    if($('#payout_info_payout_country').val() == 'GB' && $('[name="currency"]').val() == 'EUR')
                    {
                     $('.routing_number_cls').addClass('hide');
                     $('.account_number_cls').html('IBAN');

                   }
                   else
                   {
                    $('.routing_number_cls').removeClass('hide');
                    $('.account_number_cls').html('Account Number');
                  }
                }
                
                if($('[name="currency"]').val() == '' || $('[name="currency"]').val() == null)
                {

                  $('[name="currency"]').val($('[name="currency"] option:first').val());
                }

              }

              $('#add-payout-method-button').click(function() {
                $('#payout_popup1').removeClass('hide').attr("aria-hidden", "false");
                $("body").addClass("pos-fix3");
              });

              $('#address').submit(function() {
                var validation_container = '<div class="alert alert-error alert-error alert-header"><a class="close alert-close" href="javascript:void(0);"></a><i class="icon alert-icon icon-alert-alt"></i>';
                if ($('#payout_info_payout_address1').val().trim() == '') {
                  $('#popup1_flash-container').html(validation_container+$('#blank_address').val()+'</div>');
                  return false;
                }
                if ($('#payout_info_payout_city').val().trim() == '') {
                  $('#popup1_flash-container').html(validation_container+$('#blank_city').val()+'</div>');
                  return false;
                }
                if ($('#payout_info_payout_zip').val().trim() == '') {
                  $('#popup1_flash-container').html(validation_container+$('#blank_post').val()+'</div>');
                  return false;
                }
                if ($('#payout_info_payout_country').val().trim() == null) {
                  $('#popup1_flash-container').html(validation_container+$('#blank_country').val()+'</div>');
                  return false;
                }
                $('#payout_info_payout2_address1').val($('#payout_info_payout_address1').val());
                $('#payout_info_payout2_address2').val($('#payout_info_payout_address2').val());
                $('#payout_info_payout2_city').val($('#payout_info_payout_city').val());
                $('#payout_info_payout2_state').val($('#payout_info_payout_state').val());
                $('#payout_info_payout2_zip').val($('#payout_info_payout_zip').val());
                $('#payout_info_payout2_country').val($('#payout_info_payout_country').val());

                $('#payout_popup1').addClass('hide');
                $('#payout_popup2').removeClass('hide').attr("aria-hidden", "false");
              });

              $('#select-payout-method-submit').click(function() {
                var validation_container = '<div class="alert alert-error alert-error alert-header"><a class="close alert-close" href="javascript:void(0);"></a><i class="icon alert-icon icon-alert-alt"></i>';
                if ($('[id="payout2_method"]:checked').val() == undefined) {
                  $('#popup2_flash-container').html(validation_container+$('#choose_method').val()+'</div>');
                  return false;
                }

                $('#payout_info_payout3_address1').val($('#payout_info_payout2_address1').val());
                $('#payout_info_payout3_address2').val($('#payout_info_payout2_address2').val());
                $('#payout_info_payout3_city').val($('#payout_info_payout2_city').val());
                $('#payout_info_payout3_state').val($('#payout_info_payout2_state').val());
                $('#payout_info_payout3_zip').val($('#payout_info_payout2_zip').val());
                $('#payout_info_payout3_country').val($('#payout_info_payout2_country').val());
                $('#payout3_method').val($('#payout2_method').val());

                $('#payout_popup2').addClass('hide');
                $('#payout_popup3').removeClass('hide').attr("aria-hidden", "false");
              });
              $('#payout_paypal').submit(function() {
                var validation_container = '<div class="alert alert-error alert-error alert-header"><a class="close alert-close" href="javascript:void(0);"></a><i class="icon alert-icon icon-alert-alt"></i>';
                var emailChar = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (emailChar.test($('#paypal_email').val())) {
                  return true;
                } else {
                  $('#popup3_flash-container').removeClass('hide');
                  return false;
                }
              });
              $('.panel-close').click(function() {
                $(this).parent().parent().parent().parent().parent().addClass('hide');
              });

              $('[id$="_flash-container"]').on('click', '.alert-close', function() {
                $(this).parent().parent().html('');
              });

            }]);






  //search page controller

  app.controller('stores_search', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {
   $scope.category_id ='';

   setTimeout(function(){

      //console.log($scope.latitude,$scope.longitude);
      $(document).ready(function () {
        $('.search_page').addClass('loading');
        $scope.search_result();
      });

    },100);

   $scope.repeater = function (range) {
    var arr = []; 
    for (var i = 0; i < range; i++) {
      arr.push(i);
    }
    return arr;
  }

    //search page google search

    var autocompletes;
    initAutocompletes();

    function initAutocompletes() {
      autocompletes = new google.maps.places.Autocomplete(document.getElementById('location_search'),{ types: ['geocode'] });
      autocompletes.addListener('place_changed', fillInAddress1);
    }

    function fillInAddress1() {
      fetchMapAddress1(autocompletes.getPlace());
    }

    function fetchMapAddress1(data) {

      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        sublocality_level_1: 'long_name',
        sublocality_level_2: 'long_name',
        sublocality_level_3: 'long_name',
        sublocality: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'long_name',
        country: 'short_name',
        postal_code: 'short_name'
      };

      $scope.postal_code = '';
      $scope.city = '';
      $scope.latitude = '';
      $scope.longitude = '';
      $scope.street_address = '';
      $scope.locality = '';

      var place = data;
      for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
          var val = place.address_components[i][componentForm[addressType]];
          if (addressType == 'postal_code') $scope.postal_code = val;
          if (addressType == 'locality') $scope.city = val;

          if (addressType == 'locality') $scope.city = val;
          
          if (addressType == 'sublocality_level_1' && $scope.locality == '') 
            $scope.locality = val;
          else if (addressType == 'sublocality' && $scope.locality == '') 
            $scope.locality = val;
          else if (addressType == 'locality' && $scope.locality == '') 
            $scope.locality = val;
          else if(addressType == 'administrative_area_level_1' && $scope.locality == '') 
            $scope.locality = val;
          else if(addressType  == 'country' && $scope.locality == '') 
            $scope.locality = place.address_components[i]['long_name'];
          


          if(addressType       == 'street_number')
            $scope.street_address = val;
          if(addressType       == 'route')
            $scope.street_address = $scope.street_address+' '+val;
          if(addressType       == 'country')
            $scope.country = val;
          if(addressType       == 'administrative_area_level_1')
            $scope.state = val;

        }
      }
      
      $scope.latitude = place.geometry.location.lat();
      $scope.longitude = place.geometry.location.lng();
      $scope.is_auto_complete = 1;
      $('#location_search').val($scope.locality);
      $('#location_search_mob').val($scope.locality);
        //store session data
        var url_search = getUrls('store_location');
        var location_val = $('#location_search').val();
        $scope.location = location_val;
        $scope.street_address = ($scope.street_address)?$scope.street_address:$scope.city;
          //console.log(url_search,location_val);
          $http.post(url_search,{
            postal_code: $scope.postal_code,
            city       : $scope.city,
            address1   : $scope.street_address,
            latitude   : $scope.latitude,
            longitude  : $scope.longitude,
            state      : $scope.state,
            country    : $scope.country,
            location   : $scope.location,
            locality   : $scope.locality,
          }).then(function(response){
              //console.log(response);
              $('.search_page').addClass('loading');

              $scope.search_result();
            });
        }


        $('.schedule-option').click(function(){
          var status = $(this).attr('data-val');
          $scope.schedule_status_clone = status;
          $scope.schedule_status = Lang.get('js_messages.store.'+status);

          $scope.$apply();
          if($scope.schedule_status_clone=='ASAP'){

            var url = getUrls('schedule_store');
            date ='';
            time = '';
            $http.post(url,{
              status   : $scope. $scope.schedule_status_clone,
              date     : date,
              time     : time,
            }).then(function(response){
              location.reload();
            });
          }

        });


        $scope.save_time = function(){
         if($('#count_card').text() > 0)
          $('#schedule_modal_mob').modal();
        else
          $('.schedule_modal_mob').trigger("click");
      }

      $('.schedule_modal_mob').click(function(){
        var schedule_session=$('#schedule_status_session').val();  
        var date = $scope.schedule_date;
        var time = $('#mob_schedule_time').val();
        var url = getUrls('schedule_store');
        $http.post(url,{
          status   :$scope.schedule_status_clone ? $scope.schedule_status_clone : schedule_session,
          date     : date,
          time     : time,
        }).then(function(response){
          location.reload();
        });
      });





    // search result function

    $scope.search_result = function(){

      var url = getUrls('search_result');

      var request_cat =$('#request_cat').val();

      $('.search_page').addClass('loading');
      
      $http.post(url,{
        postal_code : $scope.postal_code,
        city        : $scope.city,
        latitude    : $scope.latitude,
        longitude   : $scope.longitude,
        location    : $scope.location,
        category    : $scope.category_id,
        keyword : request_cat,
      }).then(function(response){     

        $scope.store_data = response.data;
        $scope.search_data_key = response.data.search;
        $('.search_page').removeClass('loading');
        if(response.data.store==''){
          $('.no_result').css('display','block');
        }
        else{
          $('.no_result').css('display','none');
        }

      });

    }


    //category search based on recommended

    $( ".recommended_val" ).click(function( event ) {

      $scope.category_id = $(this).attr('data-id');
      
      $('.category-list').toggleClass('active');
      $('body').removeClass('non-scroll');

      $scope.search_result();

    });

    //category search based on popular

    $( ".popular_val" ).click(function( event ) {

      $scope.category_id = $(this).attr('data-id');

      $('.category-list').toggleClass('active');
      $('body').removeClass('non-scroll');

      $scope.search_result();

    });


    //top category filter search

    $('#top_category_search, #top_category_search_mob').change(function(){
      var keyword = $(this).val();
      $('.search-field .icon-close-2').trigger('click');

      var url = getUrls('search_data');
      $('.search_page').addClass('loading');
      $http.post(url,{
        keyword : keyword,
      }).then(function(response){
        $('#top_category_search, #top_category_search_mob').blur();

        $scope.store_data = response.data;
        $scope.search_data_key = response.data.search;
        $('.search_page').removeClass('loading');

        if(response.data.store=='')
        {
          $('.no_result').css('display','block');
        }

        else
        {
          $('.no_result').css('display','none');
        }
      });

      if(keyword==''){
        $scope.search_result();
      }
    });

    //top category search
    $( ".category_top_val" ).click(function( event ) {
      $scope.category_id = $(this).attr('data-id');
      $('.search-field .icon-close-2').trigger('click');
      $scope.search_result();
    });

    //more category search

    $( ".category_more_val" ).click(function( event ) {

      $scope.category_id = $(this).attr('data-id');

      $('.icon-close-2').trigger('click');

      $scope.search_result();

    });
  }]);

$('.signup-slider.owl-carousel').owlCarousel({
  items: 1,
  rtl:rtl,
  loop: true,
  autoplay: true,
  animateOut: 'fadeOut',
  dots: false
});

$('.profile-slider.owl-carousel').owlCarousel({
  items: 1,
  rtl:rtl,
  loop: true,
  autoplay: true,
  animateOut: 'fadeOut',
  dots: true,

  responsive : {
    // breakpoint from 0 up
    0 : {
      autoHeight: true
    },
    
    768 : {
      autoHeight: false
    }
  }
});

$('.owl-carousel').owlCarousel({
  items: 1,
  rtl:rtl,
  dots: true,
  nav: true,
  loop: true,
  autoplay: true,
  navText: ['<i class="icon icon-angle-arrow-pointing-to-right-1 custom-rotate"></i>', '<i class="icon icon-angle-arrow-pointing-to-right-1"></i>'],
});

$('.main-menu .navbar-toggler').click(function () {
  $('body').addClass('non-scroll');
  $(".main-menu").toggleClass("active");

  $('.search-top').removeClass('active');

  if ($('header').hasClass('active')) {} else {
    $('header').addClass('active');
  }

  if ($('.main-menu').hasClass('active')) {
  }
  else {
    $('body').removeClass('non-scroll');
  }
});

$('.categories-menu .icon-dots-menu').click(function () {
  var a = $('header').outerHeight();
  $('.category-list').toggleClass('active');
  $('.category-list').css({ "top": a + "px" });
  $('.search-top').removeClass('active');
  $('.schedule-dropdown').hide();
  $(this).toggleClass('active');

  if ($('.category-list').hasClass('active')) {
    $('body').addClass('non-scroll');
  } else {
    $('body').removeClass('non-scroll');
  }
});

function search_top() {
  var a = $('.search-field').position();
  var b = $('.search-field').outerHeight();
  $('.search-category').css({ "top": a.top + b + "px", "bottom": "0" });
}

$('.search-top .search-input').click(function () {
  $('.search-top').addClass('active');
  $('.main-menu').removeClass('active');
  $('.main-menu .navbar-toggler').attr( 'aria-expanded', 'false');
  $('.navbar-collapse').removeClass('show');
  setTimeout(function () {
    search_top();
  }, 400);

  setTimeout(function () {
    $('body').addClass('non-scroll');
  }, 500);
});

$('.search-top .icon-close-2').click(function () {
  $('.search-top').removeClass('active');
  $('body').removeClass('non-scroll');
});

$('.schedule-btn').click(function (event) {
  event.preventDefault();
  $('.schedule-dropdown').toggle();
});

$('.schedule-btn-sm').click(function (event) {
  event.preventDefault();
  $('.schedule-popup').addClass('d-flex');
  $('.schedule-dropdown').toggle();
  $('body').addClass('non-scroll');
});

$('.schedule-option').click(function (event) {
  $('.schedule-option').removeClass('active');
  $(this).addClass('active');
});

$('.sm-category-close').click(function () {
  $('.category-list').removeClass('active');
  $('body').removeClass('non-scroll');
});

$('#schedule-close-sm').click(function () {
  $('.schedule-popup').removeClass('d-flex');
  $('.schedule-dropdown').toggle();
  $('body').removeClass('non-scroll');
});

$('#location_search, #location_search_mob').on('keydown', function (e) {
  if (e.keyCode == 13) {
   return false;
 }
});

$('.more-btn').click(function (event) {
  event.preventDefault();
  $('.more-option').toggleClass('active');
});

$('.customize-btn').click(function () {
  $('.invite-input').toggle();
  $(this).toggle();
});

$('.payment-select li.select').click(function () {
  $('.payment-method').toggle();
});

$('.card-option').click(function () {
  $('.payment-option').toggle();
  $('.card-form').toggle();
});

$('.card-form .back-btn').click(function () {
  $('.payment-option').toggle();
  $('.card-form').toggle();
});

$('.payment-popup .icon-close-2').click(function () {
  $('.payment-option').show();
  $('.card-form').hide();
});

$('.promo-btn').click(function () {
  $('.add-promo').toggle();
  $(this).toggle();
});

$('.cancel-promo').click(function () {
  $('.add-promo').toggle();
  $('.promo-btn').toggle();
});

$('.method-btn').click(function () {
  $('.method-info').toggleClass('active');
});

  // when opening the sidebar
  $('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
    $('.overlay').toggle().fadeIn();
    $('.collapse.in').toggleClass('in');
    $('body').toggleClass('non-scroll');
    $('a[aria-expanded=true]').attr('aria-expanded', 'false');
  });

  $('.history-toggle').click(function () {
    $(this).closest('tr').toggleClass('active');
    $(this).closest('tr').nextUntil('tr.main-list').toggleClass('active');
  });

  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  $(document).on('click', '.tooltip-link', function () {
    var pos = $(this).position();
    $(this).find('.tooltip-content').toggle();
  });

  $('.menu-name .icon-pencil-edit-button').click(function () {
    $('.menu-name input').prop('readonly', false);
  });

  $("a[href='#top']").click(function () {
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return false;
  });

  setTimeout(function() {
    $("html, body").animate({ scrollTop: 0 }, "slow");
  },10);

  $('.driver-form-link').bind('click', function (e) {
    e.preventDefault(); // prevent hard jump, the default behavior
    var target = $(this).attr("href"); // Set the target as variable
    var top = $(target).offset().top - $('header').outerHeight();
    // perform animated scrolling by getting top-position of target-element and set it as scroll target
    $('html, body').stop().animate({
      scrollTop: top
    }, 600, function () {});
  });

  $('.driver-header .navbar-toggler').click(function () {
    $('body').toggleClass('non-scroll');
  });

  $('#top_category_search, #top_category_search_mob').each(function() { 
    var $this = $(this); 
    $('.search-field .icon-close-2').click(function() {
      $this.attr("placeholder", Lang.get('js_messages.store.search_for_store_category'));
    });   
  });

  $("#top_category_search").bind('keyup focus',function(e) { 
    if (e.keyCode !== 13 && $(this).val() != '') {
     $('.close-search').addClass('active');
   }
   else {
     $('.close-search').removeClass('active');
   }
 });

  setTimeout(function(){ $('#monthly-tab').click();},1000)
  setTimeout(function(){ $('#weekly-tab').click();},1500)

  $(document).ready(function() {
    $('#find_food_header').click(function() {
      $('#header_location_val').focus();
    });

    $('#find_food').click(function() {
      $('#head_location_val').focus();
    });

    $('#verify_modal').click(function(e) {
      e.preventDefault();
    });

   /* $('.help_nav_link').click(function() {
      var help_info = $(this).attr('help-attr');
      // $('.help-banner').attr("help-attr", help_info);
      $('[help-attr = '+help_info+']').addClass('active');
      $('[help-attr != '+help_info+']').removeClass('active');
    });*/
  });

  app.controller('help', ['$scope', '$http', function($scope, $http) {
    var url= getUrls('ajax_help_search');
    var help= getUrls('help');


    $scope.multiple_editors = function(index) {
     setTimeout(function() {
      $("#editor_"+index).Editor();
      $("#editor_"+index).parent().find('.Editor-editor').html($('#content_'+index).val());
    }, 100);
   }
   $("[name='submit']").click(function(e){
    $scope.content_update();
  });

   $scope.content_update = function() {
    $.each($scope.translations,function(i, val) {
      $('#content_'+i).text($('#editor_'+i).Editor("getText"));
    })
    return  false;
  }

  $('#help_search').autocomplete({
    source: function(request, response) {
      $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
          term: request.term
        },
        success: function(data) {
          response(data);
          $(this).removeClass('ui-autocomplete-loading');
        }
      });
    },
    search: function() {
      $(this).addClass('loading');
    },
    open: function() {
      $(this).removeClass('loading');
    }
  })
  .autocomplete("instance")._renderItem = function(ul, item) {
    if (item.id != 0) {
      $('#help_search').removeClass('ui-autocomplete-loading');
      return $("<li>")
      .append("<a href='" + help + "/" + item.page+"/" + item.category_id+ "/" + item.subcategory_id + "/" + item.id + "' class='help-link'><span class='d-flex align-items-center'><div class='help-icon mr-2'><i class='icon icon-document'></i></div>" + item.value + "</span></a>")
      .appendTo(ul);
    }
    else {
      $('#help_search').removeClass('ui-autocomplete-loading');
      return $("<li class='no-result'>")
      .append("<span class='d-flex align-items-center'><div class='help-icon mr-2'><i class='icon icon-document'></i></div>" + item.value + "</span>")
      .appendTo(ul);
    }
  };


}]);

  app.filter('checkKeyValueUsedInStack', ["$filter", function($filter) {
    return function(value, key, stack) {
      var found = $filter('filter')(stack, {locale: value});
      var found_text = $filter('filter')(stack, {key: ''+value},true);
      return !found.length && !found_text.length;
    };
  }])

  app.filter('checkActiveTranslation', ["$filter", function($filter) {
    return function(translations, languages) {
      var filtered =[];
      $.each(translations, function(i, translation){
        if(languages.hasOwnProperty(translation.locale))
        {
          filtered.push(translation);
        }
      });
      return filtered;
    };
  }])

  $('.trip-origin').click(function () {
    $('.trip-origin').toggleClass('new');
  });

  $('#head_location_val').click(function () {
    $('.navbar-toggler').addClass('close_nav_but');
  });

  $(".close_nav_but:not(.collapsed)").click(function () {
    header_fixed();
    $('.navbar-toggler').removeClass('close_nav_but');
  });
