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
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
	    <div role="tabpanel" class="tab-pane active" id="html-edit">
				<div class="" style="padding-top:10px;" >
					
					<div class="alert alert-warning" role="alert">
					  <strong>Warning!</strong> Better check yourself, you're not looking too good.
					</div>	    	
	
					<div class="clearfix" style="background-color:#f5f5f5;padding:10px;" >
					  <button type="button" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save and Compile HTML</button>
						<p class="pull-right text-success" ><span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span> Saved</p>
					</div>

					<div class="wp-ace-editor">
						<pre id="wp-ace-html-php-pre-code-editor" style="height:<?php echo $html_php_pre_code_editor_height; ?>px" class="code-content" ><?php echo htmlentities($html_php_pre_code); ?></pre>
						<input type="hidden" id="wp-ace-html-php-pre-code" name="wp-ace-html-php-pre-code" value="1<?php echo htmlentities($html_php_pre_code); ?>" >
						<input type="hidden" id="wp-ace-html-php-field-height" name="wp-ace-html-php-field-height" class="field-height" value="1<?php echo $html_php_pre_code_editor_height; ?>" >
						<input type="hidden" id="wp-ace-html-php-preprocessor" name="wp-ace-html-php-preprocessor" class="field-preprocessor" value="1<?php echo $html_php_preprocessor; ?>" >
						<input type="hidden" id="wp-ace-html-php-editor-has-focus" name="wp-ace-html-php-editor-has-focus" class="field-has-focus" value="1<?php echo $html_php_editor_has_focus; ?>" >
						<input type="hidden" id="wp-ace-html-php-editor-cursor-position" name="wp-ace-html-php-editor-cursor-position" class="field-editor-cursor-position" value="1<?php echo $html_php_editor_cursor_position ?>" >
					</div>

		    	<div class="clearfix" >
					  <div class="" >
						  <p class="text-muted" ><span class="glyphicon glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span> Proprocessing with <strong>HAML</strong>. Positioned <strong>before post content</strong>. wpautop <strong>enabled</strong>. Display only on single template <strong>Disabled</strong>. <a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" aria-controls="change-preprocessor-modal">Change Settings</a></p>
					  </div>

					</div>

					<div class="modal fade" tabindex="-1" role="dialog" id="change-settings-modal" >
					  <div class="modal-dialog">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title">HTML Settings</h4>
					      </div>
					      <div class="modal-body">
	      					<div>
										<h4>General</h4>
										<label class="checkbox">
											<input type="checkbox" value="">
											Disable wpautop 
											<span class="glyphicon glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right" title="Automatically wraps in paragraph tag" aria-hidden="true"  ></span>
										</label>
										
										<label class="checkbox"><input type="checkbox" value="">Only display on single template</label>

										<h4>Position</h4>
										<label class="radio"><input type="radio" value="">before post content </label>
										<label class="radio"><input type="radio" value="">after post content</label>
										
										<h4>Pre Processor</h4>
										<label class="radio"><input type="radio" value="">Option 1</label>
										<label class="radio"><input type="radio" value="">Option 2</label>
										<label class="radio"><input type="radio" value="">Option 3</label>						
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
	    <div role="tabpanel" class="tab-pane" id="css-edit">

					<div class="clearfix" style="background-color:#f5f5f5;padding:10px;" >
					  <button type="button" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save and Compile Sass</button>
						<p class="pull-right text-success" ><span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span> Saved</p>
					</div>

					<div class="wp-ace-editor">
						<pre id="wp-ace-css-pre-code-editor" style="height:<?php echo $css_pre_code_editor_height; ?>px" class="code-content" ><?php echo htmlentities($css_pre_code); ?></pre>
						<input type="hidden" id="wp-ace-css-pre-code" name="wp-ace-css-pre-code" value="1<?php echo htmlentities($css_pre_code); ?>" >
						<input type="hidden" id="wp-ace-css-field-height" name="wp-ace-css-field-height" class="field-height" value="1<?php echo $css_pre_code_editor_height; ?>" >
						<input type="hidden" id="wp-ace-css-preprocessor" name="wp-ace-css-preprocessor" class="field-preprocessor" value="1<?php echo $css_preprocessor; ?>" >
						<input type="hidden" id="wp-ace-css-editor-has-focus" name="wp-ace-css-editor-has-focus" class="field-has-focus" value="1<?php echo $css_editor_has_focus; ?>" >
						<input type="hidden" id="wp-ace-css-editor-cursor-position" name="wp-ace-css-editor-cursor-position" class="field-editor-cursor-position" value="1<?php echo $css_editor_cursor_position ?>" >
					</div>

		    	<div class="clearfix" >
					  <div class="" >
						  <p class="text-muted" ><span class="glyphicon glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span> Proprocessing with <strong>Sass</strong>. Enquede <strong>in header</strong>. <a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" aria-controls="change-preprocessor-modal">Change Settings</a></p>
					  </div>

					</div>

	    </div>
	    <div role="tabpanel" class="tab-pane" id="javascript-edit">

					<div class="wp-ace-editor">
						<pre id="wp-ace-js-pre-code-editor" style="height:<?php echo $js_pre_code_editor_height; ?>px" class="code-content" ><?php echo htmlentities($js_pre_code); ?></pre>
						<input type="hidden" id="wp-ace-js-pre-code" name="wp-ace-js-pre-code" value="1<?php echo htmlentities($js_pre_code); ?>" >
						<input type="hidden" id="wp-ace-js-field-height" name="wp-ace-js-field-height" class="field-height" value="1<?php echo $js_pre_code_editor_height; ?>" >
						<input type="hidden" id="wp-ace-js-preprocessor" name="wp-ace-js-preprocessor" class="field-preprocessor" value="1<?php echo $js_preprocessor; ?>" >
						<input type="hidden" id="wp-ace-js-editor-has-focus" name="wp-ace-js-editor-has-focus" class="field-has-focus" value="1<?php echo $js_editor_has_focus; ?>" >
						<input type="hidden" id="wp-ace-js-editor-cursor-position" name="wp-ace-js-editor-cursor-position" class="field-editor-cursor-position" value="1<?php echo $js_editor_cursor_position ?>" >
					</div>

		    	<div class="clearfix" >
					  <div class="" >
						  <p class="text-muted" ><span class="glyphicon glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span> Proprocessing with <strong>Coffee Script</strong>. Enquede <strong>in header</strong>. <a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" aria-controls="change-preprocessor-modal">Change Settings</a></p>
					  </div>
					</div>

	    </div>
	    <div role="tabpanel" class="tab-pane active" id="html-compiled">
	    	
	    </div>
	    <div role="tabpanel" class="tab-pane" id="css-compiled">
	    	
	    </div>
	    <div role="tabpanel" class="tab-pane" id="javascript-compiled">
	    		
	    		<div class="wp-ace-editor">
						<pre id="wp-ace-js-compiled-code-" style="height:<?php echo $js_compiled_code_height; ?>px" class="code-content" ><?php echo htmlentities($js_compiled_code); ?></pre>
						<input type="hidden" id="wp-ace-js-compiled-code" name="wp-ace-js-compiled-code" value="1<?php echo htmlentities($js_compiled_code); ?>" >
						<input type="hidden" id="wp-ace-js-compiled-field-height" name="wp-ace-js-compiled-field-height" class="field-height" value="1<?php echo $js_pre_compiled_code_height; ?>" >
					</div>

	    </div>
	  </div>

	</div>
</div>