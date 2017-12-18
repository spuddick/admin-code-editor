<?php
use \Michelf\MarkdownExtra, Leafo\ScssPhp\Compiler as ScssCompiler;
//use Stylus\Stylus;
use CoffeeScript\Compiler  as CoffeeCompiler;
use HamlPHP\Compiler  as HamlPHPCompiler;
use HamlPHP\HamlPHP  as HamlPHP, HamlPHP\FileStorage;

/**
 * Abstract class to contain common functions and variables shared by the HTML, CSS, and JavaScript classes
 * 
 * @since 1.0.0
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/editor
 */
abstract class Admin_Code_Editor_Editor {

	const DEFAULT_EDITOR_HEIGHT = 400;

	protected $code_post_title, $keys, $post_type, $code_post_name_start, $code_post_title_start, $stored_hash, $current_hash;
	protected $host_post_id, $code_post_id, $pre_code, $field_height, $preprocessor, $cursor_position;
	protected $pre_code_compile_error_msg, $pre_code_compile_status, $compiled_code;

	/**
	 * Constructor
	 * @param array $param 
	 * @since 1.0.0
	 */
	public function __construct($param) {
	
		if (isset($param['code-post-id'])) {
				$this->code_post_id = $param['code-post-id'];
		}
		if (isset($param['host-post-id'])) {
			$this->host_post_id = $param['host-post-id'];
		} else {
			// throw exception
		}

	}

	abstract protected 	function additional_updates();
	abstract protected 	function get_default_preprocessor();
	abstract public 		function initialize_from_post_request();

	/**
	 * Get the post slug text for the code post
	 * @return string
	 * @since 1.0.0
	 */
	private function get_code_name_text() {
		return $this->code_post_name_start . $this->host_post_id;
	}

	/**
	 * Get the post title text for the code post
	 * @return string
	 * @since 1.0.0
	 */
	private function get_code_title_text() {
		return $this->code_post_title_start . $this->host_post_id;
	}

	/**
	 * Get the code post id associated with the host post
	 * @return int
	 * @since 1.0.0
	 */
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

	/**
	 * Get the preprocessor currently set for the code
	 * @return string
	 * @since 1.0.0
	 */
	public function get_preprocessor() {
		if (empty($this->preprocessor)) {
			$this->preprocessor = get_post_meta($this->get_code_post_id(), '_wp_ace_preprocessor', true);
			if (!$this->preprocessor) {

				$this->preprocessor = get_option($this->keys['global_preprocessor'], $this->get_default_preprocessor());
			}
		}
		return $this->preprocessor;
	}

	/**
	 * Get the compiled code 
	 * @return string
	 * @since 1.0.0
	 */
	public function get_compiled_code() {
		if (empty($this->compiled_code)) {
			$this->compiled_code = get_post_meta($this->get_code_post_id(), '_wp_ace_compiled', true);
			if (!$this->compiled_code) {
				return;
			}
		}
		return $this->compiled_code;
	}

	/**
	 * Get the uncompiled code
	 * @return string
	 * @since 1.0.0
	 */
	public function get_pre_code() {
		if (!$this->pre_code) {
			$pre_code_post = get_post($this->get_code_post_id());
			$this->pre_code = $pre_code_post->post_content;			
		}
		return trim($this->pre_code);
	}

	/**
	 * Get the height of the code editor window that was last set by the user
	 * @return int
	 * @since 1.0.0
	 */
	public function get_editor_height() {
		if (!$this->field_height) {
			$this->field_height = get_post_meta($this->get_code_post_id(), '_wp_ace_editor_height', true);
			if (!$this->field_height) {
				$this->field_height = get_option('_wp_ace_global_editor_height', self::DEFAULT_EDITOR_HEIGHT);
			}
		}
		return $this->field_height;
	}

	/**
	 * Get the compiled code status (success, error, or empty)
	 * @return string
	 * @since 1.0.0
	 */
	public function get_code_compile_status() {
		if (!$this->pre_code_compile_status) {
			$this->pre_code_compile_status = get_post_meta($this->get_code_post_id(), '_wp_ace_compile_status', true);
			if (!$this->pre_code_compile_status) {
				return false;
			}
		}
		return $this->pre_code_compile_status;
	}

	/**
	 * Get the error message associated with the code. Only present if status is 'error'
	 * @return string
	 * @since 1.0.0
	 */
	public function get_code_compile_error_msg() {
		if (!$this->pre_code_compile_error_msg) {
			$this->pre_code_compile_error_msg = get_post_meta($this->get_code_post_id(), '_wp_ace_compile_error_msg', true);
			if (!$this->pre_code_compile_error_msg) {
				return false;
			}
		}
		return $this->pre_code_compile_error_msg;	
	}


	protected function additional_revision_data_store($latest_revision_id) {

	}

	/**
	 * Called when post is saved. Will return true if the code or code settings has changed. 
	 * This is used to prevent uneccessary saving and compilation of code and code settings if nothing has changed.
	 * This value is never stored in the database
	 * 
	 * @return boolean
	 * @since 1.0.0
	 */
	public function has_changed() {
		if (isset($_POST[$this->keys['has-changed']]) && $_POST[$this->keys['has-changed']] == '1' ) {
			return true;
		}
		return false;
	}

