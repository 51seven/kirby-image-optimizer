<?php
// Only optimize images if it's enabled
if(!c::get('zeroimgmeta')) return;

// Load Optimizer Class
require_once "lib/Optimizer.php";

$kirby = kirby();

// A new file has been uploaded
$kirby->hook('panel.file.upload', function($file) {
  optimize_init($file);
});

// A file has been replaced
$kirby->hook('panel.file.replace', function($file) {
  optimize_init($file);
});

function optimize_init($file) {
  $max_width = c::get('zeroimgmeta.max_width');

  $optimizer = new Optimizer($file);
  $optimizer->optimize(array(
    'max_width' => $max_width
  ));
}

?>
