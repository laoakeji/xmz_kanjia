<?php
namespace Home\Controller;
use Think\Controller;

class WeixinController extends Controller {
	public $wx;
	public function __construct() {
		parent::__construct();

	}

	public function check() {

		define("TOKEN", "wxy");
		if (!isset($_GET['echostr'])) {
			$this->responseMsg();
		} else {
			$this->valid();
		}
	}

	public function get_sc() {
		require_once THINK_PATH . "Weixin/WxApi.class.php";
		$this->wx = new \WxApi;

		$data['type'] = "news";
		$data['offset'] = 0;
		$data['count'] = 20;
		$data = json_encode($data);
		$res = $this->wx->get_sc($data);
		p($res);exit;
	}
	public function c5() {
		//$openid = $object->FromUserName;
		$openid = "o03uawHwk8lBtHuUEcdFAPLclNXE";
		require_once THINK_PATH . "Weixin/WxApi.class.php";
		$this->wx = new \WxApi;
		$json2['touser'] = $openid;
		$json2['msgtype'] = "mpnews";
		$json2['mpnews']['media_id'] = "6XRQqvKuBbbvPTM_bkv8q4vLeJosC6GijdA6GKxyhss";
		$json2 = json_encode($json2);
		$ss = $this->wx->wxServiceSend($json2);
	}

	public function jiqiren($info = "") {
		$info = urlencode($info);
		$ch = curl_init();
		$url = 'http://apis.baidu.com/turing/turing/turing?key=879a6cb3afb84dbf4fc84a1df2ab7319&info=' . $info . '&userid=eb2edb736';
		$header = array(
			'apikey: f596f79788f7cf76bcf9f25cd9b9d872',
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 执行HTTP请求
		curl_setopt($ch, CURLOPT_URL, $url);
		$res = curl_exec($ch);

		//var_dump(json_decode($res));
		return json_decode($res)->text;
	}

	public function valid() {
		$echoStr = $_GET["echostr"];

		//valid signature , option
		if ($this->checkSignature()) {
			echo $echoStr;
			exit;
		}
	}

	private function checkSignature() {
		// you must define TOKEN by yourself
		if (!defined("TOKEN")) {
			throw new Exception('TOKEN is not defined!');
		}

		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];

		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		// use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);

		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}

	//响应消息
	public function responseMsg() {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (!empty($postStr)) {
			$this->logger("R \r\n" . $postStr);
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$RX_TYPE = trim($postObj->MsgType);

			if (($postObj->MsgType == "event") && ($postObj->Event == "subscribe" || $postObj->Event == "unsubscribe")) {
				//过滤关注和取消关注事件
			} else {

			}

			//消息类型分离
			switch ($RX_TYPE) {
			case "event":
				$result = $this->receiveEvent($postObj);
				break;
			case "text":

				$result = $this->receiveText($postObj);

				break;
			case "image":
				$result = $this->receiveImage($postObj);
				break;
			case "location":
				$result = $this->receiveLocation($postObj);
				break;
			case "voice":
				$result = $this->receiveVoice($postObj);
				break;
			case "video":
				$result = $this->receiveVideo($postObj);
				break;
			case "link":
				$result = $this->receiveLink($postObj);
				break;
			default:
				$result = "unknown msg type: " . $RX_TYPE;
				break;
			}
			$this->logger("T \r\n" . $result);
			echo $result;
		} else {
			echo "";
			exit;
		}
	}

