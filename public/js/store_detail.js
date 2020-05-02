app.controller('stores_detail', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {

	// $('.pro-item').click(function () {
 //    	$('body').addClass('non-scroll');
 //    	$('.detail-popup').addClass('active');
 //  	});

	$('.detail-popup .icon-close-2').click(function () {
      $scope.item_count = 1;
	    $('.detail-popup').removeClass('active');
	    $('body').removeClass('non-scroll');
	});

  $('.restuarant-popup .icon-close-2').click(function () {
      $('.restuarant-popup').removeClass('active');
      $('body').removeClass('non-scroll');
  });

  $scope.show_promo = function(){
    $('.add-promo').toggle();
    $('.promo_btn_show').toggle();
  }

	$scope.menu_item = '';
	$scope.menu_item_price = '';
	$scope.item_count = 1;
  $scope.index_id='';

	$(document).on('change','#menu_changes',function(){
		var category_id = $('#menu_changes').val();
		var url_category = getUrls('category_details');
    $http.post(url_category,{
    id : category_id,
    }).then(function(response){
    $scope.menu_category = response.data.menu_category;
    });
	});
	
  $scope.apply_promo = function(){
    $('.promo_code_error').addClass('text-danger');
    

      var promo_code = $('.promo_code_val').val();
      if(promo_code=='')
      {
        $('.promo_code_error').removeClass('d-none');
        return false
      }
      $('.promo_code_error').addClass('d-none');
      $('.promo_loading').addClass('loading');
      var add_promo_code_data = getUrls('add_promo_code_data');
      $http.post(add_promo_code_data,{
        code : promo_code,
        store_id : $('#store_id').val(),
      }).then(function(response){
        $('.promo_code_error').removeClass('d-none');
        $('.promo_loading').removeClass('loading');
        if(response.data.status==0)
        {
           
           $('.promo_code_error').text(response.data.message);
          return false
        }
       
        $('.promo_code_success').removeClass('d-none');
        $('.promo_code_success').text(response.data.message);
         $scope.order_data = response.data.order_detail_data;
      });
      return false;
  };
	//menu item details show

	$('.pro-item').click(function () {
      if($('#location_search').val()=='')
      {
        $('.location_error_msg').removeClass('d-none');
        return false;
      }
      $('.location_error_msg').addClass('d-none');
    	var item_id = $(this).attr('data-id');
      var price1 = $(this).attr('data-price');
      $scope.menu_item_price1 = '';
      $scope.item_count = 1;
      $('.count_item').text($scope.item_count);
      $('#menu_item_price').text(price1);
      
    	var url_item = getUrls('item_details');

    	$http.post(url_item,{
    		item_id :  item_id,
    	}).then(function(response){
    		
    		$scope.menu_item = response.data.menu_item;
        if(response.data.menu_item.offer_price!=0)
        {
    		  $scope.menu_item_price = response.data.menu_item.offer_price;
        }
        else
        {
          $scope.menu_item_price = response.data.menu_item.price;
        }
        $scope.add_notes = '';
    		$('body').addClass('non-scroll');
 	    	$('.detail-popup').addClass('active');
    	});
  	});

  	//add or remove count

  	$(document).on('click','.value-changer',function(){

  		if($(this).attr('data-val')=='add') 
      {
        if($scope.item_count<20) 
        {
    			$scope.item_count += 1;
    			$scope.menu_item_price1 = $scope.item_count * $scope.menu_item_price;
    			$('.count_item').text($scope.item_count);
    			$('#menu_item_price').text($scope.menu_item_price1.toFixed(2));
    			$('#menu_count').val($scope.item_count);
    			$('#menu_price').val($scope.menu_item_price1);
        }
  		}

  		else if($(this).attr('data-val')=='remove')
      {
  			if($scope.item_count>1)
        {
	  			$scope.item_count -= 1;
	  			$scope.menu_item_price1 = $scope.item_count * $scope.menu_item_price;
	  			$('.count_item').text($scope.item_count);
	  			$('#menu_item_price').text($scope.menu_item_price1.toFixed(2));
	  			$('#menu_count').val($scope.item_count);
	  			$('#menu_price').val($scope.menu_item_price1);
	  		}
  		}
  	});

    //checkout page

    $('#checkout').click(function () {
      //console.log($scope.order_data=='');
      if($scope.order_data=='')
      {
        return false;
      }
      else
      {
        var url = getUrls('checkout');
        window.location.href = url ;
      }
    });

    $scope.order_data =[];
    $scope.add_notes='';

    function check_store(){

      var session_order_data = $scope.order_data;

      var prev_store_id ;

      $.each(session_order_data,function(key,val){
     
        prev_store_id =val.store_id;

      });

      if(prev_store_id){

        if(prev_store_id!=$scope.store_id){

          $scope.item_counts = $scope.item_count; 
         
          $('.icon-close-2').trigger('click');
          $('.toogle_modal').trigger('click');
          return false;
        }
      }

      return true;
    }



 //remove order


  $scope.remove_sesion_data = function(index)
  {

    $('#calculation_form').addClass('loading');

    var remove_url = getUrls('orders_remove');
    $scope.order_data.items.splice(index, 1);
    var data = $scope.order_data;
    $http.post(remove_url,{
      order_data    : data,
    }).then(function(response){
      $scope.order_data = response.data.order_data;
      $('#calculation_form').removeClass('loading');
      if($scope.order_data==''){
        $('#count_card').text('');
        $('.icon-shopping-bag-1').removeClass('active');
        $('#checkout').attr('disabled','disabled');
        if($('#check_detail_page').val()!=1){
            var url = getUrls('search');
            window.location.href = url ;
          }
      }
      setTimeout(function(){ 
        $('select').selectpicker('refresh');
      }, 1);
      $('#calculation_form').removeClass('loading');
      
    });

  }
$scope.$watch('order_data', function() {
        if($scope.other_store == 'no' && $scope.order_data) {
            if($scope.order_data.total_item_count > 0) {
            $('.icon-shopping-bag-1').addClass('active');
            }
            else {
            $('.icon-shopping-bag-1').removeClass('active');
            }
          $('#count_card').text($scope.order_data ? $scope.order_data.total_item_count:'');
        }
    });

  //dropdown change price 

  $scope.order_store_changes = function(order_item_id){

 
    $('#calculation_form').addClass('loading');
    var change_url = getUrls('orders_change');
   
    $http.post(change_url,{
      order_data    : $scope.order_data,
      order_item_id    : order_item_id,
    }).then(function(response){

      $scope.order_data = response.data.order_data;
      setTimeout(function(){ 
        $('select').selectpicker('refresh');
      }, 1);

      $('#calculation_form').removeClass('loading');
      $('#calculation_form').removeClass('loading');

    });

  }














  //sesion clear function

  $('.store_popup').click(function(){


    
    var url = getUrls('session_clear_data');

      $http.post(url,{

      }).then(function(response){

        $scope.order_data ='';
        $scope.other_store = 'no';
        $scope.order_store_session();
      });


  });



  //checkout page

  var autocompletes;
  initAutocompletes();

  function initAutocompletes() {
      autocompletes = new google.maps.places.Autocomplete(document.getElementById('confirm_address'),{types: ['geocode']});
      autocompletes.addListener('place_changed', fillInAddress1);
  }

  function fillInAddress1() {
      fetchMapAddress1(autocompletes.getPlace());
  }

  function fetchMapAddress1(data) {
     // console.log(data);
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

      $scope.address = '';
      $scope.postal_code = '';
      $scope.city = '';
      $scope.latitude = '';
      $scope.longitude = '';
      $scope.locality = '';

      var place = data;
      for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
              var val = place.address_components[i][componentForm[addressType]];
              console.log('111',val);
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
      $('.checkout-content').addClass('loading');
       var url_search = getUrls('store_location');
        var location_val = $('#header_location_val').val();
        $scope.street_address = ($scope.street_address)?$scope.street_address:$scope.city;
          //console.log(url_search,location_val);
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
              //console.log(response); 
          });

          
		$('#error_place_order').hide();
      var url              = getUrls('location_check');
      var restuarant_id    = $('#store_id').val();
      var order_data_id    = $('#order_data_id').val();
      var location         = $('#confirm_address').val();

      $http.post(url,{
        order_id         : order_data_id,
        restuarant_id    : restuarant_id,
        city             : $scope.city,
        address1         : $scope.street_address,
        state            : $scope.state,
        country          : $scope.country,
        postal_code      : $scope.postal_code,
        latitude         : $scope.latitude,
        longitude        : $scope.longitude,
        location         : location,
        locality         : $scope.locality,
        checkout_page    : 'Yes',

      }).then(function(response){

        console.log(response);
        if(response.data.success=='none'){
          $('.checkout-content').removeClass('loading');
          $('#error_place_order').show();
          $('#place_order').attr('disabled','disabled');
          $('#error_place_order').text(response.data.message);
          return false;
        }
        $('#error_place_order').hide();
        $('.checkout-content').removeClass('loading');
      });

      
      $('#place_order').removeAttr('disabled');
      $('#order_city').val($scope.city);
      $('#order_street').val($scope.street_address);
      $('#order_state').val($scope.state);
      $('#order_country').val($scope.country);
      $('#order_postal_code').val($scope.postal_code);
      $('#order_latitude').val($scope.latitude);
      $('#order_longitude').val($scope.longitude);

  }

  $('#confirm_address').change(function(){

    if($(this).val()==''){
      $('#place_order').attr('disabled','disabled');
    }

  });

  //checkout place order buttton disable

  $(document).ready(function(){
      
    if($('#confirm_address').val()==''){
      $('#place_order').attr('disabled','disabled');
      $('#error_place_order').show();
    }
    else{
       $('#place_order').removeAttr('disabled');
       $('#error_place_order').hide();
    }

    $('#confirm_address').keyup(function(){
        if($('#confirm_address').val()==''){
          $('#place_order').attr('disabled','disabled');
          $('#error_place_order').show();
          $('#error_place_order').text(Lang.get('js_messages.store.location_field_is_required'));
        }
    })

  });


  //card details add

  $('#payment_card').click(function(){

      var card_number    = $('#card_number').val();
      var expire_month   = $('#expire_month').val();
      var expire_year    = $('#expire_year').val();
      var cvv_number     = $('#cvv_number').val();
      var country_card   = $('#country_card').val();
      var card_code      = $('#card_code').val();
      $('.payment-modal_load').addClass('loading');
      $('#error_add_card').text('');
      var change_url = getUrls('card_details');
      //return false;
      $http.post(change_url,{
        card_number     : card_number,    
        expire_month    : expire_month,
        expire_year     : expire_year,
        cvv_number      : cvv_number,
        country_card    : country_card,
        card_code       : card_code,
      }).then(function(response){
        
        console.log(response);
        $('.payment-modal_load').removeClass('loading');
        if(response.data.status_code=='0'){
          $('#error_add_card').text(response.data.status_message);
        }

        
        if(response.data.status_code=='1'){
          $('#payment-modal').modal('hide');
          console.log(response.data.last4,response.data.brand);
          $('#last_4').text(response.data.last4);
          $('#card_type').text(response.data.brand);
          $scope.payment_details =response.data.payment_details;
          $scope.payment_method =1;
        }

      });

  });


  //card details

  $('#payment_method').change(function(){

    var payment_method = $('#payment_method').val();

    if(payment_method==1){
      $('#payment_detail').css('display','block');
    }
    else{
      $('#payment_detail').css('display','none');
    }

  });



  //place order

  $('#place_order').click(function(){

      //event.preventDefault();
      $('.place_order_change').addClass('loading');
      var confirm_address     = $('#confirm_address').val();
      var order_street        = $('#order_street').val();
      var order_city          = $('#order_city').val();
      var order_state         = $('#order_state').val();
      var order_country       = $('#order_country').val();
      var order_postal_code   = $('#order_postal_code').val();
      var order_latitude      = $('#order_latitude').val();
      var order_longitude     = $('#order_longitude').val();
      var suite               = $('#suite').val();
      var delivery_note       = $('#delivery_note').val();
      var payment_method      = $('#payment_method').val();
      var order_note          = $('#order_note').val();
      var delivery_time       = $('#delivery_time').val();
      var order_type          = $('#order_type').val();
      

      var change_url = getUrls('place_order_details');
      //return false;
      if(confirm_address!='' && order_city!='' && order_state!=''){
        $('#error_place_order').css('display','none');
        $http.post(change_url,{
          confirm_address     : confirm_address, 
          street              : order_street,
          city                : order_city, 
          state               : order_state,
          country             : order_country,
          postal_code         : order_postal_code,
          latitude            : order_latitude,
          longitude           : order_longitude,  
          suite               : suite,
          delivery_note       : delivery_note,
          payment_method      : payment_method,
          order_note          : order_note,
        }).then(function(response){

          console.log(response);
          //return false;
          if(response.data.success=='true'){

            // $('#order_id').val(response.data.order.id);
            var order_id = response.data.order.id;
            var wallet = 0;
            // var url = getUrls('order_track');
            // window.location.href = url+'?order_id='+response.data.order.id;

            var url = getUrls ('place_order');

            $http.post(url,{
              order_id       : order_id,
              isWallet       : wallet,
              payment_method : payment_method,
              delivery_time  : delivery_time,
              order_type     : order_type,
              notes          : order_note,
            }).then(function(response){
                console.log(response);
                $('.place_order_change').removeClass('loading');
                if(response.data.status_message=='Successfully'){
                  $('#order_id').val(response.data.order_details.id);
                  var url = getUrls('order_track');
                  window.location.href = url+'?order_id='+response.data.order_details.id;
                }
                else
                {
                  $('#error_place_order').text(response.data.status_message);
                  $('#error_place_order').show();
                }
            });
          }

        });
      }
      else{
        $('#error_place_order').css('display','block');
      }

  });


