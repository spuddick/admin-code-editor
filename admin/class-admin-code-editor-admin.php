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
			plugin_dir_url( __FILE__ ) . 'css/wp-ace-bootstrap.css', 
			array(), 
			filemtime(plugin_dir_path( __FILE__ ) . 'css/wp-ace-bootstrap.css'), 
			'all' 
		);
		
		wp_enqueue_style( 
			'wp-ace-bootstrap-theme', 
			plugin_dir_url( __FILE__ ) . 'css/wp-ace-bootstrap-theme.css', 
			array('wp-ace-bootstrap'), 
			filemtime(plugin_dir_path( __FILE__ ) . 'css/wp-ace-bootstrap-theme.css'), 
			'all' 
		);
		
		wp_enqueue_style( 
			'wp-ace-font-awesome',
			'//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', 
			array(), 
			'4.6.3', 
			'all' 
		);

		wp_enqueue_style( 
			'wp-ace-jquery-ui',
			'//code.jquery.com/ui/1.11.4/themes/black-tie/jquery-ui.css', 
			array(), 
			'1.11.4', 
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
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-resizable' );
		wp_enqueue_script( 
			'wp-ace-editor-js', 
			plugin_dir_url( __FILE__ ) . 'js/ace-src-min-noconflict/ace.js', 
			array('jquery'), 
			filemtime(plugin_dir_path( __FILE__ ) . 'js/ace-src-min-noconflict/ace.js')
		);
		
	
		wp_enqueue_script( 
			'wp-ace-bootstrap-js',
			plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js' , 
			array('jquery'), 
			filemtime(plugin_dir_path( __FILE__ ) . 'js/bootstrap.min.js')
		);

		wp_enqueue_script( 
			$this->admin_code_editor, 
			plugin_dir_url( __FILE__ ) . 'js/admin-code-editor-admin.js', 
			array( 'jquery', 'wp-ace-bootstrap-js', 'wp-ace-editor-js', 'jquery-ui-resizable' ), 
			filemtime(plugin_dir_path( __FILE__ ) . 'js/admin-code-editor-admin.js')
		);
		
	}


	function code_editor_add_meta_box() {
		// add the metabox to posts and pages
		
		 $screens = array( 'post', 'page');
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
	
		//$html_code 		= get_post_meta( $post->ID, '_html_code', true );
		wp_nonce_field( 'wp-ace-editor-nonce', 'wp-ace-editor-nonce' );
		
		$html_php_pre_code_editor_height 	= get_post_meta($post->ID, '_wp_ace_html_php_editor_height');
		$html_php_code_post_id 						= get_post_meta($post->ID, '_wp_ace_html_php_code_post_id');
		$html_php_pre_code 								= '';
		if (!$html_php_pre_code_editor_height) {
			$html_php_pre_code_editor_height = 400;
		}
		if ($html_php_code_post_id) {
			$html_php_post_obj 	= get_post( $html_php_code_post_id ); 
			$content 						= $html_php_post_obj->post_content;
			$html_php_pre_code 	= $content;
		}
		require_once('partials/admin-code-editor-admin-post-edit.php');
	
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
		if ( ! isset( $_POST['wp-ace-editor-nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['wp-ace-editor-nonce'], 'wp-ace-editor-nonce' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}


		$wp_ace_code_content_types = array("wp-ace-html", "wp-ace-css", "wp-ace-js");
		$post_type = get_post_type( $post_id );
		if ( in_array(get_post_type( $post_id ), $wp_ace_code_content_types) ) {
			// since wp_insert_post also calls code_editor_save, we need to check if this is a code content type and exit. 
			// An infinite loop will occur otherwise. 
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

		// TODO: Check if post type is WP ACE enabled
		
		$html_php_pre 										= $_POST['wp-ace-html-php-pre-code']; // TODO: suitable filter for html content
		$html_php_editor_height						= sanitize_text_field($_POST['wp-ace-html-php-field-height']);
		$html_php_preprocessor						= sanitize_text_field($_POST['wp-ace-html-php-preprocessor']);
		$html_php_editor_cursor_position 	= 0;
		$html_php_editor_has_focus 				= 0;

		$incoming_html_php_hash = md5($html_php_pre . $html_php_editor_height . $html_php_preprocessor . $html_php_editor_cursor_position . $html_php_editor_has_focus);
		$cur_html_php_hash = get_post_meta($post_id, '_wp_ace_html_php_hash');

		if ($incoming_html_php_hash != $cur_html_php_hash) {
			// Hashes don't match so settings or code has changed. We need to update.
			
			$html_php_code_post_id = get_post_meta($post_id, '_wp_ace_html_php_code_post_id');
			

			// get the appropriate post name text depending on whether this is the initial post or a revision
			$parent_id = wp_is_post_revision( $post_id );
			if (!$parent_id) {
				$parent_id = $post_id;
				$post_name_text = 'wp-ace-html-and-php-code-for-' . $parent_id;
			} else {
				$post_name_text = 'wp-ace-html-and-php-code-for-' . $parent_id . '--rev-' . $post_id;
			}
			

			if (!$html_php_code_post_id) {
				// if no existing post for HTML code, create one

				$html_php_code_post = array(
					  'post_name'    	=> $post_name_text, 
					  'post_content'  => $html_php_pre,
					  'post_status'   => 'publish',
					  'post_type'			=> 'wp-ace-html'
					);
 
				$html_php_code_post_id = wp_insert_post( $html_php_code_post );

				update_post_meta($post_id, '_wp_ace_html_php_code_post_id', $html_php_code_post_id);
			} else {
				
				// if an existing post for HTML exists, update it
			  $html_php_code_post_settings = array(
		      'ID'           	=> $html_php_code_post_id,
		      'post_name'    	=> $post_name_text,
				  'post_content'  => $html_php_pre,
				  'post_status'   => 'publish',
				  'post_type'			=> 'wp-ace-html'
			  );
				
				$html_php_code_post_id = wp_update_post( $html_php_code_post_settings, true );						  
				if (is_wp_error($html_php_code_post_id)) {
					$errors = $html_php_code_post_id->get_error_messages();
					foreach ($errors as $error) {
						echo $error;
					}
				}
			}

			// update the current hash with the new one
			update_post_meta($post_id, '_wp_ace_html_php_hash', $incoming_html_php_hash);

			// compile pre code and save it as meta data for the associated code post
			$compiled_html_php = $this->compile($html_php_pre, $html_php_preprocessor); // TODO: Write compile function with return vals
			update_post_meta($html_php_code_post_id, '_wp_ace_html_php_status', $compiled_html_php->status );
			
			// update compile error status and message
			if ($compiled_html_php->status != 'error') {
				update_post_meta($html_php_code_post_id, '_wp_ace_html_php_compiled', $compiled_html_php->compiled_code );
				delete_post_meta($html_php_code_post_id, '_wp_ace_html_php_error_msg');
			} else {
				update_post_meta($html_php_code_post_id, '_wp_ace_html_php_error_msg', $compiled_html_php->error_msg );
			}

			// update other basic meta data
			update_post_meta($html_php_code_post_id, '_wp_ace_html_php_editor_height', $html_php_editor_height );
			update_post_meta($html_php_code_post_id, '_wp_ace_html_php_preprocessor', $html_php_preprocessor );
			update_post_meta($html_php_code_post_id, '_wp_ace_html_php_insertion_pos', $html_php_editor_cursor_position );
			update_post_meta($html_php_code_post_id, '_wp_ace_html_php_is_html_active', $html_php_editor_has_focus );


			// manually add metadata to revision
			if ( $parent_id ) {
				// only adding relevant metadata to revision, to minimize database size

				add_metadata( 'post', $post_id, '_wp_ace_html_php_preprocessor', $html_php_preprocessor );
				add_metadata( 'post', $post_id, '_wp_ace_html_php_editor_height', $html_php_editor_height );
		  }

		} 

	
	}

	function restore_code_revision( $post_id, $revision_id ) {

	}

	function code_revision_fields( $fields ) {

	}
	function code_revision_field__wp_ace_html_php_preprocessor( $value, $field ) {

	}
	function code_revision_field__wp_ace_html_php_editor_height( $value, $field ) {

	}

	private function compile($pre_code, $preprocessor) {
		$ret = new stdClass();
		
		$ret->compiled_code = '';
		$ret->status = '';
		$ret->error_msg = '';

		if ( empty($pre_code) ) {
			$ret->status = 'empty';
		} else {
			try {
					
				switch($preprocessor) {
					case 'scss' :
						$scss = new scssc();
						$compiled_code = $scss->compile($pre_code);
						$ret->compiled_code = trim($compiled_code);
						$ret->status = 'success';
						break;
					case 'less' :

						break;
					case 'stylus' :

						break;
					case 'haml' :

						break;
					case 'markdown' :

						break;
				}

			}
			catch(Exception $e) {
			  $ret->status = 'error';
			  $ret->error_msg = $e->getMessage();
			}			
		}

		return $ret;
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
      'name'               => _x( 'WP ACE Pre HTML', 'post type general name', 'admin-code-editor' ),
      'singular_name'      => _x( 'WP ACE Pre HTML', 'post type singular name', 'admin-code-editor' ),
      'menu_name'          => _x( 'WP ACE Pre HTML', 'admin menu', 'admin-code-editor' ),
      'name_admin_bar'     => _x( 'WP ACE Pre HTML', 'add new on admin bar', 'admin-code-editor' ),
      'add_new'            => _x( 'Add New', 'nw-item', 'admin-code-editor' ),
      'add_new_item'       => __( 'Add New WP ACE Pre HTML', 'admin-code-editor' ),
      'new_item'           => __( 'New WP ACE Pre HTML', 'admin-code-editor' ),
      'edit_item'          => __( 'Edit WP ACE Pre HTML', 'admin-code-editor' ),
      'view_item'          => __( 'View WP ACE Pre HTML', 'admin-code-editor' ),
      'all_items'          => __( 'All WP ACE Pre HTML', 'admin-code-editor' ),
      'search_items'       => __( 'Search WP ACE Pre HTML', 'admin-code-editor' ),
      'parent_item_colon'  => __( 'Parent WP ACE Pre HTML:', 'admin-code-editor' ),
      'not_found'          => __( 'No WP ACE Pre HTML found.', 'admin-code-editor' ),
      'not_found_in_trash' => __( 'No WP ACE Pre HTML found in Trash.', 'admin-code-editor' )
    );

    $args = array(
      'labels'                => $labels,
      'public'                => false,
      'publicly_queryable'    => false,
      'exclude_from_search'   => true,
      'show_ui'               => false, 
      'show_in_menu'          => false, 
      'query_var'             => false,
      'rewrite'               => false,
      'capability_type'       => 'post',
      'has_archive'           => false, 
      'hierarchical'          => false,
      'menu_position'         => null,
      'supports'              => array( 'editor', 'author', 'revisions')
    ); 

    register_post_type('wp-ace-html',$args);


    $labels = array(
      'name'               => _x( 'WP ACE Pre CSS', 'post type general name', 'admin-code-editor' ),
      'singular_name'      => _x( 'WP ACE Pre CSS', 'post type singular name', 'admin-code-editor' ),
      'menu_name'          => _x( 'WP ACE Pre CSS', 'admin menu', 'admin-code-editor' ),
      'name_admin_bar'     => _x( 'WP ACE Pre CSS', 'add new on admin bar', 'admin-code-editor' ),
      'add_new'            => _x( 'Add New', 'nw-item', 'admin-code-editor' ),
      'add_new_item'       => __( 'Add New WP ACE Pre CSS', 'admin-code-editor' ),
      'new_item'           => __( 'New WP ACE Pre CSS', 'admin-code-editor' ),
      'edit_item'          => __( 'Edit WP ACE Pre CSS', 'admin-code-editor' ),
      'view_item'          => __( 'View WP ACE Pre CSS', 'admin-code-editor' ),
      'all_items'          => __( 'All WP ACE Pre CSS', 'admin-code-editor' ),
      'search_items'       => __( 'Search WP ACE Pre CSS', 'admin-code-editor' ),
      'parent_item_colon'  => __( 'Parent WP ACE Pre CSS:', 'admin-code-editor' ),
      'not_found'          => __( 'No WP ACE Pre CSS found.', 'admin-code-editor' ),
      'not_found_in_trash' => __( 'No WP ACE Pre CSS found in Trash.', 'admin-code-editor' )
    );

    $args = array(
      'labels'                => $labels,
      'public'                => false,
      'publicly_queryable'    => false,
      'exclude_from_search'   => true,
      'show_ui'               => false, 
      'show_in_menu'          => false, 
      'query_var'             => false,
      'rewrite'               => false,
      'capability_type'       => 'post',
      'has_archive'           => false, 
      'hierarchical'          => false,
      'menu_position'         => null,
      'supports'              => array( 'editor', 'author', 'revisions')
    ); 

    register_post_type('wp-ace-css',$args);


    $labels = array(
      'name'               => _x( 'WP ACE Pre JS', 'post type general name', 'admin-code-editor' ),
      'singular_name'      => _x( 'WP ACE Pre JS', 'post type singular name', 'admin-code-editor' ),
      'menu_name'          => _x( 'WP ACE Pre JS', 'admin menu', 'admin-code-editor' ),
      'name_admin_bar'     => _x( 'WP ACE Pre JS', 'add new on admin bar', 'admin-code-editor' ),
      'add_new'            => _x( 'Add New', 'nw-item', 'admin-code-editor' ),
      'add_new_item'       => __( 'Add New WP ACE Pre JS', 'admin-code-editor' ),
      'new_item'           => __( 'New WP ACE Pre JS', 'admin-code-editor' ),
      'edit_item'          => __( 'Edit WP ACE Pre JS', 'admin-code-editor' ),
      'view_item'          => __( 'View WP ACE Pre JS', 'admin-code-editor' ),
      'all_items'          => __( 'All WP ACE Pre JS', 'admin-code-editor' ),
      'search_items'       => __( 'Search WP ACE Pre JS', 'admin-code-editor' ),
      'parent_item_colon'  => __( 'Parent WP ACE Pre JS:', 'admin-code-editor' ),
      'not_found'          => __( 'No WP ACE Pre JS found.', 'admin-code-editor' ),
      'not_found_in_trash' => __( 'No WP ACE Pre JS found in Trash.', 'admin-code-editor' )
    );

    $args = array(
      'labels'                => $labels,
      'public'                => false,
      'publicly_queryable'    => false,
      'exclude_from_search'   => true,
      'show_ui'               => false, 
      'show_in_menu'          => false, 
      'query_var'             => false,
      'rewrite'               => false,
      'capability_type'       => 'post',
      'has_archive'           => false, 
      'hierarchical'          => false,
      'menu_position'         => null,
      'supports'              => array( 'editor', 'author', 'revisions')
    ); 

    register_post_type('wp-ace-js',$args);

	} 


	/**
	 *
	 * Add options page for Admin Code Editor Settings
	 *
	 * @since 1.0.0 
	 */
	function options_menu() {
		add_options_page( 
			'Admin Code Editor Settings',
			'Admin Code Editor',
			'manage_options',
			'admin-code-editor-options-page',
			array(&$this,'admin_code_editor_settings_page')
		);
	}


	/**
	 *
	 * Admin Code Editor Settings Page Callback
	 *
	 * @since 1.0.0 
	 */
	function admin_code_editor_settings_page() {
		?>
			<div class="wrap">
				<h1><?php _e('Admin Code Editor Settings', 'admin-code-editor'); ?></h1>
				<form method="post" action="options.php">
					<?php
						do_settings_sections("admin-code-editor-options-page"); 
						settings_fields("admin-code-editor-settings");
						     
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
	function display_post_type_selection_field_element() {
		
		$args = array(
			'public'   => true
		);

		$post_types 					= get_post_types( $args, 'objects' ); 
		$selected_post_types 	= get_option('wpcr_post_types');
		
		$is_checked_post_type = function($post_type_name) use ($selected_post_types) {
			if (!empty($selected_post_types) && in_array($post_type_name, $selected_post_types) ) {
				return ' checked ';
			}
		};

		?>
			<div class="wp-ace-bootstrap">
				<?php

					foreach ( $post_types as $post_type ) {
				?>
				<div class="checkbox">
					<label for="wp-ace__enable-post-type-<?php echo $post_type->name; ?>" > 
						<input type="checkbox" id="wp-ace__enable-post-type-<?php echo $post_type->name; ?>" name="wp-ace-enable-post-type[]" value="<?php echo $post_type->name; ?>" <?php echo $is_checked_post_type($post_type->name); ?> /><?php echo $post_type->labels->name; ?>
					</label>
				</div>
				<?php
				}

				?>
			</div>

		<?php
	}


	/**
	 *
	 * Option field to choose which post types 'custom ratings' are applied to.
	 *
	 * @since 1.0.0
	 */
	function display_default_preprocessors_field_element() {
		?>

			<div class="wp-ace-bootstrap">
				<div class="input-group">
					<div class="radio">
						<label for="wp-ace__enable-haml" >
							<input type="radio" id="wp-ace__enable-haml" name="wp-ace-enable-preprocessor" value="haml"  /><?php _e('HAML', 'admin-code-editor') ?>
						</label>
					</div>
					<div class="radio">
						<label for="wp-ace__enable-coffee-script" >
							<input type="radio" id="wp-ace__enable-coffee-script" name="wp-ace-enable-preprocessor" value="coffee-script"  /><?php _e('Coffee Script', 'admin-code-editor') ?>
						</label>
					</div>
					<div class="radio">
						<label for="wp-ace__enable-less" >
							<input type="radio" id="wp-ace__enable-less" name="wp-ace-enable-preprocessor" value="less"  /><?php _e('LESS', 'admin-code-editor') ?>
						</label>
					</div>
				</div>
				<div class="input-group">
					<div class="radio">
						<label for="wp-ace__enable-markdown" >
							<input type="radio" id="wp-ace__enable-markdown" name="wp-ace-enable-preprocessor" value="markdown"  /><?php _e('Markdown', 'admin-code-editor') ?>
						</label>
					</div>
					<div class="radio">
						<label for="wp-ace__enable-sass" >
							<input type="radio" id="wp-ace__enable-sass" name="wp-ace-enable-preprocessor" value="sass"  /><?php _e('Sass', 'admin-code-editor') ?>
						</label>
					</div>
					<div class="radio">
						<label for="wp-ace__enable-stylus" >
							<input type="radio" id="wp-ace__enable-stylus" name="wp-ace-enable-preprocessor" value="stylus"  /><?php _e('Stylus', 'admin-code-editor') ?>
						</label>
					</div>
				</div>

			</div>

		<?php
	}


	/**
	 *
	 * Option field to choose which post types 'custom ratings' are applied to.
	 *
	 * @since 1.0.0
	 */
	function display_enable_preprocessors_field_element() {
		
		?>

			<div class="wp-ace-bootstrap">
				<div class="input-group">
					<div class="checkbox">
						<label for="wp-ace__enable-haml" >
							<input type="checkbox" id="wp-ace__enable-haml" name="wp-ace-enable-preprocessor" value="haml"  /><?php _e('HAML', 'admin-code-editor') ?>
						</label>
					</div>
					<div class="checkbox">
						<label for="wp-ace__enable-coffee-script" >
							<input type="checkbox" id="wp-ace__enable-coffee-script" name="wp-ace-enable-preprocessor" value="coffee-script"  /><?php _e('Coffee Script', 'admin-code-editor') ?>
						</label>
					</div>
					<div class="checkbox">
						<label for="wp-ace__enable-less" >
							<input type="checkbox" id="wp-ace__enable-less" name="wp-ace-enable-preprocessor" value="less"  /><?php _e('LESS', 'admin-code-editor') ?>
						</label>
					</div>
				</div>
				<div class="input-group">
					<div class="checkbox">
						<label for="wp-ace__enable-markdown" >
							<input type="checkbox" id="wp-ace__enable-markdown" name="wp-ace-enable-preprocessor" value="markdown"  /><?php _e('Markdown', 'admin-code-editor') ?>
						</label>
					</div>
					<div class="checkbox">
						<label for="wp-ace__enable-sass" >
							<input type="checkbox" id="wp-ace__enable-sass" name="wp-ace-enable-preprocessor" value="sass"  /><?php _e('Sass', 'admin-code-editor') ?>
						</label>
					</div>
					<div class="checkbox">
						<label for="wp-ace__enable-stylus" >
							<input type="checkbox" id="wp-ace__enable-stylus" name="wp-ace-enable-preprocessor" value="stylus"  /><?php _e('Stylus', 'admin-code-editor') ?>
						</label>
					</div>
				</div>

			</div>


		<?php

	}


	/**
	 *
	 * Option field to choose which post types 'custom ratings' are applied to.
	 *
	 * @since 1.0.0
	 */
	function display_default_html_position_field_element() {
		?>
			


			<div class="wp-ace-bootstrap">
				<div class="radio">
					
					<label for="wp-ace__default-html-pos-above" ><input type="radio" id="wp-ace__default-html-pos-above" name="wp-ace-default-html-pos" value="above" <?php checked('above', get_option('wp_ace_default_html_pos') ); ?> /><?php _e('Above Content', 'admin-code-editor') ?></label>
				</div>
				<div class="radio">
					
					<label for="wp-ace__default-html-pos-below" ><input type="radio" id="wp-ace__default-html-pos-below" name="wp-ace-default-html-pos" value="below" <?php checked('below', get_option('wp_ace_default_html_pos') ); ?> /><?php _e('Below Content', 'admin-code-editor') ?></label>
				</div>
			</div>

		<?php
	}	

	function display_theme_panel_fields() {
		
		// Set up subsections for settings page
		add_settings_section("general-section", "General Settings", null, "admin-code-editor-options-page");
		
		// General settings fields
		add_settings_field(
			"wp_ace_post_types",
			__('Apply to Post Types', 'admin-code-editor'),
			array(&$this,"display_post_type_selection_field_element"),
			"admin-code-editor-options-page", 
			"general-section"
		);

		add_settings_field(
			"wp_ace_default_preprocessors",
			__('Default Preprocessors', 'admin-code-editor'),
			array(&$this,"display_default_preprocessors_field_element"),
			"admin-code-editor-options-page", 
			"general-section"
		);

		add_settings_field(
			"wp_ace_enable_preprocessors",
			__('Enable Preprocessors', 'admin-code-editor'),
			array(&$this,"display_enable_preprocessors_field_element"),
			"admin-code-editor-options-page", 
			"general-section"
		);

		add_settings_field(
			"wp_ace_default_html_position",
			__('Default Position', 'admin-code-editor'),
			array(&$this,"display_default_html_position_field_element"),
			"admin-code-editor-options-page", 
			"general-section"
		);
		
		// Register general settings
		register_setting("admin-code-editor-settings", "wp_ace_post_types");
		register_setting("admin-code-editor-settings", "wp_ace_default_preprocessors");
		register_setting("admin-code-editor-settings", "wp_ace_enable_preprocessors");
		register_setting("admin-code-editor-settings", "wp_ace_default_html_position");
		
	}

}
