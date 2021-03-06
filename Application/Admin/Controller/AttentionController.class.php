<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/19
 * Time: 10:13
 */
namespace Admin\Controller;
use Admin\Model\AttentionModel;
use Think\Controller;
class AttentionController extends Controller
{


    /**
     * 关注
     */
    public function attention(){
        $uid = $_POST['uid'] ? $_POST['uid'] : "";
        $token = $_POST['token'] ? $_POST['token'] : "";
        $be_uid = $_POST['be_uid'] ? $_POST['be_uid'] : "";
        $time = time();
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        $add_data = array(
            'uid' => $uid,
            'be_uid' => $be_uid,
            'time' => $time,
        );
        $res = M('attention')->add($add_data);
        if($res){
            $data['code'] = "200";
            $data['msg'] = "关注成功！";
            $data['body']['uid'] = $be_uid;
        }else{
            $data['code'] = "202";
            $data['msg'] = "关注失败！";
        }
        return $this->_array_to_json($data);

    }


    /**
     * 取消关注
     */
    public function cancel_attention(){
        $uid = $_GET['uid'] ? $_GET['uid'] : "";
        $token = $_GET['token'] ? $_GET['token'] : "";
        $be_uid = $_GET['be_uid'] ? $_GET['be_uid'] : "";
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        $attentionModel = new AttentionModel();
        $del_be_uid = $attentionModel->del_be_uid($uid,$be_uid);
        if($del_be_uid){
            $data['code'] = "200";
            $data['msg'] = "取消成功！";
        }else{
            $data['code'] = "202";
            $data['msg'] = "取消关注失败！";
        }
        return $this->_array_to_json($data);
    }



    /**
     * 获取关注
     */
    public function get_attention(){
        $uid = $_GET['uid'] ? $_GET['uid'] : "";
        $token = $_GET['token'] ? $_GET['token'] : "";
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        $where = array(
            'uid' => $uid,
        );
        $res = M('attention')->where($where)->select();
        if(!empty($res)){
            $data['code'] = "200";
            $data['msg'] = "获取成功！";
            $data['body']['data'] = $res;
        }else{
            $data['code'] = "202";
            $data['msg'] = "暂时没有关注";
            $data['body']['data'] = "";
        }
        return $this->_array_to_json($data);
    }



    /**
     * 获取关注的会员内容
     */
    public function get_attention_info(){
        $uid = $_GET['uid'] ? $_GET['uid'] : "";
        $token = $_GET['token'] ? $_GET['token'] : "";
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        $attentionModel = new AttentionModel();
        $get_attention_list = $attentionModel->get_attention_list($uid);
        if($get_attention_list){
            $data['code'] = "200";
            $data['msg'] = "获取成功！";
            $data['body']['data'] = $get_attention_list;
        }else{
            $data['code'] = "202";
            $data['msg'] = "没有数据！";
            $data['body']['data'] = "";
        }
        return $this->_array_to_json($data);

    }


    /**
     * 点赞
     */
    public function like(){
        $uid = I('get.uid') ? I('get.uid') : "";
        $token = I('get.token') ? I('get.token') : "";
        $obj_id = I('get.id') ? I('get.id') : "";
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        $attentionModel = new AttentionModel();
        $res = $attentionModel->add_like($uid,$obj_id);
        if($res){
            $data['code'] = "200";
            $data['msg'] = "点赞成功！";
        }else{
            $data['code'] = "202";
            $data['msg'] = "点赞失败！";
        }
        return $this->_array_to_json($data);

    }


    /**
     * 取消点赞
     */
    public function del_like(){
        $uid = I('get.uid') ? I('get.uid') : "";
        $token = I('get.token') ? I('get.token') : "";
        $obj_id = I('get.id') ? I('get.id') : "";
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        $attentionModel = new AttentionModel();
        $res = $attentionModel->del_like($uid,$obj_id);
        if($res){
            $data['code'] = "200";
            $data['msg'] = "取消成功！";
        }else{
            $data['code'] = "202";
            $data['msg'] = "取消失败！";
        }
        return $this->_array_to_json($data);

    }



    /**
     * 数组转换为json格式
     * @param -$data 需要转换的数组
     * @return  false or json;
     */
    public function _array_to_json($data){
        if(empty($data) || !is_array($data)){
            return false;
        }
        $data = json_encode($data);
        return $data;
    }


}