<?php

class Admin_Code_Editor_General

{
    const DEFAULT_ONLY_DISPLAY_WHEN 			= array('inside-the-loop', 'in-main-query');
    const DEFAULT_HIDE_ON_TEMPLATES 			= array();
    const DEFAULT_HIDE_CODE_EDITOR_TYPES 	= array();
    const DEFAULT_ACTIVE_ADMIN_TAB 				= 'html-edit';

    private $post_id, $disabled_templates, $only_display_in = array(), $hide_code_editor_types, $active_admin_tab;

    function __construct($post_id) {

	    $this->post_id = $post_id;

    }

    public function updateDataFromPOST() {

			if (isset($_POST['wp-ace-disabled-templates'])) {
				$this->disabled_templates = $_POST['wp-ace-disabled-templates'];
				update_post_meta($this->post_id, '_wp_ace_disabled_templates', $this->disabled_templates );
			}

			if (isset($_POST['wp-ace-only-display-in-loop'])) {
				$this->only_display_in_loop = $_POST['wp-ace-only-display-in-loop'];
				update_post_meta($this->post_id, '_wp_ace_display_only_in_loop', $this->only_display_in_loop );
			}	else {
				delete_post_meta($this->post_id, '_wp_ace_display_only_in_loop');
			}

			if (isset($_POST['wp-ace-only-display-in-main-query'])) {
				$this->only_display_in_main_query = $_POST['wp-ace-only-display-in-main-query'];
				update_post_meta($this->post_id, '_wp_ace_display_only_in_main_query', $this->only_display_in_main_query );
			} else {
				delete_post_meta($this->post_id, '_wp_ace_display_only_in_loop');
			}

			update_post_meta($this->post_id, '_wp_ace_last_active_tab', $_POST['wp-ace-last-active-tab'] );
    }

    public function getDisabledTemplates() {

			if (empty($this->disabled_templates)) {
				$this->disabled_templates = get_post_meta($this->post_id, '_wp_ace_disable_templates', true);
				if (empty($this->disabled_templates)) {
					$this->disabled_templates = get_option('wp_ace_default_disabled_template', self::DEFAULT_HIDE_ON_TEMPLATES);
				}
			} 

			return $this->disabled_templates;

    }

    public function homeTemplateIsDisabled() {
    	$this->getDisabledTemplates();
    	return in_array('home', $this->disabled_templates);
    }

    public function frontPageTemplateIsDisabled() {
    	$this->getDisabledTemplates();
    	return in_array('front-page', $this->disabled_templates);    	
    }

    public function searchTemplateIsDisabled() {
    	$this->getDisabledTemplates();
    	return in_array('search', $this->disabled_templates);    	
    }

    public function archiveTemplateIsDisabled() {
    	$this->getDisabledTemplates();
    	return in_array('archive', $this->disabled_templates);    	
    }

    public function getOnlyDisplayInLoopStatus() {

			if (isset($this->only_display_in['loop'])) {
				$this->only_display_in['loop'] = get_post_meta($this->post_id, '_wp_ace_display_only_in_loop', true);
				if (empty($this->only_display_in['loop'])) {
					$conditional_display = get_option('wp_ace_default_conditional_display', self::DEFAULT_ONLY_DISPLAY_WHEN);
					if (!empty($conditional_display) && isset($conditional_display['inside-the-loop']) ) {

						$this->only_display_in['loop'] = 1;
					} else {
						return false;
					}
				}
			}

			return $this->only_display_in['loop'];

    }

    public function getOnlyDisplayInMainQueryStatus() {

			if (isset($this->only_display_in['main-query'])) {
				$this->only_display_in['main-query'] = get_post_meta($this->post_id, '_wp_ace_display_only_in_main_query', true);
				if (empty($this->only_display_in['main-query'])) {
					$conditional_display = get_option('wp_ace_default_conditional_display', self::DEFAULT_ONLY_DISPLAY_WHEN);
					if (!empty($conditional_display) && isset($conditional_display['in-main-query']) ) {

						$this->only_display_in['main-query'] = 1;
					} else {
						return false;
					}
				}
			}

			return $this->only_display_in['main-query'];
    }

    public function getHiddenCodeEditorTypes() {

			if (empty($this->hide_code_editor_types)) {
				$this->hide_code_editor_types = get_option('wp_ace_default_disabled_code', self::DEFAULT_HIDE_CODE_EDITOR_TYPES );
			} else {
				return false;
			}
			
			return $this->hide_code_editor_types;

    }

    public function cssEditorIsDisabled() {
    	$this->getHiddenCodeEditorTypes();
    	return in_array('html', $this->hide_code_editor_types); 
    }

    public function cssEditorIsDisabled() {
    	$this->getHiddenCodeEditorTypes();
    	return in_array('css', $this->hide_code_editor_types); 
    }

    public function jsEditorIsDisabled() {
    	$this->getHiddenCodeEditorTypes();
    	return in_array('js', $this->hide_code_editor_types); 
    }

    public function getActiveAdminTab() {

			if (empty($this->active_admin_tab)) {
				$this->active_admin_tab = get_post_meta($this->post_id, '_wp_ace_last_active_tab', true);
				if (empty($this->active_admin_tab)) {
					$this->active_admin_tab = self::DEFAULT_ACTIVE_ADMIN_TAB;
				}
			} 
			
			return $this->active_admin_tab;

    }

}

?>