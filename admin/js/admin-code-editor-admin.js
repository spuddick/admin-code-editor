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

var html_editor;
var $html_field = jQuery('#html-field');

jQuery(document).ready(function(){
	console.log('loading file 2');
  jQuery('[data-toggle="tooltip"]').tooltip(); 

  if (jQuery('#html-code').length ) {
	  html_editor = ace.edit("html-code");
	  html_editor.setTheme("ace/theme/monokai");
	  html_editor.getSession().setMode("ace/mode/html");
	  html_editor.getSession().setUseWrapMode(true);
	  html_editor.getSession().setTabSize(2);
	  jQuery('#html-code').css('font-size', '13px');

  }
	//jQuery('.code-content').resizable();
  
	jQuery('.code-content').resizable({
    ghost: true,
    handles: "s",
    stop: function( event, ui ) {
    	html_editor.resize();
    	
    	height = ui.element.height();
    	//ui.element.siblings('.field-height').val(height);
    }
  });
	
});
