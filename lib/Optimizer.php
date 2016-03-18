<?php

DEFINE("JPEG", "JPEG");
DEFINE("PNG", "PNG");

/**
 *
 * @author Sven Schiffer (sven@51seven.de)
**/
class Optimizer {

  private $quality = 90;        // 0 (worst quality) to 100 (best quality)
  private $image = "";          // image path
  private $copy = "";           // optimized image path
  private $max_width = false;   // scale down to this width
  private $mimetype;            // Image's mimetype

  public function __construct($image) {
    $this->image = $image;
    $this->getMimetype();

    if(!is_writable($this->image)) {
      throw new Exception("Image is not writeable: ".$this->image);
    }
  }

  private function getQuality() {
    if($this->mimetype == PNG) {
      $quality = ($this->quality - 100) / 11.111111;
      $quality = round(abs($quality));
      
      return $quality;
    }
    else {
      return $this->quality;
    }
  }

  private function getMimetype() {
    switch (exif_imagetype($this->image)) {
      case IMAGETYPE_JPEG:
        $this->mimetype = JPEG;
        break;

      case IMAGETYPE_PNG:
        $this->mimetype = PNG;
        break;

      default:
        throw new Exception("Mimetype not supported.");
        break;
    }

    return $this->mimetype;
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
    imagejpeg($img, $this->copy, $this->getQuality()); // Best quality (100)
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
    imagepng($img, $this->copy, $this->getQuality());    // 0 compression
  }

  // validates the integrity of the operations
  private function validate() {
    $newsize = filesize($this->copy);
    $oldsize = filesize($this->image);

    if($newsize < $oldsize) {
      unlink($this->image);              // Delete the original image
      rename($this->copy, $this->image); // Rename the optimized image to the original one
    }
    else {
      unlink($this->copy);
    }
  }

  public function optimize(array $params) {
    $this->quality   = (isset($params['quality']))   ? $params['quality']   : $this->quality;
    $this->max_width = (isset($params['max_width'])) ? $params['max_width'] : $this->max_width;

    if(!is_int($params['max_width']) || $params['max_width'] < 1) {
      throw new Exception("Max width must be of type Integer and at least 1px wide.");
    }

    if(!is_int($this->quality) || $this->quality < 0 || $this->quality > 100) {
      throw new Exception("Quality must be of type Integer and between 0 and 100.");
    }

    if($this->mimetype == JPEG) {
      $this->optimize_jpeg();
    }
    else if($this->mimetype == PNG) {
      $this->optimize_png();
    }

    $this->validate();
  }
}

?>
