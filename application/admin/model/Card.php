<?php

namespace app\admin\model;

use think\Model;


class Card extends Model
{

    

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'card';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'addtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'expire_time_text',
        'addtime_text'
    ];
    

    



    public function getExpireTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['expire_time']) ? $data['expire_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getAddtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['addtime']) ? $data['addtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }
    public function getActivetimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['active_time']) ? $data['active_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }
    public function getIsActiveList()
    {
        return [1 => __('Is_active 1'), 0 => __('Is_active 0')];
    }
    protected function setExpireTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setAddtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }
    protected function setUseridAttr($value)
    {
        if(strlen($value)==11){
            return db('user')->where('mobile',$value)->find()['id'];
        }
        return $value;
    }


}
