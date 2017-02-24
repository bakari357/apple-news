<?php
/**
 * This class is in charge of handling the management of custom JSON.
 */
class Admin_Apple_JSON extends Apple_News {

	/**
	 * JSON management page name.
	 *
	 * @var string
	 * @access public
	 */
	public $json_page_name;

	/**
	 * Prefix for the key for storing custom JSON.
	 *
	 * @var string
	 * @const
	 */
	const JSON_KEY_PREFIX = 'apple_news_json_';

	/**
	 * Constructor.
	 */
	function __construct() {
		$this->json_page_name = $this->plugin_domain . '-json';

		add_action( 'admin_menu', array( $this, 'setup_json_page' ), 99 );
		add_action( 'admin_init', array( $this, 'action_router' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
		add_filter( 'admin_title', array( $this, 'set_title' ), 10, 2 );
	}

	/**
	 * Fix the title since WordPress doesn't set one.
	 *
	 * @param string $admin_title
	 * @param string $title
	 * @return strign
	 * @access public
	 */
	public function set_title( $admin_title, $title ) {
		$screen = get_current_screen();
		if ( 'admin_page_' . $this->json_page_name === $screen->base ) {
			$admin_title = __( 'Customize JSON' ) . $admin_title;
		}

		return $admin_title;
	}

	/**
	 * Options page setup.
	 *
	 * @access public
	 */
	public function setup_json_page() {
		add_submenu_page(
			'apple_news_index',
			__( 'Customize Apple News JSON', 'apple-news' ),
			__( 'Customize JSON', 'apple-news' ),
			apply_filters( 'apple_news_settings_capability', 'manage_options' ),
			$this->json_page_name,
			array( $this, 'page_json_render' )
		);
	}

	/**
	 * JSON page render.
	 *
	 * @access public
	 */
	public function page_themes_render() {
		if ( ! current_user_can( apply_filters( 'apple_news_settings_capability', 'manage_options' ) ) ) {
			wp_die( __( 'You do not have permissions to access this page.', 'apple-news' ) );
		}

		include plugin_dir_path( __FILE__ ) . 'partials/page_json.php';
	}

	/**
	 * Register assets for the options page.
	 *
	 * @param string $hook
	 * @access public
	 */
	public function register_assets( $hook ) {
		if ( 'apple-news_page_apple-news-json' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'apple-news-json-css',
			plugin_dir_url( __FILE__ ) . '../assets/css/json.css',
			array(),
			self::$version
		);

		wp_enqueue_script(
			'apple-news-json-js',
			plugin_dir_url( __FILE__ ) . '../assets/js/json.js',
			array( 'jquery' ),
			self::$version
		);
	}

	/**
	 * Saves the JSON snippets for the component
	 *
	 * @param string $component
	 * @param string $json
	 * @access private
	 */
	private function save_json( $component, $json ) {

	}

	/**
	 * Loads the JSON snippets that can be customized for the component
	 *
	 * @param string $component
	 * @return array
	 * @access private
	 */
	private function get_json( $component ) {

	}

	/**
	 * Lists all components that can be customized
	 *
	 * @return array
	 * @access private
	 */
	private function list_components( $component ) {

	}

	/**
	 * Generates a key for the JSON from the provided component
	 *
	 * @param string $component
	 * @return string
	 * @access public
	 */
	public function json_key_from_name( $component ) {
		return self::JSON_KEY_PREFIX . sanitize_key( $component );
	}

	/**
	 * Returns the URL of the JSON admin page
	 *
	 * @param string $name
	 * @return string
	 * @access public
	 */
	public function json_admin_url() {
		return add_query_arg( 'page', $this->json_page_name, admin_url( 'admin.php' ) );
	}
}