<div class="container" style="margin-top: 60px; height: 100%; padding: 0px; margin-bottom: 50px;">
    <div class="row" style="">
        <div class="col-lg-12">
            <h1 class="page-header" style="margin-top: 0px; border-bottom: #000 solid 1px;">DAFTAR LAPORAN KAS </h1>
        </div>
    </div>
    <div class="row" style="">
        <div class="col-lg-12">
            <?php if ($periode != "Laporan Bulan Ini") {
                ?>
                <h2 style="margin-bottom: 30px; margin-top: 0px;">Periode <?php echo $periode ?></h2>
            <?php } else {
                ?>
                <h2 style="margin-bottom: 30px; margin-top: 0px;"><?php echo $periode ?></h2>
                <?php
            }
            ?>
            <table id="data-table" class="tablesorter tablesorter-blue" border="1" cellpadding="8" cellspacing="0" style="width: 100%;">
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
                    <tr>
                        <td colspan="">&nbsp;</td>
                        <td colspan="">&nbsp;</td>
                        <td colspan="">&nbsp;</td>
                        <td colspan="">&nbsp;</td>
                    </tr>
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
            </table>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>bootstrap/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>jquery-ui/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>bootstrap/js/ajaxLaporan.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>Datatable/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>alertify/alertify.min.js"></script>

<script>
    $(document).ready(function () {

        $("#lokasi").val('');
        $("#salesnya_admin").val('');
        $("#nama_produk").val('');
        //                                                        $('#informasi').DataTable({
        //                                                            "bFilter": false,
        //                                                            "bPaginate": false,
        //                                                            "bLengthChange": false,
        //                                                            "bInfo": false,
        //                                                            "oLanguage": {
        //                                                                "sEmptyTable": '',
        //                                                                "sInfoEmpty": ''
        //                                                            },
        //                                                            "sEmptyTable": "",
        //                                                        });
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
        $("#add_success").hide();

    });
</script>
<script>
    alertify.defaults = {
        // dialogs defaults
        modal: true,
        basic: false,
        frameless: false,
        movable: true,
        resizable: true,
        closable: true,
        closableByDimmer: true,
        maximizable: true,
        startMaximized: false,
        pinnable: true,
        pinned: true,
        padding: true,
        overflow: true,
        maintainFocus: true,
        transition: 'pulse',
        autoReset: true,
        // notifier defaults
        notifier: {
            // auto-dismiss wait time (in seconds)  
            delay: 5,
            // default position
            position: 'bottom-right'
        },
        // language resources 
        glossary: {
            // dialogs default title
            title: 'Informasi',
            // ok button text
            ok: 'OK',
            // cancel button text
            cancel: 'Cancel'
        },
        // theme settings
        theme: {
            // class name attached to prompt dialog input textbox.
            input: 'ajs-input',
            // class name attached to ok button
            ok: 'ajs-ok',
            // class name attached to cancel button 
            cancel: 'ajs-cancel'
        }
    };

    alertify
            .alert("Tekan tombol 'CTRL + P' Untuk Print Dokument!", function () {
//                alertify.message('OK');
            });
</script>
</body>
</html>