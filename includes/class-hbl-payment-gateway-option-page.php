<?php

/**
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
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
class Hbl_Payment_Gateway_Option_Page
{
    /**
     * Holds the values to be used in the fields callbacks.
     *
     */
    protected $options;


    /**
     * Define the Option page functionality of the plugin.
     */
    public function __construct()
    {

        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));

    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_menu_page(
            'HBL settings',
            'HBL Payment',
            'manage_options',
            'hbl-setting-page',
            array($this, 'hbl_setting_general'),
            'dashicons-money', 90
        );
        add_submenu_page(
            'hbl-setting-page',
            'HBL Credentials',
            'HBL Credentials',
            'manage_options',
            'hbl-setting-page-general',
            array($this, 'hbl_setting_page')

        );
        add_submenu_page(
            'hbl-setting-page',
            'HBL Mail',
            'HBL Mail',
            'manage_options',
            'hbl-setting-page-mail',
            array($this, 'hbl_setting_mail')
        );
    }

    /**
     * Credential Options page callback
     */
    public function hbl_setting_page()
    {
        $this->options = get_option('hbl_option_name');
        ?>
        <div class="wrap">
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('hbl_option_group');
                do_settings_sections('hbl-setting-admin');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }


    /**
     * General setting Options  callback
     */
    public function hbl_setting_general()
    {
        $this->options = get_option('hbl_option_name_general');
       // var_dump($this->options);
        ?>
        <div class="wrap general-setting-form">
            <!--  <h1>HBL Payment Option</h1> -->

            <img src="<?php echo plugins_url(); ?>/HBL payment gateway/assets/images/logo.png">

            <h1>Himalayan Bank Payment Gateway</h1>
            <h3>Merchant Integration Guide</h3>
            <p>Version 1.0</p>

            <div class="info-wrp">
                <strong>Transaction with 3d secure enabled credit/debit card</strong>
                <br/>
                <p>1. Cardholder visits merchant website and completes purchase and proceed for
                    payment.</p>
                <p>2. During checkout process merchant website prepare payment request to HBL
                    payment gateway and customer will see bank payment page to enter card
                    information.</p>
                <p>3. Once cardholder has fill all card details and confirm payment. Payment gateway
                    proceed with 3D secure authentication using MPI (merchant plugin). Initial 3D secure
                    message from MPI is Verify enrollment request to card scheme directory server to
                    see if cardholder issuing bank is able to perform authentication.</p>
                <p>4. Initial 3D secure message from MPI is Verify enrollment request to card scheme
                    directory server to see if cardholder issuing bank is able to perform authentication.
                <p>5. Card scheme directory server forward Verify enrollment request to issuing bank in
                    case issue is participating in 3D secure. Issuer then response Verify enrollment
                    response back to directory what is redirected back to MPI.</p>
                <p>6. Second step of authentication is payer authentication request. MPI post
                    cardholder to issuing bank ACS page to complete authentication.</p>
                <p>7. Cardholder enters one time password in bank website and ACS prepare
                    authentication result back to MPI.</p>
                <p>8. MPI receives authentication result from ACS and proceed with standard
                    authorization</p>
                <p>9. Authorization request to charge the transaction amount for the card</p>
                <p>10. Credit card host sends out authorization message to card scheme network</p>
                <p>11. Transaction is routed to issuer system for approval</p>
                <p>12. Card scheme sends authorization response back to acquiring system</p>
            </div>
            <form method="post" action="options.php" class="">
                <?php
                // This prints out all hidden setting fields
                settings_fields('hbl_option_group_general');
                do_settings_sections('hbl-setting-page');
                //submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Mail Options page callback
     */
    public function hbl_setting_mail()
    {
        $this->options = get_option('hbl_option_name_mail');
        ?>
        <div class="wrap">
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('hbl_option_group_mail');
                do_settings_sections('hbl-setting-mail');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'hbl_option_group',
            'hbl_option_name',
            array($this, 'sanitize')
        );

        add_settings_section(
            'setting_section_id',
            'HBL PaymentGateway Options',
            array($this, 'print_section_info'),
            'hbl-setting-admin'
        );

        add_settings_field(
            'Paymentgetway_ID',
            'Paymentgetway ID',
            array($this, 'Paymentgetway_ID_callback'),
            'hbl-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'hash_code',
            'Hash Code',
            array($this, 'hash_code_callback'),
            'hbl-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'hbl_currency',
            'Choose Your Currency',
            array($this, 'currency_callback'),
            'hbl-setting-admin',
            'setting_section_id'
        );
         add_settings_field(
            'hbl_invoice_number',
            'Invoice Number',
            array($this, 'invoice_number_callback'),
            'hbl-setting-admin',
            'setting_section_id'
        );
        add_settings_field(
            'hbl_thankyou_page',
            'Thankyou Page Url. Please create hbl-thank-you page.',
            array($this, 'thankyou_page_callback'),
            'hbl-setting-admin',
            'setting_section_id'
        );
        add_settings_field(
            'hbl_thankyou_page_success',
            'Thankyou page success note',
            array($this, 'thankyou_page_success_callback'),
            'hbl-setting-admin',
            'setting_section_id'
        );
        add_settings_field(
            'hbl_thankyou_page_fail',
            'Thankyou page fail note',
            array($this, 'thankyou_page_fail_callback'),
            'hbl-setting-admin',
            'setting_section_id'
        );

        //for general settings
        register_setting(
            'hbl_option_group_general',
            'hbl_option_name_general',
            array($this, 'sanitize')
        );
        add_settings_section(
            'setting_section_id',
            'HBL PaymentGateway Introduction',
            array($this, 'print_section_info'),
            'hbl-setting-page'
        );

        //for email settings
        register_setting(
            'hbl_option_group_mail',
            'hbl_option_name_mail',
            array($this, 'sanitize')
        );
        add_settings_section(
            'setting_section_mail',
            'HBL PaymentGateway Mail Setting',
            array($this, 'print_section_info'),
            'hbl-setting-mail'
        );
        add_settings_field(
            'hbl_mail_checkbox',
            'Activate  email notification?',
            array($this, 'mail_callback'),
            'hbl-setting-mail',
            'setting_section_mail'
        );
        add_settings_field(
            'hbl_mail_address',
            'Admin Mailing address',
            array($this, 'mail_address_callback'),
            'hbl-setting-mail',
            'setting_section_mail'
        );
        add_settings_field(
            'hbl_mail_header',
            'Admin Mail Subject',
            array($this, 'mail_header_callback'),
            'hbl-setting-mail',
            'setting_section_mail'
        );
       
        add_settings_field(
            'hbl_mail_body',
            'Admin Mail Body',
            array($this, 'mail_body_callback'),
            'hbl-setting-mail',
            'setting_section_mail'
        );
         add_settings_field(
            'hbl_mail_footer',
            'Admin Mail footer',
            array($this, 'mail_footer_callback'),
            'hbl-setting-mail',
            'setting_section_mail'
        );

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $new_input = array();
        if (isset($input['Paymentgetway_ID']))
            $new_input['Paymentgetway_ID'] = sanitize_text_field($input['Paymentgetway_ID']);

        if (isset($input['hash_code']))
            $new_input['hash_code'] = sanitize_text_field($input['hash_code']);

        if (isset($input['currencyCode']))
            $new_input['currencyCode'] = sanitize_text_field($input['currencyCode']);

        if (isset($input['thankyou_page']))
            $new_input['thankyou_page'] = sanitize_text_field($input['thankyou_page']);

        if (isset($input['thankyou_page_success']))
            $new_input['thankyou_page_success'] = sanitize_text_field($input['thankyou_page_success']);

        if (isset($input['thankyou_page_fail']))
            $new_input['thankyou_page_fail'] = sanitize_text_field($input['thankyou_page_fail']);
        
        if (isset($input['invoice_number']))
            $new_input['invoice_number'] = sanitize_text_field($input['invoice_number']);

        if (isset($input['hbl_mail_checkbox']))
            $new_input['hbl_mail_checkbox'] = sanitize_text_field($input['hbl_mail_checkbox']);

        if (isset($input['currency_symbol']))
            $new_input['currency_symbol'] = sanitize_text_field($input['currency_symbol']);

        if (isset($input['mail_address']))
            $new_input['mail_address'] = sanitize_text_field($input['mail_address']);

        if (isset($input['mail_header']))
            $new_input['mail_header'] = sanitize_text_field($input['mail_header']);

        if (isset($input['mail_footer']))
            $new_input['mail_footer'] = sanitize_text_field($input['mail_footer']);

        if (isset($input['mail_body']))
            $new_input['mail_body'] = sanitize_text_field($input['mail_body']);

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Please contact Himalayan Bank Limited for your security code and Payment Gateway ID.DO not to share these codes with others.';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function Paymentgetway_ID_callback()
    {
        printf(
            '<input type="text" id="Paymentgetway_ID" name="hbl_option_name[Paymentgetway_ID]" value="%s" />',
            isset($this->options['Paymentgetway_ID']) ? esc_attr($this->options['Paymentgetway_ID']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function hash_code_callback()
    {
        printf(
            '<input type="text" id="hash_code" name="hbl_option_name[hash_code]" value="%s" />',
            isset($this->options['hash_code']) ? esc_attr($this->options['hash_code']) : ''
        );
    }

     /**
     * Get the settings option array and print one of its values
     */
    public function thankyou_page_callback()
    {
        printf(
            '<input type="text" id="thankyou_page" name="hbl_option_name[thankyou_page]" value="%s" />',
            isset($this->options['thankyou_page']) ? esc_attr($this->options['thankyou_page']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function thankyou_page_success_callback()
    {
        printf(
            '<input type="text" id="thankyou_page_success" name="hbl_option_name[thankyou_page_success]" value="%s" />',
            isset($this->options['thankyou_page_success']) ? esc_attr($this->options['thankyou_page_success']) : 'Payment process completed successfully.Thank you.'
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function thankyou_page_fail_callback()
    {
        printf(
            '<input type="text" id="thankyou_page_fail" name="hbl_option_name[thankyou_page_fail]" value="%s" />',
            isset($this->options['thankyou_page_fail']) ? esc_attr($this->options['thankyou_page_fail']) : 'Payment process could not completed.Please try again.'
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function invoice_number_callback()
    {
        printf(
            '<input type="text" id="invoice_number" name="hbl_option_name[invoice_number]" value="%s" />',
            isset($this->options['invoice_number']) ? esc_attr($this->options['invoice_number']) : ''
        );
    }
    /**
     * Get the settings option array and print one of its values
     */
    public function currencyCode_callback()
    {
        printf(
            '<input type="text" id="currencyCode" name="hbl_option_name[currencyCode]" value="%s" />',
            isset($this->options['currencyCode']) ? esc_attr($this->options['currencyCode']) : ''
        );
    }

    /**
     * Mail option callback
     */
    public function mail_callback()
    {
        //The key thing that makes it a checkbox or textbox is the input type attribute. The thing that links it to my plugin option is the name I pass it.
        if ($this->options['hbl_mail_checkbox'] == "1") {
            $checked = "checked";
        }
        echo "<input id='plugin_checkbox' name='hbl_option_name_mail[hbl_mail_checkbox]' type='checkbox' value='1' 
             " . $checked . "/>";

    }

    /**
     * Mail header callback
     */
    public function mail_header_callback()
    {
        printf(
            '<textarea id="mail_header" name="hbl_option_name_mail[mail_header]" value="" cols="80" rows="5">%s</textarea>',
            isset($this->options['mail_header']) ? esc_attr($this->options['mail_header']) : ''
        );

    }

    /**
     * Mail header callback
     */
    public function mail_address_callback()
    {
        printf(
            '<input type="text" id="mail_address" name="hbl_option_name_mail[mail_address]" value="%s" />',
            isset($this->options['mail_address']) ? esc_attr($this->options['mail_address']) : ''
        );

    }

    /**
     * Mail footer callback
     */
    public function mail_footer_callback()
    {
        printf(
            '<textarea id="mail_footer" name="hbl_option_name_mail[mail_footer]" value="" cols="80" rows="5">%s</textarea>',
            isset($this->options['mail_footer']) ? esc_attr($this->options['mail_footer']) : ''
        );

    }

    /**
     * Mail body callback
     */
    public function mail_body_callback()
    {
        $this->options = get_option('hbl_option_name_mail');
        $value = isset($this->options['mail_body']) ? esc_attr($this->options['mail_body']) : '' ;
         $settings = array(
            'textarea_name' => hbl_option_name_mail.'['.mail_body.']', 
            'editor_class' => 'class',
             'media_buttons' => false 
         );
        echo wp_editor( $value, 'mail_body',$settings); 
       
            // '<textarea  id="mail_body" name="hbl_option_name_mail[mail_body]" value="" cols="30" rows="10">%s</textarea>',
            // isset($this->options['mail_body']) ? esc_attr($this->options['mail_body']) : ''
            //);

    }

    public function currency_callback()
    {
        ?>
        <select name='hbl_option_name[currency_symbol]'>
            <option value='524' <?php if ($this->options['currency_symbol'] == "524") {
                echo "selected";
            } ?>>Nepalese Rupee
            </option>
            <option value='840' <?php if ($this->options['currency_symbol'] == "840") {
                echo "selected";
            } ?>>United States dollar
            </option>
        </select>

        <?php
    }


}

if (is_admin())
    $option_page = new Hbl_Payment_Gateway_Option_Page();
