<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/19
 * Time: 11:13
 */
namespace Admin\Model;
use Think\Model;
class AttentionModel extends Model
{

    /**
     * 获取关注会员的相关作品
     * @param -$uid 会员uid
     * @return false or array
     */
    public function get_attention_list($uid){
        if(empty($uid)){
            return false;
        }
        //$where = array(
            //'a.uid' => $uid,
            //'O.is_visible' => 1,
        //);
        //$data = M('attention as a')->join('eb_objectlist as o ON o.uid = a.be_uid')->where($where)->select();
        $sql = "select O.* from `eb_attention` as a, `eb_objectlist` as O 
                WHERE a.uid = '$uid' AND a.be_uid = O.uid AND O.is_visible = 1 
                ORDER BY O.add_time DESC ";
        $res = $this->query($sql);
        if($res){
            return $res ? $res : null;
        }else{
            return false;
        }
    }


    /**
     * 取消关注
     * @param -$uid 当前登录uid  -$be_uid  被关注uid
     * @return true or false
     */
    public function del_be_uid($uid,$be_uid){
        if(empty($uid) || empty($be_uid)){
            return false;
        }
        $where = array(
            'uid' => $uid,
            'be_uid' => $be_uid
        );
        $res = M('attention')->where($where)->delete();
        if($res){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 点赞
     * @param -$uid 会员uid  -$obj_id 作品id
     * @return true or false
     */
    public function add_like($uid,$obj_id){
        if(empty($uid) || empty($obj_id)){
            return false;
        }
        $where = array(
            'objectid' => $obj_id,
            'formuserid' => $uid,
        );
        $res = M('objectlist')->where($where)->setInc('Fabulous_num',1);
        if($res){
            return true;
        }else{
            return false;
        }

    }


    /**
     * 取消点赞
     * @param -$uid 会员uid  -$obj_id 作品id
     * @return true or false
     */
    public function del_like($uid,$obj_id){
        if(empty($uid) || empty($obj_id)){
            return false;
        }
        $where = array(
            'objectid' => $obj_id,
            'formuserid' => $uid,
        );
        $res = M('objectlist')->where($where)->setDec('Fabulous_num',1);
        if($res){
            return true;
        }else{
            return false;
        }
    }


}