	//接收事件消息
	private function receiveEvent($object) {
		$content = "";
		switch ($object->Event) {
		case "subscribe":

			//发送图文消息

			$content = "欢迎关注创业天使纸品!";
			$uid = 0;
			if (!empty($object->EventKey)) {

				$uid = str_replace("qrscene_", "", $object->EventKey);

				// $nickname = D("wx_user")->where("uid=$uid")->getField("nickname");
				// $content .= "您的推荐人是" . $nickname;

			}
			if ($uid > 0) {
				# code...
			} else {
				$uid = 0;
			}
			$res = $this->check_user($object, $uid);
			if ($res == "") {
				$content .= "\n您没有推荐人";
			} else {
				$content .= "\n您的第一推荐人是：" . $res;
			}

			//$openid = $object->FromUserName;
			$result = $this->transmitText($object, $content);
			require_once THINK_PATH . "Weixin/WxApi.class.php";
			$this->wx = new \WxApi;
			//$openid = "o03uawHwk8lBtHuUEcdFAPLclNXE";
			$openid = $object->FromUserName;
			$json2['touser'] = "$openid";
			$json2['msgtype'] = "mpnews";
			$json2['mpnews']['media_id'] = "6XRQqvKuBbbvPTM_bkv8q-hXDQZCKzQ7ATmU6Q-LrDw";
			$json2 = json_encode($json2);
			//sleep(3);
			$ss = $this->wx->wxServiceSend($json2);
			return $result;

			//$content .= (!empty($object->EventKey)) ? ("\n来自二维码场景 " . str_replace("qrscene_", "", $object->EventKey)) : "";
			break;
		case "unsubscribe":
			$content = "取消关注";
			break;
		case "CLICK":
			switch ($object->EventKey) {
			case "COMPANY":
				$content = array();
				$content[] = array("Title" => "puz", "Description" => "", "PicUrl" => "http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" => "http://m.cnblogs.com/?u=txw1958");
				break;
			case "erweima":
				// $content = "点击菜单：" . $object->FromUserName;
				// // $content = "图片正在生成中...";
				$openid = $object->FromUserName;
				require_once THINK_PATH . "Weixin/WxApi.class.php";
				$this->wx = new \WxApi;
				echo $this->send_img($object);return "";

				break;
			default:
				$content = "点击菜单：" . $object->EventKey;
				break;
			}
			break;
		case "VIEW":
			$content = "跳转链接 " . $object->EventKey;
			break;
		case "SCAN":

			//$content = "扫描场景 " . $object->EventKey;

			//$content = "您已经关注过了";
			$content = "您已经关注过了!";
			$uid = 0;
			if (!empty($object->EventKey)) {
				$uid = str_replace("qrscene_", "", $object->EventKey);
			}
			if ($uid > 0) {
				# code...
			} else {
				$uid = 0;
			}
			$res = $this->check_user($object, $uid);
			if ($res == "") {
				$content .= "\n您没有推荐人";
			} else {
				$content .= "\n您的第一推荐人是：" . $res;
			}
			break;
		case "LOCATION":
			$content = "上传位置：纬度 " . $object->Latitude . ";经度 " . $object->Longitude;
			break;
		case "scancode_waitmsg":
			if ($object->ScanCodeInfo->ScanType == "qrcode") {
				$content = "扫码带提示：类型 二维码 结果：" . $object->ScanCodeInfo->ScanResult;
			} else if ($object->ScanCodeInfo->ScanType == "barcode") {
				$codeinfo = explode(",", strval($object->ScanCodeInfo->ScanResult));
				$codeValue = $codeinfo[1];
				$content = "扫码带提示：类型 条形码 结果：" . $codeValue;
			} else {
				$content = "扫码带提示：类型 " . $object->ScanCodeInfo->ScanType . " 结果：" . $object->ScanCodeInfo->ScanResult;
			}
			break;
		case "scancode_push":
			$content = "扫码推事件";
			break;
		case "pic_sysphoto":
			$content = "系统拍照";
			break;
		case "pic_weixin":
			$content = "相册发图：数量 " . $object->SendPicsInfo->Count;
			break;
		case "pic_photo_or_album":
			$content = "拍照或者相册：数量 " . $object->SendPicsInfo->Count;
			break;
		case "location_select":
			$content = "发送位置：标签 " . $object->SendLocationInfo->Label;
			break;
		default:
			$content = "receive a new event: " . $object->Event;
			break;
		}

		if (is_array($content)) {
			if (isset($content[0]['PicUrl'])) {
				$result = $this->transmitNews($object, $content);
			} else if (isset($content['MusicUrl'])) {
				$result = $this->transmitMusic($object, $content);
			}
		} else {
			$result = $this->transmitText($object, $content);
		}
		return $result;
	}

