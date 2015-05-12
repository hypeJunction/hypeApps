<?php

namespace hypeJunction\Files;

class Image {

	const MASTER = 550;

	/**
	 * 
	 * @var \WideImage\Image|\WideImage\PaletteImage|\WideImage\TrueColorImage 
	 */
	private $source;

	/**
	 * Constructor
	 *
	 * @param string $path Path to source image
	 */
	public function __construct($path) {
		$this->source = \WideImage\WideImage::load($path);
	}

	/**
	 * Resizes the image to dimensions found in props and crops if coords are provided
	 *
	 * @param array $props  Properties, including 'w', 'h', 'croppable'
	 * @param array $coords Coordinates, include 'master_width', 'master_height', 'x1', 'y1' etc
	 * @return Image
	 */
	public function resize($props = array(), $coords = null) {

		$croppable = elgg_extract('croppable', $props, false);

		$width = elgg_extract('w', $props);
		$height = elgg_extract('h', $props);

		if (is_array($coords) && $croppable) {
			$master_width = elgg_extract('master_width', $coords, self::MASTER);
			$master_height = elgg_extract('master_height', $coords, self::MASTER);

			$x1 = elgg_extract('x1', $coords, 0);
			$y1 = elgg_extract('y1', $coords, 0);
			$x2 = elgg_extract('x2', $coords, self::MASTER);
			$y2 = elgg_extract('y2', $coords, self::MASTER);

			// scale to master size and then crop
			$this->source = $this->source->resize($master_width, $master_height, 'inside', 'down')->crop($x1, $y1, $x2 - $x1, $y2 - $y1);
		}

		if ($croppable) {
			// crop to icon width and height
			$this->source = $this->source->resize($width, $height, 'outside', 'any')->crop('center', 'center', $width, $height);
		} else if (!is_array($coords)) {
			$this->source = $this->source->resize($width, $height, 'inside', 'down');
		}

		return $this;
	}

	/**
	 * Saves resized image to a file
	 *
	 * @param string  $path    File location
	 * @param quality $quality Quality options
	 * @return Image
	 */
	public function save($path, $quality = array()) {
		$ext = pathinfo($path, PATHINFO_EXTENSION);

		$jpeg_quality = elgg_extract('jpeg_quality', $quality);
		$png_quality = elgg_extract('png_quality', $quality);
		$png_filter = elgg_extract('png_filter', $quality);
		
		switch ($ext) {
			default :
				$this->source->saveToFile($path, $jpeg_quality);
				break;

			case 'gif';
				$this->source->saveToFile($path);
				break;

			case 'png' :
				$this->source->saveToFile($path, $png_quality, $png_filter);
				break;
		}
		
		return $this;
	}

}
