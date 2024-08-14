Ext.define("SIAP.components.tree.UnitKerja",{
	extend: "Ext.tree.Panel",
	alternateClassName: "SIAP.unitkerja" ,
	alias: 'widget.unitkerja',
	rootTitle: 'Electronic City',
	onlySatker: true,
	initComponent:function(){
		var me = this;		
		me.addEvents({"itemselected": true});
		
		var store = Ext.create('Ext.data.TreeStore', {
			proxy: {
				type: 'ajax',
				actionMethods: {
					read: 'POST'
				},
				reader:{
					root: 'data',
					totalProperty: 'count'
				},
				url: Settings.MASTER_URL + '/c_unitkerja/get_unitkerja'
			},
			fields: [
				'id', 'text', 'direktorat', 'divisi', 'departemen', 'seksi', 'subseksi', 'kepalanama'
			],
			root: {
				text: me.rootTitle,
				id: '0',
				expanded: false,
				draggable: false,
			},
			listeners: {
				load: function(s,record,succeses){
					// var node = s.getNodeById('01');
					// console.log('load');
					// console.log(record);
					// console.log(node);
					// me.getSelectionModel().select([node]);
				}
			}			
		});
		
		
		Ext.apply(me,{	
			itemId: 'id_unitkerja',
			useArrows: true, border: false, rootVisible: false,
			store : store,			
			columns: [
				{xtype: 'treecolumn', text: me.rootTitle, flex: 1, sortable : true, dataIndex: 'text'},
				{ text: 'Kepala', flex:1, sortable: true, dataIndex: 'kepalanama', hidden: me.onlySatker}
			],
		});
		me.callParent([arguments]);
	}
})