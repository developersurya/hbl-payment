<?php
/**
 *
 * Class for creating shortcode for displaying payment form.
 *
 * @link       https://www.lastdoorsolutions.com/
 * @since      1.0.0
 *
 * @package    Hbl_Payment_Gateway
 * @subpackage Hbl_Payment_Gateway/includes
 */

/**
 * The Option page plugin class.
 *
 * @since      1.0.0
 * @package    Hbl_Payment_Gateway
 * @subpackage Hbl_Payment_Gateway/includes
 * @author     lastdoorsolutions <info@lastdoorsolutions.com>
 */
class Hbl_Payment_Gateway_Form {


	/**
	 * Holds the values to be used in the fields callbacks.
	 *
	 */
	protected $options;


	/**
	 * Define the Option page functionality of the plugin.
	 */
	public function __construct() {
		$this->options = get_option( 'hbl_option_name' );
        add_shortcode( 'hbl_form',  array( $this,'hbl_form_shortcode' ));
         //$this->hbl_form();

	}

    /**
     * Hash code generation from code provided by HBL 
     */

    private function hast_code(){
        $hash_code = $this->options['hash_code'] ;
        $signData = hash_hmac('SHA256', "signatureString",$hash_code, false);
        $signData = strtoupper($signData); 
        $hast_code = urlencode($signData);
        return $hast_code ;

    }

    /**
     * Invoice code generation for unique incremented number (twenty characters)
     */
    
    public function random_invoice_code(){
        //dynamic invoice concepts
         // $querystrr = "SELECT option_value FROM 0a6y1m9_options 
         //            WHERE  option_id=(
         //                SELECT max(option_id) FROM 0a6y1m9_options WHERE option_name LIKE '%hbl_payment_result_%' 
         //                )";
         //     $payposts = $wpdb->get_results($querystrr);
         //     $invoice = unserialize($payposts[0]->option_value);
         //     $invoice_number = intval($invoice[1]);
         //     $new_invoice = $invoice_number+1;
         //     $input = 1234;
         //     $n_invoice = str_pad($new_invoice, 20, "0", STR_PAD_LEFT);
             //return $n_invoice;

        //static invoice code
        //return $this->options['invoice_number'];//this return invoice from dashboard.

        //create random invoice code
        $number = "";
        for($i=0; $i<20; $i++) {
        $min = ($i == 0) ? 1:0;
        $number .= mt_rand($min,9);
        }
        return $number;

    }

    /**
     * Add shortcode for payment form 
     */
    public function hbl_form($atts)
    {
        //get the user input secret codes for payment form .
        $Paymentgetway_ID = $this->options['Paymentgetway_ID'] ;
        $hash_code = $this->hast_code();
        $currencyCode = $this->options['currency_symbol'] ;
        if($currencyCode == 840){
            $currency_Code = "$";
            $currency_Code_name = "US Dollor";
        }else{
            $currency_Code = "NRP";
            $currency_Code_name = "Nepali Rupee";
        }
        if (is_array($atts) && isset($atts ["productdesc"])) {
            $productDesc = $atts ["productdesc"];
        }
        if (is_array($atts) && isset($atts ["price"])) {
            $productPrice = $atts["price"];
        }else{
            $productPrice = "0";
        }
        
        $form = '<h3>HBL Payment Form </h3>
        <div class="payment-form form-container"> 
        <div class="cal-price">'.$productPrice.'</div>
        <Form method="post" action="https://hblpgw.2c2p.com/HBLPGW/Payment/Payment/Payment" id="process-form">';
        if(empty($Paymentgetway_ID)){
            $form .='<label class="hbl-error">Please add Payment Gateway ID in dashboard.</label>'; 
        }
        if(empty($hash_code)){
            $form .='<label class="hbl-error">Please add Hash code ID in dashboard.</label>'; 
        }
        if(empty($currencyCode)){
            $form .='<label class="hbl-error">Please add Currency Code in dashboard.</label>'; 
        }
        if(empty($atts["price"])){
            $form .='<label class="hbl-error">Please add Amount in dashboard.</label>'; 
        }
        $form .= '<input type="text" id="paymentGatewayID" name="paymentGatewayID" value="'.$Paymentgetway_ID.'" hidden>
        <input type="text" id="hashValue" name="hashValue" value="'.$hash_code.'" hidden>
        <input type="text" id="nonSecure" name="nonSecure" value="Y" hidden>
        <div class="input-field">
        <label>Invoice No:</label>
        <input type="text" id="invoiceNo" name="invoiceNo" value="'.$this->random_invoice_code().'">
        </div>
        <div class="input-field">
        <label>Product Description: </label>
        <input type="text" id="productDesc" name="productDesc" value="'.$productDesc.'"/>
        </div>
        <div class="input-field">
        <label>Extra data: </label>
        <input type="text" id="userDefined1" name="userDefined1" value="custom data"/>
        </div>
        <div class="input-field">
        <label>Amount: </label>
        <div class="show-price">'.$currency_Code.' '.$productPrice.'</div>
        <input type="text" id="amount" name="amount" value="000000010000"/>
        </div>
        <div class="input-field">
        <label>Currency: </label>
        <div class="show-currency">'.$currency_Code_name.'</div>
        <input type="text" id="currencyCode" name="currencyCode" value="'.$currencyCode.'"/>
        </div>
        
        <div class="input-field">
        <input type="submit" id="submit_form" name="submit_form" value="Pay Now"/>
        </div>
        </form>
        </div>';

        return $form;

    }

	 /**
     * Add shortcode for payment form 
     */
    public function hbl_form_shortcode($atts)
    {
        // [hbl_form ]
        $a = shortcode_atts( array(
            'price' => '000000010000',
            'productDesc' => 'something else',
        ), $atts );
        
       
        return $this->hbl_form($atts);
    }


}

//if( is_admin() )
    $form_shortcode = new Hbl_Payment_Gateway_Form();

