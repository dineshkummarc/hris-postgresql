Ext.define('SIAP.modules.master.MasterBudget', {
    extend: 'Ext.grid.Panel',
    alternateClassName: 'SIAP.masterbudget',
    alias: 'widget.masterbudget',
    requires: ['SIAP.components.field.FieldPeriode'],
    initComponent: function () {
        var me = this;
        var storebudget = Ext.create('Ext.data.Store', {
            storeId: 'storebudget',
            autoLoad: true,
            pageSize: Settings.PAGESIZE,
            proxy: {
                type: 'ajax',
                url: Settings.MASTER_URL + '/c_budget/getBudget',
                actionMethods: {
                    create: 'POST',
                    read: 'POST',
                },
                reader: {
                    type: 'json',
                    root: 'data',
                    totalProperty: 'count',
                },
            },
            fields: ['codeid', 'id', 'nama', 'coa', 'avail', 'periode', 'subtotal', 'jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'agu', 'sep', 'okt', 'nov', 'des'],
            listeners: {
                beforeload: function (store) {
                    me.fireEvent('beforeload', store);
                },
            },
        });

        Ext.apply(me, {
            layout: 'fit',
            autoScroll: true,
            frame: false,
            border: true,
            loadMask: true,
            stripeRows: true,
            allowDeselect: true,
            store: storebudget,
            columns: [
                { header: 'No', xtype: 'rownumberer', width: 30 },
                { header: 'Budget Code', dataIndex: 'id', width: 80 },
                { header: 'COA', dataIndex: 'coa', width: 50 },
                { header: 'Description', dataIndex: 'nama', width: 170 },
                { header: 'Periode', dataIndex: 'periode', width: 50 },
                {
                    header: 'JAN',
                    dataIndex: 'jan',
                    width: 90,
                    renderer: (Ext.util.Format.Currency = function (v) {
                        v = Math.round((v - 0) * 100) / 100;
                        v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                        v = String(v);
                        var ps = v.split('.');
                        var whole = ps[0];
                        var sub = ps[1] ? ',' + ps[1] : '';
                        var r = /(\d+)(\d{3})/;
                        while (r.test(whole)) {
                            whole = whole.replace(r, '$1' + '.' + '$2');
                        }
                        v = whole + sub;
                        if (v.charAt(0) == '-') {
                            return '-' + v.substr(1) + '';
                        }
                        return v.replace(/\./, '.');
                    }),
                },
                {
                    header: 'FEB',
                    dataIndex: 'feb',
                    width: 90,
                    renderer: (Ext.util.Format.Currency = function (v) {
                        v = Math.round((v - 0) * 100) / 100;
                        v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                        v = String(v);
                        var ps = v.split('.');
                        var whole = ps[0];
                        var sub = ps[1] ? ',' + ps[1] : '';
                        var r = /(\d+)(\d{3})/;
                        while (r.test(whole)) {
                            whole = whole.replace(r, '$1' + '.' + '$2');
                        }
                        v = whole + sub;
                        if (v.charAt(0) == '-') {
                            return '-' + v.substr(1) + '';
                        }
                        return v.replace(/\./, '.');
                    }),
                },
                {
                    header: 'MAR',
                    dataIndex: 'mar',
                    width: 90,
                    renderer: (Ext.util.Format.Currency = function (v) {
                        v = Math.round((v - 0) * 100) / 100;
                        v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                        v = String(v);
                        var ps = v.split('.');
                        var whole = ps[0];
                        var sub = ps[1] ? ',' + ps[1] : '';
                        var r = /(\d+)(\d{3})/;
                        while (r.test(whole)) {
                            whole = whole.replace(r, '$1' + '.' + '$2');
                        }
                        v = whole + sub;
                        if (v.charAt(0) == '-') {
                            return '-' + v.substr(1) + '';
                        }
                        return v.replace(/\./, '.');
                    }),
                },
                {
                    header: 'APR',
                    dataIndex: 'apr',
                    width: 90,
                    renderer: (Ext.util.Format.Currency = function (v) {
                        v = Math.round((v - 0) * 100) / 100;
                        v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                        v = String(v);
                        var ps = v.split('.');
                        var whole = ps[0];
                        var sub = ps[1] ? ',' + ps[1] : '';
                        var r = /(\d+)(\d{3})/;
                        while (r.test(whole)) {
                            whole = whole.replace(r, '$1' + '.' + '$2');
                        }
                        v = whole + sub;
                        if (v.charAt(0) == '-') {
                            return '-' + v.substr(1) + '';
                        }
                        return v.replace(/\./, '.');
                    }),
                },
                {
                    header: 'MAY',
                    dataIndex: 'mei',
                    width: 90,
                    renderer: (Ext.util.Format.Currency = function (v) {
                        v = Math.round((v - 0) * 100) / 100;
                        v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                        v = String(v);
                        var ps = v.split('.');
                        var whole = ps[0];
                        var sub = ps[1] ? ',' + ps[1] : '';
                        var r = /(\d+)(\d{3})/;
                        while (r.test(whole)) {
                            whole = whole.replace(r, '$1' + '.' + '$2');
                        }
                        v = whole + sub;
                        if (v.charAt(0) == '-') {
                            return '-' + v.substr(1) + '';
                        }
                        return v.replace(/\./, '.');
                    }),
                },
                {
                    header: 'JUN',
                    dataIndex: 'jun',
                    width: 90,
                    renderer: (Ext.util.Format.Currency = function (v) {
                        v = Math.round((v - 0) * 100) / 100;
                        v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                        v = String(v);
                        var ps = v.split('.');
                        var whole = ps[0];
                        var sub = ps[1] ? ',' + ps[1] : '';
                        var r = /(\d+)(\d{3})/;
                        while (r.test(whole)) {
                            whole = whole.replace(r, '$1' + '.' + '$2');
                        }
                        v = whole + sub;
                        if (v.charAt(0) == '-') {
                            return '-' + v.substr(1) + '';
                        }
                        return v.replace(/\./, '.');
                    }),
                },
                {
                    header: 'JUL',
                    dataIndex: 'jul',
                    width: 90,
                    renderer: (Ext.util.Format.Currency = function (v) {
                        v = Math.round((v - 0) * 100) / 100;
                        v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                        v = String(v);
                        var ps = v.split('.');
                        var whole = ps[0];
                        var sub = ps[1] ? ',' + ps[1] : '';
                        var r = /(\d+)(\d{3})/;
                        while (r.test(whole)) {
                            whole = whole.replace(r, '$1' + '.' + '$2');
                        }
                        v = whole + sub;
                        if (v.charAt(0) == '-') {
                            return '-' + v.substr(1) + '';
                        }
                        return v.replace(/\./, '.');
                    }),
                },
                {
                    header: 'AUG',
                    dataIndex: 'agu',
                    width: 90,
                    renderer: (Ext.util.Format.Currency = function (v) {
                        v = Math.round((v - 0) * 100) / 100;
                        v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                        v = String(v);
                        var ps = v.split('.');
                        var whole = ps[0];
                        var sub = ps[1] ? ',' + ps[1] : '';
                        var r = /(\d+)(\d{3})/;
                        while (r.test(whole)) {
                            whole = whole.replace(r, '$1' + '.' + '$2');
                        }
                        v = whole + sub;
                        if (v.charAt(0) == '-') {
                            return '-' + v.substr(1) + '';
                        }
                        return v.replace(/\./, '.');
                    }),
                },
                {
                    header: 'SEP',
                    dataIndex: 'sep',
                    width: 90,
                    renderer: (Ext.util.Format.Currency = function (v) {
                        v = Math.round((v - 0) * 100) / 100;
                        v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                        v = String(v);
                        var ps = v.split('.');
                        var whole = ps[0];
                        var sub = ps[1] ? ',' + ps[1] : '';
                        var r = /(\d+)(\d{3})/;
                        while (r.test(whole)) {
                            whole = whole.replace(r, '$1' + '.' + '$2');
                        }
                        v = whole + sub;
                        if (v.charAt(0) == '-') {
                            return '-' + v.substr(1) + '';
                        }
                        return v.replace(/\./, '.');
                    }),
                },
                {
                    header: 'OCT',
                    dataIndex: 'okt',
                    width: 90,
                    renderer: (Ext.util.Format.Currency = function (v) {
                        v = Math.round((v - 0) * 100) / 100;
                        v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                        v = String(v);
                        var ps = v.split('.');
                        var whole = ps[0];
                        var sub = ps[1] ? ',' + ps[1] : '';
                        var r = /(\d+)(\d{3})/;
                        while (r.test(whole)) {
                            whole = whole.replace(r, '$1' + '.' + '$2');
                        }
                        v = whole + sub;
                        if (v.charAt(0) == '-') {
                            return '-' + v.substr(1) + '';
                        }
                        return v.replace(/\./, '.');
                    }),
                },
                {
                    header: 'NOV',
                    dataIndex: 'nov',
                    width: 90,
                    renderer: (Ext.util.Format.Currency = function (v) {
                        v = Math.round((v - 0) * 100) / 100;
                        v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                        v = String(v);
                        var ps = v.split('.');
                        var whole = ps[0];
                        var sub = ps[1] ? ',' + ps[1] : '';
                        var r = /(\d+)(\d{3})/;
                        while (r.test(whole)) {
                            whole = whole.replace(r, '$1' + '.' + '$2');
                        }
                        v = whole + sub;
                        if (v.charAt(0) == '-') {
                            return '-' + v.substr(1) + '';
                        }
                        return v.replace(/\./, '.');
                    }),
                },
                {
                    header: 'DEC',
                    dataIndex: 'des',
                    width: 90,
                    renderer: (Ext.util.Format.Currency = function (v) {
                        v = Math.round((v - 0) * 100) / 100;
                        v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                        v = String(v);
                        var ps = v.split('.');
                        var whole = ps[0];
                        var sub = ps[1] ? ',' + ps[1] : '';
                        var r = /(\d+)(\d{3})/;
                        while (r.test(whole)) {
                            whole = whole.replace(r, '$1' + '.' + '$2');
                        }
                        v = whole + sub;
                        if (v.charAt(0) == '-') {
                            return '-' + v.substr(1) + '';
                        }
                        return v.replace(/\./, '.');
                    }),
                },
                {
                    header: 'SUBTOTAL',
                    dataIndex: 'subtotal',
                    width: 90,
                    renderer: (Ext.util.Format.Currency = function (v) {
                        v = Math.round((v - 0) * 100) / 100;
                        v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                        v = String(v);
                        var ps = v.split('.');
                        var whole = ps[0];
                        var sub = ps[1] ? ',' + ps[1] : '';
                        var r = /(\d+)(\d{3})/;
                        while (r.test(whole)) {
                            whole = whole.replace(r, '$1' + '.' + '$2');
                        }
                        v = whole + sub;
                        if (v.charAt(0) == '-') {
                            return '-' + v.substr(1) + '';
                        }
                        return "<span style='font-weight:bold'>" + v.replace(/\./, '.') + '</span>';
                    }),
                },
            ],
            tbar: [
                { itemId: 'id_ketstatus_thn', xtype: 'fieldperiode', value: new Date().getFullYear() },
                {
                    glyph: 'xf002@FontAwesome',
                    handler: function () {
                        Ext.getStore('storebudget').proxy.extraParams.tahun = Ext.ComponentQuery.query('#id_ketstatus_thn')[0].getValue();
                        Ext.getStore('storebudget').load();
                    },
                },
                '->',
                {
                    glyph: 'xf196@FontAwesome',
                    text: 'Upload File',
                    handler: function () {
                        me.win_import();
                    },
                },
                {
                    glyph: 'xf044@FontAwesome',
                    text: 'Ubah',
                    handler: function () {
                        var m = me.getSelectionModel().getSelection();
                        if (m.length > 0) {
                            console.log(m[0]);
                            me.crud(m[0], '2');
                        } else {
                            Ext.Msg.alert('Pesan', 'Harap pilih data terlebih dahulu');
                        }
                    },
                },
                {
                    glyph: 'xf019@FontAwesome',
                    text: 'Format',
                    handler: function () {
                        window.open(Settings.SITE_URL + '/report/download?' + objectParametize());
                    },
                },
            ],
            listeners: {
                afterrender: function () {
                    Ext.get('id_submenu').dom.style.display = 'block';
                },
            },
        });
        me.callParent([arguments]);
    },
    crud: function (record, flag) {
        var me = this;
        var win = Ext.create('Ext.window.Window', {
            title: 'Master Budget',
            width: 400,
            closeAction: 'destroy',
            modal: true,
            layout: 'fit',
            autoScroll: true,
            autoShow: true,
            buttons: [
                {
                    text: 'Simpan',
                    handler: function () {
                        win.down('form')
                            .getForm()
                            .submit({
                                waitTitle: 'Menyimpan...',
                                waitMsg: 'Sedang menyimpan data, mohon tunggu...',
                                url: Settings.MASTER_URL + '/c_budget/crudBudget',
                                method: 'POST',
                                success: function (form, action) {
                                    var obj = Ext.decode(action.response.responseText);
                                    win.destroy();
                                    me.getStore().reload();
                                    me.getSelectionModel().deselectAll();
                                },
                                failure: function (form, action) {},
                            });
                    },
                },
                {
                    text: 'Batal',
                    handler: function () {
                        win.destroy();
                    },
                },
            ],
            items: [
                {
                    xtype: 'form',
                    waitMsgTarget: true,
                    bodyPadding: 15,
                    layout: 'anchor',
                    defaultType: 'textfield',
                    region: 'center',
                    autoScroll: true,
                    defaults: {
                        labelWidth: 100,
                        anchor: '100%',
                    },
                    items: [
                        { xtype: 'hidden', name: 'flag', value: flag },
                        {
                            xtype: 'combo',
                            store: Ext.create('Ext.data.SimpleStore', {
                                fields: ['id', 'text'],
                                data: [
                                    ['2019', '2019'],
                                    ['2020', '2020'],
                                    ['2021', '2021'],
                                    ['2022', '2022'],
                                ],
                            }),
                            fieldLabel: 'Periode',
                            triggerAction: 'all',
                            editable: false,
                            displayField: 'text',
                            valueField: 'id',
                            name: 'periode',
                            value: '2019',
                            typeAhead: false,
                            mode: 'local',
                            forceSelection: true,
                        },
                        { fieldLabel: 'Kode', name: 'id' },
                        { fieldLabel: 'Description', name: 'nama' },
                        { fieldLabel: 'Januari', name: 'jan' },
                        { fieldLabel: 'Februari', name: 'feb' },
                        { fieldLabel: 'Maret', name: 'mar' },
                        { fieldLabel: 'April', name: 'apr' },
                        { fieldLabel: 'Mei', name: 'mei' },
                        { fieldLabel: 'Juni', name: 'jun' },
                        { fieldLabel: 'Juli', name: 'jul' },
                        { fieldLabel: 'Agustus', name: 'agu' },
                        { fieldLabel: 'September', name: 'sep' },
                        { fieldLabel: 'Oktober', name: 'okt' },
                        { fieldLabel: 'November', name: 'nov' },
                        { fieldLabel: 'Desember', name: 'des', value: '120' },
                    ],
                },
            ],
        });
        if (flag == '2') {
            win.down('form').getForm().loadRecord(record);
        }
    },
    win_import: function () {
        var me = this;
        var win = Ext.create('Ext.window.Window', {
            title: 'Import Data',
            width: 430,
            closeAction: 'destroy',
            modal: true,
            layout: 'fit',
            autoScroll: true,
            autoShow: true,
            buttons: [
                {
                    text: 'Simpan',
                    handler: function () {
                        win.down('form')
                            .getForm()
                            .submit({
                                waitTitle: 'Menyimpan...',
                                waitMsg: 'Sedang menyimpan data, mohon tunggu...',
                                url: Settings.MASTER_URL + '/c_budget/get_import_file',
                                method: 'POST',
                                success: function (form, action) {
                                    var obj = Ext.decode(action.response.responseText);

                                    var winprogress = Ext.create('SIAP.components.progressbar.WinProgress', {
                                        title: 'Proses Import Data',
                                        URL: Settings.MASTER_URL + '/c_budget/proses_import_file',
                                    });
                                    var extraParams = {};
                                    winprogress.proses_exec(obj.data, extraParams);
                                    winprogress.on('finished', function (p) {
                                        me.down('grid').getStore().load();
                                    });
                                },
                                failure: function (form, action) {},
                            });
                    },
                },
                {
                    text: 'Batal',
                    handler: function () {
                        win.destroy();
                    },
                },
            ],
            items: [
                {
                    xtype: 'form',
                    waitMsgTarget: true,
                    bodyPadding: 15,
                    layout: 'anchor',
                    defaultType: 'textfield',
                    region: 'center',
                    autoScroll: true,
                    defaults: {
                        labelWidth: 100,
                        anchor: '100%',
                    },
                    items: [
                        { xtype: 'filefield', name: 'dokumen', fieldLabel: 'Dokumen', allowBlank: false, buttonText: 'Pilih File ...' },
                        /*{ xtype: 'box', fieldLabel: 'label',
						  autoEl: {tag: 'a', href: Settings.SITE_URL + "/c_targetvisitor/cetak/template", children: [{tag: 'div', html: 'Download Template'}]},
						  style: 'cursor:pointer;'
						}*/
                    ],
                },
            ],
        });
    },
    hapus: function (record) {
        var me = this;
        var params = [];
        Ext.Array.each(record, function (rec, i) {
            var temp = {};
            temp.id = rec.get('id');
            params.push(temp);
        });
        Ext.Msg.show({
            title: 'Konfirmasi',
            msg: 'Apakah anda yakin akan menghapus data ?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            fn: function (btn) {
                if (btn == 'yes') {
                    Ext.Ajax.request({
                        url: Settings.MASTER_URL + '/c_budget/hapus',
                        method: 'POST',
                        params: {
                            params: Ext.encode(params),
                        },
                        success: function (response) {
                            var obj = Ext.decode(response.responseText);
                            Ext.Msg.alert('Informasi', obj.message);
                            me.getStore().load();
                            me.getSelectionModel().deselectAll();
                        },
                    });
                }
            },
        });
    },
});
