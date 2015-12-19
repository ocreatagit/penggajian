<div class="container" style="margin-top: 80px; height: 100%; padding: 0px; margin-bottom: 50px;">    
    <div class="row" style="">
        <div class="col-lg-6">            
            <h1 class="page-header" style="margin-top: 0px;">Balancing Saldo</h1>
        </div>
        <!--        <div class="col-lg-6" style="background-color: whitesmoke">
                    <h1 class="" id="saldo" style="margin-top: 0px;">Saldo : Rp.<?php echo number_format($saldo, 0, ",", ".") ?>,-</h1>
                </div>-->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb" style="background-color: white; margin-top: 0px;">
                <li><a href="<?php echo base_url(); ?>index.php/laporan/balancing_saldo"><i class="fa fa-home"></i> Tambah Balancing Saldo</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-success siku">
                <div class="panel-heading siku">
                    <h3 class="panel-title">Tambah Balancing Saldo</h3>
                </div>
                <div class="panel-body siku">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php if ($status != "") {
                                ?>
                                <div class="alert alert-info"><i class="glyphicon glyphicon-info-sign"></i> <?php echo $status ?></div>
                            <?php }
                            ?>
                            <form id="form_s" method="post" action="<?php echo current_url(); ?>" accept-charset="utf-8" class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-lg-2">Jenis : </label>
                                    <div class="col-lg-3">
                                        <label class="radio-inline">
                                            <input type="radio" name="jenis" id="inlineRadio1" value="0" checked=""> Tambah
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="jenis" id="inlineRadio2" value="1"> Kurangi
                                        </label>
                                    </div>
                                    <label class="col-lg-2" style="margin-top: 10px;">Saldo Saat Ini : </label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-2">Kas : </label>
                                    <div class="col-lg-3">
                                        <select class="form-control siku" name="jenis_kas" id="kas_1" onchange="change_kas('kas_1')">
                                            <option value="2" selected="">Kas Admin Kantor</option>
                                            <option value="3">Kas Bank</option>
                                        </select> 
                                    </div>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control siku" value="<?php echo "Rp. " . number_format($saldo, 0, ',', '.') . " ,-" ?>" id="saldo_1" disabled=""/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-2">Nominal : </label>
                                    <div class="col-lg-5">
                                        <input type="hidden" name="base_url" value="63hc83uiw0290ei829"/>
                                        <input type="hidden" id="base_url" value="<?php echo base_url() ?>" name="url"/>
                                        <input class="form-control siku" type="number" min="1" name="nominal" value="<?php echo set_value('nominal') ?>" id="nominal"/>
                                        <?php if (form_error("nominal") != "") : ?>
                                            <span style="color: red;"><?php echo form_error("nominal"); ?></span>                                  
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-2"></label>
                                    <div class="col-lg-5">
                                        <button name="btn_submit" value="btn_submit" type="submit" class="btn btn-info siku" id="inputCheck">Buat Balancing Saldo</button>
                                    </div>
                                </div>
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
<script type="text/javascript" src="<?php echo base_url() ?>Datatable/js/jquery.dataTables.js"></script>
<script>
                                            $("#datepicker").datepicker({
                                                inline: true,
                                                dateFormat: "dd-mm-yy"
                                            });
                                            
                                            $("#datatable").dataTable();
                                            
                                            $(document).ready(function () {
                                                $("#lokasi").val('');
                                                $("#salesnya_admin").val('');
                                                $("#gaji_sales").val('');
                                                $("#nama_produk").val('');
                                                $(".kas_keluar").val('');
                                                $("#bayar_gaji").hide();
                                            });
                                            
//                            $("#inputCheck").click(function() {
//                                event.preventDefault();
//                                var r = confirm("Apakah Data yang telah Di Masukan Benar?");
//                                if (r == true) {
//                                    var a = $(this).attr("href");
//                                    window.location.assign(a);
//                                }
//                            });
                                            
</script>
<script src="<?php echo base_url(); ?>alertify/alertify.min.js"></script>
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
                                                    title: 'Konfirmasi',
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
                                            
                                            $("#inputCheck").click(function (event) {
                                                event.preventDefault();
                                                alertify.confirm('Apakah Data yang telah Di Masukan Benar?', function (e) {
                                                    if (e) {
                                                        $("#form_s").submit();
                                                    } else {
                                                        //after clicking Cancel
                                                    }
                                                });
                                            });
</script>