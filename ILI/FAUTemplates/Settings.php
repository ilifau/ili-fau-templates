<?php

namespace ILI\FAUTemplates;

use ILI\FAUTemplates\Main;

defined('ABSPATH') || exit;

class Settings {
    
    /*
     * Main-Klasse
     * object
     */
    protected $main;
    
    protected $option_name;
    
    protected $options;
    
    /*
     * "Screen ID" der Einstellungsseite
     * string
     */
    protected $admin_settings_page;
    
    public function __construct(Main $main) {
        $this->main = $main;
        $this->option_name = $this->main->options->get_option_name();
        $this->options = $this->main->options->get_options();
    }
    
    /*
     * Füge eine Optionsseite in das Menü "Einstellungen" hinzu.
     * @return void
     */
    public function admin_settings_page() {
        $this->admin_settings_page = add_options_page(__('ILI FAU Templates', 'ili-fau-templates'), __('ILI FAU Templates', 'ili-fau-templates'), 'manage_options', 'ili-fau-templates', [$this, 'settings_page']);
        add_action('load-' . $this->admin_settings_page, [$this, 'admin_help_menu']);        
    }
    
    /*
     * Die Ausgabe der Optionsseite.
     * @return void
     */
    public function settings_page() {
        ?>
        <div class="wrap">
            <h2><?php echo __('Settings &rsaquo; ILI FAU Templates', 'ili-fau-templates'); ?></h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('ili_fau_templates_options');
                do_settings_sections('ili_fau_templates_options');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    /*
     * Legt die Einstellungen der Optionsseite fest.
     * @return void
     */
    public function admin_settings() {
        register_setting('ili_fau_templates_options', $this->option_name, [$this, 'options_validate']);
        add_settings_section('ili_fau_options_section_1', FALSE, '__return_false', 'ili_fau_templates_options');
        
        add_settings_field('ili_fau_templates_field_1', __('Field 1', 'ili-fau-templates'), [$this, 'ili_fau_templates_field_1'], 'ili_fau_templates_options', 'ili_fau_options_section_1');
        add_settings_field('ili_fau_templates_field_2', __('Field 2', 'ili-fau-templates'), [$this, 'ili_fau_templates_field_2'], 'ili_fau_templates_options', 'ili_fau_options_section_1');
    }

    /*
     * Validiert die Eingabe der Optionsseite.
     * @param array $input
     * @return array
     */
    public function options_validate($input) {
        $input['ili_fau_templates_text'] = ! empty( $input['ili_fau_templates_field_1'] ) ? $input['ili_fau_templates_field_1'] : '';
        $input['ili_fau_templates_number'] = ! empty( $input['ili_fau_templates_field_2'] ) ? absint( $input['ili_fau_templates_field_2'] ) : '0';

        return $input;
    }

    /*
     * Erstes Feld der Optionsseite
     * @return void
     */
    public function ili_fau_templates_field_1() {
        ?>
        <input type='text' name="<?php printf('%s[ili_fau_templates_field_1]', $this->option_name); ?>" value="<?php echo $this->options->ili_fau_templates_field_1; ?>">
        <?php
    }
    
    /*
     * Zweites Feld der Optionsseite (Checkbox)
     * @return void
     */
    public function ili_fau_templates_field_2() {
        ?>
        <select name="<?php printf('%s[ili_fau_templates_field_2]', $this->option_name); ?>">
            <?php foreach( array(
                0 => '0',
                1 => '1',
            ) as $key => $val ) {
                ?><option value="<?php echo $val; ?>"<?php
                    if( $key == $this->options->ili_fau_templates_field_2) echo ' selected="selected"';
                ?>><?php echo $val; ?></option>
        <?php } ?>
        </select> 
        <?php
    }

    /*
     * Erstellt die Kontexthilfe der Optionsseite.
     * @return void
     */
    public function admin_help_menu() {

        $content = [
            '<p>' . __('Here comes the Context Help content.', 'ili-fau-templates') . '</p>',
        ];


        $help_tab = [
            'id' => $this->admin_settings_page,
            'title' => __('Overview', 'ili-fau-templates'),
            'content' => implode(PHP_EOL, $content),
        ];

        $help_sidebar = sprintf('<p><strong>%1$s:</strong></p><p><a href="http://blogs.fau.de/webworking">RRZE-Webworking</a></p><p><a href="https://github.com/RRZE-Webteam">%2$s</a></p>', __('For more information', 'ili-fau-templates'), __('RRZE Webteam on Github', 'ili-fau-templates'));

        $screen = get_current_screen();

        if ($screen->id != $this->admin_settings_pageates_field_1) {
            return;
        }

        $screen->add_help_tab($help_tab);

        $screen->set_help_sidebar($help_sidebar);
    }
}