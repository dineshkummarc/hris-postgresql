Ext.define("SIAP.modules.ticket.GridTicket", {
	extend: "Ext.grid.Panel",
	alternateClassName: "SIAP.gridticket",
	alias: 'widget.gridticket',
		
	initComponent: function(){
		var me = this;
		me.addEvents({"beforeload": true});
		var storecuti = Ext.create('Ext.data.Store', {
			storeId: 'storecuti',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/ticket/getListTicket',
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
				'id','travel','noinvoice','jenis','namadepan','departemen','tgldinas','tglkembali','tujuan','jml','budgetcode','lama','nik','status'
			],
			listeners: {
				beforeload: function(store){
					me.fireEvent("beforeload", store);
				}
			}
		});
		Ext.apply(me, {
			layout: 'fit',
			autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true, enableDragDrop: true,
			store: storecuti,		
			selModel: {mode: 'MULTI'}, 
			columns: [
				{header: 'No', xtype: 'rownumberer', width: 30}, 
				{header: 'NIK', dataIndex: 'nik', width: 65},
				{header: 'Nama', dataIndex: 'namadepan', width: 120},
				{header: 'Departemen', dataIndex: 'departemen', width: 75},
				{header: 'Travel', dataIndex: 'travel', width: 50}, 
				{header: 'Transaction Order', dataIndex: 'noinvoice', width: 105, align:'center'},
				{header: 'Tiket / Hotel', dataIndex: 'jenis', align:'center', width: 80},
				{header: 'Tgl Keberangkatan', dataIndex: 'tgldinas', width: 105},
				{header: 'Tgl Kembali', dataIndex: 'tglkembali', width: 70},
				{header: 'Lama', dataIndex: 'lama', width: 50, 
					renderer: function(value, p, r){
        			return (r.data['lama'] + ' hari ');
      				} 
      			},
				{header: 'Tujuan', dataIndex: 'tujuan', width: 110},
				{header: 'Jumlah', dataIndex: 'jml' , width: 70,
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
				{header: 'Code Budget', dataIndex: 'budgetcode', width: 80, align:'center'},
				{header: 'Status', dataIndex: 'status', width: 45, align:'center',
				renderer: function(value,meta,record,index) {												
						if(record.data.status != null ) {
							return '<span style="color:blue;font-weight:bold;font-size:20px">'+'✓'+'</span>';
						} else {
							return '<span style="color:red;font-weight:bold;font-size:20px">'+'x'+'</span>';
						}											
					}
				}, 
				{header: '', width: 107, align:'center', dataIndex: 'status',
					renderer: function(value,meta,record,index){
						if(Settings.userid == '139'){
							return '<a onclick=detailticket("'+record.data.id+'","'+record.data.status+'")  style="cursor:pointer;"><span class="black-icon-brand"><i class="fa fa-external-link" aria-hidden="true"></i></span></a>';
						}
					},				
				},  
			],
			bbar: Ext.create('Ext.toolbar.Paging',{
				displayInfo: true,
				height : 35,
				store: 'storecuti'
			})
		});

		me.callParent([arguments]);
	}
});

function detailticket(id,status) {
	Ext.History.add('#ticket&detailticket&'+Base64.encode(id)+'#'+Base64.encode(status));
}

function detailAlasan(id) {
	var store = Ext.getStore('storecuti');		
	var record = store.getById(id);		
	
	var win_remark = Ext.create('Ext.window.Window', {
		title: 'Detail Alasan', 
		closeAction: 'destroy', modal:true, layout: 'fit', autoScroll: true, autoShow: true, width: 400,			
		items: [{
			xtype: 'form', waitMsgTarget:true, bodyPadding: 15, layout: 'anchor', defaultType: 'textfield', region: 'center', autoScroll: true,
			defaults: {labelWidth: 60, anchor: '100%'},			
			items: [
				{xtype: 'displayfield', value: record.get('verifikasinotes')}
			],
			buttons: [
				{text: 'Cancel', 
					handler: function(){
						win_remark.destroy();
					}
				}			
			]
		}]
	});						
}

function statusKosong(id) {
	var store = Ext.getStore('storecuti');		
	var record = store.getById(id);	
	
	var win = Ext.create('Ext.window.Window', {
		title: 'Status Kosong', 
		closeAction: 'destroy', modal:true, layout: 'fit', autoScroll: true, autoShow: true, width: 400,			
		items: [{
			buttons: [
				{text: 'Simpan', 
					handler: function(){
						win.down('form').getForm().submit({
							waitTitle: 'Menyimpan...', 
							waitMsg: 'Sedang menyimpan data, mohon tunggu...',
							url: Settings.SITE_URL + '/cuti/updStatusCutiKosong',
							method: 'POST',
							success: function(form, action){
								var obj = Ext.decode(action.response.responseText);
								win.destroy();
								window.location.reload();								
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
						{xtype: 'hidden', name:'pengajuanid', value: record.get('id')},
						{xtype: 'combostatuspjdinas', fieldLabel: 'Status Cuti', name: 'statusid'}, 
					]
				},
			]
		}]
	});						
}