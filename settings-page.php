<?php

/**
* The plugin bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              https://www.binoxmsp.com
* @since             1.0.5
* @package           Binox_Wp
*
* @wordpress-plugin
* Plugin Name:       Binox
* Plugin URI:        https://www.binoxmsp.com
* Description:       Driving traffic of you wordpress site. Our WordPress plugin will help you to track your visitors in real-time, using Binox tracking code for easier installation. This Plugin is only for Binox MSP Users please send an email to support@binoxmsp.com for further details.
* Version:           1.0.5
* Author:            Binox Developer
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       binox-wp
* Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
* Currently plugin version.
* Start at version 1.0.5 and use SemVer - https://semver.org
* Rename this for your plugin and update it as you release new versions.
*/
define( 'BINOX_WP_VERSION', '1.0.5' );

/**
* The code that runs during plugin activation.
* This action is documented in includes/class-settings-page-activator.php
*/

function activate_binox_wp() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-settings-page-activator.php';
    Binox_Wp_Activator::activate();
}

/**
* The code that runs during plugin deactivation.
* This action is documented in includes/class-settings-page-deactivator.php
*/

function deactivate_binox_wp() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-settings-page-deactivator.php';
    Binox_Wp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_binox_wp' );
register_deactivation_hook( __FILE__, 'deactivate_binox_wp' );

/**
* The core plugin class that is used to define internationalization,
* admin-specific hooks, and public-facing site hooks.
*/
require plugin_dir_path( __FILE__ ) . 'includes/class-settings-page.php';

/**
* Begins execution of the plugin.
*
* Since everything within the plugin is registered via hooks,
* then kicking off the plugin from this point in the file does
* not affect the page life cycle.
*
* @since    1.0.5
*/

function run_binox_wp() {

    $plugin = new Binox_Wp();
    $plugin->run();

}
run_binox_wp();

add_action( 'wp_enqueue_scripts', 'add_file_script_to_head_binox');
add_action( 'wp_head', 'add_script_to_head_binox');

function add_file_script_to_head_binox() {
    $account = get_option( 'binox_wp_account' );
    $domain = get_option( 'binox_wp_domain' );
    if ( $account == '' && $domain == '' ) {
        return;
    }
    wp_enqueue_script( 'binox_wp_ts', plugin_dir_url( __FILE__ ) . 'public/js/ts.js', false, BINOX_WP_VERSION, false );
}

function add_script_to_head_binox() {
    $account = get_option( 'binox_wp_account' );
    $domain = get_option( 'binox_wp_domain' );
    if ( $account == '' && $domain == '' ) {
        return;
    }
    ?>
    <script>
    var binox_settings = {
        accountId: "<?php echo esc_js($account);?>",
        domain: "<?php echo esc_js($domain);?>",
    }
    ;
    var binox_tracking = new Binox();
    binox_tracking.setAccount( binox_settings.accountId );
    binox_tracking.setDomain( binox_settings.domain );
    binox_tracking.trackPage();
    </script>
    <?php
}