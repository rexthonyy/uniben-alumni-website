<?php
	class ImageHandler {
		
		//the folder inwhich to save images
		private $saveDir;
		
		//resizing max dimension
		private $maxDim;

		//set the saveDir on instantiation format '/path/'
		public function __construct($save_dir, $max_dim = array(240, 240)) {
			$this->saveDir = $save_dir;
			$this->maxDim = $max_dim;
		}
		
		/**
		* Resizes/resamples an image uploaded via a web form
		*
		* @param array $upload the array contained in $_FILES
		* @return string the path to the resized uploaded file
		*/
		public function processUploadedImage($file, $rename = TRUE){
			//seperate the uploaded file array
			list($name, $type, $tmp, $err, $size) = array_values($file);
			
			//if an error occured, throw an exception
			if($err != UPLOAD_ERR_OK){
				throw new Exception ('An error occured with the upload!');
				return;
			}
			
			// generate a resized image
			$this->doImageResize($tmp);
			
			// rename the file if the flag is set to true
			if($rename === TRUE) {
				$img_ext = $this->getImageExtension($type);
				$name = $this->renameFile($img_ext);
			}
			
			//check that the directory exists
			$this->checkSaveDir();
			
			//create the local save path for the image
			$filepath = $this->saveDir . $name;
			
			//create the absolute path to save the image
			$absolute = $_SERVER['DOCUMENT_ROOT'] . $filepath;
			
			//save the image
			if(!move_uploaded_file($tmp, $filepath)){
				throw new Exception("Couldn't save the uploaded file!");
			}
			
			return $filepath;
		}
		
		/**
		* Ensures that the save directory exists
		*
		* Checks for the existence of the supplied save directory,
		* and creates the directory if it doesn't exist. Creation is
		* recursive.
		*
		* @param void
		* @return void
		*/
		private function checkSaveDir() {
			// determines the path to check
			//$path = $_SERVER['DOCUMENT_ROOT'] . $this->saveDir;
			$path = $this->saveDir;
			
			// checks if the directory exists
			if(!is_dir($path)) {
				// create the directory
				if(!mkdir($path, 0777, TRUE)) {
					throw new Exception("Can't create the directory!");
				}
			}
		}
		
		/**
		* Generates a unique name for a file
		*
		* Uses the current timestamp and a randomly generated number
		* to create a unique name to be used for an uploaded file.
		* This helps prevent a new file upload from overwriting an
		* existing file with the same name.
		*
		* @param string $ext the file extension for the upload
		* @return string the new filename
		*/
		private function renameFile($ext) {
			/*
			* Returns the current timestamp and a random number
			* to avoid duplicate filenames
			*/
			return time().'_'.mt_rand(1000, 9999).$ext;
		}
		
		/**
		* Determines the filetype and extension of an image
		*
		* @param string $type the MIME type of the image
		* @return string the extension to be used with the file
		*/
		private function getImageExtension($type) {
			switch($type){
				case 'image/gif':
					return '.gif';
				
				case 'image/jpeg':
				case 'image/pjpeg':
					return '.jpg';
					
				case 'image/png':
					return '.png';
					
				default:
					throw new Exception('File type not recognized!');
			}
		}
		
		/**
		* Determines new dimensions for an image
		*
		* @param string $img the path to the upload
		* @return array the new and original image dimensions
		*/
		private function getNewDims($img) {
			//Assemble the necessary variables for processing
			list($src_w, $src_h) = getImageSize($img);
			list($max_w, $max_h) = $this->maxDim;
			
			//Check that the image is bigger than the maximum dimension
			if($src_w > $max_w || $src_h > $max_h) {
				//Determine the scale to which the image will be resized
				$s = min($max_w/$src_w, $max_h/$src_h);
			} else {
				/*
				* If the image is smaller than the max dimensions, keep
				* its dimensions by multiplying by 1
				*/
				$s = 1;
			}
			
			//Get the new dimensions
			$new_h = round($src_h * $s);
			$new_w = round($src_w * $s);
			
			//Return the new dimensions
			return array($new_w, $new_h, $src_w, $src_h);
		}
		
		/**
		* Determines how to process images
		*
		* Uses the MIME type of the provided image to determine
		* what image handling functions should be used. This
		* increases the perfomance of the script versus using
		* imagecreatefromstring().
		*
		* @param string $img the path to the upload
		* @return array the image type-specific functions
		*/
		private function getImageFunction($img) {
			$info = getImageSize($img);
			
			switch($info['mime']){
				case 'image/jpeg':
				case 'image/pjpeg':
					return array('imagecreatefromjpeg', 'imagejpeg');
				case 'image/gif':
					return array('imagecreatefromgif', 'imagegif');
				case 'image/png':
					return array('imagecreatefrompng', 'imagepng');
				default:
					return false;
			}
		}
		
		/**
		* Generates a resampled and resized image
		*
		* Creates and saves a new image based on the new dimensions
		* and image type-specific functions determined by other
		* class methods.
		*
		* @param array $img the path to the upload
		* @return void
		*/
		private function doImageResize($img){
			// Determine the dimensions
			$d = $this->getNewDims($img);
			
			// Determine what function to use
			$funcs = $this->getImageFunction($img);
			
			// Create the image resource for sampling
			$src_img = $funcs[0]($img);
			$new_img = imagecreatetruecolor($d[0], $d[1]);
			
			if(imagecopyresampled(
				$new_img, $src_img, 0, 0, 0, 0, $d[0], $d[1], $d[2], $d[3]
			)) {
				imagedestroy($src_img);
				
				if($new_img && $funcs[1]($new_img, $img)){
					imagedestroy($new_img);
				}else{
					throw new Exception('Failed to save the new image!');
				}
			}else{
				throw new Exception('Could not resample the image!');
			}
		}
	}
	
	function deleteImage ($img) {
		$absolute = $_SERVER['DOCUMENT_ROOT'] . $img;
					
		unlink($absolute);
	}
?>