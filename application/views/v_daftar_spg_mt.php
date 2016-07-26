<?php date_default_timezone_set("Asia/Jakarta") ?>
<style>
    td:first-child{
        text-align: right;
    }
</style>  
<div class="container" style="">
    <!--<div--> 
    <div class="row" style="margin-top: 70px;">
        <div class="col-lg-12">
            <div class="panel panel-default siku">
                <a href="<?php echo base_url() . 'index.php/toko/tambah_spg_mt' ?>" class="btn btn-info siku" role="button" style="margin: 10px;">Tambah SPG</a>
            </div>
        </div>
        <div class="col-lg-12" style="margin-top: -10px;">
            <div class="panel panel-info siku">
                <div class="panel-heading siku">
                    <h3 class="panel-title">Daftar Sales</h3>
                </div>
                <div class="panel-body">
                    <?php
                    if ($this->session->userdata("Level") == 0) { ?>                       
                        <form style="background-color: white; padding: 15px 10px 10px 5px;; margin-bottom: 10px;" class="form-horizontal" action="<?php echo current_url() ?>" method="POST">
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
                            <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-2">
                                    <button type="submit" name='btn_submit' value='btn_pilih' class="btn btn-primary siku">&nbsp;&nbsp;Pilih&nbsp;&nbsp;</button>
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                    ?>
                    <div class="col-md-12" style="background-color: white;">
                        <?php foreach ($spg_mt as $sales): ?>
                        <!-- looping -->
                        <div class="col-md-3">
                            <div class="panel panel-success siku">
<!--                                <div class="panel-heading">
                                    <h5 style="font-size: x-large"><strong><?php echo $sales->nama ?></strong></h5>
                                </div>-->
                                <div class="panel-body">
                                    <div style="text-align: center;">
                                        <img src="<?php echo base_url() ?>uploads/<?php echo $sales->foto ?>" alt="<?php echo $sales->foto ?>" width="100%" >
                                    </div>
                                    <br>
                                    <table>
                                        <tr>
                                            <td class="col-sm-6 text-right">Nama :</td>
                                            <td><?php echo $sales->nama ?></td>
                                        </tr>
                                        <tr>
                                            <td class="col-sm-6 text-right">Gaji SPG :</td>
                                            <td><?php echo $sales->gaji ?></td>
                                        </tr>
                                    </table>
                                    <br>
                                    <div style="text-align: center">
                                        <a href='<?php echo base_url() ?>index.php/toko/tambah_spg_mt/<?php echo $sales->IDSalesMT ?>' class='btn btn-primary btn-sm siku'><i class='fa fa-pencil'></i></a>
                                        <a href='<?php echo base_url() ?>index.php/toko/delete_spg/<?php echo $sales->IDSalesMT ?>' class='btn btn-danger btn-sm siku' onclick="return confirm('Are you sure you want to delete this item?');"><i class='fa fa-trash'></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- end looping -->
                        <?php endforeach; ?>
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

</body>
</html>