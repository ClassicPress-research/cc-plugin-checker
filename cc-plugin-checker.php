<?php
/**
* Plugin Name: CC Plugin Checker
* Description: Check your WC plugins are compatible with Classic Commerce before migrating your site
* Version: 0.0.1
* Author: Alan Coggins
* Author URI: https://simplycomputing.com.au
**/

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'pcc_plugin_settings_link', 10, 5 );

function pcc_plugin_settings_link( $links )
{
    $url = '/wp-admin/admin.php?page=PCC_Check';

    $_link = '<a href="'.$url.'" target="_blank">' . __( 'CHECK NOW', 'domain' ) . '</a>';

    $links[] = $_link;

    return $links;
}

if ( !class_exists('PCC')) {

	Class PCC {

		public  function __construct() {
		//Hook to add admin menu 
			add_action("admin_menu", array($this,"PCC_Menu_Pages"));
		}

		//Define 'PCC_Menu_Pages'
		function PCC_Menu_Pages() {
			add_menu_page( 'CC Plugin Check', 'CC Plugin Check', 'manage_options', 'PCC_Check', array(__CLASS__,'PCC_Check'), 'dashicons-yes', 90);
		}

		//Define function
		public static function PCC_Check() {

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
			<h1>Check Your WC/CC Plugin Compatibility</h1>
			<p>Classic Commerce was forked from WooCommerce 3.5.3.<br />Any plugins that have a rating of "WC requires at least" that is more than 3.5.3 may not work with Classic Commerce.</p>
			<table>
			<tr>
			<th>Plugin name</th>
			<th>Current version</th>
			<th>WC requires at least</th>
			<th>WC tested up to</th>
			</tr>';

			$plugin=get_plugins();

				foreach($plugin as $key => $plug) {

					$PluginName = $plug['Name'];
					$PluginSlug = $plug['TextDomain'];
					$PluginCurrentVersion = $plug['Version'];
					$PluginPath = WP_PLUGIN_DIR . '/' . $key;

					$PluginDetails = get_plugin_data($PluginPath);

					$RequiresAtLeast = $PluginDetails['WC requires at least'];
					if ($RequiresAtLeast === '') {
						$RequiresAtLeast = '<span>No WC tag</span>';
					}

					$TestedUpTo = $PluginDetails['WC tested up to'];
					if ($TestedUpTo === '') {
						$TestedUpTo = '<span>No WC tag</span>';
					}

					echo '<tbody>
								<tr>
								<td>'.$PluginName.'</td>
								<td>'.$PluginCurrentVersion.'</td>
								<td>'.$RequiresAtLeast.'</td>
								<td>'.$TestedUpTo.'</td>
								</tr>';
			}

					echo '</tbody>
								</table>';

		}
	}
}

new PCC();