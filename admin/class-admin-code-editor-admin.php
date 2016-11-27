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
	public function enqueue_scripts($hook) {

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
		global $post;
		if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
			$selected_post_types 	= get_option('wpcr_post_types');

			if ( in_array($post->post_type, $selected_post_types)) {  
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-resizable' );
				wp_enqueue_script( 'backbone' ); 
				wp_enqueue_script( 'underscore' );
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
					array( 'jquery', 'wp-ace-bootstrap-js', 'wp-ace-editor-js', 'jquery-ui-resizable', 'underscore', 'backbone' ), 
					filemtime(plugin_dir_path( __FILE__ ) . 'js/admin-code-editor-admin.js')
				);
							
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-html-php.php';
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-css.php';
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-js.php';		
				
				$editor_args = array(
					'type' => 'html-php',
					'host-post-id' => $post->ID
				);
				$html_php_editor 	= new Admin_Code_Editor_Editor_HTML_PHP($editor_args);

				$editor_args = array(
					'type' => 'css',
					'host-post-id' => $post->ID
				);
				$css_editor 	= new Admin_Code_Editor_Editor_CSS($editor_args);

				$editor_args = array(
					'type' => 'js',
					'host-post-id' => $post->ID
				);
				$js_editor 	= new Admin_Code_Editor_Editor_JS($editor_args);

				$post_type_obj = get_post_type_object( $post->post_type );

				$wpcr_data = array(
					'wp-ace-html-php-disable-wpautop' 	=> $html_php_editor->get_disable_wpautop_status(),
					'wp-ace-html-php-code-position' 		=> $html_php_editor->get_code_output_position(),
					'wp-ace-html-php-preprocessor' 			=> $html_php_editor->get_preprocessor(),
					'wp-ace-css-preprocessor' 					=> $css_editor->get_preprocessor(),
					'wp-ace-css-include-jquery' 				=> $js_editor->get_include_jquery_status(),
					'wp-ace-js-preprocessor' 						=> $js_editor->get_preprocessor(),
					'wp-ace-post-type-singular-name'		=> $post_type_obj->labels->singular_name,
					'wp-ace-html-php-compile-status'		=> $html_php_editor->get_code_compile_status(),
					'wp-ace-css-compile-status'					=> $css_editor->get_code_compile_status(),
					'wp-ace-js-compile-status'					=> $js_editor->get_code_compile_status()
				);
				wp_localize_script( $this->admin_code_editor, 'wpcr_data', $wpcr_data);      
			}
		}
	}


	function code_editor_add_meta_box() {
		// add the metabox to posts and pages
					
		$selected_post_types 	= get_option('wpcr_post_types');
		$screens = $selected_post_types;

		foreach ( $screens as $screen ) {

			add_meta_box(
				'code_box',
				__( 'WP ACE Code Editor', 'wp-ace-editor' ),
				array(&$this,'code_editor_section_callback'),
				$screen, 
				'normal',
				'high'
			);
	       
		}

	}



	/**
	 *
	 * Display WordPress error message if at least one of the HTML, CSS, or JS precode compilation contains an error
	 *
	 */
	public function admin_post_error_notice() {
	  global $post, $pagenow;
		$screen = get_current_screen();

		if ( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) {
			$selected_post_types 	= get_option('wpcr_post_types');

			if ( in_array($post->post_type, $selected_post_types)) { 
				$editor_args = array(
					'type' => 'html-php',
					'host-post-id' => $post->ID
				);
				$html_php_editor 	= new Admin_Code_Editor_Editor_HTML_PHP($editor_args);

				$editor_args = array(
					'type' => 'css',
					'host-post-id' => $post->ID
				);
				$css_editor 	= new Admin_Code_Editor_Editor_CSS($editor_args);

				$editor_args = array(
					'type' => 'js',
					'host-post-id' => $post->ID
				);
				$js_editor 	= new Admin_Code_Editor_Editor_JS($editor_args);

			  if ( 
			  	$html_php_editor->get_code_compile_status() == 'error' ||
			  	$css_editor->get_code_compile_status() == 'error' ||
			  	$js_editor->get_code_compile_status() == 'error'
			   ) {

				  ?>
			    <div class="error">
			      <p> <?php _e('There is an error in the WP ACE Editor Code. <a href="#wp-ace__tabs">See below for more information</a>.', 'wrs-admin-code-editor'); ?> </p>
			    </div>
			    <?php
		  	}
		  }
		}
	}

	/**
	 * Prints the box content.
	 * 
	 * @param WP_Post $post The object for the current post/page.
	 */
	function code_editor_section_callback( $post ) {
	

		wp_nonce_field( 'wp-ace-editor-nonce', 'wp-ace-editor-nonce' );
		/*
		$disabled_templates 					= get_post_meta($post->ID, '_wp_ace_disabled_templates', true);
		if (!$disabled_templates ) {
			$disabled_templates = array();
		}
		$only_display_in_loop 				= get_post_meta($post->ID, '_wp_ace_display_only_in_loop', true);
		$only_display_in_main_query 	= get_post_meta($post->ID, '_wp_ace_display_only_in_main_query', true);
		$last_active_tab_id 					= get_post_meta($post->ID, '_wp_ace_last_active_tab', true);
		
	
		if (!$last_active_tab_id) {
			$last_active_tab_id = 'html-edit';
		}
		*/
	
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-admin-code-editor-general.php';
		$general_settings = new Admin_Code_Editor_General($post->ID);
		

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-html-php.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-css.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-js.php';
		
		$editor_args = array(
			'type' => 'html-php',
			'host-post-id' => $post->ID
		);
		$html_php_editor 	= new Admin_Code_Editor_Editor_HTML_PHP($editor_args);

		$editor_args = array(
			'type' => 'css',
			'host-post-id' => $post->ID
		);
		$css_editor 	= new Admin_Code_Editor_Editor_CSS($editor_args);

		$editor_args = array(
			'type' => 'js',
			'host-post-id' => $post->ID
		);
		$js_editor 	= new Admin_Code_Editor_Editor_JS($editor_args);

		$preprocessor_options = get_option('wp_ace_supported_preprocessors');

		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/partials/admin-code-editor-admin-post-edit.php';
	
	}	


	function plugin_update_check() {
		if (get_site_option( 'wp_ace_plugin_version' ) != $this->version) {
      $this->plugin_update();

    }
	}


	function delete_code_posts($postid) {
		global $post_type; 

		$selected_post_types 	= get_option('wpcr_post_types');
		if ( !in_array($post_type, $selected_post_types)) {  
			return;
		}

		$html_code_post_id = get_post_meta($postid, '_wp_ace_html_php_code_post_id', true);
		$css_code_post_id = get_post_meta($postid, '_wp_ace_css_code_post_id', true);
		$js_code_post_id = get_post_meta($postid, '_wp_ace_js_code_post_id', true);

		wp_delete_post( $html_code_post_id, true );
		wp_delete_post( $css_code_post_id, true );
		wp_delete_post( $js_code_post_id, true );

	}


	private function plugin_update() {
		$supported_preprocessors = array(
			'html' => array(
				'haml' => 'HAML',
				'markdown' => 'MarkDown'
				),
			'css' => array(
				'scss' => 'SCSS',
				'less' => 'LESS',
				//'stylus' => 'Stylus'
				),
			'js' => array(
				'coffee' => 'CoffeeScript'
				)
			);
		update_option( 'wp_ace_supported_preprocessors', $supported_preprocessors);
		update_option( 'wp_ace_plugin_version', $this->version);
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
		
		if ( false !== wp_is_post_revision( $post_id ) )
        return;

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
		/*
		if (isset($_POST['wp-ace-disabled-templates'])) {
			$disabled_templates = $_POST['wp-ace-disabled-templates'];
			update_post_meta($post_id, '_wp_ace_disabled_templates', $disabled_templates );
		}		
		if (isset($_POST['wp-ace-only-display-in-loop'])) {
			$only_display_in_loop = $_POST['wp-ace-only-display-in-loop'];
			update_post_meta($post_id, '_wp_ace_display_only_in_loop', $only_display_in_loop );
		}	else {
			delete_post_meta($post_id, '_wp_ace_display_only_in_loop');
		}			
		if (isset($_POST['wp-ace-only-display-in-main-query'])) {
			$only_display_in_main_query = $_POST['wp-ace-only-display-in-main-query'];
			update_post_meta($post_id, '_wp_ace_display_only_in_main_query', $only_display_in_main_query );
		} else {
			delete_post_meta($post_id, '_wp_ace_display_only_in_loop');
		}
		*/
		
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-admin-code-editor-general.php';
		$general_settings = new Admin_Code_Editor_General($post_id);
		$general_settings->updateDataFromPOST(); 


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-html-php.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-css.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-js.php';

		$editor_args = array(
			'type' => 'html-php',
			'host-post-id' => $post_id
		);
		$html_editor 	= new Admin_Code_Editor_Editor_HTML_PHP($editor_args);
		
		$editor_args = array(
			'type' => 'css',
			'host-post-id' => $post_id
		);		
		$css_editor 	= new Admin_Code_Editor_Editor_CSS($editor_args);
		
		$editor_args = array(
			'type' => 'js',
			'host-post-id' => $post_id
		);		
		$js_editor 		= new Admin_Code_Editor_Editor_JS($editor_args);

		$html_editor->initialize_from_post_request();
		$css_editor->initialize_from_post_request();
		$js_editor->initialize_from_post_request();

		$html_editor->update_code();
		$css_editor->update_code();
		$js_editor->update_code();
	
	}

	/**
	 *
	 * Revision Handling
	 *
	 */
	
	function restore_code_revision( $post_id, $revision_id ) {
		$post     = get_post( $post_id );
		$revision = get_post( $revision_id );
		$meta_fields = ['_wp_ace_preprocessor', '_wp_ace_editor_height'];

		foreach($meta_fields as $meta_field) {
			$meta_val  = get_metadata( 'post', $revision->ID, $meta_field, true );

			if ( false !== $meta_val )
				update_post_meta( $post_id, $meta_field, $meta_val );
			else
				delete_post_meta( $post_id, $meta_field );			
		}

		// TODO: compile code again from revision
	}

	function code_revision_fields( $fields ) {
		$fields['_wp_ace_preprocessor'] = 'HTML Proprocessor';
		$fields['_wp_ace_editor_height'] = 'HTML Editor Height';
		return $fields;
	}

	function code_revision_field__wp_ace_preprocessor( $value, $field ) {
		global $revision;
		//return get_metadata( 'post', $revision->ID, $field, true );
		return $value;
	}

	function code_revision_field__wp_ace_editor_height( $value, $field ) {
		global $revision;
		//return get_metadata( 'post', $revision->ID, $field, true );
		return $value;
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


  function filterDefaultHideonTemplates($hidden_templates) {
  	if (empty($hidden_templates)) {
  		$hidden_templates = array();
  	}  	

  	return $hidden_templates;
  }

  function filterDefaultHideCodeEditorTypes($code_editors) {
  	if (empty($code_editors)) {
  		$code_editors = array();
  	}

  	return $code_editors;
  }

  function filterDefaultConditionalDisplay($conditional_display) {
  	if (empty($conditional_display)) {
  		$conditional_display = array();
  	}

  	return $conditional_display;
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
	 * Option field to choose which post types 'custom ratings' are applied to.
	 *
	 * @since 1.0.0
	 */
	function display_post_type_selection_field_element() {
		
		$args = array(
			'public'   => true
		);

		$post_types 					= get_post_types( $args, 'objects' ); 
		$selected_post_types 	= get_option('wp_ace_enabled_post_type');
		
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
						<input type="checkbox" id="wp-ace__enable-post-type-<?php echo $post_type->name; ?>" name="wp_ace_enabled_post_type[]" value="<?php echo $post_type->name; ?>" <?php echo $is_checked_post_type($post_type->name); ?> /><?php echo $post_type->labels->name; ?>
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
	function display_default_disabled_templates_field_element() {
		
		$default_disabled_template =  get_option('wp_ace_default_disabled_template');
		if (empty($default_disabled_template) || !is_array($default_disabled_template) ) {
			$default_disabled_template = array();
		} 

		?>

			<div class="wp-ace-bootstrap">
				
				<div class="checkbox">
					<label for="wp-ace__hide-on-front-page-template" >
						<input type="checkbox" id="wp-ace__hide-on-front-page-template" name="wp_ace_default_disabled_template[]" value="front-page"  <?php checked( in_array('front-page', $default_disabled_template ) ); ?>  /><?php _e('Front Page', 'wrs-admin-code-editor') ?>
					</label>
				</div>					

				<div class="checkbox">
					<label for="wp-ace__hide-on-home-template" >
						<input type="checkbox" id="wp-ace__hide-on-home-template" name="wp_ace_default_disabled_template[]" value="home"  <?php checked( in_array( 'home', $default_disabled_template ) ); ?>  /><?php _e('Home', 'wrs-admin-code-editor') ?>
					</label>
				</div>

				<div class="checkbox">
					<label for="wp-ace__hide-on-archive-template" >
						<input type="checkbox" id="wp-ace__hide-on-archive-template" name="wp_ace_default_disabled_template[]" value="archive"  <?php checked( in_array('archive', $default_disabled_template ) ); ?>  /><?php _e('Archives', 'wrs-admin-code-editor') ?>
					</label>
				</div>					

				<div class="checkbox">
					<label for="wp-ace__hide-on-search-template" >
						<input type="checkbox" id="wp-ace__hide-on-search-template" name="wp_ace_default_disabled_template[]" value="search"  <?php checked( in_array('search',  $default_disabled_template ) ); ?>  /><?php _e('Search Results', 'wrs-admin-code-editor') ?>
					</label>
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
	function display_default_conditional_display_field_element() {
		
		$default_conditional_display =  get_option('wp_ace_default_conditional_display');
		if (empty($default_conditional_display) || !is_array($default_conditional_display) ) {
			$default_conditional_display = array();
		} 
		
		?>

			<div class="wp-ace-bootstrap">
				
				<div class="checkbox">
					<label for="wp-ace--conditional-display--in-the-loop" >
						<input type="checkbox" id="wp-ace--conditional-display--in-the-loop" name="wp_ace_default_conditional_display[]" value="inside-the-loop" <?php checked( in_array('inside-the-loop', $default_conditional_display  ) ); ?> /><?php _e('Inside the loop', 'wrs-admin-code-editor') ?>
					</label>
				</div>					

				<div class="checkbox">
					<label for="wp-ace--conditional-display--in-main-query" >
						<input type="checkbox" id="wp-ace--conditional-display--in-main-query" name="wp_ace_default_conditional_display[]" value="in-main-query"  <?php checked( in_array( 'in-main-query', $default_conditional_display ) ); ?> /><?php _e('In main query', 'wrs-admin-code-editor') ?>
					</label>
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
	function display_default_html_preprocessors_field_element() {
		
		$default_html_preprocessor =  get_option('wp_ace_default_html_preprocessor');
		if (empty($default_html_preprocessor) || !is_array($default_html_preprocessor) ) {
			$default_html_preprocessor = array();
		} 

		?>

			<div class="wp-ace-bootstrap">
				
				<div class="radio">
					<label for="wp-ace--default-html-preprocessor--none" >
						<input type="radio" id="wp-ace--default-html-preprocessor--none" name="wp_ace_default_html_preprocessor" value="none"  <?php checked( in_array( 'none', $default_html_preprocessor ) ); ?>  /><?php _e('None', 'wrs-admin-code-editor') ?>
					</label>
				</div>					

				<div class="radio">
					<label for="wp-ace--default-html-preprocessor--haml" >
						<input type="radio" id="wp-ace--default-html-preprocessor--haml" name="wp_ace_default_html_preprocessor" value="haml" <?php checked( in_array( 'haml', $default_html_preprocessor ) ); ?>    /><?php _e('HAML', 'wrs-admin-code-editor') ?>
					</label>
				</div>

				<div class="radio">
					<label for="wp-ace--default-html-preprocessor--markdown" >
						<input type="radio" id="wp-ace--default-html-preprocessor--markdown" name="wp_ace_default_html_preprocessor" value="markdown"  <?php checked( in_array( 'markdown', $default_html_preprocessor ) ); ?>  /><?php _e('MarkDown', 'wrs-admin-code-editor') ?>
					</label>
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
	function display_default_disable_wpautop_field_element() {
		?>

			<div class="wp-ace-bootstrap">
				
				<div class="checkbox">
					<label for="wp_ace_default_disable_wpautop" >
						<input type="checkbox" id="wp_ace_default_disable_wpautop" name="wp_ace_default_disable_wpautop" value="1" <?php checked('1', get_option('wp_ace_default_disable_wpautop') ); ?> /><?php _e('Disable wpautop', 'wrs-admin-code-editor') ?>
					</label>
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
					
					<label for="wp-ace__default-html-pos-above" >
						<input type="radio" id="wp-ace__default-html-pos-above" name="wp_ace_default_html_position" value="after" <?php checked('after', get_option('wp_ace_default_html_position') ); ?> /><?php _e('After Content', 'wrs-admin-code-editor') ?></label>
				</div>
				<div class="radio">
					
					<label for="wp-ace__default-html-pos-below" >
						<input type="radio" id="wp-ace__default-html-pos-below" name="wp_ace_default_html_position" value="before" <?php checked('before', get_option('wp_ace_default_html_position') ); ?> /><?php _e('Before Content', 'wrs-admin-code-editor') ?></label>
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
	function display_default_css_preprocessors_field_element() {
		?>

			<div class="wp-ace-bootstrap">
					<div class="radio">
						<label for="wp-ace--default-css-preprocessor--css" >
							<input type="radio" id="wp-ace--default-css-preprocessor--css" name="wp_ace_default_css_preprocessor" value="none" <?php checked('none', get_option('wp_ace_default_css_preprocessor') ); ?> /><?php _e('None', 'wrs-admin-code-editor') ?>
						</label>
					</div>

					<div class="radio">
						<label for="wp-ace--default-css-preprocessor--less" >
							<input type="radio" id="wp-ace--default-css-preprocessor--less" name="wp_ace_default_css_preprocessor" value="less" <?php checked('less', get_option('wp_ace_default_css_preprocessor') ); ?>  /><?php _e('LESS', 'wrs-admin-code-editor') ?>
						</label>
					</div>
				
					<div class="radio">
						<label for="wp-ace--default-css-preprocessor--scss" >
							<input type="radio" id="wp-ace--default-css-preprocessor--scss" name="wp_ace_default_css_preprocessor" value="scss" <?php checked('scss', get_option('wp_ace_default_css_preprocessor') ); ?>  /><?php _e('SCSS', 'wrs-admin-code-editor') ?>
						</label>
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
	function display_default_js_preprocessors_field_element() {
		?>

			<div class="wp-ace-bootstrap">
					<div class="radio">
						<label for="wp-ace--default-js-preprocessor--none" >
							<input type="radio" id="wp-ace--default-js-preprocessor--none" name="wp_ace_default_js_preprocessor" value="none" <?php checked('none', get_option('wp_ace_default_js_preprocessor') ); ?>  /><?php _e('None', 'wrs-admin-code-editor') ?>
						</label>
					</div>
					<div class="radio">
						<label for="wp-ace--default-js-preprocessor--coffee" >
							<input type="radio" id="wp-ace--default-js-preprocessor--coffee" name="wp_ace_default_js_preprocessor" value="coffee" <?php checked('coffee', get_option('wp_ace_default_js_preprocessor') ); ?>  /><?php _e('CoffeeScript', 'wrs-admin-code-editor') ?>
						</label>
					</div>
					
					<!-- Stylus is not currently supported in WP ACE -->
					<!--
					<div class="radio">
						<label for="wp-ace__enable-stylus" >
							<input type="radio" id="wp-ace__enable-stylus" name="wp_ace_default_js_preprocessor" value="stylus"  /><?php _e('Stylus', 'wrs-admin-code-editor') ?>
						</label>
					</div>
					-->
			</div>

		<?php
	}

	/**
	 *
	 * Option field to choose which post types 'custom ratings' are applied to.
	 *
	 * @since 1.0.0
	 */
	function display_default_include_jquery_field_element() {
		?>

			<div class="wp-ace-bootstrap">
				
				<div class="checkbox">
					<label for="wp_ace_default_include_jquery" >
						<input type="checkbox" id="wp_ace_default_include_jquery" name="wp_ace_default_include_jquery" value="1"  <?php checked('1', get_option('wp_ace_default_include_jquery') ); ?>  /><?php _e('Include jQuery', 'wrs-admin-code-editor') ?>
					</label>
				</div>					

			</div>

		<?php
	}


	/**
	 *
	 * Option field to disable certain code types (HTML, CSS, JS)
	 *
	 * @since 1.0.0
	 */
	function display_default_hide_code_types_field_element() {
		
		$default_disabled_code = get_option('wp_ace_default_disabled_code');
		if (empty($default_disabled_code) || !is_array($default_disabled_code) ) {
			$default_disabled_code = array();
		}
		?>

			<div class="wp-ace-bootstrap">
				
				<div class="checkbox">
					<label for="wp-ace--default-disabled-code--html" >
						<input type="checkbox" id="wp-ace--default-disabled-code--html" name="wp_ace_default_disabled_code[]" value="html"  <?php checked( in_array( 'html', $default_disabled_code ) ); ?>  /><?php _e('HTML', 'wrs-admin-code-editor') ?>
					</label>
				</div>					

				<div class="checkbox">
					<label for="wp-ace--default-disabled-code--css" >
						<input type="checkbox" id="wp-ace--default-disabled-code--css" name="wp_ace_default_disabled_code[]" value="css"  <?php checked( in_array( 'css', $default_disabled_code ) ); ?>  /><?php _e('CSS', 'wrs-admin-code-editor') ?>
					</label>
				</div>

				<div class="checkbox">
					<label for="wp-ace--default-disabled-code--js" >
						<input type="checkbox" id="wp-ace--default-disabled-code--js" name="wp_ace_default_disabled_code[]" value="js"  <?php checked( in_array( 'js', $default_disabled_code ) ); ?>  /><?php _e('JavaScript', 'wrs-admin-code-editor') ?>
					</label>
				</div>					

			</div>

		<?php
	}

	function display_theme_panel_fields() {
		
		// Set up subsections for settings page
		add_settings_section("enable-section", 			__('Enable Code Editor', 'wrs-admin-code-editor'), null, "admin-code-editor-options-page");
		add_settings_section("general-section", 		__('Default General Settings', 'wrs-admin-code-editor'), null, "admin-code-editor-options-page");
		add_settings_section("html-php-section", 		__('Default HTML Settings', 'wrs-admin-code-editor'), null, "admin-code-editor-options-page");
		add_settings_section("css-section", 				__('Default CSS Settings', 'wrs-admin-code-editor'), null, "admin-code-editor-options-page");
		add_settings_section("javascript-section", 	__('Default JavaScript Settings', 'wrs-admin-code-editor'), null, "admin-code-editor-options-page");

		// General settings fields
		add_settings_field(
			"wp_ace_enabled_post_type",
			__('Apply to Post Types', 'wrs-admin-code-editor'),
			array(&$this,"display_post_type_selection_field_element"),
			"admin-code-editor-options-page", 
			"enable-section"
		);
		add_settings_field(
			"wp_ace_default_conditional_display",
			__('Only display when', 'wrs-admin-code-editor'),
			array(&$this,"display_default_conditional_display_field_element"),
			"admin-code-editor-options-page", 
			"general-section"
		);
		add_settings_field(
			"wp_ace_default_hide_on_templates",
			__('Hide on Templates', 'wrs-admin-code-editor'),
			array(&$this,"display_default_disabled_templates_field_element"),
			"admin-code-editor-options-page", 
			"general-section"
		);
		add_settings_field(
			"wp_ace_default_hide_code_types",
			__('Hide Code Editor Types', 'wrs-admin-code-editor'),
			array(&$this,"display_default_hide_code_types_field_element"),
			"admin-code-editor-options-page", 
			"general-section"
		);

		// HTML settings fields
		add_settings_field(
			"wp_ace_default_html_preprocessor",
			__('Preprocessor', 'wrs-admin-code-editor'),
			array(&$this,"display_default_html_preprocessors_field_element"),
			"admin-code-editor-options-page", 
			"html-php-section"
		);
		add_settings_field(
			"wp_ace_default_html_position",
			__('Position', 'wrs-admin-code-editor'),
			array(&$this,"display_default_html_position_field_element"),
			"admin-code-editor-options-page", 
			"html-php-section"
		);
		add_settings_field(
			"wp_ace_default_disable_wpautop",
			__('Disable wpautop', 'wrs-admin-code-editor'),
			array(&$this,"display_default_disable_wpautop_field_element"),
			"admin-code-editor-options-page", 
			"html-php-section"
		);


		// CSS settings fields
		add_settings_field(
			"wp_ace_default_css_preprocessors",
			__('Preprocessor', 'wrs-admin-code-editor'),
			array(&$this,"display_default_css_preprocessors_field_element"),
			"admin-code-editor-options-page", 
			"css-section"
		);


		// JS settings fields
		add_settings_field(
			"wp_ace_default_js_preprocessors",
			__('Preprocessor', 'wrs-admin-code-editor'),
			array(&$this,"display_default_js_preprocessors_field_element"),
			"admin-code-editor-options-page", 
			"javascript-section"
		);
		add_settings_field(
			"wp_ace_default_include_jquery",
			__('Include jQuery', 'wrs-admin-code-editor'),
			array(&$this,"display_default_include_jquery_field_element"),
			"admin-code-editor-options-page", 
			"javascript-section"
		);
		
		// Register general settings
		register_setting("admin-code-editor-settings", "wp_ace_enabled_post_type");
		register_setting("admin-code-editor-settings", "wp_ace_default_disabled_template");
		register_setting("admin-code-editor-settings", "wp_ace_default_conditional_display");

		register_setting("admin-code-editor-settings", "wp_ace_default_html_preprocessor");
		register_setting("admin-code-editor-settings", "wp_ace_default_html_position");
		register_setting("admin-code-editor-settings", "wp_ace_default_disable_wpautop");

		register_setting("admin-code-editor-settings", "wp_ace_default_css_preprocessor");

		register_setting("admin-code-editor-settings", "wp_ace_default_js_preprocessor");
		register_setting("admin-code-editor-settings", "wp_ace_default_include_jquery");
		register_setting("admin-code-editor-settings", "wp_ace_default_disabled_code");

	}

}
