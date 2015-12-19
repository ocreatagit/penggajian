<div class="container" style="margin-top: 60px; height: 100%; padding: 0px; margin-bottom: 50px;">
    <div class="row" style="">
        <div class="col-lg-12">
            <h1 class="page-header" style="margin-top: 0px; border-bottom: #000 solid 1px;">PENJUALAN SPG</h1>
        </div>
    </div>
    <div class="row" style="">
        <div class="col-lg-12">
            <?php
            $total = array();
            if ($periode != "Laporan Bulan Ini") {
                ?>
                <h2 style="margin-bottom: 30px; margin-top: 0px;">Periode <?php echo $periode ?></h2>
            <?php } else {
                ?>
                <h2 style="margin-bottom: 30px; margin-top: 0px;"><?php echo $periode ?></h2>
                <?php
            }
            foreach ($barangs as $barang):
                $total[$barang->IDBarang] = 0;
            endforeach;
            ?>
            <table id="data-table" class="tablesorter tablesorter-blue" border="1" cellpadding="8" cellspacing="0" style="width: 100%;">
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