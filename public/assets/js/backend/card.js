define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            $(document).on('click','.btn-addcard', function () {
                Fast.api.open('card/addcard');
            });
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'card/index' + location.search,
                    add_url: 'card/add',
                    edit_url: 'card/edit',
                    del_url: 'card/del',                   
                    multi_url: 'card/multi',
                    table: 'card',
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
                        {field: 'card_no', title: __('Card_no')},
                        {field: 'userid', title: __('Userid')},
                        {field: 'bind_host', title: __('Bind_host')},
                        {field: 'last_day', title: __('Last_day')},
                        {field: 'expire_time', title: __('Expire_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'is_active', title: __('Is_active'), searchList: {"1":__('Is_active 1'),"0":__('Is_active 0')}, formatter: Table.api.formatter.normal},
                        {field: 'active_time', title: __('Active_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'addtime', title: __('Addtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate')},
                        {field: 'comment', title: __('Comment')},
                        {field: 'times', title: __('Times'),operate:'BETWEEN',},
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