Ext.define('SIAP.components.field.ComboStatusDailyreport', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.combostatusdailyreport',
    fieldLabel: '',
    name: 'status',
    initComponent: function () {
        var me = this;
        var storemstatusdailyreport = Ext.create('Ext.data.Store', {
            autoLoad: true,
            storeId: 'storemstatusdailyreport',
            fields: ['id', 'text'],
            proxy: {
                type: 'ajax',
                url: Settings.MASTER_URL + '/c_statusdailyreport/getStatusDailyreport',
                reader: {
                    type: 'json',
                    root: 'data',
                },
            },
        });
        Ext.apply(me, {
            store: storemstatusdailyreport,
            triggerAction: 'all',
            editable: true,
            displayField: 'text',
            valueField: 'id',
            name: me.name,
        });
        me.callParent([arguments]);
    },
});
