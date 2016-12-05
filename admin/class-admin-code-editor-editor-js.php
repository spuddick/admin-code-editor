<?php 
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor.php';
class Admin_Code_Editor_Editor_JS extends Admin_Code_Editor_Editor

{
    const DEFAULT_PREPROCESSOR = 'none';
    const DEFAULT_INLCUDE_JQUERY = 1;

    function __construct($param) {
      parent::__construct($param);
			if (isset($param['type'])) {
	      $this->type = $param['type'];
	      
	      $this->keys = array();

				$this->keys['host-hash-meta-key'] = '_wp_ace_js_hash';
				$this->keys['code-id-meta-key'] 	= '_wp_ace_js_code_post_id';
				$this->keys['global_preprocessor'] 	= 'wp_ace_default_js_preprocessor';

				$this->post_type = 'wp-ace-js';
				$this->code_post_name_start = 'wp-ace-js-code-for-';
				$this->code_post_title_start = 'WP ACE JS code for Post ID: ';

	    }					        
    }


  public function initialize_from_post_request(){
		// called from save hook or ajax request to set variable data
		$this->pre_code = (empty($_POST['wp-ace-js-pre-code'])) ? ' ' : $_POST['wp-ace-js-pre-code']; 
		$this->field_height	= sanitize_text_field($_POST['wp-ace-js-field-height']);
		$this->preprocessor = sanitize_text_field($_POST['wp-ace-js-preprocessor']);

	}

	protected function get_default_preprocessor() {
		return get_option('wp_ace_default_js_preprocessor', self::DEFAULT_PREPROCESSOR);

	}

	protected function get_current_hash() {
		if (empty($current_hash)) {
			$this->current_hash = md5($this->pre_code . $this->field_height . $this->preprocessor . $this->cursor_position);
		}
		return $this->current_hash;
	}

	protected function get_stored_hash() {
		if (empty($this->stored_hash)) {
			$this->stored_hash = get_post_meta($this->host_post_id, '_wp_ace_js_hash', true);
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

	public function get_include_jquery_status() {
		$this->include_jquery_status = get_post_meta($this->get_code_post_id(), '_wp_ace_default_include_jquery', true);
		
		if (!$this->include_jquery_status) {
			$this->include_jquery_status = get_option('wp_ace_default_include_jquery', self::DEFAULT_INLCUDE_JQUERY);

		}

		return $this->include_jquery_status;		
	}
	
}

?>