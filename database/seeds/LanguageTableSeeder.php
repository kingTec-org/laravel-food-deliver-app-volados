<?php

use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('language')->delete();
    	
        DB::table('language')->insert([
        	      ['name' => 'Bahasa Indonesia','value' => 'id','status' => '1','default_language'=>'0'],
  				      ['name' => 'Bahasa Melayu','value' => 'ms','status' => '1','default_language'=>'0'],
  				      ['name' => 'Català','value' => 'ca','status' => '1','default_language'=>'0'],
  				      ['name' => 'Dansk','value' => 'da','status' => '1','default_language'=>'0'],
  				      ['name' => 'Deutsch','value' => 'de','status' => '1','default_language'=>'0'],
  				      ['name' => 'English','value' => 'en','status' => '1','default_language'=>'1'],
  				      ['name' => 'Español','value' => 'es','status' => '1','default_language'=>'0'],
  				      ['name' => 'Eλληνικά','value' => 'el','status' => '1','default_language'=>'0'],
  				      ['name' => 'Français','value' => 'fr','status' => '1','default_language'=>'0'],
  				      ['name' => 'Italiano','value' => 'it','status' => '1','default_language'=>'0'],
  				      ['name' => 'Magyar','value' => 'hu','status' => '1','default_language'=>'0'],
  				      ['name' => 'Nederlands','value' => 'nl','status' => '1','default_language'=>'0'],
  				      ['name' => 'Norsk','value' => 'no','status' => '1','default_language'=>'0'],
  				      ['name' => 'Polski','value' => 'pl','status' => '1','default_language'=>'0'],
  				      ['name' => 'Português','value' => 'pt','status' => '1','default_language'=>'0'],
  				      ['name' => 'Suomi','value' => 'fi','status' => '1','default_language'=>'0'],
  				      ['name' => 'Svenska','value' => 'sv','status' => '1','default_language'=>'0'],
  				      ['name' => 'Türkçe','value' => 'tr','status' => '1','default_language'=>'0'],
  				      ['name' => 'Íslenska','value' => 'is','status' => '1','default_language'=>'0'],
  				      ['name' => 'Čeština','value' => 'cs','status' => '1','default_language'=>'0'],
  				      ['name' => 'Русский','value' => 'ru','status' => '1','default_language'=>'0'],
  				      ['name' => 'ภาษาไทย','value' => 'th','status' => '1','default_language'=>'0'],
  				      ['name' => '中文 (简体)','value' => 'zh','status' => '1','default_language'=>'0'],
  				      ['name' => '中文 (繁體)','value' => 'zh-TW','status' => '1','default_language'=>'0'],
  				      ['name' => '日本語','value' => 'ja','status' => '1','default_language'=>'0'],
                ['name' => '한국어','value' => 'ko','status' => '1','default_language'=>'0'],
  				      ['name' => 'العربية','value' => 'ar','status' => '1','default_language'=>'0']
        	]);
    }
}