	/**
	 * Called during post save. Updates and compiles code and code settings
	 * @since 1.0.0
	 */
	public function update_code() {
			
		if (!$this->has_changed() ) {
			return;
		}

		// get the appropriate post name text depending on whether this is the initial post or a revision
		$this->code_name_text 	= $this->code_post_name_start . $this->host_post_id;
		$this->host_title 			= get_the_title($this->host_post_id);
		$this->code_title_text 	= $this->code_post_title_start . $this->host_post_id . ' (' . $this->host_title . ')';
			
		if (is_wp_error($this->get_code_post_id())) {
			$errors = $this->code_post_id->get_error_messages();
			foreach ($errors as $error) {
				echo $error;
			}

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
				$this->additional_revision_data_store($latest_revision->ID);					   
			}
		}
			
		// compile pre code and save it as meta data for the associated code post
		$pre_code = $this->get_pre_code();
		$pre_code = str_replace('\\"', '"', $pre_code);
		$pre_code = str_replace("\\'", "'", $pre_code);
		$compiled = $this->compile($pre_code); 
		
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

		// Additional updates from subclass
		$this->additional_updates();

		return;
	}


	/**
	 * Function to compile all the various code from the supported preprocessors
	 * 
	 * @since 1.0.0
	 * @param string $pre_code 
	 * @param string|null $preprocessor 
	 * @return object contains compiled code, status, and any error message generated during compilation
	 */
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
			
			set_error_handler(function ($errno, $errstr, $errfile, $errline ,array $errcontex) {
				throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
			});

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
						$compiled_code = $less->compile($pre_code);
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
						
						$render_pos = strpos($pre_code, ' render ');
						$partial_pos = strpos($pre_code, ':partial');

						if ($render_pos !== false || $partial_pos !== false) {
							throw new Exception(__( "WP ACE Editor does not currently support 'render' or 'partials' in HAML. ",  'wrs-admin-code-editor'));
						} 

						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/HamlPHP/src/HamlPHP/HamlPHP.php';
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/HamlPHP/src/HamlPHP/Storage/FileStorage.php';
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/HamlPHP/src/HamlPHP/Compiler.php';
						
						// Make sure that a directory _tmp_ exists in your application and it is writable.
						$parser 	= new HamlPHP(new FileStorage(wp_upload_dir()['basedir'] . '/tmp/'));
						$compiler = new HamlPHPCompiler($parser);
						$content 	= $compiler->parseString($pre_code);

						$ret->compiled_code = trim($parser->evaluate($content));
						$ret->status = 'success';		
						
					break;
					case 'markdown' :
						
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/php-markdown/Michelf/MarkdownExtra.inc.php';
						
						$compiled_code  			= MarkdownExtra::defaultTransform($pre_code);
						$ret->compiled_code 	= trim($compiled_code);
						$ret->status 					= 'success';	

					break;
					case 'coffee' :
						
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/coffeescript-php/src/CoffeeScript/Init.php';
						
						// Load manually
						CoffeeScript\Init::load();
						// Temporarily writing to hard disk appeared to be to only way to successfully parse the pre code
						$tmpfname 	= tempnam( wp_upload_dir()['basedir'] .  "/tmp", "wp-ace-coffee");
						$handle 		= fopen($tmpfname, "w");
						fwrite($handle, $pre_code);
						fseek($handle, 0);
						$coffee 		= file_get_contents($tmpfname);
						$js 				= CoffeeCompiler::compile($coffee, array('filename' => $tmpfname));
						fclose($handle);
						unlink($tmpfname);

						$ret->compiled_code 	= trim($js);
						$ret->status 					= 'success';	
						
					break;
					case 'none' :
					
						$ret->compiled_code 	= trim($pre_code);
						$ret->status 					= 'success';	

					break;	
				}

			} catch (ErrorException $e) {
				$ret->status 			= 'error';
				$ret->error_msg 	= __('PHP code compile error: ',  'wrs-admin-code-editor') . $e->getMessage();
			} catch(Exception $e) {
				$ret->status 			= 'error';
				$ret->error_msg 	= $e->getMessage();
			}
			restore_error_handler();
		}
		return $ret;
	}


	/**
	 * Filter the editor height to an allowable range
	 * 
	 * @since 1.0.0
	 * @param string $height editor height 
	 * @return string|int filtered editor height
	 */
	protected static function filterEditorHeight($height) {
		$temp_field_height = intval($height);
		if ($temp_field_height < 0) {
			$temp_field_height = 1;
		} elseif ($temp_field_height > 4000) {
			$temp_field_height = self::DEFAULT_EDITOR_HEIGHT;
		}

		return $temp_field_height;
	}


	/**
	 * Determine if the preprocessor value is allowable
	 * 
	 * @since 1.0.0
	 * @param string the preprocessor 
	 * @param string the type of code the preprocessor is for (HTML, CSS, JavaScript)
	 * @return boolean whether the preprocessor is an allowable value for preprocessor type
	 */
	protected static function preprocessorIsValid($preprocessor_slug, $preprocessor_type) {
		$all_supported_preprocessors = get_option( 'wp_ace_supported_preprocessors', true);
		$supported_preprocessors = array_keys($all_supported_preprocessors[$preprocessor_type]);
		if (in_array($preprocessor_slug, $supported_preprocessors) || ($preprocessor_slug == $preprocessor_type)) {
			return true;
		} else {
			return false;
		}
	}
}
