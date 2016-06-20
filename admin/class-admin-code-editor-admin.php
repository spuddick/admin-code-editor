<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/admin
 * @author     Your Name <email@example.com>
 */
class Admin_Code_Editor_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $admin_code_editor    The ID of this plugin.
	 */
	private $admin_code_editor;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $admin_code_editor       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $admin_code_editor, $version ) {

		$this->admin_code_editor = $admin_code_editor;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Admin_Code_Editor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Admin_Code_Editor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->admin_code_editor, 
			plugin_dir_url( __FILE__ ) . 'css/admin-code-editor-admin.css', 
			array(), 
			filemtime(plugin_dir_path( __FILE__ ) . 'css/admin-code-editor-admin.css'), 
			'all' 
		);
		
		wp_enqueue_style( 
			'wp-ace-bootstrap', 
			plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', 
			array(), 
			filemtime(plugin_dir_path( __FILE__ ) . 'css/bootstrap.css'), 
			'all' 
		);
		
		wp_enqueue_style( 
			'wp-ace-bootstrap-theme', 
			plugin_dir_url( __FILE__ ) . 'css/bootstrap-theme.css', 
			array('wp-ace-bootstrap'), 
			filemtime(plugin_dir_path( __FILE__ ) . 'css/bootstrap-theme.css'), 
			'all' 
		);
		
		wp_enqueue_style( 
			'wp-ace-font-awesome',
			'//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', 
			array(), 
			'4.6.3', 
			'all' 
		);
	
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Admin_Code_Editor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Admin_Code_Editor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 
			'wp-ace-editor-js', 
			plugins_url( 'ace-builds/src-min/ace.js', __FILE__ ), 
			array('jquery'), 
			filemtime(plugin_dir_path( __FILE__ ) . 'ace-builds/src-min/ace.js')
		);
		
		wp_enqueue_script( 
			'wp-ace-bootstrap-js',
			'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js' , 
			array('jquery'), 
			'3.3.6'
		);

		wp_enqueue_script( 
			$this->admin_code_editor, 
			plugin_dir_url( __FILE__ ) . 'js/admin-code-editor-admin.js', 
			array( 'jquery', 'wp-ace-bootstrap-js', 'wp-ace-editor-js' ), 
			filemtime(plugin_dir_path( __FILE__ ) . 'js/admin-code-editor-admin.js')
		);
		

	}


	function code_editor_add_meta_box() {
		// add the metabox to posts and pages
		
		// $screens = array( 'post', 'page', 'wwsf-commitment', 'home-page-feature', 'home-page-slider', 'con-policy' );
		// need to iterate through specified post types from settings page

		foreach ( $screens as $screen ) {

			add_meta_box(
				'code_box',
				__( 'Inline Code (HTML, SCSS (CSS), Javascript)', 'liberal-2015' ),
				array(&$this,'code_editor_section_callback'),
				$screen, 
				'normal',
				'high'
			);
	       
		}

	}


	/**
	 * Prints the box content.
	 * 
	 * @param WP_Post $post The object for the current post/page.
	 */
	function code_editor_section_callback( $post ) {

		// Add a nonce field so we can check for it later.
		// wp_nonce_field( 'code_highlight_box', 'code_highlight_box_nonce' );

		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		
		/*
		$code_insert_mode = get_post_meta( $post->ID, '_code_insert_mode', true );
		if (empty($code_insert_mode)) {
			$code_insert_mode = 'append_bottom';
		}

		switch ($code_insert_mode) {
	    case 'append_bottom':
        $html_class_output = '';
        $html_header_footer_class_output = 'hidden';
        break;
	    case "header_and_footer":
        $html_class_output = 'hidden';
        $html_header_footer_class_output = '';
        break;
		}
		 

		$html_code 		= get_post_meta( $post->ID, '_html_code', true );
		$html_header_code 		= get_post_meta( $post->ID, '_html_header_code', true );
		$html_footer_code 		= get_post_meta( $post->ID, '_html_footer_code', true );
		$css_code 		= get_post_meta( $post->ID, '_css_code', true ); // This is actually SASS code. We are keeping the same variable name.
		$js_code 			= get_post_meta( $post->ID, '_js_code', true );


		$html_height 	= get_post_meta( $post->ID, '_html_field_height', true );
		$html_header_height 	= get_post_meta( $post->ID, '_html_header_field_height', true );
		$html_footer_height 	= get_post_meta( $post->ID, '_html_footer_field_height', true );
		$css_height 	= get_post_meta( $post->ID, '_css_field_height', true );
		$js_height 		= get_post_meta( $post->ID, '_js_field_height', true );

		$css_compile_error =  get_post_meta( $post->ID, '_compiled_css_error_msg', true);

		*/
		/**
		*
		* We want to have the option to use the english CSS and javascript on both the english and french.
		* In many cases, it will be the same for both languages and will make maintenance easier and prevent inconsistencies between the languages.
		* Some checks are needed first to determine what message should be displayed.
		*
		**/
		
		/*
		if (ICL_LANGUAGE_CODE == 'fr') {
			$french_post_id = $post->ID;
		} else {
			$french_post_id = icl_object_id($post->ID, get_post_type( $post->ID ), false,'fr');
		}
		
		if ($french_post_id) {
			$french_js_code 			= get_post_meta( $french_post_id, '_js_code', true );
			$french_css_code 			= get_post_meta( $french_post_id, '_css_code', true );

			if (empty($french_js_code )) {
				$french_js_status = 'No French javascript detected. Using English javascript on French front end display.';
			} else {
				$french_js_status = 'French javascript detected. Using French javascript on French front end display. Leave blank to use English javascript.';
			}
			if (empty($french_css_code )) {
				$french_css_status = 'No French CSS detected. Using English CSS on French front end display.';
			} else {
				$french_css_status = 'French CSS detected. Using French CSS on French front end display. Leave blank to use English CSS.';
			}
		} else {
			$french_js_status = 'French page has not been created yet.';
			$french_css_status = 'French page has not been created yet.';

		}

		if (empty($html_height)) {
			$html_height = 500;
		}
		if (empty($html_header_height)) {
			$html_header_height = 500;
		}
		if (empty($html_footer_height)) {
			$html_footer_height = 500;
		}
		if (empty($css_height)) {
			$css_height = 500;
		}
		if (empty($js_height)) {
			$js_height = 500;
		}
		*/
	
		require_once('partials/admin-code-editor-admin.php');
	
	}	



	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	function code_editor_save( $post_id ) {

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['code_highlight_box_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['code_highlight_box_nonce'], 'code_highlight_box' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		/* OK, it's safe for us to save the data now. */
		$code_data = array(
			'html' 	=> array( 
				'post-field' => 'html-field', 
				'data-field' => '_html_code',
				'height-data' => '_html_field_height',
				'height-field' => 'html-field-height'),
			'html-header' 	=> array( 
				'post-field' => 'html-header-field', 
				'data-field' => '_html_header_code',
				'height-data' => '_html_header_field_height',
				'height-field' => 'html-header-field-height'),
			'html-footer' 	=> array( 
				'post-field' => 'html-footer-field', 
				'data-field' => '_html_footer_code',
				'height-data' => '_html_footer_field_height',
				'height-field' => 'html-footer-field-height'),
			'css' 	=> array( 
				'post-field' => 'css-field', 
				'data-field' => '_css_code',
				'height-data' => '_css_field_height',
				'height-field' => 'css-field-height'),
			'js' 	=> array( 
				'post-field' => 'js-field', 
				'data-field' => '_js_code',
				'height-data' => '_js_field_height',
				'height-field' => 'js-field-height'
			)
		);


		// Make sure that it is set.
		if ((! isset( $_POST['html-field'])) || (! isset( $_POST['css-field'])) || (! isset( $_POST['js-field'])) ) {
			return;
		}

		update_post_meta( $post_id, '_code_insert_mode', $_POST['editor_mode']);



		$count = 0;
		foreach ($code_data as &$code_data_item) {
	    if ($code_data_item['post-field'] == 'css-field') {
		    // if it is the CSS field we need to handle things differently due to parsing of SCSS to CSS
	    	//echo 'post revision?: ' . wp_is_post_revision( $post_id);
		    
	    	//echo 'post id: ' . $post_id;
		    //echo 'post set? : ' . isset($_POST[$code_data_item['post-field']]);
		    $code 	= $_POST[$code_data_item['post-field']];
		  	//echo 'before compile: ' . $code;
		    $this->compile_sass($post_id, $code);
				//echo 'after compile: ' . $code;
	    } else {
		    $code 	= $_POST[$code_data_item['post-field']];
		    $result = update_post_meta( $post_id, $code_data_item['data-field'], $code  );	    	
	    }


	    $height 	= $_POST[$code_data_item['height-field']];
	    update_post_meta( $post_id, $code_data_item['height-data'], $height  );
	    $count++;
		}

		
		if ( $parent_id = wp_is_post_revision( $post_id ) ) {
			$parent  = get_post( $parent_id );
			foreach ($code_data as &$code_data_item) {
		    $code = get_post_meta( $parent->ID, $code_data_item['data-field'], true );
		    
		    if ( false !== $code ) {
		    	add_metadata( 'post', $post_id, $code_data_item['data-field'], $code );
		    }       	
			}
	  }
		
	}

	function compile_sass($post_id, $code) {
		$trimmed_code = trim($code);
		//echo 'trimmed code: ' . $trimmed_code;

		if ( empty($trimmed_code) ) {
			delete_post_meta( $post_id, '_compiled_css_error_msg');
			delete_post_meta( $post_id, '_compiled_css');
			delete_post_meta( $post_id, '_css_code');	
			//echo 'deleting compiled css data' . "\r\n";
			return false;
		} else {
			try {
				
				//require_once plugin_dir_path( __FILE__ )."scssphp/scss.inc.php";
				//echo 'after require';
				$scss = new scssc();
				//echo 'before update' . "\r\n";
				update_post_meta( $post_id, '_css_code', $code  );	
				//echo 'before compiling' . "\r\n";
				$compiled_css = $scss->compile($code);
				//echo 'after compiling' . "\r\n";
	  		update_post_meta( $post_id, '_compiled_css', trim($compiled_css)  );
	  		delete_post_meta( $post_id, '_compiled_css_error_msg');
	  		//echo 'end' . "\r\n";
	  		return trim($compiled_css);
			}
			catch(Exception $e) {
			  echo 'in catch';
			  update_post_meta( $post_id, '_compiled_css_error_msg', $e->getMessage() );

			}			
		}

	}


  /**
   * Create the Notes post type
   * 
   * @since 0.1.0
   */
    function wp_ace_post_type_init() {

      $labels = array(
      'name'               => _x( 'Notes', 'post type general name', 'wp-notes-widget' ),
      'singular_name'      => _x( 'Note', 'post type singular name', 'wp-notes-widget' ),
      'menu_name'          => _x( 'Notes', 'admin menu', 'wp-notes-widget' ),
      'name_admin_bar'     => _x( 'Note', 'add new on admin bar', 'wp-notes-widget' ),
      'add_new'            => _x( 'Add New', 'nw-item', 'wp-notes-widget' ),
      'add_new_item'       => __( 'Add New Note', 'wp-notes-widget' ),
      'new_item'           => __( 'New Note', 'wp-notes-widget' ),
      'edit_item'          => __( 'Edit Note', 'wp-notes-widget' ),
      'view_item'          => __( 'View Note', 'wp-notes-widget' ),
      'all_items'          => __( 'All Notes', 'wp-notes-widget' ),
      'search_items'       => __( 'Search Notes', 'wp-notes-widget' ),
      'parent_item_colon'  => __( 'Parent Notes:', 'wp-notes-widget' ),
      'not_found'          => __( 'No notes found.', 'wp-notes-widget' ),
      'not_found_in_trash' => __( 'No notes found in Trash.', 'wp-notes-widget' )
    );

    $args = array(
      'labels'                => $labels,
      'public'                => false,
      'publicly_queryable'    => false,
      'exclude_from_search'   => true,
      'show_ui'               => true, 
      'show_in_menu'          => true, 
      'query_var'             => true,
      'rewrite'               => false,
      'capability_type'       => 'post',
      'has_archive'           => false, 
      'hierarchical'          => false,
      'menu_position'         => null,
      'supports'              => array('title','page-attributes')
    ); 

    register_post_type('nw-item',$args);

  } // end notes_post_type_init


	/**
	 *
	 * Add options page for Custom Ratings Settings
	 *
	 * @since 1.0.0 
	 */
	function options_menu() {
		add_options_page( 
			'Custom Rating Settings',
			'Custom Ratings',
			'manage_options',
			'custom-ratings-options-page',
			array(&$this,'custom_ratings_settings_page')
		);
	}


	/**
	 *
	 * Custom Ratings Settings Page Callback
	 *
	 * @since 1.0.0 
	 */
	function custom_ratings_settings_page() {
		?>
			<div class="wrap">
				<h1><?php _e('Custom Ratings Settings', 'custom-ratings'); ?></h1>
				<form method="post" action="options.php">
					<?php
						do_settings_sections("custom-ratings-options-page"); 
						settings_fields("custom-ratings-settings");
						     
						submit_button(); 
					?>          
				</form>
			</div>
		<?php
	}


	/**
	 *
	 * Option field for 'report text'. This contains placeholder values for the number of votes and rating average.
	 *
	 * @since 1.0.0 
	 */
	function display_report_text_field_element() {
		?>
			
			<input type="text" name="wpcr_report_text" id="wpcr_report_text" class="wpcr__text" value="<?php echo get_option('wpcr_report_text'); ?>" placeholder="<?php _e('%TOTAL_VOTES% votes with an average of %AVG%.', 'custom-ratings') ?>" />
			<p><small><?php _e('Use the placeholders of %AVG% and %TOTAL_VOTES% in your text.', 'custom-ratings') ?></small></p>
		<?php
	}


	/**
	 *
	 * Option field to choose which post types 'custom ratings' are applied to.
	 *
	 * @since 1.0.0
	 */
	function display_post_type_choice_element() {
		
		$args = array(
			'public'   => true
		);

		$post_types 					= get_post_types( $args, 'objects' ); 
		$selected_post_types 	= get_option('wpcr_post_types');
		
		$is_selected_post_type = function($post_type_name) use ($selected_post_types) {
			if (!empty($selected_post_types) && in_array($post_type_name, $selected_post_types) ) {
				return ' selected ';
			}
		};
		print '<p><small>' . __('To select multiple post types hold down CTRL (windows) or CMD (mac) while clicking.', 'custom-ratings') . '</small></p>';
		print '<select id="wpcr_post_types" name="wpcr_post_types[]" multiple >';
		foreach ( $post_types as $post_type ) {
			echo '<option value="'. $post_type->name .'" '. $is_selected_post_type($post_type->name) .' >' . $post_type->labels->name . '</option>';
		}
		print '</select>';

	}


	function display_theme_panel_fields() {
		
		// Set up subsections for settings page
		add_settings_section("general-section", "General Settings", null, "custom-ratings-options-page");
		add_settings_section("text-section", 		"Text Settings", 		null, "custom-ratings-options-page");
		
		// General settings fields
		add_settings_field("wpcr_star_type", 								__('Choose Your Star Type', 'custom-rating'), 																			array(&$this,"display_star_type_field_element"), 					"custom-ratings-options-page", "general-section");		
		add_settings_field("wpcr_image_upload_id", 					__('Upload an image (select "custom" above)', 'custom-rating'), 										array(&$this,"display_image_upload_field_element"), 			"custom-ratings-options-page", "general-section");
		add_settings_field("wpcr_post_types", 							__('What post types should custom ratings be applied to?', 'custom-rating'), 				array(&$this,"display_post_type_choice_element"), 				"custom-ratings-options-page", "general-section");
		add_settings_field("wpcr_excerpt_output_type", 			__('Rating Tally Display Position', 'custom-rating'), 															array(&$this,"display_excerpt_output_field_element"), 		"custom-ratings-options-page", "general-section");
		add_settings_field("wpcr_content_output_type", 			__('Vote Display Position', 'custom-rating'), 																			array(&$this,"display_content_output_field_element"), 		"custom-ratings-options-page", "general-section");
		add_settings_field("wpcr_spectrum_color", 					__('Select Color', 'custom-rating'), 																								array(&$this,"display_color_selector_field_element"), 		"custom-ratings-options-page", "general-section");
		add_settings_field("wpcr_show_top_border", 					__('Display Top Border', 'custom-rating'), 																					array(&$this,"display_top_border_field_element"), 				"custom-ratings-options-page", "general-section");
		add_settings_field("wpcr_show_bottom_border", 			__('Display Buttom Border', 'custom-rating'), 																			array(&$this,"display_bottom_border_field_element"), 			"custom-ratings-options-page", "general-section");
		add_settings_field("wpcr_only_on_main_query", 			__('Main Query', 'custom-rating'), 																			array(&$this,"display_main_query_field_element"), 			"custom-ratings-options-page", "general-section");
		add_settings_field("wpcr_ajax_get_caching_time", 		__('Caching', 'custom-rating'), 																										array(&$this,"display_ajax_get_caching_field_element"), 			"custom-ratings-options-page", "general-section");
		add_settings_field("wpcr_use_own_css", 							__('CSS', 'custom-rating'), 																												array(&$this,"display_use_own_css_field_element"), 				"custom-ratings-options-page", "general-section");
		add_settings_field("wpcr_hide_on_front_page", 			__('Front Page', 'custom-rating'), 																									array(&$this,"display_hide_front_page_field_element"), 		"custom-ratings-options-page", "general-section");
		
		
		// Text settings fields
		add_settings_field("wpcr_intro_text", 							__('Intro Text', 'custom-rating'), 																									array(&$this,"display_intro_text_field_element"), 				"custom-ratings-options-page", "text-section");
		add_settings_field("wpcr_loading_text", 						__('Loading Text', 'custom-rating'), 																								array(&$this,"display_loading_text_field_element"), 			"custom-ratings-options-page", "text-section");
		add_settings_field("wpcr_error_text", 							__('Add Vote Error Text', 'custom-rating'), 																				array(&$this,"display_error_text_field_element"), 				"custom-ratings-options-page", "text-section");
		add_settings_field("wpcr_first_vote_text", 					__('First Vote Text', 'custom-rating'), 																						array(&$this,"display_first_vote_text_field_element"),		"custom-ratings-options-page", "text-section");
		add_settings_field("wpcr_adding_vote_text", 				__('Adding Vote Text', 'custom-rating'), 																						array(&$this,"display_adding_vote_text_field_element"), 	"custom-ratings-options-page", "text-section");
		add_settings_field("wpcr_thank_you_text", 					__('Thank You Text', 'custom-rating'), 																							array(&$this,"display_thank_you_text_field_element"), 		"custom-ratings-options-page", "text-section");
		add_settings_field("wpcr_report_text", 							__('Rating Report Text', 'custom-rating'), 																					array(&$this,"display_report_text_field_element"), 				"custom-ratings-options-page", "text-section");
		
		// Register general settings
		register_setting("custom-ratings-settings", "wpcr_star_type");
		register_setting("custom-ratings-settings", "wpcr_post_types");
		register_setting("custom-ratings-settings", "wpcr_excerpt_output_type");
		register_setting("custom-ratings-settings", "wpcr_content_output_type");
		register_setting("custom-ratings-settings", "wpcr_image_upload_id");
		register_setting("custom-ratings-settings", "wpcr_color");
		register_setting("custom-ratings-settings", "wpcr_top_border");
		register_setting("custom-ratings-settings", "wpcr_bottom_border");
		register_setting("custom-ratings-settings", "wpcr_only_on_main_query");
		register_setting("custom-ratings-settings", "wpcr_ajax_get_caching_time");
		register_setting("custom-ratings-settings", "wpcr_use_own_css");
		register_setting("custom-ratings-settings", "wpcr_hide_on_front_page");

		// Register text settings
		register_setting("custom-ratings-settings", "wpcr_intro_text");
		register_setting("custom-ratings-settings", "wpcr_loading_text");
		register_setting("custom-ratings-settings", "wpcr_error_text");
		register_setting("custom-ratings-settings", "wpcr_first_vote_text");
		register_setting("custom-ratings-settings", "wpcr_adding_vote_text");
		register_setting("custom-ratings-settings", "wpcr_thank_you_text");
		register_setting("custom-ratings-settings", "wpcr_report_text");
		
	}

}
