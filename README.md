# GIODC Woo Extend Filters

A WordPress plugin that extends WooCommerce with additional filter options in a sidebar widget. This plugin adds three filtering options:
- "On Sale" products filter
- "In Stock" products filter
- "Recent Products" filter (products from the last 30 days)

## Features

- Adds a sidebar widget with three filter checkboxes
- All filter options can be used independently or combined
- Includes a shortcode to display filters anywhere
- All text strings are translatable
- Clean, responsive design that integrates with any theme
- Lightweight with minimal impact on page load speed

## Requirements

- WordPress 5.0 or higher
- WooCommerce 3.0 or higher
- PHP 7.2 or higher

## Installation

1. Download the plugin zip file
2. Go to WordPress admin area and navigate to Plugins > Add New
3. Click "Upload Plugin" and select the zip file
4. Click "Install Now" and then "Activate"

Alternatively, you can manually install the plugin:

1. Upload the `giodc-woo-extend-filters` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

## Usage

### Widget

1. Go to Appearance > Widgets in your WordPress admin
2. Find the "GIODC Woo Extended Filters" widget
3. Drag it to your desired sidebar location
4. Configure the widget title (optional)
5. Save the widget settings

The widget will now appear in your sidebar on WooCommerce shop pages, category pages, and tag pages.

### Shortcode

You can also display the filters anywhere using the shortcode:

```
[giodc_wef_filters]
```

This is useful for placing the filters in:
- Custom page layouts
- Within content areas
- Inside other widgets that accept shortcodes

## Filter Options

### On Sale?

When checked, only products that are currently on sale will be displayed.

### In Stock?

When checked, only products that are currently in stock will be displayed.

### Recent Products?

When checked, only products added within the last 30 days will be displayed, sorted by newest first.

## Customization

### CSS Customization

The plugin includes basic styling that works with most themes. If you want to customize the appearance, you can add custom CSS to your theme.

The main CSS classes used by the plugin are:

- `.giodc-wef-widget` - The main widget container
- `.giodc-wef-filter-form` - The filter form
- `.giodc-wef-filter-option` - Each filter option container
- `.giodc-wef-filter-actions` - The container for buttons and reset link
- `.giodc-wef-reset-filters` - The reset filters link
- `.giodc-wef-shortcode-widget` - Container when used as shortcode

### Translations

The plugin is fully translatable. To create a translation:

1. Use a tool like Poedit to create a translation from the included .pot file
2. Save your translation files in the plugin's `/languages/` directory using the appropriate locale code

## Support

For support or feature requests, please contact the plugin author.

## License

This plugin is released under the GPL v2 or later license.

## Credits

Developed by Giovanni De Carlo.
