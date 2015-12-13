  

<div class="container" style="">
    <!--<div--> 
    <div class="row" style="margin-top: 70px;">
        <div class="col-lg-12">
            <div class="panel panel-default siku">
                <a href="<?php echo base_url() . 'index.php/Admin/tambah_admin' ?>" class="btn btn-info siku" role="button" style="margin: 10px;">Tambah Admin</a>
            </div>
        </div>
        <div class="col-lg-12" style="margin-top: -10px;">
            <?php if ($status_admin != "") { ?>
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> <?php echo $status_admin ?>
                </div>
            <?php } ?>
            <div class="panel panel-info siku">
                <div class="panel-heading siku">
                    <h3 class="panel-title">Daftar Admin</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-12" style="background-color: white;">
                        <table class='table table-bordered table-hover table-responsive' id="gUser">
                            <thead>
                                <tr style='background-color: #999'>
                                    <th>#</th>
                                    <th>Cabang</th>
                                    <th>Nama Admin</th>
                                    <th>Email</th>
                                    <th>Foto</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $nomor = 1; ?>
                                <?php foreach ($admins as $admin) : ?> 

                                    <?php echo "<tr>"; ?>
                                    <?php echo "<td>" . $nomor++ . "</td>"; ?>
                                    <?php echo "<td>" . $admin->nama . "</td>"; ?>
                                    <?php echo "<td>" . $admin->nama . "</td>"; ?>
                                    <?php echo "<td>" . $admin->email . "</td>"; ?>
                                    <?php echo "<td align='center'><img class='thumbnail' style='width: 200px; height: 200px;' src='" . base_url() . 'uploads/' . $img_admins[$admin->IDAdmin] . "'/> </td>"; ?>
                                    <?php echo "<td align='center'>"; ?>
                                    <?php echo "<a href='" . base_url() . "index.php/admin/edit_admin/" . $admin->IDAdmin . "' class='btn btn-primary btn-sm siku'><i class='fa fa-pencil'></i></a>"; ?>
                                    <?php if ($admin->level == 3) { ?>
                                    <a href='<?php echo base_url() . "index.php/admin/hapus_admin/" . $admin->IDAdmin; ?>' class='btn btn-danger btn-sm siku inputCheck'><i class='fa fa-trash'></i></a>
                                    <?php
                                } else {
                                    ?>
                                    <a id='' href='<?php echo base_url() . "index.php/admin/hapus_admin/" . $admin->IDAdmin; ?>' class='btn btn-danger btn-sm siku disabled inputCheck' title='Tidak Dapat Dihapus!'><i class='fa fa-trash'></i></a>
                                        <?php
                                    }
                                    ?>
                                    <?php echo "</td>"; ?>
                                    <?php echo "</tr>"; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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
<script src="<?php echo base_url(); ?>alertify/alertify.min.js"></script>
<script>
    var canDismiss = false;
    var notification = alertify.error('<i class="fa fa-warning"></i> Mohon Tambahkan Data Lokasi Untuk Admin yang Baru Di Tambahkan!');
    notification.ondismiss = function () {
        return canDismiss;
    };

    $("#gUser .inputCheck").click(function (event) {
        event.preventDefault();
        var a = this.href;
        alertify.confirm('Hapus Barang yang diPilih?', function (e) {
            if (e) {
                window.location.assign(a);
            } else {
                //after clicking Cancel
            }
        });
    });

    function delete_admin(btn) {
        document.preventDefault();
        alert();
    }
</script>
</body>
</html>