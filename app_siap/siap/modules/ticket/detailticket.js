Ext.define("SIAP.modules.ticket.detailticket", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.detailticket",
	alias: 'widget.detailticket',	
	requires: [],	
	initComponent: function(){
		var me = this;
		var arr_params = me.params.split('#');		
		var status = Base64.decode(arr_params[1]);
		
		Ext.apply(me,{		
			layout: 'border',
			items: [
				{id:'id_form_detailticket', xtype: 'form', region: 'center', autoScroll: true, bodyPadding: 10,
					listeners: {
						afterrender: function(p) {
							p.getForm().load({
								url: Settings.SITE_URL + '/ticket/getDetailTicket',
								method: 'POST',
								params:{ id: arr_params[0] },
								success: function(form, action) {
									var obj = Ext.decode(action.response.responseText);									
									if(obj.success) {
										console.log(obj.data);
										
										if( !Ext.isEmpty(obj.data.files)) {
											Ext.getCmp('id_lampiran').setValue('<a href="'+Settings.SITE_URL+'/cuti/download?filename='+obj.data.files+'">'+obj.data.files+'</a>');
										}
																				
									}
								}
							});							
						}
					},				
					items: [
						{ xtype:'fieldset', title: 'Rincian Ticket', collapsible: true,							
							items: [
								{ layout:'column', baseCls: 'x-plain', border: false,
									items: [
										{ xtype: 'hidden', name: 'codeid'},
										{ xtype: 'hidden', name: 'actual'},
										{ xtype: 'panel',
											columnWidth:.5,									
											bodyPadding: 10,
											layout: 'form',				
											defaultType: 'displayfield',
											baseCls: 'x-plain',
											border: false,
											defaults:{
												labelWidth: 170,
											},
											items:[ 
												{ xtype: 'displayfield', fieldLabel: 'NIK', name: 'nik', anchor: '95%'},
												{ xtype: 'displayfield', fieldLabel: 'Nama', name: 'namadepan', anchor: '95%'},
												{ xtype: 'displayfield', fieldLabel: 'Jabatan', name: 'jabatan', anchor: '95%'},
												{ xtype: 'displayfield', fieldLabel: 'Departemen', name: 'departemen', anchor: '95%'},
												{ xtype: 'displayfield', fieldLabel: 'Code Budget', name: 'budgetcode', anchor: '95%'},
												{ xtype: 'displayfield', fieldLabel: 'Unit Bisnis', value: 'ECI', anchor: '95%'},
											]
										},
										{ xtype: 'panel',
											columnWidth:.5,									
											bodyPadding: 10,
											layout: 'form',				
											defaultType: 'displayfield',
											baseCls: 'x-plain',
											border: false,
											defaults: {
												labelWidth: 170,
											},
											items:[ 
												{ xtype: 'displayfield', fieldLabel: 'Travel', name: 'travel', anchor: '95%'},
												{ xtype: 'displayfield', fieldLabel: 'Transaction Order', name: 'noinvoice', anchor: '95%'},
												{ xtype: 'displayfield', fieldLabel: 'Tgl Keberangkatan', name: 'tgldinas', anchor: '95%'},									
												{ xtype: 'displayfield', fieldLabel: 'Tgl Kembali', name: 'tglkembali', anchor: '95%'},
												{ xtype: 'displayfield', fieldLabel: 'Tujuan', name: 'tujuan', anchor: '95%'},
												{ xtype: 'displayfield', fieldLabel: 'Jumlah', name: 'jml', anchor: '95%',
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
														return "<span style='font-weight:bold' >"+(v).replace(/\./, '.')+"</span>";
													}
												},
											]
										}
									]
								}												
							]
						},
					]			
				}
			],
			tbar: [ 
				{text: 'Kembali', glyph:'xf060@FontAwesome',
					handler: function() {
						Ext.History.add('#ticket');
					}				
				},
				'->',
				{id: 'id_btn_disetujui', glyph:'xf058@FontAwesome', text: 'Diterima', hidden: true,
					handler: function() {
						var form = me.down('#id_form_detailticket').getForm();
						var codeid = form.findField('codeid').getValue();
						var actual = form.findField('actual').getValue();
						me.approveTicket(arr_params[0], codeid, actual);
					}
				},
				// {id: 'id_btn_ditolak', glyph:'xf057@FontAwesome', text: 'Ditolak', hidden: true,
				// 	handler: function() {
				// 		var form = me.down('#id_form_detailticket').getForm();
				// 		var id = form.findField('id').getValue();
				// 		var nama = form.findField('nama').getValue();
				// 		var tglpermohonan = form.findField('tglpermohonan').getValue();		
				// 		//var alasancuti = form.findField('alasancuti').getValue();				
				// 		me.rejectCuti(arr_params[0], arr_params[1], nik, nama, tglpermohonan, statusid);
				// 	}
				// },
			]
		});		
		me.callParent([arguments]);
		
		// Ketika status belum disetujui oleh Admin FA maka tampilkan button approval
		if(status == '1') {
			Ext.getCmp('id_btn_disetujui').hide();
			//Ext.getCmp('id_btn_ditolak').hide();
		}
		else {
			Ext.getCmp('id_btn_disetujui').show();
			//Ext.getCmp('id_btn_ditolak').show();			
		}		
	},	
	approveTicket: function(id, codeid, actual) {
		var me = this;

		Ext.Msg.show({
			title: 'Informasi',
			msg: 'Apakah anda yakin akan menyetujui pengajuan ticket ini?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn) {
				if(btn == 'yes'){
					Ext.Ajax.request({
						url: Settings.SITE_URL + '/ticket/approveTicket',
						method: 'POST',
						params: {
							id: id,
							codeid: codeid,
							actual: actual
						},
						success: function(response){
							var obj = Ext.decode(response.responseText);
							if(obj.success) {
								Ext.History.add('#ticket');
							}
						}
					});												
				}
			}
		});
	},
	// rejectCuti: function(pegawaiid, nourut, nik, nama, tglpermohonan, statusid) {
	// 	var me = this;
		
	// 	var winReject = Ext.create('Ext.window.Window', {
	// 		title: 'Reject Cuti', 
	// 		closeAction: 'destroy', modal:true, layout: 'fit', autoScroll: true, autoShow: true, width: 400,
	// 		buttons: [
	// 			{text: 'Yes', 
	// 				handler: function(){
	// 					winReject.down('form').getForm().submit({
	// 						waitTitle: 'Menyimpan...', 
	// 						waitMsg: 'Sedang menyimpan data, mohon tunggu...',
	// 						url: Settings.SITE_URL + '/cuti/rejectCuti',
	// 						method: 'POST',
	// 						params: {pegawaiid:pegawaiid, nourut:nourut, nik:nik, nama:nama, tglpermohonan:tglpermohonan, statusid:statusid},
	// 						success: function(form, action){
	// 							var obj = Ext.decode(action.response.responseText);
	// 							winReject.destroy();
	// 							if(obj.success) {								
	// 								Ext.History.add('#cuti');
	// 							}								
	// 						},
	// 						failure: function(form, action) {
	// 						}																				
	// 					});																														
	// 				}
	// 			},
	// 			{text: 'No', 
	// 				handler: function(){
	// 					winReject.destroy();
	// 				}
	// 			}
	// 		],
	// 		items: [{
	// 			xtype: 'form', waitMsgTarget:true, bodyPadding: 15, layout: 'anchor', defaultType: 'textfield', region: 'center', autoScroll: true,
	// 			defaults: {labelWidth: 60, anchor: '100%'},			
	// 			items: [
	// 				{xtype: 'textarea', grow: true, name: 'alasan', fieldLabel: 'Alasan'}
	// 			]				
	// 		}]
	// 	});				
	// }
});