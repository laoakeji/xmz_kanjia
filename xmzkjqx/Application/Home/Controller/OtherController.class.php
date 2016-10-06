<?php
namespace Home\Controller;
use Think\Controller;

class OtherController extends Controller {
	public function upload() {
		require_once THINK_PATH . "MyClass/Uploads.php";
		$upload = new \Uploads;
		$data = $upload->upload_imgs();
		echo $data;
	}

}