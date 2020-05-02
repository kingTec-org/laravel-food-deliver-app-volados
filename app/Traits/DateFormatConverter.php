<?php 

/**
 * Date Format Trait
 *
 * @package     Gofereats
 * @subpackage  Date Format Trait
 * @category    Date Format
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Traits;
use Str;
use Carbon\Carbon;
Use App\Models\Currency;


trait DateFormatConverter
{

     public $currency_code_field = 'currency_code';

    public function isAdminPanel()
    {
        return get_current_login_user();
    }
    public function canConvert()
    {
        return ($this->isAdminPanel()!='admin' && $this->isAdminPanel()!='store');
    }
    public function getFromCurrencyCode()
    {
        $field = $this->currency_code_field;
        return parent::getAttribute($field) ?: '';
    }

    public function getToCurrencyCode()
    {
        $code = site_setting('default_currency');
        return $code;
    }

    public function getConvertedValue($price)
    {
        $from = $this->getFromCurrencyCode();
        $to = $this->getToCurrencyCode();
        $converted_price = $this->currency_convert($from, $to, $price);
        return $converted_price;
    }

      /**
   * Currency Convert
   *
   * @param int $from   Currency Code From
   * @param int $to     Currency Code To
   * @param int $price  Price Amount
   * @return int Converted amount
   */
    public function currency_convert($from = '', $to = '', $price = 0)
    {
        if($from == '')
        {
          $from = site_setting('default_currency');
        }
        if($to == '')
        {
          $to = site_setting('default_currency');
        }

        $rate = Currency::whereCode($from)->first()->rate;
        $session_rate = Currency::whereCode($to)->first()->rate;

        $usd_amount = $price / $rate;
        return number_format($usd_amount * $session_rate, 2, '.', '');
    }

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();
        if(is_array(@$this->date_fields)) { 
            foreach ($this->date_fields as $key) {
                if (! isset($attributes[$key])) {
                    continue;
                }

                $attributes[$key] = Carbon::parse($this->original[$key])->format(site_setting('site_date_format'));
            }
        }
        if(is_array(@$this->currency_convert_fields)){ 
            foreach($this->currency_convert_fields as $key)
            {
              if (! isset($attributes[$key])) {
                    continue;
                }
                if($this->canConvert())
                {  
                   $attributes[$key] = $this->getConvertedValue($this->original[$key]);
                }
            }
        }
        return $attributes;
    

    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        
        $value = parent::getAttribute($key);
        if(is_array(@$this->date_fields)){ 
            if(in_array($key, $this->date_fields)) {
                $value = Carbon::parse($this->original[$key])->format(site_setting('site_date_format'));
            }
        }
        if(is_array(@$this->currency_convert_fields)){ 
            if(in_array($key, $this->currency_convert_fields)) 
            {
                if($this->canConvert())
                {                  
                    $value = $this->getConvertedValue($value);
                }
            }
        }

        return $value;
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        parent::setAttribute($key, $value);
        if(is_array(@$this->date_fields)){
            if(in_array($key, $this->date_fields)) {
                $date = $value;
                if(!$date instanceOf Carbon)
                    $date = Carbon::createFromFormat(site_setting('site_date_format'), $value);

                $value = $date->format('Y-m-d H:i:s');
                $this->attributes[$key] = $value;
            }
        }

        return $this;
    }
}