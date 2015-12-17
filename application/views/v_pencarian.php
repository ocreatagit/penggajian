<div class="container" style="margin-top: 80px; height: 100%; padding: 0px; margin-bottom: 50px;">
    <div class="row" style="">
        <div class="col-lg-12">
            <h2 class="page-header" style="margin-top: 0px;">Daftar Laporan Kas</h2>
            <ol class="breadcrumb" style="background-color: white; margin-top: 00px;">
                <li class="active"><i class="fa fa-home"></i> Daftar Laporan Kas</li>
            </ol>
        </div>
    </div>
    <!--    <div class="alert alert-warning alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="alert();"><span aria-hidden="true">×</span></button>
            <strong>Berhasil</strong> untuk membuat laporan baru!
        </div>-->

<!--<input class="form-control siku" type="text" id="datepicker" placeholder="Pilih Tanggal" name="tanggal" value="<?php if ($this->session->userdata("tanggal_jual") != "") echo $this->session->userdata("tanggal_jual"); ?>">-->

    <!--<div style="">-->

    <div class="col-md-12" style="background-color: white; margin-bottom: 20px; padding-bottom: 20px; padding-top: 10px;">
        <form class="form-horizontal" method="post" action="<?php echo current_url(); ?>">
            <?php if ($this->session->userdata('Level') == 0) {
                ?>
                <div class="form-group">
                    <label for="exampleInputName2" class="control-label col-lg-2" style=""> Cabang : </label>
                    <div class="col-lg-3">
                        <select name="cabang" class="form-control siku">
                            <option value="0"> --- Semua Cabang ---</option>
                            <?php
                            foreach ($cabangs as $cabang) {
                                ?>
                                <option value="<?php echo $cabang->idcabang ?>"><?php echo $cabang->provinsi . " - " . $cabang->kabupaten ?></option>
                            <?php }
                            ?>
                        </select>
                    </div>
                </div>
            <?php }
            ?>
            <div class="form-group">
                <label for="exampleInputName2" class="control-label col-lg-2" style="">Periode :</label>
                <div class="col-lg-2">
                    <input class="form-control siku" type="text" id="datepicker1" placeholder="Sampai" name="tanggal_awal" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="exampleInputName2" class="control-label col-lg-2" style="">Sampai :</label>
                <div class="col-lg-2">
                    <input class="form-control siku" type="text" id="datepicker2" placeholder="Sampai" name="tanggal_akhir" value="">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-lg-offset-2">
                    <button type="submit" name='btn_pilih' value='btn_pilih' class="btn btn-default siku">&nbsp;&nbsp;Pilih&nbsp;&nbsp;</button>
                    <button type="submit" name='btn_export' value='btn_export' class="btn btn-success siku">&nbsp;&nbsp;<i class="fa fa-book"></i> Export To XLS&nbsp;&nbsp;</button>
                    <button type="submit" name='btn_print' value='btn_print' class="btn btn-primary siku">&nbsp;&nbsp;<i class="fa fa-print"></i> Print &nbsp;&nbsp;</button> 
                </div>
            </div>
        </form>

    </div>
    <!--        <div class="col-md-1">
                <dl class="dl-horizontal">
                    <dt>Periode</dt>
                    <dd><input class="form-control siku" type="text" id="datepicker" placeholder="Pilih Tanggal" name="tanggal" value=""></dd>
                </dl>
                            <input class="form-control siku" type="text" id="datepicker" placeholder="Pilih Tanggal" name="tanggal" value="">
            </div>-->
    <!--</div>-->
    <hr>
    <div class="col-md-12" style="background-color: white; padding: 10px 20px 20px 10px;">
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
                    <!--<th>#</th>-->
                    <th>Tanggal</th>
                    <th>Admin</th>
                    <th>Keterangan</th>
                    <th>Total Penjualan</th>
                    <!--<th>Aksi</th>-->
                </tr>
            </thead>
            <tbody>
                <?php
                $total_penjualan = 0;
                $total_pengeluaran = 0;
                $total_komisi = 0;
                foreach ($laporans as $laporan):
                    ?>
                    <?php $total_penjualan += $laporan->totalPenjualan; ?>
                    <?php $total_komisi += $laporan->totalKomisi; ?>
                    <tr>
                        <td><?php echo strftime("%d-%m-%Y", strtotime($laporan->tanggal)); ?></td>
                        <td><?php echo $laporan->username; ?></td>
                        <td><?php echo $laporan->keterangan; ?></td>
                        <td>Rp <?php echo number_format($laporan->totalPenjualan, 0, ",", ".") ?>.- </td>
                    </tr>

                <?php endforeach; ?>

                <?php
                $total_biaya_keluar = 0;
                foreach ($pengeluarans as $pengeluaran) {
                    ?>
            <!--                <tr>
                        <td><?php echo strftime("%d-%m-%Y", strtotime($pengeluaran->tanggal)); ?></td>
                        <td><?php echo $pengeluaran->username; ?></td>
                        <td>Rp <?php echo number_format($pengeluaran->jumlah, 0, ",", ".") ?>.- </td>
                        <td><?php echo $pengeluaran->keterangan; ?></td>
                    </tr>-->
                    <?php
                    $total_biaya_keluar += $pengeluaran->jumlah;
                }
                ?>
                <tr style="font-size: 18px">
                    <td></td>
                    <td></td>
                    <td colspan="" style="text-align: right;">Total Penjualan</td>
                    <td colspan="" style=""><strong>Rp <?php echo number_format($total_penjualan, 0, ",", ".") ?>,-</strong></td>
                </tr>
                <tr style="font-size: 18px">
                    <td></td>
                    <td></td>
                    <td colspan="" style="text-align: right;">Total Pengeluaran</td>
                    <td colspan="" style=""><strong>Rp <?php echo number_format($total_biaya_keluar, 0, ",", ".") ?>,-</strong></td>
                </tr>
            </tbody>
            <tfoot>

            </tfoot>
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