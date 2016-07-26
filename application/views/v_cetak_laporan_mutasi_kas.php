<div class="container" style="margin-top: 60px; height: 100%; padding: 0px; margin-bottom: 50px;">
    <div class="row" style="">
        <div class="col-lg-12">
            <h1 class="page-header" style="margin-top: 0px; border-bottom: #000 solid 1px;">LAPORAN MUTASI KAS</h1>
        </div>
    </div>
    <?php
    $saldo_mutasi = 0;
    ?>
    <div class="row" style="">
        <div class="col-lg-12">
            <?php if ($periode != "Laporan Bulan Ini") {
                ?>
                <h2 style="margin-bottom: 30px;"><i class="fa fa-calendar"></i> Periode <?php echo $periode ?></h2>
            <?php } else {
                ?>
                <h2 style="margin-bottom: 30px;"><i class="fa fa-calendar"></i> <?php echo $periode ?></h2>
                <?php
            }
            ?>
            <table id="data-table" class="tablesorter tablesorter-blue" border="1" cellpadding="8" cellspacing="0" style="width: 100%;">
                <thead>
                    <tr>
                    <!--<th style="display: none;">ID</th>-->
                        <th>Tanggal Mutasi</th>
                        <th>Tanggal Buat Nota</th>
                        <th>Keterangan</th>
                        <th>Kas Masuk</th>
                        <th>Kas Keluar</th>
                        <th>Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($saldo_pindahan) > 0) {
                        foreach ($saldo_pindahan as $saldo):
                            $saldo->sifat == 'K' ? $saldo_mutasi -= $saldo->kaskeluar : $saldo_mutasi += $saldo->kasmasuk;
                        endforeach;
                    }
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>                        
                        <td>Saldo Sebelumnya </td>
                        <td>Rp.<?php echo number_format($saldo_mutasi, 0, ',', '.'); ?>,-</td>
                    </tr>
                    <?php
                    if (count($jurnals) > 0) {
                        foreach ($jurnals as $laporan):
                            $ket = explode('|', $laporan->keterangan);
                            ?>
                            <tr>
                                <td><?php echo strftime("%d-%m-%Y %H:%M:%S", strtotime($laporan->tanggal)); ?></td>
                                <td><?php
                    if (is_null(strftime("%d-%m-%Y", strtotime($laporan->tglref))) == FALSE) {
                        echo strftime("%d-%m-%Y", strtotime($laporan->tglref));
                    } else {
                        echo '';
                    }
                            ?></td>
                                <td><?php echo $laporan->keterangan; ?></td>
                                <td>Rp <?php echo number_format($laporan->kasmasuk, 0, ",", ".") ?>.- </td>
                                <td>Rp <?php echo number_format($laporan->kaskeluar, 0, ",", ".") ?>.- </td>
                                <td>Rp.<?php echo number_format($laporan->sifat == 'K' ? $saldo_mutasi -= $laporan->kaskeluar : $saldo_mutasi += $laporan->kasmasuk, 0, ',', '.'); ?>,-</td>
                            </tr>

                            <?php
                        endforeach;
                    } 
                    ?>
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