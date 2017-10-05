
<?php
/*
* CKFinder
* ========
* http://cksource.com/ckfinder
* Copyright (C) 2007-2014, CKSource - Frederico Knabben. All rights reserved.
*
* The software, this file and its contents are subject to the CKFinder
* License. Please read the license.txt file before using, installing, copying,
* modifying or distribute this file or part of its contents. The contents of
* this file is part of the Source Code of CKFinder.
*
* CKFinder extension: resize image according to a given size
*/
//if (!defined('IN_CKFINDER')) exit;

//http://stackoverflow.com/questions/6986412/resize-image-to-fit-perfectly-a-determined-box

class DCMSScaleCrop
{
		function ScaleImageToBox($src, $dst, $width = null, $height = null, $crop=0, $quality=60)
		{

		  if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

		  $type = strtolower(substr(strrchr($src,"."),1));

		  if($type == 'jpeg') $type = 'jpg';
		  switch($type){
				case 'bmp': $img = imagecreatefromwbmp($src); break;
				case 'gif': $img = imagecreatefromgif($src); break;
				case 'jpg': $img = imagecreatefromjpeg($src); break;
				case 'png': $img = imagecreatefrompng($src); break;
				default : return "Unsupported picture type!";
		  }

		  // resize
		  if($crop && !is_null($width) && !is_null($height)){
				if($w < $width or $h < $height) return "Picture is too small! (for ".$width." x ".$height.")";
				$ratio = max($width/$w, $height/$h);
				$y = ($h - $height / $ratio) / 2;
				$h = $height / $ratio;
				$x = ($w - $width / $ratio) / 2;
				$w = $width / $ratio;
		  /*}elseif($crop && (is_null($width) || is_null($height)) ){
				$ratio = max($width/$w, $height/$h);
				if(!is_null($width)){
					//set the width
					//crop the side pixels of the image
					$y = 0;
					$h = $h;
					$x = ($w - $width / $ratio) / 2;
					$w = $width / $ratio;
				}elseif(!is_null($height)){
					//set the height
					//crop some header, footer pixels of the image
					$y = ($h - $height / $ratio) / 2;
					$h = $height / $ratio;
					$x = 0;
					$w = $w;
				}
				*/
			}else{
				if($w < $width and $h < $height) return "Picture is too small!";
				$ratio = min($width/$w, $height/$h);
				$width = $w * $ratio;
				$height = $h * $ratio;
				$y = 0;
				$x = 0;
		  }

		  $new = imagecreatetruecolor($width, $height);

		  // preserve transparency
		  if($type == "gif" or $type == "png"){
				imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
				imagealphablending($new, false);
				imagesavealpha($new, true);
		  }

			//bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
		  imagecopyresampled($new, $img, 0, 0, $x, $y, $width, $height, $w, $h);

		  switch($type){
				case 'bmp': imagewbmp($new, $dst); break;
				case 'gif': imagegif($new, $dst); break;
				case 'jpg': imagejpeg($new, $dst, $quality); break;
				case 'png': imagepng($new, $dst); break;
		  }

			imagedestroy($new);

		  return true;
		}

	/**
     * @access private
     */
    function getConfig()
    {
        $config = array();
        if (isset($GLOBALS['config']['DCMSScaleCrop'])) {
            $config = $GLOBALS['config']['DCMSScaleCrop'];
        }
        return $config;
    }


	function onAfterFileUpload($currentFolder, $uploadedFile, $sFilePath)
	{
			$config = $this->getConfig();
			foreach($config['Scale'] as $size => $details)
			{
				if (strpos(strrev($sFilePath),"/")>0)
				{
					$reverseFilePath = strrev($sFilePath);

					//get the filename + extension
					$uploadFilename = strrev(substr($reverseFilePath,0,strpos($reverseFilePath,"/")));

					//get the foldername path
					$folderpath = str_replace('//','/',str_replace($uploadFilename,"",$sFilePath));
					//get the foldername path
					$folderpath = str_replace('\\','/',$folderpath);

					$executeScale = false;

					//if the folderpath is an exact match against the alloweddirectories;
					if ( $details["alloweddirectoriesexactmatch"] == true && in_array(strtolower($folderpath),$details["alloweddirectories"])) $executeScale = true;

					if ( $details["alloweddirectoriesexactmatch"] == false )
					{
						foreach($details["alloweddirectories"] as $therootfolder)
						{
					//		echo "therootfolder = ".$therootfolder . " *** folderpath = ".$folderpath;
							if(stripos(  $folderpath,$therootfolder )!== false)
							{
				//				echo "\r ==> ".$folderpath . ' gevonden '. $therootfolder;
								$executeScale = true;
							}
						}
					}

					//$p = print_r($details["alloweddirectories"],false);
					//echo $p;
					//echo "\r\n";
					//echo $folderpath;
					//echo "\r\n";
					//echo '-'.$executeScale.'-'."\r\n";

					if( isset($details["folderpath"]) && strlen(trim($details["folderpath"]))>0)
					{
						$folderpath = trim($details["folderpath"]);
					}

					$folder = $folderpath.substr($details["subfolder"],1);

					if ($executeScale == true && !file_exists($folderpath.substr($details["subfolder"],1)))
					{
						mkdir($folderpath.substr($details["subfolder"],1),'0755');
					}

				}
		//		$this->ScaleImageToBox($sFilePath, $details["dir"].$uploadFilename, $details["width"], $details["height"], 1);
				if ($executeScale == true)
				{
	//				echo "is executable";
					if(strlen($details['filename_postfix'])>0)  $uploadFilename = substr($uploadFilename,0,strpos($uploadFilename,'.')).$details['filename_postfix'].substr($uploadFilename,strpos($uploadFilename,'.'));


					$result = $this->ScaleImageToBox($sFilePath, $folder.$uploadFilename, $details["width"], $details["height"], 1);
					if($result !== true) echo $result."\r\n";
					else echo "\r created: ".$folder.$uploadFilename.'  dimension: ' . $details["width"] .' x '. $details["height"];
				}
			}
			echo "\r\nDCMSPlugin executed\r\n";
	}
}

	$DCMSScaleCrop = new DCMSScaleCrop();

	$config['Hooks']['AfterFileUpload'][] = array($DCMSScaleCrop, "onAfterFileUpload");
	/*
	$config['Hooks']['BeforeExecuteCommand'][] = array($CommandHandler_ImageResizeInfo, "onBeforeExecuteCommand");
	$config['Hooks']['InitCommand'][] = array($CommandHandler_ImageResize, "onInitCommand");
	*/


?>
