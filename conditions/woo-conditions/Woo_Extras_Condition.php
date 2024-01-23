<?php

namespace HelloWP\ElementorExtraConditions\WooConditions;

use ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base;


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Woo_Extras_Condition extends Condition_Base {

    public static function get_type() {
        return 'general';
    }

    public function get_name() {
        return 'woo_extras';
    }

    public function get_label() {
        return esc_html__( 'Woo Extras', 'elementor-extra-conditions' );
    }

    public function check( $args ) {
		return true;
	}

    public function register_sub_conditions() {
        $this->register_sub_condition( new Is_Digital_Product_Condition() );
        $this->register_sub_condition( new Is_Product_In_Stock_Condition() );
        $this->register_sub_condition( new Is_Product_Out_Of_Stock_Condition() );
        $this->register_sub_condition( new Is_Download_Product_Condition() );
        $this->register_sub_condition( new Is_Variable_Product_Condition() );
        $this->register_sub_condition( new Is_Product_On_Sale_Condition() );
    }    
}
