<?php

namespace ILI\FAUTemplates;

use ILI\FAUTemplates\Options;
use ILI\FAUTemplates\Settings;
use ILI\FAUTemplates\Templates;
use ILI\FAUTemplates\Meta;

defined('ABSPATH') || exit;

class Main {
    
    public $options;
    
    public $settings;

    public function __construct($plugin_basename) {
        $this->options = new Options();
        $this->settings = new Settings($this);       
        $this->templates = new Templates($this);       
        $this->meta = new Meta($this);       
        
        add_action('admin_menu', [$this->settings, 'admin_settings_page']);
        add_action('admin_init', [$this->settings, 'admin_settings']);
    }
    
}
