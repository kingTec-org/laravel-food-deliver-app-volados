$(document).on('click', '.confirm-delete', function () {
  var url = $(this).attr('data-href');
  swal({
    title: 'Are you sure delete?',
    text: '',
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel',
    confirmButtonClass: "btn btn-success",
    cancelButtonClass: "btn btn-danger",
    buttonsStyling: false
  }).then(function () {
    window.location = url;
  });
});


    // Code for the store_preparation_time Validator
         var $preparation_time_validator = $('#store_preparation_time').validate({
              rules: {
                "max_time[]": { required: true },
                "day[]": { required: true },
                "from_time[]": { required: true,greate_then:true },        
                "to_time[]": { required: true },                       
                "status[]": { required: true },                       
              },
              errorElement: "span",
              errorClass: "text-danger",
              errorPlacement: function( label, element ) {
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
              },
            });
   $('#open_time_form').validate({
          rules: {
            "day[]": { required: true },
            "start_time[]": { required: true,greate_then:true },        
            "end_time[]": { required: true },                       
            "status[]": { required: true },                       
          },
          errorElement: "span",
          errorClass: "text-danger",
          errorPlacement: function( label, element ) {
            if(element.attr( "data-error-placement" ) === "container" ){
              container = element.attr('data-error-container');
              $(container).append(label);
            } else {
              label.insertAfter( element ); 
            }
          },
        });
$.validator.addMethod('greate_then', function(value, element, param) {
    var end_time = $(element).attr('data-end_time');
    var start_time = $(element).val();
    
    if(end_time) {
      return start_time < end_time;
    }
    else {
      return 'false';
    }
    }, 'The start time should be less than the end time');
app.controller('cancelOrderController', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {

      var $validator = $('#order_cancel_form').validate({
              rules: {
                "translations[0][locale]" : { required: true },                
                "translations[1][locale]" : { required: true },                
                "translations[0][name]" : { required: true }, 
                "translations[1][name]" : { required: true }, 
                "translations[0][reason]" : { required: true },                
                "translations[1][reason]" : { required: true },                
                type : { required: true },  
                 reason :  { required: true },                                             
                status : { required: true },               
                                  
              },
              messages: {
                'translations[0][locale]': {required :'The Language field is required.'},                
                'translations[1][locale]': {required :'The Language field is required.'},                
                'translations[0][name]': {required :'The Name field is required.'},
                'translations[1][name]': {required :'The Name field is required.'},
                'translations[0][reason]': {required :'The Reason field is required.'},                                
                'translations[1][reason]': {required :'The Reason field is required.'},                                
                reason: {required :'The Reason field is required.'},
                status: {required :'The Status field is required.'},
                type: {required :'The Type field is required.'},
              },
              errorElement: "span",
              errorClass: "text-danger ng-binding",
              errorPlacement: function( label, element ) {
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
              },
            });  

  }]);
app.controller('promoController', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {


    var $validator = $('#promo_form').validate({
              rules: {
                "translations[0][locale]" : { required: true },                
                "translations[1][locale]" : { required: true },                
                "translations[0][lang_code]" : { required: true },
                "translations[1][lang_code]" : { required: true },
                 promo_type : { required: true },  
                 code :  { required: true },                
                price : { required: true,number: true },               
                currency_code : { required: true }, 
                percentage : { required: true,number: true },
                start_date : { required: true }, 
                end_date : { required: true }, 
                status : { required: true },                  
              },
              messages: {
                'translations[0][locale]': {required :'The Language field is required.'},                
                'translations[1][locale]': {required :'The Language field is required.'},                
                'translations[0][lang_code]': {required :'The Code field is required.'},                
                'translations[1][lang_code]': {required :'The Code field is required.'},                
                promo_type: {required :'The Promo Type field is required.'},
                code: {required :'The Code field is required.'},
                price: {required :'The Price field is required.'},
                currency_code: {required :'The Status field is required.'},
                start_date: {required :'The Start_Date field is required.'},
                end_date: {required :'The End_Date field is required.'},
                status: {required :'The Status field is required.'},
                percentage: {required :'The Percentage field is required.'},
              },
              errorElement: "span",
              errorClass: "text-danger ng-binding",
              errorPlacement: function( label, element ) {
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
              },
            });  

  }]);
