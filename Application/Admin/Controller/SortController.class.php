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
     * 获取分类
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
            $data['body'] = $get_sort_list;
        }else{
            $data['code'] ='202';
            $data['msg'] = "获取失败！";
        }
        return $this->_array_to_json($data);

    }




    /**
     * 数组转换为json格式
     * @param -$data 需要转换的数组
     * @return  false or json;
     */
    public function _array_to_json($data){
        if(empty($data) && !is_array($data)){
            return false;
        }
        $data = json_encode($data);
        return $data;
    }




}