<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/10
 * Time: 1:39
 */
namespace Admin\Model;
use Think\Model;
class FeedbackModel extends Model
{


    /**
     * 添加意见反馈
     * @param -$uid 用户uid -$content 反馈内容 -$ip 设备ip  -$time 添加时间  -$phone 手机号
     * @return false or true
     */
    public function add_feedback_info($uid,$content,$ip,$time,$phone){
        if(empty($uid) || empty($content) || empty($phone)){
            return false;
        }
        $data = array(
            'back_content' => $content,
            'mobile' => $phone,
            'add_time' => $time,
            'from_uid' => $uid,
            'from_ip' => $ip,
        );
        $res = M('feedback')->add($data);
        if($res){
            return true;
        }else{
            return false;
        }
    }




}