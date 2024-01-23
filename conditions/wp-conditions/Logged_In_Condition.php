<?php

namespace HelloWP\ElementorExtraConditions\WpConditions;

use ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Logged_In_Condition extends Condition_Base {
    public static function get_type() {
        return 'user_status';
    }

    public function get_name() {
        return 'logged_in';
    }

    public function get_label() {
        return esc_html__( 'Logged In', 'elementor-extra-conditions' );
    }

    public function check( $args ) {
        return is_user_logged_in();
    }
}
