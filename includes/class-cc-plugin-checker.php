<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    CC_Plugin_Checker
 * @subpackage CC_Plugin_Checker/includes
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
 * @since      1.0.0
 * @package    CC_Plugin_Checker
 * @subpackage CC_Plugin_Checker/includes
 * @author     Your Name <email@example.com>
 */
class CC_Plugin_Checker {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      CC_Plugin_Checker_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The unique prefix of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_prefix    The string used to uniquely prefix technical functions of this plugin.
	 */
	protected $plugin_prefix;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
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
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'CC_PLUGIN_CHECKER_VERSION' ) ) {

			$this->version = CC_PLUGIN_CHECKER_VERSION;

		} else {

			$this->version = '1.0.0';

		}

		$this->plugin_name = 'cc-plugin-checker';
		$this->plugin_prefix = 'pcc_';

		$this->define_admin_hooks();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		if ( ! is_admin() ) {
			return;
		}
		add_filter( 'plugin_action_links_' . $this->plugin_name . '/' . $this->plugin_name . '.php', array( $this, 'pcc_plugin_settings_link' ), 10, 5 );
		add_action( 'admin_menu', array( $this, 'cc_menu_pages' ) );

	}

	/**
	 * Add Menu Page
	 */
	public function cc_menu_pages() {
		add_menu_page( __( 'CC Plugin Check', 'cc-plugin-checker' ), __( 'CC Plugin Check', 'cc-plugin-checker' ), 'manage_options', 'pcc_check', array( $this, 'pcc_check' ), 'dashicons-yes', 90 );
	}

	/**
	 * Add Settings Link
	 *
	 * @param array $actions The Actions of this Plugin.
	 */
	public function pcc_plugin_settings_link( $actions ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$url = '/wp-admin/admin.php?page=pcc_check';
		$link = '<a href="' . $url . '" target="_blank">' . esc_html__( 'Check Now', 'cc-plugin-checker' ) . '</a>';
		$actions[] = $link;

		return $actions;

	}

	/**
	 * Build table for plugins list.
	 */
	public static function pcc_check() {

		if ( is_null( get_current_screen() )
			|| 'toplevel_page_pcc_check' !== get_current_screen()->id
		) {
			return;
		}

		echo '<style>
			td, th {
			border: 1px solid #ddd;
			padding: 6px;
			font-size: 16px;
			}

			th {
			padding-top: 8px;
			padding-bottom: 8px;
			text-align: left;
			background-color: #666;
			color: white;
			}

			p {
				font-size: 16px;
			}
			span {
				color: #b2b2b2;
			}
			</style>
			<br />
			<h1>' . esc_html__( 'Check Your WC/CC Plugin Compatibility', 'cc-plugin-checker' ) . '</h1>
			<p>' . esc_html__( 'Classic Commerce was forked from WooCommerce 3.5.3.', 'cc-plugin-checker' ) . '<br />' . esc_html__( 'Any plugins with a rating of "WC requires at least" that is more than 3.5.3 may not work with Classic Commerce.', 'cc-plugin-checker' ) . '</p>
			<table>
			<tr>
			<th>' . esc_html__( 'Plugin name', 'cc-plugin-checker' ) . '</th>
			<th>' . esc_html__( 'Current version', 'cc-plugin-checker' ) . '</th>
			<th>' . esc_html__( 'WC requires at least', 'cc-plugin-checker' ) . '</th>
			<th>' . esc_html__( 'WC tested up to', 'cc-plugin-checker' ) . '</th>
			</tr>';

		$plugin = get_plugins();

		foreach ( $plugin as $key => $plug ) {

			$plugin_details = get_plugin_data( WP_PLUGIN_DIR . '/' . $key );
			$plugin_name = isset( $plugin_details['Name'] ) && ! empty( $plugin_details['Name'] ) ? $plugin_details['Name'] : __( 'Unknown Plugin', 'cc-plugin-checker' );
			$plugin_version = isset( $plugin_details['Version'] ) && ! empty( $plugin_details['Version'] ) ? $plugin_details['Version'] : __( 'Unknown Version', 'cc-plugin-checker' );
			$wc_requires_least = isset( $plugin_details['WC requires at least'] ) && ! empty( $plugin_details['WC requires at least'] ) ? $plugin_details['WC requires at least'] : __( 'No WC Tag', 'cc-plugin-checker' );
			$wc_tested_to = isset( $plugin_details['WC tested up to'] ) && ! empty( $plugin_details['WC tested up to'] ) ? $plugin_details['WC tested up to'] : __( 'No WC Tag', 'cc-plugin-checker' );

			echo '<tbody>
				<tr>
				<td>' . esc_html( $plugin_name ) . '</td>
				<td>' . esc_html( $plugin_version ) . '</td>
				<td><span>' . esc_html( $wc_requires_least ) . '</span></td>
				<td><span>' . esc_html( $wc_tested_to ) . '</span></td>
				</tr>';
		}

			echo '</tbody>
				</table>';

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The unique prefix of the plugin used to uniquely prefix technical functions.
	 *
	 * @since     1.0.0
	 * @return    string    The prefix of the plugin.
	 */
	public function get_plugin_prefix() {
		return $this->plugin_prefix;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
