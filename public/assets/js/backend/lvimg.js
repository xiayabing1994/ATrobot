define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'lvimg/index' + location.search,
                    add_url: 'lvimg/add',
                    edit_url: 'lvimg/edit',
                    del_url: 'lvimg/del',
                    multi_url: 'lvimg/multi',
                    table: 'lvimg',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'lv_grade', title: __('Lv_grade')},
                        {field: 'lv_img', title: __('Lv_img')},
                        {field: 'lv_response', title: __('Lv_response')},
                        {field: 'lv_image', title: __('Lv_image'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'is_open', title: __('Is_open'), searchList: {"1":__('Is_open 1'),"0":__('Is_open 0')}, formatter: Table.api.formatter.normal},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});