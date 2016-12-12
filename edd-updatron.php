<?php
/**
 * @copyright 2016 Josh Pollock for CalderaWP - License under the terms of the GNU GPL v2+
 *
 * @wordpress-plugin
 * Plugin Name: EDD Update-A-Tron 3000
 * Plugin URI:  https://calderaforms.com
 * Description:
 * Version: 0.1.0
 * Author:      Josh Pollock For Caldera Labs <CalderaSaurus@CalderaWP.com>
 * Author URI:  https://calderaforms.com
 * Text Domain: edd-updatetron
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'plugins_loaded', [ 'CWP_EDD_UpdateTron', 'go' ] );
add_action( 'init', 'cwp_edd_updatron_register_post_type' );


class CWP_EDD_UpdateTron {
	const POST_TYPE = 'cwp_updates';

	const RELATED_DOWNLOAD_KEY = '_cwp_edd_updatron_related_downloads';

	public static function go(){
		if( is_admin() ){
			if( class_exists( 'CMB2' ) && class_exists( 'WDS_CMB2_Attached_Posts_Field' ) ){
				new CWP_EDD_UpdateTron_Admin();
			}

		}

	}
}

class CWP_EDD_UpdateTron_Admin{

	/**
	 * @var CMB2
	 */
	protected $cmb2;

	public function __construct() {
		$this->add_hooks();
	}

	public function add_hooks(){
		add_action( 'cmb2_init', [ $this, 'create_cmb2' ] );
		add_action( 'cmb2_init', [ $this, 'add_field' ] );

	}

	public function remove_hooks(){
		remove_action( 'cmb2_init', [ $this, 'create_cmb2' ] );
		remove_action( 'cmb2_init', [ $this, 'add_field' ] );
	}

	public function create_cmb2(){
		$this->cmb2 = new_cmb2_box( array(
			'id'           => 'cmb2_attached_posts_field',
			'title'        => __( 'Downloads', 'edd-updatetron' ),
			'object_types' => array( CWP_EDD_UpdateTron::POST_TYPE ), // Post type
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => false,
		) );
	}

	public function add_field(){
		$this->cmb2->add_field( array(
			'name'    => __( 'Downloads', 'cmb2' ),
			'id'      => CWP_EDD_UpdateTron::RELATED_DOWNLOAD_KEY,
			'type'    => 'custom_attached_posts',
			'options' => array(
				'show_thumbnails' => false,
				'filter_boxes'    => true,
				'query_args'      => array( 'posts_per_page' => 50 ),
			),
		) );

	}
}


function cwp_edd_updatron_register_post_type() {

	$labels = array(
		'name'                  => 'Update',
		'singular_name'         => 'Update',
		'menu_name'             => 'Updates',
		'name_admin_bar'        => 'Update',
		'archives'              => 'Item Archives',
		'parent_item_colon'     => 'Parent Item:',
		'all_items'             => 'All Items',
		'add_new_item'          => 'Add New Item',
		'add_new'               => 'Add New',
		'new_item'              => 'New Item',
		'edit_item'             => 'Edit Item',
		'update_item'           => 'Update Item',
		'view_item'             => 'View Item',
		'search_items'          => 'Search Item',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into item',
		'uploaded_to_this_item' => 'Uploaded to this item',
		'items_list'            => 'Items list',
		'items_list_navigation' => 'Items list navigation',
		'filter_items_list'     => 'Filter items list',
	);
	$args = array(
		'label'                 => 'Update',
		'description'           => 'Post Type Description',
		'labels'                => $labels,
		'supports'              => array( 'thumbnail', 'excerpt', 'title', 'editor'),
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 55,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);

	register_post_type( CWP_EDD_UpdateTron::POST_TYPE, $args );

}
