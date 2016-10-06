<?php
/**
 * 检查管理员是否登陆
 * @wxy
 * @DateTime 2016-05-20T15:21:23+0800
 * @return   [type]                   [description]
 */
function check_admin_login() {
	/*$uid = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 0;
	if ($uid > 0) {

	} else {
		redirect(U('Pangu/Login/index'));exit;
	}*/
}

function is_img($str) {
	if ($str != "") {
		echo $str;
	} else {

		echo "http://" . $_SERVER['SERVER_NAME'] . "/Uploads/source/none.jpg";
		//echo "/Uploads/images/none.jpg";
	}
}