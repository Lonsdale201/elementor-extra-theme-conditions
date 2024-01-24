<?php
/**
 * Plugin Name: HelloWP! | Elementor Extra Theme Conditions
 * Description: Add extra theme conditions like user roles, woo specific conditions, and logged in / out 
 * Version: 1.0
 * Author: Soczó Kristóf
 * Author URI: https://hellowp.io/
 * Plugin URI:  https://github.com/Lonsdale201/elementor-extra-theme-conditions
 * Text Domain: elementor-extra-conditions
 * Elementor tested up to: 3.18.3
 * Elementor Pro tested up to: 3.18.3
 */


 if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

require_once __DIR__ . '/vendor/autoload.php';

use HelloWP\ElementorExtraConditions\WpConditions\User_Role;
use HelloWP\ElementorExtraConditions\WpConditions\User_Role_Types;
use HelloWP\ElementorExtraConditions\WpConditions\User_Status_Condition;
use HelloWP\ElementorExtraConditions\WpConditions\Logged_In_Condition;
use HelloWP\ElementorExtraConditions\WpConditions\Logged_Out_Condition;

use HelloWP\ElementorExtraConditions\WooConditions\Woo_Extras_Condition;
use HelloWP\ElementorExtraConditions\WooConditions\Is_Variable_Product_Condition;
use HelloWP\ElementorExtraConditions\WooConditions\Is_Digital_Product_Condition;
use HelloWP\ElementorExtraConditions\WooConditions\Is_Product_On_Sale_Condition;
use HelloWP\ElementorExtraConditions\WooConditions\Is_Download_Product_Condition;
use HelloWP\ElementorExtraConditions\WooConditions\Is_Product_In_Stock_Condition;
use HelloWP\ElementorExtraConditions\WooConditions\Is_Product_Out_Of_Stock_Condition;


final class Plugin {

    /**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string 
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.17.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string 
	 */
	const MINIMUM_PHP_VERSION = '7.4';

	/**
	 * Constructor
	 *
	 * Perform some compatibility checks to make sure basic requirements are meet.
	 * If all compatibility checks pass, initialize the functionality.
	 *
	 * @since 1.0.0
	 * @access public
	 */

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init_on_plugins_loaded' ] );
		add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'elementor-extra-conditions', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

    public function init_on_plugins_loaded() {
        if ( $this->is_compatible() ) {
            add_action( 'elementor/init', [ $this, 'init' ] );
        }
    }

	public function is_compatible() {

		// Check if Elementor is installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return false;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return false;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return false;
		}

		return true;

	}

	public function init() {	
		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'elementor/theme/register_conditions', [ $this, 'register_woo_conditions' ] );
		}

		add_action( 'elementor/theme/register_conditions', [ $this, 'register_conditions' ] );
		add_action( 'elementor/theme/register_conditions', [ $this, 'register_user_role_conditions' ] );
	}
	

	public function register_conditions( $conditions_manager ) {
		$conditions_manager->get_condition( 'general' )->register_sub_condition( new User_Status_Condition() );
	}	
	
	public function register_woo_conditions( $conditions_manager ) {
		$conditions_manager->get_condition( 'general' )->register_sub_condition( new Woo_Extras_Condition() );
	}
	
	public function register_user_role_conditions( $conditions_manager ) {
		$conditions_manager->get_condition( 'general' )->register_sub_condition( new User_Role() );
	}

    /**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-extra-conditions' ),
			'<strong>' . esc_html__( 'Elementor extra Theme Conditions', 'elementor-extra-conditions' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-extra-conditions' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-extra-conditions' ),
			'<strong>' . esc_html__( 'Elementor extra Theme Conditions', 'elementor-extra-conditions' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-extra-conditions' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-extra-conditions' ),
			'<strong>' . esc_html__( 'Elementor Extra Theme Conditions', 'elementor-extra-conditions' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementor-extra-conditions' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

}

\Plugin::instance();

