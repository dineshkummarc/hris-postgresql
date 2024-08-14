Ext.define("SIAP.modules.schedule.GridSchedule", {
	extend: "Ext.grid.Panel",
	alternateClassName: "SIAP.gridschedule",
	alias: 'widget.gridschedule',
		
	initComponent: function(){
		var me = this;
		me.addEvents({"beforeload": true});
		var storecuti = Ext.create('Ext.data.Store', {
			storeId: 'storecuti',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/schedule/getListSchedule',
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
				'id','nik','namadepan','jabatan','tgl','tglupdate','tgl1','tgl2','tgl3','tgl4','tgl5','tgl6','tgl7','tgl8','tgl9','tgl10',
				'tgl11','tgl12','tgl13','tgl14','tgl15','tgl16','tgl17','tgl18','tgl19','tgl20',
				'tgl21','tgl22','tgl23','tgl24','tgl25','tgl26','tgl27','tgl28','tgl29','tgl30','tgl31','a','i','sk','ct','ro','off','p1','s1','m1'
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
				{header: 'Jabatan', dataIndex: 'jabatan', width: 75},
				{header: 'Tgl', dataIndex: 'tgl', width: 70}, 

				{header: 'Tgl Update', dataIndex: 'tgl', width: 70, align:'center',
				renderer: function(value,meta,record,index) {												
						if(record.data.tglupdate != null ) {
							return record.data.tglupdate;
						} else {
							return '-';
						}											
					}
				}, 
				{header: 'Tanggal', align: 'left',
					columns:[
						{header: '1', dataIndex: 'tgl1', width: 35, align:'center'},
						{header: '2', dataIndex: 'tgl2', width: 35, align:'center'},
						{header: '3', dataIndex: 'tgl3', width: 35, align:'center'},
						{header: '4', dataIndex: 'tgl4', width: 35, align:'center'},
						{header: '5', dataIndex: 'tgl5', width: 35, align:'center'},
						{header: '6', dataIndex: 'tgl6', width: 35, align:'center'},
						{header: '7', dataIndex: 'tgl7', width: 35, align:'center'},
						{header: '8', dataIndex: 'tgl8', width: 35, align:'center'},
						{header: '9', dataIndex: 'tgl9', width: 35, align:'center'},
						{header: '10', dataIndex: 'tgl10', width: 35, align:'center'},

						{header: '11', dataIndex: 'tgl11', width: 35, align:'center'},
						{header: '12', dataIndex: 'tgl12', width: 35, align:'center'},
						{header: '13', dataIndex: 'tgl13', width: 35, align:'center'},
						{header: '14', dataIndex: 'tgl14', width: 35, align:'center'},
						{header: '15', dataIndex: 'tgl15', width: 35, align:'center'},
						{header: '16', dataIndex: 'tgl16', width: 35, align:'center'},
						{header: '17', dataIndex: 'tgl17', width: 35, align:'center'},
						{header: '18', dataIndex: 'tgl18', width: 35, align:'center'},
						{header: '19', dataIndex: 'tgl19', width: 35, align:'center'},
						{header: '20', dataIndex: 'tgl20', width: 35, align:'center'},

						{header: '21', dataIndex: 'tgl21', width: 35, align:'center'},
						{header: '22', dataIndex: 'tgl22', width: 35, align:'center'},
						{header: '23', dataIndex: 'tgl23', width: 35, align:'center'},
						{header: '24', dataIndex: 'tgl24', width: 35, align:'center'},
						{header: '25', dataIndex: 'tgl25', width: 35, align:'center'},
						{header: '26', dataIndex: 'tgl26', width: 35, align:'center'},
						{header: '27', dataIndex: 'tgl27', width: 35, align:'center'},
						{header: '28', dataIndex: 'tgl28', width: 35, align:'center'},
						{header: '29', dataIndex: 'tgl29', width: 35, align:'center'},
						{header: '30', dataIndex: 'tgl30', width: 35, align:'center'},
						{header: '31', dataIndex: 'tgl31', width: 35, align:'center'},
					]
				},
				// {header: 'Total', dataIndex: 'noinvoice', width: 45, align:'center'},
				
				{header: 'Informasi', align: 'left',
					columns:[
						{header: 'A', dataIndex: 'a', width: 35}, 
						{header: 'I', dataIndex: 'i', width: 35}, 
						{header: 'SK', dataIndex: 'sk', width: 35}, 
						{header: 'CT', dataIndex: 'ct', width: 35},
						{header: 'RO', dataIndex: 'ro', width: 35},
						{header: 'OFF', dataIndex: 'off', width: 35},
						{header: 'P1', dataIndex: 'p1', width: 35},
						{header: 'M1', dataIndex: 'm1', width: 35},	
						{header: 'S1', dataIndex: 's1', width: 35},
					]
				},
				{header: '', width: 107, align:'center', dataIndex: 'status',
					renderer: function(value,meta,record,index){
						if(Settings.userid == '139'){
							return '<a onclick=detail("'+record.data.id+'","'+record.data.status+'")  style="cursor:pointer;"><span class="black-icon-brand"><i class="fa fa-external-link" aria-hidden="true"></i></span></a>';
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

function detail(id,status) {
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