app.controller('homeSliderController', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {

  

    var $validator = $('#home_slider_form').validate({
              rules: {
                "translations[0][locale]" : { required: true },                
                "translations[1][locale]" : { required: true },                
                "translations[0][name]" : { required: true },
                "translations[1][name]" : { required: true },
                "translations[0][description]" : { required: true },
                "translations[1][description]" : { required: true },
                "translations[0][title]": { required: true },
                "translations[1][title]": { required: true },
                name : { required: true },  
                 description :  { required: true },                
                title : { required: true },               
                status : { required: true }, 
                image: {

                  required : {
                    depends: function(element){
                        $formType = $('.form_type').val();
                        if($formType == 'add'){
                          return true;
                        }else{
                          return false;
                        }
                    }  
                  },



                 image_valitation:"png|jpg|jpeg|gif"},
                type : { required: true },                    
              },
              messages: {
                'translations[1][locale]': {required :'The Language field is required.'},                
                'translations[0][locale]': {required :'The Language field is required.'},                
                'translations[1][name]': {required :'The Name field is required.'},
                'translations[0][name]': {required :'The Name field is required.'},
                'translations[1][description]' : { required: 'The Description field is required.' },
                'translations[0][description]' : { required: 'The Description field is required.' },
                "translations[1][title]" : { required: 'The Title field is required.' },              
                "translations[0][title]" : { required: 'The Title field is required.' },              
                title: {required :'The Title field is required.'},
                name: {required :'The Name field is required.'},
                description: {required :'The Description field is required.'},
                status: {required :'The Status field is required.'},
                type: {required :'The Type field is required.'},
              },
              errorElement: "span",
              errorClass: "text-danger ng-binding",
              errorPlacement: function( label, element ) {
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
              },
            });  

  $.validator.addMethod("image_valitation", function(value, element, param) {

          param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
          return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
          }, $.validator.format("Please upload the images like JPG,JPEG,PNG,GIF File Only."));
  }]);

app.controller('categoryController', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {

  var $validator = $('#ciusine_form').validate({
              rules: {
                "translations[0][locale]" : { required: true },                
                "translations[1][locale]" : { required: true },                
                "translations[0][name]" : { required: true },
                "translations[1][name]" : { required: true },
                "translations[0][description]" : { required: true },
                "translations[1][description]" : { required: true },
                name : { required: true },  
                 description :  { required: true },                
                status : { required: true },               
                image: {
                  required : {
                    depends: function(element){
                        $formType = $('.form_type').val();
                        if($formType == 'add'){
                          return true;
                        }else{
                          return false;
                        }
                    }  
                  },
                  image_valitation:"png|jpg|jpeg|gif"},                    
              },
              messages: {
                'translations[0][locale]': {required :'The Language field is required.'},                
                'translations[1][locale]': {required :'The Language field is required.'},                
                'translations[0][name]': {required :'The Name field is required.'},
                'translations[1][name]': {required :'The Name field is required.'},
                'translations[0][description]' : { required: 'The Description field is required.' },               
                'translations[1][description]' : { required: 'The Description field is required.' },               
                status: {required :'The Status field is required.'},
                name: {required :'The Name field is required.'},
                description: {required :'The Description field is required.'},
                
              },
              errorElement: "span",
              errorClass: "text-danger ng-binding",
              errorPlacement: function( label, element ) {
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
              },
            });  

  $.validator.addMethod("image_valitation", function(value, element, param) {

          param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
          return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
          }, $.validator.format("Please upload the images like JPG,JPEG,PNG,GIF File Only."));


  

}]);


