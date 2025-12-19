## Assets

The `Core\Assets` class collects stylesheets and JavaScript files that your templates will include when rendering HTML responses.

Key behaviors
- Stylesheets default to the `header` region; JavaScript defaults to the `footer` region.
- Assets are kept in the order they are added.
- Regions are arbitrary string keys (for example `header`, `footer`, `print`).

API
- `addStylesheet(string $path, string $region = 'header'): void` — register a stylesheet path for the given region.
- `addJavascript(string $path, string $region = 'footer'): void` — register a JavaScript path for the given region.
- `getStylesheets(string $region = 'header'): array` — retrieve stylesheets for a region.
- `getJavascript(string $region = 'footer'): array` — retrieve JavaScript files for a region.

Note: Paths should match your asset layout in templates directory.  

Examples

In a controller (add assets to be included by the template):

```php
// Add a stylesheet to the header
$this->template->assets->addStylesheet('assets/css/screen.css', 'header');

// Add a responsive stylesheet for a print or special region
$this->template->assets->addStylesheet('assets/css/responsive.css', 'print');

// Add a script to the footer
$this->template->assets->addJavascript('assets/js/jquery-3.7.1.min.js', 'footer');
```

In a partial controller (collecting assets or exposing them to templates):

```php
$this->response->data['stylesheets'] = \Arr::merge(
		$this->response->data['stylesheets'] ?? [],
		$this->template->assets->getStylesheets('header')
);

$this->response->data['javascript'] = \Arr::merge(
		$this->response->data['javascript'] ?? [],
		$this->template->assets->getJavascript('footer')
);
```

In a template (render the assets):

```php
<?php foreach ($stylesheets as $style): ?>
	<link rel="stylesheet" href="<?php print $style; ?>" />
<?php endforeach; ?>

<?php foreach ($javascript as $script): ?>
	<script src="<?php print $script; ?>"></script>
<?php endforeach; ?>
```

Further notes
- The `Assets` object stores entries per region; templates or partial controllers decide how to include each region.
- Use `getStylesheets()` and `getJavascript()` with the matching region names to retrieve lists for rendering.