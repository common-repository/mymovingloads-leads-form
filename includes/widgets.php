<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');
// Register and load the widget
function mml_load_form_widget() {
	register_widget( 'mml_leadform_widget' );
}
add_action( 'widgets_init', 'mml_load_form_widget' );

// Creating the widget 
class mml_leadform_widget extends WP_Widget {

	function __construct() {
		parent::__construct(

			// Base ID of your widget
			'mml_leadform_widget', 

			// Widget name will appear in UI
			__('MoveAdvisor Lead Form', 'mml_leadform_widget_domain'),

			// Widget description
			array(
				'description' => __( 'MoveAdvisor Form', 'mml_leadform_widget_domain' ),
				'class'		=> __( 'mml_leadform-widget', 'mml_leadform_widget_domain' ),
			) 
		);
	}

	// Creating widget front-end

	public function widget( $args, $instance ) {
		$title = htmlspecialchars(get_option('mml_leadform_option_formtitle'));

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		// This is where you run the code and display the output
		echo do_shortcode('[mml_leadform widget="true"]');
		echo $args['after_widget'];
	}

	// Widget Backend 
	public function form( $instance ) {
		// Widget admin form
		?>
		<p>This widget loads the MoveAdvisor Form in your sidebar. If you want to change anything from color scheme to the title you will have to do it from the plugin <a href="<?php echo esc_url( admin_url( 'admin.php?page=mml_leadform_dashboard' ) ); ?>"> <?php echo __( 'Settings Page', MML_DOMAIN ); ?></a>.</p>
		<?php 
	}


} // Class mml_leadform_widget ends here

?>