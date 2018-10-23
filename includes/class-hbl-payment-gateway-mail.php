<?php
/**
 *
 * Class for  mail after submitting payment form.
 *
 * @link       https://www.lastdoorsolutions.com/
 * @since      1.0.0
 *
 * @package    Hbl_Payment_Gateway
 * @subpackage Hbl_Payment_Gateway/includes
 */

class Hbl_Payment_Gateway_Mail {

    /**
     * Holds the values to be used in the fields callbacks.
     *
     */
    protected $options;
    protected $mail_options;


    /**
     * Define the Option page functionality of the plugin.
     */
    public function __construct() {
        $this->options = get_option('hbl_option_name');
        $this->mail_options = get_option('hbl_option_name_mail');
        add_action('template_redirect',array($this,'thankyou_page'));

    }

    /**
     * [payment_mail_notification description]
     * @param  [type] $content [description]
     * @return [type]          [description]
     */
    public function payment_mail_notification($content){
        //payement returned values
        $Payment_userDefined1 = $_POST['userDefined1']; 
        $price_n = $_POST['Amount'];
        $price_c = ltrim($price_n, '0');
        $num = 100;
        $price = ((int)$price_c/(int)$num);
        $Payment_invoiceNo = $_POST['invoiceNo'];
        $Payment_approvalCode = $_POST['approvalCode'];
        $payment_detail_mail ="<h4>".$Payment_userDefined1."</h4>"; 
        $payment_detail_mail .="<p>Price: ".$price."</p>"; 
        $payment_detail_mail .="<p>Invoice".$Payment_invoiceNo."</p>"; 

        $to = $this->mail_options['mail_header'];
        $subject = $this->mail_options['mail_subject'];
        $body = $this->mail_options['mail_body'];
        $body .= $payment_detail_mail;
        $body .= $this->mail_options['footer'];
       
        $headers = array('Content-Type: text/html; charset=UTF-8');
        if(!empty($Payment_approvalCode)){
          if($this->mail_options['hbl_mail_checkbox'] ==1){
              $check_mail = wp_mail( $to, $subject, $body, $headers );
              if($check_mail){
                  return "<h3>Mail notification send successfully.</h3><br/>".$this->options['thankyou_page_success'].$content;
              }else{
                  return "<h3>Mail notification fail.</h3><br/>".$this->options['thankyou_page_success'].$content;
            }
          }
        }else{
          //echo $this->options['thankyou_page_fail'].$content;
        }
    }

    /**
     * [save_payment_data description]
     * @return [type] [description]
     */
    public function save_payment_data(){

        $thankyou_page = $this->options['thankyou_page'];
        $current_url =   $_SERVER[REQUEST_URI]; 

        if( is_page('hbl-thank-you')){
            //Update the post into the database
            //update only if returned from HBL page
            $Payment_userDefined1 = $_POST['userDefined1']; 
            $price_n = $_POST['Amount'];
            $price_c = ltrim($price_n, '0');
            $num = 100;
            $price = ((int)$price_c/(int)$num);
            $Payment_invoiceNo = $_POST['invoiceNo'];
            $Payment_approvalCode = $_POST['approvalCode'];
            if(!$payment_details){
                $payment_details = "Detail not available";
            }

             if(!empty($Payment_approvalCode)){  
                $status ="Completed/Approved";
            }else{
                $status ="Incomplete/Cancalled";
            }  
                $my_post = array(
                  'post_type' => 'hbl_payments' ,
                  'post_status' => 'private' ,
                  //'post_title'   => $Payment_name,
                  'post_title'   => wp_strip_all_tags( 'username' ),
                  //'post_content' => payment_detail(),
                  'post_content' => $payment_details,
                  'meta_input' => array(
                    'Invoice' => $Payment_invoiceNo,
                    'Payment' => $status,
                    'approvalCode'=>$Payment_approvalCode
                    )
                );
            //check the invoice last updates
            $args = array(
                'post_type'=>'hbl_payments',
                'post_status' => 'any',
                'meta_key' => 'Invoice' 
            );
            $invoice_arr = array();
                $the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) {
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post();
                        $invoice_arr[] = get_post_meta(get_the_ID(),'Invoice',true);
                    }
                    wp_reset_postdata();
                }

                //if(!empty($Payment_invoiceNo) && !in_array($Payment_invoiceNo, $invoice_arr)){ //activate it later
                    $posted_paydata = wp_insert_post( $my_post );
                    if($posted_paydata){
                    return "Payment data saved !!!!";
                    }else{
                     return "Payment data NOT saved !!!";
                    }
                //}

        }

    }

    /**
     * Thankyou page mail/data save process
     * @return [type] [description]
     */
    public function thankyou_page(){
        $thankyou_page = $this->options['thankyou_page'];
        $current_url =   $_SERVER[REQUEST_URI]; 
        if( is_page('hbl-thank-you')){
             if($this->mail_options['hbl_mail_checkbox'] ==1){
                $this->payment_mail_notification($content);
                $this->save_payment_data();
                //show message related to mail function along with the content
                add_filter( 'the_content', array($this,'payment_mail_notification' ));
                add_filter( 'the_content', array($this,'save_payment_data' ));
            }
        }



    }


}

//if( is_admin() )
$form_mail = new Hbl_Payment_Gateway_Mail();