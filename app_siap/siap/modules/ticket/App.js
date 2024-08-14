Ext.define("SIAP.modules.ticket.App", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.ticket",
	alias: 'widget.ticket',	
	requires: [
		'SIAP.components.tree.UnitKerja',
		'SIAP.modules.ticket.GridTicket'
	],	
	initComponent: function(){
		var me = this;
		Ext.apply(me,{		
			layout: 'border',
			items: [
				{ region: 'west', title: 'Daftar Unit Kerja', collapsible: true, collapsed: false, layout: 'fit', border: false,
                    resizable:{dynamic:true},		
					items:[
						{xtype: 'unitkerja', width: 200, border: false,
							listeners:{
								itemclick : function(a,b,c){
									//var tglmulai = Ext.Date.format(me.down('#id_tglmulai').getValue(),'d/m/Y');
									//var tglselesai = Ext.Date.format(me.down('#id_tglselesai').getValue(),'d/m/Y');
									
									//me.down('#id_gridticket').getStore().proxy.extraParams.tglmulai = tglmulai;
									//me.down('#id_gridticket').getStore().proxy.extraParams.tglselesai = tglselesai;
									me.down('#id_gridticket').getStore().proxy.extraParams.satkerid = b.get('id');
									me.down('#id_gridticket').getStore().loadPage(1);
								},
							},							
						}
					]
				},						
				{itemId: 'id_gridticket', xtype: 'gridticket', region: 'center', frame: true,
					listeners: {
						beforeload: function(store) {
							var satkerid = '';
							var m = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
							//var tglmulai = Ext.Date.format(me.down('#id_tglmulai').getValue(),'d/m/Y');
							//var tglselesai = Ext.Date.format(me.down('#id_tglselesai').getValue(),'d/m/Y');
							if(m.length > 0){
								satkerid = m[0].get('id');
							}
							store.proxy.extraParams.satkerid = satkerid;
							//store.proxy.extraParams.tglmulai = tglmulai;
							//store.proxy.extraParams.tglselesai = tglselesai;					
						}
					},
					tbar: [						
						// {itemId: 'id_tglmulai', xtype: 'datefield', fieldLabel: 'Periode', format: 'd/m/Y', name: 'tglmulai', value: (moment().startOf("month").format('DD/MM/YYYY')), emptyText: 'Tgl Awal', labelWidth:40, style: 'margin-left:10px;'},
						// {itemId: 'id_tglselesai', xtype: 'datefield', fieldLabel: 's/d', format: 'd/m/Y', name: 'tglselesai', value: (moment().endOf("month").format('DD/MM/YYYY')), emptyText: 'Tgl Akhir', labelWidth:20, style: 'margin-left:5px;'}, 
						// '-',
						// {glyph:'xf002@FontAwesome',
						// 	handler: function() {
						// 		me.down('#id_gridticket').getStore().loadPage(1);
						// 	}
						// },
						'->',					
						/*{glyph:'xf196@FontAwesome', text: 'Tambah',
							handler: function() {
								Ext.History.add('#cuti&AddCuti');
							}
						},*/
						{id:'id_btn_upload', glyph:'xf196@FontAwesome', text: 'Upload File',
								handler: function(){
								me.win_import();
							}
						},
						// {glyph:'xf02f@FontAwesome', text: 'Cetak',
						// 	handler: function(){
						// 		var m = me.down('#id_gridticket').getStore().proxy.extraParams;		
						// 		window.open(Settings.SITE_URL + "/cuti/cetakdokumen?" + objectParametize(m));								
						// 	}
						// }
					],
				}			
			],
			listeners: {
				afterrender: function(){
					Ext.get('id_submenu').dom.style.display = 'none';
				}
			}
		});		
		me.callParent([arguments]);
		// 
		if(Settings.userid == '1235'){
			Ext.getCmp('id_btn_upload').show();	
		} else {
			Ext.getCmp('id_btn_upload').hide();	
		}		
	},
	win_import: function(){
		var me = this;
		var win = Ext.create('Ext.window.Window',{
			title: 'Import Data', 
			width: 430,
			closeAction: 'destroy', modal:true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{text: 'Simpan', 
					handler: function(){
						win.down('form').getForm().submit({
							waitTitle: 'Menyimpan...', 
							waitMsg: 'Sedang menyimpan data, mohon tunggu...',
							url: Settings.SITE_URL + '/ticket/get_import_file',
							method: 'POST',
							success: function(form, action){
								var obj = Ext.decode(action.response.responseText);
								
								var winprogress = Ext.create('SIAP.components.progressbar.WinProgress',{
									title: 'Proses Import Data',
									URL: Settings.SITE_URL + '/ticket/proses_import_file',
								});
								var extraParams = {};
								winprogress.proses_exec(obj.data, extraParams);		
								winprogress.on('finished', function(p){
									me.down('grid').getStore().load();
								});																															
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
						{ xtype: 'filefield', name: 'dokumen', fieldLabel: 'Dokumen', allowBlank: false, buttonText: 'Pilih File ...'}, 
						/*{ xtype: 'box', fieldLabel: 'label',
						  autoEl: {tag: 'a', href: Settings.SITE_URL + "/c_targetvisitor/cetak/template", children: [{tag: 'div', html: 'Download Template'}]},
						  style: 'cursor:pointer;'
						}*/						
					]
				},
			]
		});				
	}	
});