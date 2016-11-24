<?php
use \Michelf\MarkdownExtra, Leafo\ScssPhp\Compiler as ScssCompiler;
//use Stylus\Stylus;
use CoffeeScript\Compiler  as CoffeeCompiler;
use HamlPHP\Compiler  as HamlPHPCompiler;
use HamlPHP\HamlPHP  as HamlPHP, HamlPHP\FileStorage;

abstract class Admin_Code_Editor_Editor {

	const DEFAULT_EDITOR_HEIGHT = 400;

	protected  $code_post_title, $keys, $post_type, $code_post_name_start, $code_post_title_start, $stored_hash, $current_hash;
	protected $host_post_id, $code_post_id, $pre_code, $field_height, $preprocessor, $cursor_position;
	protected $pre_code_compile_error_msg, $pre_code_compile_status, $compiled_code;

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
	abstract protected function get_default_preprocessor();

	abstract public function initialize_from_post_request();
	//abstract public function get_preprocessor();
	
	/*
	public function get_disabled_templates() {
		$ret = array();

		if (empty($this->disabled_templates)) {
			$this->disabled_templates = get_post_meta($this->code_post_id, '_wp_ace_disable_templates', true);
			if (empty($this->disabled_templates)) {
				$this->disabled_templates = get_option('wp_ace_default_disabled_template');
			}
		}

		return $this->disabled_templates;
	}
	*/

	private function get_code_name_text() {
		return $this->code_post_name_start . $this->host_post_id;
	}

	private function get_code_title_text() {
		return $this->code_post_title_start . $this->host_post_id;
	}

