<?php
namespace HelloWP\ElementorExtraConditions\WpConditions;

use ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base;

class User_Role extends Condition_Base {

    public static function get_type() {
        return 'general';
    }

    public function get_name() {
        return 'user_role';
    }

    public function get_label() {
        return esc_html__( 'User Roles', 'elementor-extra-conditions' );
    }

    public function check( $args ) {
        return true;
    }
    
    public function register_sub_conditions() {
        $wp_roles = wp_roles();
        $roles = $wp_roles->get_names();
        foreach ( array_keys( $roles ) as $role_slug ) {
            $this->register_sub_condition( new User_Role_Types( $role_slug ) );
        }
    }
}
