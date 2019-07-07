<?php

namespace app\admin\model;

use think\Model;


class Thanks extends Model
{

    

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'thanks';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'type_text'
    ];
    

    
    public function getTypeList()
    {
        return ['lightup' => __('Type lightup'), 'focus' => __('Type focus'), 'present' => __('Type present'), 'huojiang' => __('Type huojiang')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
