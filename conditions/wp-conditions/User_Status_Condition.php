<?php
namespace HelloWP\ElementorExtraConditions\WpConditions;

use ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base;

class User_Status_Condition extends Condition_Base {

    public static function get_type() {
        return 'general';
    }

    public function get_name() {
        return 'user_status';
    }

    public function get_label() {
        return esc_html__( 'User Status', 'elementor-extra-conditions' );
    }

    public function check( $args ) {
        return is_user_logged_in() ? 'logged_in' : 'logged_out';
    }
    
    public function register_sub_conditions() {
        $this->register_sub_condition( new Logged_In_Condition() );
        $this->register_sub_condition( new Logged_Out_Condition() );
    }
}
