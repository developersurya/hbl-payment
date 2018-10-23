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
class Hbl_Payment_Gateway_Records
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

        add_action('init', array($this, 'create_post_type'));

    }
    /**
	 * Add post type on plugin activation
	 */
    public function create_post_type() 
    {
		register_post_type( 'hbl_payments',
		  array(
			'labels' => array(
			  'name' => __( 'HBL payments records' ),
			  'singular_name' => __( 'HBL payments records' )
			),
			'public' => true,
            'has_archive' => true,
            'supports' => array( 'title','editor','custom-fields')
		  )
		);
    }
}
$records = new Hbl_Payment_Gateway_Records;