//add to cart 

    $scope.add_notes='';
    $scope.order_store_session=function(){
        if($scope.other_store=='yes'){
          $('#myModal').modal();
          return false;
        }
           console.log($scope.other_store);
          $('.detail-popup').addClass('loading');
          $('.cart-scroll').addClass('loading');

          $scope.item_count = $scope.item_count;
      
          var store_id = $scope.store_id;

          $scope.item_notes = $scope.add_notes;

          var index_id = $(this).attr('data-remove');

          var menu_item_id = $scope.menu_item.id;

          var add_cart = getUrls('add_cart');

         
            $http.post(add_cart,{

              store_id    : store_id,
              menu_data        : $scope.menu_item,
              item_count       :  $scope.item_count,
              item_notes       : $scope.item_notes,
              item_price       : $scope.price,
              individual_price : $scope.individual_price,
             
            }).then(function(response){
              // $scope.order_data = response.data.all_order;
              $scope.order_data = response.data.cart_detail;
              
                $('.detail-popup').removeClass('loading');
                $('.cart-scroll').removeClass('loading');

                  $('.detail-popup').removeClass('active');
                  $('body').removeClass('non-scroll');
                  $('#checkout').removeAttr('disabled');

                  setTimeout(function(){ 
                    $('select').selectpicker('refresh');
                  }, 1);

            
            });
     
    }



}]);



//orders detail controller

app.controller('orders_detail', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {

// $scope.order_detail = '';
history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };

$('.invoice-btn').click(function(){

  //console.log('sasa');
  var order_id = $(this).attr('data-id');
  //console.log(order_id);

  var url = getUrls('order_invoice');
  $http.post(url,{
    order_id    : order_id,
  }).then(function(response){

    //console.log(response.data);

    $scope.order_detail = response.data.order_detail;
    $scope.currency_symbol = response.data.currency_symbol;
    //console.log($scope.order_detail.order_item.length);
    //console.log($scope.order_detail,$scope.currency_symbol);
  });


});


$(document).ready(function(){

  var status = $('#order_status').val();

  console.log(status);

  if(parseInt(status)>=5){
    console.log('dasd');
    $('.delivery_data').css('display','block');
  }
  else{
    $('.delivery_data').css('display','none');
  }

});


$scope.open_cancel_model = function(id){
  $('#open_cancel_model').modal('show');
  $('#cancel_order_id').val(id);
}

}]);