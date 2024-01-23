<?php

namespace HelloWP\ElementorExtraConditions\WooConditions;

use ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base;


class Is_Download_Product_Condition extends Condition_Base {

    public static function get_type() {
        return 'woocommerce';
    }

    public function get_name() {
        return 'is_downloadable_product';
    }

    public function get_label() {
        return esc_html__( 'Is Downloadable Product', 'elementor-extra-conditions' );
    }

    public function check( $args ) {
        global $product;

        if ( ! $product instanceof WC_Product ) {
            $product = wc_get_product( get_the_ID() );
        }

        if ( $product ) {
            if ( $product->is_downloadable() ) {
                return true;
            }

            if ( $product->is_type( 'variable' ) ) {
                foreach ( $product->get_children() as $child_id ) {
                    $variation = wc_get_product( $child_id );
                    if ( $variation && $variation->is_downloadable() ) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}