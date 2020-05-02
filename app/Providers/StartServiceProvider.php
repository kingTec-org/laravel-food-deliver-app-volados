<?php

/**
 * StartService Provider
 *
 * @package     Makent
 * @subpackage  Provider
 * @category    Service
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Currency;
use App\Models\Language;
use App\Models\SiteSettings;
use View;
use Config;
use Schema;
use Auth;
use App;
use Session;
use Request;
use App\Models\Admin;
use App\Models\Pages;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;
class StartServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    	if(env('DB_DATABASE') != '') {
    	
		if(Schema::hasTable('currency'))
            $this->currency(); // Calling Currency function
		if(Schema::hasTable('site_setting'))
            $this->site_settings(); // Calling Site Settings function

		if(Schema::hasTable('language'))
			$this->language(); // Calling Language function

		}
        if(Schema::hasTable('static_page'))
            $this->pages(); // Calling Pages function
        

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
	 
     public function currency()
     {
        $default_currency=SiteSettings::where('name','default_currency')->first()->value;
       define('DEFAULT_CURRENCY', $default_currency);
       View::share('default_currency', $default_currency);
     }
    
	
	
	// Share Language Details to whole software
	public function language()
	{

		// Language lists for footer
        $language = Language::translatable()->whereIn('value',['en','ar','pt'])->pluck('name', 'value');
        View::share('language', $language);  
        $language = Language::translatable()->get();
        View::share('lang', $language);
		
		// Default Language for footer
		$default_language = Language::translatable()->where('default_language', '=', '1')->limit(1)->get();
        View::share('default_language', $default_language);
        
        if(Request::segment(1) == ADMIN_URL){
		$default_language = Language::translatable()->where('value', 'en')->get();
		
		}

        if($default_language->count() > 0) {
        	
			Session::put('language', $default_language[0]->value);
			\Log::info('start');
			App::setLocale($default_language[0]->value);
		}
	}

    public function site_settings()
    {
        $site_settings = SiteSettings::all();

                
        //View::share('site_settings', $site_settings);

        
        
        
        define('ADMIN_URL', $site_settings[46]->value);
        

        
    }

    public function pages() {
        if (Schema::hasTable('static_page')) {
            
            //$page = Request::segment(1) == null ? 'eater' : Request::segment(1);
                $root = check_current_root();
                $page = $root == 'web' ? 'user' : $root;
                
                if($page != 'admin' && $page != 'api'){                   
                    $static_pages_changes = Pages::User($page)->where('footer', 1)->where('status', '1')->get();
                    View::share('static_pages_changes', $static_pages_changes->split(2));
                }
            
        }

    }
	

}