app.controller('help', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {


var $validator = $('#help_categpry_form').validate({
              ignore: [],
              rules: {
                "translations[0][locale]" : { required: true },                
                "translations[1][locale]" : { required: true },                
                "translations[0][name]" : { required: true },
                "translations[1][name]" : { required: true },
                'translations[0][description]': { required: true },
                'translations[1][description]': { required: true },
                category_id : { required: true },
                subcategory_id : { required: true },                
                question : { required: true },
                answer : { required: true },   
                status : { required: true },               
                                
              },
              messages: {
                'translations[0][locale]': {required :'The Language field is required.'},                
                'translations[1][locale]': {required :'The Language field is required.'},                
                'translations[0][name]': {required :'The Question field is required.'},
                'translations[1][name]': {required :'The Question field is required.'},
                'translations[0][description]': {required :'The Answer field is required.'},
                'translations[1][description]': {required :'The Answer field is required.'},
                category_id: {required :'The Category field is required.'},
                subcategory_id: {required :'The SubCategory field is required.'},
                question: {required :'The Question field is required.'},
                answer: {required :'The Answer field is required.'},
                status: {required :'The Status field is required.'},
                
              },
              errorElement: "span",
              errorClass: "text-danger ng-binding",
              errorPlacement: function( label, element ) {
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
              },
            });

$scope.change_category = function(value) {
  
  $http.post(APP_URL+'/admin/ajax_help_subcategory/'+value).then(function(response) {

      $scope.subcategory = response.data;
      $timeout(function() { $('#input_subcategory_id').val($('#hidden_subcategory_id').val()); $('#hidden_subcategory_id').val('') }, 10);
    });
};
$timeout(function() { $scope.change_category($scope.category_id); }, 10);
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
}]);
app.controller('issue_type', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {

    var $validator = $('#issue_form').validate({
              rules: {
                "translations[0][locale]" : { required: true },                
                "translations[1][locale]" : { required: true },                
                "translations[0][name]" : { required: true },
                "translations[1][name]" : { required: true },
                type : { required: true },
                issue : { required: true },                
                status : { required: true },                
                                
              },
              messages: {
                'translations[0][locale]': {required :'The Language field is required.'},                
                'translations[1][locale]': {required :'The Language field is required.'},                
                'translations[0][name]': {required :'The Issue field is required.'},
                'translations[1][name]': {required :'The Issue field is required.'},
                type: {required :'The Type field is required.'},
                status: {required :'The Status field is required.'},
                issue: {required :'The Issue field is required.'},
                
              },
              errorElement: "span",
              errorClass: "text-danger ng-binding",
              errorPlacement: function( label, element ) {
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
              },
            });
  }]);
app.controller('page', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {

     var $validator = $('#static_page_form').validate({
              ignore: [],
              rules: {
                "translations[0][locale]" : { required: true },                
                "translations[1][locale]" : { required: true },                
                "translations[0][name]" : { required: true },
                "translations[1][name]" : { required: true },
                "translations[0][content]" : { required: true },
                "translations[1][content]" : { required: true },
                name : { required: true },
                url : { required: true },                
                footer : { required: true },
                status : { required: true },
                user_page : { required: true },
                content : { required: true },                
              },
              messages: {
                'translations[0][locale]': {required :'The Language field is required.'},                
                'translations[1][locale]': {required :'The Language field is required.'},                
                'translations[0][name]': {required :'The Name field is required.'},
                'translations[1][name]': {required :'The Name field is required.'},
                'translations[0][content]': {required :'The Content field is required.'},
                'translations[1][content]': {required :'The Content field is required.'},
                name: {required :'The Name field is required.'},
                status: {required :'The Status field is required.'},
                footer: {required :'The Footer field is required.'},
                user_page: {required :'The User Page field is required.'},
                content: {required :'The Content field is required.'},
              },
              errorElement: "span",
              errorClass: "text-danger ng-binding",
              errorPlacement: function( label, element ) {
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
              },
            });

    $scope.multiple_editors = function(index) {
        setTimeout(function() {
            $("#editor_"+index).Editor();
            $("#editor_"+index).parent().find('.Editor-editor').html($('#content_'+index).val());
        }, 100);
    }
    $("[name='submit']").click(function(e){
      
        $scope.content_update();
    });
    // $(document).on('blur', '.Editor-container .Editor-editor', function(){
    //     i = $(this).parent().parent().children('.editors').attr('data-index');
    //     $('#content_'+i).text($('#editor_'+i).Editor("getText"));
    //     $('#content_'+i).valid();
    // });
    $scope.content_update = function() {
      
        $.each($scope.translations,function(i, val) {
      
            $('#content_'+i).text($('#editor_'+i).Editor("getText"));

        })
        return  false;
    }
    // var v = $("#admin_page_form").validate({
    //     ignore: '',
    // });
}]);

