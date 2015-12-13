<div class="container" style="margin-top: 80px; height: 100%; padding: 0px; margin-bottom: 50px;">    
    <div class="row" style="">
        <div class="col-lg-12">
            <h1 class="page-header" style="margin-top: 0px;">Input Penjualan</h1>
            <ol class="breadcrumb" style="background-color: white; margin-top: 00px;">
                <li><a href=""><i class="fa fa-home"></i> Input Penjualan MT</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info siku">
                <div class="panel-heading siku">
                    <h3 class="panel-title"></h3>
                </div>
                <div class="panel-body siku">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php if ($status != "") {
                                ?>
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> <?php echo $status ?>
                                </div>
                            <?php }
                            ?>
                            <form id="form_s" class="form-horizontal" method="POST" action="<?php echo current_url() ?>" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Tanggal : </label>
                                    <div class="col-sm-3">
                                        <input type="text" name="tanggal" id="datetimepicker" class="form-control siku" placeholder="Tanggal" value="<?php echo set_value("tanggal", date("d-m-Y")) ?>"/>
                                        <?php if (form_error('tanggal')) {
                                            ?>
                                            <span class='warna' id='lokasi_error'><?php echo form_error("tanggal") ?></span>
                                        <?php }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Nama Toko : </label>
                                    <div class="col-sm-3">
                                        <select name="nama_toko" class="form-control siku">
                                            <?php foreach ($tokos as $toko) {
                                                ?>
                                                <option value="<?php echo $toko->IDToko ?>"><?php echo $toko->nama ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                        <input type="hidden" name="hid" value="421378dhjkdfasiedf083089"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Nama SPG : </label>
                                    <div class="col-sm-3">
                                        <select name="nama_spg" class="form-control siku">
                                            <?php foreach ($spgs as $spg) {
                                                ?>
                                                <option value="<?php echo $spg->IDSalesMT ?>"><?php echo $spg->nama ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Nama Barang : </label>
                                    <div class="col-sm-3">
                                        <select name="nama_barang" class="form-control siku" id="nama_barang">
                                            <?php
                                            foreach ($barangs as $barang) {
                                                ?>
                                                <option value="<?php echo $barang->IDBarangMT ?>"><?php echo $barang->nama ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <input type="hidden" name="combo_index" id="combo_index" value="0"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Jumlah Barang : </label>
                                    <div class="col-sm-3">
                                        <input type="number" name="jumlah" id="jumlah" class="form-control siku" placeholder="Jumlah" min="1" value="<?php echo set_value("jumlah", 1) ?>"/>
                                        <?php if (form_error('jumlah')) {
                                            ?>
                                            <span class='warna' id='lokasi_error'><?php echo form_error("jumlah") ?></span>
                                        <?php }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"></label>
                                    <label id='konversi' class="control-label" style="margin-left: 15px"></label>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" value="tambah_penjualan" name="btn_submit" class="btn btn-primary siku" id="inputCheck">Tambahkan Data</button>
                                    </div>
                                </div>
                            </form>  
                        </div>
                    </div><br><br>
                    <div class="panel-footer" style="background-color: white;">
                        <strong>Daftar Stok Barang</strong><hr>

                        <table class="table table-hover" id="tbl">
                            <thead style="color: white;">
                                <tr>
                                    <td><strong>Tanggal</strong></td>
                                    <td><strong>Nama Toko</strong></td>
                                    <td><strong>Nama SPG</strong></td>
                                    <td><strong>Nama Barang</strong></td>
                                    <td><strong>Jumlah Barang</strong></td>
                                    <td class="text-center"><strong>Aksi</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $total = 0;
                                foreach ($this->cart->contents() as $items) {
                                    if (strpos($items["id"], 'barangmt') !== FALSE) {
                                        ?>
                                        <tr>
                                            <td><?php echo strftime("%d-%m-%Y", strtotime($items["options"]["tanggal"])) ?></td>
                                            <td><?php echo $items["options"]["NamaToko"] ?></td>
                                            <td><?php echo $items["options"]["NamaSales"] ?></td>
                                            <td><?php echo $items["options"]["NamaBarang"] ?></td>
                                            <td>
                                                <input type="number" id="jumlah<?php echo $no; ?>" name="jumlah<?php echo $no; ?>" value="<?php echo $items["qty"] ?>" min="1" class="form-control siku"/>

                                                <input type="hidden" id="id<?php echo $no; ?>" name="id<?php echo $no; ?>" value="<?php echo $items["id"] ?>"/>
                                                <input type="hidden" id="name<?php echo $no; ?>" name="name<?php echo $no; ?>" value="<?php echo $items["name"] ?>"/>
                                                <input type="hidden" id="IDToko<?php echo $no; ?>" name="IDToko<?php echo $no; ?>" value="<?php echo $items["options"]["IDToko"] ?>"/>
                                                <input type="hidden" id="IDSales<?php echo $no; ?>" name="NamaSales<?php echo $no; ?>" value="<?php echo $items["options"]["IDSales"] ?>"/>
                                                <input type="hidden" id="NamaSales<?php echo $no; ?>" name="NamaSales<?php echo $no; ?>" value="<?php echo $items["options"]["NamaSales"] ?>"/>
                                                <input type="hidden" id="IDBarang<?php echo $no; ?>" name="IDBarang<?php echo $no; ?>" value="<?php echo $items["options"]["IDBarang"] ?>"/>
                                                <input type="hidden" id="NamaBarang<?php echo $no; ?>" name="NamaBarang<?php echo $no; ?>" value="<?php echo $items["options"]["NamaBarang"] ?>"/>
                                                <input type="hidden" id="NamaToko<?php echo $no; ?>" name="NamaToko<?php echo $no; ?>" value="<?php echo $items["options"]["NamaToko"] ?>"/>
                                                <input type="hidden" id="price<?php echo $no; ?>" name="price<?php echo $no; ?>" value="<?php echo $items["price"] ?>"/>
                                                <input type="hidden" id="rowid<?php echo $no; ?>" name="rowid<?php echo $no; ?>" value="<?php echo $items["rowid"] ?>"/>
                                                <input type="hidden" id="combo_index<?php echo $no; ?>" name="combo_index<?php echo $no; ?>" value="<?php echo $items["options"]["combo_index"] ?>"/>
                                                <input type="hidden" id="tanggal<?php echo $no; ?>" name="tanggal<?php echo $no; ?>" value="<?php echo $items["options"]["tanggal"] ?>"/>
                                            </td>
                                            <td align="center"><a href="<?php echo base_url() ?>index.php/toko/delete_cart_mt/<?php echo $items["id"] ?>" class="btn btn-sm btn-danger siku"><i class="fa fa-times"></i></a></td>
                                        </tr>
                                        <?php
                                        $no++;
                                        $total += $items["qty"];
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="" colspan="4" align="right" style="color: white"><h4>Total</h4></td>
                                    <td class="" colspan="" style="color: white"><h4 id="refresh"><?php echo $total; ?></h4></td>
                                    <td class="" colspan="" align="center"><button class="btn btn-default" id="btn_save" type="button"><i class="fa fa-save"></i> Save</button></td>
                                </tr>
                            </tfoot>
                        </table>
                        <input type="hidden" name="no" id="no" value="<?php echo $no ?>"/>
                    </div>
                    <br>
                    <div>
                        <a id="inputCheck" href="<?php echo base_url() ?>index.php/toko/insert_penjualan_mt" class="btn btn-block btn-primary btn-lg siku"><i class="fa fa-flag"></i> Selesai Input Penjualan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>bootstrap/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>jquery-ui/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>bootstrap/js/ajaxLaporan.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>Datatable/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>alertify/alertify.min.js"></script>
<style type="text/css">
    .ui-datepicker-year, .ui-datepicker-month{
        color: black;
    }
</style>

<script>
    var arrSatuan = [<?php
                                $temp = "";
                                for ($i = 0; $i < count($barangs); $i++) {
                                    if ($i == 0) {
                                        $temp .= "" . $barangs[$i]->nilai_karton;
                                    } else {
                                        $temp .= ", " . $barangs[$i]->nilaikarton;
                                    }
                                }
                                echo $temp;
                                ?>];
    $('#konversi').hide();
    var idx = 0;
    $('#nama_produk').change(function () {
        idx = $('#nama_produk option:selected').index();
    });
    $('#jumlah').keyup(function () {
        var data = $('#jumlah').val();
        var karton = Math.floor(data / (arrSatuan[idx] * 12));
        data = data % (arrSatuan[idx] * 12);
        var lusin = Math.floor(data / 12);
        data = data % 12;
        var pcs = data;
        $('#konversi').show();
        $('#konversi').html(karton + " Karton " + lusin + " Lusin " + pcs + " Pcs");
    });
</script>
<script>
    alertify.error('<i class="fa fa-warning"></i> Tekan Tombol Save Jika Merubah Jumlah Barang!', 0);

    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-dmy-pre": function (a) {
            if (a == null || a == "") {
                return 0;
            }
            var date = a.split('-');
            return (date[2] + date[1] + date[0]) * 1;
        },
        "date-dmy-asc": function (a, b) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },
        "date-dmy-desc": function (a, b) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    });
    $("#datetimepicker").datepicker({
        inline: true,
        dateFormat: "dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    $("#tbl").dataTable({
        "order": [[0, "desc"]],
        "aoColumnDefs": [
            {"sType": "date-dmy", "aTargets": [0]}
        ]
    });

    $('#nama_barang').change(function () {
        var idx = $('#nama_barang option:selected').index();
        $("#combo_index").val(idx);
    });
    
    $(document).ready(function (){
        $("#btn_save").click(function (event) {
            update_cart_mt();
            load_html();
            alertify.message('Data Telah Ditambahkan!');
        });        
    });


    function update_cart_mt() {
        var number = $("#no").val();
        for (var i = 1; i < number; i++) {
            $.ajax({
                url: "<?php echo base_url() ?>index.php/toko/edit_cart_mt",
                type: 'POST',
                data: {
                    "rowid": $("#rowid" + i).val(),
                    "id": $("#id" + i).val(),
                    "jumlah": $("#jumlah" + i).val(),
                    "name": $("#name" + i).val(),
                    "IDSales": $("#IDSales" + i).val(),
                    "IDBarang": $("#IDBarang" + i).val(),
                    "IDToko": $("#IDToko" + i).val(),
                    "NamaSales": $("#NamaSales" + i).val(),
                    "NamaBarang": $("#NamaBarang" + i).val(),
                    "NamaToko": $("#NamaToko" + i).val(),
                    "combo_index": $("#combo_index" + i).val(),
                    "tanggal": $("#tanggal" + i).val()
                }, success: function (data, textStatus, jqXHR) {
//                    alert(data);
                }, error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
    }

    function load_html() {
        $("#refresh").load("<?php echo base_url() ?>index.php/toko/refresh_total");
    }

    $("#inputCheck").click(function (event) {
        event.preventDefault();
        alertify.confirm('Apakah Data yang telah Di Masukan Benar?', function (e) {
            if (e) {
//                var a = $("#inputCheck").attr("href");
//                window.location.assign(a);
                $("#form_s").submit();
            } else {
                //after clicking Cancel
            }
        });
    });
</script>
</body>
</html>