<?php

/**
 * Eater Controller
 *
 * @package    GoferEats
 * @subpackage Controller
 * @category   Eater
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Currency;
use App\Models\IssueType;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PenalityDetails;
use App\Models\PromoCode;
use App\Models\Store;
use App\Models\StoreTime;
use App\Models\Review;
use App\Models\ReviewIssue;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserPaymentMethod;
use App\Models\UsersPromoCode;
use App\Models\Wallet;
use App\Models\Wishlist;
use App\Traits\FileProcessing;
use App\Traits\AddOrder;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use JWTAuth;
use Storage;
use Stripe;
use Validator;

class EaterController extends Controller {

	use FileProcessing,AddOrder;

	public function __construct() {

		parent::__construct();

	}

	/**
	 * Store Details display to Eater
	 *
	 * @param Get method request inputs
	 *
	 * @return Response Json
	 */

	public function home(Request $request) {

		$default_currency_code=DEFAULT_CURRENCY;
		$default_currency_symbol=Currency::where('code',DEFAULT_CURRENCY)->first()->symbol;	
		$default_currency_symbol=html_entity_decode($default_currency_symbol);


		if(isset(request()->token))
		{
		   $user_details = JWTAuth::parseToken()->authenticate();
		  
		   $already_cart = Order::where('user_id', $user_details->id)->status('cart')->first();
		}
		else
		{
			$user_details = '';
			$already_cart = '';
		}


		$category = Category::where('is_dietary', '1')->where('status', 1)->get();
		$category = $category->map(
			function ($item) {
				return [

					'id' => $item['id'],
					'name' => $item['name'],
					'dietary_icon' => $item['dietary_icon'],

				];
			}
		)->toArray();

		$address_details = $this->address_details();

		$perpage = 7;

		$latitude = $address_details['latitude'];
		$longitude = $address_details['longitude'];
		$order_type = $address_details['order_type'];
		$delivery_time = $address_details['delivery_time'];

		
		$store_details = (object) [];

		if ($already_cart) {

			$available = Store::find($already_cart->store_id);

			$store_details = ['image' => $available->banner, 'name' => $available->name];

		}

		if (isset($request->order_type)) {

			if($user_details)
			{

			$address = UserAddress::where('user_id', $user_details->id)->default()->first();
			$address->order_type = $request->order_type;
			$address->save();

		    }

		}

		//more Store

		$date = \Carbon\Carbon::today();

		$common = User::with(
			['store' => function ($query) use ($latitude, $longitude, $date) {
				$query->with(['store_category', 'review', 'store_offer', 'user_address', 'store_time']);
			}]
		)->Type('store')->whereHas('store', function ($query) use ($latitude, $longitude) {

			$query->location($latitude, $longitude)->whereHas('store_time', function ($query) {

			});

		})->status();



		$more_store = (clone $common)->paginate($perpage);

		$count_store = $more_store->lastPage();

		$more_store = $this->common_map($more_store);

		// New store

		$date = \Carbon\Carbon::today()->subDays(10);

		$new_store = (clone $common)->where('created_at', '>=', $date)->paginate($perpage);

		$count_new_store = $new_store->lastPage();

		$new_store = $this->common_map($new_store);
		// Store Offer

		$store_offer = (clone $common)->whereHas(
			'store',
			function ($query) use ($date) {
				$query->whereHas(
					'store_offer',
					function ($query) use ($date) {
						$query->where('start_date', '<=', $date)->where('end_date', '>=', $date);
					}
				);
			}
		)->paginate($perpage);

		$count_offer = $store_offer->lastPage();

		$store_offer = $store_offer->map(
			function ($item) {
				return [

					'store_id' => $item['store']['id'],
					'name' => $item['store']['name'],
					'banner' => $item['store']['banner'],
					'title' => $item['store']['store_offer'][0]['offer_title'],
					'description' => $item['store']['store_offer'][0]['offer_description'],
					'percentage' => $item['store']['store_offer'][0]['percentage'],

				];
			}
		);

		// Under prepartion time min

		$under = (clone $common)->get();


		$count_under = 0;

		$convert_mintime = 0;
		if (count($under) != 0) {

			$under = $under->sortBy(
				function ($unders) {
					return $unders->store->convert_mintime;
				}
			)->values();

			$max_time = $under[0]['store']['max_time'];

			$convert_mintime = $under[0]['store']['convert_mintime'];

			$under_minutes = (clone $common)->whereHas(
				'store',
				function ($query) use ($max_time) {
					$query->where('max_time', $max_time);
				}
			)->paginate($perpage);

			$count_under = $under_minutes->lastPage();

			$under_minutes = $this->common_map($under_minutes);
		}
		
		// Wishlist

		$wish = Wishlist::selectRaw('*,store_id as ids, (SELECT count(store_id) FROM wishlist WHERE store_id = ids) as count')->with(

			['store' => function ($query) use ($latitude, $longitude) {

				$query->with(['store_category', 'review', 'user', 'store_time', 'store_offer']);
			}]
		)->whereHas('store', function ($query) use ($latitude, $longitude) {

			$query->UserStatus()->location($latitude, $longitude)->whereHas('store_time', function ($query) {

			});

		});



		if($user_details)
		{

			$wishlist = (clone $wish)->where('user_id', $user_details->id)->paginate($perpage);

			$count_fav = $wishlist->lastPage();

			$wishlist = $this->common_map($wishlist);

		}		
	    else
	    {
	    	$wishlist = [];
	    	$count_fav = 0;
	    }
		

		// Popular Store

		$popular = (clone $wish)->groupBy('store_id')->orderBy('count', 'desc')
			->paginate($perpage);

		$count_popular = $popular->lastPage();

		$popular = $this->common_map($popular);

		$more_store = (count($more_store) > 0) ? $more_store->toArray() : array(); // more store
		$fav = (count($wishlist) > 0) ? $wishlist->toArray() : array(); // favourite store
		$under_minutes = (count($under) > 0) ? $under_minutes->toArray() : array();
		$store_offer = (count($store_offer) > 0) ? $store_offer->toArray() : array();
		$new_store = (count($new_store) > 0) ? $new_store->toArray() : array();

		if($user_details)
		$wallet = Wallet::where('user_id', $user_details->id)->first();

		return response()->json(
			[

				'status_message' => "Success",

				'status_code' => '1',

				'under_time' => $convert_mintime,

				'More Store' => $more_store,

				'Favourite Store' => $fav,

				'Popular Store' => $popular,

				'New Store' => $new_store,

				'Under Store' => $under_minutes,

				'Store Offer' => $store_offer,

				// page count

				'more_count' => $count_store,

				'fav_count' => $count_fav,

				'popular_count' => $count_popular,

				'under_count' => $count_under,

				'offer_count' => $count_offer,

				'new_count' => $count_new_store,

				'wallet_amount' => isset($wallet->amount) ? $wallet->amount : 0,

				'wallet_currency' => isset($wallet->currency_code) ? $wallet->currency_code : DEFAULT_CURRENCY,

				'cart_details' => $store_details,

				'home_categories' => [trans('api_messages.home.more_store'), trans('api_messages.home.favourite_store'), trans('api_messages.home.popular_store'), trans('api_messages.home.new_store'), trans('api_messages.home.under_store')],

				'category' => $category,
				'default_currency_code'=>$default_currency_code,
				'default_currency_symbol'=>$default_currency_symbol,

			]
		);
	}


	/**
	 * API for Ios
	 *
	 * @return Response Json response with status
	 */

	public function ios(Request $request) {

	  	if(isset($_POST['token']))
		$user_details =  JWTAuth::toUser($_POST['token']);
		else
			$user_details = JWTAuth::parseToken()->authenticate();

  	     $request = request();

		$order = Order::getAllRelation()/*->where('user_id', $user_details->id)*/->where('id', $request->order_id)->first();

        $rating = str_replace('\\', '', $request->rating);

    	$rating = json_decode($rating);

		$order_id = $order->id;

		$food_item = $rating->food;

		//Rating for Menu item

		if ($food_item) {

			foreach ($food_item as $key => $value) {

				$review = new Review;
				$review->order_id = $order_id;
				$review->type = $review->typeArray['user_menu_item'];
				$review->reviewer_id = $user_details->id;
				$review->reviewee_id = $value->id;
				$review->is_thumbs = $value->thumbs;
				$review->order_item_id = $value->order_item_id;
				$review->comments = $value->comment ?: "";
				$review->save();

				if ($value->reason) {
					$issues = explode(',', $value->reason);
					if ($request->thumbs == 0 && count($value->reason)) {
						foreach ($issues as $issue_id) {
							$review_issue = new ReviewIssue;
							$review_issue->review_id = $review->id;
							$review_issue->issue_id = $issue_id;
							$review_issue->save();
						}
					}

				}

			}

		}

		//Rating for driver

		if (count(get_object_vars($rating->driver)) > 0) {

			$review = new Review;
			$review->order_id = $order_id;
			$review->type = $review->typeArray['user_driver'];
			$review->reviewer_id = $user_details->id;
			$review->reviewee_id = $order->driver_id;
			$review->is_thumbs = $rating->driver->thumbs;
			$review->comments = $rating->driver->comment ?: "";
			$review->save();

			if ($rating->driver->reason) {
				$issues = explode(',', $rating->driver->reason);
				if ($rating->driver->thumbs == 0 && count($issues)) {
					foreach ($issues as $issue_id) {
						$review_issue = new ReviewIssue;
						$review_issue->review_id = $review->id;
						$review_issue->issue_id = $issue_id;
						$review_issue->save();
					}
				}

			}

		}

		//Rating for Store

		if (count(get_object_vars($rating->store)) > 0) {

			$review = new Review;
			$review->order_id = $order_id;
			$review->type = $review->typeArray['user_store'];
			$review->reviewer_id = $user_details->id;
			$review->reviewee_id = $order->store_id;
			$review->rating = $rating->store->thumbs;
			$review->comments = $rating->store->comment ?: "";
			$review->save();

		}
		return response()->json(
			[
				'status_message' => 'Updated Successfully',
				'status_code' => '1',
			]
		);
	}




	/**
	 * API for Search
	 *
	 * @return Response Json response with status
	 */

	public function categories(Request $request) {

		$top_category = Category::where('is_top', 1)->get();

		$more_category = Category::where('is_top', 0)->get();

		return response()->json(
			[

				'status_message' => "Success",
				'status_code' => '1',
				'top_category' => $top_category,
				'category' => $more_category,

			]
		);
	}

	public function search(Request $request) {

		$user_details ='';

		if(request()->token)
		{
		   $user_details = JWTAuth::parseToken()->authenticate();
		}

		$address_details = $this->address_details();
		return store_search($user_details, $address_details, $request->keyword);
	}

	/**
	 * API for An Store Details
	 *
	 * @return Response Json response with status
	 */

	public function get_store_details(Request $request) {

		  $user_details = '';

			if(request()->token)
			{

			    $user_details = JWTAuth::parseToken()->authenticate();

				if (isset($request->order_type)) {

				$address = UserAddress::where('user_id', $user_details->id)->default()->first();
				$address->order_type = $request->order_type;
				$address->save();

				}

			}


			$rules = array(
				'store_id' => 'required',
			);
			$messages = array(
            'required'                => ':attribute '.trans('api_messages.register.field_is_required').'', 
            
       		 );

			$niceNames = array(
				'store_id' => trans('api_messages.store.store_id'),
			
			);

		$validator = Validator::make($request->all(), $rules,$messages);
		$validator->setAttributeNames($niceNames);
		if ($validator->fails()) {
			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);
			}
			return ['status_code' => '0', 'status_message' => $error_msg['0']['0']['0']];
		} else {

		

		$address_details = $this->address_details();

		$latitude = $address_details['latitude'];
		$longitude = $address_details['longitude'];
		$order_type = $address_details['order_type'];
		$delivery_time = $address_details['delivery_time'];

		$date = \Carbon\Carbon::today();

		$store_details = Store::with(
			[
				'store_category', 'review',
				'store_preparation_time',
				'store_offer',
				'store_time',
				'store_menu' => function ($query) {
					$query->with(
						['menu_category' => function ($query) {
							$query->with(
								['menu_item' => function ($query) {
									$query->with(
										['menu_item_main_addon' => function ($query) {
											$query->with('menu_item_sub_addon');
										},

										]
									);
								}]
							)->has('menu_item');
						}]
					)->has('menu_category.menu_item');
				},

				'file' => function ($queryB) {
					$queryB->where('type', '4');
				},

			]
		)->where('id', $request->store_id)->UserStatus()->location($latitude, $longitude)->get();

		$store_details = $store_details->mapWithKeys(
			function ($item) use ($user_details, $delivery_time, $order_type) {
				$store_category = $item['store_category']->map(
					function ($item) {
						return $item['category_name'];
					}
				)->toArray();
                  $wishlist = 0;
				if(request()->token)
				{
					$wishlist = $item->wishlist($user_details->id, $item['id']);
				}
					$open_time = $item['store_time']['start_time']; 
				return [

					'order_type' => $order_type,
					'delivery_time' => $delivery_time,
					'store_id' => $item['id'],
					'name' => $item['name'],
					'category' => implode(',', $store_category),
					'banner' => $item['banner'],
					'min_time' => $item['convert_mintime'],
					'max_time' => $item['convert_maxtime'],
					'wished' => $wishlist ,
					'status' => $item['status'],
					'store_menu' => $item['store_menu'],
					'store_rating' => $item['review']['store_rating'],
					'price_rating' => $item['price_rating'],
					'average_rating' => $item['review']['average_rating'],
					'store_closed' => $item['store_time']['closed'],
					'store_open_time' =>$open_time,
					'store_next_time' => $item['store_next_opening'],

					'store_offer' => $item['store_offer']->map(

						function ($item) {

							return [

								'title' => $item->offer_title,
								'description' => $item->offer_description,
								'percentage' => $item->percentage,

							];
						}
					),

				];
			}
		);

		$store_details = $store_details->toArray();

		if (count($store_details) > 0) {

			return response()->json(
				[

					'status_message' => trans('api_messages.success'),

					'status_code' => '1',

					'store_details' => $store_details,

				]
			);
		} else {

			$store = Store::find($request->store_id);
			$store_name = $store->name;
			$store_category = $store->store_category[0]['category_name'];

			$check_address = check_store_location('', $latitude, $longitude, $request->store_id);

			if ($store->status == 0 || $check_address == 0) {

				return response()->json(
					[

						'status_message' => trans('api_messages.store.unavailable'),

						'status_code' => '2',

						'messages' => trans('api_messages.store.it_look_like') . $store_name . trans('api_messages.store.close_enough'),
						'category' => $store_category,

					]
				);

			}

			return response()->json(
				[

					'status_message' => trans('api_messages.store.store_inactive'),

					'status_code' => '3',

					'messages' =>trans('api_messages.store.it_look_like'). $store_name . trans('api_messages.store.currently_unavailable'),

					'category' => $store_category,

				]
			);

		}
	}

	}

	/**
	 * API for Add Promo details
	 *
	 * @return Response Json response with status
	 */

	public function add_promo_code(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		$rules = array(
			'code' => 'required',
		);
		$messages = array(
            'required'                => ':attribute '.trans('api_messages.register.field_is_required').'', 
            
        );
        $niceNames = array(
        	'code' => trans('api_messages.add_promo_code.code')
        	);
		$validator = Validator::make($request->all(), $rules,$messages);
		$validator->setAttributeNames($niceNames);
		if ($validator->fails()) {
			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);
			}
			return ['status_code' => '0', 'status_message' => $error_msg['0']['0']['0']];
		} else {
			$code=$request->code;
			$promo_code_date_check = PromoCode::with('promotranslation')->where(function($query) use($code){

				$query->whereHas('promotranslation',function($query1) use($code)
				{
					$query1->where('code',$code);
				})->orWhere('code',$code);

			})->where('end_date', '>=', date('Y-m-d'))->first();

			if ($promo_code_date_check) {

				$user_promocode = UsersPromoCode::where('promo_code_id', $promo_code_date_check->id)->where('user_id', $user_details->id)->first();

				if ($user_promocode) {
					return ['status_code' => '0', 'status_message' => trans('api_messages.add_promo_code.promo_code_already_applied')];
				} else {
					$users_promo_code = new UsersPromoCode;
					$users_promo_code->user_id = $user_details->id;
					$users_promo_code->promo_code_id = $promo_code_date_check->id;
					$users_promo_code->order_id = 0;
					$users_promo_code->save();
				}

				$user_promocode = UsersPromoCode::WhereHas(
					'promo_code',
					function ($q) {
					}
				)->where('user_id', $user_details->id)->where('order_id', '0')->get();

				$final_promo_details = [];

				foreach ($user_promocode as $row) {
					if (@$row->promo_code) {
						$promo_details['id'] = $row->promo_code->id;
						$promo_details['price'] = $row->promo_code->price;
						$promo_details['type'] = $row->promo_code->promo_type;
						$promo_details['percentage'] = $row->promo_code->percentage;
						$promo_details['code'] = $row->promo_code->code;
						$promo_details['expire_date'] = $row->promo_code->end_date;
						$final_promo_details[] = $promo_details;
					}
				}

				$user = array('promo_details' => $final_promo_details, 'status_message' => trans('api_messages.add_promo_code.promo_applied_successfully'), 'status_code' => '1');
				return response()->json($user);
			} else {

				$promo_code = PromoCode::with('promotranslation')->where(function($query)use ($code){

					$query->whereHas('promotranslation',function($query1) use($code)
					{
						$query1->where('code',$code);

					})->orWhere('code',$code);

					})->where('end_date', '<', date('Y-m-d'))->first();

				if ($promo_code) {
					return ['status_code' => '0', 'status_message' => trans('api_messages.add_promo_code.promo_code_expired')];
				} else {
					return ['status_code' => '0', 'status_message' => trans('api_messages.add_promo_code.invalid_code')];
				}

			}
		}
	}

	/**
	 * API for Promo details
	 *
	 * @return Response Json response with status
	 */

	public function get_promo_details(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		$user_promocode = UsersPromoCode::WhereHas(
			'promo_code',
			function ($q) {
			}
		)->where('user_id', $user_details->id)->where('order_id', '0')->get();

		$final_promo_details = [];

		foreach ($user_promocode as $row) {
			if (@$row->promo_code) {
				$promo_details['id'] = $row->promo_code->id;
				$promo_details['price'] = $row->promo_code->price;
				$promo_details['type'] = $row->promo_code->promo_type;
				$promo_details['percentage'] = $row->promo_code->percentage;
				$promo_details['code'] = $row->promo_code->code;
				$promo_details['expire_date'] = $row->promo_code->end_date;
				$final_promo_details[] = $promo_details;
			}
		}
		$user = array('promo_details' => $final_promo_details, 'status_message' =>trans('api_messages.success'), 'status_code' => '1');
		return response()->json($user);
	}

	public function get_location(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();



		$address = UserAddress::where('user_id', $user_details->id)->orderBy('type', 'ASC')->get()->map(function ($val) {

			/* For IOS */

        $val->first_address = str_replace('', Null, $val->first_address);  
        $val->second_address = str_replace('', Null, $val->second_address);
        $val->apartment = str_replace('', Null, $val->apartment);
        $val->street = str_replace('', Null, $val->street);
        $val->postal_code = str_replace('', Null, $val->postal_code);
        $val->city = str_replace('', Null, $val->city);
        $val->address = str_replace('', Null, $val->address);
        $val->address1 = str_replace('', Null, $val->address1);

        /* For IOS */
        
        return $val;

         });


		$user = array(

			'status_message' => 'Success',

			'status_code' => '1',

			'user_address' => $address,

		);

		return response()->json($user);
	}

	/**
	 * API for Set Save Location
	 *
	 * @return Response Json response with status
	 */

	public function saveLocation() {

		$request = request();

		$user_details = JWTAuth::parseToken()->authenticate();

		$already_cart = Order::where('user_id', $user_details->id)->status('cart')->first();

		if ($already_cart) {

			$check_address = check_store_location($already_cart['id'], $request->latitude, $request->longitude);

			if ($check_address == 0) {

				$address = UserAddress::where('user_id', $user_details->id)->orderBy('type', 'ASC')->get();

				 return response()->json(['status_message' => 'Store unavailable', 'status_code' => '2', 'user_address' => $check_address]);
			}

		}

		if($request->type==2)
		{
			UserAddress::where('user_id', $user_details->id)->update(['default' => 0]);		
		}
			
		$address = UserAddress::where('user_id', $user_details->id)->where('type', $request->type)->first();



		if ($request->type == 2) {			
			$default = 1;
		} 
		else if(@$address->default ==1 )
		{
			$default = 1;

		}
		else
		{

			$add = UserAddress::where('user_id', $user_details->id)->where('default','!=',1)
			->update(['default' => 0]);

			if(!$add)
			{
			  $default =0;
			}

		}

		if (count($address) == 0) {

			$address = new UserAddress;
			$address->user_id = $user_details->id;
		}



		$address->street = $request->street;
		$address->city = $request->city;
		$address->state = $request->state;
		$address->first_address = $request->first_address;
		$address->second_address = $request->second_address;
		$address->postal_code = $request->postal_code;
		$address->country = $request->country;
		$address->country_code = $request->country_code;
		$address->type = $request->type;		
		$address->default = $default;
		$address->apartment = $request->apartment;
		$address->delivery_note = $request->delivery_note ? $request->delivery_note : '';
		$address->delivery_options = $request->delivery_options ? $request->delivery_options : '';
		$address->order_type = $request->order_type ? $request->order_type : '';
		$address->delivery_time = $request->delivery_time ? $request->delivery_time : '';
		$address->latitude = $request->latitude;
		$address->longitude = $request->longitude;
		$address->address = $request->address;
		$address->save();


	$address_details= UserAddress::where('user_id', $user_details->id)->orderBy('type', 'ASC')->get();


		$user = array(

			'status_message' => 'Success',

			'status_code' => '1',

			'user_address' => $address_details,

		);

		return response()->json($user);
	}

	/**
	 * API for Set Default Location
	 *
	 * @return Response Json response with status
	 */

	public function defaultLocation(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		$rules = [
			'default' => 'required|exists:user_address,type,user_id,' . $user_details->id,
		];

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return response()->json(
				[
					'status_message' => $validator->messages()->first(),
					'status_code' => '0',
				]
			);
		}

		$add = UserAddress::where('type', $request->default)->where('user_id', $user_details->id)->first();

		$already_cart = Order::where('user_id', $user_details->id)->status('cart')->first();

		if ($already_cart) {

			$check_address = check_store_location($already_cart['id'], $add->latitude, $add->longitude);

			if ($check_address == 0) {

				$address = UserAddress::where('user_id', $user_details->id)->orderBy('type', 'ASC')->get();

				return response()->json(['status_message' => 'Store unavailable', 'status_code' => '2', 'user_address' => $check_address]);
			}

		}

		UserAddress::where('default', 1)->where('user_id', $user_details->id)->update(['default' => 0]);

		$user_address = UserAddress::where('user_id', $user_details->id)->where('type', $request->default)->first();
		$user_address->default = 1;
		$user_address->order_type = $request->order_type ? $request->order_type : '';
		$user_address->delivery_time = $request->delivery_time ? $request->delivery_time : '';
		$user_address->save();

		$user = array(

			'status_message' => 'Success',

			'status_code' => '1',

		);

		return response()->json($user);
	}

	/**
	 * API for Remove Location
	 *
	 * @return Response Json response with status
	 */

	public function remove_location(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		$remove = UserAddress::where('type', $request->type)->where('user_id', $user_details->id)->first();

		if ($remove) {

			$remove->delete();

			if ($remove->default == 1) {

				$update_default = UserAddress::where('user_id', $user_details->id)->first();
				$update_default->default = 1;
				$update_default->save();

			}

		}

		$address = UserAddress::where('user_id', $user_details->id)->get();

		$user = array(

			'status_message' => 'Success',

			'status_code' => '1',

			'user_address' => $address,

		);

		return response()->json($user);
	}

	/**
	 * API for Wishlist
	 *
	 * @return Response Json response with status
	 */

	public function add_wish_list(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		$user = User::where('id', $user_details->id)->first();

		if (count($user)) {
			$store_id = $request->store_id;

			$wishlist = Wishlist::where('user_id', $user_details->id)->where('store_id', $store_id)->first();

			if (count($wishlist)) {

				$wishlist->delete();

				return response()->json(
					[

						'status_message' => "unwishlist Success",

						'status_code' => '1',

					]
				);
			} else {
				$wishlist = new Wishlist;
				$wishlist->store_id = $store_id;
				$wishlist->user_id = $user_details->id;
				$wishlist->save();

				return response()->json(
					[

						'status_message' => "wishlist Success",

						'status_code' => '1',

					]
				);
			}
		} else {
			return response()->json(
				[

					'status_message' => "Invalid credentials",

					'status_code' => '0',

				]
			);
		}
	}

	/**
	 * API for update eater profile details
	 *
	 * @return Response Json response with status
	 */

	public function update_profile(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		$rules = array(

			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required',

		);

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);
			}
			return ['status_code' => '0', 'status_message' => $error_msg['0']['0']['0']];
		} else {
			$user_check = User::where('id', $user_details->id)->first();

			if (count($user_check)) {
				User::where('id', $user_details->id)->update(
					['name' => html_entity_decode($request->first_name . ' ' . $request->last_name), 'email' => html_entity_decode($request->email),
					]
				);

				$user = User::where('id', $user_details->id)->first();

				return response()->json(
					[

						'status_message' => 'Updated Successfully',

						'status_code' => '1',

						'name' => $user->name,

						'mobile_number' => $user->mobile_number,

						'country_code' => $user->country_code,

						'email_id' => $user->email,

						'profile_image' => $user->eater_image,

					]
				);
			} else {
				return response()->json(
					[

						'status_message' => "Invalid credentials",

						'status_code' => '0',

					]
				);
			}
		}
	}

	/**
	 * API for Eater image
	 *
	 * @return Response Json response with status
	 */

	public function upload_image(Request $request) {

		if(isset($_POST['token']))

		$user_details =  JWTAuth::toUser($_POST['token']);

		else

		$user_details = JWTAuth::parseToken()->authenticate();

		$rules = array('image' => 'required');

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {

			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);
			}

			return ['status_code' => '0', 'status_message' => $error_msg['0']['0']['0']];

		} else {

			$user_check = User::where('id', $user_details->id)->first();

			if (count($user_check)) {

				$file = $request->file('image');

				$file_path = $this->fileUpload($file, 'public/images/eater');

				$this->fileSave('eater_image', $user_details->id, $file_path['file_name'], '1');
				$orginal_path = Storage::url($file_path['path']);

				$user = User::where('id', $user_details->id)->first();

				return response()->json(
					[

						'status_message' => 'Updated Successfully',

						'status_code' => '1',

						'name' => $user->name,

						'mobile_number' => $user->mobile_number,

						'country_code' => $user->country_code,

						'email_id' => $user->email,

						'profile_image' => $user->eater_image,

					]
				);
			} else {

				return response()->json(
					[

						'status_message' => "Invalid credentials",

						'status_code' => '0',

					]
				);
			}
		}
	}

	/**
	 * API for Add to cart
	 *
	 * @return Response Json response with status
	 */

	public function add_to_cart(Request $request) {

		$data =  $this->add_cart_item($request,1);

		if($data['status_code']!=1)
		{
				return response()->json([

				'status_message' => $data['status_message'],

				'status_code' => $data['status_code'],

			]);
		}

			return response()->json(
			[

				'status_message' => 'Updated Successfully',

				'status_code' => '1',

				'subtotal' => $data['subtotal'],

				'quantity' => $data['quantity'],

			]
		);

	
	}

