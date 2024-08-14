Ext.define("SIAP.modules.detailpegawai.PanelIdentitas", {
  extend: "Ext.form.Panel",
  alternateClassName: "SIAP.PanelIdentitas",
  alias: "widget.panelidentitas",
  requires: [
    "SIAP.components.field.ComboAgama",
    "SIAP.components.field.ComboStatusPegawai",
    "SIAP.components.field.FieldJabatan",
    "SIAP.components.field.FieldSatker",
    "SIAP.components.field.ComboStatusNikah",
    "SIAP.modules.detailpegawai.TreeDRH",
  ],
  save: function () {
    this.getForm().submit({
      url: Settings.SITE_URL + "/pegawai/ubahPegawai",
      scope: this,
      waitTitle: "Menyimpan...",
      waitMsg: "Sedang menyimpan data, mohon tunggu...",
      success: function (form, action) {
        var data = Ext.decode(action.response.responseText);
        Ext.Msg.alert("Success: ", data.msg);
        window.location.reload(true);
      },
      failure: function (form, action) {
        var data = Ext.decode(action.response.responseText);
        switch (action.failureType) {
          case Ext.form.action.Action.CLIENT_INVALID:
            Ext.Msg.alert("Failure", "Harap isi semua data");
            break;
          case Ext.form.action.Action.CONNECT_FAILURE:
            Ext.Msg.alert("Failure", "Terjadi kesalahan");
            break;
          case Ext.form.action.Action.SERVER_INVALID:
            Ext.Msg.alert("Failure", data.msg);
        }
      },
    });
  },
  initComponent: function () {
    var me = this;
    var tplFoto = new Ext.XTemplate(
      '<tpl for=".">',
      '<img id="id_foto_peg" src="{url}" width="150" height="171" />',
      "</tpl>"
    );

    Ext.apply(me, {
      layout: "border",
      listeners: {
        afterrender: function (p) {
          p.getForm().load({
            url: Settings.SITE_URL + "/pegawai/getPegawaiByID",
            method: "POST",
            params: { pegawaiid: me.params },
            success: function (action, form) {
              var obj = Ext.decode(form.response.responseText);
              if (obj.success) {
                var agama = obj.data.agama.toString();

                me.down("#id_agama").reload();
                me.down("#id_agama").setValue(agama);
                Ext.getDom("id_foto_peg").src = obj.data.fotonew;
              }
            },
          });
        },
      },
      items: [
        {
          region: "west",
          title: "Daftar Riwayat Hidup",
          collapsible: true,
          collapsed: false,
          layout: "fit",
          border: false,
          split: true,
          resizable: { dynamic: true },
          items: [{ xtype: "treedrh", params: me.params }],
        },
        {
          xtype: "panel",
          region: "center",
          autoScroll: true,
          bodyPadding: 10,
          tbar: [
            {
              text: "Kembali",
              glyph: "xf060@FontAwesome",
              handler: function () {
                Ext.History.add("#pegawai");
              },
            },
            "->",
            {
              text: "Simpan",
              glyph: "xf044@FontAwesome",
              handler: function () {
                me.save();
              },
            },
            {
              glyph: "xf02f@FontAwesome",
              text: "Cetak Word",
              handler: function () {
                var m = { pegawaiid: me.params };
                window.open(
                  Settings.SITE_URL +
                    "/pegawai/cetak/identitasword?" +
                    objectParametize(m)
                );
              },
            },
            {
              glyph: "xf02f@FontAwesome",
              text: "Cetak Excel",
              handler: function () {
                var m = { pegawaiid: me.params };
                window.open(
                  Settings.SITE_URL +
                    "/pegawai/cetak/cetakexcel?" +
                    objectParametize(m)
                );
              },
            },
          ],
          items: [
            { xtype: "hidden", name: "pegawaiid" },
            { xtype: "hidden", name: "fotoname" },
            { xtype: "hidden", name: "satkerid" },
            { xtype: "hidden", name: "satkerdisp" },
            {
              xtype: "panel",
              border: false,
              width: 150,
              height: 200,
              html: tplFoto.applyTemplate({
                url: Settings.no_image_person_url,
              }),
              tbar: [
                "File Max : 1 MB",
                "->",
                {
                  xtype: "fileuploadfield",
                  id: "form_file",
                  name: "foto",
                  buttonOnly: true,
                  buttonConfig: {
                    text: "Upload",
                    glyph: "xf093@FontAwesome",
                  },
                },
              ],
            },
            {
              layout: "column",
              baseCls: "x-plain",
              border: false,
              items: [
                {
                  xtype: "panel",
                  columnWidth: 0.5,
                  bodyPadding: 10,
                  layout: "form",
                  defaultType: "displayfield",
                  baseCls: "x-plain",
                  border: false,
                  defaults: {
                    labelWidth: 170,
                  },
                  items: [
                    {
                      xtype: "textfield",
                      fieldLabel: "NIK",
                      name: "nik",
                      anchor: "95%",
                      readOnly: false,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nama Depan",
                      name: "namadepan",
                      anchor: "95%",
                      readOnly: false,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nama Belakang",
                      name: "namabelakang",
                      anchor: "95%",
                      readOnly: false,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nama Keluarga",
                      name: "namakeluarga",
                      anchor: "95%",
                      readOnly: false,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Tempat Lahir",
                      name: "tempatlahir",
                      anchor: "95%",
                    },
                    {
                      xtype: "datefield",
                      fieldLabel: "Tgl Lahir",
                      name: "tgllahir",
                      format: "d/m/Y",
                      anchor: "95%",
                    },
                    {
                      xtype: "combobox",
                      fieldLabel: "Jenis Kelamin",
                      name: "jeniskelamin",
                      anchor: "95%",
                      readOnly: false,
                      queryMode: "local",
                      displayField: "text",
                      valueField: "id",
                      store: Ext.create("Ext.data.Store", {
                        fields: ["id", "text"],
                        data: [
                          { id: "L", text: "Pria" },
                          { id: "P", text: "Wanita" },
                        ],
                      }),
                    },
                    {
                      xtype: "textareafield",
                      fieldLabel: "Alamat KTP",
                      name: "alamatktp",
                      grow: true,
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kelurahan KTP",
                      name: "kelurahanktp",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kecamatan KTP",
                      name: "kecamatanktp",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kota KTP",
                      name: "kotaktp",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kodepos KTP",
                      name: "kodeposktp",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Alamat Tinggal",
                      name: "alamat",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kelurahan",
                      name: "kelurahan",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kecamatan",
                      name: "kecamatan",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kota",
                      name: "kota",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kodepos",
                      name: "kodepos",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kewarganegaraan",
                      name: "kewarganegaraan",
                      anchor: "95%",
                    },
                    {
                      xtype: "combobox",
                      fieldLabel: "Gol Darah",
                      name: "goldarah",
                      anchor: "95%",
                      queryMode: "local",
                      displayField: "text",
                      valueField: "id",
                      store: Ext.create("Ext.data.Store", {
                        fields: ["id", "text"],
                        data: [
                          { id: "A", text: "A" },
                          { id: "B", text: "B" },
                          { id: "AB", text: "AB" },
                          { id: "O", text: "O" },
                        ],
                      }),
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Rhesus",
                      name: "rhesus",
                      anchor: "95%",
                    },
                    {
                      itemId: "id_agama",
                      xtype: "comboagama",
                      fieldLabel: "Agama",
                      name: "agama",
                      isLoad: true,
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "No Telp",
                      name: "telp",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "No HP",
                      name: "hp",
                      anchor: "95%",
                    },
                  ],
                },
                {
                  xtype: "panel",
                  columnWidth: 0.5,
                  bodyPadding: 10,
                  layout: "form",
                  defaultType: "displayfield",
                  baseCls: "x-plain",
                  border: false,
                  defaults: {
                    labelWidth: 170,
                  },
                  items: [
                    {
                      xtype: "combostatuspegawai",
                      fieldLabel: "Status Pegawai",
                      name: "statuspegawai",
                      anchor: "95%",
                      readOnly: false,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Divisi",
                      name: "divisi",
                      anchor: "95%",
                      readOnly: false,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Departement",
                      name: "departemen",
                      anchor: "95%",
                      readOnly: false,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Jabatan",
                      name: "jabatan",
                      anchor: "95%",
                      readOnly: true,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Lokasi",
                      name: "lokasi",
                      anchor: "95%",
                      readOnly: true,
                    },
                    {
                      xtype: "datefield",
                      fieldLabel: "Tgl Masuk",
                      name: "tglmulai",
                      format: "d/m/Y",
                      anchor: "95%",
                      readOnly: true,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Email Pribadi",
                      name: "email",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Email Kantor",
                      name: "emailkantor",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "No KTP",
                      name: "noktp",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Masa Berlaku KTP",
                      name: "masaberlakuktp",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "NPWP",
                      name: "npwp",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "BPJS Kesehatan",
                      name: "bpjskes",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "BPJS Ketenagakerjaan",
                      name: "bpjsnaker",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Asuransi Kesehatan",
                      name: "askes",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Passpor",
                      name: "paspor",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nomor Kartu Keluarga",
                      name: "nokk",
                      anchor: "95%",
                    },
                    {
                      xtype: "combostatusnikah",
                      fieldLabel: "Status Pernikahan",
                      name: "statusnikah",
                      anchor: "95%",
                    },
                    {
                      xtype: "datefield",
                      fieldLabel: "Tgl Menikah",
                      name: "tglnikah",
                      format: "d/m/Y",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Berat Badan",
                      name: "beratbadan",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Tinggi Badan",
                      name: "tinggibadan",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nama Kontak Darurat",
                      name: "namakontakdarurat",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "No Telp Kontak Darurat",
                      name: "telpkontakdarurat",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Relasi Kontak Darurat",
                      name: "relasikontakdarurat",
                      anchor: "95%",
                    },
                    {
                      xtype: "textareafield",
                      fieldLabel: "Hobby",
                      name: "hobby",
                      grow: true,
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Shio",
                      name: "shio",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Unsur",
                      name: "unsur",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Size Baju",
                      name: "sizebaju",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Size Celana",
                      name: "sizecelana",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Size Sepatu",
                      name: "sizesepatu",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Size Rompi",
                      name: "sizerompi",
                      anchor: "95%",
                    },
                  ],
                },
              ],
            },
          ],
        },
      ],
    });
    me.callParent([arguments]);
  },
});
