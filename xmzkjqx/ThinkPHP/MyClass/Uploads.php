<?php
/**
 *
 */
class Uploads {

	function __construct() {
		# code...
	}
	function upload_imgs() {

		//$targetFolder ='/uploads/avatar/'.date("Ymd"); // Relative to the root
		$targetFolder = __ROOT__ . '/Uploads/images/'; // Relative to the root

		if (!file_exists($targetFolder)) {

			@mkdir($targetFolder);
		}

		//$verifyToken = "md5('unique_salt' . $_POST['timestamp'])";

		if (!empty($_FILES)) {
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;

			$str = strrchr($_FILES['Filedata']['name'], ".");
			$str = date('YmdHis') . uniqid() . $str; //生成唯一图片名

			$targetFile = rtrim($targetPath, '/') . '/' . $str;

			// Validate the file type
			$fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'mp4', 'xls', 'exe', 'txt', 'doc', 'docx', 'mp3'); // File extensions
			$fileParts = pathinfo($_FILES['Filedata']['name']);

			if (in_array($fileParts['extension'], $fileTypes)) {
				move_uploaded_file($tempFile, $targetFile);
				//echo $str;
				$str = $targetFolder . $str;
				return $str;
			} else {
				return -1;
			}
		}
	}

	function upload_excel() {

		//$targetFolder ='/uploads/avatar/'.date("Ymd"); // Relative to the root
		$targetFolder = __ROOT__ . '/Uploads/excel/'; // Relative to the root

		if (!file_exists($targetFolder)) {

			@mkdir($targetFolder);
		}

		//$verifyToken = "md5('unique_salt' . $_POST['timestamp'])";

		if (!empty($_FILES)) {
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;

			$str = strrchr($_FILES['Filedata']['name'], ".");
			$str = date('YmdHis') . uniqid() . $str; //生成唯一图片名

			$targetFile = rtrim($targetPath, '/') . '/' . $str;

			// Validate the file type
			$fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'mp4', 'xls', 'exe', 'txt', 'doc', 'docx', 'mp3','xls'); // File extensions
			$fileParts = pathinfo($_FILES['Filedata']['name']);

			if (in_array($fileParts['extension'], $fileTypes)) {
				move_uploaded_file($tempFile, $targetFile);
				//echo $str;
				$str = $targetFolder . $str;
				return $str;
			} else {
				return -1;
			}
		}
	}
}
