<?php

namespace app\admin\model;

use think\Model;


class Lvimg extends Model
{

    

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'lvimg';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'is_open_text'
    ];
    

    
    public function getIsOpenList()
    {
        return ['1' => __('Is_open 1'), '0' => __('Is_open 0')];
    }


    public function getIsOpenTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['is_open']) ? $data['is_open'] : '');
        $list = $this->getIsOpenList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
