<?php


class Admin_Code_Editor_Editor {

	private $type, $host_post_id, $code_post_id, $code_post_title, $keys, $post_type, $code_post_name_start, $code_post_title_start, $stored_hash, $current_hash;
	protected $pre_code, $field_height, $preprocessor, $cursor_position;


	public function __construct($param) {
	
    if (isset($param['type'])) {
      $this->type = $param['type'];
      
      $keys = array();
      switch($this->type) {
				case 'html-php' :
					$keys['host-hash-meta-key'] = '_wp_ace_html_php_hash';
					$keys['code-id-meta-key'] 	= '_wp_ace_html_php_code_post_id';
					$post_type = 'wp-ace-html';
					$code_post_name_start = 'wp-ace-html-and-php-code-for-';
					$code_post_title_start = 'WP ACE HTML and PHP code for Post ID: ';
					
					break;
				case 'css' :
					$keys['host-hash-meta-key'] = '_wp_ace_html_php_hash';
					$keys['code-id-meta-key'] 	= '_wp_ace_html_php_code_post_id';
					
					$post_type = 'wp-ace-css';
					$code_post_name_start = 'wp-ace-css-code-for-';
					$code_post_title_start = 'WP ACE CSS code for Post ID: ';
					break;
				case 'js' :
					$keys['host-hash-meta-key'] = '_wp_ace_js_hash';
					$keys['code-id-meta-key'] 	= '_wp_ace_js_code_post_id';
					
					$post_type = 'wp-ace-js';
					$code_post_name_start = 'wp-ace-js-code-for-';
					$code_post_title_start = 'WP ACE JS code for Post ID: ';
					break;
			};
    } else {
    	// throw exception
    }
    if (isset($param['code-post-id'])) {
        $this->code_post_id = $param['code-post-id'];
    }
    if (isset($param['pre-code'])) {
        $this->pre_code = $param['pre-code'];
    }
    if (isset($param['field-height'])) {
        $this->field_height = $param['field-height'];
    }
    if (isset($param['preprocessor'])) {
        $this->preprocessor = $param['preprocessor'];
    }
    if (isset($param['cursor-position'])) {
        $this->cursor_position = $param['cursor-position'];
    }
    if (isset($param['host-post-id'])) {
      $this->host_post_id = $param['host-post-id'];
    } else {
    	// throw exception
    }

	}

