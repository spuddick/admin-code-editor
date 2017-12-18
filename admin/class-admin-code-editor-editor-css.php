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
		
	private $css_with_wrapper, $isolation_mode;

	const DEFAULT_PREPROCESSOR = 'none';
	const DEFAULT_ISOLATION_MODE = 'html-editor';

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
			$this->keys['global_isolation_mode']= 'wp_ace_default_css_isolation_mode';
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
		$this->isolation_mode	= self::filterIsolationMode($_POST['wp-ace-css-isolation-mode']);
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

		$isolation_mode = $this->get_isolation_mode();
		switch($isolation_mode) {
			case 'full-web-page':

				update_post_meta($this->get_code_post_id(), '_wp_ace_compiled_css_with_wrapper', self::sanitizeCSS($this->get_compiled_code()) );

			break;
			case 'page-content-plus-html-editor':
				$compiled_code_with_wrapper =  '.wp-ace--outer-post-' . $this->host_post_id . ' { ' . $this->get_compiled_code() . ' } ';
				$compiled = $this->compile($compiled_code_with_wrapper, 'scss');

				switch ($compiled->status) {
					case 'success':
						update_post_meta($this->get_code_post_id(), '_wp_ace_compiled_css_with_wrapper', self::sanitizeCSS($compiled->compiled_code) );
					break;
				}	

			break;
			case 'html-editor':
				$compiled_code_with_wrapper =  '.wp-ace--post-' . $this->host_post_id . ' { ' . $this->get_compiled_code() . ' } ';
				$compiled = $this->compile($compiled_code_with_wrapper, 'scss');

				switch ($compiled->status) {
					case 'success':
						update_post_meta($this->get_code_post_id(), '_wp_ace_compiled_css_with_wrapper', self::sanitizeCSS($compiled->compiled_code) );
					break;
				}				

			break;
		}


		update_post_meta($this->get_code_post_id(), '_wp_ace_code_css_isolation_mode', $this->get_isolation_mode() );
	}

	/**
	 * Store isolation mode in revision data
	 * @since 1.3.0
	 */
	protected function additional_revision_data_store($latest_revision_id) {
		$isolation_mode_old = get_post_meta($this->get_code_post_id(), '_wp_ace_code_css_isolation_mode', true);
		add_metadata( 'post', $latest_revision_id, '_wp_ace_code_css_isolation_mode', $isolation_mode_old );
	}

	/**
	 * Strip out any style tags from the HTML body
	 * @since 1.0.0
	 * @return string HTML code with <style> tags removed
	 */
	private static function sanitizeCSS($css) {
		$filtered_css = wp_check_invalid_utf8( $css, true );
		$filtered_css = preg_replace("/<\s*\/\s*style\s*.*>/i", '', $filtered_css);

		return $filtered_css;
	}


	/**
	 * Get the CSS isolation mode of the post
	 * 
	 * @since 1.3.0
	 * @return string the CSS isolation mode
	 */
	public function get_isolation_mode() {
		
		if (empty($this->isolation_mode)) {
			$this->isolation_mode = get_post_meta($this->get_code_post_id(), '_wp_ace_code_css_isolation_mode', true);
			if (!$this->isolation_mode) {
				$this->isolation_mode = get_option($this->keys['global_isolation_mode'], self::DEFAULT_ISOLATION_MODE);
			}
		}
		return $this->isolation_mode;

	}

 
	/**
	 * Enforces allowable isolation mode values
	 * 
	 * @return isolation mode of CSS within context of allowable values
	 * @since 1.3.0
	 */
	protected static function filterIsolationMode($raw_mode) {
		$allowable_modes = array(
			'full-web-page',
			'page-content-plus-html-editor',
			'html-editor'
		);

		if (in_array($raw_mode, $allowable_modes)) {
			return $raw_mode;
		} else {
			return 'html-editor';
		}

	}

}

?>