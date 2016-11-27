<?php 
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor.php';
class Admin_Code_Editor_Editor_CSS extends Admin_Code_Editor_Editor

{
    
	private $css_with_wrapper;

	const DEFAULT_PREPROCESSOR = 'none';
  function __construct($param) {
    parent::__construct($param);
		
		if (isset($param['type'])) {
      $this->type = $param['type'];
      
      $this->keys = array();

			$this->keys['host-hash-meta-key'] = '_wp_ace_css_hash';
			$this->keys['code-id-meta-key'] 	= '_wp_ace_css_code_post_id';
			$this->keys['global_preprocessor'] 	= 'wp_ace_default_css_preprocessor';
			$this->post_type = 'wp-ace-css';
			$this->code_post_name_start = 'wp-ace-css-code-for-';
			$this->code_post_title_start = 'WP ACE CSS code for Post ID: ';

    }	
      
  }



  public function initialize_from_post_request(){
		// called from save hook or ajax request to set variable data
		$this->pre_code = $_POST['wp-ace-css-pre-code']; // TODO: suitable filter for html content
		$this->field_height	= sanitize_text_field($_POST['wp-ace-css-field-height']);
		$this->preprocessor = sanitize_text_field($_POST['wp-ace-css-preprocessor']);

	}
	
	protected function get_default_preprocessor() {
		return get_option('wp_ace_default_css_preprocessor', self::DEFAULT_PREPROCESSOR);

	}

	protected function get_current_hash() {
		if (empty($current_hash)) {
			$this->current_hash = md5($this->pre_code . $this->field_height . $this->preprocessor . $this->cursor_position);
		}
		return $this->current_hash;
	}

	protected function get_stored_hash() {
		if (empty($this->stored_hash)) {
			$this->stored_hash = get_post_meta($this->host_post_id, '_wp_ace_html_php_hash', true);
		}
		return $this->stored_hash; 
	}
	
	public function load_admin_meta_data() {
		// load relevant metadata from host/code post id and assign to variables 

	}

	public function get_css_with_wrapper() {
		if (empty($this->css_with_wrapper)) {
			$this->css_with_wrapper = get_post_meta($this->get_code_post_id(), '_wp_ace_compiled_css_with_wrapper', true);
		}
		return $this->css_with_wrapper; 		
	}

	protected function additional_updates() {
		// css dependecy files
		// enqueue in header/footer
		// 
		$compiled_code_with_wrapper =  '.wp-ace-html--post-' . $this->get_code_post_id() . ' { ' . $this->get_compiled_code() . ' } ';
		$compiled = $this->compile($compiled_code_with_wrapper, 'scss');

		switch ($compiled->status) {

			case 'success':
				update_post_meta($this->get_code_post_id(), '_wp_ace_compiled_css_with_wrapper', $compiled->compiled_code );

			break;

		}
	}

 
}

?>