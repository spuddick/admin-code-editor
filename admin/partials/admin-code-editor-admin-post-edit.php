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
						<pre id="html-code" style="height:400<?php //echo $html_height ?>px" class="code-content" ><?php // echo htmlentities($html_code) ?></pre>
						<input type="hidden" id="wp-ace-html-post-field" name="wp-ace-html-post-field" value="<?php // echo htmlentities($html_code) ?>" >
						<input type="hidden" id="wp-ace-html-post-field-height" name="wp-ace-html-post-field-height" class="field-height"  value="<?php echo $html_height ?>" >
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
						<pre id="css-code" style="height:400<?php // echo $html_height ?>px" class="code-content" ><?php // echo htmlentities($html_code) ?></pre>
						<input type="hidden" id="css-field" name="css-field" value="<?php // echo htmlentities($html_code) ?>" >
						<input type="hidden" id="css-field-height" name="css-field-height" class="field-height"  value="<?php echo $css_height ?>" >
					</div>

		    	<div class="clearfix" >
					  <div class="" >
						  <p class="text-muted" ><span class="glyphicon glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span> Proprocessing with <strong>Sass</strong>. Enquede <strong>in header</strong>. <a href='#change-settings-modal' role="button" data-toggle="modal" data-backdrop="true" aria-expanded="false" aria-controls="change-preprocessor-modal">Change Settings</a></p>
					  </div>

					</div>

	    </div>
	    <div role="tabpanel" class="tab-pane" id="javascript-edit">
					<div class="wp-ace-editor">
						<pre id="js-code" style="height:400<?php // echo $html_height ?>px" class="code-content" ><?php // echo htmlentities($html_code) ?></pre>
						<input type="hidden" id="js-field" name="js-field" value="<?php // echo htmlentities($html_code) ?>" >
						<input type="hidden" id="js-field-height" name="js-field-height" class="field-height"  value="<?php echo $js_height ?>" >
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
	    	
	    </div>
	  </div>

	</div>
</div>