<?php date_default_timezone_set("Asia/Jakarta") ?>
<style>
    td:first-child{
        text-align: right;
    }
</style>  
<div class="container" style="margin-top: 80px; height: 100%; padding: 0px; margin-bottom: 50px;">
    <div class="row" style="">
        <div class="col-lg-12">
            <h2 class="page-header" style="margin-top: 0px;">Kehadiran SPG</h2>
            <ol class="breadcrumb" style="background-color: white; margin-top: 00px;">
                <li class="active"><i class="fa fa-home"></i> Kehadiran SPG</li>
            </ol>
        </div>
    </div>
    <!--<div--> 
     <?php if ($this->session->flashdata("status_laporan_kehadiran")) { ?>
        <div class="alert alert-info siku"><?php echo $this->session->flashdata("status_laporan_kehadiran") ?></div>
    <?php } ?>
    <div style="background-color: white; height: 300px;">
        <div class="col-md-12" style="margin-top: 17px;">
            <form class="form-inline" method="post" action="<?php
            echo current_url();
            $total = array();
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
                    </div>
                    <br><br>
                <?php endif; ?>
                <div class="form-group">
                    <label for="exampleInputName2">Periode : </label>
                    <input class="form-control siku" type="text" id="datepicker1" placeholder="Dari" name="tanggal_awal" value="">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail2">Sampai : </label>
                    <input class="form-control siku" type="text" id="datepicker2" placeholder="Sampai" name="tanggal_akhir" value="">
                </div>
                <br>        
                <br>        
                <div class="form-group" style="margin-left: 27px; ">
                    <label for="filterSPG">SPG : </label>
                    <select name="filter" id="filterSPG" style="width: 200px" class="form-control siku">
                        <option value="0">Semua SPG</option>
                        <?php foreach ($datasales as $sales): ?>
                            <option value="<?php echo $sales->id_sales ?>" <?php if ($sales->id_sales == $selectSeles) echo 'selected'; ?>><?php echo $sales->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <br>
                <div class="form-group" style="margin-left: 15px;">
                    <label for="exampleInputName2" class="control-label" style="">Email :</label>
                        <input class="form-control siku" type="text" id="" placeholder="Email" name="email" value="" style="background-color: whitesmoke"> <label for="exampleInputName2" class="" style="color: red;">(Diisi jika ingin mengirim email)</label>
                        <?php if (form_error('email')) {
                            ?>
                            <span class='warna' style="color: red; position: absolute; left: 100px; margin-top: 30px;" id='lokasi_error'><p style='margin: 0px; margin-top: 8px; '><?php echo form_error("email") ?></span>
                            <br>
                            <br>
                        <?php } ?>
                    
                </div>
                <br>
                <br>
                &nbsp;<button type="submit" name='btn_pilih' value='btn_pilih' style="margin-left: 70px;" class="btn btn-primary siku">&nbsp;&nbsp;Pilih&nbsp;&nbsp;</button>
                &nbsp;<button id="btn_export" type="submit" name='btn_export' value='btn_export' class="btn btn-success siku">&nbsp;&nbsp;<i class="fa fa-book"></i> Export To XLS&nbsp;&nbsp;</button>
                &nbsp;<button id="btn_print" type="submit" name='btn_print' value='btn_print' class="btn btn-default siku">&nbsp;&nbsp;<i class="fa fa-print"></i> Print&nbsp;&nbsp;</button>
                <button type="submit" name='btn_email' value='btn_email' id="btn_email" class="btn btn-warning siku">&nbsp;&nbsp;<i class="fa fa-envelope"></i> Kirim Email &nbsp;&nbsp;</button> 
            </form>

        </div>
    </div>
    <hr>
    <div class="row" >
        <div class="col-lg-12" style="margin-top: -10px;">
            <div class="panel panel-info siku">
                <div class="panel-heading siku">
                    <div class="panel-title row">
                        <div class="col-md-10">                            
                            <h2 style="margin-top: 0px;">Daftar Kehadiran Sales <?php if ($periode != "Laporan Bulan Ini") {
                            ?>
                                    Periode <?php echo $periode ?>
                                    <?php
                                } else {
                                    echo $periode;
                                }
                                ?></h2>
                        </div>
                        <div class="col-md-2" style="padding-right: 45px;">                        
                            <button class="btn btn-danger pull-right" onclick="gridview()" style="" id="btn_grid" ><i class="fa fa-th-large"></i></button>
                            <button class="btn btn-danger pull-right" onclick="listview()" style="margin-right: 2px;" id="btn_list" > <i class="fa fa-th-list"></i></button>
                        </div>
                    </div>
                </div>
                <div class="panel-body">                    
                    <div id="GridView" class="col-md-12" style="background-color: white;">
                        <!-- looping -->
                        <?php if (count($kehadirans) == 0): ?>
                        <div class="alert alert-info"><i class="fa fa-info-circle"></i> Tidak Ada Data</div>
                            <?php
                        endif;
                        foreach ($kehadirans as $kehadiran) :
                            ?> 
                            <div class="col-md-3">
                                <div class="panel panel-success siku">
                                    <div class="panel-heading">
                                        <h5 style="font-size: x-large"><strong><?php echo $kehadiran->nama ?></strong></h5>
                                    </div>
                                    <div class="panel-body">
                                        <div style="text-align: center;">
                                            <img src="<?php echo base_url() ?>uploads/<?php echo $kehadiran->foto ?>" alt="<?php echo $kehadiran->foto ?>" width="200" >
                                        </div>
                                        <br>
                                        <table>
                                            <tr>
                                                <td class="col-md-6">Kehadiran</td>
                                                <td class="text-center"><?php echo $kehadiran->hadir ?></td>
                                            </tr>
                                            <tr>
                                                <td>Absen</td>
                                                <td class="text-center"><?php echo $kehadiran->absen ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <!-- end looping -->
                        <script> console.log('(c) MyOcreata');</script>
                    </div>
                    <div id="ListView" class="col-md-12" style="background-color: white;">
                        <!-- looping -->           
                        <div class="col-md-6">                            
                            <table class="table table-hover" >
                                <thead style="background-color: #ffcc33">
                                    <tr>
                                        <td style="text-align: center"><strong>Nama SPG</strong></td>
                                        <td class="text-center"><strong>Kehadiran</strong></td>
                                        <td class="text-center"><strong>Absen</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($kehadirans) == 0): ?>
                                        <tr>
                                            <td colspan="3" style="text-align: center">Tidak Ada Data</td>
                                        </tr>
                                        <?php
                                    endif;
                                    foreach ($kehadirans as $kehadiran) :
                                        ?> 
                                        <tr>
                                            <td style="text-align: center"><?php echo $kehadiran->nama ?></td>
                                            <td class="text-center"><?php echo $kehadiran->hadir ?></td>
                                            <td class="text-center"><?php echo $kehadiran->absen ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>                        
                        <!-- end looping -->
                        <script> console.log('(c) MyOcreata');</script>
                    </div>
                </div>
            </div>
        </div>
    </div>  

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
                            $('#btn_print').hide();
                            $('#btn_email').hide();
                            function gridview() {
                                $('#GridView').show();
                                $('#ListView').hide();
                                $('#btn_export').hide();
                                $('#btn_email').hide();
                                $('#btn_print').hide();
                            }
                            function listview() {
                                $('#GridView').hide();
                                $('#ListView').show();
                                $('#btn_export').show();
                                $('#btn_print').show();
                                $('#btn_email').show();
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