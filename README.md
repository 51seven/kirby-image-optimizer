# Kirby Image Otimizer Plugin
Kirby CMS Plugin for automatic image compression and scaling.

# Installation

Copy or clone this repository into your `/site/plugins` directory.

Edit your `config.php`:
```php
c::set('imageoptim', true);           // {true} activate the plugin
c::set('imageoptim.quality', 90);     // {0-100} quality compression in percentage. 0 = lowest quality, 100 = highest quality
c::set('imageoptim.max_width', 1920); // downscale the image to a given width
```

# Copyright

Copyright 2016 - 51seven, Gesellschaft für Markenkommunikation mbH.
