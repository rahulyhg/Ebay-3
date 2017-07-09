<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/10
 * Time: 1:24
 */
namespace Admin\Controller;
use Admin\Model\FeedbackModel;
use Think\Controller;
class Feedback extends Controller
{


    /**
     * 添加意见反馈
     */
    public function add_feedback(){
        $data = array();
        $param = $_POST ? $_POST : "";
        $uid = $param['uid'] ? $param['uid'] : "";
        $token = $param['token'] ? $param['token'] : "";
        $time = time();
        $ip = $_SERVER['REMOTE_ADDR'];
        $content = $param['text'] ? $param['text'] : "";
        $phone = $param['mobile'] ? $param['mobile'] : "";
        if(!check_token($uid,$token)){
            $data['code'] = '202';
            $data['msg'] = '身份验证失败！';
            return $this->_array_to_json($data);
        }
        $feedmodel = new FeedbackModel();
        $add_feedback = $feedmodel->add_feedback_info($uid,$content,$ip,$time,$phone);
        if($add_feedback){
            $data['code'] = '200';
            $data['msg'] = '添加成功！';
        }else{
            $data['code'] = '202';
            $data['msg'] = '添加失败！';
        }
        return $this->_array_to_json($data);

    }



    /**
     * 数组转换为json
     * @param -$data  转换数组
     * @return json;
     */
    public function _array_to_json($data){
        if(!is_array($data) || empty($data)){
            return false;
        }
        $data = json_encode($data);
        return $data;
    }


}