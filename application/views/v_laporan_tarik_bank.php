<div class="container" style="margin-top: 80px; height: 100%; padding: 0px; margin-bottom: 50px;">    
    <div class="row" style="">
        <div class="col-lg-6">
            <h1 class="page-header" style="margin-top: 0px;">Daftar Pengambilan Kas Bank</h1>            
        </div>
        <div class="col-lg-6" style="background-color: whitesmoke">
            <h1 class="" id="saldo" style="margin-top: 0px;">Saldo Bank : Rp.<?php echo number_format($saldo_bank, 0, ",", ".") ?>,-</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb" style="background-color: white; margin-top: 00px;">
                <li><a href="<?php echo base_url(); ?>index.php/Laporan/daftar_tarik_bank"><i class="fa fa-home"></i> Daftar Pengambilan Kas Bank</a></li>
            </ol>
        </div>
    </div>
    <?php if ($this->session->userdata("Level") != 0) { ?>
        <div style="background-color: white; height: 70px;">
            <div class="col-md-12">
                <a href="<?php echo base_url(); ?>index.php/laporan/tarik_kas_bank" class="btn btn-primary" style="border-radius: 0px; margin-top: 10px;" style="border-radius: 0px;"><i class="fa fa-plus">&nbsp</i> Buat Transaksi Pengambilan Kas Bank</a>
            </div>
        </div>
    <?php }
    ?>
    <div style="<?php echo $this->session->userdata("Level") == 0 ? 'height: 75px; background-color: white; padding: 20px 20px;' : '' ?>">
        <form class="form-inline" method="post" action="<?php
        echo current_url();
        ?>">
                  <?php if ($this->session->userdata("Level") == 0) : ?>
                <div class="form-group">
                    <label class="">Cabang : </label>
                    <select class="form-control siku" style="width: 200px" name="cabang">
                        <option value="0"> --- Semua Cabang ---</option>
                        <?php foreach ($cabangs as $cabang): ?>
                            <option value="<?php echo $cabang->idcabang ?>" <?php echo $cabang->idcabang == $selectCabang ? "selected" : "" ?>><?php echo $cabang->provinsi ?> - <?php echo $cabang->kabupaten ?></option>
                        <?php endforeach; ?>
                    </select>                 
                    &nbsp;<button type="submit" name='btn_pilih' value='btn_pilih' class="btn btn-primary siku">&nbsp;&nbsp;Pilih&nbsp;&nbsp;</button>                
                </div>
                <br><br>
            <?php endif; ?>  
        </form>   
    </div>
    <br>
    <div class="col-md-12" style="background-color: white;">
        <table class='table table-striped table-hover' id="list_laporan">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Admin <?php
                        if ($this->session->userdata("Level") == 0) {
                            echo "Cabang";
                        }
                        ?></th>
                    <th>Total Pengambilan</th>
                    <!--<th>Aksi</th>-->
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($laporans as $laporan):
                    ?>
                    <tr>
                        <td><?php echo strftime("%d-%m-%Y", strtotime($laporan->tanggal)); ?></td>
                        <td><?php echo $laporan->provinsi . " - " . $laporan->kabupaten; ?></td>
                        <td>Rp <?php echo number_format($laporan->jumlah, 0, ",", ".") ?>.- </td>
    <!--                        <td style="width: 50px; text-align: center;">
                            <a href="<?php echo base_url(); ?>index.php/laporan/cetaklaporanpengeluaran/<?php echo $laporan->IDSetoran ?>" class="btn btn-sm btn-info siku"><i class="fa fa-print"></i></a>
                        </td>-->
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