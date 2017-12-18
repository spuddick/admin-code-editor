<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">
 
  <div id="icon-themes" class="icon32"></div>
  <div class="wp-ace-bootstrap" >
    <div class="row">
      <div class="col-lg-9 col-md-8">
        <section class="settings-page-form" >
          <h1><?php _e('Admin Code Editor Settings', 'admin-code-editor'); ?></h1>
          
          <form method="post" action="options.php">
						<?php
							do_settings_sections("admin-code-editor-options-page"); 
							settings_fields("admin-code-editor-settings");
							submit_button(); 
						?> 
          </form>
        </section>
      </div>
      <div class="col-lg-3 col-md-4">
        <div  id="wp-ace--settings-page-plugin-callout" class="panel panel-default">
          <div class="panel-heading">
            <h4>
            <i class="fa fa-info-circle" aria-hidden="true"></i> You Might Also Like...
            </h4>
          </div>
          
          <div class="panel-body">
            <p>Admin Code Editor is built by <a target="_blank" href='http://webrockstar.net?utm_source=admin-code-editor-plugin&utm_medium=settings-callout'>Web Rockstar</a>. Web Rockstar also offers several other free plugins:</p>
            <div class="">
              
              <!-- Notes Widget Wrapper Promo Item -->
              <div class="wp-ace__plugin-promo-item">
                <a target="_blank" href='https://wordpress.org/plugins/notes-widget-wrapper/' >
                  <h4>Notes Widget Wrapper</h4>
                  <div class="wp-ace__plugin-promo-item__banner" id="promo-banner--notes-widget-wrapper" ></div>
                </a>
              </div>

              <!-- WP Notes Widget Promo Item -->
              <div class="wp-ace__plugin-promo-item">
                <a target="_blank" href='https://en-ca.wordpress.org/plugins/wp-notes-widget/' >
                  <h4>WP Notes Widget</h4>
                  <div class="wp-ace__plugin-promo-item__banner" id="promo-banner--wp-notes-widget" ></div>
                </a>
              </div>

              <!-- Custom Ratings Promo Item -->
              <div class="wp-ace__plugin-promo-item">
                <a target="_blank" href='https://wordpress.org/plugins/custom-ratings/' >
                  <h4>Custom Ratings</h4>
                  <div class="wp-ace__plugin-promo-item__banner" id="promo-banner--custom-ratings" ></div>
                </a>
              </div>

            </div>
          </div>
        </div>
      </div>         

      
    </div> 
  </div>

       
</div><!-- /.wrap -->