	public function initialize_from_post_request(){
		// called from save hook or ajax request to set variable data
				$this->pre_code = $_POST['wp-ace-html-php-pre-code'], // TODO: suitable filter for html content
				
				$this->field_height	= sanitize_text_field($_POST['wp-ace-html-php-field-height']),
				
				$this->preprocessor = sanitize_text_field($_POST['wp-ace-html-php-preprocessor']),
				
				$this->cursor_position 	= sanitize_text_field($_POST['wp-ace-html-php-cursor-position'])



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

	public function get_front_end_content_vars() {
		// variables related to displaying rendered front end content
		
		$ret = array(
				'compiled-code'				=> $this->get_compiled_code(),
				'disable-wpautop'			=> $this->get_disable_wpautop_status(),
				'disabled-templates'	=> $this->get_disabled_templates(),
				'code-position'				=> $this->get_code_position()
			);

		return $ret;
	}

	public function get_admin_vars() {
		// variables related to displaying admin editor

		$ret = array(
				'compiled-code'				=> $this->get_compiled_code(),
				'disable-wpautop'			=> $this->get_disable_wpautop_status(),
				'disabled-templates'	=> $this->get_disabled_templates(),
				'code-position'				=> $this->get_code_position(),
				'pre_code'						=> $this->get_pre_code(),
				'pre_code_status'			=> $this->get_pre_code_status(),
				'pre_code_error_msg'	=> $this->get_pre_code_error_msg(),
				'field_height', 			=> $this->get_field_height(),
				'preprocessor', 			=> $this->get_preprocessor(),
				'cursor_position'			=> $this->get_cursor_position()
			);

		return $ret;
		
	}

	private function get_disabled_templates() {
		$ret = array();

		if (empty($this->disabled_templates)) {
			$this->disabled_templates = get_post_meta($this->code_post_id, '_wp_ace_disable_templates', true);
		}

		return $this->disabled_templates;
	}

	private function get_code_post_id() {
			
			if (empty($this->code_post_id)) {
				// if no existing post for code, create one

				$this->code_post_id = get_post_meta($this->host_post_id, $this->keys['host-code-meta-key'], true);

				if (empty($this->code_post_id)) {
					$code_post = array(
						  'post_name'    	=> 	$code_name_text, 
						  'post_status'   => 	'publish',
						  'post_type'			=> 	$post_type,
						  'post_title'		=> 	$code_title_text
						);
	 
					$this->code_post_id = wp_insert_post( $code_post );

					update_post_meta($this->host_post_id, $this->keys['host-code-meta-key'], $this->code_post_id);					
				}

			}

			return $this->code_post_id;
	}

	public function get_compiled_code() {
		if (empty($compiled_code)){
			return;
		} else {
			return get_post_meta($this->get_code_post_id, '_wp_ace_compiled');
		}
	}

	public function get_editor_cursor_position() {
		$this->code_position = get_post_meta($this->code_post_id, '_wp_ace_code_position', true);
		if (!$this->code_position) {
			$this->code_position = get_option( '_wp_ace_global_code_position', true);
			if (!$this->code_position) {
				$this->code_position = DEFAULT_CODE_POSITION;
			}
		}

		return $preprocessor;
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




	public function update_code() {
			
			if ($this->get_current_hash() == $this->get_stored_hash()) {
				return;
			} else {

				update_post_meta($this->host_post_id, $this->keys['code-id-meta-key'], $this->get_current_hash());

			}

		// get the appropriate post name text depending on whether this is the initial post or a revision
		$this->code_name_text = $code_post_name_start . $this->host_post_id;
		$this->host_title = get_the_title($this->host_post_id);
		$this->code_title_text = $code_post_title_start . $this->host_post_id . ' (' . $this->host_title . ')';

			// if an existing post for code exists, update it
		  $code_post_settings = array(
	      'ID'           	=> 	$this->get_code_post_id,
	      'post_name'    	=> 	$post_name_text,
			  'post_content'  => 	$pre,
			  'post_status'   => 	'publish',
			  'post_type'			=> 	$post_type,
				'post_title'		=>	$post_title_text
		  );
			
			$this->code_post_id = wp_update_post( $code_post_settings, true );						  
			
			if (is_wp_error($this->code_post_id)) {
				$errors = $this->code_post_id->get_error_messages();
				foreach ($errors as $error) {
					echo $error;
				}
			} else {
				$latest_revision = current(wp_get_post_revisions($this->code_post_id));

				if ($latest_revision) {
				   // do stuff with the latest revision
				   // $latest_revision->ID will contain the latest revision
					$preprocessor_old = get_post_meta($this->code_post_id, '_wp_ace_preprocessor', true);
					$editor_height_old = get_post_meta($this->code_post_id, '_wp_ace_editor_height', true);
					
					add_metadata( 'post', $latest_revision->ID, '_wp_ace_preprocessor', $preprocessor_old );
					add_metadata( 'post', $latest_revision->ID, '_wp_ace_editor_height', $editor_height_old );					   
				}
			}
			

			// compile pre code and save it as meta data for the associated code post
			$compiled = $this->compile(); // TODO: Write compile function with return vals
			update_post_meta($code_post_id, '_wp_ace_status', $compiled->status );
			
			// update compile error status and message
			if ($compiled->status != 'error') {
				update_post_meta($this->code_post_id, '_wp_ace_compiled', $compiled->compiled_code );
				delete_post_meta($this->code_post_id, '_wp_ace_error_msg');
			} else {
				update_post_meta($this->code_post_id, '_wp_ace_error_msg', $compiled->error_msg );
			}

			// update other basic meta data
			update_post_meta($code_post_id, '_wp_ace_editor_height', $editor_height );
			update_post_meta($code_post_id, '_wp_ace_preprocessor', $preprocessor );
			update_post_meta($code_post_id, '_wp_ace_insertion_pos', $editor_cursor_position );

			return;
	}

	private function compile() {
		$ret = new stdClass();
		
		$ret->compiled_code = '';
		$ret->status = '';
		$ret->error_msg = '';

		if ( empty($pre_code) ) {
			$ret->status = 'empty';
		} else {
			try {
					
				switch($preprocessor) {
					case 'scss' :
						// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/scss-compiler.php';

						$scss = new scssc();
						$compiled_code = $scss->compile($pre_code);
						$ret->compiled_code = trim($compiled_code);
						$ret->status = 'success';
						break;
					case 'less' :
						// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/less-compiler.php';
						
						break;
					case 'stylus' :
						// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/stylus-compiler.php';


						break;
					case 'haml' :
						// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/haml-compiler.php';


						break;
					case 'markdown' :
						// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/markdown-compiler.php';
						

						break;
				}

			}
			catch(Exception $e) {
			  $ret->status = 'error';
			  $ret->error_msg = $e->getMessage();
			}			
		}

		return $ret;
	}




}
