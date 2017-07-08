<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/8
 * Time: 11:38
 */
namespace Admin\Controller;
use Admin\Model\UserModel;
use Think\Controller;
class LoginController extends Controller
{

    const SECURE_KEY = "pKKDTKPHq5qBX5jT@!=";


    /**
     * 用户注册
     */
    public function register(){
        $param = $_POST ? $_POST : "";
        $data = array();
        $phone = $param['phone'] ? $param['phone'] : "";
        $nickname = $param['nickname'] ? $param['nickname'] : "";
        $password = $param['password'] ? $param['password'] : "";
        $salt = $this->_salt_str();
        if(empty($nickname) || empty($password)){
            $data['code'] ="202";
            $data['msg'] = "参数错误!";
            return $this->__json_array($data);
        }
        $re_ip = $_SERVER['REMOTE_ADDR'];
        $re_time = time();
        $usermodel = new UserModel();
        $add_uid = $usermodel->get_uid();
        $token = $this->_make_token($add_uid);
        $add_user = $usermodel->add_user($add_uid,$phone,$nickname,$password,$re_ip,$re_time,$salt,$token);
        if($add_user){
            $data['code'] = "200";
            $data['msg'] = "注册成功!";
        }else{
            $data['code'] = "202";
            $data['msg'] = "注册成失败!";
        }
        return $this->__json_array($data);

    }


    /**
     * 用户登录
     */
    public function login_in(){
        $param = $_POST ? $_POST : "";
        if(empty($param)){
            $data['code'] = "202";
            $data['msg'] = "参数错误!";
            return $this->__json_array($data);
        }
        $data = array();
        $phone = $param['phone'] ? $param['phone'] : "";
        $nickname = $param['name'] ? $param['name'] : "";
        $email = $param['email'] ? $param['email'] : "";
        $password = $param['password'] ? $param['password'] : "";
        $ip = $_SERVER['REMOTE_ADDR'];
        $last_time = time();
        $usermodel = new UserModel();
        $user_info = $usermodel->find_user($password,$ip,$last_time,$phone,$email,$nickname);
        if($user_info){
            $data['code'] = "200";
            $data['msg'] = "登录成功!";
            $data['body'] = $user_info;
        }else{
            $data['code'] = "202";
            $data['msg'] = "登录失败!";
        }
        return $this->__json_array($data);

    }


    /**
     * 用户修改密码
     */
    public function update_password(){
        $param = $_GET ? $_GET : "";
        $data = array();
        $uid = $param['uid'] ? $param['uid'] : "";
        $token = $param['token'] ? $param['token'] : "";
        $newpassword = $param['new_pass'] ? $param['new_pass'] : "";
        //$oldpassword = $param['old_pass'] ? $param['old_pass'] : "";
        if(empty($param['uid']) || empty($param['token'])){
            $data['code'] = "202";
            $data['msg'] = "参数错误！";
            return $this->__json_array($data);
        }
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败,请重新登录！";
            return $this->__json_array($data);
        }
        $usermodel = new UserModel();
        $update_password = $usermodel->update_password($uid,$newpassword);
        if($update_password){
            $data['code'] = "200";
            $data['msg'] = "修改密码成功!";
            $data['body'] = $uid;
        }else{
            $data['code'] = "202";
            $data['msg'] = "修改密码失败！";
        }
        return $this->__json_array($data);


    }




    /**
     * 用户退出
     */
    public function login_out(){

    }







    /**
     * 转换为json格式输出
     */
    public function __json_array($data){
        $json = json_encode($data);
        return $json;
    }

    /**
     * 密码加密随机字符
     * @return string
     */
    public function _salt_str($len = 6){
        $str = "abcdefghijklmnopqrstuvwsyzABCDEFGHIGKLIMNOPQRSTUVWSYZ123456789";
        $salt_str = "";
        for ($i = 0; $i < $len; $i++){
            $salt_str .= $str[mt_rand(0,strlen($str))];
        }
        return $salt_str;
    }


    /**
     * 生成长度为10的token
     * @param -$uid 用户uid
     * @return token
     */
    public function _make_token($uid){
        $token = md5($uid.self::SECURE_KEY);
        $token = substr($token,0,10);
        return $token;
    }



}