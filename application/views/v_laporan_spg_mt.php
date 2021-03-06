<?php date_default_timezone_set("Asia/Jakarta") ?>
<style>
    td:first-child{
        text-align: right;
    }
</style>  
<div class="container" style="margin-top: 80px; height: 100%; padding: 0px; margin-bottom: 50px;">

    <div class="row" style="">
        <div class="col-lg-12">
            <h2 class="page-header" style="margin-top: 0px;">Penjualan SPG</h2>
            <ol class="breadcrumb" style="background-color: white; margin-top: 00px;">
                <li class="active"><i class="fa fa-home"></i> Penjualan SPG</li>
            </ol>
        </div>
    </div>
    <!--<div--> 
    <?php if ($this->session->flashdata("status_laporan_penjualan_spg")) { ?>
        <div class="alert alert-info siku"><?php echo $this->session->flashdata("status_laporan_penjualan_spg") ?></div>
    <?php } ?>
    <div style="background-color: white; padding: 20px;">
        <!--<div class="col-md-12" style="margin-top: 17px;">-->
            <form class="form-horizontal" method="post" action="<?php
            echo current_url();
            $total = array();
            ?>">                
                <div class="form-group">
                    <label class="control-label col-sm-2" for="exampleInputName2">Periode : </label> 
                    <div class="col-sm-3">
                        <input class="form-control siku" type="text" id="datepicker1" placeholder="Dari" name="tanggal_awal" value="">         
                    </div>

                    <label  class="control-label col-sm-1" for="exampleInputEmail2">Sampai </label>
                    <div class="col-sm-3">
                        <input class="form-control siku" type="text" id="datepicker2" placeholder="Sampai" name="tanggal_akhir" value="">
                    </div>
                </div>        
                <div class="form-group" >
                    <label class="control-label col-sm-2" for="filterSPG">Nama Toko : </label>
                    <div class="col-sm-10">
                        <select name="filter" id="filterSPG" style="width: 30%" class="form-control siku">
                            <option value="0">Semua Toko</option>
                            <!-- looping toko -->
                            <?php foreach ($tokos as $toko): ?>
                                <option value="<?php echo $toko->IDToko ?>"><?php echo $toko->nama ?></option>
                            <?php endforeach; ?>
                            <!-- end looping toko -->
                        </select>
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
                <div class="form-group" >
                    <label class="control-label col-sm-2" for="filterSPG"></label>
                    <div class="col-sm-3">
                        <button type="submit" name='btn_pilih' value='btn_pilih' class="btn btn-primary siku btn-block">&nbsp;&nbsp;Pilih&nbsp;&nbsp;</button>
                    </div>
                </div>
            </form>

        <!--</div>-->
    </div>
    <hr>
    <div class="row" >
        <div class="col-lg-12" style="margin-top: -10px;">
            <div class="panel panel-info siku">
                <div class="panel-heading siku">
                    <div class="panel-title">                                                    
                        <h2 style="margin-top: 5px;">Laporan SPG MT <small> <?php echo $data ?> </small> </h2>                        
                    </div>
                </div>
                <div class="panel-body">   
                    <!-- loop isi -->
                    <?php foreach ($laporans as $laporan): ?>
                        <div class="col-md-3">
                            <div class="panel panel-success siku">
                                <div class="panel-heading">
                                    <h5 style="font-size: x-large"><strong><?php echo $laporan->nama ?></strong></h5>
                                </div>
                                <div class="panel-body">
                                    <div style="text-align: center;">
                                        <img src="<?php echo base_url() ?>uploads/<?php echo $laporan->foto ?>" alt="<?php echo $laporan->foto ?>" width="200" >
                                    </div>
                                    <p>Pendapatan : </p>
                                    <button type="button" class="btn btn-success col-sm-6" data-toggle="modal" data-target="#myModal<?php echo $laporan->IDSalesMT ?>"><i class='fa fa-pie-chart'></i> Detail</button>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- end loop isi -->
                </div>
            </div>
        </div>
    </div>  
    <!-- modal -->
    <?php
    $arr_id = array();
    if (count($laporan_alls)) {
        $id_temp = 0;
        for ($i = 0; $i < count($laporan_alls); $i++):
            array_push($arr_id, $laporan_alls[$i]->IDSalesMT);
            ?>
            <div class="modal fade" id="myModal<?php echo $laporan_alls[$i]->IDSalesMT ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Daftar Penjualan</h4>
                        </div>
                        <div class="modal-body">                            
                            <table>
                                <thead>
                                    <tr>
                                        <td>Nama Barang</td>
                                        <td>Jumlah Terjual (pcs)</td>
                                        <td>Konversi Satuan</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $id_temp = $laporan_alls[$i]->IDSalesMT;
                                    while ($i < count($laporan_alls)):
                                        if ($id_temp == $laporan_alls[$i]->IDSalesMT) {
                                            $total_barang = intval($laporan_alls[$i]->jumlah);
                                            $karton = floor($total_barang / (intval($laporan_alls[$i]->nilai_karton) * 12));
                                            $total_barang %= (intval($laporan_alls[$i]->nilai_karton) * 12);
                                            $lusin = floor($total_barang / 12);
                                            $total_barang %= 12;
                                            ?>
                                            <tr>
                                                <td><?php echo $laporan_alls[$i]->nama ?></td>
                                                <td><?php echo $laporan_alls[$i]->jumlah ?></td>
                                                <td><?php echo ($karton == 0 ? "" : $karton . " karton") . ($lusin == 0 ? "" : $lusin . " lusin") . $total_barang . " pcs" ?></td>
                                            </tr>
                                            <?php
                                            $i++;
                                        } else {
                                            $i--;
                                            break;
                                        }
                                    endwhile;
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <?php
        endfor;
    }
    ?>
    <!-- modal -->
    <!-- modal -->
    <?php
    foreach ($laporans as $laporan):
        if (!array_search($laporan->IDSalesMT, $arr_id)) {
            ?>
            <div class="modal fade" id="myModal<?php echo $laporan->IDSalesMT ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Daftar Penjualan</h4>
                        </div>
                        <div class="modal-body">                            
                            <h3>Tidak Ada Data Penjualan</h3>
                        </div>

                    </div>
                </div>
            </div>
    <?php } endforeach; ?>
    <!-- modal -->
</div>

<script src="<?php echo base_url(); ?>bootstrap/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>jquery-ui/jquery-ui.js"></script>
<style type="text/css">
    .ui-datepicker-year, .ui-datepicker-month{
        color: black;
    }
</style>
<script>
    $('#ListView').hide();
    $('#btn_export').hide();
    function gridview() {
        $('#GridView').show();
        $('#ListView').hide();
        $('#btn_export').hide();
    }
    function listview() {
        $('#GridView').hide();
        $('#ListView').show();
        $('#btn_export').show();
    }
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
</script>
</body>
</html>

