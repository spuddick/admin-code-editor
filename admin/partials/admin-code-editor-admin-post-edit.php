<div class="wp-ace-bootstrap">
	<div>

	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" class="active dropdown">
	    	<a href="#" aria-controls="html"  class="dropdown-toggle" data-toggle="dropdown" >
	    		HTML
			    <span class="caret"></span></a>
			    <ul class="dropdown-menu">
			      <li><a href="#html-edit" role="tab" id="" data-toggle="tab"  >Edit</a></li>
			      <li><a href="#html-compiled" role="tab" id="" data-toggle="tab" >View Compiled</a></li>
			    </ul>
	    	</a>
	    </li>
	    <li role="presentation" class="dropdown">
	    	<a href="#" aria-controls="css" class="dropdown-toggle" data-toggle="dropdown" >
	    		CSS
			    <span class="caret"></span></a>
			    <ul class="dropdown-menu">
			      <li><a href="#css-edit" role="tab" id="" data-toggle="tab" >Edit</a></li>
			      <li><a href="#css-compiled" role="tab" id="" data-toggle="tab" >View Compiled</a></li>
			    </ul>
	    	</a>
	    </li>
	    <li role="presentation" class="dropdown">
	    	<a href="#" aria-controls="javascript" class="dropdown-toggle bg-danger" data-toggle="dropdown" >
	    		Javascript
			    <span class="caret"></span></a>
			    <ul class="dropdown-menu">
			      <li><a href="#javascript-edit" role="tab" id="" data-toggle="tab" >Edit</a></li>
			      <li><a href="#javascript-compiled"  role="tab" id="" data-toggle="tab" >View Compiled</a></li>
			    </ul>
	    	</a>
	    </li>
	    <li role="" style="float:right;border:none !important;">
	    	<a style="background-color:none !important;" href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false"  data-active-modal-tab="wp-ace-general-tab-link"  >
	    		<span class="glyphicon glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span> Settings
	    	</a>
	    </li>
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
	    <div role="tabpanel" class="tab-pane active" id="html-edit">
				<div class="" style="padding-top:10px;" >
					<?php if ($html_php_editor->get_pre_code_compile_status() == 'error') { ?>
						<div class="alert alert-warning" role="alert">
						  <?php echo $html_php_editor->get_pre_code_compile_error_msg() ?>
						</div>

					<?php } ?>
	    	
	
					<div class="clearfix" style="background-color:#f5f5f5;padding:10px;" >
					  <button type="button" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save and Compile HTML</button>
						<p class="pull-right text-success" ><span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span> Saved</p>
					</div>

					<div class="wp-ace-editor">
						<pre id="wp-ace-html-php-pre-code-editor" style="height:<?php echo $html_php_editor->get_editor_height(); ?>px" class="code-content" ><?php echo htmlentities($html_php_editor->get_pre_code()); ?></pre>
						
						<input type="hidden" id="wp-ace-html-php-pre-code" name="wp-ace-html-php-pre-code" value="<?php echo htmlentities($html_php_editor->get_pre_code()); ?>" >
						
						<input type="hidden" id="wp-ace-html-php-field-height" name="wp-ace-html-php-field-height" class="field-height" value="<?php echo $html_php_editor->get_editor_height(); ?>" >
						
						<input type="hidden" id="wp-ace-html-php-editor-cursor-position" name="wp-ace-html-php-editor-cursor-position" class="field-editor-cursor-position" value="<?php echo $html_php_editor->get_editor_cursor_position(); ?>" >

					</div>

		    	<div class="clearfix" >
					  <div class="" >
						  <p class="text-muted" ><span class="glyphicon glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span> Proprocessing with <strong>HAML</strong>. Positioned <strong>before post content</strong>. wpautop <strong>enabled</strong>. Display only on single template <strong>Disabled</strong>. <a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" data-active-modal-tab="wp-ace-html-tab-link" aria-controls="change-settings-modal">Change HTML Settings</a></p>
					  </div>

					</div>

				</div>


	    </div>
	    <div role="tabpanel" class="tab-pane" id="css-edit">

					<div class="clearfix" style="background-color:#f5f5f5;padding:10px;" >
					  <button type="button" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save and Compile Sass</button>
						<p class="pull-right text-success" ><span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span> Saved</p>
					</div>

					<div class="wp-ace-editor">
						<pre id="css-code" style="height:400<?php // echo $html_height ?>px" class="code-content" ><?php // echo htmlentities($html_code) ?></pre>
						<input type="hidden" id="css-field" name="css-field" value="<?php // echo htmlentities($html_code) ?>" >
						<input type="hidden" id="css-field-height" name="css-field-height" class="field-height"  value="<?php echo $css_height ?>" >
					</div>

		    	<div class="clearfix" >
					  <div class="" >
						  <p class="text-muted" ><span class="glyphicon glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span> Proprocessing with <strong>Sass</strong>. Enquede <strong>in header</strong>. <a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" aria-controls="change-settings-modal" data-active-modal-tab="wp-ace-css-tab-link"  >Change CSS Settings</a></p>
					  </div>

					</div>

	    </div>
	    <div role="tabpanel" class="tab-pane" id="javascript-edit">
					<div class="wp-ace-editor">
						<pre id="js-code" style="height:<?php // echo $html_height ?>px" class="code-content" ><?php // echo htmlentities($html_code) ?></pre>
						<input type="hidden" id="js-field" name="js-field" value="<?php // echo htmlentities($html_code) ?>" >
						<input type="hidden" id="js-field-height" name="js-field-height" class="field-height"  value="<?php echo $js_height ?>" >
					</div>

		    	<div class="clearfix" >
					  <div class="" >
						  <p class="text-muted" ><span class="glyphicon glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span> Proprocessing with <strong>Coffee Script</strong>. Enquede <strong>in header</strong>. <a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" aria-controls="change-settings-modal"  data-active-modal-tab="wp-ace-javascript-tab-link" >Change Javascript Settings</a></p>
					  </div>
					</div>

	    </div>
	    <div role="tabpanel" class="tab-pane active" id="html-compiled">
	    	
	    </div>
	    <div role="tabpanel" class="tab-pane" id="css-compiled">
	    	
	    </div>
	    <div role="tabpanel" class="tab-pane" id="javascript-compiled">
	    	
	    </div>
	  </div>

	  <!-- Settings Modal -->
		<div class="modal fade" tabindex="-1" role="dialog" id="change-settings-modal" >
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title">Settings</h4>
		      </div>
		      <div class="modal-body">
  					
						<div>

						  <!-- Nav tabs -->
						  <ul class="nav nav-tabs" role="tablist">
						    <li role="presentation" class="active"><a href="#wp-ace-general" id="wp-ace-general-tab-link" aria-controls="wp-ace-general" role="tab" data-toggle="tab">General</a></li>
						    <li role="presentation"><a href="#wp-ace-html" id="wp-ace-html-tab-link"  aria-controls="wp-ace-html" role="tab" data-toggle="tab">HTML</a></li>
						    <li role="presentation"><a href="#wp-ace-css" id="wp-ace-css-tab-link"  aria-controls="wp-ace-css" role="tab" data-toggle="tab">CSS</a></li>
						    <li role="presentation"><a href="#wp-ace-javascript" id="wp-ace-javascript-tab-link"  aria-controls="wp-ace-javascript" role="tab" data-toggle="tab">Javascript</a></li>
						  </ul>

						  <!-- Tab panes -->
						  <div class="tab-content">
						    <div role="tabpanel" class="tab-pane active" id="wp-ace-general">
						    	
						    	<div class="form-group">
										<h5>Do not display WP ACE code on the following templates: </h5>
										<div class="checkbox">
											<label><input type="checkbox" name="wp-ace-disabled-templates[]" <?php // checked($html_php_editor->on_front_page_is_disabled(), TRUE) ?> value="front-page" >Front Page</label>
										</div>

										<div class="checkbox">
											<label ><input type="checkbox" name="wp-ace-disabled-templates[]" <?php // checked($html_php_editor->on_home_is_disabled(), TRUE) ?> value="home" >Home</label>
										</div>
										
										<div class="checkbox">
											<label><input type="checkbox" name="wp-ace-disabled-templates[]" <?php // checked($html_php_editor->on_archives_is_disabled(), TRUE) ?> value="archives" >Archives</label>	
										</div>
										
										<div class="checkbox">
											<label><input type="checkbox" name="wp-ace-disabled-templates[]" <?php // checked($html_php_editor->on_search_results_is_disabled(), TRUE) ?> value="search-results" >Search Results</label>
										</div>
						    	</div>

						    	<div class="form-group">
										<h5>Only display WP ACE code when: </h5>
										<div class="checkbox">
											<label>
												<input type="checkbox" id="wp-ace-only-display-in-loop" name="wp-ace-only-display-in-loop" class="" value="1" <?php // checked($html_php_editor->get_disable_wpautop_status(), '1') ?> >
												inside the loop
												<span class="glyphicon glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right" title="Inside the loop description https://codex.wordpress.org/Function_Reference/in_the_loop" aria-hidden="true"  ></span>									    	
											</label>	
										</div>
										
										<div class="checkbox">
											<label>
												<input type="checkbox"  id="wp-ace-only-display-in-main-query" name="wp-ace-only-display-in-main-query" class="" value="1" <?php // checked($html_php_editor->get_disable_wpautop_status(), '1') ?> >
												in main query
												<span class="glyphicon glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right" title="Inside main query description https://codex.wordpress.org/Function_Reference/is_main_query " aria-hidden="true"  ></span>									    	
											</label>	
										</div>									    	
									</div>

						    </div>
						    <div role="tabpanel" class="tab-pane" id="wp-ace-html">
	      					<div>
										<h5>Automatic Paragraphs</h5>
										<div class="checkbox">
											<label>
												<input type="checkbox"  id="wp-ace-html-php-disable-wpautop" name="wp-ace-html-php-disable-wpautop" class="field-editor-disable-wpautop" value="1" <?php checked($html_php_editor->get_disable_wpautop_status(), '1'); ?> >
												Disable wpautop 
												<span class="glyphicon glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right" title="Automatically wraps in paragraph tag" aria-hidden="true"  ></span>									    	
											</label>	
										</div>
																						
										
										<h5>Position</h5>
										<div class="radio">
											<label class="radio"><input type="radio" name="wp-ace-html-php-code-position" value="before" <?php checked($html_php_editor->get_code_output_position(), 'before'); ?> >before post content </label>
										</div>
										<div class="radio">
											<label class="radio"><input type="radio" name="wp-ace-html-php-code-position" value="after" <?php checked($html_php_editor->get_code_output_position(), 'after'); ?> >after post content</label>
										</div>
										
										
										<h5>Pre Processor</h5>
										<div class="radio">
											<label class="radio"><input type="radio" <?php checked($html_php_editor->get_preprocessor(), 'none'); ?> value="none" name="wp-ace-html-php-preprocessor" >None</label>
										</div>
										
										<?php
											foreach($preprocessor_options['html'] as $preprocessor_slug => $preprocessor_name) {
												?>
													<div class="radio">
														<label class="radio"><input type="radio" <?php checked($html_php_editor->get_preprocessor(), $preprocessor_slug); ?> value="<?php $preprocessor_slug; ?>" name="wp-ace-html-php-preprocessor" ><?php echo $preprocessor_name; ?></label>
													</div>
												<?php
											}

										?>					
									</div>									    	

						    </div>
						    <div role="tabpanel" class="tab-pane" id="wp-ace-css">
						    	

						    </div>
						    <div role="tabpanel" class="tab-pane" id="wp-ace-javascript">
						    	

						    </div>
						  </div>

						</div>



		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</div>
</div>