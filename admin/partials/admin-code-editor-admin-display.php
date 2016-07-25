<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="admin-code-editor-container">

  <div class="" style="padding-bottom:10px;margin-bottom:10px;border-bottom:1px solid #ccc" >
    <h3>Editor Mode</h3>
    <div>
    	<input type="radio" name="editor_mode" value="append_bottom" class="editor_mode" id="append_bottom" <?php checked( $code_insert_mode, 'append_bottom' ); ?>  >
    	<label for="append_bottom"  data-toggle="tooltip" data-placement="right" title="This displays the HTML after the regular page content.">Append to Bottom</label>
    	
    </div>
    <div>
    	<input type="radio" name="editor_mode" value="header_and_footer" class="editor_mode" id="header_and_footer" <?php checked( $code_insert_mode, 'header_and_footer' ); ?> >
    	<label for="header_and_footer"  data-toggle="tooltip" data-placement="right" title="This displays the header HTML, regular page content, then footer HTML." >Header and Footer</label>

    </div>
  </div>

	<p>The following HTML, SCSS (CSS), and Javascript will be appended after the page content. Shortcodes can still be embedded in HTML code.</p>
	<p><span class="fa fa-info-circle" ></span><strong>Click and drag the bottom of the code window to resize. Do not wrap javascript or CSS in &lt;script&gt; or &lt;style&gt; tags. </strong></p>
	<div role="tabpanel">

	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" id="html-tab-control" class="html-display-element <?php echo $html_class_output; ?>" ><a href="#html" aria-controls="html" role="tab" data-toggle="tab">HTML</a></li>
	    <li role="presentation" id="html-header-tab-control" class="html-header-footer-display-element <?php echo $html_header_footer_class_output; ?>"  ><a href="#html_header" aria-controls="html_header" role="tab" data-toggle="tab">HTML Header</a></li>
	    <li role="presentation" id="html-footer-tab-control" class="html-header-footer-display-element <?php echo $html_header_footer_class_output; ?>"  ><a href="#html_footer" aria-controls="html_footer" role="tab" data-toggle="tab">HTML Footer</a></li>
	    <li role="presentation" ><a href="#css" aria-controls="css" role="tab" data-toggle="tab">SCSS (or CSS)</a>
	    </li>
	    <li role="presentation"><a href="#js" aria-controls="js" role="tab" data-toggle="tab">Javascript</a></li>
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
	    <div role="tabpanel" class="tab-pane" id="html" >
				<div class="code-content-container" >

					<h4>HTML</h4>
					<pre id="html-code" style="height:400px<?php //echo $html_height ?>px" class="code-content" ><?php // echo htmlentities($html_code) ?></pre>
					<input type="hidden" id="html-field" name="html-field" value="<?php // echo htmlentities($html_code) ?>" >
					<input type="hidden" id="html-field-height" name="html-field-height" class="field-height"  value="<?php echo $html_height ?>" >
					<p>&nbsp;</p>
				
				</div>    	

	    </div>
	    
	    <div role="tabpanel" class="tab-pane" id="html_header">
				<div class="code-content-container" >

					<h4>HTML Content Header</h4>
					<pre id="html-header-code" style="height:<?php echo $html_header_height ?>px" class="code-content" ><?php echo htmlentities($html_header_code) ?></pre>
					<input type="hidden" id="html-header-field" name="html-header-field" value="<?php echo htmlentities($html_header_code) ?>" >
					<input type="hidden" id="html-header-field-height" name="html-header-field-height" class="field-height"  value="<?php echo $html_header_height ?>" >
					<p>&nbsp;</p>
				</div>    	

	    </div>

	    <div role="tabpanel" class="tab-pane" id="html_footer">
				<div class="code-content-container" >

					<h4>HTML Content Footer</h4>
					<pre id="html-footer-code" style="height:<?php echo $html_footer_height ?>px" class="code-content" ><?php echo htmlentities($html_footer_code) ?></pre>
					<input type="hidden" id="html-footer-field" name="html-footer-field" value="<?php echo htmlentities($html_footer_code) ?>" >
					<input type="hidden" id="html-footer-field-height" name="html-footer-field-height" class="field-height"  value="<?php echo $html_footer_height ?>" >
					<p>&nbsp;</p>
				</div>    	

	    </div>


	    <div role="tabpanel" class="tab-pane" id="css">
				<div class="code-content-container" >
					<h4>SCSS (or plain CSS)</h4>
					<?php if (ICL_LANGUAGE_CODE == 'fr') { echo '<p class="admin-code-highlight-alert" >' . $french_css_status . '</p>'; } ?>
					<?php if (!empty($css_compile_error)) { echo '<p class="css-compile-error" ><span class="fa fa-exclamation-triangle" ></span>'. $this->scss_compile_notice_start .'<strong>'. $css_compile_error .'</strong></p>'; } ?>
					<pre id="css-code" style="height:<?php echo $css_height ?>px" class="code-content" ><?php echo htmlentities($css_code) ?></pre>
					<input type="hidden" id="css-field" name="css-field" value="<?php echo htmlentities($css_code) ?>" >
					<input type="hidden" id="css-field-height" name="css-field-height" class="field-height"  value="<?php echo $css_height ?>" >
					<p>&nbsp;</p>
				</div>    	

	    </div>
	    <div role="tabpanel" class="tab-pane" id="js">
	    	
				<div class="code-content-container" >
					<h4>Javascript</h4>
					<?php if (ICL_LANGUAGE_CODE == 'fr') { echo '<p  class="admin-code-highlight-alert" >' . $french_js_status . '</p>'; } ?>
					<pre id="js-code"  style="height:<?php echo $js_height ?>px" class="code-content" ><?php echo htmlentities($js_code) ?></pre>
					<input type="hidden" id="js-field" name="js-field" value="<?php echo htmlentities($js_code) ?>"  >
					<input type="hidden" id="js-field-height" name="js-field-height" class="field-height" value="<?php echo $js_height ?>" >
				</div>    	
	    </div>
	  </div>



	</div>

</div>




