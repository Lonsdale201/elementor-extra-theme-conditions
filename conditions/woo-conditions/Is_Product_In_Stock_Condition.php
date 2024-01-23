<?php

namespace HelloWP\ElementorExtraConditions\WooConditions;

use ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base;



class Is_Product_In_Stock_Condition extends Condition_Base {

    public static function get_type() {
        return 'woocommerce';
    }

    public function get_name() {
        return 'is_product_in_stock';
    }

    public function get_label() {
        return esc_html__( 'Is Product In Stock', 'elementor-extra-conditions' );
    }

    public function check( $args ) {
        global $product;

        if ( ! $product instanceof WC_Product ) {
            $product = wc_get_product( get_the_ID() );
        }

        return $product ? $product->is_in_stock() : false;
    }
}
