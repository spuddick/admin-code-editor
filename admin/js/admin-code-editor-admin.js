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

    // Models
		var Code_Model = Backbone.Model.extend({
		  updatePreprocessor: function($preprocessor_obj) {
		    var new_mode = $preprocessor_obj.val();
    		console.log('updating html editor:' + new_mode);
    		console.dir(this.get('ace_editor'));
    		this.get('ace_editor').update_mode(new_mode);
		    this.set({
		      preprocessor: new_mode
		    });
		    
		  }
		  /*
		  validate: function( attributes ){
		    if( attributes.age < 0 && attributes.name != "Dr Manhatten" ){
		      return "You can't be negative years old";
		    }
		  },
			
		  initialize: function(){
		    //alert("Welcome to this world");
		    this.on("change:preprocessor", function(model){
		      var preprocessor = model.get("preprocessor"); // 'Stewie Griffin'
		      console.log("updating ace editor mode to " + preprocessor );
		    });
		    */
		    /*
		    this.bind("error", function(model, error){
		      // We have received an error, log it, alert it or forget it :)
		      alert( error );
		    })
			  
		  }*/
		});			
		
		var HTML_Code_Model = Code_Model.extend({
		  updateCodePosition: function() {
		    var code_position = jQuery('input[name=wp-ace-html-php-code-position]:checked').val();
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
		    
		  }

		});
		var CSS_Code_Model = Code_Model.extend({

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
	    },
		  codePositionChange: function(e) {
		    e.preventDefault();
		    this.model.updateCodePosition();
		  },

		  disableWPautopChange: function(e) {
		    e.preventDefault();
		    this.model.updateDisableWPautopStatus();
		  },

		  preprocessorChange: function(e) {
		    e.preventDefault();
		    this.model.updatePreprocessor(jQuery(e.currentTarget));
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
	    },
		  preprocessorChange: function(e) {
		    e.preventDefault();
		    this.model.updatePreprocessor(jQuery(e.currentTarget));
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
	    },
		  includeJqueryChange: function(e) {
		    e.preventDefault();
		    this.model.updateIncludeJqueryStatus();
		  },
		  preprocessorChange: function(e) {
		    e.preventDefault();
		    this.model.updatePreprocessor(jQuery(e.currentTarget));
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
		   * Initialize ACE code editors
		   *
		   */
		  if (jQuery('#wp-ace-html-php-pre-code-editor').length ) {
			  html_editor = ace.edit("wp-ace-html-php-pre-code-editor");
			  html_editor.setTheme(ACE_THEME);
			  //html_editor.getSession().setMode("ace/mode/html");
			  html_editor.getSession().setUseWrapMode(ACE_WRAP_MODE);
			  html_editor.getSession().setTabSize(ACE_TAB_SIZE);
			  html_editor.code_has_changed = 0;
			  /*
			  html_editor.getSession().on('change', function() {
					html_editor.code_has_changed = 1;
				});
				*/
				html_editor.update_mode = function(mode) {
					if (mode == 'none') {
						mode = 'html';
					}
					html_editor.getSession().setMode("ace/mode/" + mode);
				};
			  jQuery('#wp-ace-html-php-pre-code-editor').css('font-size', ACE_FONT_SIZE);

			  html_editor.hidden_input_id = 'wp-ace-html-php-pre-code';
		  }
		  if (jQuery('#wp-ace-css-pre-code-editor').length ) {
			  css_editor = ace.edit("wp-ace-css-pre-code-editor");
			  css_editor.setTheme(ACE_THEME);
			  //css_editor.getSession().setMode("ace/mode/scss");
			  css_editor.getSession().setUseWrapMode(ACE_WRAP_MODE);
			  css_editor.getSession().setTabSize(ACE_TAB_SIZE);
			  css_editor.code_has_changed = 0;
			  css_editor.getSession().on('change', function() {
					css_editor.code_has_changed = 1;
				});
				css_editor.update_mode = function(mode) {
					if (mode == 'none') {
						mode = 'css';
					}
					css_editor.getSession().setMode("ace/mode/" + mode);
				};
			  jQuery('#wp-ace-css-pre-code-editor').css('font-size', ACE_FONT_SIZE);

			  css_editor.hidden_input_id = 'wp-ace-css-pre-code';
		  }
		  if (jQuery('#wp-ace-js-pre-code-editor').length ) {
			  js_editor = ace.edit("wp-ace-js-pre-code-editor");
			  js_editor.setTheme(ACE_THEME);
			  //js_editor.getSession().setMode("ace/mode/coffee");
			  js_editor.getSession().setUseWrapMode(ACE_WRAP_MODE);
			  js_editor.getSession().setTabSize(ACE_TAB_SIZE);
			  js_editor.code_has_changed = 0;
			  js_editor.getSession().on('change', function() {
					js_editor.code_has_changed = 1;
				});
				js_editor.update_mode = function(mode) {
					if (mode == 'none') {
						mode = 'javascript';
					}
					js_editor.getSession().setMode("ace/mode/" + mode);
				};
			  jQuery('#wp-ace-js-pre-code-editor').css('font-size', ACE_FONT_SIZE);

			  js_editor.hidden_input_id = 'wp-ace-js-pre-code';
		  }

		  html_editor.update_mode(wpcr_data['wp-ace-html-php-preprocessor']); 
		  css_editor.update_mode(wpcr_data['wp-ace-css-preprocessor']); 
		  js_editor.update_mode(wpcr_data['wp-ace-js-preprocessor']); 

			html_code_model = new HTML_Code_Model({ 
				preprocessor: wpcr_data['wp-ace-html-php-preprocessor'], 
				ace_editor : html_editor,
				output_position : wpcr_data['wp-ace-html-php-code-position'],
				wpautop_status : wpcr_data['wp-ace-html-php-disable-wpautop']		 
			});
			css_code_model = new CSS_Code_Model({ 
				preprocessor: wpcr_data['wp-ace-css-preprocessor'], 
				ace_editor : css_editor,  
			});
			js_code_model = new JS_Code_Model({ 
				preprocessor: wpcr_data['wp-ace-js-preprocessor'], 
				ace_editor : js_editor,
				jquery_enqueued_status : wpcr_data['wp-ace-css-include-jquery'] 
			});



			html_tab_label_preprocessor_view = new Tab_Label_View({ el: jQuery("#html-php-tab-label-preprocessor"), model: html_code_model });
			css_tab_label_preprocessor_view = new Tab_Label_View({ el: jQuery("#css-tab-label-preprocessor"), model: css_code_model });
			js_tab_label_preprocessor_view = new Tab_Label_View({ el: jQuery("#js-tab-label-preprocessor"), model: js_code_model });

			html_tab_label_preprocessor_view.render();
			css_tab_label_preprocessor_view.render();
			js_tab_label_preprocessor_view.render();

			html_text_status_view = new HTML_Text_Status_View({ el: jQuery("#wp-ace-html-php-status"), model: html_code_model });
			css_text_status_view = new CSS_Text_Status_View({ el: jQuery("#wp-ace-css-status"), model: css_code_model }); 
			js_text_status_view = new JS_Text_Status_View({ el: jQuery("#wp-ace-js-status"), model: js_code_model });

			html_text_status_view.render();
			css_text_status_view.render();
			js_text_status_view.render();

			html_settings_view = new HTML_Settings_View({ el: jQuery("#wp-ace-tab-content-html"), model: html_code_model });
			css_settings_view = new CSS_Settings_View({ el: jQuery("#wp-ace-tab-content-css"), model: css_code_model }); 
			js_settings_view = new JS_Settings_View({ el: jQuery("#wp-ace-tab-content-js"), model: js_code_model });

			html_settings_view.render();
			css_settings_view.render();
			js_settings_view.render();

		  //registerPreprocessorSelectListeners();
		  //setInitialEditorModes();
		  registerFormSubmitListener();



		  // COMPILED CODE DISPLAY
		  if (jQuery('#wp-ace-html-compiled-code-display').length ) {
			  html_display = ace.edit("wp-ace-html-compiled-code-display");
			  html_display.setTheme(ACE_THEME);
			  html_display.getSession().setMode("ace/mode/html");
			  html_display.getSession().setUseWrapMode(ACE_WRAP_MODE);
			  html_display.getSession().setTabSize(ACE_TAB_SIZE);
			  /*
			  html_display.setOptions({
				    readOnly: true,
				    highlightActiveLine: false,
				    highlightGutterLine: false
				});
				*/
		  }
		  if (jQuery('#wp-ace-css-compiled-code-display').length ) {
			  css_display = ace.edit("wp-ace-css-compiled-code-display");
			  css_display.setTheme(ACE_THEME);
			  css_display.getSession().setMode("ace/mode/css");
			  css_display.getSession().setUseWrapMode(ACE_WRAP_MODE);
			  css_display.getSession().setTabSize(ACE_TAB_SIZE);
			  /*
			  css_display.setOptions({
				    readOnly: true,
				    highlightActiveLine: false,
				    highlightGutterLine: false
				});
				*/
		  }
		  if (jQuery('#wp-ace-js-compiled-code-display').length ) {
			  js_display = ace.edit("wp-ace-js-compiled-code-display");
			  js_display.setTheme(ACE_THEME);
			  js_display.getSession().setMode("ace/mode/javascript");
			  js_display.getSession().setUseWrapMode(ACE_WRAP_MODE);
			  js_display.getSession().setTabSize(ACE_TAB_SIZE);
			  /*
			  js_display.setOptions({
				    readOnly: true,
				    highlightActiveLine: false,
				    highlightGutterLine: false
				});
				*/
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

    };
    /*
    var registerPreprocessorSelectListeners = function() {
    	    	// update ace object
    	// update json data
    	// render with underscores
    	console.log('setting up select listeners');
    	jQuery('input[name=wp-ace-html-php-preprocessor]').change(function() {
    		var new_mode = jQuery(this).val();
    		console.log('updating html editor:' + new_mode);
    		html_editor.update_mode(new_mode);
				
				html_code_model.set({'preprocessor': new_mode });
    	});
    	jQuery('input[name=wp-ace-css-preprocessor]').on('change', function() {
    		var new_mode = jQuery(this).val();
    		css_editor.update_mode(new_mode);
				css_code_model.set({'preprocessor': new_mode });
    	});
    	jQuery('input[name=wp-ace-js-preprocessor]').on('change', function() {
    		var new_mode = jQuery(this).val();
    		js_editor.update_mode(new_mode);
				js_code_model.set({'preprocessor': new_mode });
    	});    	
    };
		*/
    var registerFormSubmitListener = function() {
    	// on form submit
    	setPreFormSubmitData();
    };

    var setPreFormSubmitData = function() {
    	// get cursor position, set to input
    };

    var setInitialEditorModes = function() {
			//jQuery('input[name=wp-ace-html-php-preprocessor]').trigger('change');
			//jQuery('input[name=wp-ace-css-preprocessor]').trigger('change');
			//jQuery('input[name=wp-ace-js-preprocessor]').trigger('change');
    };

    var setInputMappingListeners = function() {
    		
  		jQuery( "#post" ).submit(function( event ) {
				mapEditorCodetoInput(html_editor);
				mapEditorCodetoInput(css_editor);
				mapEditorCodetoInput(js_editor);
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

    var registerSettingsListeners = function() {
    	/*
				HTML
					disable wpautop
					position (before, after)
					preprocessor
				CSS
					preprocessor
				JS
					include jquery
					preprocessor
    	*/
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
