# Content menu [![Build Status](https://travis-ci.org/frozzare/wp-content-menu.svg?branch=master)](https://travis-ci.org/frozzare/wp-content-menu)

Adds a content menu to WordPress admin where all your post types that are used for content can live, when doing this the post type configured to use content menu will be removed from the admin menu and if you have a lot of post types you will see a more clean admin menu than before.

Both `post` and `page` will be moved to content menu by default and can be unmoved by `content_menu_post_types` filter.

Content menu will only have submenu items when a post type is selected so you can see and use the submenu items for that post types, as you can see in the second screenshot.

## Installation

```sh
composer require frozzare/wp-content-menu
```

## Usage

To move your post types into content menu you can set `content_menu` to `true` in `register_post_type` or use `content_menu_post_types` filter.

```php
// With `register_post_type`
register_post_type( 'book', [
  'content_menu' => true
] );

// With the filter.
add_filter( 'content_menu_post_types', function ( $post_types ) {
  return ['page', 'post', 'book']
} );
```

You can modify `Add New Book` label by `content_menu_add_new_item_label` filter.

```php
add_filter( 'content_menu_add_new_item_label', function ( $label ) {
  return 'Add New';
} );
```

Disable content menu for users:

```php
add_filter( 'content_menu_user_allowed', function ($allowed, $user) {
  return false;
}, 10, 2 );
```

## Screenshots

![](https://cloud.githubusercontent.com/assets/14610/20240391/4ab1f8d0-a917-11e6-9994-616924b94f53.png)

![](https://cloud.githubusercontent.com/assets/14610/20256173/0ce9c790-aa42-11e6-818e-8348862a252d.png)

## Contributing

Everyone is welcome to contribute with patches, bug-fixes and new features.

## License

MIT © [Fredrik Forsmo](https://github.com/frozzare)
