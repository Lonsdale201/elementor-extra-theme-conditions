<?php

namespace HelloWP\ElementorExtraConditions\WooConditions;

use ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base;

class Is_Variable_Product_Condition extends Condition_Base {

    public static function get_type() {
        return 'woocommerce';
    }

    public function get_name() {
        return 'is_variable_product';
    }

    public function get_label() {
        return esc_html__( 'Is Variable Product', 'elementor-extra-conditions' );
    }

    public function check( $args ) {
        global $product;

        if ( ! $product instanceof WC_Product ) {
            $product = wc_get_product( get_the_ID() );
        }

        if ( $product && $product->is_type( 'variable' ) ) {
            return true;
        }

        return false;
    }
}