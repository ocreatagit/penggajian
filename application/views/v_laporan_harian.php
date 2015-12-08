<div class="container" style="margin-top: 80px; height: 100%; padding: 0px; margin-bottom: 50px;">    
    <div class="row" style="">
        <div class="col-lg-12">
            <h1 class="page-header" style="margin-top: 0px;"> <?php echo ($this->session->userdata("Level") == 3) ? "Laporan Harian" : "Input Data Penjualan" ?> </h1>
            <ol class="breadcrumb" style="background-color: white; margin-top: 00px;">
                <li><a href="<?php echo base_url(); ?>index.php/Laporan/harian"><i class="fa fa-home"></i> Daftar Laporan Harian</a></li>
            </ol>
        </div>
    </div>
    <?php
    if ($this->session->userdata("Level") != 0) {
        if ($this->session->userdata("Level") != 3) {
            ?>
            <div style="background-color: white; height: 70px;">
                <div class="col-md-12">
                    <a href="<?php echo base_url(); ?>index.php/laporan/HarianPenjualan" class="btn btn-primary" style="border-radius: 0px; margin-top: 10px;" style="border-radius: 0px;"><i class="fa fa-plus">&nbsp</i> Buat Laporan Harian</a>
                </div>
            </div>
            <?php
        }
    } else {
        if ($filter != "") {
            ?>
            <div class="alert alert-info alert-dismissible siku" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Pencarian Berdasarkan '<?php echo $filter; ?>'
            </div>
        <?php } ?>
        <form style="background-color: white; padding: 15px 10px 10px 5px;; margin-bottom: 10px;" class="form-horizontal" action="<?php echo current_url() ?>" method="POST">
            <div class="form-group">
                <label for="exampleInputName2" class="control-label col-lg-2" style=""> Cabang : </label>
                <div class="col-lg-3">
                    <select name="cabang" class="form-control siku">
                        <option value="0">--- Semua Cabang ---</option>
                        <?php
                        foreach ($cabangs as $cabang) {
                            ?>
                            <option value="<?php echo $cabang->idcabang ?>"><?php echo $cabang->provinsi . " - " . $cabang->kabupaten ?></option>
                        <?php }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-lg-offset-2">
                    <button type="submit" name='btn_submit' value='btn_pilih' class="btn btn-primary siku">&nbsp;&nbsp;Pilih&nbsp;&nbsp;</button>
                </div>
            </div>
        </form>
    <?php }
    ?>
    <div class="col-md-12" style="background-color: white;">
        <table class='table table-striped table-hover' id="list_laporan">
            <thead>
                <tr>
                    <!--<th style="display: none;">ID</th>-->
                    <th>Tanggal</th>
                    <th>Admin <?php
                        if ($this->session->userdata("Level") == 0) {
                            echo "Cabang";
                        }
                        ?></th>
                    <th>Total Penjualan</th>
                    <!--<th>Total Pengeluaran</th>-->
                    <th>Keterangan</th>
                    <th>Pembatalan Nota ?</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($laporans as $laporan):
                    ?>
                    <tr>
                        <!--<td style="display: none;"><?php echo ($laporan->idlaporan); ?></td>-->
                        <!--<td><?php echo ($laporan->tanggal); ?></td>-->
                        <td><?php echo strftime("%d-%m-%Y", strtotime($laporan->tanggal)); ?></td>
                        <td><?php echo $laporan->username; ?></td>
                        <td>Rp <?php echo number_format($laporan->totalPenjualan, 0, ",", ".") ?>.- </td>
                        <!--<td>Rp <?php echo number_format($laporan->totalPengeluaran, 0, ",", ".") ?>.- </td>-->
                        <td><?php echo $laporan->keterangan; ?></td>
                        <td><?php echo $laporan->IDPembatalan == 0 ? "Tidak" : "Ya"; ?></td>
                        <td style="width: 50px; text-align: center;">
                            <a href="<?php echo base_url(); ?>index.php/laporan/cetaklaporan/<?php echo $laporan->idlaporan ?>" class="btn btn-sm btn-info siku"><i class="fa fa-print"></i></a>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
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
<script>
    $("#datepicker").datepicker({
        inline: true,
        dateFormat: "dd-mm-yy"
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