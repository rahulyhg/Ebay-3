<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/8
 * Time: 11:38
 */
namespace Admin\Controller;
use Admin\Model\UserModel;
use Org\Email;
use Org\QQ;
use Think\Controller;
class LoginController extends Controller
{

    const SECURE_KEY = "pKKDTKPHq5qBX5jT@!=";


    /**
     * 用户手机发送短信验证码
     */
    public function phone_code(){
        $data = array();
        $param = $_GET ? $_GET : "";
        $moblie = $param['phone'] ? $param['phone'] : "";
        if(empty($moblie)){
            $data['code'] = "202";
            $data['msg'] = "参数错误！";
            return $this->_array_to_json($data);
        }
        $code = $this->Send_code($moblie);
        if(is_array($data) && !empty($data)){
            $data['code'] = "200";
            $data['msg'] = "验证码获取成功！";
            $data['body'] = $code;
        }else{
            $data['code'] = "202";
            $data['msg'] = "验证码获取失败！";
        }
        return $this->_array_to_json($data);


    }


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
            return $this->_array_to_json($data);
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
        return $this->_array_to_json($data);

    }


    /**
     * 用户登录
     */
    #TODO:用户登录三种方式登录
    public function login_in(){
        $param = $_POST ? $_POST : "";
        if(empty($param)){
            $data['code'] = "202";
            $data['msg'] = "参数错误!";
            return $this->_array_to_json($data);
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
            $data['body']['info'] = $user_info;
        }else{
            $data['code'] = "202";
            $data['msg'] = "登录失败!";
            $data['body']['info'] = "";
        }
        return $this->_array_to_json($data);

    }


    /**
     * qq三方登录
     */
    public function QQ_login(){
        $app_id = C('qq_app_id');
        $app_key = C('qq_app_key');
        $callBach_url = C('qq_callback_url');
        $qq_obj = new QQ($app_id,$app_key,$callBach_url);
        $qq_obj->getAuthCode();
    }


    /**
     * qq登录回调
     */
    #TODO:数据库暂未处理
    public function callback(){
        $data = array();
        $app_id = C('qq_app_id');
        $app_key = C('qq_app_key');
        $callBach_url = C('qq_callback_url');
        $Qqconnect = new QQ($app_id,$app_key,$callBach_url);
        $openid = $Qqconnect->getOpenId();
        $qq = session('qq');
        $map = array();
        $map['openid'] = $openid;
        $userInfo = M('Member')->where($map)->find();
        if(!empty($userInfo)){
            //$this->success('登陆成功！',U('Member/index'));
            $data['code'] = "200";
            $data['msg'] = "登陆成功！";
        }else {
            $userInfo = $Qqconnect->getInfo($qq['openid'], $qq['access_token']);
            if($userInfo){
                $data['code'] = "200";
                $data['msg'] = "登陆成功！";
                $data['body']['info'] = $userInfo;
            }else{
                $data['code'] = "202";
                $data['msg'] = "登录失败！";
            }
        }
        return $this->_array_to_json($data);
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
            return $this->_array_to_json($data);
        }
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败,请重新登录！";
            return $this->_array_to_json($data);
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
        return $this->_array_to_json($data);


    }


    /**
     * 修改个人信息
     */
    public function update_user_info(){
        $data = array();
        $param = $_POST ? $_POST : "";
        $uid = $param['uid'] ? $param['uid'] : "";
        $nickname = $param['nickname'] ? $param['nickname'] : "";
        $sex = $param['sex'] ? $param['sex'] : "";
        $birthday = $param['birthday'] ? $param['birthday'] : "";
        $token = $param['token'] ? $param['token'] : "";
        $birthday = strtotime($birthday);
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        if(empty($uid)){
            $data['code'] = "202";
            $data['msg'] = "参数错误！";
            return $this->_array_to_json($data);
        }
        $usermode = new UserModel();
        $update_user_info = $usermode->update_user_info($uid,$nickname,$sex,$birthday);
        if($update_user_info){
            $data['code'] = "200";
            $data['msg'] = "修改成功！";
            $data['body'] = array(
                'uid' => $uid
            );
        }else{
            $data['code'] = "202";
            $date['msg'] = "修改失败！";
        }
        return $this->_array_to_json($data);

    }




    /**
     * 用户退出
     */
    public function login_out(){
        $data = array();
        $param = $_GET ? $_GET : "";
        $uid = $param['uid'] ? $param['uid'] : "";
        if(empty($uid)){
            $data['code'] = "202";
            $data['msg'] = "参数错误！";
            return $this->_array_to_json($data);
        }
        $setdata = array(
            'is_login' => '0'
        );
        $update_user = M('user')->where('uid',$uid)->save($setdata);
        if($update_user){
            $data['code'] = "200";
            $data['msg'] = "退出成功!";
        }else{
            $data['code'] = "202";
            $data['msg'] = "退出失败！";
        }
        return $this->_array_to_json($data);
        
    }


    /**
     * 修改头像
     */
    public function update_headimg(){
        $data = array();
        $param = $_POST ? $_POST : "";
        $uid = $param['uid'] ? $param['uid'] : "";
        $token = $param['token'] ? $param['token'] : "";
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        $upload_file = upload_file($_FILES['files']);
        if($upload_file){
            $data['code'] = "200";
            $data['msg'] = "修改成功！";
            $data['body']['headimg'] = $upload_file;
        }else{
            $data['code'] = "202";
            $data['msg'] = "修改失败！";
        }
        return $this->_array_to_json($data);
    }




    /**
     * 互亿无线api接口
     * 发送短信验证
     * @param -$mobile 用户手机号
     * @return array;
     */
    public function Send_code($mobile){
        $data = array();
        //短信接口地址
        $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
        //6位随机验证码
        $mobile_code = $this->random(6,1);
        $post_data = "account=C54085965&password=c4ffd409e7f85abf00d7a8c4dbfb653f&mobile=";
        $post_data .= $mobile."&content=".rawurlencode("您的验证码是：".$mobile_code."。请不要把验证码泄露给其他人。");
        $gets = $this->xml_to_array($this->_post($post_data, $target));
        if($gets['SubmitResult']['code']==2){
            $data = array(
                'mobile' => $mobile,
                'mobile_code' => $mobile_code,
            );
        }
        return $data;
    }



    /**
     * 发送邮件验证码
     * @param -$email 用户邮箱
     * @retuen  false or code
     */
    public function Smtp_code($email){
        //SMTP服务器
        $smtpserver = "smtp.qq.com";
        //SMTP服务器端口
        $smtpserverport = C('smtp_port');
        //SMTP服务器的用户邮箱
        $smtpusermail = C('user');
        //发送给谁
        $smtpemailto = $email;
        //SMTP服务器的用户帐号
        $smtpuser = $smtpusermail;
        //SMTP服务器的用户密码
        $smtppass = C('pass');
        //邮件主题
        $mailsubject = C('title');
        //邮件内容
        $code = $this->random(6,1);
        $mailbody = "<h1>欢迎进入易趣，您的验证码为:".$code."。</h1>";
        //邮件格式（HTML/TXT）,TXT为文本邮件
        $mailtype = "HTML";
        //这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp = new Email($smtpserver, $smtpserverport, true, $smtpuser, $smtppass);
        $smtp->debug = false;//是否显示发送的调试信息
        $send_code = $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
        if($send_code){
            return $code ? $code : "";
        }else{
            return false;
        }


    }



    /**
     * 发送post请求
     * @param -$url  请求地址  -$curlPost
     */
    public function _post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_HEADER,false);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_NOBODY,true);
        curl_setopt($curl,CURLOPT_POST,true);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }


    /**
     * 将xml数据转换为数组格式
     * @param -$xml xml格式数据
     * @return array
     */
    public function xml_to_array($xml){
        $reg = "/<(\w+)[^-->]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
                $subxml= $matches[2][$i];
                $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = xml_to_array( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }


    /**
     * 获取随机整数
     */

    public function random($length = 6 , $numeric = 0) {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }




    /**
     * 发送短信
     */
    public function sms(){
        session_start();
        header("Content-type:text/html; charset=UTF-8");
        //短信接口地址
        $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
        //获取手机号
        $mobile = $_GET['mobile'];
        //获取验证码
        $send_code = $_GET['send_code'];
        //生成的随机数
        $mobile_code = $this->random(4,1);
        if(empty($mobile)){
            exit('手机号码不能为空');
        }
        //防用户恶意请求
        if(empty($_SESSION['send_code']) or $send_code!=$_SESSION['send_code']){
            exit('请求超时，请刷新页面后重试');
        }
        $post_data = "account=C54085965&password=c4ffd409e7f85abf00d7a8c4dbfb653f&mobile=".$mobile."&content=".rawurlencode("您的验证码是：".$mobile_code."。请不要把验证码泄露给其他人。");
        //用户名是登录ihuyi.com账号名（例如：cf_demo123）
        //查看密码请登录用户中心->验证码、通知短信->帐户及签名设置->APIKEY
        $gets =  $this->xml_to_array($this->_post($post_data, $target));
        if($gets['SubmitResult']['code']==2){
            $_SESSION['mobile'] = $mobile;
            $_SESSION['mobile_code'] = $mobile_code;
        }
        echo $gets['SubmitResult']['msg'];
    }


    /**
     * 转换为json格式输出
     */
    public function _array_to_json($data){
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