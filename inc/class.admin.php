<?php
class SimpleSidebars_Admin {
	var $admin_url 	= '';
	var $admin_slug = 'simple-sidebars-settings';
	
	// Error management
	var $message = '';
	var $status  = '';
	
	/**
	 * Constructor
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	function SimpleSidebars_Admin() {
		$this->admin_url = admin_url( 'options-general.php?page='.$this->admin_slug );
		
		// Register hooks
		add_action( 'admin_init', array(&$this, 'checkSidebars') );
		add_action( 'admin_menu', array(&$this, 'addMenu') );
	}
	
	/**
	 * Add settings menu page
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	function addMenu() {
		add_options_page( __('Simple Sidebars', 'simple-sidebars'), __('Simple Sidebars', 'simple-sidebars'), 'manage_options', $this->admin_slug, array( &$this, 'pageManage' ) );
	}
	
	/**
	 * Display options on admin
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	function pageManage() {
		global $wp_registered_sidebars;
		
		// Display message
		$this->displayMessage();
		
		// Get default settings
		$default_settings = $this->stripslashes_deep(get_option( SS_OPTION . 'default' ));
		
		// Current sidebars
		$current_sidebars = get_option( SS_OPTION );
		
		// Hidden sidebars
		$current_sidebars_hide = get_option( SS_OPTION.'hide' );
		if ( $current_sidebars_hide == false )
			$current_sidebars_hide = array();
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2 class="nav-tab-wrapper">
				<a id="tab-simple-sidebar-list" class="nav-tab" href="#simple-sidebar-list"><?php _e("Custom sidebars", 'simple-sidebars'); ?></a>
				<a id="tab-simple-sidebar-add" class="nav-tab" href="#simple-sidebar-add"><?php _e("Add a new sidebar", 'simple-sidebars'); ?></a>
				<a id="tab-simple-sidebar-settings" class="nav-tab" href="#simple-sidebar-settings"><?php _e("Settings sidebar", 'simple-sidebars'); ?></a>
				<a id="tab-simple-sidebar-theme" class="nav-tab" href="#simple-sidebar-theme"><?php _e("Hidden theme sidebar", 'simple-sidebars'); ?></a>
			</h2>
			<br />
			
			<div id="simple-sidebar-list" class="group">
				<form action="<?php echo $this->admin_url; ?>" method="post">
					<?php if ( is_array($current_sidebars) && !empty($current_sidebars) ) : ?>
						<table class="widefat">
							<thead>
								<tr>
									<th scope="col"><?php _e('ID', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('Name', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('Description', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('Before widget', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('After widget', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('Before title', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('After title', 'simple-sidebars'); ?></th>
									<th class="manage-column"><?php _e('Delete ?', 'simple-taxonomy'); ?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th scope="col"><?php _e('ID', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('Name', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('Description', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('Before widget', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('After widget', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('Before title', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('After title', 'simple-sidebars'); ?></th>
									<th class="manage-column"><?php _e('Delete ?', 'simple-taxonomy'); ?></th>
								</tr>
							</tfoot>
							<tbody id="the-list" class="list:sidebars">
								<?php
								$class = 'alternate';
								$i = 0;
								foreach ( (array) $current_sidebars as $cs ) :
									$i++;
									$class = ( $class == 'alternate' ) ? '' : 'alternate';
									?>
									<tr id="custom-sidebar-<?php echo $i; ?>" class="<?php echo $class; ?>">
										<td class="name column-name"><input type="text" class="widefat" name="sidebar[<?php echo $cs['id']; ?>][id]" value="<?php echo esc_attr(stripslashes($cs['id'])); ?>" readonly="readonly" /></td>
										<td class="name column-name"><input type="text" class="widefat" name="sidebar[<?php echo $cs['id']; ?>][name]" value="<?php echo esc_attr(stripslashes($cs['name'])); ?>" /></td>
										<td class="name column-name"><input type="text" class="widefat" name="sidebar[<?php echo $cs['id']; ?>][description]" value="<?php echo esc_attr(stripslashes($cs['description'])); ?>" /></td>
										<td class="name column-name"><input type="text" class="widefat" name="sidebar[<?php echo $cs['id']; ?>][before_widget]" value="<?php echo esc_attr(stripslashes($cs['before_widget'])); ?>" /></td>
										<td class="name column-name"><input type="text" class="widefat" name="sidebar[<?php echo $cs['id']; ?>][after_widget]" value="<?php echo esc_attr(stripslashes($cs['after_widget'])); ?>" /></td>
										<td class="name column-name"><input type="text" class="widefat" name="sidebar[<?php echo $cs['id']; ?>][before_title]" value="<?php echo esc_attr(stripslashes($cs['before_title'])); ?>" /></td>
										<td class="name column-name"><input type="text" class="widefat" name="sidebar[<?php echo $cs['id']; ?>][after_title]" value="<?php echo esc_attr(stripslashes($cs['after_title'])); ?>" /></td>
										<td style="text-align:center;"><input name="sidebar[<?php echo $cs['id']; ?>][delete]" type="checkbox" value="1" /></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<p class="submit">
							<?php wp_nonce_field( 'save-csidebars-settings' ); ?>
							<input class="button-primary" name="save-csidebars" type="submit" value="<?php _e('Save custom sidebars', 'simple-sidebars'); ?>" />
						</p>
					</form>
				<?php endif;?>
			</div>
			
			<div id="simple-sidebar-add" class="group">
				<h3><?php _e('Add a sidebar', 'simple-sidebars'); ?></h3>
				
				<form method="post" action="<?php echo $this->admin_url; ?>">
					<table class="form-table">
						<tr class="form-field form-required">
							<th scope="row"><?php _e('ID', 'simple-sidebars'); ?></th>
							<td><input class="regular-text" type="text" name="nsidebar[id]" /></td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><?php _e('Name', 'simple-sidebars'); ?></th>
							<td><input class="regular-text" type="text" name="nsidebar[name]" /></td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><?php _e('Description', 'simple-sidebars'); ?></th>
							<td><input class="regular-text" type="text" name="nsidebar[description]" /></td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><?php _e('Before widget', 'simple-sidebars'); ?></th>
							<td><input class="regular-text" type="text" name="nsidebar[before_widget]" value="<?php echo esc_attr($default_settings['before_widget']); ?>" onclick="if (this.value == '<?php echo esc_js(esc_attr($default_settings['before_widget'])); ?>') this.value = '';" /></td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><?php _e('After widget', 'simple-sidebars'); ?></th>
							<td><input class="regular-text" type="text" name="nsidebar[after_widget]" value="<?php echo esc_attr($default_settings['after_widget']); ?>" onclick="if (this.value == '<?php echo esc_js(esc_attr($default_settings['after_widget'])); ?>') this.value = '';" /></td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><?php _e('Before title', 'simple-sidebars'); ?></th>
							<td><input class="regular-text" type="text" name="nsidebar[before_title]" value="<?php echo esc_attr($default_settings['before_title']); ?>" onclick="if (this.value == '<?php echo esc_js(esc_attr($default_settings['before_title'])); ?>') this.value = '';" /></td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><?php _e('After title', 'simple-sidebars'); ?></th>
							<td><input class="regular-text" type="text" name="nsidebar[after_title]" value="<?php echo esc_attr($default_settings['after_title']); ?>" onclick="if (this.value == '<?php echo esc_js(esc_attr($default_settings['after_title'])); ?>') this.value = '';" /></td>
						</tr>
					</table>
					
					<p class="submit">
						<?php wp_nonce_field( 'add-csidebars-settings' ); ?>
						<input class="button-primary" name="add-csidebars" type="submit" value="<?php _e('Add sidebar', 'simple-sidebars'); ?>" />
					</p>
				</form>
			</div>
			
			<div id="simple-sidebar-settings" class="group">
				<h3><?php _e('Default sidebar settings', 'simple-sidebars'); ?></h3>
				
				<form method="post" action="<?php echo $this->admin_url; ?>">
					<table class="form-table">
						<tr class="form-field form-required">
							<th scope="row"><?php _e('Before widget', 'simple-sidebars'); ?></th>
							<td><input class="regular-text" type="text" name="dsidebar[before_widget]" value="<?php echo esc_attr($default_settings['before_widget']); ?>" /></td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><?php _e('After widget', 'simple-sidebars'); ?></th>
							<td><input class="regular-text" type="text" name="dsidebar[after_widget]" value="<?php echo esc_attr($default_settings['after_widget']); ?>" /></td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><?php _e('Before title', 'simple-sidebars'); ?></th>
							<td><input class="regular-text" type="text" name="dsidebar[before_title]" value="<?php echo esc_attr($default_settings['before_title']); ?>" /></td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><?php _e('After title', 'simple-sidebars'); ?></th>
							<td><input class="regular-text" type="text" name="dsidebar[after_title]" value="<?php echo esc_attr($default_settings['after_title']); ?>" /></td>
						</tr>
					</table>
					
					<p class="submit">
						<?php wp_nonce_field( 'save-default-sidebars-settings' ); ?>
						<input class="button-primary" name="save-default-sidebars-settings" type="submit" value="<?php _e('Save', 'simple-sidebars'); ?>" />
					</p>
				</form>
			</div>
			
			<div id="simple-sidebar-theme" class="group">
				<form action="<?php echo $this->admin_url; ?>" method="post">
					<?php if ( is_array($wp_registered_sidebars) && !empty($wp_registered_sidebars) ) : ?>
						<table class="widefat">
							<thead>
								<tr>
									<th scope="col"><?php _e('ID', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('Name', 'simple-sidebars'); ?></th>
									<th class="manage-column"><?php _e('Hide ?', 'simple-taxonomy'); ?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th scope="col"><?php _e('ID', 'simple-sidebars'); ?></th>
									<th scope="col"><?php _e('Name', 'simple-sidebars'); ?></th>
									<th class="manage-column"><?php _e('Hide ?', 'simple-taxonomy'); ?></th>
								</tr>
							</tfoot>
							<tbody id="the-list" class="list:sidebars">
								<?php
								$class = 'alternate';
								$i = 0;
								foreach ( (array) $wp_registered_sidebars as $sidebar ) :
									if ( isset($current_sidebars[$sidebar['id']]) ) {
										continue;
									}
									$i++;
									$class = ( $class == 'alternate' ) ? '' : 'alternate';
									?>
									<tr id="custom-sidebar-<?php echo $i; ?>" class="<?php echo $class; ?>">
										<td class="name column-name"><?php echo stripslashes($sidebar['id']); ?></td>
										<td class="name column-name"><?php echo stripslashes($sidebar['name']); ?></td>
										<td style="text-align:left;"><input name="hide_sidebar[]" <?php checked( in_array(stripslashes($sidebar['id']), $current_sidebars_hide), true); ?> type="checkbox" value="<?php echo stripslashes($sidebar['id']); ?>" /></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<p class="submit">
							<?php wp_nonce_field( 'save-csidebars-hidden' ); ?>
							<input class="button-primary" name="save-csidebars-hide" type="submit" value="<?php _e('Save hidden sidebars', 'simple-sidebars'); ?>" />
						</p>
					</form>
				<?php endif;?>
			</div>
		</div>
		
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				// Switches tabs sections
				$('.group').hide();
				$('.group:first').fadeIn();
				$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
				$('.nav-tab-wrapper a').click(function(evt) {
					$('.nav-tab-wrapper a').removeClass('nav-tab-active');
					$(this).addClass('nav-tab-active').blur();
					$('.group').hide();
					$($(this).attr('href')).fadeIn();
					evt.preventDefault();
				});
			});
		</script>
		<?php
		return true;
	}
	
	/**
	 * Check $_POST datas for relations liaisons
	 * 
	 * @return boolean
	 */
	function checkSidebars() {
		if ( isset($_POST['save-default-sidebars-settings']) && $_POST['dsidebar'] ) {
			
			check_admin_referer( 'save-default-sidebars-settings' );
			
			$this->message = __('Default sidebars settings updated with success !', 'simple-sidebars');
			update_option( SS_OPTION . 'default' , $this->stripslashes_deep($_POST['dsidebar']) );
			
		} elseif( isset($_POST['save-csidebars-hide']) ) {
				
			check_admin_referer( 'save-csidebars-hidden' );
			
			$this->message = __('Hidden sidebars settings updated with success !', 'simple-sidebars');
			update_option( SS_OPTION . 'hide' , $this->stripslashes_deep($_POST['hide_sidebar']) );
			
		} elseif ( isset($_POST['save-csidebars']) ) {
			
			check_admin_referer( 'save-csidebars-settings' );
			
			// Update array
			$current_sidebars = array();
			foreach( (array) $_POST['sidebar'] as $key => $sidebar ) {
				// Delete taxo ?
				if ( !isset( $sidebar['delete'] ) ) {;
					$current_sidebars[$key] = $sidebar;
				}
			}
			
			$this->message = __('Custom sidebars updated with success !', 'simple-sidebars');
			update_option( SS_OPTION, $current_sidebars );
			
		} elseif ( isset($_POST['add-csidebars']) && !empty($_POST['nsidebar']['id']) ) {
			
			check_admin_referer( 'add-csidebars-settings' );
			
			$current_sidebars = get_option( SS_OPTION );
			
			// Make unique ID ?
			$_POST['nsidebar']['id'] = sanitize_title($_POST['nsidebar']['id']);
			if ( isset($current_sidebars[$_POST['nsidebar']['id']]) ) {
				$num = 2;
				do {
					$alt_key = $_POST['nsidebar']['id'] . "-$num";
					$num++;
					$key_check = isset($current_sidebars[$alt_key]);
				} while ( $key_check );
				$_POST['nsidebar']['id'] = $alt_key;
			}
			
			$current_sidebars[$_POST['nsidebar']['id']] = $_POST['nsidebar'];
			
			$this->message = __('Custom sidebars added with success !', 'simple-sidebars');
			update_option( SS_OPTION, $current_sidebars );
			
		}
		
		return false;
	}

	/**
	 * Stripslashes on array
	 */
	function stripslashes_deep($value) {
		$value = is_array($value) ? array_map(array(&$this, 'stripslashes_deep'), $value) : stripslashes($value);
		return $value;
	}
	
	/**
	 * Display WP alert
	 *
	 */
	function displayMessage() {
		if ( $this->message != '') {
			$message = $this->message;
			$status = $this->status;
			$this->message = $this->status = ''; // Reset
		}
		
		if ( isset($message) && !empty($message) ) {
		?>
			<div id="message" class="<?php echo ($status != '') ? $status :'updated'; ?> fade">
				<p><strong><?php echo $message; ?></strong></p>
			</div>
		<?php
		}
	}
}
?>