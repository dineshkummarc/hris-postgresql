Ext.define("SIAP.modules.report.PanelReportLocation", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportlocation",
	alias: 'widget.panelreportlocation',
	initComponent: function(){
		var me = this;
		
		var store_chart = Ext.create('Ext.data.Store', {
			storeId: 'statistiklocation',
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getGraphByLocation',
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
				{name: 'name', mapping: 'lokasi', type: 'string'}, 
				{name: 'lokasiid', mapping: 'lokasiid'}, 
				{name: 'data1', mapping: 'jml'},
			],
			listeners: {
				beforeload: function(store){
				},
			}
		});
		
		var chart_location = Ext.create('Ext.chart.Chart', {
			animate: {
				easing: 'ease',
				duration: 350
			},
			width: 1340, height: 500,
			shadow: false,
			store: store_chart,
			axes: [{
				type: 'Numeric',
				position: 'left',
				fields: ['data1'],
				title: 'Total',
				grid: false,
				minimum: 0,
				// maximum: 1000
			}, {
				type: 'Category',
				position: 'bottom',
				fields: ['name'],
				title: 'Location',
				label: {
					rotate: {
						degrees: 270
					}
				},
			}],
			series: [{
				type: 'column',
				axis: 'left',
				gutter: 10,
				highlight: true,
				xField: 'name',
				yField: ['data1'],		
				label: {
					display: 'outside',
					field: 'data1',
					renderer: Ext.util.Format.numberRenderer('0'),
					// orientation: 'horizontal',
					color: '#333',
					font: 'bold 12px Arial',
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
					Ext.getStore('storereportlocation').proxy.extraParams.lokasiid = obj.storeItem.data.lokasiid;
					Ext.getStore('storereportlocation').load();																				
				},
			}										
		});
		
		var storereportlocation = Ext.create('Ext.data.Store', {
			storeId: 'storereportlocation',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportListLocation',
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
				'levelid', 'level', 'lokasiid', 'lokasi'
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
				{xtype: 'panel', region: 'center', title: 'Graph', autoScroll: true, 
					// resizable:true,
					items: chart_location
				},
				{xtype: 'grid', region: 'south', title: 'Raw Data', height: 250, collapsed: true, collapsible: true,
					autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storereportlocation,
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
						},
						{header: 'Lokasi', dataIndex: 'lokasi', width: 120}
					],
					bbar: Ext.create('Ext.toolbar.Paging',{
						displayInfo: true,
						height : 35,
						store: 'storereportlocation'
					})
					
				},				
			],
			tbar: ['->',
				{text: 'Cetak',
					handler: function(){
						var m = me.down('grid').getStore().proxy.extraParams;
						window.open(Settings.SITE_URL + "/report/cetak/bydivisi?" + objectParametize(m));
					}
				}
			]
		});

		me.callParent([arguments]);
		
		
	}
});

