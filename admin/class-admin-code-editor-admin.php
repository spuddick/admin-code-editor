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
		wp_enqueue_script( 'wp-util' ); // enqueues underscore.js
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
		// TODO: need to iterate through specified post types from settings page

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
	 * Prints the box content.
	 * 
	 * @param WP_Post $post The object for the current post/page.
	 */
	function code_editor_section_callback( $post ) {
	
		//$html_code 		= get_post_meta( $post->ID, '_html_code', true );
		wp_nonce_field( 'wp-ace-editor-nonce', 'wp-ace-editor-nonce' );
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-html-php.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-css.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-js.php';
		$editor_args = array(
			'type' => 'html-php',
			'host-post-id' => $post->ID
		);
		$html_php_editor 	= new Admin_Code_Editor_Editor_HTML_PHP($editor_args);

		$preprocessor_options = get_option('wp_ace_supported_preprocessors');

		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/partials/admin-code-editor-admin-post-edit.php';
	
	}	


	function plugin_update_check() {
		if (get_site_option( 'wp_ace_plugin_version' ) != $this->version) {
      $this->plugin_update();

    }
	}


	private function plugin_update() {
		$supported_preprocessors = array(
			'html' => array(
				'haml' => 'HAML',
				'markdown' => 'MarkDown'
				),
			'css' => array(
				'scss' => 'Scss',
				'less' => 'LESS'
				),
			'js' => array(
				'coffeescript' => 'CoffeeScript',
				'stylus' => 'Stylus'
				),
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-html-php.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-css.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-js.php';

		$editor_args = array(
			'type' => 'html-php',
			'host-post-id' => $post_id
		);
		$html_editor 	= new Admin_Code_Editor_Editor_HTML_PHP($editor_args);
		//$css_editor 	= new Admin_Code_Editor_Editor('css', $post_id);
		//$js_editor 		= new Admin_Code_Editor_Editor('js', $post_id);

		$html_editor->initialize_from_post_request();
		//$css_editor->initialize_from_post_request();
		//$js_editor->initialize_from_post_request();

		$html_editor->update_code();
		//$css_editor->update_code();
		//$js_editor->update_code();
	
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
		?>

			<div class="wp-ace-bootstrap">
				
				<div class="checkbox">
					<label for="wp-ace__enable-coffee-script" >
						<input type="checkbox" id="wp-ace__enable-coffee-script" name="wp_ace_default_disabled_template[]" value="none"  /><?php _e('Front Page', 'admin-code-editor') ?>
					</label>
				</div>					

				<div class="checkbox">
					<label for="wp-ace__enable-haml" >
						<input type="checkbox" id="wp-ace__enable-haml" name="wp_ace_default_disabled_template[]" value="haml"  /><?php _e('Home', 'admin-code-editor') ?>
					</label>
				</div>

				<div class="checkbox">
					<label for="wp-ace__enable-coffee-script" >
						<input type="checkbox" id="wp-ace__enable-coffee-script" name="wp_ace_default_disabled_template[]" value="none"  /><?php _e('Archives', 'admin-code-editor') ?>
					</label>
				</div>					

				<div class="checkbox">
					<label for="wp-ace__enable-haml" >
						<input type="checkbox" id="wp-ace__enable-haml" name="wp_ace_default_disabled_template[]" value="haml"  /><?php _e('Search Results', 'admin-code-editor') ?>
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
		?>

			<div class="wp-ace-bootstrap">
				
				<div class="checkbox">
					<label for="wp-ace__enable-coffee-script" >
						<input type="checkbox" id="wp-ace__enable-coffee-script" name="wp_ace_default_conditional_display[]" value="none"  /><?php _e('inside the loop', 'admin-code-editor') ?>
					</label>
				</div>					

				<div class="checkbox">
					<label for="wp-ace__enable-haml" >
						<input type="checkbox" id="wp-ace__enable-haml" name="wp_ace_default_conditional_display[]" value="haml"  /><?php _e('in main query', 'admin-code-editor') ?>
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
		?>

			<div class="wp-ace-bootstrap">
				
				<div class="radio">
					<label for="wp-ace__enable-coffee-script" >
						<input type="radio" id="wp-ace__enable-coffee-script" name="wp_ace_default_html_preprocessor" value="none"  /><?php _e('None', 'admin-code-editor') ?>
					</label>
				</div>					

				<div class="radio">
					<label for="wp-ace__enable-haml" >
						<input type="radio" id="wp-ace__enable-haml" name="wp_ace_default_html_preprocessor" value="haml"  /><?php _e('HAML', 'admin-code-editor') ?>
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
					<label for="wp-ace__enable-coffee-script" >
						<input type="checkbox" id="wp-ace__enable-coffee-script" name="wp_ace_default_disable_wpautop" value="none"  /><?php _e('Disable wpautop', 'admin-code-editor') ?>
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
					
					<label for="wp-ace__default-html-pos-above" ><input type="radio" id="wp-ace__default-html-pos-above" name="wp_ace_default_html_position" value="above" <?php checked('above', get_option('wp_ace_default_html_pos') ); ?> /><?php _e('Above Content', 'admin-code-editor') ?></label>
				</div>
				<div class="radio">
					
					<label for="wp-ace__default-html-pos-below" ><input type="radio" id="wp-ace__default-html-pos-below" name="wp_ace_default_html_position" value="below" <?php checked('below', get_option('wp_ace_default_html_pos') ); ?> /><?php _e('Below Content', 'admin-code-editor') ?></label>
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
						<label for="wp-ace__enable-haml" >
							<input type="radio" id="wp-ace__enable-haml" name="wp_ace_default_css_preprocessor" value="none"  /><?php _e('None', 'admin-code-editor') ?>
						</label>
					</div>

					<div class="radio">
						<label for="wp-ace__enable-less" >
							<input type="radio" id="wp-ace__enable-less" name="wp_ace_default_css_preprocessor" value="less"  /><?php _e('LESS', 'admin-code-editor') ?>
						</label>
					</div>
				
					<div class="radio">
						<label for="wp-ace__enable-sass" >
							<input type="radio" id="wp-ace__enable-sass" name="wp_ace_default_css_preprocessor" value="sass"  /><?php _e('Sass', 'admin-code-editor') ?>
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
						<label for="wp-ace__enable-haml" >
							<input type="radio" id="wp-ace__enable-haml" name="wp_ace_default_js_preprocessor" value="none"  /><?php _e('None', 'admin-code-editor') ?>
						</label>
					</div>
					<div class="radio">
						<label for="wp-ace__enable-coffee-script" >
							<input type="radio" id="wp-ace__enable-coffee-script" name="wp_ace_default_js_preprocessor" value="coffee-script"  /><?php _e('Coffee Script', 'admin-code-editor') ?>
						</label>
					</div>
					<div class="radio">
						<label for="wp-ace__enable-stylus" >
							<input type="radio" id="wp-ace__enable-stylus" name="wp_ace_default_js_preprocessor" value="stylus"  /><?php _e('Stylus', 'admin-code-editor') ?>
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
	function display_default_include_jquery_field_element() {
		?>

			<div class="wp-ace-bootstrap">
				
				<div class="checkbox">
					<label for="wp-ace__enable-coffee-script" >
						<input type="checkbox" id="wp-ace__enable-coffee-script" name="wp_ace_default_include_jquery" value="none"  /><?php _e('Include jQuery', 'admin-code-editor') ?>
					</label>
				</div>					

			</div>

		<?php
	}

	function display_theme_panel_fields() {
		
		// Set up subsections for settings page
		add_settings_section("general-section", "General Settings", null, "admin-code-editor-options-page");
		add_settings_section("html-php-section", "HTML Settings", null, "admin-code-editor-options-page");
		add_settings_section("css-section", "CSS Settings", null, "admin-code-editor-options-page");
		add_settings_section("javascript-section", "Javascript Settings", null, "admin-code-editor-options-page");

		// General settings fields
		add_settings_field(
			"wp_ace_enabled_post_types",
			__('Apply to Post Types', 'admin-code-editor'),
			array(&$this,"display_post_type_selection_field_element"),
			"admin-code-editor-options-page", 
			"general-section"
		);
		add_settings_field(
			"wp_ace_default_conditional_display",
			__('Only display when', 'admin-code-editor'),
			array(&$this,"display_default_conditional_display_field_element"),
			"admin-code-editor-options-page", 
			"general-section"
		);
		add_settings_field(
			"wp_ace_default_hide_on_templates",
			__('Hide on Templates', 'admin-code-editor'),
			array(&$this,"display_default_disabled_templates_field_element"),
			"admin-code-editor-options-page", 
			"general-section"
		);


		// HTML settings fields
		add_settings_field(
			"wp_ace_default_html_preprocessors",
			__('Default Preprocessor', 'admin-code-editor'),
			array(&$this,"display_default_html_preprocessors_field_element"),
			"admin-code-editor-options-page", 
			"html-php-section"
		);
		add_settings_field(
			"wp_ace_default_html_position",
			__('Default Position', 'admin-code-editor'),
			array(&$this,"display_default_html_position_field_element"),
			"admin-code-editor-options-page", 
			"html-php-section"
		);
		add_settings_field(
			"wp_ace_default_disable_wpautop",
			__('Disable wpautop', 'admin-code-editor'),
			array(&$this,"display_default_disable_wpautop_field_element"),
			"admin-code-editor-options-page", 
			"html-php-section"
		);


		// CSS settings fields
		add_settings_field(
			"wp_ace_default_css_preprocessors",
			__('Default Preprocessor', 'admin-code-editor'),
			array(&$this,"display_default_css_preprocessors_field_element"),
			"admin-code-editor-options-page", 
			"css-section"
		);


		// JS settings fields
		add_settings_field(
			"wp_ace_default_js_preprocessors",
			__('Default Preprocessor', 'admin-code-editor'),
			array(&$this,"display_default_js_preprocessors_field_element"),
			"admin-code-editor-options-page", 
			"javascript-section"
		);
		add_settings_field(
			"wp_ace_default_include_jquery",
			__('Include jQuery', 'admin-code-editor'),
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
		
	}

}
