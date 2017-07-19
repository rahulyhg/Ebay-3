<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/9
 * Time: 10:01
 */
namespace Admin\Controller;
use Admin\Model\SortModel;
use Think\Controller;
class SortController extends Controller
{


    /**
     * 获取分类标签
     */
    public function get_sort_list(){
        $data = array();
        $param = $_GET ? $_GET : "";
        $uid = $param['uid'] ? $param['uid'] : "";
        $token = $param['token'] ? $param['token'] : "";
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        $sortmodel = new SortModel();
        $get_sort_list = $sortmodel->get_sort_list();
        if(!empty($get_sort_list) && is_array($get_sort_list)){
            $data['code'] = "200";
            $data['msg'] = "获取成功！";
            $data['body']['sort_list'] = $get_sort_list;
        }else{
            $data['code'] ='202';
            $data['msg'] = "获取失败！";
        }
        return $this->_array_to_json($data);

    }


    /**
     * 获取分类内容(前20条最新发布)
     */
    public function sort_content(){
        $data = array();
        $param = $_GET ? $_GET : "";
        $uid = $param['uid'] ? $param['uid'] : "";
        $token = $param['token'] ? $param['token'] : "";
        $objId = $param['id'] ? $param['id'] : "";
        $page = 0;
        $size = 20;
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        if(empty($objId)){
            $data['code'] = "202";
            $data['msg'] = "参数错误！";
            return $this->_array_to_json($data);
        }
        $sortmodel = new SortModel();
        $get_object_info = $sortmodel->get_object_info($objId,$page,$size);
        if($get_object_info){
            $data['code'] = '200';
            $data['msg'] = "获取数据成功！";
            $data['body']['object_list'] = $get_object_info;
        }else{
            $data['code'] = "202";
            $data['msg'] = "获取数据失败！";
        }
        return $this->_array_to_json($data);

    }


    /**
     * 获取分类更多内容
     */
    public function more_sort_content(){
        $data = array();
        $param = $_GET ? $_GET : "";
        $uid = $param['uid'] ? $param['uid'] : "";
        $token = $param['token'] ? $param['token'] : "";
        $sort_id = $param['id'] ? $param['id'] : "";
        $page = $param['page'] ? $param['page'] : "";
        $size = 20;
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        $sortmodel = new SortModel();
        $get_more_content = $sortmodel->get_object_info($sort_id,$page,$size);
        if(!empty($get_more_content) && is_array($get_more_content)){
            $data['code'] = "200";
            $data['msg'] = "获取数据成功！";
            $data['body']['object_list'] = $get_more_content;
        }else{
            $data['code'] = "202";
            $data['msg'] = "获取数据失败！";
        }
        return $this->_array_to_json($data);
    }


    /**
     * 获取分类banner
     */
    public function get_sort_banner(){
        $data = array();
        $sort_id = $_GET['id'] ? $_GET['id'] : "";
        $uid = $_GET['uid'] ? $_GET['uid'] : "";
        $token = $_GET['token'] ? $_GET['token'] : "";
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        $sortmodel = new SortModel();
        $get_sort_banner = $sortmodel->get_banner($sort_id);
        if($get_sort_banner){
            $data['code'] = "200";
            $data['msg'] = "获取数据成功！";
            $data['body']['banner_info'] = $get_sort_banner;
        }else{
            $data['code'] = "202";
            $data['msg'] = "获取数据失败！";
        }
        return $this->_array_to_json($data);
    }



    /**
     * 添加作品
     * @param $sort_id = 1 => 图片   $sort_id => 2 音乐
     */
    #TODO:发布尚未处理
    public function add_object(){
        $data = array();
        $uid = $_POST['uid'] ? $_POST['uid'] : "";
        $token = $_POST['token'] ? $_POST['token'] : "";
        $sort_id = $_POST['id'] ? $_POST['id'] : "";
        //$files = $_POST['files'] ? $_POST['files'] : "";
        $descrption = $_POST['text'] ? $_POST['text'] : "";
        $label = $_POST['label'] ? $_POST['label'] : "";
        $user_position = $_POST['position'] ? $_POST['position'] : "";
        $time = time();
        $descrption = addslashes($descrption);
        if(!check_token($uid,$token)){
            $data['code'] = "202";
            $data['msg'] = "身份验证失败！";
            return $this->_array_to_json($data);
        }
        if($sort_id == 1){
            //$file = upload_all('files','3145728','0');
            $file = upload_file($_FILES['files']);
        }else if ($sort_id == 2){
            $file = upload_all('files','3145728','0');
        }
        $sortmodel = new SortModel();
        $add_object = $sortmodel->add_object($uid,$sort_id,$time,$file,$descrption,$label,$user_position);
        if($add_object){
            $data['code'] = "200";
            $data['msg'] = "发表成功！";
        }else{
            $data['code'] = "202";
            $data['msg'] = "发表失败！";
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