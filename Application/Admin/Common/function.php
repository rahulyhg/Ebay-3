<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/8
 * Time: 13:26
 */





/**
 * 验证用户登录状态
 * @param -$uid 用户uid  -$token 用户唯一token
 * @return true or false
 */
function check_token($uid,$token,$salt = ''){
    if(!is_numeric($uid) || $uid < 0 || strlen($token) != 10){
        return false;
    }
    $secure_key = "pKKDTKPHq5qBX5jT@!=";
    $token = strtolower($token);
    $cal_token = md5 ( $uid . $secure_key.$salt);
    $cal_token = substr($cal_token,0,10);
    if($token != $cal_token){
        return false;
    }
    return true;
}