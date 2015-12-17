<div class="container" style="margin-top: 80px; height: 100%; padding: 0px; margin-bottom: 50px;">

    <div class="row" style="">
        <div class="col-lg-12">
            <h2 class="page-header" style="margin-top: 0px;">Penjualan SPG</h2>
            <ol class="breadcrumb" style="background-color: white; margin-top: 00px;">
                <li class="active"><i class="fa fa-home"></i> Penjualan SPG</li>
            </ol>
        </div>
    </div>

    <div style="background-color: white; height: 230px;">
        <div class="col-md-12" style="margin-top: 17px;">
            <form class="form-inline" method="post" action="<?php
            echo current_url();
            $total = array();
            ?>"><?php if ($this->session->userdata("Level") == 0) : ?>
                    <div class="form-group">
                        <label class="">Cabang : </label>
                        <select class="form-control siku" style="width: 200px" name="cabang">
                            <option value="0"> --- Semua Cabang ---</option>
                            <?php foreach ($cabangs as $cabang): ?>
                                <option value="<?php echo $cabang->idcabang ?>"><?php echo $cabang->provinsi ?> - <?php echo $cabang->kabupaten ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <br><br>
                <?php endif; ?>
                <div class="form-group">
                    <label for="exampleInputName2">Periode : </label>
                    <input class="form-control siku" type="text" id="datepicker1" placeholder="Dari" name="tanggal_awal" value="">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail2">Sampai : </label>
                    <input class="form-control siku" type="text" id="datepicker2" placeholder="Sampai" name="tanggal_akhir" value="">
                </div>
                <br>        
                <br>        
                <div class="form-group" style="margin-left: 27px; ">
                    <label for="filterSPG">SPG : </label>
                    <select name="filter" id="filterSPG" style="width: 200px" class="form-control siku">
                        <option value="0">Semua SPG</option>
                        <?php foreach ($datasales as $sales): ?>
                            <option value="<?php echo $sales->id_sales ?>" <?php if ($sales->id_sales == $selectSeles) echo 'selected'; ?>><?php echo $sales->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <br>
                <div class="form-group" style="margin-left: 3px; ">
                    <label for="filterBarang1">Barang : </label>
                    <select name="filterBarang" id="filterBarang1" style="width: 200px" class="form-control siku">
                        <option value="0">Semua Barang</option>
                        <?php foreach ($barangs as $barang): $total[$barang->IDBarang] = 0; ?>
                            <option value="<?php echo $barang->IDBarang ?>" <?php if ($barang->IDBarang == $selectBarang) echo 'selected'; ?>><?php echo $barang->namaBarang ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>                
                &nbsp;<button type="submit" name='btn_pilih' value='btn_pilih' class="btn btn-default siku">&nbsp;&nbsp;Pilih&nbsp;&nbsp;</button>
                &nbsp;<button type="submit" name='btn_export' value='btn_export' class="btn btn-success siku">&nbsp;&nbsp;<i class="fa fa-book"></i> Export To XLS&nbsp;&nbsp;</button>
                &nbsp;<button type="submit" name='btn_print' value='btn_print' class="btn btn-primary siku">&nbsp;&nbsp;<i class="fa fa-print"></i> Print &nbsp;&nbsp;</button> 
            </form>

        </div>
    </div>
    <hr>
    <div class="col-md-12" style="background-color: white;">
        <?php if ($periode != "Laporan Bulan Ini") {
            ?>
            <h2 style="margin-bottom: 30px;"><i class="fa fa-calendar"></i> Periode <?php echo $periode ?></h2>
        <?php } else {
            ?>
            <h2 style="margin-bottom: 30px;"><i class="fa fa-calendar"></i> <?php echo $periode ?></h2>
            <?php
        }
        ?>
        <table class='table table-striped table-hover' id="list_laporan" style="">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>SPG</th>
                    <th>Nama Barang</th>
                    <th>Penjualan(pcs)</th>
                    <th>Konversi Satuan</th>
                    <th>Lokasi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($datapenjualan as $penjualan):
                    $total[$penjualan->IDBarang] += intval($penjualan->jumlah);
                    if (count($konversi_satuan) > 0) {
                        $satuan = intval($konversi_satuan[$penjualan->IDBarang]->total_konversi);
                    }
                    $jumlah = intval($penjualan->jumlah);
                    $karton = 0;
                    if ($satuan != 0) {
                        $karton = floor($jumlah / ($satuan * 12));
                        $jumlah %= ($satuan * 12);
                    }
                    $lusin = floor($jumlah / 12);
                    $jumlah %= 12;
                    ?>
                    <tr>
                        <td><?php echo strftime("%d-%m-%Y", strtotime($penjualan->tanggal)) ?></td>
                        <td><?php echo $penjualan->nama ?></td>
                        <td><?php echo $penjualan->namaBarang ?></td>
                        <td><?php echo $penjualan->jumlah ?></td>
                        <td><?php echo ( $karton == 0 ? "" : $karton . " karton ") . ($lusin == 0 ? "" : $lusin . " lusin ") . $jumlah . " pcs" ?></td>
                        <td><?php echo $penjualan->desa ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tr>
                <td colspan="6" style="text-align: center; height: 20px;"></td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: center; font-size: medium; background-color: #ccccff"><strong>TOTAL PENJUALAN</strong> </td>
            </tr>

            <?php
            $counter = 0;           
            foreach ($total as $key => $value) :
                $satuan = 0;
                if (count($konversi_satuan) > 0) {
                    $satuan = intval($konversi_satuan[$key]->total_konversi);
                }
                $jumlah = intval($value);
                $karton = 0;
                if ($satuan != 0) {
                    $karton = floor($jumlah / ($satuan * 12));
                    $jumlah %= ($satuan * 12);
                }
                $lusin = floor($jumlah / 12);
                $jumlah %= 12;
                ?>
                <tr>
                    <td colspan="3" style="text-align: right;"><?php echo $barangs[$counter++]->namaBarang ?> :</td>
                    <td><?php echo $value ?></td>
                    <td><?php echo ( $karton == 0 ? "" : $karton . " karton ") . ($lusin == 0 ? "" : $lusin . " lusin ") . $jumlah . " pcs" ?></td>
                    <td></td>
                </tr>
            <?php endforeach; ?>            
        </table>
    </div>

</div>

<script src="<?php echo base_url(); ?>bootstrap/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>jquery-ui/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>bootstrap/js/ajaxLaporan.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>Datatable/js/jquery.dataTables.js"></script>



<script>
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

    $(document).ready(function () {
        $("#lokasi").val('');
        $("#salesnya_admin").val('');
        $("#nama_produk").val('');
        $('#list_laporan').DataTable({
            "order": [[0, "desc"]],
            "aoColumnDefs": [
                {"sType": "date-dmy", "aTargets": [0]}
            ]
        });
    });
</script>
<style type="text/css">
    .ui-datepicker-year, .ui-datepicker-month{
        color: black;
    }
</style>
<script>
    $("#datepicker1").datepicker({
        inline: true,
        dateFormat: "dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    $("#datepicker2").datepicker({
        inline: true,
        dateFormat: "dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });

    $(document).ready(function () {
        $("#lokasi").val('');
        $("#salesnya_admin").val('');
        $("#gaji_sales").val('');
        $("#nama_produk").val('');
        $(".kas_keluar").val('');
        $("#bayar_gaji").hide();
    });
</script>
</body>
</html>