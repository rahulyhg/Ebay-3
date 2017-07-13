<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/13
 * Time: 10:32
 */
namespace Admin\Controller;
use Admin\Model\CommentModel;
use Admin\Model\CommonModel;
use Think\Controller;
use Think\Cache\Driver\Redis;
class CommentController extends Controller
{

    /**
     * 写入评论
     */
    public function add_comment(){
        $data = array();
        $uid = $_POST['uid'] ? $_POST['uid'] : "";
        $token = $_POST['token'] ? $_POST['token'] : "";
        $obj_id = $_POST['id'] ? $_POST['id'] : "";
        $content = $_POST['text'] ? $_POST['text'] : "";
        $replyCommentId = $_POST['replyCommentId'] ? $_POST[' replyCommentId'] : "";
        $replyThemeId = $_POST['replyThemeId'] ? $_POST['replyThemeId'] : "";
        $time = time();
        $ip = $_SERVER['REMOTE_ADDR'];
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        $commentmodel = new CommentModel();
        $commonmodel = new CommonModel();
        if(!empty($replyCommentId)){
            $is_reply = 1;
        }else{
            $is_reply = 0;
        }
        $get_user_status = $commonmodel->get_user_info($uid);
        if($get_user_status['status'] == 1){
            $data['code'] = "202";
            $data['msg'] = "你已被禁言！";
            return $this->_array_to_json($data);
        }
        $param = array(
            'objectid' => $obj_id,
            'uid' => $uid,
            'contents' => addslashes($content),
            'add_time' => $time,
            'comment_ip' => $ip,
            'is_reply' => $is_reply,
            'reply_comments_id' => $replyCommentId,
            'reply_theme_id' => $replyThemeId,
            'status' => 1,
            'is_visible' => 1,
        );
        $add_comment = $commentmodel->add_comment($param);
        $redisKey = "recommendList:bookid:$obj_id";
        $redis = new Redis();
        $redis->rm($redisKey);
        if($add_comment){
            $data['code'] = "200";
            $data['msg'] = "添加成功！";
        }else{
            $data['code'] = "202";
            $data['msg'] = "添加数据失败！";
        }
        return $this->_array_to_json($data);
    }


    /**
     * 获取作品评论
     */
    public function get_object_comments(){
        $data = array();
        $uid = $_GET['uid'] ? $_GET['uid'] : "";
        $token = $_GET['token'] ? $_GET['token'] : "";
        $object_id = $_GET['id'] ? $_GET['id'] : "";
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        $commentmodel = new CommentModel();
        $redis = new Redis();
        $redisKey = "recommendList:bookid:$object_id";
        $last_info = $redis -> get($redisKey);
        if(!$last_info){
            $get_comments = $commentmodel->get_comments_info($object_id);
            if($get_comments){
                $redis->set($redisKey,$get_comments);
                $data['code'] = "200";
                $data['msg'] = "获取成功！";
                $data['body']['data'] = $get_comments;
            }
        }else{
            $data['code'] = "200";
            $data['msg'] = "获取成功！";
            $data['body']['data'] = $last_info;
        }
        return $this->_array_to_json($data);
    }




    /**
     * 数组转化为json
     */
    public function _array_to_json($data){
        if(empty($data) || !is_array($data)){
            return false;
        }
        $data = json_encode($data);
        return $data;
    }



}