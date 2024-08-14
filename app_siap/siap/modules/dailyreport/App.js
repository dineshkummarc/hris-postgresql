Ext.define('SIAP.modules.dailyreport.App', {
    extend: 'Ext.panel.Panel',
    alternateClassName: 'SIAP.dailyreport',
    alias: 'widget.dailyreport',
    requires: ['SIAP.components.tree.UnitKerja', 'SIAP.modules.dailyreport.GridDailyreport', 'SIAP.components.field.ComboStatusDailyreport', 'SIAP.components.progressbar.WinProgress'],
    initComponent: function () {
        var me = this;
        Ext.apply(me, {
            layout: 'border',
            items: [
                {
                    region: 'west',
                    title: 'Daftar Unit Kerja',
                    collapsible: true,
                    collapsed: false,
                    layout: 'fit',
                    border: false,
                    resizable: { dynamic: true },
                    items: [
                        {
                            xtype: 'unitkerja',
                            width: 200,
                            border: false,
                            listeners: {
                                itemclick: function (a, b, c) {
                                    var tglmulai = Ext.Date.format(me.down('#id_tglmulai').getValue(), 'd/m/Y');
                                    var tglselesai = Ext.Date.format(me.down('#id_tglselesai').getValue(), 'd/m/Y');

                                    me.down('#id_griddailyreport').getStore().proxy.extraParams.tglmulai = tglmulai;
                                    me.down('#id_griddailyreport').getStore().proxy.extraParams.tglselesai = tglselesai;
                                    me.down('#id_griddailyreport').getStore().proxy.extraParams.satkerid = b.get('id');
                                    me.down('#id_griddailyreport').getStore().loadPage(1);
                                },
                            },
                        },
                    ],
                },
                {
                    itemId: 'id_griddailyreport',
                    xtype: 'GridDailyreport',
                    region: 'center',
                    frame: true,
                    listeners: {
                        beforeload: function (store) {
                            var satkerid = '';
                            var lokasiid = me.down('#id_unitkerja').getStore().proxy.extraParams.lokasiid;
                            var tglmulai = me.down('#id_tglmulai');
                            tglmulai = Ext.isEmpty(tglmulai.getValue()) ? '' : Ext.Date.format(tglmulai.getValue(), 'Y-m-d');
                            var tglselesai = me.down('#id_tglselesai');
                            tglselesai = Ext.isEmpty(tglselesai.getValue()) ? '' : Ext.Date.format(tglselesai.getValue(), 'Y-m-d');

                            store.proxy.extraParams.lokasiid = lokasiid;
                            store.proxy.extraParams.tglmulai = tglmulai;
                            store.proxy.extraParams.tglselesai = tglselesai;
                        },
                    },
                    tbar: [
                        { itemId: 'id_tglmulai', xtype: 'datefield', fieldLabel: 'Periode', format: 'd/m/Y', name: 'tglmulai', value: moment().startOf('month').format('DD/MM/YYYY'), emptyText: 'Tgl Awal', labelWidth: 40, style: 'margin-left:10px;' },

                        { itemId: 'id_tglselesai', xtype: 'datefield', fieldLabel: 's/d', format: 'd/m/Y', name: 'tglselesai', value: moment().endOf('month').format('DD/MM/YYYY'), emptyText: 'Tgl Akhir', labelWidth: 20, style: 'margin-left:5px;' },
                        '-',
                        {
                            glyph: 'xf002@FontAwesome',
                            handler: function () {
                                me.down('#id_griddailyreport').getStore().load();
                            },
                        },
                        '->',
                        {
                            glyph: 'xf02f@FontAwesome',
                            text: 'Cetak',
                            handler: function () {
                                var m = me.down('#id_griddailyreport').getStore().proxy.extraParams;
                                window.open(Settings.SITE_URL + '/dailyreport/cetakdokumen?' + objectParametize(m));
                            },
                        },
                    ],
                },
            ],
        });
        me.callParent([arguments]);
    },
});