	//接收文本消息
	private function receiveText($object) {
		$keyword = trim($object->Content);
		//多客服人工回复模式
		if (strstr($keyword, "请问在吗") || strstr($keyword, "在线客服")) {
			$result = $this->transmitService($object);
			return $result;
		}

		//自动回复模式
		if (strstr($keyword, "文本")) {
			$content = "这是个文本消息";
		} else if (strstr($keyword, "表情")) {
			$content = "中国：" . $this->bytes_to_emoji(0x1F1E8) . $this->bytes_to_emoji(0x1F1F3) . "\n仙人掌：" . $this->bytes_to_emoji(0x1F335);
		} else if (strstr($keyword, "单图文")) {
			$content = array();
			$content[] = array("Title" => "单图文标题", "Description" => "单图文内容", "PicUrl" => "http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" => "http://m.cnblogs.com/?u=txw1958");
		} else if (strstr($keyword, "图文") || strstr($keyword, "多图文")) {
			$content = array();
			$content[] = array("Title" => "多图文1标题", "Description" => "", "PicUrl" => "http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" => "http://m.cnblogs.com/?u=txw1958");
			$content[] = array("Title" => "多图文2标题", "Description" => "", "PicUrl" => "http://d.hiphotos.bdimg.com/wisegame/pic/item/f3529822720e0cf3ac9f1ada0846f21fbe09aaa3.jpg", "Url" => "http://m.cnblogs.com/?u=txw1958");
			$content[] = array("Title" => "多图文3标题", "Description" => "", "PicUrl" => "http://g.hiphotos.bdimg.com/wisegame/pic/item/18cb0a46f21fbe090d338acc6a600c338644adfd.jpg", "Url" => "http://m.cnblogs.com/?u=txw1958");
		} else if (strstr($keyword, "音乐")) {
			$content = array();
			$content = array("Title" => "最炫民族风", "Description" => "歌手：凤凰传奇", "MusicUrl" => "http://121.199.4.61/music/zxmzf.mp3", "HQMusicUrl" => "http://121.199.4.61/music/zxmzf.mp3");
		} else if (strstr($keyword, "openid")) {
			$content = "您的OpenID：" . $object->FromUserName;
		} else {
			$content = "稍等，客服正忙";
			//$content = $this->jiqiren($keyword);
		}

		if (is_array($content)) {
			if (isset($content[0])) {
				$result = $this->transmitNews($object, $content);
			} else if (isset($content['MusicUrl'])) {
				$result = $this->transmitMusic($object, $content);
			}
		} else {
			$result = $this->transmitText($object, $content);
		}
		return $result;
	}

	//接收图片消息
	private function receiveImage($object) {
		$content = array("MediaId" => $object->MediaId);
		$result = $this->transmitImage($object, $content);
		return $result;
	}

	//接收位置消息
	private function receiveLocation($object) {
		$content = "你发送的是位置，经度为：" . $object->Location_Y . "；纬度为：" . $object->Location_X . "；缩放级别为：" . $object->Scale . "；位置为：" . $object->Label;
		$result = $this->transmitText($object, $content);
		return $result;
	}

	//接收语音消息
	private function receiveVoice($object) {
		if (isset($object->Recognition) && !empty($object->Recognition)) {
			$content = "你刚才说的是：" . $object->Recognition;
			$result = $this->transmitText($object, $content);
		} else {
			$content = array("MediaId" => $object->MediaId);
			$result = $this->transmitVoice($object, $content);
		}
		return $result;
	}

	//接收视频消息
	private function receiveVideo($object) {
		$content = array("MediaId" => $object->MediaId, "ThumbMediaId" => $object->ThumbMediaId, "Title" => "", "Description" => "");
		$result = $this->transmitVideo($object, $content);
		return $result;
	}

	//接收链接消息
	private function receiveLink($object) {
		$content = "你发送的是链接，标题为：" . $object->Title . "；内容为：" . $object->Description . "；链接地址为：" . $object->Url;
		$result = $this->transmitText($object, $content);
		return $result;
	}

	//回复文本消息
	private function transmitText($object, $content) {
		if (!isset($content) || empty($content)) {
			return "";
		}

		$xmlTpl = "<xml>
	    <ToUserName><![CDATA[%s]]></ToUserName>
	    <FromUserName><![CDATA[%s]]></FromUserName>
	    <CreateTime>%s</CreateTime>
	    <MsgType><![CDATA[text]]></MsgType>
	    <Content><![CDATA[%s]]></Content>
	</xml>";
		$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);

