<?php
class SimpleSidebars_Admin_Post {
	/**
	 * Constructor
	 *
	 * @return boolean
	 */
	public function __construct() {
		add_action( 'save_post', array(__CLASS__, 'saveCustomSidebars'), 10, 2 );
		add_action( 'add_meta_boxes', array(__CLASS__, 'initCustomSidebars'), 10, 2 );
	}
	
	/**
	 * Save custom sidebars when save object.
	 *
	 * @param string $post_ID
	 * @param object $post
	 * @return boolean
	 * @author Amaury Balmer
	 */
	public static function saveCustomSidebars( $post_ID = 0, $post = null ) {
		// Don't do anything when autosave 
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return false;
		
		// Check if the nonce field is correct
		if ( !isset( $_POST['custom_sidebar_nonce_field'] ) || !wp_verify_nonce( $_POST['custom_sidebar_nonce_field'], plugin_basename( __FILE__ ) ) )
			return false;
		
		if ( isset($_POST['custom-sidebar']) ) {
			if ( $_POST['custom-sidebar'] != '-' ) {
				update_post_meta( $post_ID, 'custom-sidebar', $_POST['custom-sidebar'] );
			} else {
				delete_post_meta( $post_ID, 'custom-sidebar' );
			}
		}
	}
	
	/**
	 * Add block for choose custom sidebars
	 *
	 * @param string $post_type
	 * @param object $post
	 * @return boolean
	 * @author Amaury Balmer
	 */
	public static function initCustomSidebars( $post_type = '', $post = null ) {
		foreach( get_post_types( array(), 'names' ) as $post_type ) {
			add_meta_box('simplesidebar-div', __('Custom sidebar', 'simple-sidebars'), array(__CLASS__, 'custom_sidebar_meta_box'), 'page', 'side');
		}
	}
	
	/**
	 * Displays a metabox with a select for choose the custom sidebar !
	 *
	 * @param string $object Not used.
	 * @param string $post_type The post type object.
	 */
	public static function custom_sidebar_meta_box( $object, $post_type ) {
		global $wp_registered_sidebars;
		
		// Get value from DB
		$current_value = get_post_meta( $object->ID, 'custom-sidebar', true );
		
		// Get current sidebar for this page ?
		$herited_sidebar = get_custom_sidebar_id( $object->ID, true, false );
		$herited_object  = get_custom_sidebar_id( $object->ID, true, true );
		
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'custom_sidebar_nonce_field' );
		
		// Hidden sidebars
		$current_sidebars_hide = get_option( SS_OPTION.'hide' );
		if ( $current_sidebars_hide == false )
			$current_sidebars_hide = array();
		?>
		<select name="custom-sidebar" class="widefat">
			<option value="-"><?php _e('No custom sidebar', 'simple-sidebars'); ?></option>
			<?php
			foreach( $wp_registered_sidebars as $sidebar ) :
				if ( in_array($sidebar['id'], $current_sidebars_hide) )
					continue;
				?>
				<option <?php selected($sidebar['id'], $current_value); ?> value="<?php echo esc_attr($sidebar['id']); ?>"><?php echo esc_html(stripslashes($sidebar['name'])); ?></option>
			<?php endforeach; ?>
		</select>
		
		<?php if ( !empty($herited_sidebar) ) : ?>
			<p>
				<?php printf(__('This page use the sidebar <strong>&laquo;%s&raquo;</strong>, it herited from the page <strong>&laquo;%s&raquo;</strong>'), $wp_registered_sidebars[$herited_sidebar]['name'], get_the_title($herited_object) ); ?>
			</p>
		<?php endif; ?>
		
		<p>
			<a href="<?php echo admin_url('themes.php?page='.SimpleSidebars_Admin::admin_slug.'#form-add-sidebar'); ?>" target="_blank"><?php _e('Add a custom sidebar', 'simple-sidebars'); ?></a>
		</p>
		<?php
	}
}
?>