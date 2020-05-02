<script src="{{asset('js/app.js')}}" type="text/javascript"></script>
<script src="{{asset('js/owl.carousel.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/bootstrap-select.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/bootstrap-toggle.min.js')}}" type="text/javascript"></script>
<script src="{{ url('admin_assets/js/plugins/jquery.validate.min.js') }}"></script>

<!-- angular js -->
<script src="{{asset('admin_assets/js/angular.js')}}"></script>
<script src="{{asset('admin_assets/js/angular-sanitize.js')}}"></script>
<script type="text/javascript">
  var app = angular.module('App', ['ngSanitize']);
  var jquery_datetimepicker_date_format = "{{site_setting('jquery_date_format')}}";
</script>

<!--  Google Maps Plugin  -->
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key={{site_setting('google_api_key')}}&libraries=places&sensor=false&language={{ (Session::get('language')) ? Session::get('language') : $default_language[0]->value }}"></script>
<script src="{{asset('messages.js')}}" type="text/javascript"></script>
<script src="{{asset('js/store_detail.js')}}" type="text/javascript"></script>
<script src="{{asset('js/driver.js')}}" type="text/javascript"></script>
<script src="{{asset('js/store.js')}}" type="text/javascript"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('js/common.js')}}" type="text/javascript"></script>
<script src="{{asset('js/locationpicker.jquery.js')}}?dasd" type="text/javascript"></script>

<script type="text/javascript">
    $('select').addClass('selectpicker');

    var APP_URL = {!! json_encode(url('/')) !!};
    function myMap() {
        var mapOptions = {
            center: new google.maps.LatLng(51.5, -0.12),
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.HYBRID
        }
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);
    }

// get url data
var ajax_url_list ={

    search             : '{{route("search")}}',

    store_location     : '{{route("store_location")}}',

    search_result	   : '{{route("search_result")}}',

    signup2	   		   : '{{route("signup2")}}',

    signup_data		   : '{{route("signup_data")}}',

    item_details	   : '{{route("item_details")}}',

    orders_store	   : '{{route("orders_store")}}',

    orders_remove	   : '{{route("orders_remove")}}',

    orders_change	   : '{{route("orders_change")}}',

    category_details   : '{{route("category_details")}}',

    checkout           : '{{route("checkout")}}',

    card_details       : '{{route("card_details")}}',

    place_order_details: '{{route("place_order_details")}}',

    order_track        : '{{route("order_track")}}',

    search_data        : '{{route("search_data")}}',

    session_clear_data : '{{route("session_clear_data")}}',

    place_order 	   : '{{route("place_order")}}',

    order_invoice 	   : '{{route("order_invoice")}}',

    add_cart           : '{{route("add_cart")}}',

    add_promo_code_data: '{{route("add_promo_code_data")}}',

    schedule_store     : '{{route("schedule_store")}}',

    password_change    : '{{route("password_change")}}',

    change_password    : '{{route("store.change_password")}}',

    dasboard           : '{{route("store.dashboard")}}',

    cancel_order       : '{{route("cancel_order")}}',

    location_check     : '{{route("location_check")}}',

    location_not_found : '{{route("location_not_found")}}',

    get_payout_preference : '{{route("store.get_payout_preference")}}',

    offers_status      : '{{route("store.offers_status")}}',

    remove_time        : '{{route("store.remove_time")}}',

    send_message       : '{{route("store.send_message")}}',

    confirm_phone_no   : '{{route("store.confirm_phone_no")}}',

    profile_pic_upload : '{{route("driver.profile_upload")}}',

    status_update      : '{{route("store.status_update")}}',

    show_comments      : '{{route("store.show_comments")}}',

    particular_order   : '{{route("driver.particular_order")}}',

    invoice_filter     : '{{route("driver.invoice_filter")}}',

    ajax_help_search     : '{{route("ajax_help_search")}}',

    help     : '{{route("help")}}',
};

function getUrl(key, replaceValues={}) {
	url = ajax_url_list[key];
	//console.log(url);
	var replace_url = url;
	$.each(replaceValues, function(i, v) {
     replace_url = replace_url.replace('@'+i, v);
	  //console.log(replace_url);
	});
	return replace_url;
}

function getUrls(key) {
	url = ajax_url_list[key];
	//console.log(url);
	var replace_url = url;
	return replace_url;
}
$.datepicker.setDefaults($.datepicker.regional[ "{{ (Session::get('language')) ? Session::get('language') : $default_language[0]->value }}" ])
</script>
@if(Session::get('language') != 'en')
{!! Html::script('js/i18n/datepicker-'.Session::get('language').'.js') !!}
@endif
<!-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jstimezonedetect/1.0.4/jstz.min.js">
</script>
<script type="text/javascript">
  $(document).ready(function(){
    var tz = jstz.determine(); // Determines the time zone of the browser client
    var timezone = tz.name(); //'Asia/Kolhata' for Indian Time.
     $.get('{{route("home")}}', {tz: timezone}, function(data) {
     });
  });
</script> -->
@stack('scripts')
</body></html>