<?php
class SimpleSidebars_Client {
	/**
	 * Constructor, register hooks
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	function SimpleSidebars_Client() {
		// Register hook for custom sidebars
		add_action( 'widgets_init', array(&$this, 'initCustomSidebars'), 11 );
	}
	
	/**
	 * Init custom sidebars
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	function initCustomSidebars() {
		$current_sidebars = get_option( SS_OPTION );
		if ( is_array($current_sidebars) && !empty($current_sidebars) ) {
			foreach( $current_sidebars as $cs ) {
				register_sidebar( $this->stripslashes_deep($cs) );
			}
		}
	}
	
	/**
	 * Stripslashes on array
	 */
	function stripslashes_deep($value) {
		$value = is_array($value) ? array_map(array(&$this, 'stripslashes_deep'), $value) : stripslashes($value);
		return $value;
	}
}
?>