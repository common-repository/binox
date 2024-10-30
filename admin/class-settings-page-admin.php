<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wplauncher.com
 * @since      1.0.5
 *
 * @package    Binox_Wp
 * @subpackage Binox_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Binox_Wp
 * @subpackage Binox_Wp/admin
 * @author     Ben Shadle <benshadle@gmail.com>
 */
class Binox_Wp_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.5
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.5
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.5
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action('admin_menu', array($this, 'addPluginAdminMenu'), 9);
        add_action('admin_init', array($this, 'registerAndBuildFields'));

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.5
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Binox_Wp_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Binox_Wp_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/settings-page-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.5
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Binox_Wp_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Binox_Wp_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/settings-page-admin.js', array('jquery'), $this->version, false);

    }
    public function addPluginAdminMenu()
    {
        //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        add_menu_page($this->plugin_name, 'Binox', 'administrator', $this->plugin_name, array($this, 'displayPluginAdminDashboard'), 'dashicons-chart-area', 26);

        //add_submenu_page( '$parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
        // add_submenu_page($this->plugin_name, 'Settings', 'Settings', 'administrator', $this->plugin_name . '-settings', array($this, 'displayPluginAdminSettings'));
    }
    public function displayPluginAdminDashboard()
    {
        // require_once 'partials/'.$this->plugin_name.'-admin-display.php';
        $active_tab = isset($_GET['tab']) ? sanitize_title($_GET['tab']) : 'general';
        if (isset($_GET['error_message'])) {
            add_action('admin_notices', array($this, 'settingsPageSettingsMessages'));
            do_action('admin_notices', $_GET['error_message']);
        }
        require_once 'partials/' . $this->plugin_name . '-admin-settings-display.php';
    }
    public function displayPluginAdminSettings()
    {
        // set this var to be used in the settings-display view
        $active_tab = isset($_GET['tab']) ? sanitize_title($_GET['tab']) : 'general';
        if (isset($_GET['error_message'])) {
            add_action('admin_notices', array($this, 'settingsPageSettingsMessages'));
            do_action('admin_notices', $_GET['error_message']);
        }
        require_once 'partials/' . $this->plugin_name . '-admin-settings-display.php';
    }
    public function settingsPageSettingsMessages($error_message)
    {
        switch ($error_message) {
            case '1':
                $message = __('There was an error adding this setting. Please try again.  If this persists, shoot us an email.', 'my-text-domain');
                $err_code = esc_attr('settings_page_example_setting');
                $setting_field = 'settings_page_example_setting';
                break;
        }
        $type = 'error';
        add_settings_error(
            $setting_field,
            $err_code,
            $message,
            $type
        );
    }
    public function registerAndBuildFields()
    {
        /**
         * First, we add_settings_section. This is necessary since all future settings must belong to one.
         * Second, add_settings_field
         * Third, register_setting
         */
		
        add_settings_section(
            // ID used to identify this section and with which to register options
            'binox_wp_general_section',
            // Title to be displayed on the administration page
            '',
            // Callback used to render the description of the section
            array($this, 'settings_page_display_general_account'),
            // Page on which to add this section of options
            'binox_wp_general_settings',
			'validate_inputs_binox'
        );
        unset($args);
        $args = array(
            'type' => 'input',
            'subtype' => 'text',
            'id' => 'binox_wp_account',
            'name' => 'binox_wp_account',
            'required' => 'true',
            'get_options_list' => '',
            'value_type' => 'normal',
            'wp_data' => 'option',
        );
        add_settings_field(
            'binox_wp_account',
            'Account ID',
            array($this, 'settings_page_render_settings_field'),
            'binox_wp_general_settings',
            'binox_wp_general_section',
            $args
        );
        // unset($args);
        $args2 = array(
            'type' => 'input',
            'subtype' => 'text',
            'id' => 'binox_wp_domain',
            'name' => 'binox_wp_domain',
            'required' => 'true',
            'get_options_list' => '',
            'value_type' => 'normal',
            'wp_data' => 'option',
        );
        add_settings_field(
            'binox_wp_domain',
            'Domain',
            array($this, 'settings_page_render_settings_field'),
            'binox_wp_general_settings',
            'binox_wp_general_section',
            $args2
        );

		register_setting(
            'binox_wp_general_settings',
			'binox_wp_account',
			'validate_inputs_binox'
        );
        register_setting(
            'binox_wp_general_settings',
			'binox_wp_domain',
			'validate_inputs_binox'
        );
    }
	function validate_inputs_binox( $input ) {
		$valid = array();
		// var_dump($input);
		$valid['boss_email'] = sanitize_email( $input['boss_email'] );
		
		// Something dirty entered? Warn user.
		// if( $valid['boss_email'] != $input['boss_email'] ) {
			add_settings_error(
				'binox_wp_general_settings',           // setting title
				'binox_wp_domainerror',            // error ID
				'Invalid email, please fix',   // error message
				'error'                        // type of message
			);		
		// }
		
		return false;
	}
    public function settings_page_display_general_account()
    {
        echo '<p>These settings apply to all Plugin Name functionality.</p>';
    }
    public function settings_page_render_settings_field($args)
    {
        /* EXAMPLE INPUT
        'type'      => 'input',
        'subtype'   => '',
        'id'    => $this->plugin_name.'_example_setting',
        'name'      => $this->plugin_name.'_example_setting',
        'required' => 'required="required"',
        'get_option_list' => "",
        'value_type' = serialized OR normal,
        'wp_data'=>(option or post_meta),
        'post_id' =>
         */
        if ($args['wp_data'] == 'option') {
            $wp_data_value = get_option($args['name']);
        } elseif ($args['wp_data'] == 'post_meta') {
            $wp_data_value = get_post_meta($args['post_id'], $args['name'], true);
        }

        switch ($args['type']) {

            case 'input':
                $value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
                if ($args['subtype'] != 'checkbox') {
                    $prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">' . $args['prepend_value'] . '</span>' : '';
                    $prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';
                    $step = (isset($args['step'])) ? 'step="' . $args['step'] . '"' : '';
                    $min = (isset($args['min'])) ? 'min="' . $args['min'] . '"' : '';
                    $max = (isset($args['max'])) ? 'max="' . $args['max'] . '"' : '';
                    if (isset($args['disabled'])) {
                        // hide the actual input bc if it was just a disabled input the info saved in the database would be wrong - bc it would pass empty values and wipe the actual information
                        echo esc_html($prependStart) . esc_html('<input type="' . esc_attr($args['subtype']) . '" id="' . esc_attr($args['id']) . '_disabled" ' . esc_attr($step) . ' ' . esc_attr($max) . ' ' . esc_attr($min) . ' name="' . esc_attr($args['name']) . '_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="' . esc_attr($args['id']) . '" ' . esc_attr($step) . ' ' . esc_attr($max) . ' ' . esc_attr($min) . ' name="' . esc_attr($args['name']) . '" size="40" value="' . esc_attr($value) . '" />') . esc_html($prependEnd);
                    } else {
                        echo esc_html($prependStart) . esc_html('<input type="' . esc_attr($args['subtype']) . '" id="' . esc_attr($args['id']) . '" "' . esc_attr($args['required']) . '" ' . esc_attr($step) . ' ' . esc_attr($max) . ' ' . esc_attr($min) . ' name="' . esc_attr($args['name']) . '" size="40" value="' . esc_attr($value) . '" />') . esc_html($prependEnd);
                    }
                    /*<input required="required" '.$disabled.' type="number" step="any" id="'.$this->plugin_name.'_cost2" name="'.$this->plugin_name.'_cost2" value="' . esc_attr( $cost ) . '" size="25" /><input type="hidden" id="'.$this->plugin_name.'_cost" step="any" name="'.$this->plugin_name.'_cost" value="' . esc_attr( $cost ) . '" />*/

                } else {
                    $checked = ($value) ? 'checked' : '';
                    echo esc_html('<input type="' . esc_attr($args['subtype']) . '" id="' . esc_attr($args['id']) . '" "' . esc_attr($args['required']) . '" name="' . esc_attr($args['name']) . '" size="40" value="1" ' . esc_attr($checked) . ' />');
                }
                break;
            default:
                # code...
                break;
        }
    }
}
