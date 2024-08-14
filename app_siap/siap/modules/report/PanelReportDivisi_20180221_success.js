Ext.define("SIAP.modules.report.PanelReportDivisi", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportdivisi",
	alias: 'widget.panelreportdivisi',
	initComponent: function(){
		var me = this;
		
		var store_chart = Ext.create('Ext.data.Store', {
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
				
		Ext.apply(me, {
			layout: 'border',
			items: [
				{xtype: 'panel', region: 'west', layout: 'fit', width: 600,
					items: [					
						Ext.create('Ext.chart.Chart', {
							animate: true,
							shadow: true,
							store: store_chart,
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
								}
							}]
							
						})							
					]
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

