<?php

namespace app\admin\model;

use think\Model;


class RankThanks extends Model
{

    

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'rank_thanks';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'type_text',
        'rank_text'
    ];
    

    
    public function getTypeList()
    {
        return ['week' => __('Type week'), 'scene' => __('Type scene')];
    }

    public function getRankList()
    {
        return ['0' => __('Rank 0'), '1' => __('Rank 1'), '2' => __('Rank 2'), '3' => __('Rank 3'), '4' => __('Rank 4'), '5' => __('Rank 5')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getRankTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['rank']) ? $data['rank'] : '');
        $list = $this->getRankList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
