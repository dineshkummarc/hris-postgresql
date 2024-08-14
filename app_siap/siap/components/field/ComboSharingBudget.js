Ext.define('SIAP.components.field.ComboSharingBudget', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.combosharingbudget',
    fieldLabel: '',
    name: 'sharing',
    initComponent: function() {
        var me = this;
        var storesharingbudget = Ext.create('Ext.data.Store', {
            autoLoad: true,
            storeId: 'storesharingbudget',
            fields: ['pegawaiid', 'detailpengajuanid', 'tglmulai', 'tglselesai', 'kota', 'pegawaiid', 'levelid', 'nik', 'pengajuanid'],
            proxy: {
                type: 'ajax',
                url: Settings.SITE_URL + '/perjalanandinas/getSharingBudget',
                reader: {
                    type: 'json',
                    root: 'data'
                }
            }
        });
        Ext.apply(me, {
            store: storesharingbudget,
            triggerAction: 'all',
            editable: true,
            displayField: 'pegawaiid',
            valueField: 'sharingbudget',
            name: me.name
        });
        me.callParent([arguments]);
    }
});
