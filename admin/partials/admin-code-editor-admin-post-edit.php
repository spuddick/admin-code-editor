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
	<% } else if (preprocessor_has_changed) { %>
		<p class="wp-ace__notice wp-ace__notice--info text-warning" >
			<span class="fa fa-info-circle" aria-hidden="true"></span>
			<span class="wp-ace__notice__text" >
			<?php echo sprintf( __('Preprocessor has changed to %1$s. The following compiled code may not reflect the current preprocessed code.', 'wrs-admin-code-editor'), '<%= preprocessor_nicename %>'); ?>
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
<script type="text/template" id="tmpl-wp-ace--code--change-status">
	<input type="hidden" name="<%= code_change_slug %>"	id="<%= code_change_slug %>" value="<%= has_changed %>" />
</script>
<div class="wp-ace-bootstrap">
	<div>
		<!-- Nav tabs -->
		<ul id="wp-ace__tabs" class="nav nav-tabs" role="tablist">
			<?php if (!$general_settings->htmlEditorIsDisabled()) { ?>
			<li role="presentation" class="<?php if ($general_settings->getActiveAdminTab() == 'html-edit' ) { echo 'active'; } ?> ">
				<a href="#html-edit" aria-controls="html"  class=" <?php if ($html_php_editor->get_code_compile_status() == 'error') { echo 'bg-danger wp-ace__error-tab'; } ?> " role="tab" data-toggle="tab" >
					<?php _e('HTML', 'wrs-admin-code-editor'); ?>
					<span id="html-php-tab-label-preprocessor" class="text-muted" ></span>
				</a>
			</li>
			<?php } ?>
			<?php if (!$general_settings->cssEditorIsDisabled()) { ?>
			<li role="presentation" class="<?php if ($general_settings->getActiveAdminTab() == 'css-edit' ) { echo 'active'; } ?>">
				<a href="#css-edit" aria-controls="css" class=" <?php if ($css_editor->get_code_compile_status() == 'error') { echo 'bg-danger wp-ace__error-tab'; } ?>" role="tab" data-toggle="tab" >
					<?php _e('CSS', 'wrs-admin-code-editor'); ?>
					<span id="css-tab-label-preprocessor" class="text-muted" ></span>
				</a>
			</li>
			<?php } ?>
			<?php if (!$general_settings->jsEditorIsDisabled()) { ?>
			<li role="presentation" class="<?php if ($general_settings->getActiveAdminTab() == 'javascript-edit' ) { echo 'active'; } ?>">
				<a href="#javascript-edit" aria-controls="javascript" class=" <?php if ($js_editor->get_code_compile_status() == 'error') { echo 'bg-danger  wp-ace__error-tab'; } ?>" role="tab" data-toggle="tab" >
					<?php _e('JavaScript', 'wrs-admin-code-editor'); ?>
					<span id="js-tab-label-preprocessor" class="text-muted" ></span>
				</a>
			</li>
			<?php } ?>
			<li id="wp-ace__settings-tab" role="" >
				<a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false"  data-active-modal-tab="wp-ace-general-tab-link"  >
					<span class="fa fa-cogs" aria-hidden="true"></span> <?php _e('Settings', 'wrs-admin-code-editor'); ?>
				</a>
			</li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<?php if (!$general_settings->htmlEditorIsDisabled()) { ?>
				<div role="tabpanel" class="tab-pane <?php if ($general_settings->getActiveAdminTab() == 'html-edit' ) { echo 'active'; } ?>" id="html-edit">
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
									<li><a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" data-active-modal-tab="wp-ace-html-tab-link" aria-controls="change-settings-modal"><span class="fa fa-cog" aria-hidden="true"></span>  <?php _e('Change HTML Settings', 'wrs-admin-code-editor'); ?></a></li>
									<li><a href='#wp-ace--compiled-html-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false"  ><span class="fa fa-eye" aria-hidden="true"></span> <?php _e('View Compiled HTML', 'wrs-admin-code-editor'); ?></a></li>
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

									<% if (allow_searchable_html) { %>	
										<?php _e('Meta field created to <strong>allow for searchable HTML</strong>', 'wrs-admin-code-editor'); ?>.
									<% } else { %>
										<?php _e('<strong>HTML is not searchable</strong>', 'wrs-admin-code-editor'); ?>.
									<% } %>

								</script>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>

			<?php if (!$general_settings->cssEditorIsDisabled()) { ?>
				<div role="tabpanel" class="tab-pane <?php if ($general_settings->getActiveAdminTab() == 'css-edit' ) { echo 'active'; } ?>" id="css-edit">
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
									<li><a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" aria-controls="change-settings-modal" data-active-modal-tab="wp-ace-css-tab-link"  ><span class="fa fa-cog" aria-hidden="true"></span> <?php _e('Change CSS Settings', 'wrs-admin-code-editor'); ?></a></li>
									<li><a href='#wp-ace--compiled-css-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false"  ><span class="fa fa-eye" aria-hidden="true"></span> <?php _e('View Compiled CSS', 'wrs-admin-code-editor'); ?></a></li>
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
									<% if (isolation_mode == 'full-web-page') { %>
										<?php _e('CSS applied to <strong>full web page</strong> (no isolation)', 'wrs-admin-code-editor'); ?>.
									<% } else if (isolation_mode == 'page-content-plus-html-editor'){ %>
										<?php _e('CSS isolated to <strong>post content and HTML editor code</strong>', 'wrs-admin-code-editor'); ?>.
									<% } else if (isolation_mode == 'html-editor'){ %>
										<?php _e('CSS isolated to <strong>HTML editor code</strong>', 'wrs-admin-code-editor'); ?>.
									<% } %>
								</script>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>

			<?php if (!$general_settings->jsEditorIsDisabled()) { ?>
				<div role="tabpanel" class="tab-pane <?php if ($general_settings->getActiveAdminTab() == 'javascript-edit' ) { echo 'active'; } ?>" id="javascript-edit">
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
									<li><a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" aria-controls="change-settings-modal"  data-active-modal-tab="wp-ace-javascript-tab-link" ><span class="fa fa-cog" aria-hidden="true"></span> <?php _e('Change Javascript Settings', 'wrs-admin-code-editor'); ?></a></li>
									<li><a href='#wp-ace--compiled-js-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" ><span class="fa fa-eye" aria-hidden="true"></span> <?php _e('View Compiled JavaScript', 'wrs-admin-code-editor'); ?></a></li>
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
			<?php } ?>  
		</div>  
		<p class="wp-ace--web-rockstar--callout" ><small>Admin Code Editor is built by <a href='https://webrockstar.net?utm_source=admin-code-editor-plugin&utm_medium=editor-footer' target="_blank">webrockstar.net</a>. If you find this plugin useful, please consider <a href='https://wordpress.org/plugins/admin-code-editor/#reviews' target="_blank">giving a rating</a>. Thanks.</small></p>
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
						<h4 class="modal-title"><span class="fa fa-cogs" aria-hidden="true"></span> <?php _e('Admin Code Editor Settings', 'wrs-admin-code-editor'); ?></h4>
					</div>
					<div class="modal-body">
						<div>
							<!-- Nav tabs -->
							<ul class="nav nav-tabs" role="tablist">
								
								<?php if (!$general_settings->htmlEditorIsDisabled()) { ?>
									<li role="presentation"><a href="#wp-ace-html" id="wp-ace-html-tab-link"  aria-controls="wp-ace-html" role="tab" data-toggle="tab"><?php _e('HTML', 'wrs-admin-code-editor'); ?></a></li>
								<?php } ?>
								<?php if (!$general_settings->cssEditorIsDisabled()) { ?>
									<li role="presentation"><a href="#wp-ace-css" id="wp-ace-css-tab-link"  aria-controls="wp-ace-css" role="tab" data-toggle="tab"><?php _e('CSS', 'wrs-admin-code-editor'); ?></a></li>
								<?php } ?>	
								<?php if (!$general_settings->jsEditorIsDisabled()) { ?>
									<li role="presentation"><a href="#wp-ace-javascript" id="wp-ace-javascript-tab-link"  aria-controls="wp-ace-javascript" role="tab" data-toggle="tab"><?php _e('JavaScript', 'wrs-admin-code-editor'); ?></a></li>
								<?php } ?>
								<li role="presentation" class="active pull-right"><a href="#wp-ace-general" id="wp-ace-general-tab-link" aria-controls="wp-ace-general" role="tab" data-toggle="tab"><?php _e('General', 'wrs-admin-code-editor'); ?></a></li>	
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="wp-ace-general">
									<div class="form-group">
										<h5><?php _e('Only display when:', 'wrs-admin-code-editor'); ?> </h5>
										<div class="checkbox">
											<label>
												<input type="checkbox" id="wp-ace-only-display-in-loop" name="wp-ace-only-display-in-loop" class="" value="1" <?php  checked($general_settings->getOnlyDisplayInLoopStatus() ) ?> >
												<?php _e('Inside the Loop', 'wrs-admin-code-editor'); ?>									
											</label>	
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox"  id="wp-ace-only-display-in-main-query" name="wp-ace-only-display-in-main-query" class="" value="1" <?php  checked($general_settings->getOnlyDisplayInMainQueryStatus()) ?> >
												<?php _e('In Main Query', 'wrs-admin-code-editor'); ?>									
											</label>	
										</div>
										<input type="hidden" name="wp-ace-last-active-tab" id="wp-ace-last-active-tab" value="<?php echo $general_settings->getActiveAdminTab(); ?>" />									    
									</div>									
									<div class="form-group">
										<h5><?php _e('Do not display Admin Code Editor code on the following templates:', 'wrs-admin-code-editor'); ?> </h5>
										<div class="checkbox">
											<label><input type="checkbox" name="wp-ace-disabled-templates[]" <?php if ($general_settings->frontPageTemplateIsDisabled()) { echo 'checked'; } ?> value="front-page" ><?php _e('Front Page', 'wrs-admin-code-editor'); ?> </label>
										</div>
										<div class="checkbox">
											<label ><input type="checkbox" name="wp-ace-disabled-templates[]" <?php if ($general_settings->homeTemplateIsDisabled()) { echo 'checked'; }?> value="home" ><?php _e('Home', 'wrs-admin-code-editor'); ?></label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" name="wp-ace-disabled-templates[]" <?php if ($general_settings->archiveTemplateIsDisabled()) { echo 'checked'; }?> value="archives" ><?php _e('Archives', 'wrs-admin-code-editor'); ?></label>	
										</div>
										<div class="checkbox">
											<label><input type="checkbox" name="wp-ace-disabled-templates[]" <?php if ($general_settings->searchTemplateIsDisabled()) { echo 'checked'; }?> value="search-results" ><?php _e('Search Results', 'wrs-admin-code-editor'); ?></label>
										</div>
									</div>

								</div>
								<div role="tabpanel" class="tab-pane" id="wp-ace-html">
									<div id="wp-ace-tab-content-html"></div>
									<script type="text/template" id="tmpl-wp-ace-html">
										<div>
											<h5><?php _e('Preprocessor', 'wrs-admin-code-editor'); ?></h5>
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
											<h5><?php _e('Position', 'wrs-admin-code-editor'); ?></h5>
											<div class="radio">
												<label class="radio"><input type="radio" name="wp-ace-html-php-code-position" value="before" <?php checked($html_php_editor->get_code_output_position(), 'before'); ?> ><?php _e('Before Post Content', 'wrs-admin-code-editor'); ?> </label>
											</div>	
											<div class="radio">
												<label class="radio"><input type="radio" name="wp-ace-html-php-code-position" value="after" <?php checked($html_php_editor->get_code_output_position(), 'after'); ?> ><?php _e('After Post Content', 'wrs-admin-code-editor'); ?></label>
											</div>

											<h5><?php _e('Allow Searchable HTML', 'wrs-admin-code-editor'); ?> </h5>
											<div class="checkbox">
												<label>
													<input type="checkbox"  id="wp-ace-html-php-allow-searchable-html" name="wp-ace-html-php-allow-searchable-html" value="1" <?php checked($html_php_editor->get_allow_searchable_html_status(), '1'); ?> >
													<?php _e('Yes', 'wrs-admin-code-editor'); ?> 
													<?php

														$title_text = sprintf( __('Creates a hidden meta field %1$s containing the tag-stripped HTML, which can be used by extended search plugins', 'wrs-admin-code-editor'), '<em>_wp_ace_html_php_filtered_html</em>' );
													?> 

													<a href="#" data-toggle="tooltip" data-placement="right" data-html="true" title="<?php echo $title_text; ?>"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
												</label>	
											</div>

										</div>	
									</script>
									<div id="wp-ace--html-php--changed-flag-container"></div>
								</div>
								<div role="tabpanel" class="tab-pane" id="wp-ace-css">
									<div id="wp-ace-tab-content-css"></div>
									<script type="text/template" id="tmpl-wp-ace-css">
										<h5><?php _e('Preprocessor', 'wrs-admin-code-editor'); ?></h5>
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

										<div class="form-group">
											<h5><?php _e('Isolation Mode', 'wrs-admin-code-editor'); ?></h5>
											<div class="radio">
												<label class="radio"><input type="radio" <?php checked($css_editor->get_isolation_mode(), 'full-web-page'); ?> value="full-web-page" name="wp-ace-css-isolation-mode" ><?php _e('Full Web Page', 'wrs-admin-code-editor'); ?>
												 <a href="#" data-toggle="tooltip" data-placement="right" data-html="true" title="<?php _e('CSS will be applied to entire webpage', 'wrs-admin-code-editor'); ?>"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
												</label>
											</div>
											<div class="radio">
												<label class="radio"><input type="radio" <?php checked($css_editor->get_isolation_mode(), 'page-content-plus-html-editor'); ?> value="page-content-plus-html-editor" name="wp-ace-css-isolation-mode" ><?php _e('Post Content + HTML Editor Code', 'wrs-admin-code-editor'); ?>
												 <a href="#" data-toggle="tooltip" data-placement="right" data-html="true" title="<?php _e('CSS will be isolated to WordPress post content and Code Editor HTML', 'wrs-admin-code-editor'); ?>"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
												</label>
											</div>
											<div class="radio">
												<label class="radio"><input type="radio" <?php checked($css_editor->get_isolation_mode(), 'html-editor'); ?> value="html-editor" name="wp-ace-css-isolation-mode" ><?php _e('HTML Editor Code', 'wrs-admin-code-editor'); ?>
												 <a href="#" data-toggle="tooltip" data-placement="right" data-html="true" title="<?php _e('CSS will be isolated to Code Editor HTML', 'wrs-admin-code-editor'); ?>"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
												</label>
											</div>
										</div>

									</script>
									<div id="wp-ace--css--changed-flag-container"></div>
								</div>
								<div role="tabpanel" class="tab-pane" id="wp-ace-javascript">
									<div id="wp-ace-tab-content-js"></div>
									<script type="text/template" id="tmpl-wp-ace-js">
										<h5><?php _e('Preprocessor', 'wrs-admin-code-editor'); ?></h5>
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
										<h5><?php _e('Include Libraries', 'wrs-admin-code-editor'); ?></h5>
										<div class="checkbox">
											<label>
												<input type="checkbox"  id="wp-ace-js-include-jquery" name="wp-ace-js-include-jquery" value="1" <?php checked($js_editor->get_include_jquery_status(), '1'); ?> >
												<?php _e('Include jQuery', 'wrs-admin-code-editor'); ?>
											</label>	
										</div>
									</script>
									<div id="wp-ace--js--changed-flag-container"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<?php if (current_user_can('manage_options')) { ?>
							<a href="<?php menu_page_url( 'admin-code-editor-options-page', true ); ?>" type="button" class="btn btn-default pull-left" >
								<span class="fa fa-sliders" aria-hidden="true"></span>
								<?php _e('Manage Default Settings', 'wrs-admin-code-editor'); ?>
							</a>
						<?php } ?>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							<span class="fa fa-times" aria-hidden="true"></span>
							<?php _e('Close', 'wrs-admin-code-editor'); ?>
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</div>
</div>