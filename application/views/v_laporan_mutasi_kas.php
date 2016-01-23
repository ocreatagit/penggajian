<div class="container" style="margin-top: 80px; height: 100%; padding: 0px; margin-bottom: 50px;">    
    <div class="row" style="">
        <div class="col-lg-12">
            <h1 class="page-header" style="margin-top: 0px;">Laporan Mutasi Kas</h1>
            <ol class="breadcrumb" style="background-color: white; margin-top: 00px;">
                <li><a href="<?php echo base_url(); ?>index.php/Laporan/harian"><i class="fa fa-home"></i> Laporan Mutasi Kas</a></li>
            </ol>
        </div>
    </div>
    <?php if ($this->session->flashdata("status_laporan_mutasi_kas")) { ?>
        <div class="alert alert-info siku"><?php echo $this->session->flashdata("status_laporan_mutasi_kas") ?></div>
    <?php } ?>
    <?php
    $saldo_mutasi = 0;
    ?>
    <div class="col-md-12" style="background-color: white; margin-bottom: 20px; padding-bottom: 20px; padding-top: 10px;">
        <form class="form-horizontal" method="post" action="<?php echo current_url(); ?>">
            <?php if ($this->session->userdata('Level') == 0) {
                ?>
                <div class="form-group">
                    <label for="exampleInputName2" class="control-label col-lg-2" style=""> Cabang : </label>
                    <div class="col-lg-3">
                        <select name="cabang" class="form-control siku">
                            <?php
                            foreach ($cabangs as $cabang) {
                                ?>
                                <option value="<?php echo $cabang->idcabang ?>" <?php if ($status_IDCabang == $cabang->idcabang) echo "selected"; ?>><?php echo $cabang->provinsi . " - " . $cabang->kabupaten ?></option>
                            <?php }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <select name="jenis" class="form-control siku">
                            <option value="1" <?php if ($status_jenis == 1) echo "selected"; ?>>Admin Lapangan</option>
                            <option value="2" <?php if ($status_jenis == 2) echo "selected"; ?>>Admin Kantor</option>
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
            <div class="form-group" style="">
                <label for="exampleInputName2" class="control-label col-lg-2" style="">Kirim ke Email :</label>
                <div class="col-lg-2">
                    <input class="form-control siku" type="text" id="" placeholder="Email" name="email" value="" style="background-color: whitesmoke">
                    <?php if (form_error('email')) {
                        ?>
                        <span class='warna' style="color: red;" id='lokasi_error'><p style='margin: 0px; margin-top: 8px;'><?php echo form_error("email") ?></span>
                    <?php } ?>
                </div>
                <label for="exampleInputName2" class="col-lg-4" style="color: red;">(Diisi jika ingin mengirim email)</label>
            </div>
            <div class="form-group">
                <div class="col-lg-6 col-lg-offset-2">
                    <button type="submit" name='btn_pilih' value='btn_pilih' class="btn btn-default siku">&nbsp;&nbsp;Pilih&nbsp;&nbsp;</button>
                    <button type="submit" name='btn_export' value='btn_export' class="btn btn-success siku">&nbsp;&nbsp;<i class="fa fa-book"></i> Export To XLS&nbsp;&nbsp;</button>
                    <button type="submit" name='btn_print' value='btn_print' class="btn btn-primary siku">&nbsp;&nbsp;<i class="fa fa-print"></i> Print &nbsp;&nbsp;</button> 
                    <button type="submit" name='btn_email' value='btn_email' class="btn btn-warning siku">&nbsp;&nbsp;<i class="fa fa-envelope"></i> Kirim Email &nbsp;&nbsp;</button> 
                </div>
            </div>
        </form>

    </div>
    <div class="col-md-12" style="background-color: white; padding-top: 15px;">
        <?php if ($periode != "Laporan Bulan Ini") {
            ?>
            <h2 style="margin-bottom: 30px;"><i class="fa fa-calendar"></i> Periode <?php echo $periode ?></h2>
        <?php } else {
            ?>
            <h2 style="margin-bottom: 30px;"><i class="fa fa-calendar"></i> <?php echo $periode ?></h2>
            <?php
        }
        ?>
        <table class='table table-striped table-hover' id="list_laporan">
            <thead>
                <tr>
                    <!--<th style="display: none;">ID</th>-->
                    <th>Tanggal Mutasi</th>
                    <th style="background-color: #2B669A">Tanggal Buat Nota</th>
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
                    ?>

                    <?php
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
                foreach ($jurnals as $laporan):
                    ?>
                    <tr>
                        <td><?php echo strftime("%d-%m-%Y %H:%M:%S", strtotime($laporan->tanggal)); ?></td>
                        <td><?php
                            echo strftime("%d-%m-%Y", strtotime($laporan->tglref));
                            ?></td>
                        <td><?php echo $laporan->keterangan; ?></td>
                        <td>Rp <?php echo number_format($laporan->kasmasuk, 0, ",", ".") ?>.- </td>
                        <td>Rp <?php echo number_format($laporan->kaskeluar, 0, ",", ".") ?>.- </td>
                        <td>Rp.<?php echo number_format($laporan->sifat == 'K' ? $saldo_mutasi -= $laporan->kaskeluar : $saldo_mutasi += $laporan->kasmasuk, 0, ',', '.'); ?>,-</td>
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

<style type="text/css">
    .ui-datepicker-year, .ui-datepicker-month{
        color: black;
    }
</style>

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
            "order": [[0, "ASC"]],
            "aoColumnDefs": [
                {"sType": "date-dmy", "aTargets": [0]}
//                {"bSortable": false, "aTargets": [ 0 ]}
            ],
        });
    });
</script>
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