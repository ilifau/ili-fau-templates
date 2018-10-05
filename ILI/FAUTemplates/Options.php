<?php

namespace ILI\FAUTemplates;

defined('ABSPATH') || exit;

class Options {
    
    protected $option_name = 'ili_fau_templates';
    
    public function __construct() {
        // ...
    }
    
    /*
     * Standard Einstellungen werden definiert
     * @return array
     */
    public function default_options() {
        $options = [
            'ili_fau_templates_max_num_slides' => '',
            'ili_fau_templates_topic_box_excerpt_length' => '',
            'ili_fau_templates_field_role' => '',
            // Hier können weitere Felder ('key' => 'value') angelegt werden.
        ];

        return $options;
    }

    /*
     * Gibt die Einstellungen zurück.
     * @return object
     */
    public function get_options() {
        $defaults = self::default_options();

        $options = (array) get_option($this->option_name);
        $options = wp_parse_args($options, $defaults);
        $options = array_intersect_key($options, $defaults);

        return (object) $options;
    }
    
    public function get_option_name() {
        return $this->option_name;
    }
    
}
