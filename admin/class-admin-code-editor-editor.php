<?php


abstract class Admin_Code_Editor_Editor {

	const DEFAULT_EDITOR_HEIGHT = 400;
	const DEFAULT_CURSOR_POSITION = 0;
	protected  $code_post_title, $keys, $post_type, $code_post_name_start, $code_post_title_start, $stored_hash, $current_hash;
	protected $host_post_id, $code_post_id, $pre_code, $field_height, $preprocessor, $cursor_position;
	protected $pre_code_compile_error_msg, $pre_code_compile_status;

	public function __construct($param) {
	
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

	abstract protected function get_current_hash();
	abstract protected function get_stored_hash();
	abstract protected function additional_updates();
	
	abstract public function initialize_from_post_request();
	//abstract public function get_preprocessor();

	public function get_disabled_templates() {
		$ret = array();

		if (empty($this->disabled_templates)) {
			$this->disabled_templates = get_post_meta($this->code_post_id, '_wp_ace_disable_templates', true);
		}

		return $this->disabled_templates;
	}

	private function get_code_name_text() {
		return $this->code_post_name_start . $this->host_post_id;
	}

	private function get_code_title_text() {
		return $this->code_post_title_start . $this->host_post_id;
	}

	public function get_code_post_id() {
			
		if (empty($this->code_post_id)) {
			// if no existing post for code, create one

			$this->code_post_id = get_post_meta($this->host_post_id, $this->keys['code-id-meta-key'], true);

			if (empty($this->code_post_id)) {
				$code_post = array(
					  'post_name'    	=> 	$this->get_code_name_text(), 
					  'post_status'   => 	'publish',
					  'post_type'			=> 	$this->post_type,
					  'post_title'		=> 	$this->get_code_title_text()
					);
 
				$this->code_post_id = wp_insert_post( $code_post );

				update_post_meta($this->host_post_id, $this->keys['code-id-meta-key'], $this->code_post_id);					
			}

		}

		return $this->code_post_id;
	}

	public function get_preprocessor() {
		if (empty($this->preprocessor)) {
			$this->preprocessor = get_post_meta($this->get_code_post_id(), '_wp_ace_preprocessor', true);
			if (!$this->preprocessor) {
				$this->preprocessor = get_option($this->keys['global_preprocessor'], self::DEFAULT_PREPROCESSOR);

			}
		}
		return $this->preprocessor;
	}

	public function get_compiled_code() {
		if (empty($compiled_code)){
			return;
		} else {
			return get_post_meta($this->get_code_post_id(), '_wp_ace_compiled');
		}
	}

	public function get_pre_code() {
		
		if (!$this->pre_code) {
			$pre_code_post = get_post($this->get_code_post_id());
			$this->pre_code = $pre_code_post->post_content;			
		}

		return $this->pre_code;
	}

	public function get_editor_cursor_position() {
		if (!$this->cursor_position) {
			$this->cursor_position = get_post_meta($this->get_code_post_id(), '_wp_ace_cursor_position', true);
			if (!$this->cursor_position) {
				$this->cursor_position = get_option( '_wp_ace_global_cursor_position', self::DEFAULT_CURSOR_POSITION);

			}
		}
		return $this->cursor_position;
	}

	public function get_editor_height() {
		if (!$this->field_height) {
			$this->field_height = get_post_meta($this->get_code_post_id(), '_wp_ace_editor_height', true);
			if (!$this->field_height) {
				$this->field_height = get_option('_wp_ace_global_editor_height', self::DEFAULT_EDITOR_HEIGHT);

			}
		}
		return $this->field_height;
	}

	public function get_pre_code_compile_status() {
		if (!$this->pre_code_compile_status) {
			$this->pre_code_compile_status = get_post_meta($this->get_code_post_id(), '_wp_ace_compile_status', true);
			if (!$this->pre_code_compile_status) {
				return false;

			}
		}
		return $this->pre_code_compile_status;

	}

	public function get_pre_code_compile_error_msg() {
		if (!$this->pre_code_compile_error_msg) {
			$this->pre_code_compile_error_msg = get_post_meta($this->get_code_post_id(), '_wp_ace_compile_error_msg', true);
			if (!$this->pre_code_compile_error_msg) {
				return false;
			}
		}
		return $this->pre_code_compile_error_msg;	
	}

	public function update_code() {
			
			if ($this->get_current_hash() == $this->get_stored_hash()) {
				// check if any code or settings has changed. If has is the same, nothing new to save

				//return;
			} else {

				update_post_meta($this->host_post_id, $this->keys['host-hash-meta-key'], $this->get_current_hash());

			}

		// get the appropriate post name text depending on whether this is the initial post or a revision
		$this->code_name_text = $this->code_post_name_start . $this->host_post_id;
		$this->host_title = get_the_title($this->host_post_id);
		$this->code_title_text = $this->code_post_title_start . $this->host_post_id . ' (' . $this->host_title . ')';
			
			if (is_wp_error($this->get_code_post_id())) {
				$errors = $this->code_post_id->get_error_messages();
				foreach ($errors as $error) {
					echo $error;
				}

				// throw exception
			} else {
				
					$code_post = array(
						'ID'           	=> 	$this->get_code_post_id(),
					  'post_name'    	=> 	$this->code_name_text, 
					  'post_status'   => 	'publish',
					  'post_type'			=> 	$this->post_type,
					  'post_title'		=> 	$this->code_title_text,
					  'post_content' 	=> 	$this->get_pre_code()
					);
 
					wp_update_post( $code_post );

				$latest_revision = current(wp_get_post_revisions($this->get_code_post_id()));

				if ($latest_revision) {
				   // do stuff with the latest revision
				   // $latest_revision->ID will contain the latest revision
					$preprocessor_old = get_post_meta($this->get_code_post_id(), '_wp_ace_preprocessor', true);
					$editor_height_old = get_post_meta($this->get_code_post_id(), '_wp_ace_editor_height', true);
					
					add_metadata( 'post', $latest_revision->ID, '_wp_ace_preprocessor', $preprocessor_old );
					add_metadata( 'post', $latest_revision->ID, '_wp_ace_editor_height', $editor_height_old );					   
				}
			}
			
			/*
			// compile pre code and save it as meta data for the associated code post
			$compiled = $this->compile(); // TODO: Write compile function with return vals
			update_post_meta($code_post_id, '_wp_ace_compile_status', $compiled->status );
			
			// update compile error status and message
			if ($compiled->status != 'error') {
				update_post_meta($this->code_post_id, '_wp_ace_compiled', $compiled->compiled_code );
				delete_post_meta($this->code_post_id, '_wp_ace_compile_error_msg');
			} else {
				update_post_meta($this->code_post_id, '_wp_ace_compile_error_msg', $compiled->error_msg );
			}
			*/
		
			// update other basic meta data
			update_post_meta($this->code_post_id, '_wp_ace_editor_height', $this->get_editor_height() );
			update_post_meta($this->code_post_id, '_wp_ace_preprocessor', $this->get_preprocessor() );
			update_post_meta($this->code_post_id, '_wp_ace_editor_cursor_position', $this->get_editor_cursor_position() );

			$this->additional_updates();

			return;
	}



	private function compile() {
		$ret = new stdClass();
		
		$ret->compiled_code = '';
		$ret->status = '';
		$ret->error_msg = '';

		if ( empty($this->get_pre_code()) ) {
			$ret->status = 'empty';
		} else {
			try {
					
				switch($this->get_preprocessor()) {
					case 'scss' :
						// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/scss-compiler.php';

						$scss = new scssc();
						$compiled_code = $scss->compile($this->get_pre_code());
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
