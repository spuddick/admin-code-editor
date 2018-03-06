Backbone.Model.prototype._super = function(funcName){
	return this.constructor.__super__[funcName].apply(this, _.rest(arguments));
}

var wpAceInterface = (function() {
 
		const ACE_THEME 		= "ace/theme/monokai";
		const ACE_WRAP_MODE = true;
		const ACE_TAB_SIZE 	= 2;
		const ACE_FONT_SIZE = '13px';


		var html_editor, css_editor, js_editor;
		var html_code_model, css_code_model, js_code_model;
		var html_tab_label_preprocessor_view, css_tab_label_preprocessor_view, js_tab_label_preprocessor_view;
		var html_text_status_view, css_text_status_view, js_text_status_view;
		var html_settings_view, css_settings_view, js_settings_view;
		var html_update_notice_view, css_update_notice_view, js_update_notice_view;
		var html_change_flag, css_change_flag, js_change_flag;
		var form_submitting = false;
		
		// Backbone Models
		var Code_Model = Backbone.Model.extend({
			defaults : {
				code_has_changed 							: 0,
				has_changed 									: 0,
				preprocessor_has_changed			: 0,
				preprocessed_code_has_errors 	: 0,
				preprocessor_nicename_map 		: '',
				preprocessor_nicename 				: ''
			},
			updatePreprocessor: function($preprocessor_obj) {
				var new_mode = $preprocessor_obj.val();

				this.get('ace_editor').update_mode(new_mode);
				this.set({
					preprocessor: new_mode
				});
				this.set({
					preprocessor_nicename: this.get('preprocessor_nicename_map')[new_mode]
				});
				this.set({preprocessor_has_changed: 1});
			},
			updateChangedStatus : function(){
				this.set({has_changed: 1});
			},
			updateCodeChangedStatus : function(){
				this.set({code_has_changed: 1});
			}
		});			
		
		var HTML_Code_Model = Code_Model.extend({
			updateCodePosition: function() {
				var code_position = jQuery('input[name=wp-ace-html-php-code-position]:checked').val();
				console.log('updating code position status'); 
				this.set({
					output_position : code_position
				});
			},
			updateDisableWPautopStatus: function() {
				var status = 0;
				if (jQuery('input#wp-ace-html-php-disable-wpautop').is(":checked")) {
					status = 1;
				} 
				this.set({
					wpautop_status : status
				});
			},
			updateAllowSearchableHTMLStatus: function() {
				var status = 0;
				if (jQuery('input#wp-ace-html-php-allow-searchable-html').is(":checked")) {
					status = 1;
				}
				console.log('updating searchable status'); 
				this.set({
					allow_searchable_html : status
				});
			},
			initialize: function(){
				this.set({
					preprocessor_nicename_map : {
						none 			: 'HTML',
						haml 			: 'HAML',
						markdown 	: 'MarkDown'
					}
				});
				this.set({
					preprocessor_nicename: this.get('preprocessor_nicename_map')[this.get('preprocessor')]
				});
				
			}
		});

		_.extend(HTML_Code_Model.prototype.defaults, Code_Model.prototype.defaults);
		
		var CSS_Code_Model = Code_Model.extend({
			updateIsolationMode: function() {
				var isolation_mode = jQuery('input[name=wp-ace-css-isolation-mode]:checked').val();
				this.set({
					isolation_mode : isolation_mode
				});
			},
			initialize: function(){
				this.set({
					preprocessor_nicename_map : {
						none 		: 'CSS',
						scss 		: 'SCSS',
						less 		: 'LESS',
						stylus 	: 'Stylus'
					}
				});
				this.set({
					preprocessor_nicename: this.get('preprocessor_nicename_map')[this.get('preprocessor')]
				});

			}  
		});
		var JS_Code_Model = Code_Model.extend({

			updateIncludeJqueryStatus: function() {
				var status = 0;
				if (jQuery('input#wp-ace-js-include-jquery').is(":checked")) {
					status = 1;
				} 
				this.set({
					jquery_enqueued_status : status 
				});
			},
			initialize: function(){
				this.set({
					preprocessor_nicename_map : {
						none : 'JavaScript',
						coffee : 'CoffeeScript'
					}
				});
				this.set({
					preprocessor_nicename: this.get('preprocessor_nicename_map')[this.get('preprocessor')]
				});
			} 
		});


		// Backbone Views
		var Tab_Label_View = Backbone.View.extend({
			model: Code_Model,
			tagName: 'span',
			template: '',

			initialize: function() {
				this.template = _.template(jQuery('#tab-label-preprocessor-template').html());
				this.listenTo(this.model, "change", this.render);
			},
			render: function() {
				this.$el.html(this.template(this.model.attributes));
				return this;
			}
		});

		var HTML_Text_Status_View = Backbone.View.extend({
			model: HTML_Code_Model,
			tagName: 'span',
			template: '',
			initialize: function() {
				this.template = _.template(jQuery('#tmpl-wp-ace-html-php-status-template').html());
				this.listenTo(this.model, "change", this.render);
			},
			render: function() {
				this.$el.html(this.template(this.model.attributes));
				return this;
			}
		});

		var CSS_Text_Status_View = Backbone.View.extend({
			model: CSS_Code_Model,
			tagName: 'span',
			template: '',
			initialize: function() {
				this.template = _.template(jQuery('#tmpl-wp-ace-css-status-template').html());
				this.listenTo(this.model, "change", this.render);
			},
			render: function() {
				this.$el.html(this.template(this.model.attributes));
				return this;
			}
		});

		var JS_Text_Status_View = Backbone.View.extend({
			model: JS_Code_Model,
			tagName: 'span',
			template: '',
			initialize: function() {
				this.template = _.template(jQuery('#tmpl-wp-ace-js-status-template').html());
				this.listenTo(this.model, "change", this.render);
			},
			render: function() {
				this.$el.html(this.template(this.model.attributes));
				return this;
			}
		});
		
		var HTML_Settings_View = Backbone.View.extend({
			model: HTML_Code_Model,
			tagName: 'div',
			template: '',
			events: {
				'change input[name=wp-ace-html-php-code-position]'	: 'codePositionChange',
				'change input#wp-ace-html-php-disable-wpautop'			: 'disableWPautopChange',
				'change input[name=wp-ace-html-php-preprocessor]'		: 'preprocessorChange',
				'change input[name=wp-ace-html-php-allow-searchable-html]'		: 'searchableHTMLChange'
			},
			initialize: function() {
				console.log('html template: ' + jQuery('#tmpl-wp-ace-html').html().length);
				this.template = _.template(jQuery('#tmpl-wp-ace-html').html());
				jQuery('input[name=wp-ace-html-php-preprocessor]').trigger('change');
				jQuery('input#wp-ace-html-php-disable-wpautop').trigger('change');
				jQuery('input[name=wp-ace-html-php-allow-searchable-html]').trigger('change');

				console.log('checkbox: ' + jQuery('input[name=wp-ace-html-php-allow-searchable-html]').length);
				console.log('preprocessor: ' + jQuery('input[name=wp-ace-html-php-preprocessor]').length);
			},
			codePositionChange: function(e) {
				e.preventDefault();
				this.model.updateCodePosition();
				this.model.updateChangedStatus();
			},
			disableWPautopChange: function(e) {
				e.preventDefault();
				this.model.updateDisableWPautopStatus();
				this.model.updateChangedStatus();
			},
			preprocessorChange: function(e) {
				e.preventDefault();
				this.model.updatePreprocessor(jQuery(e.currentTarget));
				this.model.updateChangedStatus();
			},
			searchableHTMLChange: function(e) {
				e.preventDefault();
				this.model.updateAllowSearchableHTMLStatus();
				this.model.updateChangedStatus();
				console.log('in searchable change');
			},
			render: function() {
				this.$el.html(this.template(this.model.attributes));
				return this;
			}
		});
		
		var CSS_Settings_View = Backbone.View.extend({
			model: CSS_Code_Model,
			tagName: 'div',
			template: '',
			events: {
				'change input[name=wp-ace-css-preprocessor]': 'preprocessorChange',
				'change input[name=wp-ace-css-isolation-mode]': 'isolationModeChange'
			},
			initialize: function() {
				this.template = _.template(jQuery('#tmpl-wp-ace-css').html());
				jQuery('input[name=wp-ace-css-preprocessor]').trigger('change');
			},
			preprocessorChange: function(e) {
				e.preventDefault();
				this.model.updatePreprocessor(jQuery(e.currentTarget));
				this.model.updateChangedStatus();
			},
			isolationModeChange: function(e) {
				e.preventDefault();
				this.model.updateIsolationMode();
				this.model.updateChangedStatus();
			},
			render: function() {
				this.$el.html(this.template(this.model.attributes));
				return this;
			}
		});

		var JS_Settings_View = Backbone.View.extend({
			model: JS_Code_Model,
			tagName: 'div',
			template: '',
			events: {
				'change input#wp-ace-js-include-jquery': 'includeJqueryChange',
				'change input[name=wp-ace-js-preprocessor]': 'preprocessorChange'
			},
			initialize: function() {
				console.log('js template: ' + jQuery('#tmpl-wp-ace-js').html().length);
				this.template = _.template(jQuery('#tmpl-wp-ace-js').html());
				jQuery('input#wp-ace-css-include-jquery').trigger('change');
				jQuery('input[name=wp-ace-js-preprocessor]').trigger('change');
			},
			includeJqueryChange: function(e) {
				e.preventDefault();
				this.model.updateIncludeJqueryStatus();
				this.model.updateChangedStatus();
			},
			preprocessorChange: function(e) {
				e.preventDefault();
				this.model.updatePreprocessor(jQuery(e.currentTarget));
				this.model.updateChangedStatus();
			},
			render: function() {
				this.$el.html(this.template(this.model.attributes));
				return this;
			}
		});
		
		var Code_Update_Notice_View = Backbone.View.extend({
			tagName: 'div',
			initialize: function() {
				this.template = _.template(jQuery('#tmpl-wp-ace-code-changed').html());
				this.listenTo(this.model, "change", this.render);
			},
			render: function() {
				this.$el.html(this.template(this.model.attributes));
				return this;
			}
		});

		var Code_Settings_Change_Flag_View = Backbone.View.extend({
			tagName: 'div',
			initialize: function() {
				this.template = _.template(jQuery('#tmpl-wp-ace--code--change-status').html());
				this.listenTo(this.model, "change", this.render);
			},
			render: function() {
				this.$el.html(this.template(this.model.attributes));
				return this;
			}
		});

		var init = function() {

			// Initialize Bootstrap tooltips
			jQuery('[data-toggle="tooltip"]').tooltip(); 

			// Last active tab listener
			jQuery('.wp-ace-bootstrap #wp-ace__tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				e.target // newly activated tab
				e.relatedTarget // previous active tab
				var tab_id = jQuery(e.target).attr('href').replace('#','');
				
				jQuery('#wp-ace-last-active-tab').val(tab_id);

			})

			// Initialize ACE code editors
			if (jQuery('#wp-ace-html-php-pre-code-editor').length ) {
				
				// ACE HTML Editor set up 
				html_editor = ace.edit("wp-ace-html-php-pre-code-editor");
				html_editor.setTheme(ACE_THEME);
				html_editor.getSession().setUseWrapMode(ACE_WRAP_MODE);
				html_editor.getSession().setTabSize(ACE_TAB_SIZE);
				html_editor.getSession().on('change', function() {
					html_code_model.updateChangedStatus();
					html_code_model.updateCodeChangedStatus();
				});
				html_editor.update_mode = function(mode) {
					if (mode == 'none') {
						mode = 'html';
					}
					html_editor.getSession().setMode("ace/mode/" + mode);
				};

				jQuery('#wp-ace-html-php-pre-code-editor').css('font-size', ACE_FONT_SIZE);
				html_editor.hidden_input_id = 'wp-ace-html-php-pre-code';
				html_editor.update_mode(wpcr_data['wp-ace-html-php-preprocessor']); 
				
				// Backbone HTML model set up
				html_code_model = new HTML_Code_Model({ 
					preprocessor 									: wpcr_data['wp-ace-html-php-preprocessor'], 
					ace_editor 										: html_editor,
					output_position 							: wpcr_data['wp-ace-html-php-code-position'],
					wpautop_status 								: wpcr_data['wp-ace-html-php-disable-wpautop'],
					allow_searchable_html 				: parseInt(wpcr_data['wp-ace-html-php-allow-searchable-html']),
					post_type_name 								: wpcr_data['wp-ace-post-type-singular-name'],
					preprocessed_code_has_errors 	: (wpcr_data['wp-ace-html-php-compile-status'] == 'error' ? 1 : 0),
					code_change_slug 							: 'wp-ace--html-php--changed-flag'	 
				});

				// Backbone HTML views set up		  
				html_tab_label_preprocessor_view 	= new Tab_Label_View({ el: jQuery("#html-php-tab-label-preprocessor"), model: html_code_model });
				html_tab_label_preprocessor_view.render();
				html_text_status_view 						= new HTML_Text_Status_View({ el: jQuery("#wp-ace-html-php-status"), model: html_code_model });
				html_text_status_view.render();
				html_settings_view 								= new HTML_Settings_View({ el: jQuery("#wp-ace-tab-content-html"), model: html_code_model });
				html_settings_view.render();
				html_update_notice_view 					= new Code_Update_Notice_View({ el: jQuery("#wp-ace__notice-container--html-php"), model: html_code_model });
				html_update_notice_view.render();
				html_change_flag 									= new Code_Settings_Change_Flag_View({el: jQuery("#wp-ace--html-php--changed-flag-container"), model: html_code_model });
				html_change_flag.render();

				// Resizable ACE code editors 
				jQuery('#wp-ace-html-php-pre-code-editor').resizable({
					ghost: true,
					handles: "s",
					stop: function( event, ui ) {
						html_editor.resize();
						height = ui.element.height();
						ui.element.siblings('.field-height').val(height);
						html_code_model.updateChangedStatus();
					}
				});
			}

			if (jQuery('#wp-ace-css-pre-code-editor').length ) {
				
				// ACE CSS editor set up
				css_editor = ace.edit("wp-ace-css-pre-code-editor");
				css_editor.setTheme(ACE_THEME);
				css_editor.getSession().setUseWrapMode(ACE_WRAP_MODE);
				css_editor.getSession().setTabSize(ACE_TAB_SIZE);
				css_editor.getSession().on('change', function() {
					css_code_model.updateChangedStatus();
					css_code_model.updateCodeChangedStatus();
				});
				css_editor.update_mode = function(mode) {
					if (mode == 'none') {
						mode = 'css';
					}
					css_editor.getSession().setMode("ace/mode/" + mode);
				};
				jQuery('#wp-ace-css-pre-code-editor').css('font-size', ACE_FONT_SIZE);
				css_editor.hidden_input_id = 'wp-ace-css-pre-code';
				css_editor.update_mode(wpcr_data['wp-ace-css-preprocessor']);

				// Backbone CSS model set up
				css_code_model = new CSS_Code_Model({ 
					preprocessor 									: wpcr_data['wp-ace-css-preprocessor'], 
					ace_editor 										: css_editor,
					post_type_name 								: wpcr_data['wp-ace-post-type-singular-name'],
					preprocessed_code_has_errors 	: (wpcr_data['wp-ace-css-compile-status'] == 'error' ? 1 : 0),
					code_change_slug 							: 'wp-ace--css--changed-flag',
					isolation_mode 								: wpcr_data['wp-ace-css-isolation-mode']		   
				});

				// Backbone CSS view set up		  
				css_tab_label_preprocessor_view 	= new Tab_Label_View({ el: jQuery("#css-tab-label-preprocessor"), model: css_code_model });
				css_tab_label_preprocessor_view.render();
				css_text_status_view 							= new CSS_Text_Status_View({ el: jQuery("#wp-ace-css-status"), model: css_code_model }); 
				css_text_status_view.render();
				css_settings_view 								= new CSS_Settings_View({ el: jQuery("#wp-ace-tab-content-css"), model: css_code_model }); 
				css_settings_view.render();
				css_update_notice_view 						= new Code_Update_Notice_View({ el: jQuery("#wp-ace__notice-container--css"), model: css_code_model });
				css_update_notice_view.render();
				css_change_flag 									= new Code_Settings_Change_Flag_View({ el: jQuery("#wp-ace--css--changed-flag-container"), model: css_code_model });
				css_change_flag.render();

				// Resizable ACE code editors 
				jQuery('#wp-ace-css-pre-code-editor').resizable({
					ghost: true,
					handles: "s",
					stop: function( event, ui ) {
						css_editor.resize();
						height = ui.element.height();
						ui.element.siblings('.field-height').val(height);
						css_code_model.updateChangedStatus();
					}
				});
	
			}

			if (jQuery('#wp-ace-js-pre-code-editor').length ) {
				
				// ACE JS editor set up
				js_editor = ace.edit("wp-ace-js-pre-code-editor");
				js_editor.setTheme(ACE_THEME);
				js_editor.getSession().setUseWrapMode(ACE_WRAP_MODE);
				js_editor.getSession().setTabSize(ACE_TAB_SIZE);
				js_editor.getSession().on('change', function() {
					js_code_model.updateChangedStatus();
					js_code_model.updateCodeChangedStatus();
				});
				js_editor.update_mode = function(mode) {
					if (mode == 'none') {
						mode = 'javascript';
					}
					js_editor.getSession().setMode("ace/mode/" + mode);
				};
				jQuery('#wp-ace-js-pre-code-editor').css('font-size', ACE_FONT_SIZE);
				js_editor.hidden_input_id = 'wp-ace-js-pre-code';
				js_editor.update_mode(wpcr_data['wp-ace-js-preprocessor']);

				// Backbone JS model set up
				js_code_model = new JS_Code_Model({ 
					preprocessor 									: wpcr_data['wp-ace-js-preprocessor'], 
					ace_editor 										: js_editor,
					jquery_enqueued_status 				: wpcr_data['wp-ace-js-include-jquery'],
					post_type_name 								: wpcr_data['wp-ace-post-type-singular-name'],
					preprocessed_code_has_errors 	: (wpcr_data['wp-ace-js-compile-status'] == 'error' ? 1 : 0),
					code_change_slug 							: 'wp-ace--js--changed-flag'		  
				});

				// Backbone JS view set up	
				js_tab_label_preprocessor_view 	= new Tab_Label_View({ el: jQuery("#js-tab-label-preprocessor"), model: js_code_model });
				js_tab_label_preprocessor_view.render();
				js_text_status_view 						= new JS_Text_Status_View({ el: jQuery("#wp-ace-js-status"), model: js_code_model });
				js_text_status_view.render();
				js_settings_view 								= new JS_Settings_View({ el: jQuery("#wp-ace-tab-content-js"), model: js_code_model });
				js_settings_view.render();
				js_update_notice_view 					= new Code_Update_Notice_View({ el: jQuery("#wp-ace__notice-container--js"), model: js_code_model });
				js_update_notice_view.render();
				js_change_flag 									= new Code_Settings_Change_Flag_View({ el: jQuery("#wp-ace--js--changed-flag-container"), model: js_code_model });
				js_change_flag.render();			  

				// Resizable ACE code editors 
				jQuery('#wp-ace-js-pre-code-editor').resizable({
					ghost: true,
					handles: "s",
					stop: function( event, ui ) {
						js_editor.resize();
						height = ui.element.height();
						ui.element.siblings('.field-height').val(height);
						js_code_model.updateChangedStatus();
					}
				});

			}

			// ACE compiled code display
			if (jQuery('#wp-ace-html-compiled-code-display').length ) {
				html_display = ace.edit("wp-ace-html-compiled-code-display");
				html_display.setTheme(ACE_THEME);
				html_display.getSession().setMode("ace/mode/html");
				html_display.getSession().setUseWrapMode(ACE_WRAP_MODE);
				html_display.getSession().setTabSize(ACE_TAB_SIZE);
				html_display.setReadOnly(true);
			}
			if (jQuery('#wp-ace-css-compiled-code-display').length ) {
				css_display = ace.edit("wp-ace-css-compiled-code-display");
				css_display.setTheme(ACE_THEME);
				css_display.getSession().setMode("ace/mode/css");
				css_display.getSession().setUseWrapMode(ACE_WRAP_MODE);
				css_display.getSession().setTabSize(ACE_TAB_SIZE);
				css_display.setReadOnly(true);
			}
			if (jQuery('#wp-ace-js-compiled-code-display').length ) {
				js_display = ace.edit("wp-ace-js-compiled-code-display");
				js_display.setTheme(ACE_THEME);
				js_display.getSession().setMode("ace/mode/javascript");
				js_display.getSession().setUseWrapMode(ACE_WRAP_MODE);
				js_display.getSession().setTabSize(ACE_TAB_SIZE);
				js_display.setReadOnly(true);
			}	  

			jQuery('#change-settings-modal').on('show.bs.modal', function (e) {
				// Proper tab activation in modal display
				//var $clicked_anchor = jQuery(e.relatedTarget);
				//jQuery('#' + $clicked_anchor.data('active-modal-tab')).tab('show');

				// adjust WordPress interface z-indexes for modal
				jQuery('body').addClass('wp-ace-modal-active');
			});

			jQuery('#change-settings-modal').on('hide.bs.modal', function (e) {
			  // adjust WordPress interface z-indexes for modal
				jQuery('body').removeClass('wp-ace-modal-active');
			})

			// Notice if leaving page without saving
			jQuery(window).on("beforeunload", function() {
				if ((
					(typeof html_editor == 'undefined' 	|| html_code_model.get('has_changed')) || 
					(typeof css_editor == 'undefined' 	|| css_code_model.get('has_changed')) || 
					(typeof js_editor == 'undefined' 		|| js_code_model.get('has_changed'))) && 
					!form_submitting )  {
					return true;
				}
				return;
			});
			jQuery( "form#post" ).submit(function( event ) {
				// don't show notice if form is submitting
				form_submitting = true;
			});

			// If previous active tab not present in HTML output (due to code type being disabled), select the first available tab
			 if (!jQuery('ul#wp-ace__tabs > li.active').length) {
				jQuery('ul#wp-ace__tabs > li:first-child a').tab('show')
			 }
		};

		// Before form submit, copy ACE editor code content into hidden form input
		var setInputMappingListeners = function() {
			jQuery( "form#post" ).submit(function( event ) {
				if (typeof html_editor != 'undefined') {
					mapEditorCodetoInput(html_editor);
				}
				if (typeof css_editor != 'undefined') {
					mapEditorCodetoInput(css_editor);
				}
				if (typeof js_editor != 'undefined') {
					mapEditorCodetoInput(js_editor);
				}
			});
		};
 
		var mapEditorCodetoInput = function(editor_obj) {
			$editor_input = jQuery('#' + editor_obj.hidden_input_id);
			if ( (typeof editor_obj !== 'undefined') && $editor_input.val() != editor_obj.getSession().getValue() ) {
				$editor_input.val(editor_obj.getSession().getValue());
			}
		};

		// Public API
		return {
				init: init,
				setInputMappingListeners: setInputMappingListeners
		};
})();

