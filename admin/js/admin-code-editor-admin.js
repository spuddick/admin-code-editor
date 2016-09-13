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

var wpAceInterface = (function() {
 
    // Private variables and functions
    const ACE_THEME 		= "ace/theme/monokai";
    const ACE_WRAP_MODE = true;
    const ACE_TAB_SIZE 	= 2;
    const ACE_FONT_SIZE = '13px';

    var html_editor, css_editor, js_editor;
 
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
			  html_editor.getSession().on('change', function() {
					html_editor.code_has_changed = 1;
				});
				html_editor.update_mode = function(mode) {
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
					js_editor.getSession().setMode("ace/mode/" + mode);
				};
			  jQuery('#wp-ace-js-pre-code-editor').css('font-size', ACE_FONT_SIZE);

			  js_editor.hidden_input_id = 'wp-ace-js-pre-code';
		  }

		  setInitialEditorModes();
		  registerPreprocessorSelectListeners();
		  registerFormSubmitListener();

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

    var registerFormSubmitListener = function() {
    	// on form submit
    	setPreFormSubmitData();
    };

    var setPreFormSubmitData = function() {
    	// get cursor position, set to input
    };

    var setInitialEditorModes = function() {
			var html_mode = $('#wp-ace-html-php-preprocessor').val();
			var css_mode 	= $('#wp-ace-css-preprocessor').val();
			var js_mode 	= $('#wp-ace-js-preprocessor').val();
			
			html_editor.update_mode(html_mode);
			css_editor.update_mode(css_mode);
			js_editor.update_mode(js_mode);
    };

    var registerPreprocessorSelectListeners = function() {
    	// update ace object
    	// update json data
    	// render with underscores
    }

    var setInputMappingListeners = function() {
    		
  		jQuery( "#post" ).submit(function( event ) {
				mapEditorCodetoInput(html_editor);
				mapEditorCodetoInput(css_editor);
				mapEditorCodetoInput(js_editor);
				//event.preventDefault();
			});
				
    }
 
    var mapEditorCodetoInput = function(editor_obj) {
			$editor_input = jQuery('#' + editor_obj.hidden_input_id);

			if ( (typeof editor_obj !== 'undefined') && $editor_input.val() != editor_obj.getSession().getValue() ) {
				console.log('updating html code');
				console.log(editor_obj.getSession().getValue());
				$editor_input.val(editor_obj.getSession().getValue());
			}
    }

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
    }

    // Public API
    return {
        init: init,
        setInputMappingListeners: setInputMappingListeners
    };
})();


jQuery(document).ready(function(){
	wpAceInterface.init();
	
	wpAceInterface.setInputMappingListeners();
	wpAceInterface.registerSettingsListeners();

});