app.controller('helpSubCategoryController', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {

  var $validator = $('#help_subcategory_form').validate({
              rules: {
                "translations[0][locale]" : { required: true },                
                "translations[1][locale]" : { required: true },                
                "translations[0][name]" : { required: true },
                "translations[1][name]" : { required: true },
                name : { required: true },
                category_id : { required: true },                                
                status : { required: true },
                description : { required: true },               
                "translations[0][description]" : { required: true },                                
                "translations[1][description]" : { required: true },                                
              },
              messages: {
                'translations[0][locale]': {required :'The Language field is required.'},                
                'translations[1][locale]': {required :'The Language field is required.'},                
                'translations[0][name]': {required :'The Name field is required.'},
                'translations[1][name]': {required :'The Name field is required.'},
                'translations[0][description]': {required :'The Description field is required.'},
                'translations[1][description]': {required :'The Description field is required.'},
                name: {required :'The Name field is required.'},
                category_id: {required :'The Category field is required.'},                
                status: {required :'The Status field is required.'},
                description : {required :'The Description field is required.'},

                
              },
              errorElement: "span",
              errorClass: "text-danger ng-binding",
              errorPlacement: function( label, element ) {
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
              },
            });

}]);
app.controller('category_language', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {

var $validator = $('#help_category_form').validate({
              rules: {
                "translations[0][locale]" : { required: true },                
                "translations[1][locale]" : { required: true },                
                "translations[0][name]" : { required: true },
                "translations[1][name]" : { required: true },
                name : { required: true },
                type : { required: true },                                
                status : { required: true },               
                                
              },
              messages: {
                'translations[0][locale]': {required :'The Language field is required.'},                
                'translations[1][locale]': {required :'The Language field is required.'},                
                'translations[0][name]': {required :'The Name field is required.'},
                'translations[1][name]': {required :'The Name field is required.'},
                name: {required :'The Name field is required.'},
                type: {required :'The Type field is required.'},                
                status: {required :'The Status field is required.'},
                
              },
              errorElement: "span",
              errorClass: "text-danger ng-binding",
              errorPlacement: function( label, element ) {
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
              },
            });


}]);

