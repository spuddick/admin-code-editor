<?php 
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor.php';

/**
 * JavaScript Editor class for getting, setting, and manipulating JavaScript code (and associated settings), associated with a post
 * 
 * @since 1.0.0
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/editor
 */
class Admin_Code_Editor_Editor_JS extends Admin_Code_Editor_Editor {
	
	const DEFAULT_PREPROCESSOR = 'none';
	const DEFAULT_INLCUDE_JQUERY = 1;

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
			$this->keys['host-hash-meta-key'] 	= '_wp_ace_js_hash';
			$this->keys['code-id-meta-key'] 		= '_wp_ace_js_code_post_id';
			$this->keys['global_preprocessor'] 	= 'wp_ace_default_js_preprocessor';
			$this->keys['has-changed'] 					= 'wp-ace--js--changed-flag';
			$this->post_type 										= 'wp-ace-js';
			$this->code_post_name_start 				= 'wp-ace-js-code-for-';
			$this->code_post_title_start 				= 'WP ACE JS code for Post ID: ';
		}					        
	}

	/**
	 * Called when a post is saved. Set values from POST request data
	 * @since 1.0.0
	 */
	public function initialize_from_post_request(){

		$this->pre_code 			= (empty($_POST['wp-ace-js-pre-code'])) ? ' ' : $_POST['wp-ace-js-pre-code']; 
		$this->field_height		= sanitize_text_field($_POST['wp-ace-js-field-height']);
		$this->preprocessor 	= sanitize_text_field($_POST['wp-ace-js-preprocessor']);

	}

	/**
	 * Get the set default preprocessor, or hardcoded default if not available
	 * @return string default preprocessor
	 * @since 1.0.0
	 */
	protected function get_default_preprocessor() {
		return get_option('wp_ace_default_js_preprocessor', self::DEFAULT_PREPROCESSOR);
	}

	/**
	 * During post save, update fields specific to JS code (none needed, but required since inherits from abstract class which defines it)
	 * @since 1.0.0
	 */
	protected function additional_updates() {

	}

	/**
	 * Get include jQuery status. Currently this function is irrelivent. WP ACE automatically includes jQuery on every front end page. May be fixed in future.
	 * @return string
	 */
	public function get_include_jquery_status() {
		$this->include_jquery_status = get_post_meta($this->get_code_post_id(), '_wp_ace_default_include_jquery', true);
		
		if (!$this->include_jquery_status) {
			$this->include_jquery_status = get_option('wp_ace_default_include_jquery', self::DEFAULT_INLCUDE_JQUERY);

		}

		return $this->include_jquery_status;		
	}
	
}

?>