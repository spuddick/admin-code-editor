<?php 
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor.php';
class Admin_Code_Editor_Editor_HTML_PHP extends Admin_Code_Editor_Editor

{
    function __construct($param) {
        
      parent::__construct($param);
      if (isset($param['type'])) {
	      $this->type = $param['type'];
	      
	      $keys = array();

				$keys['host-hash-meta-key'] = '_wp_ace_html_php_hash';
				$keys['code-id-meta-key'] 	= '_wp_ace_html_php_code_post_id';
				$post_type = 'wp-ace-html';
				$code_post_name_start = 'wp-ace-html-and-php-code-for-';
				$code_post_title_start = 'WP ACE HTML and PHP code for Post ID: ';

	    }

    }

	public function initialize_from_post_request(){
		// called from save hook or ajax request to set variable data
		$this->pre_code = $_POST['wp-ace-html-php-pre-code']; // TODO: suitable filter for html content
		$this->field_height	= sanitize_text_field($_POST['wp-ace-html-php-field-height']);
		$this->preprocessor = sanitize_text_field($_POST['wp-ace-html-php-preprocessor']);
		$this->cursor_position 	= sanitize_text_field($_POST['wp-ace-html-php-cursor-position']);

	}

	protected function get_current_hash() {
		if (empty($current_hash)) {
			$this->current_hash = md5($this->pre_code . $this->field_height . $this->preprocessor . $this->cursor_position);
		}
		return $this->current_hash;
	}

	protected function get_stored_hash() {
		if (empty($this->stored_hash)) {
			$this->stored_hash = get_post_meta($post_id, '_wp_ace_html_php_hash', true);
		}
		return $this->stored_hash; 
	}

	public function load_admin_meta_data() {
		// load relevant metadata from host/code post id and assign to variables 

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

	public function get_disable_wpautop_status() {
		$disable_wpautop = get_post_meta($this->code_post_id, '_wp_ace_disable_wpautop', true);
		if (!$disable_wpautop) {
			$disable_wpautop = get_option('_wp_ace_global_wpautop', true);
			if (!$disable_wpautop) {
				$disable_wpautop = DEFAULT_DISABLE_WPAUTOP;
			}
		}

		return $disable_wpautop;		
	}


}

?>