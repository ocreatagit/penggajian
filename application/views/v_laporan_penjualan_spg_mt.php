<?php date_default_timezone_set("Asia/Jakarta") ?>
<style>
    td:first-child{
        text-align: right;
    }
</style>  
<div class="container" style="margin-top: 80px; height: 100%; padding: 0px; margin-bottom: 50px;">

    <div class="row" style="">
        <div class="col-lg-12">
            <h2 class="page-header" style="margin-top: 0px;">Laporan Penjualan SPG</h2>
            <ol class="breadcrumb" style="background-color: white; margin-top: 00px;">
                <li class="active"><i class="fa fa-home"></i> Laporan Penjualan SPG</li>
            </ol>
        </div>
    </div>
    <!--<div--> 
    <div style="background-color: white; height: 310px;">
        <div class="col-md-12" style="margin-top: 17px;">
            <form class="form-horizontal" method="post" action="<?php
            echo current_url();
            $total = array();
            ?>">

                <?php if ($this->session->userdata("Level") == 0) : ?>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Cabang : </label>
                        <div class="col-sm-10">                            
                            <select class="form-control siku" style="width: 30%" name="cabang">
                                <option value="0"> --- Semua Cabang ---</option>
                                <?php foreach ($cabangs as $cabang): ?>
                                    <option value="<?php echo $cabang->IDCabang ?>"><?php echo $cabang->provinsi ?> - <?php echo $cabang->kabupaten ?></option>                                                                                                                                  <option value="////<?php echo $cabang->idcabang ?>" <?php echo $cabang->idcabang == $selectCabang ? "selected" : "" ?>><?php echo $cabang->provinsi ?> - <?php echo $cabang->kabupaten ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>                        
                    </div>
                <?php endif; ?>

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
                    <label class="control-label col-sm-2" for="filterSPG">Nama SPG : </label>
                    <div class="col-sm-10">
                        <select name="filterSPG" id="filterSPG" style="width: 30%" class="form-control siku">
                            <option value="0">Semua SPG</option>
                            <!-- looping toko -->
                            <?php foreach ($spgs as $spg): ?>
                                <option value="<?php echo $spg->IDSalesMT ?>"><?php echo $spg->nama ?></option>
                            <?php endforeach; ?>
                            <!-- end looping toko -->
                        </select>
                    </div>
                </div>
                <div class="form-group" >
                    <label class="control-label col-sm-2" for="filterSPG">Nama Toko : </label>
                    <div class="col-sm-10">
                        <select name="filterToko" id="filterSPG" style="width: 30%" class="form-control siku">
                            <option value="0">Semua Toko</option>
                            <!-- looping toko -->
                            <?php foreach ($tokos as $toko): ?>
                                <option value="<?php echo $toko->IDToko ?>"><?php echo $toko->nama ?></option>
                            <?php endforeach; ?>
                            <!-- end looping toko -->
                        </select>
                    </div>
                </div>
                <div class="form-group" >
                    <label class="control-label col-sm-2" for="filterSPG">Nama Barang : </label>
                    <div class="col-sm-10">
                        <select name="filterBarang" id="filterSPG" style="width: 30%" class="form-control siku">
                            <option value="0">Semua Barang</option>
                            <!-- looping toko -->
                            <?php foreach ($barangs as $barang): ?>
                                <option value="<?php echo $barang->IDBarangMT ?>"><?php echo $barang->nama ?></option>
                            <?php endforeach; ?>
                            <!-- end looping toko -->
                        </select>
                    </div>
                </div>     
                <div class="form-group" >
                    <label class="control-label col-sm-2" for="filterSPG"></label>
                    <div class="col-sm-3">
                        <button type="submit" name='btn_pilih' value='btn_pilih' class="btn btn-primary siku btn-block">&nbsp;&nbsp;Pilih&nbsp;&nbsp;</button>
                    </div>                    
                    <div class="col-sm-3">
                        <button type="submit" name='btn_print' value='btn_print' class="btn btn-success siku btn-block">&nbsp;&nbsp; <i class="fa fa-book"></i> Export To Xls&nbsp;&nbsp;</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <hr>
    <div class="row" >
        <div class="col-lg-12" style="margin-top: -10px;">
            <div class="panel panel-info siku">
                <div class="panel-heading siku">
                    <div class="panel-title">                                                    
                        <h2 style="margin-top: 5px;">Laporan Penjualan SPG MT <small> <?php echo $data ?> </small> </h2>                        
                    </div>
                </div>
                <div class="panel-body">   
                    <table class='table table-striped table-hover' id="list_laporan">
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
                                $total_barang = intval($laporan->umlah);
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
                                $total_barang = intval($total->total_jumlah);
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
    </div>  
</div>

<script src="<?php echo base_url(); ?>bootstrap/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>Datatable/js/jquery.dataTables.js"></script>
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
        $('#list_laporan').DataTable({
            "order": [[0, "asc"]],
            "aoColumnDefs": [
                {"sType": "date-dmy", "aTargets": [0]}]
        });
    });
</script>
</body>
</html>

