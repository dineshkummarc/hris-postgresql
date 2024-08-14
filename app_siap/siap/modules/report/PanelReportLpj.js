Ext.define("SIAP.modules.report.PanelReportLpj", {
	extend: "Ext.grid.Panel",
	alternateClassName: "SIAP.panelreportlpj",
	alias: 'widget.panelreportlpj',
	requires: [
		'SIAP.components.field.ComboStatusPegawai',
		'SIAP.components.field.ComboBulan',
		'SIAP.components.field.FieldPeriode'
	],	
	initComponent: function(){
		var me = this;
		
		var storelpj = Ext.create('Ext.data.Store', {
			storeId: 'storelpj',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportLpj',
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
				'id','pengajuanid','pegawaiid','jenisform','nik','namadepan','nourut','grandtotal','idaccomodation','satkerid','tglmulai','tglselesai','kota','kelebihan','kekurangan'
			],
			listeners: {
				beforeload: function(store){
					me.fireEvent("beforeload", store);
				}
			}
		});
		
		
		Ext.apply(me, {
			title: 'LPJ', xtype: 'grid', region: 'center', layout: 'fit', autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
			//\region: 'center', layout: 'fit', autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
			store: storelpj,
			columns: [
				{header: 'No', xtype: 'rownumberer', width: 30},
				{header: 'Budget Code', dataIndex: 'idaccomodation', width: 80},
				{header: 'Nik', dataIndex: 'nik', width: 80}, 
				{header: 'Nama', dataIndex: 'namadepan', width: 120},
				{header: 'Jenis Form', dataIndex: 'jenisform', width: 260},
				{header: 'Tgl Tugas', dataIndex: 'tglmulai', width: 80},
				{header: 'Tgl Kembali', dataIndex: 'tglselesai', width: 80},
				{header: 'Kota', dataIndex: 'kota', width: 80},
				{header: 'Total', dataIndex: 'grandtotal', width: 80,
					renderer : Ext.util.Format.Currency = function(v) {
						v = (Math.round((v-0)*100))/100;
						v = (v == Math.floor(v)) ? v + "" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
						v = String(v);
						var ps = v.split('.');
						var whole = ps[0];
						var sub = ps[1] ? ','+ ps[1] : "";
						var r = /(\d+)(\d{3})/;
						while (r.test(whole)) {
							whole = whole.replace(r, '$1' + '.' + '$2');
						}
						v = whole + sub;
						if(v.charAt(0) == '-'){
							return '-' + v.substr(1) + "";
						}
						return (v).replace(/\./, '.');
					}
				},
				{header: 'Kelebihan', dataIndex: 'kelebihan', width: 80,
					renderer : Ext.util.Format.Currency = function(v) {
						v = (Math.round((v-0)*100))/100;
						v = (v == Math.floor(v)) ? v + "" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
						v = String(v);
						var ps = v.split('.');
						var whole = ps[0];
						var sub = ps[1] ? ','+ ps[1] : "";
						var r = /(\d+)(\d{3})/;
						while (r.test(whole)) {
							whole = whole.replace(r, '$1' + '.' + '$2');
						}
						v = whole + sub;
						if(v.charAt(0) == '-'){
							return '-' + v.substr(1) + "";
						}
						return (v).replace(/\./, '.');
					}
				},
				{header: 'Kekurangan', dataIndex: 'kekurangan', width: 80,
					renderer : Ext.util.Format.Currency = function(v) {
						v = (Math.round((v-0)*100))/100;
						v = (v == Math.floor(v)) ? v + "" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
						v = String(v);
						var ps = v.split('.');
						var whole = ps[0];
						var sub = ps[1] ? ','+ ps[1] : "";
						var r = /(\d+)(\d{3})/;
						while (r.test(whole)) {
							whole = whole.replace(r, '$1' + '.' + '$2');
						}
						v = whole + sub;
						if(v.charAt(0) == '-'){
							return '-' + v.substr(1) + "";
						}
						return (v).replace(/\./, '.');
					}
				},
				/*{header: 'Informasi Awal', align: 'left',
					columns:[
					{ header: 'Ticket', 
						columns:[
						{header: 'Orders', dataIndex: 'idticket', align:'center', renderer: function(value, element, record) {
								if (record.data['idticket'] == null) {
									return v='-';
								} else {
									return record.data['idticket'] + '	' + record.data['tnama'];
								}
							}, width: 150
						},
						{header: 'Available', dataIndex: 'ticket', width: 129, align:'center',
							renderer : Ext.util.Format.Currency = function(v) {
								v = (Math.round((v-0)*100))/100;
								v = (v == Math.floor(v)) ? v + "" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
								v = String(v);
								var ps = v.split('.');
								var whole = ps[0];
								var sub = ps[1] ? ','+ ps[1] : "";
								var r = /(\d+)(\d{3})/;
								while (r.test(whole)) {
									whole = whole.replace(r, '$1' + '.' + '$2');
								}
								v = whole + sub;
								if(v.charAt(0) == '-'){
									return '-' + v.substr(1) + "";
								}
								if (v == 'NaN') {
									return v='0';
								} else {
									return (v).replace(/\./, '.');
								}
							}
						}
						] 		
					},
					{ header: 'Accomodation', 
						columns:[
						{header: 'Orders', dataIndex: 'idaccomodation', align:'center', renderer: function(value, element, record) {
						  		if (record.data['idaccomodation'] == null) {
									return v='-';
								} else {
									return record.data['idaccomodation'] + '	' + record.data['anama'];
								}
							}, width: 150
						},
						{header: 'Available', dataIndex: 'accomodation', width: 129, align:'center',
							renderer : Ext.util.Format.Currency = function(v) {
								v = (Math.round((v-0)*100))/100;
								v = (v == Math.floor(v)) ? v + "" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
								v = String(v);
								var ps = v.split('.');
								var whole = ps[0];
								var sub = ps[1] ? ','+ ps[1] : "";
								var r = /(\d+)(\d{3})/;
								while (r.test(whole)) {
									whole = whole.replace(r, '$1' + '.' + '$2');
								}
								v = whole + sub;
								if(v.charAt(0) == '-'){
									return '-' + v.substr(1) + "";
								}
								if (v == 'NaN') {
									return v='0';
								} else {
									return (v).replace(/\./, '.');
								}
							}
						}
						] 		
					},
					]
				},
				{header: 'Perubahan Informasi', align: 'left',
					columns:[
					{ header: 'Ticket', 
						columns:[
						{header: 'Orders', dataIndex: 'idticket', align:'center', renderer: function(value, element, record) {
								if (record.data['idticket'] == null) {
									return v='-';
								} else {
									return record.data['idticket'] + '	' + record.data['tnama'];
								}
							}, width: 150
						},
						{header: 'Available', dataIndex: 'sumtick', width: 129, align:'center',
							renderer : Ext.util.Format.Currency = function(v) {
								v = (Math.round((v-0)*100))/100;
								v = (v == Math.floor(v)) ? v + "" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
								v = String(v);
								var ps = v.split('.');
								var whole = ps[0];
								var sub = ps[1] ? ','+ ps[1] : "";
								var r = /(\d+)(\d{3})/;
								while (r.test(whole)) {
									whole = whole.replace(r, '$1' + '.' + '$2');
								}
								v = whole + sub;
								if(v.charAt(0) == '-'){
									return '-' + v.substr(1) + "";
								}
								if (v == 'NaN') {
									return v='0';
								} else {
									return (v).replace(/\./, '.');
								}
							}
						}
						] 		
					},
					{ header: 'Accomodation', 
						columns:[
						{header: 'Orders', dataIndex: 'idaccomodation', align:'center', renderer: function(value, element, record) {
						  		if (record.data['idaccomodation'] == null) {
									return v='-';
								} else {
									return record.data['idaccomodation'] + '	' + record.data['anama'];
								}
							}, width: 150
						},
						{header: 'Available', dataIndex: 'sumacco', width: 129, align:'center',
							renderer : Ext.util.Format.Currency = function(v) {
								v = (Math.round((v-0)*100))/100;
								v = (v == Math.floor(v)) ? v + "" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
								v = String(v);
								var ps = v.split('.');
								var whole = ps[0];
								var sub = ps[1] ? ','+ ps[1] : "";
								var r = /(\d+)(\d{3})/;
								while (r.test(whole)) {
									whole = whole.replace(r, '$1' + '.' + '$2');
								}
								v = whole + sub;
								if(v.charAt(0) == '-'){
									return '-' + v.substr(1) + "";
								}
								if (v == 'NaN') {
									return v='0';
								} else {
									return (v).replace(/\./, '.');
								}
							}
						}
						] 		
					},
					]
				},*/			
			],
			viewConfig: {
				getRowClass: function(record, rowIndex, rowParams, store){
					if(record.get('monthexp') < 0){
						return 'red_grid_rows';
					}
				}
			},
			tbar: [
			/*{itemId: 'id_ketstatus_bulan', xtype: 'combobulan', 
				listeners: {
					afterrender : function () {
						this.setValue('');
					},
					select: function(combo, rec){
						this.setValue(rec[0].data.id);
					}
				}																
			},
			{itemId: 'id_ketstatus_thn', xtype: 'fieldperiode', value: new Date().getFullYear()},
			{ glyph:'xf002@FontAwesome', 
				handler: function(){
					var tree = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
					treeid = null;
					if(tree.length > 0){
						treeid = tree[0].get('id');
					}							
					Ext.getStore('storebudget').proxy.extraParams.satkerid = treeid;
					Ext.getStore('storebudget').proxy.extraParams.bulan = Ext.ComponentQuery.query('#id_ketstatus_bulan')[1].getValue();
					Ext.getStore('storebudget').proxy.extraParams.tahun = Ext.ComponentQuery.query('#id_ketstatus_thn')[0].getValue();
					Ext.getStore('storebudget').load();
				}
			},*/
			'->',
			/*{glyph:'xf02f@FontAwesome', text: 'Cetak',
					handler: function(){
					var tree = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
					treeid = null;
					if(tree.length > 0){
						treeid = tree[0].get('id');
					}
					Ext.getStore('storelpj').proxy.extraParams.satkerid = treeid;
					var m = Ext.getStore('storelpj').proxy.extraParams;
					window.open(Settings.SITE_URL + "/report/cetakdokumen/mutasipromosi?" + objectParametize(m));							
					}
				},*/
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

