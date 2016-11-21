<script type="text/template" id="tab-label-preprocessor-template">
  <% if ( preprocessor.length && preprocessor != "none") { %>
  	(<%= preprocessor_nicename %>)
  <% } %>
</script>
<script type="text/template" id="tmpl-wp-ace-code-changed">
  <% if (code_has_changed) { %>
	  <p class="wp-ace__notice wp-ace__notice--info text-warning" >
			<span class="fa fa-info-circle" aria-hidden="true"></span>
			<span class="wp-ace__notice__text" >
			<?php echo sprintf( __('%1$s code has changed. Publish/Update %2$s to view latest compiled code.', 'wrs-admin-code-editor'), '<%= preprocessor_nicename %>', '<%= post_type_name %>' ); ?>
			</span>
		</p>
  <% } else if (preprocessed_code_has_errors) { %>
	  <p class="wp-ace__notice wp-ace__notice--info text-danger" >
			<span class="fa fa-exclamation-triangle" aria-hidden="true"></span>
			<span class="wp-ace__notice__text" >
			<?php echo sprintf( __('The %1$s code contains errors. The following compiled code may not reflect the current preprocessed code.', 'wrs-admin-code-editor'), '<%= preprocessor_nicename %>'); ?>
			</span>
		</p>
  <% } else { %>
	  <p class="wp-ace__notice wp-ace__notice--info text-warning" >
	  	<span class="wp-ace__notice__text" >&nbsp;</span>
		</p>
  <% } %>
