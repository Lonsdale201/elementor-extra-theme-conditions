<?php

namespace HelloWP\ElementorExtraConditions\WooConditions;

use ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base;

class Is_Digital_Product_Condition extends Condition_Base {

public static function get_type() {
    return 'woocommerce';
}

public function get_name() {
    return 'is_digital_product';
}

public function get_label() {
    return esc_html__( 'Is Digital Product', 'elementor-extra-conditions' );
}

public function check( $args ) {
    global $product;

    if ( ! $product instanceof WC_Product ) {
        $product = wc_get_product( get_the_ID() );
    }

    // Check if product is virtual
    if ( $product ) {
        if ( $product->is_virtual() ) {
            return true;
        }

        // check include virtual
        if ( $product->is_type( 'variable' ) ) {
            foreach ( $product->get_children() as $child_id ) {
                $variation = wc_get_product( $child_id );
                if ( $variation && $variation->is_virtual() ) {
                    return true;
                }
            }
        }
    }

    return false;
}
}