<?php

namespace app\admin\model;

use think\Model;


class Present extends Model
{

    

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'present';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'is_sale_text',
        'is_effect_text',
        'is_notice_text',
        'is_thanks_text',
        'is_whole_text'
    ];
    

    
    public function getIsSaleList()
    {
        return ['是' => __('Is_sale 是'), '否' => __('Is_sale 否')];
    }

    public function getIsEffectList()
    {
        return ['是' => __('Is_effect 是'), '否' => __('Is_effect 否')];
    }

    public function getIsNoticeList()
    {
        return ['是' => __('Is_notice 是'), '否' => __('Is_notice 否')];
    }

    public function getIsThanksList()
    {
        return ['是' => __('Is_thanks 是'), '否' => __('Is_thanks 否')];
    }

    public function getIsWholeList()
    {
        return ['是' => __('Is_whole 是'), '否' => __('Is_whole 否')];
    }


    public function getIsSaleTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['is_sale']) ? $data['is_sale'] : '');
        $list = $this->getIsSaleList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsEffectTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['is_effect']) ? $data['is_effect'] : '');
        $list = $this->getIsEffectList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsNoticeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['is_notice']) ? $data['is_notice'] : '');
        $list = $this->getIsNoticeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsThanksTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['is_thanks']) ? $data['is_thanks'] : '');
        $list = $this->getIsThanksList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsWholeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['is_whole']) ? $data['is_whole'] : '');
        $list = $this->getIsWholeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
