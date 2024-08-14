Ext.define('SIAP.modules.perjalanandinas.detailperjalanan', {
    extend: 'Ext.panel.Panel',
    alternateClassName: 'SIAP.detailperjalanan',
    alias: 'widget.detailperjalanan',
    requires: ['SIAP.components.field.ComboSharingBudget'],
    initComponent: function() {
        var me = this;
        var arr_params = me.params.split('#');
        var statusid = Base64.decode(arr_params[3]);
        var jenisformid = Base64.decode(arr_params[4]);
        var jenistiketid = Base64.decode(arr_params[5]);
        var pengajuanid = Base64.decode(arr_params[6]);
        var idsharing = Base64.decode(arr_params[7]);
        var sharingbudget = Base64.decode(arr_params[8]);
        var kelebihan = Base64.decode(arr_params[9]);
        // var jenisperjalananid = Base64.decode(arr_params[9]);

        Ext.apply(me, {
            layout: 'border',
            items: [
                {
                    id: 'id_form_detailcuti',
                    xtype: 'form',
                    region: 'center',
                    autoScroll: true,
                    bodyPadding: 10,
                    listeners: {
                        afterrender: function(p) {
                            p.getForm().load({
                                url: Settings.SITE_URL + '/perjalanandinas/getDetailCutiPegawai',
                                method: 'POST',
                                params: { pegawaiid: arr_params[0], nourut: arr_params[1], tahun: arr_params[2] },
                                success: function(form, action) {
                                    var obj = Ext.decode(action.response.responseText);
                                    if (obj.success) {
                                        Ext.getStore('storeDetailPengajuanCuti').proxy.extraParams.pengajuanid = obj.data.pengajuanid;
                                        Ext.getStore('storeDetailPengajuanCuti').load();

                                        Ext.getStore('storeDetailRincianBiayaDN').proxy.extraParams.pengajuanid = obj.data.pengajuanid;
                                        Ext.getStore('storeDetailRincianBiayaDN').load();
                                        Ext.getStore('storeDetailRincianBiayaLN').proxy.extraParams.pengajuanid = obj.data.pengajuanid;
                                        Ext.getStore('storeDetailRincianBiayaLN').load();

                                        Ext.getStore('storeDetailRincianBiayaPertanggungan').proxy.extraParams.pengajuanid = obj.data.pengajuanid;
                                        Ext.getStore('storeDetailRincianBiayaPertanggungan').load();

                                        Ext.getStore('storeDetailBudgetFromSap').proxy.extraParams.jenisformid = obj.data.jenisformid;
                                        Ext.getStore('storeDetailBudgetFromSap').proxy.extraParams.accomodation = obj.data.aid;
                                        Ext.getStore('storeDetailBudgetFromSap').proxy.extraParams.ticket = obj.data.tid;
                                        Ext.getStore('storeDetailBudgetFromSap').load();

                                        Ext.getStore('storeDetailBudgetFromLocalDb').proxy.extraParams.pengajuanid = obj.data.pengajuanid;
                                        Ext.getStore('storeDetailBudgetFromLocalDb').proxy.extraParams.jenisformid = obj.data.jenisformid;
                                        Ext.getStore('storeDetailBudgetFromLocalDb').proxy.extraParams.accomodation = obj.data.accomodation;
                                        Ext.getStore('storeDetailBudgetFromLocalDb').proxy.extraParams.ticket = obj.data.ticket;
                                        Ext.getStore('storeDetailBudgetFromLocalDb').load();

                                        Ext.getStore('storeSharingBudget').proxy.extraParams.pengajuanid = obj.data.pengajuanid;
                                        Ext.getStore('storeSharingBudget').proxy.extraParams.tglmulai = obj.data.tglmulai;
                                        Ext.getStore('storeSharingBudget').proxy.extraParams.tglselesai = obj.data.tglselesai;
                                        Ext.getStore('storeSharingBudget').proxy.extraParams.gol = obj.data.gol;
                                        Ext.getStore('storeSharingBudget').proxy.extraParams.periode = obj.data.periode;
                                        Ext.getStore('storeSharingBudget').load();

                                        console.log(obj.data);

                                        if (!Ext.isEmpty(obj.data.files)) {
                                            Ext.getCmp('id_lampiran').setValue('<a href="' + Settings.SITE_URL + '/cuti/download?filename=' + obj.data.files + '">' + obj.data.files + '</a>');
                                        }
                                    }
                                }
                            });
                        }
                    },

                    items: [
                        {
                            xtype: 'fieldset',
                            title: 'Data Karyawan',
                            collapsible: true,
                            items: [
                                { xtype: 'hidden', name: 'email' },
                                { xtype: 'hidden', name: 'tglpermohonan' },
                                { xtype: 'hidden', name: 'fingerid' },
                                { xtype: 'hidden', name: 'tglapprove', value: Ext.Date.format(new Date(), 'd/m/Y') },
                                { xtype: 'hidden', name: 'tgladmga' },
                                { xtype: 'hidden', name: 'tglassmanga' },
                                { xtype: 'hidden', name: 'tglheadga' },
                                { xtype: 'hidden', name: 'tgladmfa' },
                                { xtype: 'hidden', name: 'tglheadfa' },
                                { xtype: 'hidden', name: 'grandtotal' },
                                { xtype: 'hidden', name: 'normalisasi' },
                                { xtype: 'hidden', name: 'ticket' },
                                { xtype: 'hidden', name: 'accomodation' },
                                { xtype: 'hidden', name: 'kelebihan' },
                                { xtype: 'hidden', name: 'kekurangan' },
                                { xtype: 'hidden', name: 'active' },
                                { xtype: 'hidden', name: 'sharingbudget' },
                                { xtype: 'hidden', name: 'pengajuanid' },
                                { xtype: 'hidden', name: 'jenisperjalananid' },
                                { xtype: 'hidden', name: 'biayajenistiket' },
                                { xtype: 'hidden', name: 'actual' },
                                { xtype: 'hidden', name: 'actualinvit' },
                                { xtype: 'hidden', name: 'actualticket' },
                                { xtype: 'hidden', name: 'periode' },
                                { xtype: 'hidden', name: 'aid' },
                                { xtype: 'hidden', name: 'tid' },
                                { xtype: 'hidden', name: 'southeastasia' },
                                { xtype: 'hidden', name: 'non_southeastasia' },
                                { xtype: 'hidden', name: 'japan' },
                                { xtype: 'hidden', name: 'eropa_usa_dubai' },
                                { xtype: 'hidden', name: 'no_southeastasia' },
                                { xtype: 'hidden', name: 'no_non_southeastasia' },
                                { xtype: 'hidden', name: 'no_japan' },
                                { xtype: 'hidden', name: 'no_eropa_usa_dubai' },
                                { xtype: 'hidden', name: 'kotaid' },
                                { xtype: 'hidden', name: 'jenisformid' },
                                { xtype: 'hidden', name: 'tglmulai' },
                                { xtype: 'hidden', name: 'tglselesai' },
                                { xtype: 'hidden', name: 'gol' },
                                { xtype: 'hidden', name: 'pegawaiid' },
                                { xtype: 'hidden', name: 'hotelprice' },
                                { xtype: 'hidden', name: 'totalhistory' },
                                { xtype: 'hidden', name: 'idsharing' },
                                { xtype: 'hidden', name: 'sharingbudget' },
                                { xtype: 'hidden', name: 'relasiid' },
                                { xtype: 'hidden', name: 'grandacco' },
                                { xtype: 'hidden', name: 'grandtiket' },
                                {
                                    layout: 'column',
                                    baseCls: 'x-plain',
                                    border: false,
                                    items: [
                                        {
                                            xtype: 'panel',
                                            columnWidth: 0.5,
                                            bodyPadding: 10,
                                            layout: 'form',
                                            defaultType: 'displayfield',
                                            baseCls: 'x-plain',
                                            border: false,
                                            defaults: {
                                                labelWidth: 170
                                            },
                                            items: [
                                                { xtype: 'displayfield', fieldLabel: 'NIK', name: 'nik', anchor: '95%' },
                                                { xtype: 'displayfield', fieldLabel: 'Nama', name: 'nama', anchor: '95%' },
                                                { xtype: 'displayfield', fieldLabel: 'Jabatan', name: 'jabatan', anchor: '95%' },
                                                { xtype: 'displayfield', fieldLabel: 'Periode', name: 'periode', anchor: '95%' }
                                            ]
                                        },
                                        {
                                            xtype: 'panel',
                                            columnWidth: 0.5,
                                            bodyPadding: 10,
                                            layout: 'form',
                                            defaultType: 'displayfield',
                                            baseCls: 'x-plain',
                                            border: false,
                                            defaults: {
                                                labelWidth: 170
                                            },
                                            items: [{ xtype: 'displayfield', fieldLabel: 'Level', name: 'level', anchor: '95%' }, { xtype: 'displayfield', fieldLabel: 'Divisi', name: 'divisi', anchor: '95%' }, { xtype: 'displayfield', fieldLabel: 'Lokasi', name: 'lokasi', anchor: '95%' }]
                                        }
                                    ]
                                }
                            ]
                        },

                        {
                            xtype: 'fieldset',
                            title: 'Kontak Selama Ketidakhadiran',
                            collapsible: true,
                            items: [{ xtype: 'displayfield', fieldLabel: 'HP', name: 'hp', anchor: '95%' }, { id: 'id_lampiran', xtype: 'displayfield', fieldLabel: 'Lampiran', anchor: '95%' }]
                        },

                        {
                            xtype: 'fieldset',
                            title: 'Pengajuan Perjalanan Dinas',
                            collapsible: true,
                            style: 'padding: 5px 10px 5px 10px',
                            items: [
                                { xtype: 'displayfield', fieldLabel: 'Status', name: 'status', anchor: '95%' },
                                { xtype: 'displayfield', id: 'id_namasharing', fieldLabel: 'Sharing Dengan', name: 'namasharing', anchor: '95%' },
                                { xtype: 'displayfield', id: 'id_namasharinglap', fieldLabel: 'Sharing Dengan', name: 'namasharing_lap', anchor: '95%' },
                                {
                                    style: 'padding-bottom: 10px',
                                    xtype: 'grid',
                                    store: Ext.create('Ext.data.Store', {
                                        storeId: 'storeDetailPengajuanCuti',
                                        autoLoad: false,
                                        proxy: {
                                            type: 'ajax',
                                            url: Settings.SITE_URL + '/perjalanandinas/getDetailPengajuanCuti',
                                            actionMethods: {
                                                create: 'POST',
                                                read: 'POST'
                                            },
                                            reader: {
                                                type: 'json',
                                                root: 'data',
                                                totalProperty: 'count'
                                            }
                                        },
                                        fields: ['detailpengajuanid', 'jenisformid', 'jenisform', 'tglmulai', 'tglselesai', 'lama', 'satuan', 'keperluan', 'status', 'kotaid', 'kota', 'jenisperjalananid', 'negaraid', 'negara']
                                    }),
                                    columns: [
                                        { text: 'No', xtype: 'rownumberer', width: 30 },
                                        { text: 'Jenis Form', dataIndex: 'jenisform', width: 300 },
                                        {
                                            text: 'Tujuan',
                                            dataIndex: 'jenisformid',
                                            width: 200,
                                            renderer: (Ext.util.Format.Currency = function(v, meta, record, rowIndex) {
                                                if (typeof v === 'undefined') {
                                                    var v = '-';
                                                    return v;
                                                } else {
                                                    if (v == '1' || v == '2') {
                                                        var v = record.get('kota');
                                                        return v;
                                                    } else {
                                                        var v = record.get('negara');
                                                        return v;
                                                    }
                                                }
                                            })
                                        },
                                        {
                                            text: 'Jenis Perjalanan',
                                            dataIndex: 'jenisperjalananid',
                                            width: 150,
                                            renderer: (Ext.util.Format.Currency = function(v, meta, record, rowIndex) {
                                                if (typeof v === 'undefined') {
                                                    var v = '-';
                                                    return v;
                                                } else {
                                                    if (v == '1') {
                                                        var v = 'No Invitation';
                                                        return v;
                                                    } else {
                                                        var v = 'Invitation';
                                                        return v;
                                                    }
                                                }
                                            })
                                        },
                                        { text: 'Keperluan', dataIndex: 'keperluan', flex: 1 },
                                        { text: 'Tanggal Tugas', dataIndex: 'tglmulai' },
                                        { text: 'Tanggal Kembali', dataIndex: 'tglselesai' },
                                        {
                                            text: 'Lama Tugas',
                                            dataIndex: 'lama',
                                            renderer: function(value, p, r) {
                                                return r.data['lama'] + ' hari ' + (r.data['lama'] - '1' + ' malam');
                                            }
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            width: 200,
                            border: false,
                            style: 'bottom:11px',
                            buttons: [
                                {
                                    id: 'id_buttonsharing',
                                    text: 'Sharing Budget',
                                    handler: function() {
                                        var form = me.down('#id_form_detailcuti').getForm();
                                        var sharingbudget = form.findField('sharingbudget').getValue();
                                        if (sharingbudget == '1') {
                                            Ext.Msg.show({
                                                title: 'Attention',
                                                msg: 'Anda Telah Melakukan Sharing Budget',
                                                buttons: Ext.Msg.OK,
                                                icon: Ext.Msg.QUESTION
                                            });
                                        } else {
                                            if (Ext.getCmp('id_sharingbudget').isVisible()) Ext.getCmp('id_sharingbudget').setVisible(false);
                                            else Ext.getCmp('id_sharingbudget').setVisible(true);
                                        }
                                    }
                                },
                                {
                                    id: 'id_cancelbuttonsharing',
                                    text: 'Cancel Sharing',
                                    handler: function() {
                                        var form = me.down('#id_form_detailcuti').getForm();
                                        var pengajuanid = form.findField('pengajuanid').getValue();
                                        var hotelprice = form.findField('hotelprice').getValue();
                                        var periode = form.findField('periode').getValue();
                                        var aid = form.findField('aid').getValue();
                                        var accomodation = form.findField('accomodation').getValue();
                                        var totalhistory = form.findField('totalhistory').getValue();
                                        var sharingbudget = form.findField('sharingbudget').getValue();
                                        var idsharing = form.findField('idsharing').getValue();

                                        if (idsharing == '1') {
                                            Ext.Msg.show({
                                                title: 'Attention',
                                                msg: 'Anda Telah Melakukan Sharing Budget',
                                                buttons: Ext.Msg.OK,
                                                icon: Ext.Msg.QUESTION
                                            });
                                        } else if (sharingbudget == '' || sharingbudget == '0') {
                                            Ext.Msg.show({
                                                title: 'Attention',
                                                msg: 'Sharing Budget Belum Aktif',
                                                buttons: Ext.Msg.OK,
                                                icon: Ext.Msg.QUESTION
                                            });
                                        } else if (sharingbudget == '1') {
                                            Ext.Msg.show({
                                                title: 'Attention',
                                                msg: 'Maaf Cancel Sharing Bugdet Tidak Dapat Dilakukan',
                                                buttons: Ext.Msg.OK,
                                                icon: Ext.Msg.QUESTION
                                            });
                                        } else {
                                            Ext.Msg.show({
                                                title: 'Cancel Sharing',
                                                msg: 'Cancel Sharing Budget',
                                                buttons: Ext.Msg.YESNO,
                                                icon: Ext.Msg.QUESTION,
                                                fn: function(btn) {
                                                    if (btn == 'yes') {
                                                        Ext.Ajax.request({
                                                            url: Settings.SITE_URL + '/perjalanandinas/cancelSharingBudget',
                                                            method: 'POST',
                                                            params: {
                                                                pengajuanid: pengajuanid,
                                                                hotelprice: hotelprice,
                                                                periode: periode,
                                                                aid: aid,
                                                                accomodation: accomodation,
                                                                totalhistory: totalhistory,
                                                                idsharing: idsharing
                                                            },
                                                            success: function(response) {
                                                                // var obj = Ext.decode(response.responseText);
                                                                // if (obj.success) {
                                                                location.reload();
                                                                // }
                                                            }
                                                        });
                                                    }
                                                }
                                            });
                                        }
                                    }
                                }
                            ]
                        },

                        // EDIT 11 MEI 2020

                        // END EDIT 11 MEI 2020

                        {
                            xtype: 'fieldset',
                            title: 'Sharing Budget',
                            collapsible: true,
                            style: 'padding: 5px 10px 5px 10px',
                            id: 'id_sharingbudget',
                            items: [
                                {
                                    xtype: 'grid',
                                    store: Ext.create('Ext.data.Store', {
                                        storeId: 'storeSharingBudget',
                                        autoLoad: false,
                                        proxy: {
                                            type: 'ajax',
                                            url: Settings.SITE_URL + '/perjalanandinas/getSharingBudget',
                                            actionMethods: {
                                                create: 'POST',
                                                read: 'POST'
                                            },
                                            reader: {
                                                type: 'json',
                                                root: 'data',
                                                totalProperty: 'count'
                                            }
                                        },
                                        fields: ['pegawaiid', 'detailpengajuanid', 'email', 'tglmulai', 'tglselesai', 'kota', 'pegawaiid', 'levelid', 'nik', 'pengajuanid', 'sharingbudget', 'idacco', 'avail', 'hotelprice', 'periode', 'level', 'namadepan', 'gol', 'totalhistory', 'accomodationhistory']
                                    }),
                                    columns: [
                                        { text: 'No', xtype: 'rownumberer', width: 30 },
                                        { text: 'Nik', dataIndex: 'nik' },
                                        { text: 'Nama', dataIndex: 'namadepan', width: 200 },
                                        { text: 'Tanggal Mulai', dataIndex: 'tglmulai', width: 200 },
                                        { text: 'Tanggal Selesai', dataIndex: 'tglselesai', width: 200 },
                                        { text: 'Tujuan Kota', dataIndex: 'kota', width: 200 },
                                        { text: 'Jabatan', dataIndex: 'level', width: 200 },
                                        {
                                            xtype: 'checkcolumn',
                                            text: 'Sharing',
                                            allowBlank: false,
                                            listeners: {
                                                beforecheckchange: function(me, rowIndex, checked, record, e, eOpts) {
                                                    return false;
                                                }
                                            },
                                            editor: { xtype: 'checkbox' },
                                            flex: 1
                                        }
                                    ],
                                    plugins: [
                                        {
                                            ptype: 'cellediting',
                                            clicksToEdit: 1
                                        }
                                    ],

                                    listeners: {
                                        edit: function(editor, e) {
                                            if (statusid == '5' && Settings.userid == '1235') {
                                                var form = me.down('#id_form_detailcuti').getForm();
                                                var pengajuanid = e.record.data['pengajuanid'];
                                                var pegawaiid = e.record.data['pegawaiid'];
                                                var totalhistory = e.record.data['totalhistory'];
                                                var accomodationhistory = e.record.data['accomodationhistory'];
                                                var avail = e.record.data['avail'];
                                                var hotelprice = e.record.data['hotelprice'];
                                                var idacco = e.record.data['idacco'];
                                                var periode = e.record.data['periode'];
                                                var pengajuanidsharing = form.findField('pengajuanid').getValue();
                                                var pegawaiidsharing = form.findField('pegawaiid').getValue();
                                                var namasharing = e.record.data['namadepan'];
                                                var nama = form.findField('nama').getValue();
                                                var niksharing = e.record.data['nik'];
                                                var nik = form.findField('nik').getValue();
                                                var emailsharing = e.record.data['email'];
                                                var email = form.findField('email').getValue();

                                                var tglpermohonan = form.findField('tglpermohonan').getValue();

                                                Ext.Msg.show({
                                                    title: 'Sharing Budget',
                                                    msg: 'Apakah anda yakin sharing budget',
                                                    buttons: Ext.Msg.YESNO,
                                                    icon: Ext.Msg.QUESTION,
                                                    fn: function(btn) {
                                                        Ext.Ajax.request({
                                                            url: Settings.SITE_URL + '/perjalanandinas/updSharingBudget',
                                                            method: 'POST',
                                                            params: {
                                                                pegawaiid: pegawaiid,
                                                                pengajuanid: pengajuanid,
                                                                totalhistory: totalhistory,
                                                                accomodationhistory: accomodationhistory,
                                                                hotelprice: hotelprice,
                                                                avail: avail,
                                                                idacco: idacco,
                                                                periode: periode,
                                                                pegawaiidsharing: pegawaiidsharing,
                                                                pengajuanidsharing: pengajuanidsharing,
                                                                nama: nama,
                                                                namasharing: namasharing,
                                                                nik: nik,
                                                                niksharing: niksharing,
                                                                tglpermohonan: tglpermohonan,
                                                                emailsharing: emailsharing,
                                                                email: email
                                                            },
                                                            success: function(response) {
                                                                // var obj = Ext.decode(response.responseText);
                                                                // if (obj.success) {
                                                                location.reload();
                                                                // }
                                                            }
                                                        });
                                                    }
                                                });
                                            }
                                        }
                                    }
                                }
                            ]
                        },

                        {
                            xtype: 'fieldset',
                            title: 'Rincian Pertanggungan',
                            id: 'id_pertanggungan',
                            collapsible: true,
                            style: 'padding: 5px 10px 5px 10px',
                            items: [
                                { xtype: 'displayfield', fieldLabel: 'Pertanggungan', anchor: '95%' },

                                {
                                    xtype: 'grid',
                                    store: Ext.create('Ext.data.Store', {
                                        storeId: 'storeDetailRincianBiayaPertanggungan',
                                        autoLoad: false,
                                        proxy: {
                                            type: 'ajax',
                                            url: Settings.SITE_URL + '/perjalanandinas/getRincianBiayaPertanggungan',
                                            actionMethods: {
                                                create: 'POST',
                                                read: 'POST'
                                            },
                                            reader: {
                                                type: 'json',
                                                root: 'data',
                                                totalProperty: 'count'
                                            }
                                        },
                                        fields: ['pengajuanid', 'pegawaiid', 'nourut', 'status', 'transport', 'hotelprice', 'uangsaku', 'uangmuka', 'kekurangan', 'kelebihan', 'uraian', 'lainlain', 'grandtotal', 'tiket', 'active']
                                    }),
                                    columns: [
                                        { text: 'No', xtype: 'rownumberer', width: 30 },
                                        {
                                            text: 'Uang saku',
                                            dataIndex: 'uangsaku',
                                            width: 100,
                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                v = Math.round((v - 0) * 100) / 100;
                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                v = String(v);
                                                var ps = v.split('.');
                                                var whole = ps[0];
                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                var r = /(\d+)(\d{3})/;
                                                while (r.test(whole)) {
                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                }
                                                v = whole + sub;
                                                if (v.charAt(0) == '-') {
                                                    return '-' + v.substr(1) + '';
                                                }
                                                return v.replace(/\./, '.');
                                            })
                                        },
                                        {
                                            text: 'Tiket',
                                            dataIndex: 'tiket',
                                            // ,editor: { xtype: 'textfield',allowBlank: false }
                                            width: 100,
                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                v = Math.round((v - 0) * 100) / 100;
                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                v = String(v);
                                                var ps = v.split('.');
                                                var whole = ps[0];
                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                var r = /(\d+)(\d{3})/;
                                                while (r.test(whole)) {
                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                }
                                                v = whole + sub;
                                                if (v.charAt(0) == '-') {
                                                    return '-' + v.substr(1) + '';
                                                }
                                                return v.replace(/\./, '.');
                                            })
                                        },
                                        {
                                            text: 'Transportasi',
                                            dataIndex: 'transport',
                                            width: 100,
                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                v = Math.round((v - 0) * 100) / 100;
                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                v = String(v);
                                                var ps = v.split('.');
                                                var whole = ps[0];
                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                var r = /(\d+)(\d{3})/;
                                                while (r.test(whole)) {
                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                }
                                                v = whole + sub;
                                                if (v.charAt(0) == '-') {
                                                    return '-' + v.substr(1) + '';
                                                }
                                                return v.replace(/\./, '.');
                                            })
                                        },
                                        {
                                            text: 'Hotel',
                                            dataIndex: 'hotelprice',
                                            width: 100,
                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                v = Math.round((v - 0) * 100) / 100;
                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                v = String(v);
                                                var ps = v.split('.');
                                                var whole = ps[0];
                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                var r = /(\d+)(\d{3})/;
                                                while (r.test(whole)) {
                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                }
                                                v = whole + sub;
                                                if (v.charAt(0) == '-') {
                                                    return '-' + v.substr(1) + '';
                                                }
                                                return v.replace(/\./, '.');
                                            })
                                        },
                                        {
                                            text: 'Lain - lain',
                                            dataIndex: 'lainlain',
                                            editor: { xtype: 'textfield', allowBlank: false },
                                            width: 100,
                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                v = Math.round((v - 0) * 100) / 100;
                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                v = String(v);
                                                var ps = v.split('.');
                                                var whole = ps[0];
                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                var r = /(\d+)(\d{3})/;
                                                while (r.test(whole)) {
                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                }
                                                v = whole + sub;
                                                if (v.charAt(0) == '-') {
                                                    return '-' + v.substr(1) + '';
                                                }
                                                return v.replace(/\./, '.');
                                            })
                                        },
                                        {
                                            text: 'Total',
                                            dataIndex: 'grandtotal',
                                            width: 115,
                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                v = Math.round((v - 0) * 100) / 100;
                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                v = String(v);
                                                var ps = v.split('.');
                                                var whole = ps[0];
                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                var r = /(\d+)(\d{3})/;
                                                while (r.test(whole)) {
                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                }
                                                v = whole + sub;
                                                if (v.charAt(0) == '-') {
                                                    return '-' + v.substr(1) + '';
                                                }
                                                return "<span style='font-weight:bold' >" + v.replace(/\./, '.') + '</span>';
                                            })
                                        },
                                        {
                                            text: 'Uang muka',
                                            dataIndex: 'uangmuka',
                                            width: 100,
                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                v = Math.round((v - 0) * 100) / 100;
                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                v = String(v);
                                                var ps = v.split('.');
                                                var whole = ps[0];
                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                var r = /(\d+)(\d{3})/;
                                                while (r.test(whole)) {
                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                }
                                                v = whole + sub;
                                                if (v.charAt(0) == '-') {
                                                    return '-' + v.substr(1) + '';
                                                }
                                                return v.replace(/\./, '.');
                                            })
                                        },
                                        {
                                            text: 'Kekurangan yang dibayar',
                                            dataIndex: 'kekurangan',
                                            width: 150,
                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                v = Math.round((v - 0) * 100) / 100;
                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                v = String(v);
                                                var ps = v.split('.');
                                                var whole = ps[0];
                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                var r = /(\d+)(\d{3})/;
                                                while (r.test(whole)) {
                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                }
                                                v = whole + sub;
                                                if (v.charAt(0) == '-') {
                                                    return '-' + v.substr(1) + '';
                                                }
                                                return v.replace(/\./, '.');
                                            })
                                        },
                                        {
                                            text: 'Kelebihan yang dikembalikan',
                                            dataIndex: 'kelebihan',
                                            width: 180,
                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                v = Math.round((v - 0) * 100) / 100;
                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                v = String(v);
                                                var ps = v.split('.');
                                                var whole = ps[0];
                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                var r = /(\d+)(\d{3})/;
                                                while (r.test(whole)) {
                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                }
                                                v = whole + sub;
                                                if (v.charAt(0) == '-') {
                                                    return '-' + v.substr(1) + '';
                                                }
                                                return v.replace(/\./, '.');
                                            })
                                        },

                                        { text: 'Uraian', dataIndex: 'uraian', width: 340 }
                                    ]
                                },
                                {
                                    width: 120,
                                    border: false,
                                    style: 'bottom:0px',
                                    id: 'id_btn_kelebihan',
                                    buttons: [
                                        {
                                            text: 'Diterima Kelebihan',
                                            handler: function() {
                                                if (statusid == '5' && Settings.userid == '1235') {
                                                    var form = me.down('#id_form_detailcuti').getForm();
                                                    var pengajuanid = form.findField('pengajuanid').getValue();
                                                    var kelebihan = form.findField('kelebihan').getValue();
                                                    var active = form.findField('active').getValue();
                                                    if (active == 't') {
                                                        Ext.Msg.show({
                                                            title: 'Attention',
                                                            msg: 'Kelebihan Telah Diterima',
                                                            icon: Ext.Msg.QUESTION
                                                        });
                                                    } else if (kelebihan == '0') {
                                                        Ext.Msg.show({
                                                            title: 'Attention',
                                                            msg: 'Tidak Ada Kelebihan',
                                                            buttons: Ext.Msg.OK,
                                                            icon: Ext.Msg.QUESTION
                                                        });
                                                    } else {
                                                        Ext.Ajax.request({
                                                            url: Settings.SITE_URL + '/perjalanandinas/kelebihanDikembalikan',
                                                            method: 'POST',
                                                            params: {
                                                                pengajuanid: pengajuanid,
                                                                kelebihan: kelebihan
                                                            },
                                                            success: function(response) {
                                                                // var obj = Ext.decode(response.responseText);
                                                                // if(obj.success) {
                                                                location.reload();
                                                                // }
                                                            }
                                                        });
                                                    }
                                                }
                                            }
                                        }
                                    ]
                                }
                            ]
                        },

                        {
                            xtype: 'fieldset',
                            title: 'Rincian Perjalanan',
                            id: 'id_rincian',
                            collapsible: true,
                            items: [
                                {
                                    layout: 'column',
                                    baseCls: 'x-plain',
                                    border: false,
                                    items: [
                                        {
                                            xtype: 'panel',
                                            columnWidth: 0.5,
                                            layout: 'form',
                                            defaultType: 'displayfield',
                                            baseCls: 'x-plain',
                                            border: false,
                                            defaults: {
                                                labelWidth: 170
                                            },
                                            items: [
                                                { xtype: 'displayfield', fieldLabel: 'Rincian Biaya', anchor: '95%' },
                                                {
                                                    xtype: 'grid',
                                                    id: 'id_rincian_dn',
                                                    anchor: '100%',
                                                    store: Ext.create('Ext.data.Store', {
                                                        storeId: 'storeDetailRincianBiayaDN',
                                                        autoLoad: false,
                                                        proxy: {
                                                            type: 'ajax',
                                                            url: Settings.SITE_URL + '/perjalanandinas/getRincianBiayaDinas',
                                                            actionMethods: {
                                                                create: 'POST',
                                                                read: 'POST'
                                                            },
                                                            reader: {
                                                                type: 'json',
                                                                root: 'data',
                                                                totalProperty: 'count'
                                                            }
                                                        },
                                                        fields: [
                                                            'pengajuanid',
                                                            'jenistiketid',
                                                            'jenisformid',
                                                            'jenisperjalananid',
                                                            'uangsaku',
                                                            'biayatiket',
                                                            'transport',
                                                            'hotelprice',
                                                            'lainlain',
                                                            'total',
                                                            'uangsaku_lap',
                                                            'biayatiket_lap',
                                                            'transport_lap',
                                                            'hotelprice_lap',
                                                            'total_lap',
                                                            'addbiayatiket'
                                                        ]
                                                    }),
                                                    columns: [
                                                        { text: 'No', xtype: 'rownumberer', width: 30 },
                                                        {
                                                            text: 'Uang Saku',
                                                            width: 105,
                                                            dataIndex: 'jenisformid',
                                                            renderer: (Ext.util.Format.Currency = function(v, meta, record, rowIndex) {
                                                                if (typeof v === 'undefined') {
                                                                    var v = '-';
                                                                    return v;
                                                                } else {
                                                                    if (v == '1') {
                                                                        var v = record.get('uangsaku');
                                                                        return v;
                                                                    } else {
                                                                        var v = record.get('uangsaku_lap');
                                                                        return v;
                                                                    }
                                                                }
                                                            })
                                                        },
                                                        {
                                                            text: 'Jenis Tiket',
                                                            width: 90,
                                                            dataIndex: 'jenistiketid',
                                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                                if (typeof v === 'undefined') {
                                                                    var v = '-';
                                                                    return v;
                                                                } else {
                                                                    if (v == '1') {
                                                                        var v = 'Pesawat';
                                                                        return v;
                                                                    } else if (v == '2') {
                                                                        var v = 'Kereta Api';
                                                                        return v;
                                                                    } else {
                                                                        var v = 'Travel';
                                                                        return v;
                                                                    }
                                                                }
                                                            })
                                                        },
                                                        {
                                                            text: 'Tiket',
                                                            editor: { xtype: 'textfield', allowBlank: false },
                                                            width: 90,
                                                            dataIndex: 'biayatiket',
                                                            renderer: function(value, meta, record) {
                                                                var val = record.get('jenisformid');
                                                                if (val == '1') {
                                                                    var v = record.get('biayatiket');
                                                                    return v;
                                                                } else if (val == '2') {
                                                                    var v = record.get('biayatiket_lap');
                                                                    return v;
                                                                }
                                                                return ''; // default -> no image at all
                                                            }
                                                        },
                                                        {
                                                            text: 'Transport',
                                                            width: 90,
                                                            dataIndex: 'jenisformid',
                                                            renderer: (Ext.util.Format.Currency = function(v, meta, record, rowIndex) {
                                                                if (typeof v === 'undefined') {
                                                                    var v = '-';
                                                                    return v;
                                                                } else {
                                                                    if (v == '1') {
                                                                        var v = record.get('transport');
                                                                        return v;
                                                                    } else {
                                                                        var v = record.get('transport_lap');
                                                                        return v;
                                                                    }
                                                                }
                                                            })
                                                        },
                                                        {
                                                            text: 'Hotel',
                                                            // editor: { xtype: 'textfield', allowBlank: false },
                                                            width: 90,
                                                            dataIndex: 'jenisformid',
                                                            renderer: (Ext.util.Format.Currency = function(v, meta, record, rowIndex) {
                                                                if (typeof v === 'undefined') {
                                                                    var v = '-';
                                                                    return v;
                                                                } else {
                                                                    if (v == '1') {
                                                                        var v = record.get('hotelprice');
                                                                        return v;
                                                                    } else {
                                                                        var v = record.get('hotelprice_lap');
                                                                        return v;
                                                                    }
                                                                }
                                                            })
                                                        },
                                                        {
                                                            text: 'Lain-lain',
                                                            width: 80,
                                                            dataIndex: 'lainlain',
                                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                                v = Math.round((v - 0) * 100) / 100;
                                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                                v = String(v);
                                                                var ps = v.split('.');
                                                                var whole = ps[0];
                                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                                var r = /(\d+)(\d{3})/;
                                                                while (r.test(whole)) {
                                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                                }
                                                                v = whole + sub;
                                                                if (v.charAt(0) == '-') {
                                                                    return '-' + v.substr(1) + '';
                                                                }
                                                                return v.replace(/\./, '.');
                                                            })
                                                        },
                                                        {
                                                            text: 'Total',
                                                            dataIndex: 'total',
                                                            width: 100,
                                                            dataIndex: 'jenisformid',
                                                            renderer: (Ext.util.Format.Currency = function(v, meta, record, rowIndex) {
                                                                if (typeof v === 'undefined') {
                                                                    var v = '-';
                                                                    return v;
                                                                } else {
                                                                    if (v == '1') {
                                                                        var v = record.get('total');
                                                                        return v;
                                                                    } else {
                                                                        var v = record.get('total_lap');
                                                                        return v;
                                                                    }
                                                                }
                                                            })
                                                        }
                                                    ],
                                                    plugins: [
                                                        {
                                                            ptype: 'cellediting',
                                                            clicksToEdit: 1
                                                        }
                                                    ],
                                                    listeners: {
                                                        edit: function(editor, e) {
                                                            if (statusid == '5' && Settings.userid == '1235' && jenistiketid == '1' && jenisformid == '1') {
                                                                var form = me.down('#id_form_detailcuti').getForm();
                                                                var pengajuanid = form.findField('pengajuanid').getValue();
                                                                var periode = form.findField('periode').getValue();
                                                                var tid = form.findField('tid').getValue();
                                                                var budgettiket = form.findField('ticket').getValue();
                                                                var totalhistory = form.findField('totalhistory').getValue();
                                                                var tiket = e.record.data['biayatiket'];
                                                                var biayatiket = e.record.data['addbiayatiket'];

                                                                Ext.Ajax.request({
                                                                    url: Settings.SITE_URL + '/perjalanandinas/addTicket',
                                                                    method: 'POST',
                                                                    params: {
                                                                        pengajuanid: pengajuanid,
                                                                        tiket: tiket,
                                                                        periode: periode,
                                                                        tid: tid,
                                                                        budgettiket: budgettiket,
                                                                        totalhistory: totalhistory,
                                                                        biayatiket: biayatiket
                                                                    },
                                                                    success: function(response) {
                                                                        var obj = Ext.decode(response.responseText);
                                                                        if (obj.success) {
                                                                            location.reload();
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        }
                                                    }
                                                },
                                                {
                                                    xtype: 'grid',
                                                    id: 'id_rincian_ln',
                                                    anchor: '100%',
                                                    store: Ext.create('Ext.data.Store', {
                                                        storeId: 'storeDetailRincianBiayaLN',
                                                        autoLoad: false,
                                                        proxy: {
                                                            type: 'ajax',
                                                            url: Settings.SITE_URL + '/perjalanandinas/getRincianBiayaDinas',
                                                            actionMethods: {
                                                                create: 'POST',
                                                                read: 'POST'
                                                            },
                                                            reader: {
                                                                type: 'json',
                                                                root: 'data',
                                                                totalProperty: 'count'
                                                            }
                                                        },
                                                        fields: [
                                                            'pengajuanid',
                                                            'jenistiketid',
                                                            'jenisformid',
                                                            'jenisperjalananid',
                                                            'southeastasia',
                                                            'non_southeastasia',
                                                            'japan',
                                                            'eropa_usa_dubai',
                                                            'total_ln',
                                                            'lainlain_ln',
                                                            'southeastasia_lap',
                                                            'non_southeastasia_lap',
                                                            'japan_lap',
                                                            'eropa_usa_dubai_lap',
                                                            'total_lap_ln'
                                                        ]
                                                    }),
                                                    columns: [
                                                        { text: 'No', xtype: 'rownumberer', width: 30 },
                                                        {
                                                            text: 'Jenis Tiket',
                                                            width: 80,
                                                            dataIndex: 'jenistiketid',
                                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                                if (typeof v === 'undefined') {
                                                                    var v = '-';
                                                                    return v;
                                                                } else {
                                                                    if (v == '1') {
                                                                        var v = 'Pesawat';
                                                                        return v;
                                                                    } else if (v == '2') {
                                                                        var v = 'Kereta Api';
                                                                        return v;
                                                                    } else {
                                                                        var v = 'Travel';
                                                                        return v;
                                                                    }
                                                                }
                                                            })
                                                        },
                                                        {
                                                            text: 'South East Asia',
                                                            width: 90,
                                                            dataIndex: 'jenisformid',
                                                            renderer: (Ext.util.Format.Currency = function(v, meta, record, rowIndex) {
                                                                if (typeof v === 'undefined') {
                                                                    var v = '-';
                                                                    return v;
                                                                } else {
                                                                    if (v == '3') {
                                                                        var v = record.get('southeastasia');
                                                                        return v;
                                                                    } else {
                                                                        var v = record.get('southeastasia_lap');
                                                                        return v;
                                                                    }
                                                                }
                                                            })
                                                        },
                                                        {
                                                            text: 'Non South East Asia',
                                                            width: 100,
                                                            dataIndex: 'jenisformid',
                                                            renderer: (Ext.util.Format.Currency = function(v, meta, record, rowIndex) {
                                                                if (typeof v === 'undefined') {
                                                                    var v = '-';
                                                                    return v;
                                                                } else {
                                                                    if (v == '3') {
                                                                        var v = record.get('non_southeastasia');
                                                                        return v;
                                                                    } else {
                                                                        var v = record.get('non_southeastasia_lap');
                                                                        return v;
                                                                    }
                                                                }
                                                            })
                                                        },
                                                        {
                                                            text: 'Japan',
                                                            width: 80,
                                                            dataIndex: 'jenisformid',
                                                            renderer: (Ext.util.Format.Currency = function(v, meta, record, rowIndex) {
                                                                if (typeof v === 'undefined') {
                                                                    var v = '-';
                                                                    return v;
                                                                } else {
                                                                    if (v == '3') {
                                                                        var v = record.get('japan');
                                                                        return v;
                                                                    } else {
                                                                        var v = record.get('japan_lap');
                                                                        return v;
                                                                    }
                                                                }
                                                            })
                                                        },
                                                        {
                                                            text: 'Eropa / USA / Dubai',
                                                            width: 105,
                                                            dataIndex: 'jenisformid',
                                                            renderer: (Ext.util.Format.Currency = function(v, meta, record, rowIndex) {
                                                                if (typeof v === 'undefined') {
                                                                    var v = '-';
                                                                    return v;
                                                                } else {
                                                                    if (v == '3') {
                                                                        var v = record.get('eropa_usa_dubai');
                                                                        return v;
                                                                    } else {
                                                                        var v = record.get('eropa_usa_dubai_lap');
                                                                        return v;
                                                                    }
                                                                }
                                                            })
                                                        },
                                                        {
                                                            text: 'Lain-lain',
                                                            width: 80,
                                                            dataIndex: 'lainlain_ln',
                                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                                v = Math.round((v - 0) * 100) / 100;
                                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                                v = String(v);
                                                                var ps = v.split('.');
                                                                var whole = ps[0];
                                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                                var r = /(\d+)(\d{3})/;
                                                                while (r.test(whole)) {
                                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                                }
                                                                v = whole + sub;
                                                                if (v.charAt(0) == '-') {
                                                                    return '-' + v.substr(1) + '';
                                                                }
                                                                return v.replace(/\./, '.');
                                                            })
                                                        },
                                                        {
                                                            text: 'Total',
                                                            width: 100,
                                                            dataIndex: 'jenisformid',
                                                            renderer: (Ext.util.Format.Currency = function(v, meta, record, rowIndex) {
                                                                if (typeof v === 'undefined') {
                                                                    var v = '-';
                                                                    return v;
                                                                } else {
                                                                    if (v == '3') {
                                                                        var v = record.get('total_ln');
                                                                        return v;
                                                                    } else {
                                                                        var v = record.get('total_lap_ln');
                                                                        return v;
                                                                    }
                                                                }
                                                            })
                                                        }
                                                    ]
                                                },
                                                { xtype: 'hidden' }
                                            ]
                                        },
                                        { xtype: 'panel', anchor: '60% 40%' },
                                        {
                                            xtype: 'panel',
                                            columnWidth: 0.5,
                                            layout: 'form',
                                            defaultType: 'displayfield',
                                            baseCls: 'x-plain',
                                            border: false,
                                            defaults: {
                                                labelWidth: 170
                                            },
                                            items: [
                                                { xtype: 'displayfield', id: 'id_budget_label', fieldLabel: 'Actual Data From Sap', anchor: '95%' },
                                                {
                                                    xtype: 'grid',
                                                    id: 'id_budget',
                                                    anchor: '100%',
                                                    store: Ext.create('Ext.data.Store', {
                                                        storeId: 'storeDetailBudgetFromSap',
                                                        autoLoad: false,
                                                        proxy: {
                                                            type: 'ajax',
                                                            url: Settings.SITE_URL + '/perjalanandinas/getBudgetFromSap',
                                                            actionMethods: {
                                                                create: 'POST',
                                                                read: 'POST'
                                                            },
                                                            reader: {
                                                                type: 'json',
                                                                root: 'data',
                                                                totalProperty: 'count'
                                                            }
                                                        },
                                                        fields: ['Aufnr', 'Ktext', 'Allott', { name: 'Budget', type: 'integer' }, 'Avail']
                                                    }),
                                                    columns: [
                                                        { header: 'No', xtype: 'rownumberer', width: 30 },
                                                        {
                                                            header: 'Orders',
                                                            dataIndex: 'Aufnr',
                                                            renderer: function(value, element, record) {
                                                                return record.data['Aufnr'] + '	' + record.data['Ktext'];
                                                            },
                                                            width: 250
                                                        },
                                                        {
                                                            text: 'Budget',
                                                            dataIndex: 'Budget',
                                                            width: 115,
                                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                                v = Math.round((v - 0) * 100) / 100;
                                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                                v = String(v);
                                                                var ps = v.split('.');
                                                                var whole = ps[0];
                                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                                var r = /(\d+)(\d{3})/;
                                                                while (r.test(whole)) {
                                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                                }
                                                                v = whole + sub;
                                                                if (v.charAt(0) == '-') {
                                                                    return '-' + v.substr(1) + '';
                                                                }
                                                                return v.replace(/\./, '.');
                                                            })
                                                        },
                                                        {
                                                            text: 'Allotted',
                                                            dataIndex: 'Allott',
                                                            width: 120,
                                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                                v = Math.round((v - 0) * 100) / 100;
                                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                                v = String(v);
                                                                var ps = v.split('.');
                                                                var whole = ps[0];
                                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                                var r = /(\d+)(\d{3})/;
                                                                while (r.test(whole)) {
                                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                                }
                                                                v = whole + sub;
                                                                if (v.charAt(0) == '-') {
                                                                    return '-' + v.substr(1) + '';
                                                                }
                                                                return v.replace(/\./, '.');
                                                            })
                                                        },
                                                        {
                                                            text: 'Available',
                                                            dataIndex: 'Avail',
                                                            width: 134,
                                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                                v = Math.round((v - 0) * 100) / 100;
                                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                                v = String(v);
                                                                var ps = v.split('.');
                                                                var whole = ps[0];
                                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                                var r = /(\d+)(\d{3})/;
                                                                while (r.test(whole)) {
                                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                                }
                                                                v = whole + sub;
                                                                if (v.charAt(0) == '-') {
                                                                    return '-' + v.substr(1) + '';
                                                                }
                                                                var value = v.replace(/\./, '.');
                                                                return "<span style='font-weight:bold' >" + value + '</span>';
                                                            })
                                                        }
                                                    ]
                                                },
                                                { xtype: 'displayfield', id: 'id_local_label', fieldLabel: 'Data From Database', anchor: '95%' },
                                                {
                                                    xtype: 'grid',
                                                    id: 'id_local',
                                                    anchor: '100%',
                                                    store: Ext.create('Ext.data.Store', {
                                                        storeId: 'storeDetailBudgetFromLocalDb',
                                                        autoLoad: false,
                                                        proxy: {
                                                            type: 'ajax',
                                                            url: Settings.SITE_URL + '/perjalanandinas/getBudgetFromLocalDb',
                                                            actionMethods: {
                                                                create: 'POST',
                                                                read: 'POST'
                                                            },
                                                            reader: {
                                                                type: 'json',
                                                                root: 'data',
                                                                totalProperty: 'count'
                                                            }
                                                        },
                                                        fields: ['tid', 'nticket', 'ticket', 'aid', 'naccomodation', 'accomodation', 'total', 'grandtotal', 'normalisasi']
                                                    }),
                                                    columns: [
                                                        { header: 'No', xtype: 'rownumberer', width: 30 },
                                                        {
                                                            header: 'Orders',
                                                            dataIndex: 'tid',
                                                            align: 'center',
                                                            renderer: function(value, element, record) {
                                                                return record.data['tid'] + '	' + record.data['nticket'];
                                                            },
                                                            width: 180
                                                        },
                                                        {
                                                            header: 'Available',
                                                            dataIndex: 'ticket',
                                                            width: 129,
                                                            align: 'center',
                                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                                v = Math.round((v - 0) * 100) / 100;
                                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                                v = String(v);
                                                                var ps = v.split('.');
                                                                var whole = ps[0];
                                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                                var r = /(\d+)(\d{3})/;
                                                                while (r.test(whole)) {
                                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                                }
                                                                v = whole + sub;
                                                                if (v.charAt(0) == '-') {
                                                                    return '-' + v.substr(1) + '';
                                                                }
                                                                var value = v.replace(/\./, '.');
                                                                return "<span style='font-weight:bold' >" + value + '</span>';
                                                            })
                                                        },
                                                        {
                                                            header: 'Orders',
                                                            dataIndex: 'aid',
                                                            align: 'center',
                                                            renderer: function(value, element, record) {
                                                                return record.data['aid'] + '	' + record.data['naccomodation'];
                                                            },
                                                            width: 180
                                                        },
                                                        {
                                                            header: 'Available',
                                                            dataIndex: 'accomodation',
                                                            width: 129,
                                                            align: 'center',
                                                            renderer: (Ext.util.Format.Currency = function(v) {
                                                                v = Math.round((v - 0) * 100) / 100;
                                                                v = v == Math.floor(v) ? v + '' : v * 10 == Math.floor(v * 10) ? v + '0' : v;
                                                                v = String(v);
                                                                var ps = v.split('.');
                                                                var whole = ps[0];
                                                                var sub = ps[1] ? ',' + ps[1] : '';
                                                                var r = /(\d+)(\d{3})/;
                                                                while (r.test(whole)) {
                                                                    whole = whole.replace(r, '$1' + '.' + '$2');
                                                                }
                                                                v = whole + sub;
                                                                if (v.charAt(0) == '-') {
                                                                    return '-' + v.substr(1) + '';
                                                                }
                                                                var value = v.replace(/\./, '.');
                                                                return "<span style='font-weight:bold' >" + value + '</span>';
                                                            })
                                                        }
                                                    ]
                                                },
                                                { xtype: 'hidden', name: 'avail' },
                                                { xtype: 'hidden', name: 'budget' }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        },

                        {
                            xtype: 'fieldset',
                            id: 'id_pelimpahan',
                            title: 'Pelimpahan Tanggung Jawab',
                            collapsible: true,
                            items: [
                                {
                                    layout: 'column',
                                    baseCls: 'x-plain',
                                    border: false,
                                    items: [
                                        {
                                            xtype: 'panel',
                                            columnWidth: 0.5,
                                            bodyPadding: 10,
                                            layout: 'form',
                                            defaultType: 'displayfield',
                                            baseCls: 'x-plain',
                                            border: false,
                                            defaults: {
                                                labelWidth: 170
                                            },
                                            items: [
                                                { xtype: 'displayfield', fieldLabel: 'NIK', name: 'pelimpahannik', anchor: '95%' },
                                                { xtype: 'displayfield', fieldLabel: 'Nama', name: 'pelimpahannama', anchor: '95%' },
                                                { xtype: 'displayfield', fieldLabel: 'HP', name: 'pelimpahanhp', anchor: '95%' },
                                                { xtype: 'displayfield', fieldLabel: 'Jabatan', name: 'pelimpahanjabatan', anchor: '95%' }
                                            ]
                                        },
                                        {
                                            xtype: 'panel',
                                            columnWidth: 0.5,
                                            bodyPadding: 10,
                                            layout: 'form',
                                            defaultType: 'displayfield',
                                            baseCls: 'x-plain',
                                            border: false,
                                            defaults: {
                                                labelWidth: 170
                                            },
                                            items: [
                                                { xtype: 'displayfield', fieldLabel: 'Divisi', name: 'pelimpahansatker', anchor: '95%' },
                                                { xtype: 'displayfield', fieldLabel: 'Lokasi', name: 'pelimpahanlokasi', anchor: '95%' },
                                                { xtype: 'displayfield', fieldLabel: 'Unit Bisnis', name: 'pelimpahanunitbisnis', anchor: '95%' }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            xtype: 'panel',
                            layout: 'column',
                            border: false,
                            items: [
                                {
                                    xtype: 'fieldset',
                                    columnWidth: 0.5,
                                    title: 'Diperiksa oleh',
                                    collapsible: true,
                                    defaultType: 'displayfield',
                                    style: 'margin-right:5px;',
                                    defaults: { anchor: '100%' },
                                    layout: 'anchor',
                                    items: [{ fieldLabel: 'Nama', name: 'atasannama' }, { fieldLabel: 'Jabatan', name: 'atasanjabatan' }]
                                },
                                {
                                    xtype: 'fieldset',
                                    columnWidth: 0.5,
                                    title: 'Disetujui oleh',
                                    collapsible: true,
                                    defaultType: 'displayfield',
                                    style: 'margin-left:5px;',
                                    defaults: { anchor: '100%' },
                                    layout: 'anchor',
                                    items: [{ fieldLabel: 'Nama', name: 'atasan2nama' }, { fieldLabel: 'Jabatan', name: 'atasan2jabatan' }]
                                }
                            ]
                        }
                    ]
                }
            ],
            tbar: [
                {
                    text: 'Kembali',
                    glyph: 'xf060@FontAwesome',
                    handler: function() {
                        Ext.History.add('#perjalanandinas');
                    }
                },
                '->',

                // {
                //     id: 'id_btn_cetak',
                //     glyph: 'xf02f@FontAwesome',
                //     text: 'Cetak',
                //     handler: function() {
                //         var m = Ext.getStore('storeDetailPengajuanCuti').proxy.extraParams;
                //         window.open(Settings.SITE_URL + '/perjalanandinas/cetakdokumen?' + objectParametize(m));
                //     }
                // },

                {
                    id: 'id_btn_cetak_advance',
                    glyph: 'xf02f@FontAwesome',
                    text: 'Cetak',
                    handler: function() {
                        var form = me.down('#id_form_detailcuti').getForm();
                        var jenisperjalananid = form.findField('jenisperjalananid').getValue();
                        var jenisformid = form.findField('jenisformid').getValue();
                        var kotaid = form.findField('kotaid').getValue();
                        if (jenisformid == '1') {
                            var m = Ext.getStore('storeDetailPengajuanCuti').proxy.extraParams;
                            window.open(Settings.SITE_URL + '/perjalanandinas/cetakdokumenadvance_dn?' + objectParametize(m));
                        } else if (jenisformid == '3') {
                            var m = Ext.getStore('storeDetailPengajuanCuti').proxy.extraParams;
                            window.open(Settings.SITE_URL + '/perjalanandinas/cetakdokumenadvance_ln?' + objectParametize(m));
                        }
                    }
                },

                {
                    id: 'id_btn_cetak_lpj',
                    glyph: 'xf02f@FontAwesome',
                    text: 'Cetak',
                    handler: function() {
                        var form = me.down('#id_form_detailcuti').getForm();
                        var jenisperjalananid = form.findField('jenisperjalananid').getValue();
                        var jenisformid = form.findField('jenisformid').getValue();
                        if (jenisformid == '2') {
                            var m = Ext.getStore('storeDetailPengajuanCuti').proxy.extraParams;
                            window.open(Settings.SITE_URL + '/perjalanandinas/cetakdokumenlpj_dn?' + objectParametize(m));
                        } else {
                            var m = Ext.getStore('storeDetailPengajuanCuti').proxy.extraParams;
                            window.open(Settings.SITE_URL + '/perjalanandinas/cetakdokumenlpj_ln?' + objectParametize(m));
                        }
                    }
                },
                {
                    id: 'id_btn_cetak_batal',
                    glyph: 'xf02f@FontAwesome',
                    text: 'Cetak',
                    handler: function() {
                        var form = me.down('#id_form_detailcuti').getForm();
                        var jenisperjalananid = form.findField('jenisperjalananid').getValue();
                        var jenisformid = form.findField('jenisformid').getValue();
                        if (jenisformid == '1' || jenisformid == '3') {
                            var m = Ext.getStore('storeDetailPengajuanCuti').proxy.extraParams;
                            window.open(Settings.SITE_URL + '/perjalanandinas/cetakdokumenbatal?' + objectParametize(m));
                        }
                    }
                },

                {
                    id: 'id_btn_disetujui',
                    glyph: 'xf058@FontAwesome',
                    text: 'Diterima',
                    hidden: true,
                    handler: function() {
                        var form = me.down('#id_form_detailcuti').getForm();
                        var pengajuanid = form.findField('pengajuanid').getValue();
                        var nik = form.findField('nik').getValue();
                        var nama = form.findField('nama').getValue();
                        var grandtotal = form.findField('grandtotal').getValue();
                        var normalisasi = form.findField('normalisasi').getValue();
                        var aid = form.findField('aid').getValue();
                        var tid = form.findField('tid').getValue();
                        var accomodation = form.findField('accomodation').getValue();
                        var ticket = form.findField('ticket').getValue();
                        var sum_a = form.findField('accomodation').getValue();
                        var sum_t = form.findField('ticket').getValue();
                        var tglpermohonan = form.findField('tglpermohonan').getValue();
                        var grandacco = form.findField('grandacco').getValue();
                        var grandtiket = form.findField('grandtiket').getValue();
                        var periode = form.findField('periode').getValue();
                        // update tgl approve
                        var tglapprove = form.findField('tglapprove').getValue();
                        var tgladmga = form.findField('tgladmga').getValue();
                        var tglassmanga = form.findField('tglassmanga').getValue();
                        var tglheadga = form.findField('tglheadga').getValue();
                        var tgladmfa = form.findField('tgladmfa').getValue();
                        var tglheadfa = form.findField('tglheadfa').getValue();
                        var kelebihan = form.findField('kelebihan').getValue();
                        var kekurangan = form.findField('kekurangan').getValue();
                        var active = form.findField('active').getValue();
                        var sharingbudget = form.findField('sharingbudget').getValue();
                        var relasiid = form.findField('relasiid').getValue();
                        var jenisformid = form.findField('jenisformid').getValue();

                        if (statusid == '5' || statusid == '7' || statusid == '9') {
                            if (statusid == '5') {
                                if (tgladmga != '') {
                                    tgladmga = tgladmga;
                                } else {
                                    tgladmga = tglapprove;
                                }
                            } else if (statusid == '7') {
                                if (tglassmanga != '') {
                                    tglassmanga = tglassmanga;
                                } else {
                                    tglassmanga = tglapprove;
                                }
                            } else if (statusid == '9') {
                                if (tglheadga != '') {
                                    tglheadga = tglheadga;
                                } else {
                                    tglheadga = tglapprove;
                                }
                            }
                        } else if (statusid == '11' || statusid == '13') {
                            if (statusid == '11') {
                                if (tgladmfa != '') {
                                    tgladmfa = tgladmfa;
                                } else {
                                    tgladmfa = tglapprove;
                                }
                            } else {
                                if (tglheadfa != '') {
                                    tglheadfa = tglheadfa;
                                } else {
                                    tglheadfa = tglapprove;
                                }
                            }
                        }
                        // kondisi apabila user GA lupa ceklist kelebihan //
                        if (kelebihan > 0 && active == '') {
                            Ext.Msg.show({
                                title: 'Approve Status Kelebihan',
                                msg: 'Anda harus melakukan pengecekan budget terlebih dahulu',
                                buttons: Ext.Msg.OK,
                                icon: Ext.Msg.QUESTION
                            });
                        } else {
                            me.approvePerjalanan(
                                arr_params[0],
                                arr_params[1],
                                pengajuanid,
                                nik,
                                nama,
                                tglpermohonan,
                                statusid,
                                grandtotal,
                                normalisasi,
                                aid,
                                tid,
                                accomodation,
                                ticket,
                                sum_a,
                                sum_t,
                                tgladmga,
                                tglassmanga,
                                tglheadga,
                                tgladmfa,
                                tglheadfa,
                                kelebihan,
                                kekurangan,
                                active,
                                sharingbudget,
                                relasiid,
                                jenisformid,
                                grandacco,
                                grandtiket,
                                periode
                            );
                        }
                    }
                },
                {
                    id: 'id_btn_ditolak',
                    glyph: 'xf057@FontAwesome',
                    text: 'Ditolak',
                    hidden: true,
                    handler: function() {
                        var form = me.down('#id_form_detailcuti').getForm();
                        var nik = form.findField('nik').getValue();
                        var nama = form.findField('nama').getValue();
                        var tglpermohonan = form.findField('tglpermohonan').getValue();
                        var aid = form.findField('aid').getValue();
                        var grandtotal = form.findField('grandtotal').getValue();
                        var accomodation = form.findField('accomodation').getValue();
                        var pengajuanid = form.findField('pengajuanid').getValue();
                        var jenisperjalananid = form.findField('jenisperjalananid').getValue();
                        var biayajenistiket = form.findField('biayajenistiket').getValue();
                        var actual = form.findField('actual').getValue();
                        var actualinvit = form.findField('actualinvit').getValue();
                        var actualticket = form.findField('actualticket').getValue();
                        var periode = form.findField('periode').getValue();
                        var tid = form.findField('tid').getValue();
                        var southeastasia = form.findField('southeastasia').getValue();
                        var non_southeastasia = form.findField('non_southeastasia').getValue();
                        var japan = form.findField('japan').getValue();
                        var eropa_usa_dubai = form.findField('eropa_usa_dubai').getValue();
                        var no_southeastasia = form.findField('no_southeastasia').getValue();
                        var no_non_southeastasia = form.findField('no_non_southeastasia').getValue();
                        var no_japan = form.findField('no_japan').getValue();
                        var no_eropa_usa_dubai = form.findField('no_eropa_usa_dubai').getValue();
                        var kotaid = form.findField('kotaid').getValue();
                        var jenisformid = form.findField('jenisformid').getValue();
                        var sharingbudget = form.findField('sharingbudget').getValue();
                        var relasiid = form.findField('relasiid').getValue();

                        if (sharingbudget == '2' || sharingbudget == '1') {
                            Ext.Msg.show({
                                title: 'Attention',
                                msg: 'Maaf Anda Tidak Bisa Menolak Pengajuan Karena Memiliki Sharing Budget',
                                buttons: Ext.Msg.OK,
                                icon: Ext.Msg.QUESTION
                            });
                        } else {
                            me.rejectPerjalanan(
                                arr_params[0],
                                arr_params[1],
                                nik,
                                nama,
                                tglpermohonan,
                                aid,
                                grandtotal,
                                accomodation,
                                statusid,
                                pengajuanid,
                                jenisperjalananid,
                                biayajenistiket,
                                actual,
                                actualinvit,
                                actualticket,
                                periode,
                                tid,
                                southeastasia,
                                non_southeastasia,
                                japan,
                                eropa_usa_dubai,
                                no_southeastasia,
                                no_non_southeastasia,
                                no_japan,
                                no_eropa_usa_dubai,
                                kotaid,
                                jenisformid,
                                relasiid
                            );
                        }
                    }
                }
            ]
        });
        me.callParent([arguments]);
        // Ketika status sudah disetujui oleh Admin GA maka tampilkan button approval
        // Button approval sampai di admin FA
        if ((statusid == '5' && Settings.userid == '1235') || (statusid == '7' && Settings.userid == '108') || (statusid == '9' && Settings.userid == '244') || (statusid == '11' && Settings.userid == '139')) {
            Ext.getCmp('id_btn_disetujui').show();
            Ext.getCmp('id_btn_ditolak').show();
            Ext.getCmp('id_cancelbuttonsharing').show();
        } else {
            Ext.getCmp('id_btn_disetujui').hide();
            Ext.getCmp('id_btn_ditolak').hide();
            Ext.getCmp('id_cancelbuttonsharing').hide();
        }
        // cetak dokuman untuk FA
        if (statusid == '13' && Settings.userid == '139') {
            if (jenisformid == '1' || jenisformid == '3') {
                Ext.getCmp('id_btn_cetak_advance').show();
                Ext.getCmp('id_btn_cetak_lpj').hide();
            } else {
                Ext.getCmp('id_btn_cetak_advance').hide();
                Ext.getCmp('id_btn_cetak_lpj').show();
            }
        } else {
            Ext.getCmp('id_btn_cetak_advance').hide();
            Ext.getCmp('id_btn_cetak_lpj').hide();
        }

        // cetak dokuman untuk GA
        if (statusid == '8' && Settings.userid == '1235') {
            Ext.getCmp('id_btn_cetak_batal').show();
        } else {
            Ext.getCmp('id_btn_cetak_batal').hide();
        }
        if (statusid == '13' && Settings.userid == '1235') {
            if (jenisformid == '2' || jenisformid == '4') {
                Ext.getCmp('id_btn_cetak_lpj').show();
            } else {
                Ext.getCmp('id_btn_cetak_lpj').hide();
            }
        } else {
            Ext.getCmp('id_btn_cetak_lpj').hide();
        }

        // apabila jenis form pertanggungan
        if (jenisformid == '1' || jenisformid == '3') {
            Ext.getCmp('id_pelimpahan').show();
            Ext.getCmp('id_rincian').show();
            Ext.getCmp('id_pertanggungan').hide();
            Ext.getCmp('id_namasharing').show();
            Ext.getCmp('id_namasharinglap').hide();
            if (Settings.userid == '1235' || Settings.userid == '108' || Settings.userid == '93' || Settings.userid == '244') {
                Ext.getCmp('id_budget').hide();
                Ext.getCmp('id_budget_label').hide();
                //Ext.getCmp('id_search_budget').hide();
                // Ext.getCmp('id_local_label').show();
                // Ext.getCmp('id_local').show();
            } else {
                Ext.getCmp('id_budget').show();
                Ext.getCmp('id_budget_label').show();
                /*if (statusid == '9') {
					Ext.getCmp('id_search_budget').show(); //searchfield kurang budget
					Ext.getCmp('id_search_accomodation').show();
					Ext.getCmp('id_search_ticket').hide();
					Ext.getCmp('id_label_accomodation').show();
					if (jenistiketid=='1') {
						Ext.getCmp('id_label_ticket').hide();
					} else {
						Ext.getCmp('id_label_ticket').show();
					}
				} else {
					Ext.getCmp('id_search_budget').hide(); //searchfield kurang budget
				}*/
            }
        } else {
            Ext.getCmp('id_pelimpahan').hide();
            Ext.getCmp('id_rincian').show();
            Ext.getCmp('id_pertanggungan').show();
            Ext.getCmp('id_namasharing').hide();
            Ext.getCmp('id_namasharinglap').show();
            if (Settings.userid == '1235' || Settings.userid == '108' || Settings.userid == '244') {
                Ext.getCmp('id_budget').hide();
                Ext.getCmp('id_budget_label').hide();
                //Ext.getCmp('id_search_budget').hide();
                // Ext.getCmp('id_local_label').show();
                // Ext.getCmp('id_local').show();
            } else {
                Ext.getCmp('id_budget').show();
                Ext.getCmp('id_budget_label').show();
                /*if (statusid == '9') {
					Ext.getCmp('id_search_budget').show(); //searchfield kurang budget
					Ext.getCmp('id_search_accomodation').hide();
					Ext.getCmp('id_search_ticket').show();
					Ext.getCmp('id_label_accomodation').show();
					Ext.getCmp('id_label_ticket').show();
				} else {
					Ext.getCmp('id_search_budget').hide(); //searchfield kurang budget
				}*/
            }
        }

        // status kelebihan
        if ((statusid == '5' && Settings.userid == '1235' && jenisformid == '2') || (statusid == '5' && Settings.userid == '1235' && jenisformid == '4')) {
            Ext.getCmp('id_btn_kelebihan').show();
        } else {
            Ext.getCmp('id_btn_kelebihan').hide();
        }

        // Button Sharing Budget
        if (statusid == '5' && Settings.userid == '1235' && jenisformid == '1') {
            Ext.getCmp('id_buttonsharing').show();
            if (idsharing == '') {
                Ext.getCmp('id_buttonsharing').show();
                Ext.getCmp('id_sharingbudget').hide();
            } else {
                Ext.getCmp('id_buttonsharing').disable();
                Ext.getCmp('id_sharingbudget').hide();
            }
        } else {
            Ext.getCmp('id_buttonsharing').hide();
            Ext.getCmp('id_sharingbudget').hide();
            Ext.getCmp('id_cancelbuttonsharing').hide();
        }
        // End Button Sharing Budget

        if (jenisformid == '1' || jenisformid == '2') {
            Ext.getCmp('id_rincian_dn').show();
            Ext.getCmp('id_rincian_ln').hide();
        } else {
            Ext.getCmp('id_rincian_dn').hide();
            Ext.getCmp('id_rincian_ln').show();
            // Ext.getCmp('id_rincian').hide();
        }
    },
    approvePerjalanan: function(
        pegawaiid,
        nourut,
        pengajuanid,
        nik,
        nama,
        tglpermohonan,
        statusid,
        grandtotal,
        normalisasi,
        aid,
        tid,
        accomodation,
        ticket,
        sum_a,
        sum_t,
        tgladmga,
        tglassmanga,
        tglheadga,
        tgladmfa,
        tglheadfa,
        kelebihan,
        kekurangan,
        active,
        sharingbudget,
        relasiid,
        jenisformid,
        grandacco,
        grandtiket,
        periode
    ) {
        var me = this;
        Ext.Msg.show({
            title: 'Approve Perjalanan',
            msg: 'Apakah anda yakin akan menyetujui perjalanan ini?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            fn: function(btn) {
                if (btn == 'yes') {
                    Ext.Ajax.request({
                        url: Settings.SITE_URL + '/perjalanandinas/approvePerjalanan',
                        method: 'POST',
                        params: {
                            pegawaiid: pegawaiid,
                            nourut: nourut,
                            pengajuanid: pengajuanid,
                            nik: nik,
                            nama: nama,
                            tglpermohonan: tglpermohonan,
                            statusid: statusid,
                            grandtotal: grandtotal,
                            normalisasi: normalisasi,
                            aid: aid,
                            tid: tid,
                            accomodation: accomodation,
                            ticket: ticket,
                            sum_a: sum_a,
                            sum_t: sum_t,
                            tgladmga: tgladmga,
                            tglassmanga: tglassmanga,
                            tglheadga: tglheadga,
                            tgladmfa: tgladmfa,
                            tglheadfa: tglheadfa,
                            kelebihan: kelebihan,
                            kekurangan: kekurangan,
                            active: active,
                            sharingbudget: sharingbudget,
                            relasiid: relasiid,
                            jenisformid: jenisformid,
                            grandacco: grandacco,
                            grandtiket: grandtiket,
                            periode: periode
                        },
                        success: function(response) {
                            var obj = Ext.decode(response.responseText);
                            if (obj.success) {
                                Ext.History.add('#perjalanandinas');
                            }
                        }
                    });
                }
            }
        });
    },
    rejectPerjalanan: function(
        pegawaiid,
        nourut,
        nik,
        nama,
        tglpermohonan,
        aid,
        grandtotal,
        accomodation,
        statusid,
        pengajuanid,
        jenisperjalananid,
        biayajenistiket,
        actual,
        actualinvit,
        actualticket,
        periode,
        tid,
        southeastasia,
        non_southeastasia,
        japan,
        eropa_usa_dubai,
        no_southeastasia,
        no_non_southeastasia,
        no_japan,
        no_eropa_usa_dubai,
        kotaid,
        jenisformid,
        relasiid
    ) {
        var me = this;
        var winReject = Ext.create('Ext.window.Window', {
            title: 'Tolak Perjalanan',
            closeAction: 'destroy',
            modal: true,
            layout: 'fit',
            autoScroll: true,
            autoShow: true,
            width: 400,
            buttons: [
                {
                    text: 'Yes',
                    handler: function() {
                        winReject
                            .down('form')
                            .getForm()
                            .submit({
                                waitTitle: 'Menyimpan...',
                                waitMsg: 'Sedang menyimpan data, mohon tunggu...',
                                url: Settings.SITE_URL + '/perjalanandinas/rejectPerjalanan',
                                method: 'POST',
                                params: {
                                    pegawaiid: pegawaiid,
                                    nourut: nourut,
                                    nik: nik,
                                    nama: nama,
                                    tglpermohonan: tglpermohonan,
                                    aid: aid,
                                    grandtotal: grandtotal,
                                    accomodation: accomodation,
                                    statusid: statusid,
                                    pengajuanid: pengajuanid,
                                    jenisperjalananid: jenisperjalananid,
                                    biayajenistiket: biayajenistiket,
                                    actual: actual,
                                    actualinvit: actualinvit,
                                    actualticket: actualticket,
                                    periode: periode,
                                    tid: tid,
                                    southeastasia: southeastasia,
                                    non_southeastasia: non_southeastasia,
                                    japan: japan,
                                    eropa_usa_dubai: eropa_usa_dubai,
                                    no_southeastasia: no_southeastasia,
                                    no_non_southeastasia: no_non_southeastasia,
                                    no_japan: no_japan,
                                    no_eropa_usa_dubai: no_eropa_usa_dubai,
                                    kotaid: kotaid,
                                    jenisformid: jenisformid,
                                    relasiid: relasiid
                                },
                                success: function(form, action) {
                                    var obj = Ext.decode(action.response.responseText);
                                    winReject.destroy();
                                    if (obj.success) {
                                        Ext.History.add('#perjalanandinas');
                                    }
                                },
                                failure: function(form, action) {}
                            });
                    }
                },
                {
                    text: 'No',
                    handler: function() {
                        winReject.destroy();
                    }
                }
            ],
            items: [
                {
                    xtype: 'form',
                    waitMsgTarget: true,
                    bodyPadding: 15,
                    layout: 'anchor',
                    defaultType: 'textfield',
                    region: 'center',
                    autoScroll: true,
                    defaults: { labelWidth: 60, anchor: '100%' },
                    items: [{ xtype: 'textarea', grow: true, name: 'alasan', fieldLabel: 'Alasan' }]
                }
            ]
        });
    }
});
