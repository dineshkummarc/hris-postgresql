Ext.define('SIAP.modules.dailyreport.GridDailyreport', {
    extend: 'Ext.grid.Panel',
    alternateClassName: 'SIAP.GridDailyreport',
    alias: 'widget.GridDailyreport',
    initComponent: function () {
        var me = this;
        me.addEvents({ beforeload: true });
        var storedailyreport = Ext.create('Ext.data.Store', {
            storeId: 'storedailyreport',
            autoLoad: true,
            pageSize: Settings.PAGESIZE,
            proxy: {
                type: 'ajax',
                url: Settings.SITE_URL + '/dailyreport/getListDailyreport',
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
            fields: ['pengajuanid', 'pegawaiid', 'nourut', 'tglmulai', 'nik', 'nama', 'direktorat', 'divisi', 'departemen', 'seksi', 'subseksi', 'actjob', 'keterangan', 'atasannotes', 'status', 'statusid'],
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
            store: storedailyreport,
            columns: [
                { header: 'No', xtype: 'rownumberer', width: 30 },
                { header: 'Tgl Report', dataIndex: 'tglmulai', width: 80 },
                { header: 'NIK', dataIndex: 'nik', width: 80 },
                { header: 'Nama', dataIndex: 'nama', width: 150 },
                {
                    header: 'Unit',
                    align: 'left',
                    columns: [
                        { header: 'Direktorat', dataIndex: 'direktorat', width: 120 },
                        { header: 'Divisi', dataIndex: 'divisi', width: 120 },
                        { header: 'Departemen', dataIndex: 'departemen', width: 120 },
                        { header: 'Seksi', dataIndex: 'seksi', width: 120 },
                        { header: 'Sub Seksi', dataIndex: 'subseksi', width: 120 },
                    ],
                },
                { header: 'Activity', dataIndex: 'actjob', width: 120 },
                { header: 'Keterangan', dataIndex: 'keterangan', width: 120 },
                { header: 'Note Atasan', dataIndex: 'atasannotes', width: 120 },
                { header: 'Status', dataIndex: 'status', width: 120 },
                {
                    header: '',
                    width: 30,
                    renderer: function (value, meta, record, index) {
                        return '<a onclick=detaildailyreport("' + record.data.pegawaiid + '","' + record.data.nourut + '","' + record.data.statusid + '")  style="cursor:pointer;"><span class="black-icon-brand"><i class="fa fa-external-link" aria-hidden="true"></i></span></a>';
                    },
                },
            ],
            bbar: Ext.create('Ext.toolbar.Paging', {
                displayInfo: true,
                height: 35,
                store: 'storedailyreport',
            }),
        });
        me.callParent([arguments]);
    },
});

function detaildailyreport(pegawaiid, nourut, statusid) {
    Ext.History.add('#dailyreport&detaildailyreport&' + Base64.encode(pegawaiid) + '#' + Base64.encode(nourut) + '#' + Base64.encode(statusid));
}
