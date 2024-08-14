Ext.define('SIAP.components.field.ComboStatusPjdinas', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combostatuspjdinas',
	fieldLabel: '',
	name: 'status',
	initComponent	: function() {	
		var me = this;	
		var storemstatuscuti = Ext.create('Ext.data.Store', {
			autoLoad : true,
			storeId: 'storemstatuspjdinas',
			fields: ['id','text'],
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_statuspjdinas/getStatusPjdinas',
				reader: {
					type: 'json',
					root:'data'
				}
			},
		});		
		Ext.apply(me,{		
			store: storemstatuscuti,
			triggerAction : 'all',
			editable : true,
			displayField: 'text',
			valueField: 'id',
			name: me.name,		
		});		
		me.callParent([arguments]);
	},
});