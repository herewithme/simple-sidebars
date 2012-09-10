<?php
class SimpleSidebars_Client {
	/**
	 * Constructor, register hook for custom sidebars
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public function __construct() {
		add_action( 'widgets_init', array(__CLASS__, 'widgets_init'), 11 );
	}
	
	/**
	 * Init custom sidebars
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public static function widgets_init() {
		$current_sidebars = get_option( SS_OPTION );
		if ( is_array($current_sidebars) && !empty($current_sidebars) ) {
			foreach( $current_sidebars as $cs ) {
				register_sidebar( stripslashes_deep($cs) );
			}
		}
	}

	public static function activate() {
		$role = get_role( 'administrator' );
		if ( !empty( $role ) ) {
			$role->add_cap( 'manage_custom_sidebars' );
		}
	}
}
?>