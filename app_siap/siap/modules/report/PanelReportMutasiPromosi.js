Ext.define("SIAP.modules.report.PanelReportMutasiPromosi", {
	extend: "Ext.grid.Panel",
	alternateClassName: "SIAP.panelreportmutasipromosi",
	alias: 'widget.panelreportmutasipromosi',
	requires: [
		'SIAP.components.field.ComboStatusPegawai'
	],	
	initComponent: function(){
		var me = this;
		
		var storemutasipromosi = Ext.create('Ext.data.Store', {
			storeId: 'storemutasipromosi',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getMutasiPromosi	',
				actionMethods: {
					create : 'POST',
					read   : 'POST',
				},								
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'count'
				}
			},
			fields: [
				'pegawaiid','nik','namadepan','mutasipromosi',
				'direktorat1','divisi1','departemen1','seksi1','subseksi1','level1','jabatan1','lokasi1','satker1',
				'direktorat2','divisi2','departemen2','seksi2','subseksi2','level2','jabatan2','lokasi2','satker2',
				'tglmulai','tglakhir','keterangan'
			],
			listeners: {
				beforeload: function(store){
					me.fireEvent("beforeload", store);
				}
			}
		});
		
		
		Ext.apply(me, {
			title: 'Mutasi & Promosi', xtype: 'grid', region: 'center', layout: 'fit', autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
			//region: 'center', layout: 'fit', autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
			store: storemutasipromosi,
			columns: [
				{header: 'No', xtype: 'rownumberer', width: 30}, 
				{header: 'NIK', dataIndex: 'nik', width: 80}, 
				{header: 'Nama', dataIndex: 'namadepan', width: 150},
				{header: 'Mutasi / Promosi', dataIndex: 'mutasipromosi', width: 100},
				{header: 'Informasi Awal', align: 'left',
					columns:[
						{header: 'Level', dataIndex: 'level1', width: 120},
						{header: 'Jabatan', dataIndex: 'jabatan1', width: 120},
						{header: 'Direktorat', dataIndex: 'direktorat1', width: 120}, 
						{header: 'Divisi', dataIndex: 'divisi1', width: 120}, 
						{header: 'Departemen', dataIndex: 'departemen1', width: 120}, 
						{header: 'Seksi', dataIndex: 'seksi1', width: 120}, 
						{header: 'Sub Seksi', dataIndex: 'subseksi1', width: 120},
						{header: 'Lokasi', dataIndex: 'lokasi1', width: 120}, 					
					]
				},
				{header: 'Pengajuan Perubahan Informasi', align: 'left',
					columns:[
						{header: 'Level', dataIndex: 'level2', width: 120},
						{header: 'Jabatan', dataIndex: 'jabatan2', width: 120},
						{header: 'Direktorat', dataIndex: 'direktorat2', width: 120}, 
						{header: 'Divisi', dataIndex: 'divisi2', width: 120}, 
						{header: 'Departemen', dataIndex: 'departemen2', width: 120}, 
						{header: 'Seksi', dataIndex: 'seksi2', width: 120}, 
						{header: 'Sub Seksi', dataIndex: 'subseksi2', width: 120},
						{header: 'Lokasi', dataIndex: 'lokasi2', width: 120}, 					
					]
				},
				{header: 'Tgl Efektif', dataIndex: 'tglmulai', width: 100},
				{header: 'Tgl Akhir', dataIndex: 'tglakhir', width: 100},
				{header: 'keterangan', dataIndex: 'keterangan', width: 120},				
			],
			bbar: Ext.create('Ext.toolbar.Paging',{
				displayInfo: true,
				height : 35,
				store: 'storemutasipromosi'
			}),
			tbar: [
			'->',
			{glyph:'xf02f@FontAwesome', text: 'Cetak',
					handler: function(){
					var tree = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
					treeid = null;
					if(tree.length > 0){
						treeid = tree[0].get('id');
					}
					Ext.getStore('storemutasipromosi').proxy.extraParams.satkerid = treeid;
					var m = Ext.getStore('storemutasipromosi').proxy.extraParams;
					window.open(Settings.SITE_URL + "/report/cetakdokumen/mutasipromosi?" + objectParametize(m));							
					}
				},
			]
		});

		me.callParent([arguments]);
	},
	crud: function(record, flag){
		var me = this;
		var win = Ext.create('Ext.window.Window',{
			title: 'Tgl Habis Kontrak', 
			width: 400,
			closeAction: 'destroy', modal:true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{text: 'Simpan', 
					handler: function(){
						win.down('form').getForm().submit({
							waitTitle: 'Menyimpan...', 
							waitMsg: 'Sedang menyimpan data, mohon tunggu...',
							url: Settings.MASTER_URL + '/c_statuspegawai/crudEndOfContract',
							method: 'POST',
							success: function(form, action){
								var obj = Ext.decode(action.response.responseText);
								win.destroy();
								me.getStore().reload();								
								me.getSelectionModel().deselectAll();
							},
							failure: function(form, action) {
							}																				
						});
					}
				},
				{text: 'Batal', 
					handler: function(){
						win.destroy();
					}
				},
			],
			items: [
				{ xtype: 'form', waitMsgTarget:true, bodyPadding: 15, layout: 'anchor', defaultType: 'textfield', region: 'center', autoScroll: true,
					defaults: {
						labelWidth: 100, anchor: '100%'
					},					
					items: [
						{xtype: 'hidden', name:'flag', value: flag},
						{xtype: 'hidden', name: 'pegawaiid'}, 
						{xtype: 'combostatuspegawai', fieldLabel: 'Status Pegawai', name: 'statuspegawaiid',
							listeners: {
								select: function(combo, rec, opt){
									win.down('form').getForm().findField('statuspegawaiid').setValue(rec[0].data.id);
								}
							}
						}, 
						{xtype: 'datefield', fieldLabel: 'Tgl Habis Kontrak', format: 'd/m/Y', name: 'tglakhirkontrak'}, 
						{xtype: 'datefield', fieldLabel: 'Tgl Permanent', format: 'd/m/Y', name: 'tglpermanent'}, 
					]
				},
			]
		});		
		if(flag == '1'){
			win.down('form').getForm().loadRecord(record);
		}		
	},
});

