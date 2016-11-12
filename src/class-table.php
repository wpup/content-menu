<?php

namespace Frozzare\Content;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Table extends \WP_List_Table {

	/**
	 * Table construct.
	 *
	 * @param array $post_types
	 */
	public function __construct( array $post_types = [] ) {
		parent::__construct( [
			'singular' => __( 'Content', 'wp-content-menu' ),
			'plural'   => __( 'Content', 'wp-content-menu' ),
			'ajax'     => false,
		] );

		global $submenu;

		foreach ( $post_types as $index => $post_type ) {
			$key = 'edit.php?post_type=';

			if ( $post_type === 'post' ) {
				$key = 'edit.php';
			} else {
				$key .= $post_type;
			}

			if ( ! isset( $submenu[$key] ) ) {
				continue;
			}

			$post_type_object = get_post_type_object( $post_type );

			$links = $submenu[$key];
			$item  = [
				'ID'   => $index,
				'type' => $post_type_object->labels->name,
				'menu' => ''
			];

			foreach ( $links as $index2 => $link ) {
				if ( $index2 === 5 ) {
					$url = admin_url( $link[2] );
					$item['type'] = sprintf( '<a href="%s">%s</a>', $url, $item['type'] );
					continue;
				}

				$url = admin_url( $link[2] );
				$item['menu'] .= sprintf( '<li><a href="%s">%s</a></li>', $url, $link[0] );
			}

			$item['menu'] = sprintf( '<ul class="content-type-menu">%s</ul>', $item['menu'] );

			$this->items[] = $item;
		}
	}

	/**
	 * Get a list of columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return [
			'type' => __( 'Type', 'wp-content-menu' ),
			'menu' => __( 'Menu', 'wp-content-menu' )
		];
	}

	/**
	 * Get default column value.
	 *
	 * @return string
	 */
	protected function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'type':
			case 'menu':
				return $item[$column_name];
			default:
				return '';
		}
	}

	/**
	 * Prepares the list of items for displaying.
	 */
	public function prepare_items() {
		$this->_column_headers = [$this->get_columns()];
		usort( $this->items, array( $this, 'usort_reorder' ) );
	}

	/**
	 * Callback to allow sorting of example data.
	 *
	 * @param  string $a
	 * @param  string $b
	 *
	 * @return int
	 */
	protected function usort_reorder( $a, $b ) {
		return strcmp( $a['type'], $b['type'] );
	}
}
