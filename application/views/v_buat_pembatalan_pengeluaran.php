<div class="container" style="margin-top: 80px; height: 100%; padding: 0px; margin-bottom: 50px;">    
    <div class="row" style="">
        <div class="col-lg-12">
            <h1 class="page-header" style="margin-top: 0px;">Buat Pembatalan Pengeluaran</h1>
            <ol class="breadcrumb" style="background-color: white; margin-top: 00px;">
                <li><a href="<?php echo base_url(); ?>index.php/Laporan/laporan_pembatalan_pengeluaran"><i class="fa fa-home"></i> Daftar Pembatalan Pengeluaran </a></li>
                <li><a href="">Buat Pembatalan Pengeluaran</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-success siku">
                <div class="panel-heading siku">
                    <h3 class="panel-title">Tambah Data Pembatalan</h3>
                </div>
                <div class="panel-body siku">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php if ($status != "") {
                                ?>
                                <div class="alert alert-info"><i class="glyphicon glyphicon-info-sign"></i> <?php echo $status ?></div>
                            <?php }
                            ?>

                            <form method="post" action="<?php echo current_url(); ?>" accept-charset="utf-8" class="form-horizontal" id="form_setor">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Tanggal : </label>
                                    <div class="col-lg-9">
                                        <input class="form-control siku" type="text" name="tanggal" id="datepicker" placeholder="Pilih Tanggal">
                                        <input type="hidden" id="base_url" name="base_url" value="<?php echo base_url() ?>">
                                        <?php if (form_error("tanggal") != "") : ?>
                                            <span style="color: red;"><?php echo form_error("tanggal"); ?></span>                                  
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Jenis : </label>
                                    <div class="col-lg-9">
                                        <label class="radio-inline">
                                            <input type="radio" name="tipe" id="inlineRadio1" value="1" onclick="pengeluaran()" checked=""> Pengeluaran
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipe" id="inlineRadio2" value="2" onclick="gaji()"> Komisi & Gaji
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-3">No Laporan : </label>
                                    <div class="col-lg-9">
                                        <button id="btn_pengeluaran" type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary siku"><i class="fa fa-money"></i> Ambil Laporan Pengeluaran</button>
                                        <button id="btn_gaji" type="button" data-toggle="modal" data-target="#myModal2" class="btn btn-primary siku"><i class="fa fa-user"></i> Ambil Laporan Gaji & Komisi</button>
                                        <?php if (form_error("IDPengeluaran") != "") : ?>
                                            <span style="color: red;"><?php echo form_error("IDPengeluaran"); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Tanggal Laporan : </label>
                                    <div class="col-lg-9">
                                        <input class="form-control siku" type="text" name="tanggal_pengeluaran" id="tanggal_pengeluaran" placeholder="" disabled="" value="">
                                        <input type="hidden" id="base_url" value="<?php echo base_url() ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Total Laporan : </label>
                                    <div class="col-lg-9">
                                        <input type="hidden" id="IDPengeluaran" value="" name="IDPengeluaran"/>
                                        <input type="text" id="total_pengeluaran" value="" name="total_pengeluaran" class="form-control siku" disabled=""/>
                                        <!--<textarea class="form-control siku" rows="3" name="keterangan"></textarea>-->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Keterangan : </label>
                                    <div class="col-lg-9">
                                        <textarea class="form-control siku" rows="3" name="keterangan"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-3"></label>
                                    <div class="col-lg-9">
                                        <button name="btn_submit" value="btn_submit" type="submit" class="btn btn-info siku" id="bayar_button">Buat Pembatalan Pengeluaran</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">                            
                    <table class='table table-striped table-hover' id="list_laporan">
                        <thead>
                            <tr>
                                <!--<th style="display: none;">ID</th>-->
                                <th>Tanggal</th>
                                <th>Admin <?php
                                    if ($this->session->userdata("Level") == 0) {
                                        echo "Cabang";
                                    }
                                    ?></th>
                                <th>No Laporan</th>
                                <th>Total Pengeluaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($laporans as $laporan):
                                ?>
                                <tr>
                                    <td id="tanggal<?php echo $laporan->idlaporan ?>"><?php echo strftime("%d-%m-%Y", strtotime($laporan->tanggal)); ?></td>
                                    <td><?php echo $laporan->username; ?>
                                        <input type="hidden" id="idnota<?php echo $laporan->idlaporan; ?>" value="<?php echo $laporan->idlaporan; ?>" name="idnota<?php echo $no; ?>"/>
                                    </td>
                                    <td><?php echo $laporan->KodePengeluaran; ?></td>
                                    <td id="total<?php echo $laporan->idlaporan ?>">Rp <?php echo number_format($laporan->totalPengeluaran, 0, ",", ".") ?>.-
                                    </td>
                                    <td style="width: 50px; text-align: center;">
                                        <button type="button" id="" onclick="pilih_nota(<?php echo $laporan->idlaporan ?>)" class="btn btn-sm btn-primary siku"><i class=""></i> Pilih</button>
                                    </td>
                                </tr>
                                <?php
                                $no++;
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">                            
                    <table class='table table-striped table-hover' id="list_laporan_2">
                        <thead>
                            <tr>
                                <!--<th style="display: none;">ID</th>-->
                                <th>Tanggal</th>
                                <th>Admin <?php
                                    if ($this->session->userdata("Level") == 0) {
                                        echo "Cabang";
                                    }
                                    ?></th>
                                <th>No Laporan</th>
                                <th>Total Penggajian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($laporan_penggajians as $laporan):
                                ?>
                                <tr>
                                    <td id="tanggalgaji<?php echo $laporan->idlaporan ?>"><?php echo strftime("%d-%m-%Y", strtotime($laporan->tanggal)); ?></td>
                                    <td><?php echo $laporan->username; ?>
                                        <input type="hidden" id="idgaji<?php echo $laporan->idlaporan; ?>" value="<?php echo $laporan->idlaporan; ?>" name="idgaji<?php echo $no; ?>"/>
                                    </td>
                                    <td><?php echo $laporan->KodePenggajian; ?></td>
                                    <td id="totalgaji<?php echo $laporan->idlaporan ?>">Rp <?php echo number_format($laporan->totalPenggajian, 0, ",", ".") ?>.-
                                    </td>
                                    <td style="width: 50px; text-align: center;">
                                        <button type="button" id="" onclick="pilih_gaji(<?php echo $laporan->idlaporan ?>)" class="btn btn-sm btn-primary siku"><i class=""></i> Pilih</button>
                                    </td>
                                </tr>
                                <?php
                                $no++;
                            endforeach;
                            ?>
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
<script src="<?php echo base_url() ?>bootstrap/js/ajaxLaporan.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>Datatable/js/jquery.dataTables.js"></script>
<script>
                                            $("#datepicker").datepicker({
                                                inline: true,
                                                dateFormat: "dd-mm-yy"
                                            });

                                            $("#datatable").dataTable();

                                            $("#list_laporan").dataTable();
                                            $("#list_laporan_2").dataTable();
                                            

                                            $(document).ready(function () {
                                                $("#lokasi").val('');
                                                $("#salesnya_admin").val('');
                                                $("#gaji_sales").val('');
                                                $("#nama_produk").val('');
                                                $(".kas_keluar").val('');
                                                $("#bayar_gaji").hide();
                                            });

