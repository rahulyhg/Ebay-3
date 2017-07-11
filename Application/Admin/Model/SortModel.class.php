<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/9
 * Time: 10:13
 */
namespace Admin\Model;
use Think\Model;
class SortModel extends Model
{


    /**
     * 获取分类
     * @return false or array
     */
    public function get_sort_list(){
        $where = array(
            'is_visible' => '1',
            'is_del' => '0',
        );
        $data = M('sort')->where($where)->select();
        if(!empty($data) && is_array($data)){
            return $data;
        }else{
            return false;
        }

    }


    /**
     * 获取分类内容
     * @param -$objId 作品id
     * @return false or array
     */
    public function get_object_info($objId,$page,$size){
        if(empty($objId)){
            return false;
        }
        $sql = "select o.*,u.nickname,u.headimg,s.sort_title from `eb_objectlist` as o, `eb_user` as u, `eb_sort` as s
                WHERE u.uid = o.formuserid AND o.objectid = '$objId' AND o.`is_visible` = '1'
                AND s.`sortid` = o.`sort_id` 
                ORDER BY o.add_time DESC limit $page ,$size";
        $res = $this->query($sql);
        if($res){
            return $res ? $res : null;
        }else{
            return false;
        }
    }


    /**
     * 获取分类banner
     * @param -$sort_id 分类id
     * @return false or array
     */
    public function get_banner($sort_id){
        if(empty($sort_id)){
            return false;
        }
        $data = M('banner')->field('title','url','image')->where(['is_visible' => 1,'is_del' => 0])
            ->order('rank desc')->select();
        if(!empty($data) && is_array($data)){
            return $data ? $data : null;
        }else{
            return false;
        }


    }


    /**
     * 添加作品
     * @param -$uid 用户uid -$sort_id 分类id  -$time 添加时间
     */
    public function add_object($uid,$sort_id,$time){
        if(empty($uid) || empty($sort_id) || empty($time)){
            return false;
        }

    }


}
