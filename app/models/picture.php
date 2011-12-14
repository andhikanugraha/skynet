<?php

// steps
// 1. upload original through ->upload() then ->save()
// 2. resize using jQuery on a dedicated page
// 3. crop using ->crop()
// 4. generate thumbnail using ->generate_thumbnail()
// 5. ->save()
// 6. associate with user

// all files are saved under the path defined in conf picture_upload_path

class Picture extends HeliumRecord {
	
	public $upload_path = '';
	public $public_path = '';
	public $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
	public $width_ratio = 3;
	public $height_ratio = 4;

	public $original_filename;
	public $cropped_filename;
	public $thumbnail_filename;
	public $format;
	public $cropped_width;
	public $cropped_height;
	public $processed = false;
	public $applicant_id = 0;

	public $upload_error = '';

	public function init() {
		$this->upload_path = Helium::conf('picture_upload_path');
		$this->public_path = Helium::conf('picture_public_path');
		// $this->belongs_to('applicant');
	}

	public function generate_filename() {
		return sha1(microtime()) . '_' . mt_rand();
	}

	public function upload_original($file_array) { // $file_array is $_FILE['userfile']
		extract($file_array);

		// check upload validity
		if (!is_uploaded_file($tmp_name)) {
			$this->upload_error = 'not_uploaded_file';
			return false;
		}

		// check format validity
		// let's just check extensions here
		$ext = strrchr($name, '.');
		$ext = substr($ext, 1);
		$ext = strtolower($ext);
		if (!in_array($ext, $this->allowed_types)) {
			$this->upload_error = 'invalid_format';
			return false;
		}

		$filename = $this->generate_filename() . '.' . $ext;

		// move along
		$destination = $this->upload_path . '/' . $filename;
		$move = move_uploaded_file($tmp_name, $destination);
		if (!$move) {
			$this->upload_error = 'failed_move';
			return false;
		}

		// success! store our work in the db.

		$this->original_filename = $filename;
		$this->format = $ext;

		$this->save();

		return true;
	}

	public function crop($params = array()) {

		// adjust memory limits
		ini_set('memory_limit', '128M');
		
		// // debug
		// extract($params);
		// $original_full_path = $this->upload_path . '/' . $this->original_filename;
		// 
		// $source = imagecreatefromjpeg($original_full_path);
		// var_dump($source);
		// imagedestroy($source);
		// exit;

		$ideal = $this->get_default_crop_dimensions();

		$original_full_path = $this->upload_path . '/' . $this->original_filename;
		list($mw, $mh, $type) = getimagesize($original_full_path);

		$checks = array();
		$checks[] = $params['x'] <= $mw;
		$checks[] = $params['y'] <= $mh;
		$checks[] = $params['width'] >= 60;
		$checks[] = $params['height'] >= 80;
		$checks[] = $params['width'] <= $mw;
		$checks[] = $params['height'] <= $mh;
		
		foreach ($checks as $check) {
			if (!$check) {
				$params = $ideal;
				break;
			}
		}

		extract($params);
		
		$max_width = 900;
		$max_height = 1200;
		$end_width = $width;
		$end_height = $height;
		if ($width > $max_width || $height > $max_height) {
			$end_width = $max_width;
			$end_height = $max_height;
		}

		// begin crop
		switch ($type) {
			case IMAGETYPE_JPEG:
				$source = imagecreatefromjpeg($original_full_path);
				break;
			case IMAGETYPE_PNG:
				$source = imagecreatefrompng($original_full_path);
				break;
			case IMAGETYPE_GIF:
				$source = imagecreatefromgif($original_full_path);
				break;
			default:
				return false;
		}
		$canvas = imagecreatetruecolor($end_width, $end_height);

		// paint to canvas
		$paint = @imagecopyresampled($canvas, $source, 0, 0, $x, $y, $end_width, $end_height, $width, $height);
		if (!$paint)
			return false;

		$raw_filename = strstr($this->original_filename, '.', true);
		$cropped_filename = $raw_filename . '_cropped.jpg';
		$this->cropped_filename = $cropped_filename;

		$save = @imagejpeg($canvas, $this->upload_path . '/' . $cropped_filename);
		if (!$save)
			return false;

		$this->cropped_width = $width;
		$this->cropped_height = $height;
		$this->save();
		
		// delete the original image. bye!
		@unlink($this->upload_path . '/' . $this->original_filename);
		return true;
	}
	
	public function generate_thumbnail() {
		if (!$this->cropped_filename)
			return false;

		$source = @imagecreatefromjpeg($this->upload_path . '/' . $this->cropped_filename);
		$canvas = @imagecreatetruecolor(150, 200); // thumbnail is 150x200
		
		// this is enough
		$resize = @imagecopyresampled($canvas, $source, 0, 0, 0, 0, 150, 200, $this->cropped_width, $this->cropped_height);
		if (!$resize)
			return false;

		$raw_filename = strstr($this->original_filename, '.', true);
		$thumbnail_filename = $raw_filename . '_thumb.jpg';
		$this->thumbnail_filename = $thumbnail_filename;

		$save = @imagejpeg($canvas, $this->upload_path . '/' . $thumbnail_filename);
		if (!$save)
			return false;

		$this->save();

		return true;
	}

	public function process($params) {
		return $this->crop($params) && $this->generate_thumbnail();
	}

	public function get_default_crop_dimensions() {
		$original_full_path = $this->upload_path . '/' . $this->original_filename;
		list($width, $height, $type, $attr) = getimagesize($original_full_path);

		// get default crop dimensions
		$ideal = compact('width', 'height');
		$ideal['x'] = $ideal['y'] = 0;
		$ratio = $width / $height;
		if ($ratio < 0.75) {
			// image is taller than 3:4.
			$h = $ideal['height'] = floor((4/3) * $width);
			$ideal['y'] = floor(($height - $h) / 2);
		}
		elseif ($ratio > 0.75) {
			// image is wider than 3:4
			$w = $ideal['width'] = ceil((3/4) * $height);
			$ideal['x'] = floor(($width - $w) / 2);
		}
		
		return $ideal;
	}

	public function get_original_url() {
		return $this->public_path . '/' . $this->original_filename;
	}

	public function get_cropped_url() {
		return $this->public_path . '/' . $this->cropped_filename;
	}
	
	public function get_cropped_path() {
		return $this->upload_path . '/' . $this->cropped_filename;
	}

	public function get_thumbnail_url() {
		return $this->public_path . '/' . $this->thumbnail_filename;
	}
	
	public function __wakeup() {
		parent::__wakeup();
		$this->init();
	}
}