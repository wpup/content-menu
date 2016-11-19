<?php

namespace Frozzare\Tests\Content;

use Frozzare\Content\Menu;

class Menu_Test extends \WP_UnitTestCase {
	public function setUp() {
		parent::setUp();

		$this->class = new Menu;
	}

	public function tearDown() {
		parent::tearDown();

		unset( $this->class );
	}

	public function test_actions() {
		$this->assertSame( 10, has_action( 'admin_init', [$this->class, 'remove_post_types_menu'] ) );
		$this->assertSame( 10, has_action( 'admin_init', [$this->class, 'move_post_type_menu'] ) );
		$this->assertSame( 10, has_action( 'admin_menu', [$this->class, 'admin_menu'] ) );
	}

	public function test_admin_menu() {
		global $menu;

		$menu = [];
		$this->assertFalse( isset( $menu[2] ) );

		$this->class->admin_menu();

		$this->assertTrue( isset( $menu[2] ) );
		$this->assertSame( 'Content', $menu[2][0] );
	}

	public function test_move_post_type_menu() {
		global $menu, $submenu;

		$menu = [];

		$this->assertNull( $this->class->move_post_type_menu() );

		$menu[20] = [
			'Pages',
			'edit_pages',
			'edit.php?post_type=page',
			'',
			'menu-top menu-icon-page',
			'menu-pages',
			'dashicons-admin-page'
		];

		$submenu = [];
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

		$_GET['post_type'] = 'page';
		$this->class->admin_menu();
		$this->class->move_post_type_menu();

		$this->assertSame( ['All Content', 'edit_pages', 'admin.php?page=content'], $submenu['edit.php?post_type=page'][1] );
	}

	public function test_remove_post_types_menu() {
		global $menu;

		$menu = [];

		$this->assertNull( $this->class->remove_post_types_menu() );

		$menu[20] = [
			'Pages',
			'edit_pages',
			'edit.php?post_type=page',
			'',
			'menu-top menu-icon-page',
			'menu-pages',
			'dashicons-admin-page'
		];

		$this->assertTrue( isset( $menu[20] ) );

		$this->class->admin_menu();
		$this->class->remove_post_types_menu();

		$this->assertFalse( isset( $menu[20] ) );
	}
}
