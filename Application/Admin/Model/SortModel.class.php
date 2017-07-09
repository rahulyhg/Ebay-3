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


}
