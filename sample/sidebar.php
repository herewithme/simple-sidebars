<div id="primary" class="widget-area" role="complementary">
	<ul class="xoxo">
		<?php
		if ( have_custom_sidebar() ) {
			dynamic_sidebar( get_custom_sidebar_id() );
		} else {
			dynamic_sidebar( 'primary-widget-area' );
		}
		?>
	</ul>
</div><!-- #primary .widget-area -->