	protected function get_code_post_id() {
			
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
				$this->preprocessor = get_option($this->keys['global_preprocessor'], $this->get_default_preprocessor());

			}
		}
		return $this->preprocessor;
	}

	public function get_compiled_code() {
		
		if (empty($this->compiled_code)) {
			$this->compiled_code = get_post_meta($this->get_code_post_id(), '_wp_ace_compiled', true);
			if (!$this->compiled_code) {
				return;
			}
		}
		return $this->compiled_code;
	}

	public function get_compiled_code_status() {
		if (empty($compiled_code_status)){
			return 'empty';
		} else {
			return get_post_meta($this->get_code_post_id(), '_wp_ace_compile_status');
		}
	}

	public function get_pre_code() {
		
		if (!$this->pre_code) {
			$pre_code_post = get_post($this->get_code_post_id());
			$this->pre_code = $pre_code_post->post_content;			
		}

		return $this->pre_code;
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

	public function get_code_compile_status() {
		if (!$this->pre_code_compile_status) {
			$this->pre_code_compile_status = get_post_meta($this->get_code_post_id(), '_wp_ace_compile_status', true);
			if (!$this->pre_code_compile_status) {
				return false;

			}
		}
		return $this->pre_code_compile_status;

	}

	public function get_code_compile_error_msg() {
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
			
			
			// compile pre code and save it as meta data for the associated code post
			$compiled = $this->compile($this->get_pre_code()); 
			update_post_meta($this->get_code_post_id(), '_wp_ace_compile_status', $compiled->status );
			
			switch ($compiled->status) {
				case 'error':
					update_post_meta($this->get_code_post_id(), '_wp_ace_compile_error_msg', $compiled->error_msg );
				break;
				case 'success':
					update_post_meta($this->get_code_post_id(), '_wp_ace_compiled', $compiled->compiled_code );
					delete_post_meta($this->get_code_post_id(), '_wp_ace_compile_error_msg');
				break;
				case 'empty': 
					delete_post_meta($this->get_code_post_id(), '_wp_ace_compile_error_msg');
					update_post_meta($this->get_code_post_id(), '_wp_ace_compiled', $compiled->compiled_code );
				break;
			}

		
			// update other basic meta data
			update_post_meta($this->get_code_post_id(), '_wp_ace_editor_height', $this->get_editor_height() );
			update_post_meta($this->get_code_post_id(), '_wp_ace_preprocessor', $this->get_preprocessor() );

			$this->additional_updates();

			return;
	}



	protected function compile($pre_code, $preprocessor = null) {
		$ret = new stdClass();
		
		$ret->compiled_code = '';
		$ret->status = '';
		$ret->error_msg = '';

		if (null === $preprocessor) {
      $preprocessor = $this->get_preprocessor();
    }

		if ( empty($pre_code) ) {
			$ret->status = 'empty';
		} else {
			try {
					
				switch($preprocessor) {
					case 'scss' :
						
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/scssphp/scss.inc.php';

						$scss = new ScssCompiler();
						$compiled_code = $scss->compile($pre_code);
						$ret->compiled_code = trim($compiled_code);
						$ret->status = 'success';
						


						break;
					
					case 'less' :
						
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/lessphp/lessc.inc.php';
						
						$less = new lessc;
						echo $less->compile($pre_code);
						$ret->compiled_code = trim($compiled_code);
						$ret->status = 'success';
						
						break;
					case 'stylus' :
						// Can't get stylus compilation to work, so disabled for now	

						/*
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/Stylus.php/src/Stylus/Stylus.php';
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/Stylus.php/src/Stylus/Exception.php';
						$stylus = new Stylus();
						//$compiled_code = $stylus->fromString($pre_code)->toString();
						
						// From file to string
						//$css = $stylus->fromFile(wp_upload_dir()['basedir'] . '/tmp-read/sample-stylus.stylus')->toString();
						clearstatcache();
						//$stylus = new Stylus();
						$stylus->setReadDir( plugin_dir_path( dirname( __FILE__ ) ) . 'lib/Stylus.php/src/Stylus/tmp-read');
						$stylus->setWriteDir(plugin_dir_path( dirname( __FILE__ ) ) . 'lib/Stylus.php/src/Stylus/tmp-write');
						$stylus->setImportDir(plugin_dir_path( dirname( __FILE__ ) ) . 'lib/Stylus.php/src/Stylus/tmp-read'); //if you import a file without setting this, it will import from the read directory
						$stylus->parseFiles();

						//$compiled_code = $stylus->fromString("body\n color black")->toString();
						//$ret->compiled_code = trim($compiled_code);
						//$ret->status = 'success';						
						
						//$stylus->setReadDir(wp_upload_dir()['basedir'] );
						//$stylus->setWriteDir(wp_upload_dir()['basedir'] );
						//$stylus->fromString("body\n color black")->toFile("oudfst.css", true);

						$css = $stylus->fromString("body\n color black")->toString();
						$ret->compiled_code = trim($css);
						$ret->status = 'success';
						*/
					break;
					case 'haml' :
						
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/HamlPHP/src/HamlPHP/HamlPHP.php';
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/HamlPHP/src/HamlPHP/Storage/FileStorage.php';
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/HamlPHP/src/HamlPHP/Compiler.php';
						
						// Make sure that a directory _tmp_ exists in your application and it is writable.
						$parser = new HamlPHP(new FileStorage(wp_upload_dir()['basedir'] . '/tmp/'));
						$compiler = new HamlPHPCompiler($parser);
						$content = $compiler->parseString($pre_code);

						$ret->compiled_code = trim($parser->evaluate($content));
						$ret->status = 'success';		
						
						
					break;
					case 'markdown' :
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/php-markdown/Michelf/MarkdownExtra.inc.php';
						
						$compiled_code  = MarkdownExtra::defaultTransform($pre_code);
						$ret->compiled_code = trim($compiled_code);
						$ret->status = 'success';		
					break;
					case 'coffee' :
						
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/coffeescript-php/src/CoffeeScript/Init.php';
						
						// Load manually
						CoffeeScript\Init::load();

					  // Temporarily writing to hard disk appeared to be to only way to successfully parse the pre code
				  	$tmpfname = tempnam( wp_upload_dir()['basedir'] .  "/tmp", "wp-ace-coffee");
				  	$handle = fopen($tmpfname, "w");
						fwrite($handle, $pre_code);
						fseek($handle, 0);
				    $coffee = file_get_contents($tmpfname);
					  $js = CoffeeCompiler::compile($coffee, array('filename' => $tmpfname));
					  fclose($handle);
					  unlink($tmpfname);

					  $ret->compiled_code = trim($js);
						$ret->status = 'success';	
						
					break;
					case 'none' :
					
					  $ret->compiled_code = trim($pre_code);
						$ret->status = 'success';	

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
