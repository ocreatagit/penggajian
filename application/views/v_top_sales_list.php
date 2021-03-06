<div class="container" style="margin-top: 80px; height: 100%; padding: 0px; margin-bottom: 50px;">    
    <div class="row" style="">
        <div class="col-lg-12">
            <h1 class="page-header" style="margin-top: 0px;">Top Sales</h1>
            <ol class="breadcrumb" style="background-color: white; margin-top: 00px;">
                <li><a href=""><i class="fa fa-home"></i> Top Sales</a></li>
            </ol>
        </div>
    </div>
    <div class="row" style="">
        <div class="col-lg-12">
            <div class="panel panel-default siku">
                <div class="panel-body siku">
                    <form class="form-horizontal" method="POST" action="<?php echo current_url(); ?>">
                        <?php if ($this->session->userdata('Level') == 0) {
                            ?>
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
                        <?php }
                        ?>
                        <!--                        <div class="form-group">
                                                    <label class="col-lg-2 control-label" for="exampleInputName2">Jenis Pencarian</label>                    
                                                    <label class="checkbox-inline"><input type="radio" checked name="kategori" value="Periode">Periode</label>
                                                    <label class="checkbox-inline"><input type="radio" name="kategori" value="Bulan">Bulan</label>
                                                </div>-->
                        <div id="periode" class="form-group">
                            <label class="col-lg-2 control-label" for="exampleInputName2">Dari</label>
                            <div class="col-lg-3">
                                <input class="form-control siku" type="text" id="datepicker1" placeholder="Dari" name="tanggal_awal" value="">
                            </div>
                            <label class="col-lg-1 control-label" for="exampleInputName2">Sampai</label>
                            <div class="col-lg-3">
                                <input class="form-control siku" type="text" id="datepicker2" placeholder="Sampai" name="tanggal_akhir" value="">
                            </div>                    
                        </div>
                        <div class="form-group" >
                            <div class="col-lg-12 col-lg-offset-2">                        
                                <button class="btn btn-primary siku" type="submit" value="kategori" name="submit">Pilih</button>
                                &nbsp;<button type="submit" name='btn_export' value='btn_export' class="btn btn-success siku">&nbsp;&nbsp;<i class="fa fa-book"></i> Export To XLS&nbsp;&nbsp;</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="panel panel-default siku">
                <div class="panel-body siku">
                    <h2 style="margin-left: 15px; margin-top: 0px;">TOP SALES <?php echo $data ?></h2>
                    <div class="col-lg-12">
                        <table class='table table-striped table-hover'>
                            <thead>
                                <tr>
                                    <td>No.</td>
                                    <td>Nama</td>
                                    <td>Pendapatan</td>
                                    <td>Nama Barang</td>
                                    <td>Jumlah Barang</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 1;
                                $jumlah_barang = count($barangs);
                                if (count($topbarangs) > 0) {
                                    foreach ($topbarangs as $sales) :
                                        ?> 
                                        <tr>
                                            <td rowspan="<?= $jumlah_barang + 1; ?>"><?php echo $counter++; ?></td>
                                            <td rowspan="<?= $jumlah_barang + 1; ?>"><?php echo $sales->nama; ?></td>
                                            <td rowspan="<?= $jumlah_barang + 1; ?>">Rp. <?php echo number_format($sales->jumlah, 0, ',', '.') ?>,-</td>
                                        </tr>


                                        <?php
                                        $modal = $modalsales[$counter - 2];
                                        foreach ($barangs as $semuabarang) {
                                            $tulis = true;
                                            ?>                                    
                                            <tr>
                                                <td><?php
                                                    echo $semuabarang->namaBarang
                                                    ?></td>
                                                <td>
                                                    <?php foreach ($modal as $mbarang) { ?>
                                                        <?php
                                                        if ($semuabarang->namaBarang == $mbarang->namaBarang) {
                                                            echo $mbarang->jumlah;
                                                            $tulis = false;
                                                        }
                                                        ?>
                                                        <?php
                                                    } if ($tulis) {
                                                        echo "0";
                                                    }
                                                    ?>

                                                </td>
                                            </tr>
                                        <?php } ?>


                                        <?php
                                    endforeach;
                                } else {
                                    ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal -->
    <?php
    if (count($modalsales) > 0) {
        foreach ($modalsales as $modal) {
            if (count($modal) > 0) {
                ?>
                <div class="modal fade" id="myModal<?php echo $modal[0]->IDSales ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Daftar Penjualan</h4>
                                <h4 class="modal-title">Nama : <?php echo $modal[0]->nama ?></h4>
                            </div>
                            <div class="modal-body">                            
                                <table>
                                    <thead>
                                        <tr>
                                            <td>Nama Barang</td>
                                            <td>Jumlah Terjual</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($barangs as $semuabarang) {
                                            $tulis = true;
                                            ?>                                    
                                            <tr>
                                                <td><?php
                                                    echo $semuabarang->namaBarang
                                                    ?></td>
                                                <td>
                                                    <?php foreach ($modal as $mbarang) { ?>
                                                        <?php
                                                        if ($semuabarang->namaBarang == $mbarang->namaBarang) {
                                                            echo $mbarang->jumlah;
                                                            $tulis = false;
                                                        }
                                                        ?>
                                                        <?php
                                                    } if ($tulis) {
                                                        echo "0";
                                                    }
                                                    ?>

                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
    ?>

    <!-- modal -->
</div>


<script src="<?php echo base_url(); ?>bootstrap/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>jquery-ui/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>bootstrap/js/ajaxLaporan.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>Datatable/js/jquery.dataTables.js"></script>



<script>
    $(document).ready(function () {
        $("#lokasi").val('');
        $("#salesnya_admin").val('');
        $("#nama_produk").val('');
        $('#list_laporan').DataTable({});
        $('#monthly').hide();
        $(document).ready(function () {
            $('input[type=radio][name=kategori]').change(function () {
                if (this.value == 'Periode') {
                    $('#periode').show();
                    $('#monthly').hide();
<<<<<<< HEAD
                } else if (this.value == 'Bulan') {
=======
                }
                else if (this.value == 'Bulan') {
>>>>>>> 5efc2b365b404c9b771c8ee2287ef21733adbcbc
                    $('#periode').hide();
                    $('#monthly').show();
                }
            });
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


