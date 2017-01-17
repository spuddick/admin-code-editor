<?php 
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor.php';

/**
 * CSS Editor class for getting, setting, and manipulating CSS code associated with a post
 * 
 * @since 1.0.0
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/editor
 */
class Admin_Code_Editor_Editor_CSS extends Admin_Code_Editor_Editor {
		
	private $css_with_wrapper;

	const DEFAULT_PREPROCESSOR = 'none';


	/**
	 * Constructor
	 * @param array $param set of values passed to parent constructor 
	 * @return type
	 * @since 1.0.0
	 */
	function __construct($param) {
		parent::__construct($param);
		
		if (isset($param['type'])) {
			$this->type 												= $param['type'];
			$this->keys 												= array();
			$this->keys['host-hash-meta-key'] 	= '_wp_ace_css_hash';
			$this->keys['code-id-meta-key'] 		= '_wp_ace_css_code_post_id';
			$this->keys['global_preprocessor'] 	= 'wp_ace_default_css_preprocessor';
			$this->keys['has-changed'] 					= 'wp-ace--css--changed-flag';
			$this->post_type 										= 'wp-ace-css';
			$this->code_post_name_start 				= 'wp-ace-css-code-for-';
			$this->code_post_title_start 				= 'WP ACE CSS code for Post ID: ';
		}	
	}

	/**
	 * Called when a post is saved. Set values from POST request data
	 * @since 1.0.0
	 */
	public function initialize_from_post_request(){
		$this->pre_code = (empty($_POST['wp-ace-css-pre-code'])) ? ' ' : self::sanitizeCSS($_POST['wp-ace-css-pre-code']); 
		
		$this->field_height	= self::filterEditorHeight($_POST['wp-ace-css-field-height']);
		
		if (self::preprocessorIsValid($_POST['wp-ace-css-preprocessor'],'css')) {
			$this->preprocessor = sanitize_key($_POST['wp-ace-css-preprocessor']);
		} else {
			$this->preprocessor = self::DEFAULT_PREPROCESSOR;
		}
		
	}
	
	/**
	 * Get the set default preprocessor, or hardcoded default if not available
	 * @return string default preprocessor
	 * @since 1.0.0
	 */
	protected function get_default_preprocessor() {
		$temp_preprocessor = get_option('wp_ace_default_css_preprocessor', self::DEFAULT_PREPROCESSOR);
		
		if (self::preprocessorIsValid($temp_preprocessor, 'css')) {
			return $temp_preprocessor;
		} else {
			return self::DEFAULT_PREPROCESSOR;
		}		
		
	}
	
	/**
	 * Used for the front end output. CSS is wrapped in additional class to isolate styles to only HTML code for that post 
	 * @return string CSS prefixed with wrapper
	 * @since 1.0.0
	 */
	public function get_css_with_wrapper() {
		if (empty($this->css_with_wrapper)) {
			$this->css_with_wrapper = get_post_meta($this->get_code_post_id(), '_wp_ace_compiled_css_with_wrapper', true);
		}
		return $this->css_with_wrapper; 		
	}

	/**
	 * During post save, update fields specific to CSS code
	 * @since 1.0.0
	 */
	protected function additional_updates() {

		$compiled_code_with_wrapper =  '.wp-ace--post-' . $this->host_post_id . ' { ' . $this->get_compiled_code() . ' } ';
		$compiled = $this->compile($compiled_code_with_wrapper, 'scss');

		switch ($compiled->status) {
			case 'success':
				update_post_meta($this->get_code_post_id(), '_wp_ace_compiled_css_with_wrapper', self::sanitizeCSS($compiled->compiled_code) );
			break;
		}
	}


	private static function sanitizeCSS($css) {
		$filtered_css = wp_check_invalid_utf8( $css, true );
		$filtered_css = preg_replace("/<\s*\/\s*style\s*.*>/i", '', $filtered_css);

		return $filtered_css;
	}
 
}

?>