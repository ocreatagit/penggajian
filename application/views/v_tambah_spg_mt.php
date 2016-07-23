<div class="container" style="margin-top: 80px; height: 100%;; background-color: white; padding: 0px;">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-success siku">
                <div class="panel-heading siku">
                    <h3 class="panel-title">Tambah Sales Baru</h3>
                </div>
                <div class="panel-body siku">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php if ($status != "") { ?>
                                <div class="alert alert-info"><i class="glyphicon glyphicon-info-sign"></i> <?php echo $status ?></div>
                            <?php } ?>

                            <form class="form-vertical" method="post" action="<?php echo current_url(); ?>" accept-charset="utf-8" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control siku" name="nama" placeholder="Masukkan Nama" value="<?php echo $data_sales == NULL ? set_value('nama') : set_value('nama', $data_sales[0]->nama) ?>">
                                </div>                                
                                <div class="form-group">
                                    <label>Nomor Telepon</label>
                                    <input type="text" class="form-control siku" name="noHP" placeholder="Masukkan Nomor Telepon" value="<?php echo $data_sales == NULL ? set_value('notelp') : set_value('notelp', $data_sales[0]->noHP) ?>">
                                </div>                                
                                <div class="form-group">
                                    <label>Tempat Lahir</label>
                                    <input type="text" class="form-control siku" name="tempatLahir" placeholder="Masukkan Tempat Lahir" value="<?php echo $data_sales == NULL ? set_value('tempatLahir') : set_value('tempatLahir', $data_sales[0]->tempatLahir) ?>">
                                </div>                                
                                <label>Tanggal Lahir</label>
                                <div class="input-group">                                    
                                    <input class="form-control siku" type="text" name="tanggalLahir" id="datepicker" placeholder="Pilih Tanggal" value="<?php echo $data_sales != NULL ? date('d/m/Y', strtotime($data_sales[0]->tanggalLahir)) : '1/1/1990' ?>">
                                    <span class="input-group-addon siku"><i class="glyphicon glyphicon-calendar" aria-hidden="true"></i></span>
                                </div>                                
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <input type="text" class="form-control siku" name="alamat" placeholder="Masukkan Alamat" value="<?php echo $data_sales == NULL ? set_value('alamat') : set_value('alamat', $data_sales[0]->tempatLahir) ?>">
                                </div>                         
                                <div class="form-group">                                    
                                    <label>Foto Sales</label>
                                    <input type="file" name="foto_sales" id="exampleInputFile" accept="">
                                    <input type="hidden" name="fotolama" value="<?php
                                    if ($data_sales != null) {
                                        echo $data_sales[0]->foto;
                                    } else {
                                        echo 'kosong';
                                    }
                                    ?>">
                                </div>
                                <div class="form-group">
                                    <label>Gaji</label>
                                    <input type="number" name="gaji" min="0" class="form-control" value="<?php if($data_sales != null) echo $data_sales[0]->gaji ?>">                                    
                                </div>
                                <div class="form-group">
                                    <label>Keterangan lain</label>
                                    <textarea name="keterangan" class="form-control"></textarea>                                    
                                </div>
                                
                                <br>
                                <a href="<?php echo base_url() ?>index.php/Toko/spg_mt" class="btn btn-default siku"><i class='fa fa-backward'></i> Kembali</a>
                                <button name="btn_submit" value="btn_submit" type="submit" class="btn btn-info siku" id="selesai">Selesai</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>bootstrap/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>jquery-ui/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>bootstrap/js/ajaxLaporan.js"></script>
<style type="text/css">
    .ui-datepicker-year, .ui-datepicker-month{
        color: black;
    }
</style>
<script>
    $("#datepicker").datepicker({
        inline: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        changeMonth: true
    });
//    $("#datepicker").datepicker("setDate", "<?php // echo $data_sales != NULL ? date('d/m/Y', strtotime($data_sales[0]->tanggalLahir)) : '1/1/1990' ?>");
</script>

</body>
</html>
