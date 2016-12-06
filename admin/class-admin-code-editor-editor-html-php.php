<?php 
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-editor.php';
class Admin_Code_Editor_Editor_HTML_PHP extends Admin_Code_Editor_Editor {
  
  	const DEFAULT_DISABLE_WPAUTOP = 1;
  	const DEFAULT_PREPROCESSOR = 'none';
  	const DEFAULT_CODE_OUTPUT_POSITION = 'before';

  	private $wpautop_disabled, $code_output_position;

    function __construct($param) {
        
      parent::__construct($param);
      if (isset($param['type'])) {
	      $this->type = $param['type'];
	      
	      $this->keys = array();

				$this->keys['host-hash-meta-key'] = '_wp_ace_html_php_hash';
				$this->keys['code-id-meta-key'] 	= '_wp_ace_html_php_code_post_id';
				$this->keys['global_preprocessor'] 	= 'wp_ace_default_html_preprocessor';
				$this->keys['has-changed'] = 'wp-ace--html--has-changed';
				$this->post_type = 'wp-ace-html';
				$this->code_post_name_start = 'wp-ace-html-and-php-code-for-';
				$this->code_post_title_start = 'WP ACE HTML and PHP code for Post ID: ';

	    }

    }

	public function initialize_from_post_request() {
		// called from save hook or ajax request to set variable data
		
		if (isset($_POST['wp-ace-html-php-pre-code'])) {
			$this->pre_code 				= (empty($_POST['wp-ace-html-php-pre-code'])) ? ' ' : $_POST['wp-ace-html-php-pre-code']; 
		}
		if (isset($_POST['wp-ace-html-php-field-height'])) {
			$this->field_height			= sanitize_text_field($_POST['wp-ace-html-php-field-height']);
		}
		if (isset($_POST['wp-ace-html-php-preprocessor'])) {
			$this->preprocessor 		= sanitize_text_field($_POST['wp-ace-html-php-preprocessor']);
		}

		if (isset($_POST['wp-ace-html-php-code-output-position'])) {
			$this->code_output_position 	= sanitize_text_field($_POST['wp-ace-html-php-code-output-position']);
		}
		if (isset($_POST['wp-ace-html-php-disable-wpautop'])) {
			$this->wpautop_disabled 	= sanitize_text_field($_POST['wp-ace-html-php-disable-wpautop']);
		} else {
			$this->wpautop_disabled 	= 0;
		}

	}
	
	protected function get_default_preprocessor() {
		return get_option('wp_ace_default_html_preprocessor', self::DEFAULT_PREPROCESSOR);

	}

	protected function get_current_hash() {
		if (empty($this->current_hash)) {
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

	protected function additional_updates() {
		
		update_post_meta($this->code_post_id, '_wp_ace_wpautop_is_disabled', $this->get_disable_wpautop_status() );
		update_post_meta($this->code_post_id, '_wp_ace_code_output_position', $this->get_code_output_position() );
	}

	public function get_code_output_position() {
		if (!$this->code_output_position) {
			$this->code_output_position = get_post_meta($this->get_code_post_id(), '_wp_ace_code_output_position', true);
			if (!$this->code_output_position) {
				$this->code_output_position = get_option( 'wp_ace_default_html_position', self::DEFAULT_CODE_OUTPUT_POSITION);

			}
		}
		return $this->code_output_position;
	}

	public function get_disable_wpautop_status() {
		$this->wpautop_is_disabled_status = get_post_meta($this->get_code_post_id(), '_wp_ace_disable_wpautop', true);
		
		if (!$this->wpautop_is_disabled_status) {
			$this->wpautop_is_disabled_status = get_option('wp_ace_default_disable_wpautop', self::DEFAULT_DISABLE_WPAUTOP);

		}

		return $this->wpautop_is_disabled_status;		
	}


}

?>