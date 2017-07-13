<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/13
 * Time: 11:17
 */
namespace Admin\Model;
use Think\Model;

class CommonModel extends Model
{


    /**
     * 获取用户详细信息
     * @param -$uid 会员uid
     * @return false or array
     */
    public function get_user_info($uid){
        if(empty($uid)){
            return false;
        }
        $data = M('user')->where('uid',$uid)->select();
        if(!empty($data) && is_array($data)){
            return $data[0];
        }else{
            return false;
        }
    }
}