//                                            $("#inputCheck").click(function () {
//                                                event.preventDefault();
//                                                var r = confirm("Apakah Data yang telah Di Masukan Benar?");
//                                                if (r == true) {
//                                                    var a = $(this).attr("href");
//                                                    window.location.assign(a);
//                                                }
//                                            });

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
                                                        var a = $("#inputCheck").attr("href");
                                                        window.location.assign(a);
                                                    } else {
                                                        //after clicking Cancel
                                                    }
                                                });
                                            });

                                            $("#bayar_button").click(function (event) {
                                                event.preventDefault();
                                                alertify.confirm('Apakah Data yang telah Di Masukan Benar?', function (e) {
                                                    if (e) {
                                                        $("#form_setor").submit();
                                                    } else {
                                                        //after clicking Cancel
                                                    }
                                                });
                                            });

                                            function pilih_nota(nota) {
                                                var tgl_nota = $("#tanggal" + nota).html();
                                                var total_nota = $("#total" + nota).html();
                                                var IDNota = $("#idnota" + nota).val();

                                                $('#myModal').modal('hide');
                                                $("#tanggal_pengeluaran").val(tgl_nota);
                                                $("#total_pengeluaran").val(total_nota);
                                                $("#IDPengeluaran").val(IDNota);
                                            }
                                            
                                            function pilih_gaji(nota) {
                                                var tgl_nota = $("#tanggalgaji" + nota).html();
                                                var total_nota = $("#totalgaji" + nota).html();
                                                var IDNota = $("#idgaji" + nota).val();

                                                $('#myModal2').modal('hide');
                                                $("#tanggal_pengeluaran").val(tgl_nota);
                                                $("#total_pengeluaran").val(total_nota);
                                                $("#IDPengeluaran").val(IDNota);
                                            }
                                            
                                            $("#btn_gaji").attr("style", "display: none;");

                                            function pengeluaran() {
                                                $("#btn_pengeluaran").attr("style", "display: block;");
                                                $("#btn_gaji").attr("style", "display: none;");
                                                $("#tanggal_pengeluaran").val("");
                                                $("#total_pengeluaran").val("");
                                                $("#IDPengeluaran").val("");
                                            }

                                            function gaji() {
                                                $("#btn_pengeluaran").attr("style", "display: none;");
                                                $("#btn_gaji").attr("style", "display: block;");
                                                $("#tanggal_pengeluaran").val("");
                                                $("#total_pengeluaran").val("");
                                                $("#IDPengeluaran").val("");
                                            }
</script>