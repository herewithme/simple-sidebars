<?php
function have_custom_sidebar() {
	return (boolean) get_custom_sidebar_id();
}

function get_custom_sidebar_id( $object_id = 0, $check_parent = true, $return_object_id = false ) {
	global $wp_query;

	if ( is_singular() && (int) $object_id == 0 ) {
		$object_id = $wp_query->get_queried_object_id();
	}
	
	if ( (int) $object_id == 0 ) // Really not object ID ?
		return false;
		
	$sidebar = get_post_meta( $object_id, 'custom-sidebar', true );
	if ( empty($sidebar) && $check_parent == true ) {
		// Get ancestors
		$_p = get_post($object_id);
		_get_post_ancestors( $_p );
		
		// Try for each ancestors
		foreach( (array) $_p->ancestors as $ancestor ) {
			$sidebar = get_post_meta( (int) $ancestor, 'custom-sidebar', true );
			if ( !empty($sidebar) ) {
				if ( $return_object_id == true )
					return $ancestor;
				return $sidebar;
			}
		}
	} elseif( !empty($sidebar) ) {
		if ( $return_object_id == true )
			return $object_id;
		return $sidebar;
	}

	return false;
}
?>