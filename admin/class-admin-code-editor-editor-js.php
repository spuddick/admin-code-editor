<?php 
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor.php';
class Admin_Code_Editor_Editor_JS extends Admin_Code_Editor_Editor

{
    function __construct() {
      parent::__construct();
			if (isset($param['type'])) {
	      $this->type = $param['type'];
	      
	      $keys = array();

				$keys['host-hash-meta-key'] = '_wp_ace_js_hash';
				$keys['code-id-meta-key'] 	= '_wp_ace_js_code_post_id';
				
				$post_type = 'wp-ace-js';
				$code_post_name_start = 'wp-ace-js-code-for-';
				$code_post_title_start = 'WP ACE JS code for Post ID: ';

	    }					        
    }


  public function initialize_from_post_request(){
		// called from save hook or ajax request to set variable data
		$this->pre_code = $_POST['wp-ace-js-pre-code']; // TODO: suitable filter for html content
		$this->field_height	= sanitize_text_field($_POST['wp-ace-js-field-height']);
		$this->preprocessor = sanitize_text_field($_POST['wp-ace-js-preprocessor']);
		$this->cursor_position 	= sanitize_text_field($_POST['wp-ace-js-cursor-position']);

	}

	private function get_current_hash() {
		if (empty($current_hash)) {
			$this->current_hash = md5($this->pre_code . $this->field_height . $this->preprocessor . $this->cursor_position);
		}
		return $this->current_hash;
	}

	private function get_stored_hash() {
		if (empty($this->stored_hash)) {
			$this->stored_hash = get_post_meta($post_id, '_wp_ace_html_php_hash', true);
		}
		return $this->stored_hash; 
	}

	public function load_admin_meta_data() {
		// load relevant metadata from host/code post id and assign to variables 

	}
	
	protected function additional_updates() {
		// js dependecy files
		// enqueue in header/footer
	}

	public function get_preprocessor() {
		$preprocessor = get_post_meta($this->code_post_id, '_wp_ace_preprocessor', true);
		if (!$preprocessor) {
			$preprocessor = get_option('_wp_ace_global_proprocessor', true);
			if (!$preprocessor) {
				$preprocessor = DEFAULT_PREPROCESSOR;
			}
		}

		return $preprocessor;
	}		
}

?>