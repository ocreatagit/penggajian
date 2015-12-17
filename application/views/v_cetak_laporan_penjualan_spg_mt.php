<div class="container" style="margin-top: 60px; height: 100%; padding: 0px; margin-bottom: 50px;">
    <div class="row" style="">
        <div class="col-lg-12">
            <h1 class="page-header" style="margin-top: 0px; border-bottom: #000 solid 1px;">LAPORAN PENJUALAN SPG MT</h1>
        </div>
    </div>
    <?php
    $saldo_mutasi = 0;
    ?>
    <div class="row" style="">
        <div class="col-lg-12">
            <h2 style="margin-top: 5px;">Laporan Penjualan SPG MT <small> <?php echo $data ?> </small> </h2>
            <table id="data-table" class="tablesorter tablesorter-blue" border="1" cellpadding="8" cellspacing="0" style="width: 100%;">
                <thead>
                    <tr>
                        <!--<th style="display: none;">ID</th>-->
                        <th>Tanggal</th>
                        <th>Nama SPG</th>
                        <th>Nama Barang</th>
                        <th>Jumlah (Pcs)</th>
                        <th>Konversi Satuan</th>
                        <th>Nama Toko</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- loop isi -->
                    <?php
                    foreach ($laporans as $laporan):
                        $total_barang = intval($laporan->jumlah);
                        $karton = floor($total_barang / (intval($laporan->nilai_karton) * 12));
                        $total_barang %= (intval($laporan->nilai_karton) * 12);
                        $lusin = floor($total_barang / 12);
                        $total_barang %= 12;
                        ?>
                        <tr>
                            <td><?php echo strftime("%d-%m-%Y", strtotime($laporan->tanggal)); ?></td>
                            <td><?php echo $laporan->sales; ?></td>
                            <td><?php echo $laporan->barang ?> </td>
                            <td><?php echo $laporan->jumlah ?></td>
                            <td><?php echo ($karton == 0 ? "" : $karton . " karton") . ($lusin == 0 ? "" : $lusin . " lusin") . $total_barang . " pcs" ?></td>
                            <td><?php echo $laporan->toko ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <!-- end loop isi -->
                </tbody>
            </table>

            <hr>
            <h2>Total Penjualan</h2>
            <table class="col-sm-3">
                <thead>
                    <tr style="background-color: yellow" >
                        <td style="text-align: center">Nama Barang</td>
                        <td class="text-center">Jumlah (Pcs)</td>
                        <td class="text-center">Konversi Satuan</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($totals) <= 0) : ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">
                                Tidak Ada Data
                            </td>
                        </tr>
                        <?php
                    endif;
                    foreach ($totals as $total):
                        $total_barang = intval($total->jumlah);
                        $karton = floor($total_barang / (intval($total->nilai_karton) * 12));
                        $total_barang %= (intval($total->nilai_karton) * 12);
                        $lusin = floor($total_barang / 12);
                        $total_barang %= 12;
                        ?>
                        <tr>
                            <td style="text-align: center"><?php echo $total->barang ?></td>
                            <td class="text-center"><?php echo $total->jumlah ?></td>
                            <td><?php echo ($karton == 0 ? "" : $karton . " karton") . ($lusin == 0 ? "" : $lusin . " lusin") . $total_barang . " pcs" ?></td>
                        </tr>
                    <?php endforeach; ?>
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