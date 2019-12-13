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
        $this->screen = 'settings_page_ili-fau-templates';
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
        
        add_settings_field('ili_fau_templates_max_num_slides', __('Max. Anzahl Slides pro Seite', 'ili-fau-templates'), [$this, 'ili_fau_templates_max_num_slides'], 'ili_fau_templates_options', 'ili_fau_options_section_1');
        add_settings_field('ili_fau_templates_topic_box_excerpt_length_default', __('Länge des Anreißertextes der Themenboxen (mind. 10 Zeichen)', 'ili-fau-templates'), [$this, 'ili_fau_templates_topic_box_excerpt_length_default'], 'ili_fau_templates_options', 'ili_fau_options_section_1');
        add_settings_field('ili_fau_templates_slide_default', __('Default Slide (ID)', 'ili-fau-templates'), [$this, 'ili_fau_templates_slide_default'], 'ili_fau_templates_options', 'ili_fau_options_section_1');
        // add_settings_field('ili_fau_templates_field_role', __('Rolle mindestens', 'ili-fau-templates'), [$this, 'ili_fau_templates_field_role'], 'ili_fau_templates_options', 'ili_fau_options_section_1');
    }

    /*
     * Validiert die Eingabe der Optionsseite.
     * @param array $input
     * @return array
     */
    public function options_validate($input) {
        $input['ili_fau_templates_max_num_slides'] = absint( $input['ili_fau_templates_max_num_slides'] );
        $input['ili_fau_templates_topic_box_excerpt_length_default'] = absint( $input['ili_fau_templates_topic_box_excerpt_length_default'] );
        
        if( $input['ili_fau_templates_max_num_slides'] < 1 ) {
            $input['ili_fau_templates_max_num_slides'] = 1;
        }
        
        if( $input['ili_fau_templates_topic_box_excerpt_length_default'] < 10 ) {
            $input['ili_fau_templates_topic_box_excerpt_length_default'] = 10;
        }
        
        $input['ili_fau_templates_slide_default'] = ! empty( $input['ili_fau_templates_slide_default'] ) ? absint( $input['ili_fau_templates_slide_default'] ) : '0';
        $input['ili_fau_templates_number'] = ! empty( $input['ili_fau_templates_field_role'] ) ? absint( $input['ili_fau_templates_field_role'] ) : '0';

        return $input;
    }

    /*
     * Option maximale Anzahl der Slides pro Seite
     * @return void
     */
    public function ili_fau_templates_max_num_slides() {
        ?>
        <input type="text" class="ilifautpl-input"  name="<?php printf('%s[ili_fau_templates_max_num_slides]', $this->option_name); ?>" value="<?php echo $this->options->ili_fau_templates_max_num_slides; ?>" placeholder="3">
        <?php
    }
    
    /*
     * Option Länge des Anreißertextes der Thememboxen
     * @return void
     */
    public function ili_fau_templates_topic_box_excerpt_length_default() {
        ?>
        <input type="number" class="ilifautpl-input" min="10" max="999999" step="1" name="<?php printf('%s[ili_fau_templates_topic_box_excerpt_length_default]', $this->option_name); ?>" value="<?php echo $this->options->ili_fau_templates_topic_box_excerpt_length_default; ?>" placeholder="150">
        <?php
    }
    
    /*
     * Option default slide
     * @return void
     */
    public function ili_fau_templates_slide_default() {
        ?>
        <div class="ilifautpl-input-select-wrapper"><?php
            $basename = basename( plugin_dir_path(  dirname( __FILE__ , 2 ) ) );
            $placeholder = esc_url( plugins_url() . '/' . $basename . '/assets/img/slide-default.jpg' );
            $upload_dir = wp_upload_dir();
            
            echo '<div class="ilifautpl-input-slide-wrapper ilifautpl-input-select-wrapper" data-id="1">';
            
                if( $this->options->ili_fau_templates_slide_default === '0' || empty( $this->options->ili_fau_templates_slide_default ) ) {
                    echo '<img class="ilifautpl-slide-preview" src="' . $placeholder . '" alt="" />';
                } else {
                    if( function_exists('fau_get_image_attributs') ) {
                        $atts = fau_get_image_attributs( $this->options->ili_fau_templates_slide_default );
                    } else {
                        $atts = [];
                    }

                    echo '<img class="ilifautpl-slide-preview" src="' . esc_url( $upload_dir['baseurl'] . '/' . $atts['attachment_file'] ) . '" alt="" />';
                } ?>
                
                <input type="text" class="ilifautpl-input ilifautpl-input-select" name="<?php printf('%s[ili_fau_templates_slide_default]', $this->option_name); ?>" value="<?php echo $this->options->ili_fau_templates_slide_default; ?>" placeholder="ID&hellip;">
                <a class="button ilifautpl-input-select-media" data-id="1" data-placeholder="<?php echo $placeholder; ?>"><?php _e('Bild auswählen', 'ili-fau-templates'); ?></a>
            </div>
        <?php
    }
    
    /*
     * Option für rollenbasierten Zugriff auf das Plugin (inaktiv)
     * @return void
     */
    public function ili_fau_templates_field_role() {
        $roles = get_editable_roles();
        ?>
        <select name="<?php printf('%s[ili_fau_templates_field_role]', $this->option_name); ?>">
            <?php foreach( $roles as $key => $role ) {
                ?><option value="<?php echo $key ?>"<?php
                    if( $key == $this->options->ili_fau_templates_field_role) echo ' selected="selected"';
                ?>><?php echo $role['name'] ?></option>
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

        if ($screen->id !== $this->screen) {
            return;
        }

        $screen->add_help_tab($help_tab);

        $screen->set_help_sidebar($help_sidebar);
    }
}