		return $result;
	}

	//回复图文消息
	private function transmitNews($object, $newsArray) {
		if (!is_array($newsArray)) {
			return "";
		}
		$itemTpl = "        <item>
	            <Title><![CDATA[%s]]></Title>
	            <Description><![CDATA[%s]]></Description>
	            <PicUrl><![CDATA[%s]]></PicUrl>
	            <Url><![CDATA[%s]]></Url>
	        </item>
	";
		$item_str = "";
		foreach ($newsArray as $item) {
			$item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
		}
		$xmlTpl = "<xml>
	    <ToUserName><![CDATA[%s]]></ToUserName>
	    <FromUserName><![CDATA[%s]]></FromUserName>
	    <CreateTime>%s</CreateTime>
	    <MsgType><![CDATA[news]]></MsgType>
	    <ArticleCount>%s</ArticleCount>
	    <Articles>
	$item_str    </Articles>
	</xml>";

		$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
		return $result;
	}

	//回复音乐消息
	private function transmitMusic($object, $musicArray) {
		if (!is_array($musicArray)) {
			return "";
		}
		$itemTpl = "<Music>
	        <Title><![CDATA[%s]]></Title>
	        <Description><![CDATA[%s]]></Description>
	        <MusicUrl><![CDATA[%s]]></MusicUrl>
	        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
	    </Music>";

		$item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

		$xmlTpl = "<xml>
	    <ToUserName><![CDATA[%s]]></ToUserName>
	    <FromUserName><![CDATA[%s]]></FromUserName>
	    <CreateTime>%s</CreateTime>
	    <MsgType><![CDATA[music]]></MsgType>
	    $item_str
	</xml>";

		$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
		return $result;
	}

	//回复图片消息
	private function transmitImage($object, $imageArray) {
		$itemTpl = "<Image>
	        <MediaId><![CDATA[%s]]></MediaId>
	    </Image>";

		$item_str = sprintf($itemTpl, $imageArray['MediaId']);

		$xmlTpl = "<xml>
	    <ToUserName><![CDATA[%s]]></ToUserName>
	    <FromUserName><![CDATA[%s]]></FromUserName>
	    <CreateTime>%s</CreateTime>
	    <MsgType><![CDATA[image]]></MsgType>
	    $item_str
	</xml>";

		$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
		return $result;
	}

	//回复语音消息
	private function transmitVoice($object, $voiceArray) {
		$itemTpl = "<Voice>
	        <MediaId><![CDATA[%s]]></MediaId>
	    </Voice>";

		$item_str = sprintf($itemTpl, $voiceArray['MediaId']);
		$xmlTpl = "<xml>
	    <ToUserName><![CDATA[%s]]></ToUserName>
	    <FromUserName><![CDATA[%s]]></FromUserName>
	    <CreateTime>%s</CreateTime>
	    <MsgType><![CDATA[voice]]></MsgType>
	    $item_str
	</xml>";

		$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
		return $result;
	}

	//回复视频消息
	private function transmitVideo($object, $videoArray) {
		$itemTpl = "<Video>
	        <MediaId><![CDATA[%s]]></MediaId>
	        <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
	        <Title><![CDATA[%s]]></Title>
	        <Description><![CDATA[%s]]></Description>
	    </Video>";

		$item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

		$xmlTpl = "<xml>
	    <ToUserName><![CDATA[%s]]></ToUserName>
	    <FromUserName><![CDATA[%s]]></FromUserName>
	    <CreateTime>%s</CreateTime>
	    <MsgType><![CDATA[video]]></MsgType>
	    $item_str
	</xml>";

		$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
		return $result;
	}

	//回复多客服消息
	private function transmitService($object) {
		$xmlTpl = "<xml>
	    <ToUserName><![CDATA[%s]]></ToUserName>
	    <FromUserName><![CDATA[%s]]></FromUserName>
	    <CreateTime>%s</CreateTime>
	    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
	</xml>";
		$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
		return $result;
	}

	//回复第三方接口消息
	private function relayPart3($url, $rawData) {
		$headers = array("Content-Type: text/xml; charset=utf-8");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}

	//字节转Emoji表情
	function bytes_to_emoji($cp) {
		if ($cp > 0x10000) {
			# 4 bytes
			return chr(0xF0 | (($cp & 0x1C0000) >> 18)) . chr(0x80 | (($cp & 0x3F000) >> 12)) . chr(0x80 | (($cp & 0xFC0) >> 6)) . chr(0x80 | ($cp & 0x3F));
		} else if ($cp > 0x800) {
			# 3 bytes
			return chr(0xE0 | (($cp & 0xF000) >> 12)) . chr(0x80 | (($cp & 0xFC0) >> 6)) . chr(0x80 | ($cp & 0x3F));
		} else if ($cp > 0x80) {
			# 2 bytes
			return chr(0xC0 | (($cp & 0x7C0) >> 6)) . chr(0x80 | ($cp & 0x3F));
		} else {
			# 1 byte
			return chr($cp);
		}
	}

	//日志记录
	private function logger($log_content) {
		return false;
		if (isset($_SERVER['HTTP_APPNAME'])) {
			//SAE
			sae_set_display_errors(false);
			sae_debug($log_content);
			sae_set_display_errors(true);
		} else if ($_SERVER['REMOTE_ADDR'] != "127.0.0.1") {
			//LOCAL
			$max_size = 1000000;
			$log_filename = THINK_PATH . "Weixin/log.xml";
			if (file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)) {unlink($log_filename);}
			file_put_contents($log_filename, date('Y-m-d H:i:s') . " " . $log_content . "\r\n", FILE_APPEND);
		}
	}

	public function send_kf_img($picurl = "", $a = "", $b = "", $c = "", $openid = "") {
		require_once THINK_PATH . "Weixin/WxApi.class.php";
		$this->wx = new \WxApi;
		$picurl = $picurl;
		$imageInfo = $this->downloadWeixinFile($picurl, $a, $b, $c); //保存文件'http://www.chuangyepaper.com/a.jpg'
		$fwxy = date('YmdHis') . 'w' . $openid;
		$filename = "/www/web/999cuohela/public_html/Uploads/erweima/" . $fwxy . ".jpg";
		$local_file = fopen($filename, 'w');
		if (false !== $local_file) {
			if (false !== fwrite($local_file, $imageInfo["body"])) {
				fclose($local_file);
			}
		}
		$data['erweima'] = "http://" . $_SERVER['HTTP_HOST'] . "/Uploads/erweima/" . $fwxy . ".jpg";
		$data['er_time'] = time() + C("erweima_expire_time");
		D("wx_user")->where("openid='" . $openid . "'")->save($data);
		$type = "image";
		$access_token = $this->wx->wxAccessToken();
		$filedata = array("media" => "@" . $filename); //文件路径，前面要加@，表明是文件上传.
		$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";

		$result = $this->wx->https_request($url, $filedata);
		$res = json_decode($result, true);
		$media_id = $res['media_id'];
		$json2['touser'] = $openid;
		$json2['msgtype'] = "image";
		$json2['image']['media_id'] = $media_id;
		$json2 = json_encode($json2);
		$ss = $this->wx->wxServiceSend($json2);

	}
	public function send_img($object) {
		require_once THINK_PATH . "Weixin/WxApi.class.php";
		$this->wx = new \WxApi;
		//echo $this->responseText($object, "您的专属二维码已生成\r\n有效期至 \r\n正在发送,请稍等.....");exit;
		$arr56 = json_decode(json_encode($object), true);
		$json['touser'] = $openid = $arr56['FromUserName'];
		$user_info = D("wx_user")->where("openid='" . $openid . "'")->field("uid,headimgurl,user_type")->find();
		$url_c = $user_info['headimgurl'];
		$host = $_SERVER['HTTP_HOST'];
		// {"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
		// {"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 123}}}
		if ($user_info['type'] == 2) {
			$qrd = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id":' . $user_info['uid'] . '}}}';
		} else {

			$qrd = '{"expire_seconds": ' . C("erweima_expire_time") . ', "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id":' . $user_info['uid'] . '}}}';
		}
		$tickets = $this->wx->wxQrCodeTicket($qrd); //获取临时ticket
		$tic = json_decode($tickets, true);
		$ticket = $tic['ticket'];
		$picurl = $this->wx->wxQrCode($ticket); //换取二维码
		$picurl = "http://" . $host . "/index.php/Home/Weixin/make_img&url_a=" . "http://" . $host . "/a.jpg&url_b=" . $picurl . "&url_c=" . $url_c;
		$errno = 0;
		$errstr = '';
		$fp = fsockopen($host, 80, $errno, $errstr, 100);
		if (!$fp) {
			echo 'error fsockopen';
		} else {
			stream_set_blocking($fp, 0);
			$http = "GET /index.php/Home/Weixin/send_img22/?picurl=" . $picurl . "&openid=" . $openid . " HTTP/1.1\r\n";
			$http .= "host:" . $host . "\r\n";
			$http .= "content-type:application/text/html;\r\n";
			$http .= "Connection: Close\r\n\r\n";
			fwrite($fp, $http);
			fclose($fp);

		}
		$t = time() + 30 * 24 * 60 * 60;
		$y_date = date("Y-m-d H:i:s", $t);
		echo $this->responseText($object, "您的专属二维码已生成\r\n有效期至" . $y_date . "\r\n正在发送,请稍等.....");
		return 1;

	}

	public function send_img22($picurl = "", $openid = "") {
		$picurl = I("get.picurl");
		$a = I("get.url_a");
		$b = I("get.url_b");
		$c = I("get.url_c");
		$picurl = I("get.picurl");
		//$picurl = urldecode($picurl);
		$openid = I("get.openid");

		$this->send_kf_img($picurl, $a, $b, $c, $openid);

	}
	public function make_img($aa = "", $bb = "", $cc = "") {

		/**
		 * 图片合并
		 **/
		//var_dump($a);exit;
		$pic_a = I('get.aa');
		$pic_b = I("get.bb");
		$pic_c = I("get.cc");

		$data['a'] = $pic_a;
		$data['b'] = $pic_b;
		$data['c'] = $pic_c;

		$str1 = $pic_c;
		$str2 = 'http';
		//strpos 大小写敏感  stripos大小写不敏感    两个函数都是返回str2 在str1 第一次出现的位置
		if (strpos($str1, $str2) === false) {
			//使用绝对等于
			$pic_c = "http://" . $_SERVER['HTTP_HOST'] . $pic_c;
		} else {

		}
		$bg_w = 640; // 背景图片宽度
		$bg_h = 960; // 背景图片高度
		$background = imagecreatetruecolor($bg_w, $bg_h); // 背景图片
		$color = imagecolorallocate($background, 202, 201, 201); // 为真彩色画布创建白色背景，再设置为透明
		imagefill($background, 0, 0, $color);
		imageColorTransparent($background, $color);
		$pathInfo = pathinfo($pic_c);
		switch (strtolower($pathInfo['extension'])) {
		case 'jpg':
		case 'jpeg':
			$imagecreatefromjpeg = 'imagecreatefromjpeg';
			break;
		case 'png':
			$imagecreatefromjpeg = 'imagecreatefrompng';
			break;
		case 'gif':
		default:
			$imagecreatefromjpeg = 'imagecreatefromstring';
			$pic_c = file_get_contents($pic_c);
			break;
		}
		$resource_a = imagecreatefromjpeg($pic_a);
		$resource_b = imagecreatefromjpeg($pic_b);
		$resource_c = $imagecreatefromjpeg($pic_c);
		imagecopyresized($background, $resource_a, 0, 0, 0, 0, 640, 960, imagesx($resource_a), imagesy($resource_a)); //
		imagecopyresized($background, $resource_b, 200, 636, 0, 0, 230, 230, imagesx($resource_b), imagesy($resource_b)); //
		imagecopyresized($background, $resource_c, 15, 482, 0, 0, 155, 155, imagesx($resource_c), imagesy($resource_c)); //

		header("Content-type: image/jpg");
		imagejpeg($background);

	}

	public function downloadWeixinFile($url, $a, $b, $c) {
		$url = $url . "?aa=$a&bb=$b&cc=$c";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY, 0); //只取body头
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$package = curl_exec($ch);
		$httpinfo = curl_getinfo($ch);
		curl_close($ch);
		return array_merge(array('body' => $package), array('header' => $httpinfo));
	}

	public function responseText($object, $content, $flag = 0) {
		$textTpl = "<xml>
	                    <ToUserName><![CDATA[%s]]></ToUserName>
	                    <FromUserName><![CDATA[%s]]></FromUserName>
	                    <CreateTime>%s</CreateTime>
	                    <MsgType><![CDATA[text]]></MsgType>
	                    <Content><![CDATA[%s]]></Content>
	                    <FuncFlag>%d</FuncFlag>
	                    </xml>";
		$resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
		return $resultStr;
	}

	/**
	 * 关注后检查用户是否注册
	 * @wxy
	 * @DateTime 2016-05-19T23:52:00+0800
	 * @return   [type]                   [description]
	 */
	public function check_user($object, $uid) {
		$openid = $object->FromUserName;
		$openid = "$openid";
		//return $uid;
		$arr = D("wx_user")->where("openid='" . $openid . "'")->field("uid,a_id")->find();
		$sb['data'] = $openid;
		D("log")->add($sb);
		$sj_name = D("wx_user")->where("uid=$uid")->getField("nickname");

		if ($arr) {

			if ($arr['a_id'] > 0) {
				$a_id = $arr['a_id'];
				$sj_name = D("wx_user")->where("uid=$a_id")->getField("nickname");
			} else {
				if ($arr['uid'] != $uid) {

					$data['a_id'] = $uid;
					$data['a_time'] = time();
					$wx_user = D("wx_user");
					$wx_user->startTrans();
					$s8 = $wx_user->where("openid='" . $openid . "'")->save($data);

					$s9 = D("user_data")->where("uid=$uid")->setInc("xj_num", 1);
					if ($s8 > 0 && $s9 > 0) {
						$wx_user->commit();
						send_xj_msg($uid, $arr['nickname']);
					} else {
						$wx_user->rollback();
					}

				} else {
					$sj_name = "";
				}
				//send_templet_msg("", 1); //发送模板消息

			}
		} else {

			$wx_user = D("wx_user");
			require_once THINK_PATH . "Weixin/WxApi.class.php";
			$this->wx = new \WxApi;
			$arr2 = $this->wx->wxGetUser($openid);

			$data['a_id'] = $uid;

			if ($uid > 0) {
				$arr_66 = $wx_user->where("uid=$uid")->field("a_id,b_id,c_id")->find();
				$data['b_id'] = $arr_66['a_id'];
				$data['c_id'] = $arr_66['b_id'];
			}
			$data['a_time'] = time();
			$data['openid'] = $arr2['openid'];
			$data['nickname'] = $arr2['nickname'];
			$data['headimgurl'] = $arr2['headimgurl'];
			$data['sex'] = $arr2['sex'];
			$data['city'] = $arr2['city'];
			$data['province'] = $arr2['province'];
			$data['country'] = $arr2['country'];
			$data['subscribe'] = 1;
			$data['subscribe_time'] = $arr2['subscribe_time'];
			$data3['x_time'] = $data2['c_time'] = $data['c_time'] = $data['x_time'] = time();
			$wx_user->startTrans(); //事务开始

			$s2 = $wx_user->add($data);
			$data3['uid'] = $s2;

			$s3 = D("user_data")->add($data3);
			if ($uid > 0) {
				$s4 = D("user_data")->where("uid=$uid")->setInc("xj_num", 1);
			} else {
				$s4 = 1;
			}

			if ($s2 > 0 && $s3 > 0 && $s4 > 0) {
				$wx_user->commit(); //事务提交
				$uid = $s2;
				if ($data['a_id'] > 0) {
					send_xj_msg($data['a_id'], $arr2['nickname']);
				}

			} else {
				$wx_user->rollback(); //事务回滚
				echo "<h1>注册失败，请联系管理员微信号：wxy511433</h1>";exit;
			}

			$_SESSION['uid'] = $s2;

		}
		return $sj_name;
	}

}
