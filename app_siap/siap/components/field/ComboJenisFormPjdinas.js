Ext.define('SIAP.components.field.ComboJenisFormPjdinas', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.combojenisformpjdinas',
    fieldLabel: '',
    name: 'status',
    initComponent: function () {
        var me = this;
        var storejenisform = Ext.create('Ext.data.Store', {
            autoLoad: true,
            storeId: 'storejenisformjdinas',
            fields: ['id', 'text'],
            proxy: {
                type: 'ajax',
                url: Settings.MASTER_URL + '/c_statuspjdinas/getJenisForm',
                reader: {
                    type: 'json',
                    root: 'data',
                },
            },
        });
        Ext.apply(me, {
            store: storejenisform,
            triggerAction: 'all',
            editable: true,
            displayField: 'text',
            valueField: 'id',
            name: me.name,
        });
        me.callParent([arguments]);
    },
});
