<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/8
 * Time: 11:49
 */
namespace Admin\Model;
use Think\Model;
class UserModel extends Model
{

    /**
     * 用户注册
     * @param -$phone 用户手机号  -$nickname 用户名
     * @param -$password 用户密码 -$$re_ip 注册ip  -$re_time 注册时间  -$salt 随机字符
     * @return true or false
     */
    public function add_user($add_uid,$phone,$nickname,$password,$re_ip,$re_time,$salt,$token){
        if(empty($phone) || empty($nickname) || empty($password)){
            return false;
        }
        $password = md5(md5($password).$salt);
        $experience = 100;
        $sql = "insert into `eb_user`(`uid`,`mobile`,`headimg`,`password`,`nickname`,`regip`,`regdate`,`salt`,`token`,`experience`)
                VALUES ('$add_uid','$phone','','$password','$nickname','$re_ip','$re_time','$salt','$token','$experience')";
        $res = $this->query($sql);
        if($res){
            return true;
        }else{
            return false;
        }

    }


    /**
     * 随机生成uid
     * @return 字符型整形数字
     */
    public function get_uid(){
        $data = M('user')->order('uid desc')->limit(1)->select();
        if(empty($data)){
            $data[0]['uid'] = intval(time().mt_rand(1,1000000));
        }else{
            $data[0]['uid'] += 1;
        }
        return $data[0]['uid'] ? $data[0]['uid'] : null;
    }


    /**
     * 登录获取用户信息
     * @param -$password 用户密码 $ip -登录ip  $last_time -最后登录时间
     * @param -$phone -用户手机号  $email -用户邮箱 $nickname -用户名称
     * @return false or array
     */
    public function find_user($password,$ip,$last_time,$phone = "",$email = "",$nickname = ""){
        if(empty($password) || empty($ip) || empty($last_time)){
            return false;
        }
        $sql = "select uid,mobile,email,headimg,nickname,token from `eb_user` 
                WHERE `is_visible` = '0' AND ";
        if(!empty($nickname)){
            $sql .= "`nickname` = '$nickname'";
        }
        if(!empty($email)){
            $sql .= "`email` = '$email'";
        }
        if(!empty($phone)){
            $sql .= "`mobile` = '$phone'";
        }
        $data = $this->query($sql);
        foreach ($data as $k => $v){
            $pass = $v['password'];
            $salt = $v['salt'];
            $uid = $v['uid'];
        }
        $password = md5(md5($password).$salt);
        if($password == $pass){
            $sql = "update `eb_user` set `last_log_ip` = '$ip',`last_log_time` = '$last_time',`is_login` = '1'
                    WHERE `uid` = '$uid'";
            $res = $this->query($sql);
            if($res){
                return $data[0] ? $data[0] : null;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }




    /**
     * 修改用户密码
     * @param -$uid 用户uid  -$newpassword 新密码
     * @return true or false
     */
    public function update_password($uid,$newpassword){
        if(empty($uid) || empty($newpassword)){
            return false;
        }
        $uid = intval($uid);
        $res = M('user')->where(['uid' => $uid])->find();
        foreach ($res as $k => $v){
            $salt = $v['salt'];
        }
        $password = md5(md5($newpassword).$salt);
        $data = [
            'password' => $password,
        ];
        $update_user = M('user')->where(['uid' => $uid])->save($data);
        if($update_user){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 修改个人资料
     * @param -$uid 用户uid -$nickname 用户名称 -$sex 用户性别  -$birthday 用户生日
     * @return true or false
     */
    public function update_user_info($uid,$nickname = '',$sex = '',$birthday = ''){
        if(empty($uid)){
            return false;
        }
        $sql = "update `eb_user` set ";
        if(!empty($nickname)){
            $sql .= "`nickname` = '$nickname', ";
        }
        if(!empty($sex)){
            $sql .= "`sex` = '$sex', ";
        }
        if(!empty($birthday)){
            $sql .= "`birthday` = '$birthday' ";
        }
        $sql .= rtrim($sql,',');
        $sql .= " where `uid` = '$uid'";
        $res = $this->query($sql);
        if($res){
            return true;
        }else{
            return false;
        }

    }



    /**
     * 获取用户表最后uid
     * @return array or false
     */
    public function get_last_uid(){
        $res = M('user')->order('uid desc')->limit(1)->find();
        if(!empty($res)){
            return $res[0]['uid'];
        }else{
            return false;
        }
    }




}