app.filter('checkKeyValueUsedInStack', ["$filter", function($filter) {
  return function(value, key, stack) {
    var found = $filter('filter')(stack, {locale: value});
    var found_text = $filter('filter')(stack, {key: ''+value}, true);
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
app.controller('store', ['$scope','$http','$timeout', function($scope,$http,$timeout) {

    $scope.add_document = function(){
        $scope.all_document.push({ 'document_name': ''});
    };
    $scope.delete_document = function(index){
        $scope.all_document.splice(index, 1);
    };


  //open time
  $scope.add_open_time = function() {
    $('.text-danger').text('');
    $scope.open_time_timing.push({'day':''});
  };

  $scope.delete_open_time = function(index) {  
    if($scope.open_time_timing.length < 2) 
      return false;     
    $scope.open_time_timing.splice( index, 1 );   
  };


//preparation time
  $scope.add_preparation_time = function () {
    $scope.preparation_timing.push({id:'',max_time:50});

  }


$scope.remove_preparation =function(index) 
{

$scope.preparation_timing.splice(index, 1);

}

$scope.increment =function ($index){ 
var value = parseInt($scope.preparation_timing[$index].max_time);
if(value >=55)
{
  $scope.preparation_timing[$index].max_time = 55;
  return false;
}
$scope.preparation_timing[$index].max_time = value+5;
}

$scope.decrement =function ($index){ 

if($scope.preparation_timing[$index].max_time ==5) return false;
$scope.preparation_timing[$index].max_time -= 5;

} 

$scope.default_increment =function (){ 
if($scope.max_time >=55) return false;
$scope.max_time += 5;

}

$scope.default_decrement =function (){ 
if($scope.max_time ==5) return false;
$scope.max_time -= 5;

} 


    //Google Place Autocomplete Code

$("#location_val").keypress(function(e) { 
  if (event.keyCode === 13) { 
    event.preventDefault(); 
  }
});

var autocomplete;
initAutocomplete();
// $scope.is_auto_complete  = '';
function initAutocomplete()
{
    autocomplete = new google.maps.places.Autocomplete(document.getElementById('location_val'),{types:['geocode']});
    autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() 
{
    fetchMapAddress(autocomplete.getPlace());
}

function fetchMapAddress(data)
{ 

  var componentForm = {
      street_number: 'short_name',
      route: 'long_name',
      sublocality_level_1: 'long_name',
      sublocality: 'long_name',
      locality: 'long_name',
      administrative_area_level_1: 'long_name',
      country: 'short_name',
      postal_code: 'short_name',
       administrative_area_level_1: 'long_name',
  };

    $scope.postal_code  = '';
    $scope.city     = '';
    $scope.latitude   = '';
    $scope.longitude  = '';
    

    var place = data;
    var street_number ='';
    for (var i = 0; i < place.address_components.length; i++) 
    {
      var addressType = place.address_components[i].types[0];

      if (componentForm[addressType]) 
      {
        var val = place.address_components[i][componentForm[addressType]];
        if (addressType == 'street_number')
            street_number = val;
        if (addressType == 'route')
          $scope.address_line_1 = street_number + ' ' + val;
        if (addressType == 'postal_code')
          $scope.postal_code = val;
        if (addressType == 'locality')
          $scope.city = val;  
        if (addressType == 'administrative_area_level_1')
          $scope.state = val;   
        if (addressType == 'country')
          $scope.country_code = val;
       
      }
    }

    $scope.latitude  = place.geometry.location.lat();
    $scope.longitude = place.geometry.location.lng();
    $scope.is_auto_complete = 1;
    $scope.$apply();
}   

$('#location_val').keyup(function(){
  $scope.is_auto_complete = '';
});



}]);


app.filter('checkKeyValueUsedInStack', ["$filter", function($filter) {

  return function(value, key, stack) {

    var found = $filter('filter')(stack, {[key]: value});
    var found_text = $filter('filter')(stack, {[key]:''+value}, true);

    return !found.length && !found_text.length;
  };

}])

app.controller('vehicleController', ['$scope','$http','$timeout','$filter','fileUploadService',function($scope,$http,$timeout,$filter,fileUploadService) {
        
     var $validator = $('#vehicle_form').validate({
              
              rules: {
                "translations[0][locale]" : { required: true },
                "translations[1][locale]" : { required: true },
                "translations[0][name]" : { required: true },
                "translations[1][name]" : { required: true },
                name : { required: true },
                status : { required: true },
                vehicle_image: {
                  required : {
                    depends: function(element){
                        $formType = $('.form_type').val();
                        if($formType == 'add'){
                          return true;
                        }else{
                          return false;
                        }
                    }  
                  },
                  image_valitation:"png|jpg|jpeg|gif"
                },                                    
              },
              messages: {
                'translations[0][locale]': {required :'The Language field is required.'},
                'translations[1][locale]': {required :'The Language field is required.'},
                'translations[0][name]': {required :'The Name field is required.'},
                'translations[1][name]': {required :'The Name field is required.'},
                name: {required :'The Name field is required.'},
                status: {required :'The Status field is required.'},
              },
              errorElement: "span",
              errorClass: "text-danger ng-binding",
              errorPlacement: function( label, element ) {
                
                if(element.attr( "data-error-placement" ) === "container" ){
                  
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  console.log('test2');
                  label.insertAfter( element ); 
                }
              },
            });

     $.validator.addMethod("image_valitation", function(value, element, param) {

          param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
          return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
          }, $.validator.format("Please upload the images like JPG,JPEG,PNG,GIF File Only."));


}]);
app.controller('menu_editor', ['$scope','$http','$timeout','$filter','fileUploadService',function($scope,$http,$timeout,$filter,fileUploadService) {

// Code for the Validator
         var $validator = $('.form_valitate').validate({
              rules: {

                menu_item_name: { required: true },
                menu_item_desc: { required: true },
                menu_item_price: { required: true,number:true,maxlength:7},
                item_type: { required: true },
                menu_item_tax: { required: true,number:true,maxlength:7,max:100 },
                item_status: { required: true },  
                item_image: { image_valitation:"png|jpg|jpeg|gif"},                    
                "menu_item_translations[0][locale]":{ required: true },
                "menu_item_translations[1][locale]":{ required: true },
                "menu_item_translations[0][name]":{ required: true },
                "menu_item_translations[1][name]":{ required: true },
                "menu_item_translations[0][description]":{ required: true },
                "menu_item_translations[1][description]":{ required: true },
                
              },
              errorElement: "span",
              errorClass: "text-danger",
              errorPlacement: function( label, element ) {
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
              },
            });

         $.validator.addMethod("image_valitation", function(value, element, param) {
          param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
          return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
          }, $.validator.format("Please upload the images like JPG,JPEG,PNG,GIF File Only."));



         
  // Code for the Validator
         var $validator = $('.update_menu_time').validate({
              rules: {
                menu_name: { required: true },
                "menu_timing_day[]": { required: true },
                "menu_timing_start_time[]": { required: true,greate_then:true },        
                "menu_timing_end_time[]": { required: true },
                "translations[0][locale]":{ required: true },
                "translations[1][locale]":{ required: true },
                "translations[0][name]":{ required: true },
                "translations[1][name]":{ required: true },
              },
              errorElement: "span",
              errorClass: "text-danger",
              errorPlacement: function( label, element ) {
                  if(element.attr( "name" ) === "menu_timing_day[]" || element.attr( "name" ) === "menu_timing_start_time[]" || 
                    element.attr( "name" ) === "menu_timing_end_time[]"){
                      
                    element.parent().parent().append( label );
                    
                  }
                  else{
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
                }
              },
            });

         $.validator.addMethod('greate_then', function(value, element, param) {
            var end_time = $(element).attr('data-end_time');
            var start_time = $(element).val();
            
            if(end_time) {
              return start_time < end_time;
            }
            else {
              return 'false';
            }
            }, 'The start time should be less than the end time');

          $('#category_add_form').validate({
              rules: {
                category_name: {required: true},
                "category_translations[0][locale]": { required: true },
                "category_translations[1][locale]": { required: true },
                "category_translations[0][name]": { required: true },        
                "category_translations[1][name]": { required: true },        
                
              },
              errorElement: "span",
              errorClass: "text-danger",
              errorPlacement: function( label, element ) {
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
              },
            });

           $('#category_edit_form').validate({
              rules: {
                category_name: {required: true},
                "category_translations[0][locale]": { required: true },
                "category_translations[1][locale]": { required: true },
                "category_translations[0][name]": { required: true },        
                "category_translations[1][name]": { required: true },        
                
              },
              errorElement: "span",
              errorClass: "text-danger",
              errorPlacement: function( label, element ) {
                if(element.attr( "data-error-placement" ) === "container" ){
                  container = element.attr('data-error-container');
                  $(container).append(label);
                } else {
                  label.insertAfter( element ); 
                }
              },
            });
          
  $scope.initToggleBar = function() {

     $scope.menu_item_index = null;
     $scope.category_index = null;
     // $scope.menu_index = null;
      if(!$scope.$$phase) {
        $scope.$apply();
      }
 
}

$scope.select_menu = function(index) {

  if($scope.menu_index==='' || $scope.menu_index===null)
    $scope.menu_index = index;
  else{
    if($scope.menu_index==index)
      $scope.menu_index = null;
    else
      $scope.menu_index = index;
    $scope.category_index = null;
    $scope.menu_item_index = null;
  }
}


$scope.category = function(index, menu_index) {
  
  $scope.category_index = index;
  $scope.menu_index = menu_index;
  $scope.menu_item_index = null;
}

$scope.select_menu_item = function(index) {

  $scope.menu_item_index = index;
  $scope.menu_item_details = $scope.menu[$scope.menu_index].menu_category[$scope.category_index].menu_item[$scope.menu_item_index];
 ;
 
 $scope.menu_item_translations = $scope.menu_item_details.translations;
  $('#myFileField').val('');

}


$scope.add_category = function(menu_id,name){
  $('.text-danger').text('');
$scope.menu_id = menu_id;
$scope.category_name = name;
$scope.category_translations = [];

}

$scope.edit_category = function(id,name,menu){
 $('.text-danger').text('');
 var translations;
$scope.menu_id = '';
$scope.category_name = name;
$scope.category_id = id;
translations = menu.translations;
if (typeof menu.translations === 'undefined') {
  translations = [];
}
$scope.category_translations = translations;
}

$scope.save_category = function(action){
   
    if(action == 'add'){
      var $valid = $('#category_add_form').valid();
    

      if (!$valid) {
          $validator.focusInvalid();
          return false;
      }
    }else{
       var $valid = $('#category_edit_form').valid();
        

      if (!$valid) {
          $validator.focusInvalid();
          return false;
      }
    }
    
    
    var method = 'POST';
    var url = getUrl('update_category');   
    var FormData = { store_id:$scope.store_id, 'name' : $scope.category_name ,'id' : $scope.category_id,'action' : action , 'menu_id' : $scope.menu_id, 'translations': $scope.category_translations };
    $('.item_all_details').addClass('loading');
    $http({

          method: method,
          url: url,
          data: FormData,
        
    }).
    success(function(response) {
      $('.item_all_details').removeClass('loading');  
      if(action=='edit'){
      $('#sub_edit_modal').modal('toggle');
      var getMenu = response.translations;
      $scope.menu[$scope.menu_index].menu_category[$scope.category_index].menu_category = response.category_name;
     
      
    }
    else
    { 
      
        $('#add_modal').modal('toggle');
        
         $scope.menu[$scope.menu_index].menu_category.push({
          'menu_category_id' : response.category_id,
          'menu_category' : response.category_name,
          'menu_item' : [],
          'translations' : response.translations
         });

    }


    }).
    error(function(response) {
      $('.item_all_details').removeClass('loading');
        $scope.codeStatus = response || "Request failed";
    });
    return false;

}



  $scope.menu_time = function(index,menu_id,name){
    $('.text-danger').text('');
    
    $scope.menu_index = index;
    $scope.menu_id = menu_id;
    
    var method = 'GET';
    var url = getUrl('menu_time',{'id':menu_id});  
    var FormData = { store_id:$scope.store_id};
    $('.item_all_details').addClass('loading');
    $http({

          method: method,
          url: url,
          data: FormData,
        
    }).
    success(function(response) {

      var getMenu = response.translations;
     $scope.translations = getMenu[0].translations;
    $scope.menu_timing = response.menu_time;
    $scope.menu_name = name;
    $('.item_all_details').removeClass('loading');
    }).
    error(function(response) {
      
    });
    return false;

  }

  $scope.add_menu_pop = function () {
    
    $scope.menu_timing = [];
    $scope.menu_name = "";
    $scope.menu_index = null;
    $scope.menu_id = null;
    $scope.translations = [];
    $('.text-danger').text('');
  }
  $scope.add_menu_time = function () {
    $scope.menu_timing.push({id:''});

  }
  $scope.remove_menu_time= function(item,id) { 

  $scope.menu_timing.splice(item, 1);  


  var method = 'GET';
  var url =  getUrl('remove_menu_time',id);
  var FormData = { store_id:$scope.store_id};

    $http({

          method: method,
          url: url,
          data: FormData,

        
    }).
    success(function(response) {

   
    }).
    error(function(response) {
      
    });
    return false;



}


$scope.update_menu_time = function(){
   var $valid = $('.update_menu_time').valid();
      if (!$valid) {
          $validator.focusInvalid();
          return false;
      }
    var method = 'POST';
    var url = getUrl('update_menu_time');

    var FormData = { store_id:$scope.store_id , menu_time : $scope.menu_timing,menu_id : $scope.menu_id ,menu_name : $scope.menu_name,translations : $scope.translations  }
    $('.item_all_details').addClass('loading');
    $http({

          method: method,
          url: url,
          data: FormData,

        
    }).
    success(function(response) {
      $('.item_all_details').removeClass('loading');
    $('#edit_modal').modal('toggle');
    if($scope.menu_id)
    $scope.menu[$scope.menu_index].menu = response.menu_name; 
    else
    $scope.menu = response.menu; 
    
    }).
    error(function(response) {
      $('.item_all_details').removeClass('loading');
    });
    return false;

}

$scope.update_item = function()
{
  $('.item_all_details').addClass('loading');
  var $valid = $('.form_valitate').valid();
      if (!$valid) {
          $validator.focusInvalid();
          $('.item_all_details').removeClass('loading');
          return false;
      }
    var method = 'POST';
    var url = getUrl('update_menu_item');
         var file = $scope.myFile;

    var FormData = {menu_item : $scope.menu_item_details}
     promise = fileUploadService.uploadFileToUrl(file, url,FormData,$scope.store_id,$scope.menu_item_translations);

      promise.success(function(response) {
        $('.item_all_details').removeClass('loading');
      if(response.menu_item_id)
        $scope.menu[$scope.menu_index].menu_category[$scope.category_index].menu_item.push(response);
        $scope.menu_item_translations = response.translations;
      if(response.edit_menu_item_image){
        $scope.menu[$scope.menu_index].menu_category[$scope.category_index].menu_item[$scope.menu_item_index].item_image=response.edit_menu_item_image;
      }

      $scope.menu_item_index = null;
    
    }).
    error(function(response) {
      
    });
    return false;

}

$scope.add_new_item = function() {

$scope.menu_item_index = 0;
$scope.menu_item_details = { 'menu_item_id' : '',
'menu_item_name' : '',
'menu_item_desc' : '',
'menu_item_price' :'', 
'menu_item_tax_percentage' :'' ,
'menu_item_type' :'' ,
'menu_item_status' :'' ,
'item_image' :'' ,
'menu_id' : $scope.menu[$scope.menu_index].menu_id,
'category_id' : $scope.menu[$scope.menu_index].menu_category[$scope.category_index].menu_category_id,

  
 };
 $('#myFileField').val('');
  $scope.myFile='';

  $scope.menu_item_translations= [];
}

 $scope.set= function(set_id,name) { 

$('.delete_item_msg').text('');
$scope.remove_id = set_id; 
    $scope.delete_name = name; 
     if(name=='menu')
      $scope.menu_index = set_id;
  $scope.myFile='';

 }

  $scope.remove_item= function(item,$text) { 

  var item = item;
    var method = 'POST';
    var url = getUrl('delete_menu');
    var FormData = {category_index:$scope.category_index,store_id:$scope.store_id,menu : $scope.menu[$scope.menu_index],category : $text ,key : item }

    $http({

          method: method,
          url: url,
          data: FormData,

        
    }).
    success(function(response) {
      
      $('.delete_item_msg').text('');
      if(response.status=='false'){
          $('#delete_error_modal').modal();
          $('.delete_item_msg').text(response.msg);
          return false;
      }
      $('#delete_modal').modal('toggle');
   if($text=='menu')
    {
      $scope.menu.splice(item, 1);  
       $scope.category_index = null;
       $scope.menu_item_index = null;  
       $scope.menu_index = null;  
    }
    else if($text=='category'){
      $scope.menu[$scope.menu_index].menu_category.splice(item, 1);
       $scope.category_index = null;
    }
    else{
      $scope.menu[$scope.menu_index].menu_category[$scope.category_index].menu_item.splice(item, 1);
      $scope.menu_item_index = null;  
    }

    
    }).
    error(function(response) {
      
    });
    return false;


  }

  $scope.add_preparation_time = function () {
    $scope.menu_timing.push({id:''});

  }


  }]);

 /*
     A directive to enable two way binding of file field
     */
    app.directive('demoFileModel', function ($parse) {
        return {
            restrict: 'A', //the directive can be used as an attribute only
 
            /*
             link is a function that defines functionality of directive
             scope: scope associated with the element
             element: element on which this directive used
             attrs: key value pair of element attributes
             */
            link: function (scope, element, attrs) {
                var model = $parse(attrs.demoFileModel),
                    modelSetter = model.assign; //define a setter for demoFileModel
 
                //Bind change event on the element
                element.bind('change', function () {
                    //Call apply on scope, it checks for value changes and reflect them on UI
                    scope.$apply(function () {
                        //set the model value
                        modelSetter(scope, element[0].files[0]);
                    });
                });
            }
        };
   
});


    app.service('fileUploadService', function ($http, $q) {

        this.uploadFileToUrl = function (file, uploadUrl,data,store_id,translations) {
          
            //FormData, object of key/value pair for form fields and values
            var fileFormData = new FormData();
            fileFormData.append('file', file);
            fileFormData.append('store_id', store_id);
            fileFormData.append('item_translations',JSON.stringify(translations));
          if(data){

          $.each(data, function(i, v){

          $.each(v, function(j, k){

          fileFormData.append(j, k);

          })

          })
          }


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
$(document).ready(function () {
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  $(document).mouseup(function(e) 
  {
      var container = $(".tooltip-content");

      // if the target of the click isn't the container nor a descendant of the container
      if (!container.is(e.target) && container.has(e.target).length === 0) 
      {
          container.hide();
      }
  });

  $(document).on('click', '.tooltip-link', function () {
    var pos = $(this).position();
    $(this).find('.tooltip-content').toggle();
  });

  // $('.menu-list > li > a').click(function() {
  //   $(this).parent('li').toggleClass('open');
  // });

  $('.menu-name .icon-pencil-edit-button').click(function () {
    $('.menu-name input').prop('readonly', false);
  });
  
});