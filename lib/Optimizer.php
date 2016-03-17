<?php

/**
 *
 * @author Sven Schiffer (sven@51seven.de)
**/
class Optimizer {

  private $quality = 90;        // 0 (worst quality) to 100 (best quality)
  private $image = "";          // image path
  private $copy = "";           // optimized image path
  private $max_width = false;   // scale down to this width

  public function __construct($image, array $options = array()) {
    $this->quality = (int) (isset($options['quality'])) ? $options['quality'] : $this->quality;
    $this->image = $image;

    if(!is_int($this->quality)) {
      throw new Exception("Quality must be of type Integer.");
    }

    if($this->quality < 0 || $this->quality > 100) {
      throw new Exception("Quality must be between 0 and 100.");
    }

    // $this->max_width = ((isset($params['max_width']) && is_int($params['max_width']) && $params['max_width'] > 1) ? $params['max_width'] : false);

    if(!is_writable($this->image)) {
      throw new Exception("Image is not writeable: ".$this->image);
    }
  }

  private function getPngQuality() {
    
  }

  // Optimizes a jpeg image and downscales it if necessary
  private function optimize_jpeg() {
    list($width, $height, $type, $attr) = getimagesize($this->image);

    $img = imageCreateFromJpeg($this->image);

    // check if we need to downscale the image
    if($this->max_width && $width > $this->max_width) {
      $img = imagescale($img, $this->max_width);
    }

    $this->copy = $this->image.".jpg"; // append this affix to avoid access conditions
    imagejpeg($img, $this->copy, 90); // Best quality (100)
  }

  // Optimizes a png image and downscales it if necessary
  private function optimize_png() {
    list($width, $height, $type, $attr) = getimagesize($this->image);

    $img = imageCreateFromPng($this->image);

    // check if we need to downscale the image
    if($this->max_width && $width > $this->max_width) {
      $img = imagescale($img, $this->max_width);
    }

    $this->copy = $this->image.".png"; // append this affix to avoid access conditions
    imagepng($img, $this->copy, $this->getPngQuality());    // 0 compression
  }

  // validates the integrity of the operations
  private function validate() {
    $newsize = filesize($this->copy);
    $oldsize = filesize($this->image);

    if($newsize < $oldsize) {
      unlink($this->image);              // Delete the original image
      rename($this->copy, $this->image); // Rename the optimized image to the original one

      error_log("optimized image and saved ".round((($oldsize-$newsize) / 1024))." kb");
    }
    else {
      unlink($this->copy);

      error_log("new image is larger then old one.");
    }
  }

  public function optimize($params) {

    switch (exif_imagetype($this->image)) {
      case IMAGETYPE_JPEG: // IMAGETYPE_JPEG
        $this->optimize_jpeg();
        break;

      case IMAGETYPE_PNG: // IMAGETYPE_PNG
        $this->optimize_png();
        break;

      default:
        throw new Exception("Mimetype not supported.");
        break;
    }

    $this->validate();
  }
}

?>
