<?php 
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor.php';

/**
 * HTML Editor class for getting, setting, and manipulating HTML code (and associated settings), associated with a post
 * 
 * @since 1.0.0
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/editor
 */
class Admin_Code_Editor_Editor_HTML_PHP extends Admin_Code_Editor_Editor {
	
	const DEFAULT_PREPROCESSOR 								= 'none';
	const DEFAULT_CODE_OUTPUT_POSITION 				= 'before';
	const DEFAULT_ALLOW_SEARCHABLE_HTML 			= 1;
	private static $ALLOWABLE_OUTPUT_POSITION = array('before', 'after');

	private $wpautop_is_disabled_status, $code_output_position, $allow_searchable_html;

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
			$this->keys['host-hash-meta-key'] 	= '_wp_ace_html_php_hash';
			$this->keys['code-id-meta-key'] 		= '_wp_ace_html_php_code_post_id';
			$this->keys['filtered-html-meta-key'] 		= '_wp_ace_html_php_filtered_html';
			$this->keys['global_preprocessor'] 	= 'wp_ace_default_html_preprocessor';
			$this->keys['has-changed'] 					= 'wp-ace--html-php--changed-flag';
			$this->post_type 										= 'wp-ace-html';
			$this->code_post_name_start 				= 'wp-ace-html-and-php-code-for-';
			$this->code_post_title_start 				= 'WP ACE HTML and PHP code for Post ID: ';

		}

	}

	/**
	 * Called when a post is saved. Set values from POST request data
	 * @since 1.0.0
	 */
	public function initialize_from_post_request() {
		
		if (isset($_POST['wp-ace-html-php-pre-code'])) {
			$this->pre_code = (empty($_POST['wp-ace-html-php-pre-code'])) ? ' ' : $_POST['wp-ace-html-php-pre-code']; 
		}
		if (isset($_POST['wp-ace-html-php-field-height'])) {
			$this->field_height	= self::filterEditorHeight($_POST['wp-ace-html-php-field-height']);
		}
		if (isset($_POST['wp-ace-html-php-preprocessor'])) {
			if (self::preprocessorIsValid($_POST['wp-ace-html-php-preprocessor'], 'html')) {
				$this->preprocessor = sanitize_key($_POST['wp-ace-html-php-preprocessor']);
			} else {
				$this->preprocessor = self::DEFAULT_PREPROCESSOR;
			}
		}
		if (isset($_POST['wp-ace-html-php-code-position'])) {
			if (self::isValidOutputPosition($_POST['wp-ace-html-php-code-position'])) {
				$this->code_output_position = sanitize_text_field($_POST['wp-ace-html-php-code-position']);
			} else {
				$this->code_output_position = self::DEFAULT_CODE_OUTPUT_POSITION;
			}
		}

		if (isset($_POST['wp-ace-html-php-allow-searchable-html'])) {
			if ($_POST['wp-ace-html-php-allow-searchable-html']) {
				$this->allow_searchable_html = 1;
			} else {
				$this->allow_searchable_html = 0;
			}
		} else {
			$this->allow_searchable_html = 0;
		}
	}
	
	/**
	 * Get the set default preprocessor, or hardcoded default if not available
	 * @return string default preprocessor
	 * @since 1.0.0
	 */
	protected function get_default_preprocessor() {

		$temp_preprocessor = get_option('wp_ace_default_html_preprocessor', self::DEFAULT_PREPROCESSOR);
		
		if (self::preprocessorIsValid($temp_preprocessor, 'html')) {
			return $temp_preprocessor;
		} else {
			return self::DEFAULT_PREPROCESSOR;
		}		
	}


	/**
	 * Get the HTML Searchable Status
	 * @since new_version
	 * @return  boolean HTML searchable status flag
	 */
	public function get_allow_searchable_html_status() {
		if (is_null($this->allow_searchable_html)) {
			$this->allow_searchable_html = get_post_meta($this->get_code_post_id(), '_wp_ace_html_searchable', true);
			if (is_null($this->allow_searchable_html) || !strlen($this->allow_searchable_html) ) {
				$this->allow_searchable_html = get_option( 'wp_ace_default_allow_searchable_html', self::DEFAULT_ALLOW_SEARCHABLE_HTML);
			}
		}
		return $this->allow_searchable_html;
	}


	/**
	 * During post save, update fields specific to HTML code
	 * @since 1.0.0
	 */
	protected function additional_updates() {

		update_post_meta($this->get_code_post_id(), '_wp_ace_code_output_position', $this->get_code_output_position() );

		update_post_meta($this->get_code_post_id(), '_wp_ace_html_searchable', $this->get_allow_searchable_html_status());

		if ($this->get_allow_searchable_html_status()) {
			$compiled_code = $this->get_compiled_code();	
			update_post_meta($this->host_post_id, $this->keys['filtered-html-meta-key'], strip_tags($compiled_code) );
		} else {
			delete_post_meta($this->host_post_id, $this->keys['filtered-html-meta-key']);	
		}
	}

	/**
	 * Gets the position the HTML code should be in relation to the post content, above or below
	 * @return string
	 * @since 1.0.0
	 */
	public function get_code_output_position() {
		if (!$this->code_output_position) {
			$this->code_output_position = get_post_meta($this->get_code_post_id(), '_wp_ace_code_output_position', true);
			if (!$this->code_output_position) {
				$this->code_output_position = get_option( 'wp_ace_default_html_position', self::DEFAULT_CODE_OUTPUT_POSITION);
			}
		}
		if (self::isValidOutputPosition($this->code_output_position)) {
			return $this->code_output_position;
		} else {
			return self::DEFAULT_CODE_OUTPUT_POSITION;
		}
		
	}


	/**
	 * Check to see if output position value is within allowable values
	 * @return boolean
	 * @since 1.0.0
	 */
	private static function isValidOutputPosition($position) {
		if (in_array($position, self::$ALLOWABLE_OUTPUT_POSITION)) {
			return true;
		} else {
			return false;
		}
	}

}

?>