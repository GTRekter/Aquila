<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package     CodeIgniter
 * @author  	Ivan Porta
 * @copyright 	Copyright (c) 2014.
 * @license  	GLP
 * @since  		Version 1.0
 * @version  	1.0
 */

// ------------------------------------------------------------------------

abstract class CI_UT_images {

	public function resizeImg($maxPhotoWidth,$maxPhotoHeight,$sourceFile,$newImage,$resizeType) {
	
		$this->load->library('image_lib');
		
		switch ($resizeType) {
			case 'banner':
				// RESIZING
				if ( $sourceFile['image_width'] != $maxPhotoWidth || $sourceFile['image_height'] != $maxPhotoHeight ) {
				
					if (! file_exists('./resources/img/tmp')) { 
					    mkdir('./resources/img/tmp', 0777, true);
					}
					$manipulation_config = array(
						'image_library' => 'gd2',
						'source_image' => $sourceFile['file_path'].'/'.$sourceFile['file_name'],
						'new_image' => $newImage,
						'maintain_ratio' => TRUE,
						'width' => $maxPhotoWidth,
					);
					$this->image_lib->clear();
					$this->image_lib->initialize($manipulation_config);
					$this->image_lib->resize();
					
					$new_img = getimagesize( $newImage );
					if ( $new_img[1] > $maxPhotoHeight ) {
						$manipulation_config = array(
							'image_library' => 'gd2',
							'source_image' => $newImage,
							'maintain_ratio' => 'FALSE',
							'height' => $maxPhotoHeight,
						);
						$this->image_lib->clear();
						$this->image_lib->initialize($manipulation_config);
						$this->image_lib->crop();
					} else {
						$manipulation_config = array(
							'image_library' => 'gd2',
							'source_image' => $newImage,
							'new_image' => $newImage,
							'maintain_ratio' => TRUE,
							'height' => $maxPhotoHeight,
						);
						$this->image_lib->clear();
						$this->image_lib->initialize($manipulation_config);
						$this->image_lib->resize();
						
						
						$blankImage = getimagesize( $newImage );
						$x = ($blankImage[0] - $maxPhotoWidth)/2;
						
						$manipulation_config = array(
							'image_library' => 'gd2',
							'source_image' => $newImage,
							'maintain_ratio' => false,
							'width' => $blankImage[0] - ($x*2),
							'x_axis' => $x
						);
						$this->image_lib->clear();
						$this->image_lib->initialize($manipulation_config);
						$this->image_lib->crop();
					}
				} else {
					copy($sourceFile['file_path'].'/'.$sourceFile['file_name'], $newImage);
				}
				
				$files = glob('./resources/img/tmp/*'); // get all file names
				foreach($files as $file){ // iterate files
					if (is_file($file)) {
				    	unlink($file); // delete file
				    }
				}
				rmdir('./resources/img/tmp');
				// END RESIZING
				break;
			case 'product':
				// RESIZING
				if (! file_exists('./resources/img/tmp')) { 
				    mkdir('./resources/img/tmp', 0777, true);
				}
				if ( $sourceFile['image_width'] != $maxPhotoWidth || $sourceFile['image_height'] != $maxPhotoHeight ) {
					$manipulation_config = array(
						'image_library' => 'gd2',
						'source_image' => $sourceFile['file_path'].'/'.$sourceFile['file_name'],
						'new_image' => $newImage,
						'maintain_ratio' => true,
						// 280
						'width' => $maxPhotoWidth,
					);
					$this->image_lib->clear();
					$this->image_lib->initialize($manipulation_config);
					$this->image_lib->resize();
					
					$new_img = getimagesize( $newImage );
					// 184 > 357
					if ( $new_img[1] > $maxPhotoHeight ) {
						$manipulation_config = array(
							'image_library' => 'gd2',
							'source_image' => $newImage,
							'maintain_ratio' => false,
							'height' => $maxPhotoHeight,
						);
						$this->image_lib->clear();
						$this->image_lib->initialize($manipulation_config);
						$this->image_lib->crop();
					} else {
						$blankImage = getimagesize( $this->config->item('resources_url').'/img/blank.jpg');
						
						// 1280 > 1920
						if ($blankImage[1] > $blankImage[0]) {
							$manipulation_config = array(
								'image_library' => 'gd2',
								'source_image' => './resources/img/blank.jpg',
								'new_image' => './resources/img/tmp/blank.jpg',
								'maintain_ratio' => true,
								'width' => $maxPhotoWidth,
							);
							$this->image_lib->clear();
							$this->image_lib->initialize($manipulation_config);
							$this->image_lib->resize();
							
							$manipulation_config = array(
								'image_library' => 'gd2',
								'source_image' => './resources/img/tmp/blank.jpg',
								'maintain_ratio' => true,
								'y_axis' => $maxPhotoHeight,
							);
							$this->image_lib->clear();
							$this->image_lib->initialize($manipulation_config);
							$this->image_lib->crop();
						} else {
							$manipulation_config = array(
								'image_library' => 'gd2',
								'source_image' => './resources/img/blank.jpg',
								'new_image' => './resources/img/tmp/blank.jpg',
								'maintain_ratio' => true,
								'height' => $maxPhotoHeight,
							);

							$this->image_lib->clear();
							$this->image_lib->initialize($manipulation_config);
							$this->image_lib->resize();
							
							$blankImage = getimagesize( $this->config->item('resources_url').'/img/tmp/blank.jpg');
							$x = ($blankImage[0] - $maxPhotoWidth)/2;

							$manipulation_config = array(
								'image_library' => 'gd2',
								'source_image' => './resources/img/tmp/blank.jpg',
								'new_image' => './resources/img/tmp/blank.jpg',
								'maintain_ratio' => false,
								'width' => $blankImage[0] - ($x*2),
								'x_axis' => $x
							);
							$this->image_lib->clear();
							$this->image_lib->initialize($manipulation_config);
							$this->image_lib->crop();
						}
					
						$manipulation_config = array(
							'image_library' => 'gd2',
							'source_image' => './resources/img/tmp/blank.jpg', 
							'wm_overlay_path' => $newImage, //the overlay image,
							'new_image' => $newImage,
							'wm_type' => 'overlay',
							'wm_opacity' => 100,
							'wm_vrt_alignment' => 'middle',
							'wm_hor_alignment' => 'center',
						);
						$this->image_lib->clear();
						$this->image_lib->initialize($manipulation_config);
						$this->image_lib->watermark();
					}
				} else {
					copy($sourceFile['file_path'].'/'.$sourceFile['file_name'], $newImage);
				}
				
				$files = glob('./resources/img/tmp/*');
				foreach($files as $file){ 
					if (is_file($file)) {
				    	unlink($file); 
				    }
				}
				rmdir('./resources/img/tmp');
				// END RESIZING
				break;
		}
	}
}