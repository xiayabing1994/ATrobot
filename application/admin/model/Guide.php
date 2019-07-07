<?php

namespace app\admin\model;

use think\Model;


class Guide extends Model
{

    

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'guide';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'g_type_text',
        'status_text'
    ];
    

    
    public function getGTypeList()
    {
        return ['ad' => __('G_type ad'), 'topic' => __('G_type topic'), 'new' => __('G_type new'), 'pk' => __('G_type pk')];
    }

    public function getStatusList()
    {
        return ['1' => __('Status 1'), '0' => __('Status 0')];
    }


    public function getGTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['g_type']) ? $data['g_type'] : '');
        $list = $this->getGTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
