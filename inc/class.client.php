<?php
class SimpleSidebars_Client {
	/**
	 * Constructor, register hook for custom sidebars
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public function SimpleSidebars_Client() {
		add_action( 'widgets_init', array(__CLASS__, 'initCustomSidebars'), 11 );
	}
	
	/**
	 * Init custom sidebars
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public static function initCustomSidebars() {
		$current_sidebars = get_option( SS_OPTION );
		if ( is_array($current_sidebars) && !empty($current_sidebars) ) {
			foreach( $current_sidebars as $cs ) {
				register_sidebar( stripslashes_deep($cs) );
			}
		}
	}
}
?>