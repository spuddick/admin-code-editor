<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
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
 * @author     Your Name <email@example.com>
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

		wp_enqueue_script( $this->admin_code_editor, plugin_dir_url( __FILE__ ) . 'js/admin-code-editor-public.js', array( 'jquery' ), $this->version, false );

	}



	public function insert_script_in_head($from_template = false) {
		if (!$from_template) {
			wp_reset_postdata(); 
		}
    global $post;
    $output = '';

 
		//$js_code = get_post_meta( $post->ID, '_js_code', true ); 
		$compiled_css_code = get_post_meta( $post->ID, '_compiled_css', true );
		

		if ( empty($compiled_css_code) ) {
			$css_code = get_post_meta( $post->ID, '_css_code', true );
			$compiled_css_code = self::compile_sass($post->ID, $css_code);
		}   

		if (ICL_LANGUAGE_CODE == 'fr') {
			$en_post_id = icl_object_id($post->ID, get_post_type( $post->ID ), false, 'en');
			
			if (empty($compiled_css_code)) {
				// if there is no compiled french CSS... 

				// get the english compiled CSS
				$compiled_css_code = get_post_meta( $en_post_id, '_compiled_css', true );

				if ( empty($compiled_css_code) ) {
					// if there is no english compiled CSS, we can try to see if there is any SCSS that needs to be compiled
					$css_code = get_post_meta( $en_post_id, '_css_code', true );
					$compiled_css_code = self::compile_sass($en_post_id, $css_code);
				}  
			}

		} 

		if (!empty($compiled_css_code)) {
			$output = '<style  id="admin-code-highlight-style"  >' .  $compiled_css_code . '</style>';
		}


		echo $output;
		
	}

	public function insert_script_in_footer($from_template = false) {
		if (!$from_template) {
			wp_reset_postdata(); 
		}
		
    global $post;
    $output = '';

		if (ICL_LANGUAGE_CODE == 'fr') {
			$en_post_id = icl_object_id($post->ID, get_post_type( $post->ID ), false, 'en');
		} else {
			$en_post_id = $post->ID;
		}
 
		$js_code = get_post_meta( $en_post_id, '_js_code', true ); 


		if (!empty($js_code)) {
			$output = '<script>//<![CDATA[' . "\r\n" . $js_code . "\r\n" . '//]]></script>';
		}

		echo $output;
		
	}

	public function get_html_content() {
		
    global $post;
    $output = '';

    /*
		$code_insert_mode = get_post_meta( $post->ID, '_code_insert_mode', true );
		if (empty($code_insert_mode)) {
			$code_insert_mode = 'append_bottom';
		}
		*/

		$output = '';
		 	
   	$html_code = get_post_meta( $post->ID, '_html_code', true );

		if (!empty($html_code)) {
			$output .= $html_code;
		}

		echo do_shortcode($output);
		
	}

	function wp_ace_the_content($content) {
  	global $post;
  	$selected_post_types 	= get_option('wpcr_post_types');
		
		if ( !in_array($post->post_type, $selected_post_types)) {  
			return wpautop($content);
		}
		
		return $content;

	}


	function insert_ace_code_in_page($content){
    // The different types of code (HTML, CSS, Javascript) are appended after the regular page content (using the wordpress function the_content() ).
    // We hook into the 'the_content' filter to acheive this.

    global $post, $wp_ace_js_output, $wp_ace_css_output;

    if (!isset($wp_ace_js_output)) {
    	$wp_ace_js_output = array();
    }

    if (!isset($wp_ace_css_output)) {
    	$wp_ace_css_output = array();
    }

  	$selected_post_types 	= get_option('wpcr_post_types');
		if ( !in_array($post->post_type, $selected_post_types)) {  

			return $content;
		}

		$disabled_templates 					= get_post_meta($post->ID, '_wp_ace_disabled_templates', true);
		if (!$disabled_templates ) {
			$disabled_templates = array();
		}
		if (is_home() && in_array('home', $disabled_templates) ) {
			return $content;
		}
		if (is_front_page() && in_array('front-page', $disabled_templates) ) {
			return $content;
		}
		if (is_archive() && in_array('archives', $disabled_templates) ) {
			return $content;
		}
		if (is_search() && in_array('search', $disabled_templates) ) {
			return $content;
		}


		$only_display_in_loop 				= get_post_meta($post->ID, '_wp_ace_display_only_in_loop', true);
		$only_display_in_main_query 	= get_post_meta($post->ID, '_wp_ace_display_only_in_main_query', true);		
		if (!is_main_query()  && $only_display_in_main_query) {
			return $content;
		}
		if (!in_the_loop() && $only_display_in_loop ) {
			return $content;
		}


		// is private or protected post
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-html-php.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-css.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor-js.php';		
		$editor_args = array(
			'host-post-id' => $post->ID
		);
		$html_php_editor 	= new Admin_Code_Editor_Editor_HTML_PHP($editor_args);

		$editor_args = array(
			'host-post-id' => $post->ID
		);
		$css_editor 	= new Admin_Code_Editor_Editor_CSS($editor_args);

		$editor_args = array(
			'host-post-id' => $post->ID
		);
		$js_editor 	= new Admin_Code_Editor_Editor_JS($editor_args);


		array_push($wp_ace_js_output[$post->ID], $js_editor->get_compiled_code());
		array_push($wp_ace_css_output[$post->ID], $css_editor->get_css_with_wrapper());

		$html_code_insert_position 	= $html_php_editor->get_code_output_position();
		$wp_autop_disable_status 		= $html_php_editor->get_disable_wpautop_status();
		$html 											= $html_php_editor->get_compiled_code();
		
		$html = '<div class="wp-ace-css--post-'. $post->ID .'">' . $html . '</div>';

		$content = wpautop($content);
		if (!$wp_autop_disable_status) {
			$html = wpautop($html);
		}
		$html = do_shortcode($html);

		switch ($html_code_insert_position) {
	    case 'before':

			  $content =  $html . $content;

        break;
	    case 'after':

	    	$content =  $content . $html;

        break;
		}
		
		return $content;

	}	

}
