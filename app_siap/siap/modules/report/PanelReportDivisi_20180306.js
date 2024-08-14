Ext.define("SIAP.modules.report.PanelReportDivisi", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportdivisi",
	alias: 'widget.panelreportdivisi',
	initComponent: function(){
		var me = this;
		
		// store_chart
		var store_chart_divisi = Ext.create('Ext.data.Store', {
			storeId: 'statistikdivisi',
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getGraphByDivisi',
				actionMethods: {
					create : 'POST',
					read   : 'POST',
				},								
				reader: {
					type: 'json',
					root: 'data',
					// totalProperty: 'count'
				}
			},
			fields: [
				{name: 'name', mapping: 'satker'}, 
				{name: 'satkerid', mapping: 'satkerid'}, 
				{name: 'data1', mapping: 'jml'},
				// {name: 'warna', mapping: 'warna'}, 
			],
			listeners: {
				beforeload: function(store){
				},
			}
		});
		
		var storereportdivisi = Ext.create('Ext.data.Store', {
			storeId: 'storereportdivisi',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportListDivisi',
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
				{xtype: 'panel', region: 'center', title: 'Graph', autoScroll: true, // layout: 'fit',
					layout : 'fit', resizable:true,
					items: [					
						Ext.create('Ext.chart.Chart', {
							itemId: 'chartdivisi',
							id: 'idchartdivisi',
							animate: true, autoScroll: true, // width: 1140, height: 500, 
							
							shadow: true,
							store: store_chart_divisi,
							axes: [{
								type: 'Numeric',
								position: 'left',
								fields: ['data1'],
								title: 'Total',
								grid: true,
								minimum: 0,
								// maximum: 1000
							}, {
								type: 'Category',
								position: 'bottom',
								fields: ['name'],
								title: 'Unit',
								label: {
									rotate: {
										degrees: 270
									}
								}
							}],
							series: [{
								type: 'column',
								axis: 'left',
								gutter: 80,
								xField: 'name',
								yField: ['data1'],		
								label: {
									display: 'insideStart',
									field: 'data1',
									renderer: Ext.util.Format.numberRenderer('0'),
									orientation: 'horizontal',
									color: '#333',
									'text-anchor': 'left'
								},								
								tips: {
									trackMouse: true,
									width: 74,
									height: 38,
									renderer: function(storeItem, item) {
										this.setTitle(storeItem.get('name'));
										this.update(storeItem.get('data1'));										
									}
								},
								style: {
									fill: '#4f81bd'
								},
								listeners: {
									itemmouseup: function(obj){										
										this.chart.fireEvent('chartitemmouseup', obj);
									}   
								}
							}],
							listeners: {
								 chartitemmouseup: function(obj){
									console.log('testing');
								}
								
							}							
						})							
					]
				},
				{xtype: 'grid', region: 'south', title: 'Raw Data', height: 250, collapsed: true, collapsible: true,
					autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storereportdivisi,
					columns: [
						{header: 'No', xtype: 'rownumberer', width: 30}, 
						{header: 'NIK', dataIndex: 'nik', width: 80}, 
						{header: 'Nama', dataIndex: 'nama', width: 150}, 
						{header: 'Jabatan', dataIndex: 'jabatan', width: 180},
						{header: 'Level', dataIndex: 'level', width: 120},
						{header: 'Unit', align: 'left',
							columns:[
								{header: 'Direktorat', dataIndex: 'direktorat', width: 120}, 
								{header: 'Divisi', dataIndex: 'divisi', width: 120}, 
								{header: 'Departemen', dataIndex: 'departemen', width: 120}, 
								{header: 'Seksi', dataIndex: 'seksi', width: 120}, 
								{header: 'Sub Seksi', dataIndex: 'subseksi', width: 120}, 					
							]
						}
					],
					bbar: Ext.create('Ext.toolbar.Paging',{
						displayInfo: true,
						height : 35,
						store: 'storereportdivisi'
					})
					
				},
				
			]
		});

		me.callParent([arguments]);
		
		
	}
});

