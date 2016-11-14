<?php

namespace Frozzare\Content;

class Menu {

	/**
	 * The menu index.
	 *
	 * @var int
	 */
	protected $index;

	/**
	 * Menu construct.
	 */
	public function __construct() {
		// Setup menu index.
		$this->index = $this->get_index();

		// Load localization files.
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wp-content-menu' );
		load_textdomain( 'wp-content-menu', WP_LANG_DIR . '/wp-content-menu/wp-content-menu-' . $locale . '.mo' );
		load_textdomain( 'wp-content-menu', PAPI_PLUGIN_DIR . '../languages/wp-content-menu-' . $locale . '.mo' );

		// Hook into admin actions.
		add_action( 'admin_init', [$this, 'remove_post_types_menu'] );
		add_action( 'admin_init', [$this, 'move_post_type_menu'] );
		add_action( 'admin_menu', [$this, 'admin_menu'] );
	}

	/**
	 * Get menu index.
	 *
	 * @return int
	 */
	protected function get_index() {
		global $menu;

		$index = 2;

		while ( isset( $menu[$index] ) ) {
			$index++;
		}

		return $index;
	}

	/**
	 * Add content menu.
	 */
	public function admin_menu() {
		add_menu_page(
			__( 'Content', 'wp-content-menu' ),
			__( 'Content', 'wp-content-menu' ),
			'edit_pages',
			'content',
			[$this, 'render'],
			'dashicons-editor-table',
			$this->index
		);
	}

	/**
	 * Get current post type.
	 *
	 * @return string
	 */
	protected function get_post_type() {
		if ( $post = get_post() ) {
			return get_post_type( $post );
		}

		return isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
	}

	/**
	 * Get post types.
	 *
	 * @return array
	 */
	protected function get_post_types() {
		$post_types = get_post_types( ['content_menu' => true] );
		$post_types = array_values( $post_types );
		$post_types = array_merge( $post_types, ['post', 'page'] );
		$post_types = array_unique( $post_types );

		/**
		 * Let developers modify post types array.
		 *
		 * @param array $post_types
		 */
		$post_types = apply_filters( 'content_menu_post_types', $post_types );
		$post_types = is_array( $post_types ) ? $post_types : [];

		return $post_types;
	}

	/**
	 * Move current post type menu items to content menu item.
	 */
	public function move_post_type_menu() {
		if ( ( ! $post_type = $this->get_post_type() ) ) {
			return;
		}

		global $menu, $submenu;

		$key = 'edit.php?post_type=';

		if ( $post_type === 'post' ) {
			$key = 'edit.php';
		} else {
			$key .= $post_type;
		}

		$i = $this->index - 1;

		$post_type_object = get_post_type_object( $post_type );

		foreach ( $menu as $index => $value ) {
			if ( ! array_search( $key, $value ) ) {
				continue;
			}

			$before = $menu[$index];

			unset( $menu[$index] );

			if ( $before[2] !== $key ) {
				continue;
			}

			$menu[$i][1] = $before[1];
			$menu[$i][2] = $before[2];
			$menu[$i][5] = $before[5];

			$sub = [
				1 => [
					0 => __( 'All Content', 'wp-content-menu' ),
					1 => $before[1],
					2 => 'admin.php?page=content'
				]
			];

			foreach ( $submenu[$key] as $index => $value ) {
				if ( $value[0] === $post_type_object->labels->add_new ) {
					$value[0] = apply_filters( 'content_menu_add_new_item_label',
						sprintf( '%s %s', __( 'Add New', 'wp-content-menu' ), $post_type_object->labels->singular_name )
					);
				}

				$sub[$index] = $value;
			}

			$submenu[$menu[$i][2]] = $sub;
		}
	}

	/**
	 * Add current post type submenu to content menu
	 * and remove all other post types.
	 */
	public function remove_post_types_menu() {
		global $menu;

		// Remove current post type if any.
		$post_types = array_diff( $this->get_post_types(), [$this->get_post_type()] );

		// Remove all post types except the current one.
		foreach ( $post_types as $post_type ) {
			$key = 'edit.php?post_type=';

			if ( $post_type === 'post' ) {
				$key = 'edit.php';
			} else {
				$key .= $post_type;
			}

			foreach ( $menu as $index => $value ) {
				if ( ! array_search( $key, $value ) ) {
					continue;
				}

				unset( $menu[$index] );
			}
		}
	}

	/**
	 * Render content page.
	 */
	public function render() {
		?>
		<style type="text/css">
			.tablenav {
				display: none;
			}

			.wp-list-table span.dashicons {
				padding-right: 5px;
			}

			.wp-list-table tfoot {
				display: none;
			}

			.content-type-menu {
				margin: 0;
				padding: 0;
			}

			.content-type-menu li {
				float: left;
			}

			.content-type-menu li:after {
				content: " | ";
				padding-right: 3px;
			}

			.content-type-menu li:last-child:after {
				content: "";
			}
		</style>
		<div class="wrap">
			<h1><?php echo __( 'Content', 'wp-content-menu' ); ?></h1>
			<?php
				$table = new Table( $this->get_post_types() );
				$table->prepare_items();
				$table->display();
			?>
		</div>
		<?php
	}
}
