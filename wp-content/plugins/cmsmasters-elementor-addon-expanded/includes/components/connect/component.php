<?php
namespace CmsmastersElementor\Components\Connect;

use CmsmastersElementor\Components\Connect\Apps\Base_App;
use CmsmastersElementor\Components\Connect\Apps\Connect;
use CmsmastersElementor\Components\Connect\Apps\Library;

use Elementor\Core\Base\Module as BaseModule;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Component extends BaseModule {

	/**
	 * @since 1.0.0
	 */
	public function get_name() {
		return 'connect';
	}

	/**
	 * @var array
	 */
	protected $registered_apps = array();

	/**
	 * Apps Instances.
	 *
	 * Holds the list of all the apps instances.
	 *
	 * @since 1.0.0
	 *
	 * @var Base_App[]
	 */
	protected $apps = array();

	/**
	 * Registered apps categories.
	 *
	 * Holds the list of all the registered apps categories.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $categories = array();

	protected $admin_page;

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->registered_apps = array(
			'connect' => Connect::get_class_name(),
			'library' => Library::get_class_name(),
		);

		// Note: The priority 11 is for allowing plugins to add their register callback on elementor init.
		add_action( 'elementor/init', array( $this, 'init' ), 11 );
	}

	/**
	 * Register default apps.
	 *
	 * Registers the default apps.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		// if ( is_admin() ) {
		// 	$this->admin_page = new Admin();
		// }

		/**
		 * Register Addon apps.
		 *
		 * Fires after Addon registers the default apps.
		 *
		 * @since 1.0.0
		 *
		 * @param self $this The apps manager instance.
		 */
		do_action( 'cmsmasters_elementor/connect/apps/register', $this );

		foreach ( $this->registered_apps as $slug => $class ) {
			$this->apps[ $slug ] = new $class();
		}

		add_filter( 'cmsmasters_elementor/editor/localize_settings', array( $this, 'localize_settings' ) );
	}

	public function localize_settings( $settings ) {
		return array_replace_recursive( $settings, array(
			'i18n' => array(
				'connect_error' => __( 'Unable to connect', 'cmsmasters-elementor' ),
				'connected_successfully' => __( 'Connected successfully', 'cmsmasters-elementor' ),
			),
		) );
	}

	/**
	 * Register app.
	 *
	 * Registers an app.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug App slug.
	 * @param string $class App full class name.
	 *
	 * @return self The updated apps manager instance.
	 */
	public function register_app( $slug, $class ) {
		$this->registered_apps[ $slug ] = $class;

		return $this;
	}

	/**
	 * Get app instance.
	 *
	 * Retrieve the app instance.
	 *
	 * @since 1.0.0
	 *
	 * @param $slug
	 *
	 * @return Base_App|null
	 */
	public function get_app( $slug ) {
		if ( isset( $this->apps[ $slug ] ) ) {
			return $this->apps[ $slug ];
		}

		return null;
	}

	/**
	 * @since 1.0.0
	 *
	 * @return Base_App[]
	 */
	public function get_apps() {
		return $this->apps;
	}

	/**
	 * @since 1.0.0
	 */
	public function register_category( $slug, $args ) {
		$this->categories[ $slug ] = $args;

		return $this;
	}

	/**
	 * @since 1.0.0
	 */
	public function get_categories() {
		return $this->categories;
	}

}
