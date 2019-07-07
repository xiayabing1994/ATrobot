<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;
/**
 * 感谢语管理
 *
 * @icon fa fa-circle-o
 */
class Thanks extends Backend
{
    
    /**
     * Thanks模型对象
     * @var \app\admin\model\Thanks
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Thanks;
        $this->view->assign("typeList", $this->model->getTypeList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public  function adds(){
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $result = true;
                Db::startTrans();
                foreach($params['content'] as $content){
                    $data[]=['content'=>$content,'type'=>$params['type']];
                }
                $this->model->allowField(true)->saveAll($data);
                Db::commit();
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

}
