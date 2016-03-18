# Kirby Image Otimizer Plugin
Kirby CMS Plugin for automatic image compression and scaling.

# Installation

Copy or clone this repository into your `/site/plugins` directory.

### Basic setup `config.php`:
```php
c::set('imageoptim', true);           // {true} activate the plugin
```
### Advanced options
```php
// Quality compression in percentage. (0 = lowest quality, 100 = highest quality)
c::set('imageoptim.quality', 90); // default: 90

// Downscale the image to a given width.
c::set('imageoptim.max_width', 1920); // default: disabled
```

# Copyright

Copyright 2016 - 51seven, Gesellschaft für Markenkommunikation mbH.
