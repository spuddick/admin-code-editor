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

		wp_enqueue_style( $this->admin_code_editor, plugin_dir_url( __FILE__ ) . 'css/admin-code-editor-admin.css', array(), $this->version, 'all' );
		wp_register_style( 'bootstrap-style', plugins_url( 'css/bootstrap.css', __FILE__ ), null, filemtime(plugin_dir_path( __FILE__ ) .'css/bootstrap.css')  );
	 	wp_enqueue_style( 'bootstrap-style' );

		wp_register_style( 'bootstrap-theme-style', plugins_url( 'css/bootstrap-theme.css', __FILE__ ), null, filemtime(plugin_dir_path( __FILE__ ) .'css/bootstrap-theme.css')  );
	 	wp_enqueue_style( 'bootstrap-theme-style' );

		wp_register_style( 'highlight-admin-style', plugins_url( 'liberal-admin-code-highlight.css', __FILE__ ), null, filemtime(plugin_dir_path( __FILE__ ) .'/liberal-admin-code-highlight.css')  );
	 	wp_enqueue_style( 'highlight-admin-style' );

		wp_register_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
	 	wp_enqueue_style( 'font-awesome' );	 	
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

		wp_enqueue_script( $this->admin_code_editor, plugin_dir_url( __FILE__ ) . 'js/admin-code-editor-admin.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'ace-highlight-src', plugins_url( 'ace-builds/src-min/ace.js', __FILE__ ), array('jquery') );
	 	wp_enqueue_script( 'ace-highlight-src' );
		wp_register_script( 'ace-highlight-admin', plugins_url( 'admin-code-highlight.js', __FILE__ ), array('ace-highlight-src'), filemtime(plugin_dir_path( __FILE__ ) .'/admin-code-highlight.js') );
	 	wp_enqueue_script( 'ace-highlight-admin' );
		wp_register_script( 'bootstrap-script', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js' );
	 	wp_enqueue_script( 'bootstrap-script' );
	}


	function code_editor_add_meta_box() {
		// add the metabox to posts and pages
		
		$screens = array( 'post', 'page', 'wwsf-commitment', 'home-page-feature', 'home-page-slider', 'con-policy' );
		
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
		wp_nonce_field( 'code_highlight_box', 'code_highlight_box_nonce' );

		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		
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
		
		/**
		*
		* We want to have the option to use the english CSS and javascript on both the english and french.
		* In many cases, it will be the same for both languages and will make maintenance easier and prevent inconsistencies between the languages.
		* Some checks are needed first to determine what message should be displayed.
		*
		**/
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
				
				require_once plugin_dir_path( __FILE__ )."scssphp/scss.inc.php";
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

}