initAceTabListeners = function() {
	jQuery( "#wp-ace__tabs a" ).click(function( event ) {
		var link_href = jQuery(this).attr('href');
		console.log('in tabs event handler');
		switch(link_href) {
			case '#html-edit':
				jQuery('#change-settings-modal a[href="#wp-ace-html"]').tab('show');
				console.log('#html-edit clicked');
			break;
			case '#css-edit':
				jQuery('#change-settings-modal a[href="#wp-ace-css"]').tab('show');
				console.log('#css-edit clicked');
			break;
			case '#javascript-edit':
				jQuery('#change-settings-modal a[href="#wp-ace-javascript"]').tab('show');
				console.log('#javascript-edit clicked');
			break;
		}
	});	

	jQuery( "#change-settings-modal .nav-tabs a" ).click(function( event ) {
		var link_href = jQuery(this).attr('href');
		switch(link_href) {
			case '#wp-ace-html':
				jQuery('#wp-ace__tabs a[href="#html-edit"]').tab('show');

			break;
			case '#wp-ace-css':
				jQuery('#wp-ace__tabs a[href="#css-edit"]').tab('show');

			break;
			case '#wp-ace-javascript':
				jQuery('#wp-ace__tabs a[href="#javascript-edit"]').tab('show');

			break;
		}
	});	
};

jQuery(document).ready(function(){
	wpAceInterface.init();
	wpAceInterface.setInputMappingListeners();

	initAceTabListeners();

	jQuery('[data-toggle="tooltip"]').tooltip(); 
});
