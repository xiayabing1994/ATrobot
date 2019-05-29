define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'present/index' + location.search,
                    add_url: 'present/add',
                    edit_url: 'present/edit',
                    del_url: 'present/del',
                    multi_url: 'present/multi',
                    table: 'present',
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
                        {field: 'present_name', title: __('Present_name')},
                        {field: 'present_url', title: __('Present_url'), formatter: Table.api.formatter.url},
                        {field: 'present_cate', title: __('Present_cate')},
                        {field: 'present_image', title: __('Present_image'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'value', title: __('Value')},
                        {field: 'describe', title: __('Describe')},
                        {field: 'is_effect', title: __('Is_effect'), searchList: {"是":__('Is_effect 是'),"否":__('Is_effect 否')}, formatter: Table.api.formatter.normal},
                        {field: 'is_notice', title: __('Is_notice'), searchList: {"是":__('Is_notice 是'),"否":__('Is_notice 否')}, formatter: Table.api.formatter.normal},
                        {field: 'is_thanks', title: __('Is_thanks'), searchList: {"是":__('Is_thanks 是'),"否":__('Is_thanks 否')}, formatter: Table.api.formatter.normal},
                        {field: 'is_whole', title: __('Is_whole'), searchList: {"是":__('Is_whole 是'),"否":__('Is_whole 否')}, formatter: Table.api.formatter.normal},
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