</script>
<div class="wp-ace-bootstrap">
	<div>
	  <!-- Nav tabs -->
	  <ul id="wp-ace__tabs" class="nav nav-tabs" role="tablist">
	    <li role="presentation" class="<?php if ($last_active_tab_id == 'html-edit' ) { echo 'active'; } ?> ">
	    	<a href="#html-edit" aria-controls="html"  class=" <?php if ($html_php_editor->get_code_compile_status() == 'error') { echo 'bg-danger wp-ace__error-tab'; } ?> " role="tab" data-toggle="tab" >
	    		<?php _e('HTML', 'wrs-admin-code-editor'); ?>
			    <span id="html-php-tab-label-preprocessor" class="text-muted" ></span>
	    	</a>
	    </li>
	    <li role="presentation" class="<?php if ($last_active_tab_id == 'css-edit' ) { echo 'active'; } ?>">
	    	<a href="#css-edit" aria-controls="css" class=" <?php if ($css_editor->get_code_compile_status() == 'error') { echo 'bg-danger wp-ace__error-tab'; } ?>" role="tab" data-toggle="tab" >
	    		<?php _e('CSS', 'wrs-admin-code-editor'); ?>
	    		<span id="css-tab-label-preprocessor" class="text-muted" ></span>

	    	</a>
	    </li>
	    <li role="presentation" class="<?php if ($last_active_tab_id == 'javascript-edit' ) { echo 'active'; } ?>">
	    	<a href="#javascript-edit" aria-controls="javascript" class=" <?php if ($js_editor->get_code_compile_status() == 'error') { echo 'bg-danger  wp-ace__error-tab'; } ?>" role="tab" data-toggle="tab" >
	    		<?php _e('JavaScript', 'wrs-admin-code-editor'); ?>
	    		<span id="js-tab-label-preprocessor" class="text-muted" ></span>
	    	</a>
	    </li>

	    <li id="wp-ace__settings-tab" role="" >
	    	<a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false"  data-active-modal-tab="wp-ace-general-tab-link"  >
	    		<span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <?php _e('Settings', 'wrs-admin-code-editor'); ?>
	    	</a>
	    </li>
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
	    <div role="tabpanel" class="tab-pane <?php if ($last_active_tab_id == 'html-edit' ) { echo 'active'; } ?>" id="html-edit">
				<div class="wp-ace__tab-panel-inner <?php if ($html_php_editor->get_code_compile_status() == 'error') { echo 'bg-danger'; } ?>" >
					<div class="wp-ace__tab-panel-inner__header">
						<?php if ($html_php_editor->get_code_compile_status() == 'error') { ?>
						<p class="wp-ace__notice wp-ace__notice--info text-error" >
		    			<span class="fa fa-exclamation-triangle" aria-hidden="true"></span>
		    			<span class="wp-ace__notice__text" ><?php echo $html_php_editor->get_code_compile_error_msg() ?></span>
		    		</p>
						<?php } else { ?>
							<p class="wp-ace__notice wp-ace__notice--info text-error" >&nbsp;</p>
						<?php } ?>
						<div class="btn-group">
						  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
						    <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu">
						    <li><a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" data-active-modal-tab="wp-ace-html-tab-link" aria-controls="change-settings-modal"><?php _e('Change HTML Settings', 'wrs-admin-code-editor'); ?></a></li>
						    <li><a href='#wp-ace--compiled-html-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false"  ><?php _e('View Compiled HTML', 'wrs-admin-code-editor'); ?></a></li>
						  </ul>
						</div>
					</div>
					<div class="wp-ace-editor">
						<pre id="wp-ace-html-php-pre-code-editor" style="height:<?php echo $html_php_editor->get_editor_height(); ?>px" class="code-content" ><?php echo htmlentities($html_php_editor->get_pre_code()); ?></pre>
						
						<input type="hidden" id="wp-ace-html-php-pre-code" name="wp-ace-html-php-pre-code" value="<?php echo htmlentities($html_php_editor->get_pre_code()); ?>" >
						
						<input type="hidden" id="wp-ace-html-php-field-height" name="wp-ace-html-php-field-height" class="field-height" value="<?php echo $html_php_editor->get_editor_height(); ?>" >

					</div>

		    	<div class="clearfix" >
					  <div class="" >
					  	
					  	<p class="text-muted" ><span class="glyphicon glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span><span id="wp-ace-html-php-status" ></span> </p>
						 	
						 	<script type="text/template" id="tmpl-wp-ace-html-php-status-template">
							  
							  <% if (preprocessor == 'none') { %>
							  	<?php _e('No HTML Preprocessor selected', 'wrs-admin-code-editor'); ?>.
							  <% } else { %>
							  	<?php echo sprintf( __('Preprocessing with %1$s', 'wrs-admin-code-editor'), '<strong><%= preprocessor_nicename %></strong>'); ?>.
							  <% } %>
							  
						  	<% if (output_position == 'before') { %>	
						  		<?php _e('Positioned <strong>before post content</strong>', 'wrs-admin-code-editor'); ?>.
						  	<% } else if (output_position == 'after') { %>
									<?php _e('Positioned <strong>after post content</strong>', 'wrs-admin-code-editor'); ?>.
						  	<% } %>
							  
							  <% if (wpautop_status) { %>
							  	<?php _e('wpautop <strong>enabled</strong>', 'wrs-admin-code-editor'); ?>.
							  <% } else { %>
									<?php _e('wpautop <strong>disabled</strong>', 'wrs-admin-code-editor'); ?>.
							  <% } %>
								
							</script>
							
					  </div>

					</div>

				</div>


	    </div>
	    <div role="tabpanel" class="tab-pane <?php if ($last_active_tab_id == 'css-edit' ) { echo 'active'; } ?>" id="css-edit">
	    	<div class="wp-ace__tab-panel-inner <?php if ($css_editor->get_code_compile_status() == 'error') { echo 'bg-danger'; } ?>" >
	    		<div class="wp-ace__tab-panel-inner__header">
						<?php if ($css_editor->get_code_compile_status() == 'error') { ?>
						<p class="wp-ace__notice wp-ace__notice--info text-error" >
		    			<span class="fa fa-exclamation-triangle" aria-hidden="true"></span>
		    			<span class="wp-ace__notice__text" ><?php echo $css_editor->get_code_compile_error_msg() ?></span>
		    		</p>
						<?php } else { ?>
							<p class="wp-ace__notice wp-ace__notice--info text-error" >&nbsp;</p>
						<?php } ?>
						<div class="btn-group">
						  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
						    <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu">
						    <li><a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" aria-controls="change-settings-modal" data-active-modal-tab="wp-ace-css-tab-link"  ><?php _e('Change CSS Settings', 'wrs-admin-code-editor'); ?></a></li>
						    <li><a href='#wp-ace--compiled-css-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false"  ><?php _e('View Compiled CSS', 'wrs-admin-code-editor'); ?></a></li>
						  </ul>
						</div>
					</div>
					<div class="wp-ace-editor">
						<pre id="wp-ace-css-pre-code-editor" style="height:<?php echo $css_editor->get_editor_height(); ?>px" class="code-content" ><?php echo htmlentities($css_editor->get_pre_code()); ?></pre>
						
						<input type="hidden" id="wp-ace-css-pre-code" name="wp-ace-css-pre-code" value="<?php echo htmlentities($css_editor->get_pre_code()); ?>" >
						
						<input type="hidden" id="wp-ace-css-field-height" name="wp-ace-css-field-height" class="field-height" value="<?php echo $css_editor->get_editor_height(); ?>" >

					</div>

		    	<div class="clearfix" >
					  <div class="" >
						  
					  	<p class="text-muted" ><span class="glyphicon glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span> <span id="wp-ace-css-status" ></span> </p>
						 	<script type="text/template" id="tmpl-wp-ace-css-status-template">
							  <% if (preprocessor == 'none') { %>
							  	<?php _e('No CSS Preprocessor selected', 'wrs-admin-code-editor'); ?>.
							  <% } else { %>
							  	<?php echo sprintf( __('Preprocessing with %1$s', 'wrs-admin-code-editor'), '<strong><%= preprocessor_nicename %></strong>'); ?>.
							  <% } %>
							  
							</script>
					  </div>

					</div>
				</div>
	    </div>
	    <div role="tabpanel" class="tab-pane <?php if ($last_active_tab_id == 'javascript-edit' ) { echo 'active'; } ?>" id="javascript-edit">
				<div class="wp-ace__tab-panel-inner <?php if ($js_editor->get_code_compile_status() == 'error') { echo 'bg-danger'; } ?>" >	
					<div class="wp-ace__tab-panel-inner__header">
						<?php if ($js_editor->get_code_compile_status() == 'error') { ?>
						<p class="wp-ace__notice wp-ace__notice--info text-error" >
		    			<span class="fa fa-exclamation-triangle" aria-hidden="true"></span>
		    			<span class="wp-ace__notice__text" ><?php echo $js_editor->get_code_compile_error_msg() ?></span>
		    		</p>
						<?php } else { ?>
							<p class="wp-ace__notice wp-ace__notice--info text-error" >&nbsp;</p>
						<?php } ?>
						<div class="btn-group">
						  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
						    <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu">
						    <li><a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" aria-controls="change-settings-modal"  data-active-modal-tab="wp-ace-javascript-tab-link" ><?php _e('Change Javascript Settings', 'wrs-admin-code-editor'); ?></a></li>
						    <li><a href='#wp-ace--compiled-js-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" ><?php _e('View Compiled JavaScript', 'wrs-admin-code-editor'); ?></a></li>
						  </ul>
						</div>
					</div>
					<div class="wp-ace-editor">
						<pre id="wp-ace-js-pre-code-editor" style="height:<?php echo $js_editor->get_editor_height(); ?>px" class="code-content" ><?php echo htmlentities($js_editor->get_pre_code()); ?></pre>
						
						<input type="hidden" id="wp-ace-js-pre-code" name="wp-ace-js-pre-code" value="<?php echo htmlentities($js_editor->get_pre_code()); ?>" >
						
						<input type="hidden" id="wp-ace-js-field-height" name="wp-ace-js-field-height" class="field-height" value="<?php echo $js_editor->get_editor_height(); ?>" >

					</div>

		    	<div class="clearfix" >
					  <div class="" >
						  
						  <p class="text-muted" ><span class="glyphicon glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span><span id="wp-ace-js-status" ></span>  </p>
						 	
						 	<script type="text/template" id="tmpl-wp-ace-js-status-template">
							  
							  <% if (preprocessor == 'none') { %>
							  	<?php _e('No JavaScript Preprocessor selected', 'wrs-admin-code-editor'); ?>.
							  <% } else { %>
									<?php echo sprintf( __('Preprocessing with %1$s', 'wrs-admin-code-editor'), '<strong><%= preprocessor_nicename %></strong>'); ?>.
							  <% } %>
							  
							  <% if (jquery_enqueued_status) { %>
							  	<?php _e('jQuery <strong>enqueued</strong>', 'wrs-admin-code-editor'); ?>.
							  <% } else { %>
									<?php _e('jQuery <strong>not enqueued</strong>', 'wrs-admin-code-editor'); ?>. 
							  <% } %>

							</script>

					  </div>
					</div>

				</div>
	    </div>

	  </div>  


		<div class="modal fade" tabindex="-1" role="dialog" id="wp-ace--compiled-html-modal" >
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
				  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  	<h4 class="modal-title"><?php _e('Compiled HTML <span class="text-muted" >(Read Only)</span>', 'wrs-admin-code-editor'); ?></h4>
				  </div>
				  <div class="modal-body">
		    		<div id="wp-ace__notice-container--html-php"></div>
		    		<div class="wp-ace-editor">
							<pre id="wp-ace-html-compiled-code-display" class="wp-ace-compiled-code-display" style="height:400px" ><?php echo htmlentities($html_php_editor->get_compiled_code()); ?></pre>
						</div>
				  </div>
				  <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'wrs-admin-code-editor'); ?></button>
		      </div>		
				</div>
			</div>
		</div>
		<div class="modal fade" tabindex="-1" role="dialog" id="wp-ace--compiled-css-modal" >
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
				  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  	<h4 class="modal-title"><?php _e('Compiled CSS <span class="text-muted" >(Read Only)</span>', 'wrs-admin-code-editor'); ?></h4>
				  </div>
				  <div class="modal-body">
		    		<div id="wp-ace__notice-container--css"></div>
		    		<div class="wp-ace-editor">
							<pre id="wp-ace-css-compiled-code-display" class="wp-ace-compiled-code-display" style="height:400px"  ><?php echo htmlentities($css_editor->get_compiled_code()); ?></pre>
							
						</div>	
				  </div>
				  <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'wrs-admin-code-editor'); ?></button>
		      </div>		
				</div>
			</div>
		</div>
		<div class="modal fade" tabindex="-1" role="dialog" id="wp-ace--compiled-js-modal" >
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
				  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  	<h4 class="modal-title"><?php _e('Compiled JavaScript <span class="text-muted" >(Read Only)</span>', 'wrs-admin-code-editor'); ?></h4>
				  </div>
				  <div class="modal-body">
		    		<div id="wp-ace__notice-container--js"></div>
		    		<div class="wp-ace-editor">
							<pre id="wp-ace-js-compiled-code-display" class="wp-ace-compiled-code-display" style="height:400px"  ><?php echo htmlentities($js_editor->get_compiled_code()); ?></pre>
							
						</div>	
				  </div>
				  <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'wrs-admin-code-editor'); ?></button>
		      </div>	
				</div>
			</div>
		</div>


	 

	  <!-- Settings Modal -->
		<div class="modal fade" tabindex="-1" role="dialog" id="change-settings-modal" >
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title"><?php _e('WP ACE Settings', 'wrs-admin-code-editor'); ?></h4>
		      </div>
		      <div class="modal-body">
  					
						<div>

						  <!-- Nav tabs -->
						  <ul class="nav nav-tabs" role="tablist">
						    <li role="presentation" class="active"><a href="#wp-ace-general" id="wp-ace-general-tab-link" aria-controls="wp-ace-general" role="tab" data-toggle="tab"><?php _e('General', 'wrs-admin-code-editor'); ?></a></li>
						    <li role="presentation"><a href="#wp-ace-html" id="wp-ace-html-tab-link"  aria-controls="wp-ace-html" role="tab" data-toggle="tab"><?php _e('HTML', 'wrs-admin-code-editor'); ?></a></li>
						    <li role="presentation"><a href="#wp-ace-css" id="wp-ace-css-tab-link"  aria-controls="wp-ace-css" role="tab" data-toggle="tab"><?php _e('CSS', 'wrs-admin-code-editor'); ?></a></li>
						    <li role="presentation"><a href="#wp-ace-javascript" id="wp-ace-javascript-tab-link"  aria-controls="wp-ace-javascript" role="tab" data-toggle="tab"><?php _e('JavaScript', 'wrs-admin-code-editor'); ?></a></li>
						  </ul>

						  <!-- Tab panes -->
						  <div class="tab-content">
						    <div role="tabpanel" class="tab-pane active" id="wp-ace-general">

						    	<div class="form-group">
										<h5><?php _e('Do not display WP ACE code on the following templates:', 'wrs-admin-code-editor'); ?> </h5>
										<div class="checkbox">
											<label><input type="checkbox" name="wp-ace-disabled-templates[]" <?php if (in_array("front-page", $disabled_templates)) { echo 'checked'; } ?> value="front-page" ><?php _e('Front Page', 'wrs-admin-code-editor'); ?> </label>
										</div>

										<div class="checkbox">
											<label ><input type="checkbox" name="wp-ace-disabled-templates[]" <?php if (in_array("home", $disabled_templates)) { echo 'checked'; }?> value="home" ><?php _e('Home', 'wrs-admin-code-editor'); ?></label>
										</div>
										
										<div class="checkbox">
											<label><input type="checkbox" name="wp-ace-disabled-templates[]" <?php if (in_array("archives", $disabled_templates)) { echo 'checked'; }?> value="archives" ><?php _e('Archives', 'wrs-admin-code-editor'); ?></label>	
										</div>
										
										<div class="checkbox">
											<label><input type="checkbox" name="wp-ace-disabled-templates[]" <?php if (in_array("search-results", $disabled_templates)) { echo 'checked'; }?> value="search-results" ><?php _e('Search Results', 'wrs-admin-code-editor'); ?></label>
										</div>
						    	</div>

						    	<div class="form-group">
										<h5><?php _e('Only display WP ACE code when:', 'wrs-admin-code-editor'); ?> </h5>
										<div class="checkbox">
											<label>
												<input type="checkbox" id="wp-ace-only-display-in-loop" name="wp-ace-only-display-in-loop" class="" value="1" <?php  checked($only_display_in_loop , '1') ?> >
												<?php _e('inside the loop', 'wrs-admin-code-editor'); ?>
												<span class="glyphicon glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right" title="<?php _e('Inside the loop description https://codex.wordpress.org/Function_Reference/in_the_loop', 'wrs-admin-code-editor'); ?>" aria-hidden="true"  ></span>									    	
											</label>	
										</div>
										
										<div class="checkbox">
											<label>
												<input type="checkbox"  id="wp-ace-only-display-in-main-query" name="wp-ace-only-display-in-main-query" class="" value="1" <?php  checked($only_display_in_main_query, '1') ?> >
												<?php _e('in main query', 'wrs-admin-code-editor'); ?>
												<span class="glyphicon glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right" title="<?php _e('Inside main query description https://codex.wordpress.org/Function_Reference/is_main_query', 'wrs-admin-code-editor'); ?>" aria-hidden="true"  ></span>									    	
											</label>	
										</div>
										<input type="hidden" name="wp-ace-last-active-tab" id="wp-ace-last-active-tab" value="<?php echo $last_active_tab_id; ?>" />									    	
									</div>
									
						    </div>
						    <div role="tabpanel" class="tab-pane" id="wp-ace-html">
	      					<div id="wp-ace-tab-content-html"></div>
	      					<script type="text/template" id="tmpl-wp-ace-html">
		      					<div>
											<h5><?php _e('Automatic Paragraphs', 'wrs-admin-code-editor'); ?></h5>
											<div class="checkbox">
												<label>
													<input type="checkbox"  id="wp-ace-html-php-disable-wpautop" name="wp-ace-html-php-disable-wpautop" class="field-editor-disable-wpautop" value="1" <?php checked($html_php_editor->get_disable_wpautop_status(), '1'); ?> >
													<?php _e('Disable wpautop', 'wrs-admin-code-editor'); ?> 
													<span class="glyphicon glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right" title="<?php _e('Automatically wraps in paragraph tag', 'wrs-admin-code-editor'); ?>" aria-hidden="true"  ></span>									    	
												</label>	
											</div>
																							
											
											<h5><?php _e('Position', 'wrs-admin-code-editor'); ?></h5>
											<div class="radio">
												<label class="radio"><input type="radio" name="wp-ace-html-php-code-position" value="before" <?php checked($html_php_editor->get_code_output_position(), 'before'); ?> ><?php _e('before post content', 'wrs-admin-code-editor'); ?> </label>
											</div>
											<div class="radio">
												<label class="radio"><input type="radio" name="wp-ace-html-php-code-position" value="after" <?php checked($html_php_editor->get_code_output_position(), 'after'); ?> ><?php _e('after post content', 'wrs-admin-code-editor'); ?></label>
											</div>
											
											
											<h5><?php _e('Pre Processor', 'wrs-admin-code-editor'); ?></h5>
											<div class="radio">
												<label class="radio"><input type="radio" <?php checked($html_php_editor->get_preprocessor(), 'none'); ?> value="none" name="wp-ace-html-php-preprocessor" ><?php _e('None', 'wrs-admin-code-editor'); ?></label>
											</div>
											
											<?php
												foreach($preprocessor_options['html'] as $preprocessor_slug => $preprocessor_name) {
													?>
														<div class="radio">
															<label class="radio"><input type="radio" <?php checked($html_php_editor->get_preprocessor(), $preprocessor_slug); ?> value="<?php echo $preprocessor_slug; ?>"  name="wp-ace-html-php-preprocessor" ><?php echo $preprocessor_name; ?></label>
														</div>
													<?php
												}

											?>					
										</div>	
	      					</script>

						    </div>
						    <div role="tabpanel" class="tab-pane" id="wp-ace-css">
									<div id="wp-ace-tab-content-css"></div>
									<script type="text/template" id="tmpl-wp-ace-css">
										<h5><?php _e('Pre Processor', 'wrs-admin-code-editor'); ?></h5>
		
										<div class="radio">
											<label class="radio"><input type="radio" <?php checked($css_editor->get_preprocessor(), 'none'); ?> value="none" name="wp-ace-css-preprocessor" ><?php _e('None', 'wrs-admin-code-editor'); ?></label>
										</div>
										
										<?php
											foreach($preprocessor_options['css'] as $preprocessor_slug => $preprocessor_name) {
												?>
													<div class="radio">
														<label class="radio"><input type="radio" <?php checked($css_editor->get_preprocessor(), $preprocessor_slug); ?> value="<?php echo $preprocessor_slug; ?>" name="wp-ace-css-preprocessor" ><?php echo $preprocessor_name; ?></label>
													</div>
												<?php
											}

										?>	
									</script>
						    </div>
						    <div role="tabpanel" class="tab-pane" id="wp-ace-javascript">
									<div id="wp-ace-tab-content-js"></div>
									<script type="text/template" id="tmpl-wp-ace-js">
										<h5><?php _e('Include Libraries', 'wrs-admin-code-editor'); ?></h5>
										<div class="checkbox">
											<label>
												<input type="checkbox"  id="wp-ace-css-include-jquery" name="wp-ace-css-include-jquery" value="1" <?php checked($js_editor->get_include_jquery_status(), '1'); ?> >
												<?php _e('Include jQuery', 'wrs-admin-code-editor'); ?>
											</label>	
										</div>

										<h5><?php _e('Pre Processor', 'wrs-admin-code-editor'); ?></h5>

										<div class="radio">
											<label class="radio"><input type="radio" <?php checked($js_editor->get_preprocessor(), 'none'); ?> value="none" name="wp-ace-js-preprocessor" ><?php _e('None', 'wrs-admin-code-editor'); ?></label>
										</div>
										
										<?php
											foreach($preprocessor_options['js'] as $preprocessor_slug => $preprocessor_name) {
												?>
													<div class="radio">
														<label class="radio"><input type="radio" <?php checked($js_editor->get_preprocessor(), $preprocessor_slug); ?> value="<?php echo $preprocessor_slug; ?>"  name="wp-ace-js-preprocessor" ><?php echo $preprocessor_name; ?></label>
													</div>
												<?php
											}

										?>	
									</script>

						    </div>
						  </div>

						</div>



		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'wrs-admin-code-editor'); ?></button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</div>
</div>