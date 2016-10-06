<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
	public function __construct() {
		parent::__construct();
		//check_wx_login();
        wx_login();
        
        $this->get_signPackage();

        $sys_set['share_title'] = "";
        $sys_set['share_img'] = "";
        $sys_set['share_desc'] = "";

        $this->assign("sys_set",$sys_set);

        //$_SESSION['uid'] = 11501;
	}

    /*public function clear(){
        $uid = uid();
        if($uid == 13173){
            
        }
    }*/

    /*分享配置参数*/
    public function get_signPackage() {
        require_once THINK_PATH . "Weixin/WxApi.class.php";
        $wx = new \WxApi;
        $signPackage = $wx->wxJsapiPackage();
        $this->assign('signPackage', $signPackage);
    }

    public function index(){
        $this->display();
    }

    public function hd_list(){
        $this->display();
    }

    public function hd_ny(){
        $uid = uid();
        $gid = I("get.id",1);
        $arr = D("kanjia_record")->where("uid=$uid and kid=$gid")->find();
        if($arr){
            //$this->hd_ny_result();exit;
            echo "<script>location.href='http://sn818.ktwlkj.com/index.php/Home/Index/hd_ny_result.html?id=".$gid."'</script>";exit;
        }else{
            $list = D("kanjia")->where("id = $gid")->find();
            $this->assign("list",$list);
            $this->assign("id",$gid);
        }

        $info = D("goods")->where("id=$gid")->find();
        $info['detail_img'] = html_entity_decode($info['detail_img']);
        $this->assign("info", $info);
        $this->display();
    }

    public function get_kanjia_info($krid) {
        $arr3 = D("kanjia_help_record")->where("krid=$krid")->join("pg_wx_user on pg_kanjia_help_record.uid=pg_wx_user.uid")->limit('100')->field("pg_wx_user.nickname,pg_wx_user.headimgurl,pg_kanjia_help_record.*")->order("pg_kanjia_help_record.c_time desc")->select();
        $k_arr = D("kanjia_record")->where("id=$krid")->find();
        $this->assign("k_list", $k_arr);

        $this->assign("k_num", $k_arr['k_peo']);
        $this->assign("k_money", $k_arr['k_price']);
        $this->assign("help_list", $arr3);
    }

    public function hd_ny_result(){
        $id = I("get.id", 0);
        $pid = $id;
        $uid = uid();
        $arr = D("kanjia")->where("pg_kanjia.id=$id")->join("pg_goods on pg_goods.id=pg_kanjia.pid")->field("pg_goods.*,pg_kanjia.status,pg_kanjia.max_peo,pg_kanjia.cost_price,pg_kanjia.max_peo,pg_kanjia.rand_a,pg_kanjia.rand_b,pg_kanjia.low_price")->find();
        $arr['goods_imgs_wap'] = explode(',', ltrim($arr['goods_imgs_wap'], ','));
        if (!$arr) {
            $this->error('砍价商品不存在');exit;
        }
        $arr2 = D("kanjia_record")->where("uid=$uid and kid='" . $id . "'")->find();

        //p($arr2);exit;
        if ($arr2) {
            $krid = $arr2['id'];
            $k_price = D("kanjia_help_record")->where("uid=$uid and krid=$krid")->getField("k_price");
        } else {
            $time = time();
            $data['uid'] = $uid;
            $data['kid'] = $id;
            $data['c_time'] = $time;
            $data['status'] = 1;
            $k_price = rand($arr['rand_a'] * 100, $arr['rand_b'] * 100) * 0.01;
            $data['k_price'] = $k_price;
            $kanjia_record = D("kanjia_record");
            $kanjia_record->startTrans();
            $s1 = $kanjia_record->add($data);

            $map['uid'] = $uid;
            $map['krid'] = $s1;
            $map['k_price'] = $k_price;
            $map['c_time'] = $time;
            $map['staus'] = 1;

            //$map['dao'] = $this->get_dao();

            $s2 = D("kanjia_help_record")->add($map);
            if ($s1 > 0 && $s2 > 0) {
             $kanjia_record->commit();
            } else {
             $kanjia_record->rollback();
             $this->error('新增砍价记录出错');exit;
            }
            $krid = $s1;

            $arr2 = D("kanjia_record")->where("uid=$uid and kid='" . $id . "'")->find();
        }

        //进度条
        $process = ((double)$arr2['k_peo']/(double)$arr['max_peo'])*100;
        $this->assign("process", $process);

        $this->get_kanjia_info($krid);
        $this->assign("list", $arr);
        $this->assign("record",$arr2);
        $this->assign("id", $id);
        $this->assign("krid", $krid);
        $this->assign("k_price", $k_price);

        $this->assign("uid", $uid);

        $info = D("goods")->where("id=$pid")->find();
        $info['detail_img'] = html_entity_decode($info['detail_img']);
        $this->assign("info", $info);

        $this->display();
    }

    public function paihangbang(){
        $krid = I("get.krid",0);
        $uid = uid();

        $sql = "SELECT b.headimgurl,b.nickname,a.k_price FROM pg_kanjia_help_record a,pg_wx_user b WHERE a.uid = b.uid AND a.krid = ".$krid." order by a.k_price desc";
        $list = M()->query($sql);
        $this->assign("list",$list);
        $this->display();
    }

    public function youhuijuan(){
        $uid = uid();
        $sql = "SELECT b.id,b.goods_name,b.goods_no,b.price ,a.k_price,a.is_prize FROM pg_kanjia_record a,pg_goods b WHERE a.kid = b.id AND a.uid = ".$uid;
        $list = M()->query($sql);
        $this->assign("list",$list);
        $this->display();
    }

    public function get_prize(){
        $id = I("post.kid",0);
        $uid = uid();

        $map['is_prize'] = 2;
        $is = D("kanjia_record")->where("kid = ".$id." and uid=".$uid)->save($map);
        if($is){
            $this->ajaxReturn(array("code"=>1,"msg"=>"领取成功"));
        }else{
            $this->ajaxReturn(array("code"=>-1,"msg"=>"领取失败"));
        }
    }

    //朋友砍价
    public function help_kan(){
        $krid = I("get.krid", 0);
        $state = I("get.state", 0);
        if ($state > 0) {
            $krid = $state;
        }
        $uid = uid();
        if ($krid == 0) {
            $this->error('砍价记录不存在');exit;
        }

        $arr = D("kanjia_record")->where("id=$krid")->find();
        if ($arr['uid'] == uid()) {
            echo "<script>location.href='http://sn818.ktwlkj.com/index.php/Home/Index/hd_ny_result.html?id=".$arr['kid']."'</script>";exit;
        }

        $pid = $arr['kid'];
        $arr2 = D("kanjia")->where("pg_kanjia.id='" . $arr['kid'] . "'")->join("pg_goods on pg_goods.id=pg_kanjia.pid")->field("pg_goods.*,pg_kanjia.status,pg_kanjia.max_peo,pg_kanjia.cost_price,pg_kanjia.max_peo,pg_kanjia.rand_a,pg_kanjia.rand_b,pg_kanjia.low_price")->find();
        if (!$arr2) {
            $this->error('砍价商品不存在');exit;
        }

        $this->assign("list", $arr2);
        $this->assign("record", $arr);
        //p($arr2);exit;
        $this->get_kanjia_info($krid);

        //返回用户基本信息
        $k_user = D("wx_user")->where("uid='" . $arr['uid'] . "'")->find();
        $this->assign("k_user", $k_user);
        $this->assign("krid", $krid);

        $arr9 = D("kanjia_help_record")->where("uid=$uid and krid=$krid")->find();
        if ($arr9) {
            $is_k = 1; //已帮助
            $this->assign("list9", $arr9);
        } else {
            $is_k = 0; //未砍价
        }
        if ($arr['status'] == 2) {
            $is_k = 2;
        }
        if ($arr['status'] == -1) {
            $is_k = -1;
        }

        //进度条
        $process = ((double)$arr['k_peo']/(double)$arr2['max_peo'])*100;
        $this->assign("process", $process);

        $info = D("goods")->where("id=$pid")->find();
        $info['detail_img'] = html_entity_decode($info['detail_img']);
        $this->assign("info", $info);

        $this->assign("is_k", $is_k);
        $this->display();
    }

    public function help_kanhou($krid = 0) {
        $krid = I("get.krid", 0);
        $this->assign("krid", $krid);
        $uid = uid();
        $arr = D("kanjia_record")->where("id=$krid")->find();
        
        $arr3 = D("kanjia")->where("id='" . $arr['kid'] . "'")->find();
        $arr2 = D("kanjia_help_record")->where("krid=$krid and uid=$uid")->find();

        if (!$arr) {
            $this->result($krid,'砍价记录不存在', 1);exit;

        }
        if ($arr['status'] == 2) {
            $this->result($krid,'已砍到底价了', 1);exit;
        }
        if ($arr['status'] == -1) {
            $this->result($krid,'已失败', 1);exit;
        }

        if ($arr['k_peo'] >= $arr3['max_peo']) {
            $map6['status'] = -1;
            D("kanjia_record")->where("id=$krid")->save($map6);

            $this->result($krid,'帮助人数已达上限', 1);exit;
        }

        if ($arr2) {
            $k_arr = $arr2;
            $this->assign("k_price",$k_arr['k_price']);$this->display();exit;
        } else {
            $k_price = rand($arr3['rand_a'] * 100, $arr3['rand_b'] * 100) * 0.01;

            $map['uid'] = $uid;
            $map['krid'] = $krid;

            $map['c_time'] = time();
            $map['staus'] = 1;
            $kanjia_record = D("kanjia_record");
            $kanjia_record->startTrans();

            if (($arr3['cost_price'] - $k_price) > ($arr3['low_price'] + $arr['k_price'])) {
                $map2['k_price'] = $arr['k_price'] + $k_price;
                $s2 = $kanjia_record->where("id=$krid")->save($map2);
            } else {//砍到底价
                $k_price = ($arr3['cost_price'] - $arr3['low_price'] - $arr['k_price']);
                $map2['status'] = 2;
                $map2['k_price'] = $arr['k_price'] + $k_price;
                $s2 = $kanjia_record->where("id=$krid")->save($map2);
            }
            $map['k_price'] = $k_price;
            $s1 = D("kanjia_help_record")->add($map);
            $s3 = $kanjia_record->where("id=$krid")->setInc("k_peo", 1);
            $arr8 = $kanjia_record->where("id=$krid")->find();
            $s4 = $arr3['max_peo'] - $arr8['k_peo'];
            if ($s4 == 0 && $arr8['status'] == 1) {
                $map6['status'] = -1;
                $kanjia_record->where("id=$krid")->save($map6);
            }

            if ($s1 > 0 && $s2 > 0 && $s3 > 0 && $s4 >= 0) {
                $kanjia_record->commit();
                $k_arr = $map;
            } else {
                $kanjia_record->rollback();
                $this->result($krid,'帮砍价失败', 1);exit;
                //$this->result('帮砍价失败', 0);exit;
            }
        }
        /*$this->assign("k_price",$k_arr['k_price']);
        $this->display();*/
        $this->result($krid,'您' . '帮他砍了' . $k_arr['k_price'], 0);
        return $k_arr;
    }

    public function result($krid,$str="",$type) {
        $uid = uid();
        $arr = D("kanjia_record")->where("id=$krid")->find();
        $arr3 = D("kanjia")->where("id='" . $arr['kid'] . "'")->find();
        $arr2 = D("kanjia_help_record")->where("krid=$krid and uid=$uid")->find();

        $pid = $arr['kid'];
        //进度条
        $process = ((double)$arr['k_peo']/(double)$arr3['max_peo'])*100;
        $this->assign("process", $process);

        $this->assign("record",$arr);
        $this->assign("list",$arr3);
        $this->assign("k_price",$arr2['k_price']);
        $this->assign("str",$str);
        $this->assign("type",$type);
        $this->assign("krid",$krid);

        $info = D("goods")->where("id=$pid")->find();
        $info['detail_img'] = html_entity_decode($info['detail_img']);
        $this->assign("info", $info);

        $this->display("help_kanhou");
    }

    public function qixing(){
        $uid = uid();
        $arr = D("dk_record")->where("uid=".$uid)->find();
        if($arr){
            //$this->display("qixinghou");
            $this->qixinghou();exit;
        }else{
            $money = $this->getNowMoney();
            $this->assign("allmoney",$money);
        }
        $this->display();
    }

    public function getNowMoney(){
        $count = D("dk_record")->where("status=2")->count();
        return $count*2;
    }

    public function qixinghou(){
        $uid = uid();
        $money = $this->getNowMoney();
        $this->assign("allmoney",$money);
        $status = 0;
        $rid = 0;

        $arr = D("dk_record")->where("uid=".$uid)->find();
        if($arr){
            $status = 1;
            $process = (int)$arr['num']*10;
            if((int)$arr['num']>=10){
                $status = 2;//人数已满
            }
            $rid = $arr['id'];
            $this->assign("list",$arr);
        }else{
            $map['uid'] = $uid;
            $map['c_time'] = time();
            $map['num'] = 1;
            $id = D("dk_record")->add($map);

            if($id){
                $map2["rid"] = $id;
                $map2['c_time'] = time();
                $map2['uid'] = $uid;
                $id2 = D("dk_help_record")->add($map2);
            }
            $rid = $id;
            $process = 10;
            $this->assign("list",$map);
        }
        $this->assign("rid",$rid);
        $this->assign("status",$status);
        //进度条
        $this->assign("process",$process);
        //总参与人数
        $count = D("dk_help_record")->count();
        $this->assign("count",$count);
        $this->display("qixinghou");
    }

    public function juankuan(){
        $sql = "SELECT a.headimgurl,a.nickname FROM pg_wx_user a,pg_dk_record b WHERE a.uid = b.uid AND b.status=2 ORDER BY b.`s_time` DESC LIMIT 100";
        $list = M()->query($sql);
        $this->assign("list",$list);
        $this->display();
    }

    public function help_qixing(){
        $rid = I("get.rid", 0);
        $uid = uid();
        $status = 0;
        if ($rid == 0) {
            $this->error('打卡记录不存在');exit;
        }

        $money = $this->getNowMoney();
        $this->assign("allmoney",$money);

        $arr = D("dk_record")->where("id=$rid")->find();
        if(!$arr){
            $this->error('打卡记录不存在');exit;
        }else{
            if ($arr['uid'] == uid()) {
                echo "<script>location.href='http://sn818.ktwlkj.com/index.php/Home/Index/qixinghou.html'</script>";exit;
            }
        }

        //判断是否已经打卡
        $result = D("dk_help_record")->where("uid=$uid and rid = $rid")->find();
        if($result){
            $status = 1;
        }else{
            $status = 0;
        }

        $user = D("wx_user")->where("uid=".$arr['uid'])->find();

        $this->assign("rid", $rid);
        $this->assign("status", $status);
        $this->assign("num",$arr['num']);
        //总参与人数
        $count = D("dk_help_record")->count();
        $this->assign("count",$count);
        //返回总金额
        $money = $this->getNowMoney();
        $this->assign("allmoney",$money);
        //进度条
        $process = (int)$arr['num']*10;
        $this->assign("process",$process);
        $this->assign("nickname",$user['nickname']);

        $this->display();
    }

    public function help_qixinghou(){
        $rid = I("get.rid", 0);
        $uid = uid();
        $arr = D("dk_record")->where("id=$rid")->find();
        
        $arr2 = D("dk_help_record")->where("rid=$rid and uid=$uid")->find();

        if (!$arr) {
            $this->result_dk($rid,'砍价记录不存在', 1);exit;
        }
        if ($arr['status'] == 2) {
            $this->result_dk($rid,'已砍到底价了', 1);exit;
        }

        if ($arr['num'] >= 10) {
            $map6['status'] = 2;
            D("dk_record")->where("id=$rid")->save($map6);
            $this->result_dk($rid,'帮助人数已达上限', 1);exit;
        }


        if ($arr2) {
            $this->result_dk($rid,'帮助人数已达上限', 1);exit;
        } else {
            $map['uid'] = $uid;
            $map['rid'] = $rid;

            $map['c_time'] = time();
            $map['staus'] = 1;
            $dk_record = D("dk_record");
            $dk_record->startTrans();

            $s1 = D("dk_help_record")->add($map);
            $s3 = $dk_record->where("id=$rid")->setInc("num", 1);
            $arr8 = $dk_record->where("id=$rid")->find();
            $s4 = 10 - $arr8['num'];
            if ($s4 == 0 && $arr8['status'] == 1) {
                $map6['status'] = 2;
                $dk_record->where("id=$rid")->save($map6);
            }

            if ($s1 > 0  && $s3 > 0 && $s4 >= 0) {
                $dk_record->commit();
                $k_arr = $map;
            } else {
                $dk_record->rollback();
                $this->result_dk($rid,'帮砍价失败', 1);exit;
            }
        }
        $this->result_dk($rid,'您' . '帮他砍了' . $k_arr['k_price'], 0);
        return $k_arr;
    }

    public function result_dk($rid,$str="",$type){
        $uid = uid();
        $arr = D("dk_record")->where("id=$rid")->find();
        $arr2 = D("dk_help_record")->where("rid=$rid and uid=$uid")->find();

        //进度条
        $process = (int)$arr['num']*10;
        $this->assign("process", $process);

        //总参与人数
        $count = D("dk_help_record")->count();
        $this->assign("count",$count);
        $this->assign("num",$arr['num']);
        //返回总金额
        $money = $this->getNowMoney();
        $this->assign("allmoney",$money);

        $this->assign("record",$arr);
        $this->assign("str",$str);
        $this->assign("type",$type);
        $this->assign("rid",$rid);
        $this->display("help_qixinghou");
    }

}