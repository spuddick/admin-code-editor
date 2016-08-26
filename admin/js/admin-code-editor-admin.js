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
			  html_editor.getSession().setMode("ace/mode/html");
			  html_editor.getSession().setUseWrapMode(ACE_WRAP_MODE);
			  html_editor.getSession().setTabSize(ACE_TAB_SIZE);
			  html_editor.code_has_changed = 0;
			  html_editor.getSession().on('change', function() {
					html_editor.code_has_changed = 1;
				});
			  jQuery('#wp-ace-html-php-pre-code-editor').css('font-size', ACE_FONT_SIZE);

			  html_editor.hidden_input_id = 'wp-ace-html-php-pre-code';
		  }
		  /*
		  if (jQuery('#css-code').length ) {
			  html_editor = ace.edit("css-code");
			  html_editor.setTheme(ACE_THEME);
			  html_editor.getSession().setMode("ace/mode/scss");
			  html_editor.getSession().setUseWrapMode(ACE_WRAP_MODE);
			  html_editor.getSession().setTabSize(ACE_TAB_SIZE);
			  jQuery('#css-code').css('font-size', ACE_FONT_SIZE);
		  }

		  if (jQuery('#js-code').length ) {
			  html_editor = ace.edit("js-code");
			  html_editor.setTheme(ACE_THEME);
			  html_editor.getSession().setMode("ace/mode/javascript");
			  html_editor.getSession().setUseWrapMode(ACE_WRAP_MODE);
			  html_editor.getSession().setTabSize(ACE_TAB_SIZE);
			  jQuery('#js-code').css('font-size', ACE_FONT_SIZE);
		  }
			*/
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

    };

    var setInputMappingListeners = function() {
    		
  		jQuery( "#post" ).submit(function( event ) {
				mapEditorCodetoInput(html_editor);
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

    // Public API
    return {
        init: init,
        setInputMappingListeners: setInputMappingListeners
    };
})();


jQuery(document).ready(function(){
	
	wpAceInterface.init();
	wpAceInterface.setInputMappingListeners();

});
