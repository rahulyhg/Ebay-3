<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/13
 * Time: 10:58
 */
namespace Admin\Model;
use Think\Model;
class CommentModel extends Model
{

    /**
     * 写入评论
     * @param -$param 数据集合
     * @return false or true
     */
    public function add_comment($param){
        if(empty($param) || !is_array($param)){
            return false;
        }
        $res = M('comments')->add($param);
        if($res){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 获取评论
     * @param -$obj_id 作品id
     * @return false or array
     */
    public function get_comments_info($obj_id){
        if(empty($obj_id)){
            return false;
        }
        $where = array(
            'uid' => $obj_id,
            'is_visible' => 1,
        );
//        $data = M('comments')->where($where)->order('add_time desc')->select();
        $sql = "select c.*,u.headimg,u.nickname from `eb_comments` as c , `eb_user` as u 
                WHERE c.uid = u.uid AND c.objectid = '$obj_id' AND c.is_visible = '1'
                ORDER BY c.add_time DESC ";
        $data = $this->query($sql);
        if(!empty($data) && is_array($data)){
            return $data;
        }else{
            return false;
        }
    }


}