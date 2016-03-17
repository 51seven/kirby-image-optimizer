<?php
// Only optimize images if it's enabled
if(!c::get('imageoptim')) return;

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
  try {
    $optimizer = new Optimizer($file);
    $optimizer->optimize(array(
      'max_width' => c::get('imageoptim.max_width'),
      'quality'   => c::get('imageoptim.quality')
    ));
  }
  catch(Exception $e) {
    error_log("Kirby - ImageOptim: ". $e->getMessage());
  }
}

?>
