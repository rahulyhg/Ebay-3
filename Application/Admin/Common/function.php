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




/**
 * 公共上传文件
 */
function upload_file($file){
    $upload = new \Think\Upload();
    $upload->maxSize = 3145728;
    $upload->exts = array('jpg','gif','png','jpeg');
    $upload->rootPath = './Public/Upload/headimg/';
    $upload->savePath = '';
    $upload->autoSub = false;
    $info = $upload->uploadOne($file);
    if(!$info){
        return false;
    }else{
        return $upload->rootPath.$info['savename'];
    }
}


/**
 * 上传所有格式文件
 */
function upload_all($name,$maxSize,$type = 0){
    switch ($type){
        case 0:
            $arr = array('jpg','jpeg','png','gif');
            break;
        case 1:
            $arr = array('mp3','ogg');
            break;
        case 2:
            $arr = array('rmvb');
            break;
    }
    if(is_array($_FILES[$name]['name'])){
        $nameArr = $_FILES[$name]['name'];
        $objArr = $_FILES[$name]['tmp_name'];
        $sizeArr = $_FILES[$name]['size'];
        $arr_return = array();
        for ($i = 0; $i < count($nameArr); $i++){
            if(!$nameArr[$i]){
                continue;
            }
            $_arr = explode('.',$nameArr[$i]);
            $lastTime = end($_arr);
            if(in_array($lastTime,$arr) && $sizeArr[$i] <= $maxSize){
                $savrName = 'uploads/'.uniqid().'.'.$lastTime;
                move_uploaded_file($objArr[$i],$savrName);
                array_push($arr_return,$savrName);
            }
        }
        return $arr_return;
    }else{
        $_name = $_FILES[$name]['name'];
        $size = $_FILES[$name]['size'];
        $obj = $_FILES[$name]['tem_name'];
        $_arrName = explode('.',$_name);
        $_lastName = end($_arrName);
        if(in_array($_lastName,$arr) && $size <= $maxSize){
            $saveName = C('_UPLOAD_').uniqid().'.'.$_lastName;
            move_uploaded_file($obj,$saveName);
        }
        return $saveName;
    }




}