/**
 * API for without token Add to cart
 *
 * @return Response Json response with status
 */

	public function without_add_to_cart(Request $request)
	{

		$menu = MenuItem::where('id', $request->menu_item_id)->first();

		//check menu item Available or not

		if (!$menu) {

			return response()->json(
				[

					'status_message' => 'Menu item not available at the moment',

					'status_code' => '2',

				]
			);

		}
      
      return response()->json(
			[

				'status_message' => 'Updated Successfully',

				'status_code' => '1',

				'subtotal' => 0,

				'quantity' => 1,

			]
		);

	}
	

/**
 * API for view cart
 *
 * @return Response Json response with status
 */

	public function view_cart(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		$address_details = $this->address_details();

		$order_type = $address_details['order_type'];
		$delivery_time = $address_details['delivery_time'];

		//Check Cart

		$cart_order = Order::getAllRelation()->where('user_id', $user_details->id)->status('cart')->first();

		if (!$cart_order) {

			return response()->json(
				[

					'status_message' => trans('api_messages.cart.cart_empty'),

					'status_code' => '2',

				]
			);
		}

		// Order menu Available or not

		if ($order_type == 0) {

			$date = date('Y-m-d H:i');

		} else {

			$date = $delivery_time;

		}

		$check_menu = check_menu_available($cart_order->id, $date);

		if (count($check_menu) > 0) {

			return response()->json(
				[

					'status_message' => trans('api_messages.cart.item_not_available'),

					'status_code' => '3',

					'unavailable' => $check_menu,

				]
			);

		}

		$delivery_address = UserAddress::where('user_id', $user_details->id)->default()->first();

		//check address

		if (!$delivery_address) {

			$delivery_address = '';

			return response()->json(['status_message' => trans('api_messages.cart.address_empty'), 'status_code' => '0']);

		} else {

			$check_address = check_location($cart_order['id']);

			if ($check_address == 0) {

				return response()->json(['status_message' => trans('api_messages.cart.store_unavailable'), 'status_code' => '0']);

			}

		}

		$update_order = Order::find($cart_order->id);

		$update_order->schedule_status = $order_type;
		$update_order->schedule_time = $delivery_time;
		$update_order->save();

		$offer_amount = offer_calculation($cart_order->store_id, $cart_order->id);
		$promo_amount = promo_calculation();
		$penality = penality($cart_order->id);

		//wallet apply

		$is_wallet = $request->isWallet;

		$wallet_amount = use_wallet_Amount($cart_order->id, $is_wallet);

		$cart_order = Order::getAllRelation()->where('user_id', $user_details->id)->status('cart')->first();

		$cart_details = $cart_order->toArray();

		$data = [

			'id' => $cart_details['id'],
			'store_id' => $cart_details['store_id'],
			'user_id' => $cart_details['user_id'],
			'address' => $delivery_address,
			'driver_id' => $cart_details['driver_id'],
			'subtotal' => $cart_details['subtotal'],
			'offer_percentage' => $cart_details['offer_percentage'],
			'offer_amount' => $cart_details['offer_amount'],
			'promo_id' => $cart_details['promo_id'],
			'promo_amount' => $cart_details['promo_amount'],
			'delivery_fee' => $cart_details['delivery_fee'],
			'booking_fee' => $cart_details['booking_fee'],
			'store_commision_fee' => $cart_details['store_commision_fee'],
			'driver_commision_fee' => $cart_details['driver_commision_fee'],
			'tax' => $cart_details['tax'],
			'total_amount' => $cart_details['total_amount'],
			'wallet_amount' => $cart_details['wallet_amount'],
			'payment_type' => $cart_details['payment_type'],
			'owe_amount' => $cart_details['owe_amount'],
			'status' => $cart_details['status'],
			'payout_status' => $cart_details['payout_status'],
			'store_status' => $cart_details['store_status'],
			'penality' => $cart_details['user_penality'],

		];

		$data['invoice'] = [
			array('key' =>trans('api_messages.cart.subtotal'), 'value' => $cart_details['subtotal']),
			array('key' =>trans('api_messages.cart.delivery_fee'), 'value' => $cart_details['delivery_fee']),
			array('key' =>trans('api_messages.cart.booking_fee'), 'value' => $cart_details['booking_fee']),
			array('key' =>trans('api_messages.cart.tax'), 'value' => $cart_details['tax']),
			array('key' =>trans('api_messages.cart.promo_amount'), 'value' => $cart_details['promo_amount']),
			array('key' =>trans('api_messages.cart.wallet_amount'), 'value' => $cart_details['wallet_amount']),
			array('key' =>trans('api_messages.cart.total'), 'value' => $cart_details['total_amount']),
		];

		$data['store'] = $cart_details['store'];
		$order_item = $cart_details['order_item'];

		foreach ($order_item as $order_item) {

			$data['menu_item'][] = ['order_item_id' => $order_item['id'],
				'order_id' => $order_item['order_id'],
				'menu_item_id' => $order_item['menu_item_id'],
				'price' => $order_item['price'],
				'quantity' => $order_item['quantity'],
				'modifier_price' => $order_item['modifier_price'],
				'total_amount' => $order_item['total_amount'],
				'offer_price' => $order_item['offer_price'],
				'tax' => $order_item['tax'],
				'notes' => $order_item['notes'],
				'id' => $order_item['menu_item']['id'],
				'is_visible' => $order_item['menu_item']['is_visible'],
				'is_offer' => $order_item['menu_item']['is_offer'],
				'menu_id' => $order_item['menu_item']['menu_id'],
				'menu_category_id' => $order_item['menu_item']['menu_category_id'],
				'name' => $order_item['menu_item']['name'],
				'description' => $order_item['menu_item']['description'],
				'tax_percentage' => $order_item['menu_item']['tax_percentage'],
				'type' => $order_item['menu_item']['type'],
				'status' => $order_item['menu_item']['status'],
				'menu_item_image' => $order_item['menu_item']['menu_item_image'],
				'menu_item_main_addon' => $order_item['menu_item']['menu_item_main_addon'],

			];
		}

		$user_promocode = UsersPromoCode::WhereHas(
			'promo_code',
			function ($q) {
			}
		)->where('user_id', $user_details->id)->where('order_id', '0')->get();

		$final_promo_details = [];

		foreach ($user_promocode as $row) {
			if (@$row->promo_code) {
				$promo_details['id'] = $row->promo_code->id;
				$promo_details['price'] = $row->promo_code->price;
				$promo_details['code'] = $row->promo_code->code;
				$promo_details['expire_date'] = $row->promo_code->end_date;
				$final_promo_details[] = $promo_details;
			}
		}

		return response()->json(
			[

				'status_message' => trans('api_messages.cart.updated_successfully'),

				'status_code' => '1',

				'cart_details' => $data,

				'promo_details' => $final_promo_details,

			]
		);
	}
	//testing purpose
	public function unicode_decode($data) {
		/*$uniCode = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $data);*/

    	$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\\\1;",urldecode($data));
    	//dd($str);
    	return $str;
	}
	/**
	 * API for order item
	 *
	 * @return Response Json response with status
	 */

	public function clear_cart(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		$remove_order = OrderItem::find($request->order_item_id);

		if ($remove_order) {

			$remove_order->delete();

		}

		$orderitem = OrderItem::where('order_id', $request->order_id)->count();

		if ($orderitem == 0) {

			$remove_order_delivery = OrderDelivery::where('order_id', $request->order_id)->first();

			if ($remove_order_delivery) {

				$remove_order_delivery->delete();

			}

			$order = Order::find($request->order_id);

			if ($order) {

				$remove_penality = PenalityDetails::where('order_id', $order->id)->first();

				if ($remove_penality) {
					$remove_penality->delete();
				}

				$order->delete();

				//ASAP

				$address = UserAddress::where('user_id', $user_details->id)->default()->first();
				$address->order_type = 0;
				$address->save();
			}

		}

		return response()->json(
			[

				'status_message' => 'Removed Successfully',

				'status_code' => '1',

			]
		);
	}

	/**
	 * API for order with order item
	 *
	 * @return Response Json response with status
	 */

	public function clear_all_cart(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		$order = Order::where('user_id', $user_details->id)->status('cart')->first();
		$checkNull = is_null($order);
		if($checkNull){
			return response()->json(
			[

				'status_message' => trans('api_messages.unable_to_remove'),

				'status_code' => '0',

			]
		);
		}			
		$remove_order = OrderItem::where('order_id', $order->id);

		if ($remove_order) {

			$remove_order->delete();
		}

		$remove_order_delivery = OrderDelivery::where('order_id', $order->id)->first();

		if ($remove_order_delivery) {

			$remove_order_delivery->delete();

		}

		$remove_penality = PenalityDetails::where('order_id', $order->id)->first();

			if ($remove_penality) {
				$remove_penality->delete();
			}

		$order = Order::find($order->id)->delete();

		return response()->json(
			[

				'status_message' => trans('api_messages.removed_successfully'),

				'status_code' => '1',

			]
		);
	}

	/**
	 * API for Order history and upcoming order
	 *
	 * @return Response Json response with status
	 */

	public function order_list(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		$order_list = Order::getAllRelation()->where('user_id', $user_details->id)->history()->orderBy('id', 'DESC')->get();
		$upcoming = Order::getAllRelation()->where('user_id', $user_details->id)->upcoming()->orderBy('id', 'DESC')->get();

		$order_list = $order_list->map(
			function ($item) {
				$menu_item = $item['order_item']->map(
					function ($item) {
						return [

							'quantity' => $item['quantity'],
							'menu_item_id' => $item['menu_item']['id'],
							'item_image' => $item['menu_item']['menu_item_image'],
							'price' => $item['total_amount'],
							'menu_name' => $item['menu_item']['name'],
							'type' => $item['menu_item']['type'],
							'status' => $item['menu_item']['status'],
							'review' => $item['review'] ? $item['review']['is_thumbs'] : 2,
						];
					}
				)->toArray();

				$rating = "0";
				$contact = '';

				if ($item->driver_id && $item->driver) {

					if ($item->driver->review) {
						$rating = $item->driver->review->user_to_driver_rating;
					}

					$contact = $item->driver->driver_contact;

				}

				$user_id = get_store_user_id($item['store']['id']);

				$store_address = get_store_address($user_id);

				$user_address = get_user_address($item['user_id']);

				$star_rating = '0.0';
				$is_rating = 0;
				$food_status = [];

				if ($item->status_text == 'completed') {

					$food_status[] = [

						'time' => $item->completed_at->format('h:i').' '.trans('api_messages.monthandtime.'.$item->completed_at->format('a')),
						'status' => trans('api_messages.orders.ready_to_eat'),
					];

				}

				if (($item->status_text == 'delivery' || $item->status_text == 'completed') && isset($item->order_delivery->started_at)) {

					$delivery_at = (string) date('h:i', strtotime($item->delivery_at)).' '.trans('api_messages.monthandtime.'.date('a', strtotime($item->delivery_at)));

					$food_status[] = [

						'time' => $delivery_at,
						'status' => trans('api_messages.orders.item_on_the_way'),
					];
				}

				if ($item->status_text == 'accepted' || $item->status_text == 'completed') {

					$food_status[] = [

						'time' => $item->accepted_at->format('h:i').' '.trans('api_messages.monthandtime.'.$item->accepted_at->format('a')),
						'status' => trans('api_messages.orders.preparing_your_item'),
					];

					$food_status[] = [

						'time' => $item->accepted_at->format('h:i').' '.trans('api_messages.monthandtime.'.$item->accepted_at->format('a')),
						'status' => trans('api_messages.orders.order_accepted'),
					];

					$is_rating = $item->review->user_atleast == 1 ? 1 : 0;

					$star_rating = $item->review !== Null ? $item->review->star_rating : '0';
				}
				$show_date = ($item->order_status == 4) ? date('d F Y h:i a', strtotime($item['cancelled_at'])) : date('d F Y h:i a', strtotime($item['updated_at']));

				$total_amount = $item['total_amount'];

				if($item['status']==4)
				{
                      $total_amount = '0.0';
				}

				$getOpenTime = $item['store']['store_time']['start_time'];
				$get_show_date = date('d', strtotime($show_date)).' '.trans('api_messages.monthandtime.'.date('M', strtotime($show_date))).' '.date('Y', strtotime($show_date)).' '.date('h:i', strtotime($show_date)).' '.trans('api_messages.monthandtime.'.date('a', strtotime($show_date)));
				$est_time = date('h:i', strtotime($item->est_delivery_time)).' '.trans('api_messages.monthandtime.'.date('a', strtotime($item->est_delivery_time)));

				return [

					'order_id' => $item['id'],
					'total_amount' => $total_amount,
					'subtotal' => $item['subtotal'],
					'delivery_fee' => $item['delivery_fee'],
					'booking_fee' => $item['booking_fee'],
					'tax' => $item['tax'],
					'wallet_amount' => $item['wallet_amount'],
					'promo_amount' => $item['promo_amount'],
					'order_status' => $item['status'],
					'name' => $item['store']['name'],
					'store_id' => $item['store']['id'],
					'store_status' => $item['store']['status'],
					'store_open_time' => $getOpenTime,
					'status' => $item['status'],
					'store_banner' => $item['store']['banner'],
					'date' => $get_show_date ,
					'menu' => $menu_item,
					'total_seconds' => $item->user_total_seconds,
					'remaining_seconds' => $item->user_remaining_seconds,
					'user_status_text' => $item->user_status_text,
					'est_complete_time' => $est_time,

					'driver_name' => $item->driver ? $item->driver->user->name : "",
					'driver_image' => $item->driver ? $item->driver->user->user_image_url : "",
					'vehicle_type' => $item->driver ? $item->driver->vehicle_type_details->name : '',
					'vehicle_number' => $item->driver ? $item->driver->vehicle_number : '',
					'driver_rating' => $rating,
					'driver_contact' => $contact,
					'order_type' => $item['schedule_status'],
					'delivery_time' => $item['schedule_time'],
					'delivery_options' => $item->user->user_address ? $item->user->user_address->delivery_options : '',

					'apartment' => $item->user->user_address ? $item->user->user_address->apartment : '',
					'delivery_note' => $item->user->user_address ? $item->user->user_address->delivery_note : '',
					'order_delivery_status' => $item->order_delivery ? $item->order_delivery['status'] : '-1',

					'pickup_latitude' => $store_address->latitude,

					'pickup_longitude' => $store_address->longitude,

					'store_location' => $store_address->address,

					'drop_latitude' => $user_address->latitude,

					'drop_longitude' => $user_address->longitude,

					'driver_latitude' => $item->driver ? $item->driver->latitude : "",

					'driver_longitude' => $item->driver ? $item->driver->longitude : "",

					'is_rating' => $is_rating,

					'star_rating' => $star_rating,

					'food_status' => $food_status,

					'store_closed' => $item['store']['store_time']['closed'],

					'store_next_time' => $item['store']['store_next_opening'],

					'penality' => $item['user_penality'],

					'applied_penality' => $item['user_applied_penality'],

					'notes' => (string) $item['user_notes'],

					'invoice' => [

						array('key' =>trans('api_messages.cart.subtotal'), 'value' => $item['subtotal']),
						array('key' =>trans('api_messages.cart.delivery_fee'), 'value' => $item['delivery_fee']),
						array('key' =>trans('api_messages.cart.booking_fee'), 'value' => $item['booking_fee']),
						array('key' =>trans('api_messages.cart.tax'), 'value' => $item['tax']),
						array('key' =>trans('api_messages.cart.promo_amount'), 'value' => $item['promo_amount']),
						array('key' =>trans('api_messages.cart.wallet_amount'), 'value' => $item['wallet_amount']),
						array('key' =>trans('api_messages.cart.total'), 'value' => $item['total_amount']),


					],

				];

			}
		);

		$order_list = $order_list->toArray();

		//upcoming

		$upcoming = $upcoming->map(
			function ($item) {
				$upcoming_menu_item = $item['order_item']->map(
					function ($item) {
						return [

							'quantity' => $item['quantity'],
							'menu_item_id' => $item['menu_item']['id'],
							'item_image' => $item['menu_item']['menu_item_image'],
							'price' => $item['total_amount'],
							'menu_name' => $item['menu_item']['name'],
							'type' => $item['menu_item']['type'],
							'status' => $item['menu_item']['status'],

						];
					}
				)->toArray();

				$rating = 0;
				$contact = '';

				if ($item->driver_id && $item->driver) {

					if ($item->driver->review) {
						$rating = $item->driver->review->user_to_driver_rating;
					}

					$contact = $item->driver->driver_contact;

				}

				$user_id = get_store_user_id($item['store']['id']);

				$store_address = get_store_address($user_id);

				$user_address = get_user_address($item['user_id']);

				$food_status = array();

				if ($item->status_text == 'completed') {

					$food_status[] = [

						
						'time' => $item->completed_at->format('h:i').' '.trans('api_messages.monthandtime.'.$item->completed_at->format('a')),
						'status' => trans('api_messages.orders.ready_to_eat'),
					];

				}

				if (($item->status_text == 'delivery' || $item->status_text == 'completed') && isset($item->order_delivery->started_at)) {

					
					$delivery_at = (string) date('h:i', strtotime($item->delivery_at)).' '.trans('api_messages.monthandtime.'.date('a', strtotime($item->delivery_at)));
					$food_status[] = [

						'time' => $delivery_at,
						'status' => trans('api_messages.orders.item_on_the_way'),
					];
				}

				if ($item->schedule_status == '0' && ($item->status_text == 'accepted' || $item->status_text == 'completed' || $item->status_text == 'delivery')) {

					$food_status[] = [

						

						'time' => $item->accepted_at->format('h:i').' '.trans('api_messages.monthandtime.'.$item->accepted_at->format('a')),
						'status' => trans('api_messages.orders.preparing_your_item'),
					];

				}

				if ($item->status_text == 'accepted' || $item->status_text == 'completed' || $item->status_text == 'delivery') {

					$food_status[] = [

						'time' => $item->accepted_at->format('h:i').' '.trans('api_messages.monthandtime.'.$item->accepted_at->format('a')),
						'status' => trans('api_messages.orders.order_accepted'),
					];
				}

				$date = date('Y-m-d', strtotime($item['created_at']));
				$schedule_time = date('Y-m-d', strtotime($item['schedule_time']));

				if ($item['schedule_status'] == 0) {

					if ($date == date('Y-m-d')) {
						
						$date = 'Today' . ' ' . date('M d h:i a', strtotime($item['created_at']));
					} else if ($date == date('Y-m-d', strtotime("+1 days"))) {
						
						$date = 'Tomorrow' . ' ' . date('M d h:i a', strtotime($item['created_at']));
					} else { $date = date('l M d h:i a', strtotime($item['created_at']));}

				} else {

					$time_Stamp = strtotime($item['schedule_time']) + 1800;

					$del_time = date('h:i a', $time_Stamp);
					$common = date('M d h:i a', strtotime($item['schedule_time']));

					if ($schedule_time == date('Y-m-d')) {
						
						$date = 'Today' . ' ' . $common . ' - ' . $del_time;
					} else if ($schedule_time == date('Y-m-d', strtotime("+1 days"))) {
						
						$date = 'Tomorrow'. ' ' . $common . ' - ' . $del_time;
					} else {
						$date = date('l M d h:i a', strtotime($item['schedule_time'])) . ' - ' . $del_time;
					}

				}

				if ($item->status_text == "pending") {

					$est_completed_time = $item->est_delivery_time;
				} else {
					$est_completed_time = $item->completed_at;
				}
					$date = date('Y-m-d H:i:s', strtotime($item['created_at']));
					$get_show_date = date('d', strtotime($date)).' '.trans('api_messages.monthandtime.'.date('M', strtotime($date))).' '.date('Y', strtotime($date)).' '.date('h:i', strtotime($date)).' '.trans('api_messages.monthandtime.'.date('a', strtotime($date)));

					// $get_show_date = $date;
					
				$est_time = date('h:i', strtotime($est_completed_time)).' '.trans('api_messages.monthandtime.'.date('a', strtotime($est_completed_time)));

				return [

					'order_id' => $item['id'],
					'total_amount' => $item['total_amount'],
					'subtotal' => $item['subtotal'],
					'delivery_fee' => $item['delivery_fee'],
					'booking_fee' => $item['booking_fee'],
					'tax' => $item['tax'],
					'wallet_amount' => $item['wallet_amount'],
					'promo_amount' => $item['promo_amount'],
					'order_status' => $item['status'],
					'name' => $item['store']['name'],
					'store_id' => $item['store']['id'],
					'store_status' => 1,
					'store_open_time' => $item['store']['store_time']['start_time'],

					'status' => $item['status'],
					'store_banner' => $item['store']['banner'],
					'order_type' => $item['schedule_status'],
					'delivery_time' => $item['schedule_time'],
					'date' => $get_show_date,
					'menu' => $upcoming_menu_item,
					'total_seconds' => $item->user_total_seconds,
					'remaining_seconds' => $item->user_remaining_seconds,
					'user_status_text' => $item->user_status_text,
					'est_complete_time' => $est_time,

					'driver_name' => $item->driver ? $item->driver->user->name : "",
					'driver_image' => $item->driver ? $item->driver->user->user_image_url : "",
					'vehicle_type' => $item->driver ? $item->driver->vehicle_type_details->name : '',
					'vehicle_number' => $item->driver ? $item->driver->vehicle_number : '',
					'driver_rating' => $rating,
					'driver_contact' => $contact,

					'delivery_options' => $item->user->user_address ? $item->user->user_address->delivery_options : '',

					'apartment' => $item->user->user_address ? $item->user->user_address->apartment : '',
					'delivery_note' => $item->user->user_address ? $item->user->user_address->delivery_note : '',
					'order_delivery_status' => $item->order_delivery ? $item->order_delivery['status'] : '-1',

					'pickup_latitude' => $store_address->latitude,

					'pickup_longitude' => $store_address->longitude,

					'store_location' => $store_address->address,

					'drop_latitude' => $user_address->latitude,

					'drop_longitude' => $user_address->longitude,

					'driver_latitude' => $item->driver ? $item->driver->latitude : "",

					'driver_longitude' => $item->driver ? $item->driver->longitude : "",

					'food_status' => $food_status,

					'store_closed' => 1,

					'store_next_time' => $item['store']['store_next_opening'],
					'penality' => $item['user_penality'],

				
					'invoice' => [

						

						array('key' =>trans('api_messages.cart.subtotal'), 'value' => $item['subtotal']),
						array('key' =>trans('api_messages.cart.delivery_fee'), 'value' => $item['delivery_fee']),
						array('key' =>trans('api_messages.cart.booking_fee'), 'value' => $item['booking_fee']),
						array('key' =>trans('api_messages.cart.tax'), 'value' => $item['tax']),
						array('key' =>trans('api_messages.cart.promo_amount'), 'value' => $item['promo_amount']),
						array('key' =>trans('api_messages.cart.wallet_amount'), 'value' => $item['wallet_amount']),
						array('key' =>trans('api_messages.cart.total'), 'value' => $item['total_amount']),


					],

				];
			}
		);

		$upcoming = $upcoming->toArray();

		return response()->json(
			[

				'status_message' => trans('api_messages.orders.successfully'),

				'status_code' => '1',

				'order_history' => $order_list,

				'upcoming' => $upcoming,

			]
		);
	}

	/**
	 * API for create a customer id  based on card details using stripe payment gateway
	 *
	 * @return Response Json response with status
	 */

	public function add_card_details(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		try {

			$stripe_key = site_setting('stripe_secret_key');

			\Stripe\Stripe::setApiKey($stripe_key);

			$payment_details = UserPaymentMethod::where('user_id', $user_details->id)->first();

			if ($payment_details) {	
				$customer = \Stripe\Customer::create(
					array(
						"description" => "Customer for daniel.jones@example.com",
						"source" => $request->stripe_id, // obtained with Stripe.js
					)
				);

				$customer_details = \Stripe\Customer::retrieve($customer->id);

				$payment_details->stripe_customer_id = $customer->id;
				$payment_details->brand = $customer_details->sources->data[0]['brand'];
				$payment_details->last4 = $customer_details->sources->data[0]['last4'];
				$payment_details->save();



			} else 
			{

				// $stripe = \Stripe\Token::create(array(
				// 	"card" => array(
				// 		"number" => "4242424242424242",
				// 		"exp_month" => 6,
				// 		"exp_year" => 2019,
				// 		"cvc" => "314",
				// 	),
				// ));

				// $id = $stripe->id;

				$id = $request->stripe_id;

				$customer = \Stripe\Customer::create(
					array(
						"description" => "Customer for daniel.jones@example.com",
						"source" => $id, // obtained with Stripe.js //
					)
				);

				$customer_details = \Stripe\Customer::retrieve($customer->id);

				$payment_details = new UserPaymentMethod;
				$payment_details->user_id = $user_details->id;
				$payment_details->stripe_customer_id = $customer->id;
				$payment_details->brand = $customer_details->sources->data[0]['brand'];
				$payment_details->last4 = $customer_details->sources->data[0]['last4'];
				$payment_details->save();
			}

			$customer_details = \Stripe\Customer::retrieve($customer->id);

			$result = $customer_details->sources->data;

			// dd($result);

			return response()->json(
				[

					'status_message' => 'Successfully',

					'status_code' => '1',

					'brand' => $result[0]['brand'],

					'last4' => $result[0]['last4'],

					'payment_details' => $payment_details,

				]
			);
		} catch (\Exception $e) {
			return response()->json(
				[

					'status_message' => $e->getMessage(),

					'status_code' => '0',

				]
			);
		}
	}

	/**
	 * API for payment card details
	 *
	 * @return Response Json response with status
	 */

	public function get_card_details(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		try {

			$stripe_key = site_setting('stripe_secret_key');

			\Stripe\Stripe::setApiKey($stripe_key);

			$customer_id = UserPaymentMethod::where('user_id', $user_details->id)->first()->stripe_customer_id;

			$customer_details = \Stripe\Customer::retrieve($customer_id);

			$result = $customer_details->sources->data;

			// dd($result);

			return response()->json(
				[

					'status_message' => 'Successfully',

					'status_code' => '1',

					'brand' => $result[0]['brand'],

					'last4' => $result[0]['last4'],

					'stripe_publish_key' => site_setting('stripe_publish_key'),

				]
			);
		} catch (\Exception $e) {
			return response()->json(
				[

					'status_message' => $e->getMessage(),

					'status_code' => '0',

					'stripe_publish_key' => site_setting('stripe_publish_key'),

				]
			);
		}
	}

	/**
	 * API for filter
	 *
	 * @return Response Json response with status
	 */

	public function filter(Request $request) {

		$user_details ='';

		if(request()->token)
		{
		   $user_details = JWTAuth::parseToken()->authenticate();
		}

		$address_details = $this->address_details();

		$latitude = $address_details['latitude'];
		$longitude = $address_details['longitude'];

		$perpage = 7;

		$dietary = '';
		$price = [];

		if ($request->price) {
			$price = explode(',', $request->price);
		}

		if ($request->dietary || $request->dietary != '') {
			$dietary = explode(',', $request->dietary);
		}

		$type = $request->type;
		$sort = $request->sort;

		$search = [

			0 => 'Filter',
			1 => 'Favourite',
			2 => 'Popular',
			3 => 'Under',
			4 => 'New Store',
			5 => 'More store',

		];

		$user = User::with(
			['store' => function ($query) use ($price, $dietary, $type, $sort) {
				$query->with(['store_category', 'store_preparation_time', 'wished' => function ($query) {
					$query->select('store_id', DB::raw('count(store_id) as count'))->groupBy('store_id');}, 'review']);
			}]
		)->Type('store')->whereHas('store', function ($query) use ($latitude, $longitude) {

			$query->location($latitude, $longitude)->whereHas('store_time', function ($query) {

			});

		})->status();

		if ($type == 0) {

			$store = $user->whereHas(

				'store',

				function ($query) use ($price, $dietary, $type, $sort) {

					if (count($price) > 0) {

						$query->whereIn('price_rating', $price);
					}

					if ($sort == 0 && $sort != null) {

						$query->where('recommend', '1');

					}

					if ($sort == 1) {

						$query->whereHas('wished', function ($query) {

						});

					}

					if (count($dietary) > 0 && $dietary != '') {

						$query->whereHas('store_category', function ($query) use ($dietary) {

							$query->whereIn('category_id', $dietary);

						});

					}

				});

			if ($sort == 2) {

				$rating = (clone $store)->get();

				$collection = collect($rating)->sortByDesc(function ($rating) {

					return $rating->store->review->store_rating;

				});

				$store = $collection->forPage($request->page, $perpage)->values();

				$page_count = round(ceil($store->count() / $perpage));

			} else if ($sort == 3) {

				$delivery_time = (clone $store)->get();

				$collection = collect($delivery_time)->sortBy(function ($delivery_time) {

					return $delivery_time->store->convert_mintime;

				});

				$store = $collection->forPage($request->page, $perpage)->values();

				$page_count = round(ceil($store->count() / $perpage));

			} else {

				$store = $store->paginate($perpage);

				$page_count = $store->lastPage();

			}

		} else {

			if ($type == 2) {

				$store = Wishlist::select('store_id', DB::raw('count(store_id) as count'))->with(

					['store' => function ($query) use ($latitude, $longitude) {

						$query->with(['store_category', 'review', 'user', 'store_time', 'store_offer']);
					}]
				)->whereHas('store', function ($query) use ($latitude, $longitude) {

					$query->UserStatus()->location($latitude, $longitude)->whereHas('store_time', function ($query) {

					});

				})->groupBy('store_id')->orderBy('count', 'desc')->paginate($perpage);
				$page_count = $store->lastPage();

			} else {

				$date = \Carbon\Carbon::today()->subDays(10);

				$min_time = $request->min_time ? convert_format($request->min_time) : '00:20:00';

				$store = $user->whereHas(

					'store',

					function ($query) use ($type, $min_time, $date) {

						if ($type == 1) {

							$query->whereHas('wished', function ($query) {

							});
						} else if ($type == 3) {

							$query->where('max_time', $min_time);

						} else if ($type == 4) {

							$query->where('created_at', '>=', $date);

						} else {

							// more store
						}

					})->paginate($perpage);

				$page_count = $store->lastPage();

			}

		}

		$user = $this->common_map($store);

		$user = (count($user) > 0) ? $user->toArray() : array(); // more store

		return response()->json(
			[

				'status_message' => "Success",

				'status_code' => '1',

				'store' => $user,

				'page_count' => $page_count,

				'search_text' => $search[$type],

			]
		);
	}

	/**
	 * API for cancel order who place the order
	 *
	 * @return Response Json response with status
	 */

	public function cancel_order(PaymentController $PaymentController) {

		$request = request();

		$order = new Order;

		$order = Order::where('id', $request->order_id)->first();

		if ($order->status == '2' || $order->status == '4') {

			return response()->json(
				[
					'status_message' => trans('api_messages.eater.already_cancelled'),
					'status_code' => '0',
				]
			);
		}

		if ($order->schedule_status == 0) {

			$rules = [

				'order_id' => 'required|exists:order,id,status,' . $order->statusArray['pending'],
			];

			$messages = [

				'order_id.exists' => trans('api_messages.user.your_order_progress'),

			];

			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				return response()->json(
					[
						'status_message' => $validator->messages()->first(),
						'status_code' => '0',
					]
				);
			}
		}

		$user_details = JWTAuth::parseToken()->authenticate();

		$order->cancel_order("eater", $request->cancel_reason, $request->cancel_message);

		$refund = $PaymentController->refund($request, 'Cancelled',$order->user_id,'eater');

		return response()->json(
			[

				'status_message' => trans('api_messages.user.order_cancel'),

				'status_code' => '1',

			]
		);
	}

	/**
	 * API for user review details and issue type
	 *
	 * @return Response Json response with status
	 */

	public function user_review(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		$item = Order::getAllRelation()->where('user_id', $user_details->id)->where('id', $request->order_id)->first();

		if (!$item) {
			return response()->json([

				'status_message' => 'Empty',

				'status_code' => '0',

			]);
		}

		$menu_item = $item['order_item']->map(
			function ($item) {
				return [
					'quantity' => $item['quantity'],
					'order_item_id' => $item['id'],
					'menu_item_id' => $item['menu_item']['id'],
					'item_image' => $item['menu_item']['menu_item_image'],
					'price' => $item['menu_item']['price'],
					'menu_name' => $item['menu_item']['name'],
					'type' => $item['menu_item']['type'],
					'status' => $item['menu_item']['status'],

				];
			}
		)->toArray();

		$driver_image = '';
		$driver_id = 0;
		$driver_name = '';

		if ($item->driver_id && $item->driver) {

			$driver_image = $item->driver->user->user_image_url;
			$driver_name = $item->driver->user->name;
			$driver_id = $item->driver_id;

		}

		$issue_user_menu_item = [];
		$issue_user_driver = [];

		$issue_user_menu_item = IssueType::TypeText('user_menu_item')->get();
		$issue_user_driver = IssueType::TypeText('user_driver')->get();

		$order_details = [

			'order_id' => $item['id'],
			'total_amount' => $item['total_amount'],
			'subtotal' => $item['subtotal'],
			'delivery_fee' => $item['delivery_fee'],
			'tax' => $item['tax'],
			'order_status' => $item['status'],
			'name' => $item['store']['name'],
			'store_id' => $item['store']['id'],
			'store_open_time' => $item['store']['store_time']['start_time'],
			'status' => $item['status'],
			'store_banner' => $item['store']['banner'],
			'date' => date('d F Y H:i a', strtotime($item['updated_at'])),
			'menu' => $menu_item,
			'driver_image' => $driver_image,
			'driver_name' => $driver_name,
			'driver_id' => $driver_id,
			'issue_user_menu_item' => $issue_user_menu_item,
			'issue_user_driver' => $issue_user_driver,
		];

		return response()->json([

			'status_message' => 'Success',

			'status_code' => '1',

			'user_review_data' => $order_details,

		]);

	}

	/**
	 * API for Add rating in a order to menu item and delivery from user
	 *
	 * @return Response Json response with status
	 */

	public function add_user_review() {


	  	if(isset($_POST['token']))
		$user_details =  JWTAuth::toUser($_POST['token']);
		else
			$user_details = JWTAuth::parseToken()->authenticate();

		$request = request();

		$order = Order::getAllRelation()/*->where('user_id', $user_details->id)*/->where('id', $request->order_id)->first();

        $rating = str_replace('\\', '', $request->rating);

    	$rating = json_decode($rating);

		$order_id = $order->id;

		$food_item = $rating->food;

		//Rating for Menu item

		if ($food_item) {

			foreach ($food_item as $key => $value) {

				$review = new Review;
				$review->order_id = $order_id;
				$review->type = $review->typeArray['user_menu_item'];
				$review->reviewer_id = $user_details->id;
				$review->reviewee_id = $value->id;
				$review->is_thumbs = $value->thumbs;
				$review->order_item_id = $value->order_item_id;
				$review->comments = $value->comment ?: "";
				$review->save();

				if ($value->reason) {
					$issues = explode(',', $value->reason);
					if ($request->thumbs == 0 && count($value->reason)) {
						foreach ($issues as $issue_id) {
							$review_issue = new ReviewIssue;
							$review_issue->review_id = $review->id;
							$review_issue->issue_id = $issue_id;
							$review_issue->save();
						}
					}

				}

			}

		}

		//Rating for driver

		if (count(get_object_vars($rating->driver)) > 0) {

			$review = new Review;
			$review->order_id = $order_id;
			$review->type = $review->typeArray['user_driver'];
			$review->reviewer_id = $user_details->id;
			$review->reviewee_id = $order->driver_id;
			$review->is_thumbs = $rating->driver->thumbs;
			$review->comments = $rating->driver->comment ?: "";
			$review->save();

			if ($rating->driver->reason) {
				$issues = explode(',', $rating->driver->reason);
				if ($rating->driver->thumbs == 0 && count($issues)) {
					foreach ($issues as $issue_id) {
						$review_issue = new ReviewIssue;
						$review_issue->review_id = $review->id;
						$review_issue->issue_id = $issue_id;
						$review_issue->save();
					}
				}

			}

		}

		//Rating for Store

		if (count(get_object_vars($rating->store)) > 0) {

			$review = new Review;
			$review->order_id = $order_id;
			$review->type = $review->typeArray['user_store'];
			$review->reviewer_id = $user_details->id;
			$review->reviewee_id = $order->store_id;
			$review->rating = $rating->store->thumbs;
			$review->comments = $rating->store->comment ?: "";
			$review->save();

		}
		return response()->json(
			[
				'status_message' => 'Updated Successfully',
				'status_code' => '1',
			]
		);

	}

	/**
	 * API for wallet amount
	 *
	 * @return Response Json response with status
	 */

	public function add_wallet_amount(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();
		$amount = $request->amount;
		$currency_code = DEFAULT_CURRENCY;

		// stripe

		try {

			$customer_id = UserPaymentMethod::where('user_id', $user_details->id)->first()->stripe_customer_id;
			$stripe_key = site_setting('stripe_secret_key');

			\Stripe\Stripe::setApiKey($stripe_key);

			$charge = \Stripe\Charge::create(
				[
					"amount" => $amount * 100,
					'currency' => $currency_code,
					"customer" => $customer_id,
				]
			);

			$wallet = Wallet::where('user_id', $user_details->id)->first();

			if ($wallet) {
				$amount = $wallet->amount + $amount;

			}

			$wallet = Wallet::updateOrCreate(
				['user_id' => $user_details->id],
				['amount' => $amount,
					'currency_code' => $currency_code,
				]);

			$payment = new Payment;
			$payment->user_id = $user_details->id;
			$payment->transaction_id = $charge->id;
			$payment->amount = $amount;
			$payment->status = 1;
			$payment->type = 1;
			$payment->currency_code = $currency_code;
			$payment->save();

			$wallet_details = Wallet::where('user_id', $user_details->id)->first();

			return response()->json(
				[

					'status_message' => trans('api_messages.success'),

					'status_code' => '1',

					'wallet_amount' => $wallet_details->amount,

					'currency_code' => $wallet_details->currency_code,

				]
			);
		} catch (\Exception $e) {
				if($e->getMessage() == "Invalid positive integer"){
					$error_msg = trans('api_messages.wallet.positive_integer');
				}else{
					$error_msg = $e->getMessage();
				}
			return response()->json(
				[


					'status_message' => $error_msg,

					'status_code' => '0',

				]
			);
		}

	}

	/**
	 * API for Wishlist
	 *
	 * @return Response Json response with status
	 */

	public function wishlist(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		$user = User::where('id', $user_details->id)->first();

		list('latitude' => $latitude, 'longitude' => $longitude) =
		collect($user->user_address)->only(['latitude', 'longitude'])->toArray();

		$wishlist = Wishlist::selectRaw('*,store_id as ids, (SELECT count(store_id) FROM wishlist WHERE store_id = ids) as count')->with(

			['store' => function ($query) use ($latitude, $longitude) {

				$query->with(['store_category', 'review', 'user', 'store_time', 'store_offer']);
			}]
		)->whereHas('store', function ($query) use ($latitude, $longitude) {

			$query->UserStatus()->location($latitude, $longitude)->whereHas('store_time', function ($query) {

			});

		})->where('user_id', $user_details->id)->get();

		$wishlist = $this->common_map($wishlist);

		return response()->json(
			[

				'wishlist' => $wishlist ? $wishlist : [],

				'status_message' => 'Success',

				'status_code' => '1',

			]
		);

	}

	/**
	 * API for Info window
	 *
	 * @return Response Json response with status
	 */

	public function info_window(Request $request) {

		if(request()->token)
		{
		   $user_details = JWTAuth::parseToken()->authenticate();
		   $user_address = get_user_address($user_details->id);
		   list('latitude' => $user_latitude, 'longitude' => $user_longitude, 'address' => $user_location) = collect($user_address)->only(['latitude', 'longitude', 'address'])->toArray();
		}
		else
		{

           	$user_latitude =  request()->latitude;
           	$user_longitude = request()->longitude;
           	$user_location =  request()->address;

		}

		$restauant_user_id = get_store_user_id($request->id);
		
		$restauant_address = get_store_address($restauant_user_id);

		list('latitude' => $store_latitude, 'longitude' => $store_longitude, 'address' => $store_location) = collect($restauant_address)->only(['latitude', 'longitude', 'address'])->toArray();

		$store_time = StoreTime::where('store_id', $request->id)->orderBy('day', 'asc')->get();
		$store = Store::find($request->id);
		$store_name = $store->name;

		return response()->json(
			[

				'status_message' => 'Success',
				'status_code' => '1',
				'user_latitude' => $user_latitude,
				'user_longitude' => $user_longitude,
				'user_location' => $user_location,
				'store_latitude' => $store_latitude,
				'store_longitude' => $store_longitude,
				'store_location' => $store_location,
				'store_time' => $store_time,
				'store_name' => $store_name,

			]
		);

	}

	public function common_map($query) {

		if(isset(request()->token))
		{
		   $user_details = JWTAuth::parseToken()->authenticate();
		   $user = User::where('id', $user_details->id)->first();

	list('latitude' => $latitude, 'longitude' => $longitude, 'order_type' => $order_type, 'delivery_time' => $delivery_time) =
	collect($user->user_address)->only(['latitude', 'longitude', 'order_type', 'delivery_time'])->toArray();

		}
		else
		{
			$user_details = '';
			$latitude = request()->latitude;
			$longitude = request()->longitude;
			$order_type = request()->order_type;
			$delivery_time = request()->delivery_time;
			
		}

		

		return $query->map(
			function ($item) use ($user_details, $order_type, $delivery_time) {
				$store_category = $item['store']['store_category']->map(
					function ($item) {
						return $item['category_name'];
					}
				)->toArray();


				if($user_details)
					$wishlist = $item['store']->wishlist($user_details->id, $item['store']['id']);
				else
					$wishlist = 0;



				return [

					'order_type' => $order_type,
					'delivery_time' => $delivery_time,
					'store_id' => $item['store']['id'],
					'name' => $item['store']['name'],
					'category' => implode(',', $store_category),
					'banner' => $item['store']['banner'],
					'min_time' => $item['store']['convert_mintime'],
					'max_time' => $item['store']['convert_maxtime'],
					'store_rating' => $item['store']['review']['store_rating'],
					'price_rating' => $item['store']['price_rating'],
					'average_rating' => $item['store']['review']['average_rating'],
					'wished' => $wishlist,
					'status' => $item['store']['status'],
					'store_open_time' => $item['store']['store_time']['start_time'],
					'store_next_time' => $item['store']['store_next_opening'],
					'store_closed' => $item['store']['store_time']['closed'],
					'store_offer' => $item['store']['store_offer']->map(

						function ($item) {

							return [

								'title' => $item->offer_title,
								'description' => $item->offer_description,
								'percentage' => $item->percentage,

							];
						}
					),

				];
			}
		);
	}

	/**
	 * Default user address
	 */

	public function address_details() {


		if(isset(request()->token))
		{
		   $user_details = JWTAuth::parseToken()->authenticate();
		   $user = User::where('id', $user_details->id)->first();

	return list('latitude' => $latitude, 'longitude' => $longitude, 'order_type' => $order_type, 'delivery_time' => $delivery_time) =
	collect($user->user_address)->only(['latitude', 'longitude', 'order_type', 'delivery_time'])->toArray();

		}
		else
		{

			return ['latitude' => request()->latitude, 'longitude' => request()->longitude, 'order_type' => request()->type, 'delivery_time' => request()->delivery_time ];
			
		}

	}

}
