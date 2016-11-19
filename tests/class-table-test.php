<?php

namespace Frozzare\Tests\Content;

use Frozzare\Content\Table;

class Table_Test extends \WP_UnitTestCase {

	public function test_items() {
		global $hook_suffix, $submenu;

		$hook_suffix = '';
		$submenu = [];

		$table = new Table( ['page'] );
		$this->assertEmpty( $table->items );

		$submenu['edit.php?post_type=page'] = [
			5  => [
				'All Pages',
				'edit_pages',
				'edit.php?post_type=page'
			],
			10 => [
				'Add New',
				'edit_pages',
				'post-new.php?post_type=page'
			]
		];

		$table = new Table( ['page'] );
		$this->assertSame( 'Pages', $table->items[0]['title'] );
	}

	public function test_get_columns() {
		$table = new Table;
		$this->assertTrue( is_array( $table->get_columns() ) );
	}

	public function test_prepare_items() {
		global $hook_suffix, $submenu;

		$hook_suffix = '';
		$submenu = [];

		$submenu['edit.php'] = [
			5  => [
				'All Posts',
				'edit_posts',
				'edit.php'
			],
			10 => [
				'Add New',
				'edit_posts',
				'post-new.php'
			]
		];

		$submenu['edit.php?post_type=page'] = [
			5  => [
				'All Pages',
				'edit_pages',
				'edit.php?post_type=page'
			],
			10 => [
				'Add New',
				'edit_pages',
				'post-new.php?post_type=page'
			]
		];

		$table = new Table( ['post', 'page'] );
		$this->assertSame( 'Posts', $table->items[0]['title'] );
		$this->assertSame( 'Pages', $table->items[1]['title'] );

		$table->prepare_items();
		$this->assertSame( 'Pages', $table->items[0]['title'] );
		$this->assertSame( 'Posts', $table->items[1]['title'] );
	}
}
