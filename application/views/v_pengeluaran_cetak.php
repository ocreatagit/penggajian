<div class="container" style="margin-top: 60px; height: 100%; padding: 0px; margin-bottom: 50px;">
    <div class="row" style="">
        <div class="col-lg-12">
            <h1 class="page-header" style="margin-top: 0px; border-bottom: #000 solid 1px;">PENGELUARAN</h1>
        </div>
    </div>
    <div class="row" style="">
        <div class="col-lg-12">
            <h2 style="margin-left: 15px; margin-top: 0px;">Pengeluaran <small><?php echo $searchby ?> - Periode <?php echo $data ?></small></h2>            
            <table id="data-table" class="tablesorter tablesorter-blue" border="1" cellpadding="8" cellspacing="0" style="width: 100%;">
                <thead style="text-align: center; background-color: #ffcc33 ">
                    <tr>
                        <td><strong>Tanggal</strong></td>
                        <?php if ($kolom): ?>
                            <td><strong>Keterangan</strong></td>
                        <?php endif; ?>
                        <td><strong>Jumlah Pengeluaran</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    if (count($isi_tabel) > 0) {
                        foreach ($isi_tabel as $isi):
                            ?>
                            <tr>
                                <td><?php echo date('d-m-Y', strtotime($isi->tanggal)) ?></td>
                                <?php if ($kolom): ?>
                                    <td><?php echo $isi->keterangan . ($isi->keterangan_lanjut != "" ? " - " . $isi->keterangan_lanjut : "" ) ?></td>
                                <?php endif; ?>
                                <td>Rp. <?php echo number_format($isi->jumlah, 0, ',', '.') ?>,-</td>
                            </tr>
                            <?php
                            $total+= $isi->jumlah;
                        endforeach;
                    } else {
                        ?>
                        <tr>
                            <td colspan="3" style="text-align: center">Tidak ada data penjualan pada periode ini</td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td <?php echo $kolom ? 'colspan="2"' : '' ?> align="right"> Total Pengeluaran </td>
                        <td  align="left">Rp. <?php echo number_format($total, 0, ',', '.') ?>,-</td>
                    </tr>
                </tfoot>  
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