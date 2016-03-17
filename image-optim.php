<?php
require_once('lib/imageoptimizer/src/ImageOptimizer/OptimizerFactory.php');

use ImageOptimizer;


// Only optimize images if it's enabled
if(!c::get('image-optim')) return;

$kirby = kirby();

$kirby->hook('panel.file.upload', function($file) {
  error_log("panel.file.upload triggered \o/");
  optimize($params['file'] = $file);
});

$kirby->hook('panel.file.replace', function($file) {
  error_log("panel.file.replace triggered \o/");
  optimize($params['file'] = $file);
});

function optimize($params) {
  //include __DIR__."/lib/imageoptimizer/src/ImageOptimizer/OptimizerFactory.php";

  // $factory = new \ImageOptimizer\OptimizerFactory();
  $factory = new lib\imageoptimizer\src\ImageOptimizer\OptimizerFactory();
  return;
  $factory = new OptimizerFactory();
  $optimizer = $factory->get();

  error_log(json_encode($params));

  /*$filepath = /* path to image */;

  // $optimizer->optimize($filepath);
}




?>
