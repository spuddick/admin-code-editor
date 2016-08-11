<?php


class Admin_Code_Editor_Editor {

	private $type, $host_post_id, $code_post_id, $code_post_title;
	protected $pre_code, $field_height, $preprocessor, $cursor_position;
	protected	$post_meta_keys = array(
		'current-hash' => null,
		'incoming-hash' => null,
	);


	public function __construct($param) {
		/*
		$this->admin_code_editor = 'admin-code-editor';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		*/
	
    if (is_int($param)) {
        // numerical ID was given
        $code_post_id = $param;
    } elseif (is_array($param)) {

      if (isset($param['code-post-id'])) {
          $code_post_id = $param['code-post-id'];
      }
      if (isset($param['pre-code'])) {
          $pre_code = $param['pre-code'];
      }
      if (isset($param['field-height'])) {
          $field_height = $param['field-height'];
      }
      if (isset($param['preprocessor'])) {
          $preprocessor = $param['preprocessor'];
      }
      if (isset($param['cursor-position'])) {
          $cursor_position = $param['cursor-position'];
      }
      if (isset($param['host-post-id'])) {
          $host_post_id = $param['host-post-id'];
      }
      if (isset($param['code-post-id'])) {
          $code_post_id = $param['code-post-id'];
      }
      if (isset($param['code-post-title'])) {
          $code_post_title = $param['code-post-title'];
      }

    }

	}

	public function update_code($post_id, $code_editor) {
			$code_post_id = get_post_meta($post_id, '_wp_ace_code_post_id', true);
			

			// get the appropriate post name text depending on whether this is the initial post or a revision
			$post_name_text = 'wp-ace-html-and-php-code-for-' . $post_id;
			$post_title = get_the_title($post_id);
			$post_title_text = 'WP ACE HTML and PHP code for: Post ID ' . $post_id . ' (' . $post_title . ')';

			if (empty($code_post_id)) {
				// if no existing post for HTML code, create one

				$code_post = array(
					  'post_name'    	=> $post_name_text, 
					  'post_content'  => $pre,
					  'post_status'   => 'publish',
					  'post_type'			=> 'wp-ace-html',
					  'post_title'		=>	$post_title_text
					);
 
				$code_post_id = wp_insert_post( $code_post );

				update_post_meta($post_id, '_wp_ace_code_post_id', $code_post_id);
			} else {
				
				// if an existing post for HTML exists, update it
			  $code_post_settings = array(
		      'ID'           	=> $code_post_id,
		      'post_name'    	=> $post_name_text,
				  'post_content'  => $pre,
				  'post_status'   => 'publish',
				  'post_type'			=> 'wp-ace-html',
					'post_title'		=>	$post_title_text
			  );
				
				$code_post_id = wp_update_post( $code_post_settings, true );						  
				if (is_wp_error($code_post_id)) {
					$errors = $code_post_id->get_error_messages();
					foreach ($errors as $error) {
						echo $error;
					}
				} else {
					$latest_revision = current(wp_get_post_revisions($code_post_id));

					if ($latest_revision) {
					   // do stuff with the latest revision
					   // $latest_revision->ID will contain the latest revision
						$preprocessor_old = get_post_meta($code_post_id, '_wp_ace_preprocessor', true);
						$editor_height_old = get_post_meta($code_post_id, '_wp_ace_editor_height', true);
						
						add_metadata( 'post', $latest_revision->ID, '_wp_ace_preprocessor', $preprocessor_old );
						add_metadata( 'post', $latest_revision->ID, '_wp_ace_editor_height', $editor_height_old );					   
					}
				}
			}



			// compile pre code and save it as meta data for the associated code post
			$compiled = $this->compile($pre, $preprocessor); // TODO: Write compile function with return vals
			update_post_meta($code_post_id, '_wp_ace_status', $compiled->status );
			
			// update compile error status and message
			if ($compiled->status != 'error') {
				update_post_meta($code_post_id, '_wp_ace_compiled', $compiled->compiled_code );
				delete_post_meta($code_post_id, '_wp_ace_error_msg');
			} else {
				update_post_meta($code_post_id, '_wp_ace_error_msg', $compiled->error_msg );
			}

			// update other basic meta data
			update_post_meta($code_post_id, '_wp_ace_editor_height', $editor_height );
			update_post_meta($code_post_id, '_wp_ace_preprocessor', $preprocessor );
			update_post_meta($code_post_id, '_wp_ace_insertion_pos', $editor_cursor_position );

			return;
	}

	private function compile($post_id, $pre_code, $preprocessor) {
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
