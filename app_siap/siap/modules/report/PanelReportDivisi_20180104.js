Ext.define("SIAP.modules.report.PanelReportDivisi", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportdivisi",
	alias: 'widget.panelreportdivisi',
	initComponent: function(){
		var me = this;
		
		var store1 = Ext.create('Ext.data.JsonStore', {
			fields: ['name', 'data1', 'data2', 'data3', 'data4', 'data5', 'data6', 'data7', 'data9', 'data9'],
			data: generateData()
		});		
		store1.loadData(generateData(6, 20));
		
		
		var storereportdivisi = Ext.create('Ext.data.Store', {
			storeId: 'storereportdivisi',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/pegawai/getListPegawai',
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
				'pegawaiid', 'nama', 'nik', 'satkerid', 'direktorat', 'divisi', 'departemen', 'seksi', 'subseksi', 'jabatanid', 'jabatan', 
				'levelid', 'level', 'statuspegawaiid', 'statuspegawai', 'jeniskelamin', 'email', 'emailkantor', 'telp', 'tglmulai', 'tglselesai', 'keterangan'
			],
			listeners: {
				beforeload: function(store){
					me.fireEvent("beforeload", store);
				}
			}
		});
		
		
		Ext.apply(me, {
			layout: 'border',
			items: [
				{xtype: 'panel', region: 'west', layout: 'fit', width: 400,
					items: [					
						Ext.create('Ext.chart.Chart', {
							xtype: 'chart',
							animate: true,
							store: store1,
							shadow: true,
							legend: {
								position: 'right'
							},
							insetPadding: 20,
							theme: 'Base:gradients',
							style: 'border:1px solid #000;',
							series: [{
								type: 'pie',
								field: 'data1',
								showInLegend: true,
								donut: 35,
								tips: {
									trackMouse: true,
									width: 140,
									height: 28,
									renderer: function(storeItem, item) {
										var total = 0;
										store1.each(function(rec) {
											total += rec.get('data1');
										});
										
										this.setTitle(storeItem.get('name') + ': ' + Math.round(storeItem.get('data1') / total * 100) + '%');
									}
								},
								highlight: {
									segment: {
										margin: 20
									}
								},
								listeners: {
									itemmouseup: function(obj){
										this.chart.fireEvent('chartitemmouseup', obj);
									}   
								}						
							}],
							listeners: {
								 chartitemmouseup: function(obj){
									console.log('chartitemmouseup', obj)
								}
								
							}
						})							
					]
				},
				{xtype: 'grid', region: 'center', layout: 'fit',
					autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storereportdivisi,
					columns: [
						{header: 'No', xtype: 'rownumberer', width: 30}, 
						{header: 'NIK', dataIndex: 'nik', width: 80}, 
						{header: 'Nama', dataIndex: 'nama', width: 150}, 
						{header: 'Jabatan', dataIndex: 'jabatan', width: 180},
						{header: 'Level', dataIndex: 'level', width: 120},
						{header: 'Title', dataIndex: 'level', width: 120},
						{header: 'Unit', align: 'left',
							columns:[
								{header: 'Direktorat', dataIndex: 'direktorat', width: 120}, 
								{header: 'Divisi', dataIndex: 'divisi', width: 120}, 
								{header: 'Departemen', dataIndex: 'departemen', width: 120}, 
								{header: 'Seksi', dataIndex: 'seksi', width: 120}, 
								{header: 'Sub Seksi', dataIndex: 'subseksi', width: 120}, 					
							]
						},
						{header: 'Tanggal Masuk', dataIndex: 'tglmasuk', width: 120},
						{header: 'Tanggal Keluar', dataIndex: 'tglkeluar', width: 120},
						{header: 'Alasan Keluar', dataIndex: 'alasankeluar', width: 120},
						{header: 'Status Pegawai', dataIndex: 'statuspegawai', width: 120},
						{header: 'Jenis Kelamin', dataIndex: 'jeniskelamin', width: 120},
						{header: 'Email', dataIndex: 'email', width: 120},
						{header: 'Telp', dataIndex: 'telp', width: 120},
					],
					bbar: Ext.create('Ext.toolbar.Paging',{
						displayInfo: true,
						height : 35,
						store: 'storereportdivisi'
					})
					
				},
			],
			tbar: ['->',
				{text: 'Cetak'}
			]
		});

		me.callParent([arguments]);
	}
});

function generateData(n, floor){
	var data = [],
		p = (Math.random() *  11) + 1,
		i;
		
	floor = (!floor && floor !== 0)? 20 : floor;
	
	for (i = 0; i < (n || 12); i++) {
		data.push({
			name: Ext.Date.monthNames[i % 12],
			data1: Math.floor(Math.max((Math.random() * 100), floor)),
			data2: Math.floor(Math.max((Math.random() * 100), floor)),
			data3: Math.floor(Math.max((Math.random() * 100), floor)),
			data4: Math.floor(Math.max((Math.random() * 100), floor)),
			data5: Math.floor(Math.max((Math.random() * 100), floor)),
			data6: Math.floor(Math.max((Math.random() * 100), floor)),
			data7: Math.floor(Math.max((Math.random() * 100), floor)),
			data8: Math.floor(Math.max((Math.random() * 100), floor)),
			data9: Math.floor(Math.max((Math.random() * 100), floor))
		});
	}
	return data;
};

