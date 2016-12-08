(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 
	 //$('[data-toggle="tooltip"]').tooltip();
})( jQuery );

Backbone.Model.prototype._super = function(funcName){
  return this.constructor.__super__[funcName].apply(this, _.rest(arguments));
}

var wpAceInterface = (function() {
 
    // Private variables and functions
    const ACE_THEME 		= "ace/theme/monokai";
    const ACE_WRAP_MODE = true;
    const ACE_TAB_SIZE 	= 2;
    const ACE_FONT_SIZE = '13px';


    var html_editor, css_editor, js_editor;
		var html_code_model, css_code_model, js_code_model;
		var html_tab_label_preprocessor_view, css_tab_label_preprocessor_view, js_tab_label_preprocessor_view;
		var html_text_status_view, css_text_status_view, js_text_status_view;
		var html_settings_view, css_settings_view, js_settings_view;
		var form_submitting = false;
		
    // Models
		var Code_Model = Backbone.Model.extend({
		  defaults : {
		  	code_has_changed : 0,
		  	has_changed : 0,
		  	preprocessed_code_has_errors : 0,
		    preprocessor_nicename_map : '',
		    preprocessor_nicename : ''
		  },
		  updatePreprocessor: function($preprocessor_obj) {
		    var new_mode = $preprocessor_obj.val();
    		//console.log('updating html editor:' + new_mode);
    		//console.dir(this.get('ace_editor'));
    		this.get('ace_editor').update_mode(new_mode);
		    this.set({
		      preprocessor: new_mode
		    });
		    //console.dir(this.get('preprocessor_nicename_map'));
		    this.set({
		      preprocessor_nicename: this.get('preprocessor_nicename_map')[new_mode]
		    });
		  },
		  updateChangedStatus : function(){
		  	//console.log('has changed ' + this.get('has_changed'));
		  	this.set({has_changed: 1});
		  	
		  },
		  updateCodeChangedStatus : function(){
		  	this.set({code_has_changed: 1});
		  	//console.log('code has changed');
		  }
		});			
		
		var HTML_Code_Model = Code_Model.extend({
		  defaults : {
		  	test_var : function() {
		  		return Math.random();
		  	}
		  },
		  updateCodePosition: function() {
		    var code_position = jQuery('input[name=wp-ace-html-php-code-position]:checked').val();
		    this.set({
		      output_position : code_position
		    });
		    console.log('from html update codepos: ' + this.get('has_changed'));
		  },
		  updateDisableWPautopStatus: function() {
		    var status = 0;
		    if (jQuery('input#wp-ace-html-php-disable-wpautop').is(":checked")) {
          status = 1;
        } 
		    this.set({
		      wpautop_status : status
		    });
		    console.log('from html update wpautop: ' + this.get('has_changed'));
		  },
		  initialize: function(){
		    this.set({
				  preprocessor_nicename_map : {
				  	none : 'HTML',
				  	haml : 'HAML',
				  	markdown : 'MarkDown'
				  }
		    });
			  this.set({
		      preprocessor_nicename: this.get('preprocessor_nicename_map')[this.get('preprocessor')]
		    });
		    
		  }
		});

		_.extend(HTML_Code_Model.prototype.defaults, Code_Model.prototype.defaults);
		var CSS_Code_Model = Code_Model.extend({

		  initialize: function(){
		    this.set({
				  preprocessor_nicename_map : {
				  	none : 'CSS',
				  	scss : 'SCSS',
				  	less : 'LESS',
				  	stylus : 'Stylus'
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
		    if (jQuery('input#wp-ace-css-include-jquery').is(":checked")) {
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
		    'change input[name=wp-ace-html-php-code-position]': 'codePositionChange',
		    'change input#wp-ace-html-php-disable-wpautop': 'disableWPautopChange',
		    'change input[name=wp-ace-html-php-preprocessor]': 'preprocessorChange'
		  },

		  
		  initialize: function() {
        console.log('html template: ' + jQuery('#tmpl-wp-ace-html').html().length);
        this.template = _.template(jQuery('#tmpl-wp-ace-html').html());
        jQuery('input[name=wp-ace-html-php-preprocessor]').trigger('change');
        jQuery('input#wp-ace-html-php-disable-wpautop').trigger('change');
        jQuery('input[name=wp-ace-html-php-preprocessor]').trigger('change');
        this.listenTo(this.model, "change", this.render);
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
		    'change input[name=wp-ace-css-preprocessor]': 'preprocessorChange'
		  },

		  
		  initialize: function() {
        console.log('css template: ' + jQuery('#tmpl-wp-ace-css').html().length);
        this.template = _.template(jQuery('#tmpl-wp-ace-css').html());
        jQuery('input[name=wp-ace-css-preprocessor]').trigger('change');
        this.listenTo(this.model, "change", this.render);
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
		var JS_Settings_View = Backbone.View.extend({
			model: JS_Code_Model,
		  tagName: 'div',
		  template: '',
		  events: {
		  	'change input#wp-ace-css-include-jquery': 'includeJqueryChange',
		    'change input[name=wp-ace-js-preprocessor]': 'preprocessorChange'
		  },

		  initialize: function() {
        console.log('js template: ' + jQuery('#tmpl-wp-ace-js').html().length);
        this.template = _.template(jQuery('#tmpl-wp-ace-js').html());
        jQuery('input#wp-ace-css-include-jquery').trigger('change');
        jQuery('input[name=wp-ace-js-preprocessor]').trigger('change');
        this.listenTo(this.model, "change", this.render);
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


    var init = function() {


    	/**
    	 *
    	 * Initialize Bootstrap tooltips
    	 *
    	 */
		  jQuery('[data-toggle="tooltip"]').tooltip(); 


		  /**
		   *
		   * Last active tab listener
		   *
		   */
		  jQuery('.wp-ace-bootstrap #wp-ace__tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			  e.target // newly activated tab
			  e.relatedTarget // previous active tab
			  var tab_id = jQuery(e.target).attr('href').replace('#','');
			  
			  jQuery('#wp-ace-last-active-tab').val(tab_id);

			})


		  /**
		   *
		   * Initialize ACE code editors
		   *
		   */
		  if (jQuery('#wp-ace-html-php-pre-code-editor').length ) {
			  html_editor = ace.edit("wp-ace-html-php-pre-code-editor");
			  html_editor.setTheme(ACE_THEME);
			  html_editor.getSession().setUseWrapMode(ACE_WRAP_MODE);
			  html_editor.getSession().setTabSize(ACE_TAB_SIZE);
			  html_editor.code_has_changed = 0;
			  html_editor.getSession().on('change', function() {
					html_editor.code_has_changed = 1;
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
				html_code_model = new HTML_Code_Model({ 
					preprocessor: wpcr_data['wp-ace-html-php-preprocessor'], 
					ace_editor : html_editor,
					output_position : wpcr_data['wp-ace-html-php-code-position'],
					wpautop_status : wpcr_data['wp-ace-html-php-disable-wpautop'],
					post_type_name : wpcr_data['wp-ace-post-type-singular-name'],
					preprocessed_code_has_errors : (wpcr_data['wp-ace-html-php-compile-status'] == 'error' ? 1 : 0)	 
				});		  
				html_tab_label_preprocessor_view = new Tab_Label_View({ el: jQuery("#html-php-tab-label-preprocessor"), model: html_code_model });
				html_tab_label_preprocessor_view.render();
				html_text_status_view = new HTML_Text_Status_View({ el: jQuery("#wp-ace-html-php-status"), model: html_code_model });
				html_text_status_view.render();
				html_settings_view = new HTML_Settings_View({ el: jQuery("#wp-ace-tab-content-html"), model: html_code_model });
				html_settings_view.render();
				html_update_notice_view = new Code_Update_Notice_View({ el: jQuery("#wp-ace__notice-container--html-php"), model: html_code_model });
				html_update_notice_view.render();
		  }

		  if (jQuery('#wp-ace-css-pre-code-editor').length ) {
			  css_editor = ace.edit("wp-ace-css-pre-code-editor");
			  css_editor.setTheme(ACE_THEME);
			  css_editor.getSession().setUseWrapMode(ACE_WRAP_MODE);
			  css_editor.getSession().setTabSize(ACE_TAB_SIZE);
			  css_editor.code_has_changed = 0;
			  css_editor.getSession().on('change', function() {
					css_editor.code_has_changed = 1;
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
				css_code_model = new CSS_Code_Model({ 
					preprocessor: wpcr_data['wp-ace-css-preprocessor'], 
					ace_editor : css_editor,
					post_type_name : wpcr_data['wp-ace-post-type-singular-name'],
					preprocessed_code_has_errors : (wpcr_data['wp-ace-css-compile-status'] == 'error' ? 1 : 0)	   
				});		  
				css_tab_label_preprocessor_view = new Tab_Label_View({ el: jQuery("#css-tab-label-preprocessor"), model: css_code_model });
				css_tab_label_preprocessor_view.render();
				css_text_status_view = new CSS_Text_Status_View({ el: jQuery("#wp-ace-css-status"), model: css_code_model }); 
				css_text_status_view.render();
				css_settings_view = new CSS_Settings_View({ el: jQuery("#wp-ace-tab-content-css"), model: css_code_model }); 
				css_settings_view.render();
				css_update_notice_view = new Code_Update_Notice_View({ el: jQuery("#wp-ace__notice-container--css"), model: css_code_model });
				css_update_notice_view.render();

		  }

		  if (jQuery('#wp-ace-js-pre-code-editor').length ) {
			  js_editor = ace.edit("wp-ace-js-pre-code-editor");
			  js_editor.setTheme(ACE_THEME);
			  js_editor.getSession().setUseWrapMode(ACE_WRAP_MODE);
			  js_editor.getSession().setTabSize(ACE_TAB_SIZE);
			  js_editor.code_has_changed = 0;
			  js_editor.getSession().on('change', function() {
					js_editor.code_has_changed = 1;
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
				js_code_model = new JS_Code_Model({ 
					preprocessor: wpcr_data['wp-ace-js-preprocessor'], 
					ace_editor : js_editor,
					jquery_enqueued_status : wpcr_data['wp-ace-css-include-jquery'],
					post_type_name : wpcr_data['wp-ace-post-type-singular-name'],
					preprocessed_code_has_errors : (wpcr_data['wp-ace-js-compile-status'] == 'error' ? 1 : 0)	  
				});
				js_tab_label_preprocessor_view = new Tab_Label_View({ el: jQuery("#js-tab-label-preprocessor"), model: js_code_model });
				js_tab_label_preprocessor_view.render();
				js_text_status_view = new JS_Text_Status_View({ el: jQuery("#wp-ace-js-status"), model: js_code_model });
				js_text_status_view.render();
				js_settings_view = new JS_Settings_View({ el: jQuery("#wp-ace-tab-content-js"), model: js_code_model });
				js_settings_view.render();
				js_update_notice_view = new Code_Update_Notice_View({ el: jQuery("#wp-ace__notice-container--js"), model: js_code_model });
				js_update_notice_view.render();			  
		  }


		  registerFormSubmitListener();


		  // COMPILED CODE DISPLAY
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

		  /**
		   *
		   * Resizable code editor areas
		   *
		   */
			jQuery('.code-content').resizable({
		    ghost: true,
		    handles: "s",
		    stop: function( event, ui ) {
		    	html_editor.resize();
		    	
		    	height = ui.element.height();
		    	ui.element.siblings('.field-height').val(height);
		    }
		  });


			/**
			 *
			 * Proper tab activation in modal display
			 *
			 */
			jQuery('#change-settings-modal').on('show.bs.modal', function (e) {
			  var $clicked_anchor = jQuery(e.relatedTarget);
			  jQuery('#' + $clicked_anchor.data('active-modal-tab')).tab('show');
			})


			/**
			 *
			 * Notice if leaving page without saving
			 *
			 */
			jQuery(window).on("beforeunload", function() {
        
        if ((
        	(typeof html_editor == 'undefined' || html_editor.has_changed) || 
        	(typeof css_editor == 'undefined' || css_editor.has_changed) || 
        	(typeof js_editor == 'undefined' || js_editor.has_changed)) && 
        	!form_submitting )  {
        	return true;
        }
        return;
				
			//return true;
      });
  		jQuery( "form#post" ).submit(function( event ) {
				form_submitting = true;

			});


  		/**
  		 *
  		 * If previous active tab not present in HTML output (due to code type being disabled), select the first available tab
  		 *
  		 */
  		
  		 if (!jQuery('ul#wp-ace__tabs > li.active').length) {
  		 	jQuery('ul#wp-ace__tabs > li:first-child a').tab('show')
  		 }
    };

    var registerFormSubmitListener = function() {
    	// on form submit
    	//setPreFormSubmitData();
    	
    };


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
				
				//event.preventDefault();
			});
				
    };
 
    var mapEditorCodetoInput = function(editor_obj) {
			$editor_input = jQuery('#' + editor_obj.hidden_input_id);

			if ( (typeof editor_obj !== 'undefined') && $editor_input.val() != editor_obj.getSession().getValue() ) {
				console.log('updating html code');
				console.log(editor_obj.getSession().getValue());
				$editor_input.val(editor_obj.getSession().getValue());
			}
    };

    // Public API
    return {
        init: init,
        setInputMappingListeners: setInputMappingListeners
    };
})();


jQuery(document).ready(function(){
	wpAceInterface.init();
	
	wpAceInterface.setInputMappingListeners();
	//wpAceInterface.registerSettingsListeners();

});
