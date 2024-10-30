<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.wplauncher.com
 * @since      1.0.5
 *
 * @package    Binox_Wp
 * @subpackage Binox_Wp/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.5
 * @package    Binox_Wp
 * @subpackage Binox_Wp/includes
 * @author     Ben Shadle <benshadle@gmail.com>
 */
class Binox_Wp {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.5
	 * @access   protected
	 * @var      Binox_Wp_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.5
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.5
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.5
	 */
	public function __construct() {
		if ( defined( 'BINOX_WP_VERSION' ) ) {
			$this->version = BINOX_WP_VERSION;
		} else {
			$this->version = '1.0.5';
		}
		$this->plugin_name = 'settings-page';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Binox_Wp_Loader. Orchestrates the hooks of the plugin.
	 * - Binox_Wp_i18n. Defines internationalization functionality.
	 * - Binox_Wp_Admin. Defines all hooks for the admin area.
	 * - Binox_Wp_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.5
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-settings-page-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-settings-page-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-settings-page-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-settings-page-public.php';

		$this->loader = new Binox_Wp_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Binox_Wp_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.5
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Binox_Wp_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.5
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Binox_Wp_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.5
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Binox_Wp_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.5
	 */
	public function run() {
		if ( is_admin() ) {
			// Display the link with the plugin action links.
			add_filter( 'plugin_action_links', array( $this, 'plugin_links' ), 10, 3 );

			// Display the link with the plugin meta.
			add_filter( 'plugin_row_meta', array( $this, 'plugin_links' ), 10, 4 );
		}
		$this->loader->run();
	}
	public function plugin_links( $links, $plugin_file, $plugin_data ) {
		if ( isset( $plugin_data['PluginURI'] ) && false !== strpos( $plugin_data['PluginURI'], 'http://wordpress.org/extend/plugins/' ) ) {
			$slug = basename( $plugin_data['PluginURI'] );
	
			$links[] = sprintf( '<a href="%s" class="thickbox" title="%s">%s</a>',
				self_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $slug . '&amp;TB_iframe=true&amp;width=600&amp;height=550' ),
				esc_attr( sprintf( __( 'More information about %s' ), $plugin_data['Name'] ) ),
				__( 'Details' )
			);
		}
	
		return $links;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.5
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.5
	 * @return    Binox_Wp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.5
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
