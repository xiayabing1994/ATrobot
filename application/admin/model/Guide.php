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
        'g_cate_text',
        'status_text'
    ];
    

    
    public function getGCateList()
    {
        return ['ad' => __('G_cate ad'), 'topic' => __('G_cate topic'), 'new' => __('G_cate new'), 'pk' => __('G_cate pk')];
    }

    public function getStatusList()
    {
        return ['1' => __('Status 1'), '0' => __('Status 0')];
    }


    public function getGCateTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['g_cate']) ? $data['g_cate'] : '');
        $list = $this->getGCateList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
