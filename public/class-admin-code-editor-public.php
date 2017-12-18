<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://webrockstar.net
 * @since      1.0.0
 *
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/public
 * @author     Steve Puddick <steve@webrockstar.net>
 */
class Admin_Code_Editor_Public {

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
	 * @param      string    $admin_code_editor       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $admin_code_editor, $version ) {

		$this->admin_code_editor = $admin_code_editor;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->admin_code_editor, plugin_dir_url( __FILE__ ) . 'css/admin-code-editor-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		//wp_enqueue_script( $this->admin_code_editor, plugin_dir_url( __FILE__ ) . 'js/admin-code-editor-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('jquery'); 
	}

	/**
	 * Output JavaScript in footer from posts displayed on the current page (from global array)
	 * @since 1.0.0
	 */
	public function insert_script_in_footer() {
		global $wp_ace_js_output;
		$wp_ace_js_output_string = '';

		if (!empty($wp_ace_js_output)) {
			foreach ($wp_ace_js_output as $post_id => $wp_ace_js_code) {
				$wp_ace_js_output_string .= '<script id="wp-ace-javascript--post-'. $post_id .'" >//<![CDATA[' . "\r\n" . $wp_ace_js_code . "\r\n" . '//]]></script>';
			}
			echo $wp_ace_js_output_string;			
		}

	}

	/**
	 * A custom filter to apply/not apply the wpautop filter when needed
	 * 
	 * @param string $content 
	 * @return string
	 * @since 1.0.0 
	 */
	function wp_ace_wpautop($content) {
		global $post;
		$selected_post_types 	= get_option('wp_ace_enabled_post_type');
		
		if ( !in_array($post->post_type, $selected_post_types)) {  
			return wpautop($content);
		}
		
		return $content;

	}

	/**
	 * Insert WP ACE HTML, CSS, and JavaScript into the page
	 * 
	 * @param string $content Post content 
	 * @return string post content with WP ACE code appended
	 * @since 1.0.0
	 */
	function insert_ace_code_in_page($content){
		// The different types of code (HTML, CSS, Javascript) are appended after the regular page content (using the wordpress function the_content() ).
		// We hook into the 'the_content' filter to acheive this.
		
		$content = wpautop($content);
		if (current_filter() != 'the_content') {
			return $content;
		}

		global $post, $wp_ace_js_output;

		if (post_password_required( $post )) {
			return $content;
		}

		if (empty($wp_ace_js_output)) {
			$wp_ace_js_output = array();
		}

		$selected_post_types 	= get_option('wp_ace_enabled_post_type');
		if ( !in_array($post->post_type, $selected_post_types)) {  

			return $content;
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-admin-code-editor-general.php';
		$general_settings = new Admin_Code_Editor_General($post->ID);

		if (is_home() && $general_settings->homeTemplateIsDisabled() ) {
			return $content;
		}
		if (is_front_page() && $general_settings->frontPageTemplateIsDisabled() ) {
			return $content;
		}
		if (is_archive() && $general_settings->archiveTemplateIsDisabled() ) {
			return $content;
		}
		if (is_search() && $general_settings->searchTemplateIsDisabled() ) {
			return $content;
		}

		if (!is_main_query()  && $general_settings->getOnlyDisplayInMainQueryStatus()) {
			return $content;
		}
		if (!in_the_loop() && $general_settings->getOnlyDisplayInLoopStatus() ) {
			return $content;
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-html-php.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-css.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-js.php';		
		
		$editor_args = array(
			'type' 					=> 'html-php',
			'host-post-id' 	=> $post->ID
		);
		$html_php_editor 	= new Admin_Code_Editor_Editor_HTML_PHP($editor_args);

		$editor_args = array(
			'type' 					=> 'css',
			'host-post-id' 	=> $post->ID
		);
		$css_editor 	= new Admin_Code_Editor_Editor_CSS($editor_args);

		$editor_args = array(
			'type' 					=> 'js',
			'host-post-id' 	=> $post->ID
		);
		$js_editor 	= new Admin_Code_Editor_Editor_JS($editor_args);

		$wp_ace_css_tag_output = '';

		if (!$general_settings->jsEditorIsDisabled()) {
			$wp_ace_js_output[$post->ID] = $js_editor->get_compiled_code();
		}
		

		if (!$general_settings->htmlEditorIsDisabled()) {
			$html_code_insert_position 	= $html_php_editor->get_code_output_position();
			$html 											= $html_php_editor->get_compiled_code();
			
			$html = '<div class="wp-ace--post-'. $post->ID .'">' . $html . '</div>';
			$html = do_shortcode($html);

			switch ($html_code_insert_position) {
				case 'before':
					$content =  $html . $content;
					break;
				case 'after':
					$content =  $content . $html;
					break;
			}
		} 
		wp_reset_postdata();
		$start_outer_content_wrapper = '<div class="wp-ace--outer-post-'. $post->ID .'">';
		$end_outer_content_wrapper = '</div>';

		$content = $start_outer_content_wrapper . $content . $end_outer_content_wrapper;
		if (!$general_settings->cssEditorIsDisabled()) {
			$wp_ace_css_tag_output 	= '<style id="wp-ace-css--post-' . $post->ID . '" >' . $css_editor->get_css_with_wrapper() . '</style>';
			$content 								= $wp_ace_css_tag_output . $content;
		}
		wp_reset_postdata();
		return $content;
	}	
}
