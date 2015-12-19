var base_url = $("#base_url").val();
var current_url = $("#current_url").val();
var saldo = $("#saldo").val();
var tampSales;
var tampBarang;
var tampJumlah;
var tampPendapatan;
var keterangan_kas_keluar;
var no = 1;
var no_kas_keluar = 1;
arrayKasKeluar = new Array();
arrayPendapatan = new Array();

var ErrorPenjualan = 0;

/*
 * 
 * @param {int} Tampung Penjualan dan Pengeluaran 
 */
var totalPengeluaran = 0;
var totalKomisi = 0;
var totalPenjualan = 0;
indexPendapatan = 1;
indexKasKeluar = 1;

$(document).ready(function () {
    /* Validation || Penjualan */
//    $("#text_saldo").html("Saldo: " + saldo);

    $('#salesnya_admin').change(function () {
        $("#sales_error").html("");
    });
    $('#nama_produk').change(function () {
        $("#produk_error").html("");
    });
    $("#jumlah").keyup(function () {
        $("#jumlah_error").html("");
    });
    $("#pendapatan_SPG").keyup(function () {
        $("#penjualan_error").html("");
    });
    $("#lokasi").change(function () {
        $("#lokasi_error").html("");
    });

    /* Cek Penggajian */

    /* Cek Penggajian */

});


/*----------------------*/
/* PASSING-PASSING DATA */
/*----------------------*/
function passing_data(sales, barangs, jumlah, pendapatan, kodesales, kodebarang) {
    if ($("#datepicker").val()) {
        if ($("#lokasi").val()) {
            if (kodesales) {
                if (kodebarang) {
                    if (jumlah) {
                        if (isNaN(jumlah) == false) { /* Numeric */
                            if (jumlah > 0) {
                                if (pendapatan) {
                                    if (isNaN(pendapatan) == false) { /* Numeric */
                                        if (pendapatan > 0) {
                                            ErrorPenjualan = 0;
                                            /* Here we go, no error(s) anymore */
                                        } else {
                                            ErrorPenjualan = 1;
                                            $("#penjualan_error").html("Total jual harus lebih besar dari 0!");
                                        }
                                    } else {
                                        ErrorPenjualan = 1;
                                        $("#penjualan_error").html("Total jual harus berupa angka!");
                                    }
                                } else {
                                    ErrorPenjualan = 1;
                                    $("#penjualan_error").html("Total jual tidak boleh kosong!");
                                }
                            } else {
                                ErrorPenjualan = 1;
                                $("#jumlah_error").html("Jumlah harus lebih besar dari 0!");
                            }
                        } else {
                            /* Not Numeric*/
                            ErrorPenjualan = 1;
                            $("#jumlah_error").html("Jumlah barang harus berupa angka!");
                        }
                    } else {
                        ErrorPenjualan = 1;
                        $("#jumlah_error").html("Jumlah tidak boleh kosong!");
                    }
                } else {
                    ErrorPenjualan = 1;
                    $("#produk_error").html("Produk tidak boleh kosong!");
                }
            } else {
                ErrorPenjualan = 1;
                $("#sales_error").html("Sales tidak boleh kosong!");
            }
        } else {
            ErrorPenjualan = 1;
            $("#lokasi_error").html("Lokasi tidak boleh kosong!");
        }
    } else {
        ErrorPenjualan = 1;
        $("#tanggal_error").html("Tanggal tidak boleh kosong!");
    }

    if (ErrorPenjualan == 0) {
        lokasi = ($('#lokasi').find('option:selected').text());
        arrayPendapatan[indexPendapatan] = new Array(6);
        arrayPendapatan[indexPendapatan][0] = kodesales;
        arrayPendapatan[indexPendapatan][1] = kodebarang;
        arrayPendapatan[indexPendapatan][2] = jumlah;
        arrayPendapatan[indexPendapatan][3] = pendapatan;
        arrayPendapatan[indexPendapatan][4] = $("#lokasi").val();
        tampSales = sales;
        tampBarang = barangs;
        tampJumlah = jumlah;
        tampPendapatan = pendapatan;

        $.ajax({
            url: base_url + "index.php/Laporan/get_komisi",
            type: 'POST',
            data: {
                "IDSales": kodesales,
                "IDBarang": kodebarang
            },
            success: function (data, textStatus, jqXHR) {
                arrayPendapatan[indexPendapatan][5] = parseInt(tampJumlah * data);
                var tamp = "<tr id='baris" + no + "'>" +
                        "<td>" + lokasi + "</td>" +
                        "<td>" + tampSales + "</td>" +
                        "<td>" + tampBarang + "</td>" +
                        "<td>" + tampJumlah + "</td>" +
                        "<td>" + tampPendapatan + "</td>" +
                        "<td id='komisi" + no + "'>" + (arrayPendapatan[indexPendapatan][5]) + "</td>" +
                        "<td><button type='button' onclick='deleteRow(" + no + ")' class='btn btn-danger siku'>X</td>" +
                        "</tr>";
                $("#baris").prepend(tamp);
                indexPendapatan++;
                no++;

                $("#lokasi").val('');
                $("#salesnya_admin").val('');
                $("#nama_produk").val('');
                $("#jumlah").val('');
                $("#pendapatan_SPG").val('');
            }
        });
    }
}

function kas_keluar() {
    arrayKasKeluar[indexKasKeluar] = new Array(2);

    if (keterangan_kas_keluar == "lain-lain") {
        arrayKasKeluar[indexKasKeluar][0] = $("#keterangan_kas").val();
        arrayKasKeluar[indexKasKeluar][1] = $("#nominal").val();
    } else if (keterangan_kas_keluar == "Gaji") {
        arrayKasKeluar[indexKasKeluar] = new Array(3);
        arrayKasKeluar[indexKasKeluar][0] = keterangan_kas_keluar + " " + ($('#gaji_sales').find('option:selected').text());
        arrayKasKeluar[indexKasKeluar][1] = $("#nominal_gaji").val();
        arrayKasKeluar[indexKasKeluar][2] = $('#gaji_sales').val();
    } else {
        arrayKasKeluar[indexKasKeluar][0] = keterangan_kas_keluar;
        arrayKasKeluar[indexKasKeluar][1] = $("#nominal").val();
    }
    console.log(arrayKasKeluar[indexKasKeluar][0]);
    console.log(arrayKasKeluar[indexKasKeluar][1]);


    var tamp = "<tr id='baris_keluar" + no_kas_keluar + "'>" +
            "<td>" + arrayKasKeluar[indexKasKeluar][0] + "</td>" +
            "<td>" + arrayKasKeluar[indexKasKeluar][1] + "</td>" +
            "<td><button type='button' onclick='deleteRowKasKeluar(" + no_kas_keluar + ")' class='btn btn-danger siku'>X</td>" +
            "</tr>";

    $("#baris_kas_keluar").append(tamp);
    no_kas_keluar++;
    indexKasKeluar++;
}

function selesai_penjualan() {
    var TanggalBuatLaporan = $("#datepicker").val();

    if (ErrorPenjualan == 0) {
        $.ajax({
            url: base_url + "index.php/Laporan/insert_pendapatan",
            type: "POST",
            data: {
                "tanggal": TanggalBuatLaporan,
                "keterangan": $("#keterangan_lainnya").val(),
                "IDCabang": $("#idcabang").val()
            },
            success: function (data, textStatus, jqXHR) {
                var IDPenjualan = data;
                for (var i = 1; i <= arrayPendapatan.length; i++) {
                    if (arrayPendapatan[i][0] == "0") {

                    } else { /* 0 dsini sudah dihapus index ketika delete table, dan diberi 0 artinya sudah tidak ada ditable.*/
                        var komisi = arrayPendapatan[i][5];
                        totalKomisi += parseInt(arrayPendapatan[i][5]);
                        totalPenjualan += parseInt(arrayPendapatan[i][3]);

                        localStorage.setItem("total_komisi", totalKomisi);
                        localStorage.setItem("total_penjualan", totalPenjualan);

                        $.ajax({
                            url: base_url + "index.php/Laporan/insert_detail_pendapatan",
                            type: "POST",
                            data: {
                                "IDPenjualan": IDPenjualan,
                                "IDSales": arrayPendapatan[i][0],
                                "IDBarang": arrayPendapatan[i][1],
                                "IDLokasi": arrayPendapatan[i][4],
                                "jumlah": arrayPendapatan[i][2],
                                "pendapatan": arrayPendapatan[i][3]
                            },
                            success: function (data, textStatus, jqXHR) {
                                var IDSales = (data);
                                /*Tambah Saldo Gaji Sales */
                                $.ajax({
                                    url: base_url + "index.php/Laporan/tambah_gaji_dan_komisi_sales",
                                    type: "POST",
                                    data: {
                                        "IDSalez": IDSales,
                                        "Komisi": komisi,
                                        "Tanggal": TanggalBuatLaporan,
                                        "IDPenjualan": IDPenjualan
                                    }, success: function (data, textStatus, jqXHR) {
//                                    console.log(data);
                                        location.assign(base_url + "index.php/Laporan/HarianPengeluaran/" + IDPenjualan);
                                    }, error: function (jqXHR, textStatus, errorThrown) {
//                                        alert(jqXHR);
                                    }
                                });
                            }, error: function (jqXHR, textStatus, errorThrown) {
//                                alert(jqXHR);
                            }
                        });
                    }
                }
            }
        });
    } else {
        alert("Masih ada data yang belum benar!");
    }

}

function laporan_selesai() {
    var IDPenjualan = $("#IDPenjualan").val();

//    alert("---other");
//    alert(arrayKasKeluar.length);
    for (var i = 1; i <= arrayKasKeluar.length; i++) {

        if (arrayKasKeluar[i][0] != "0") {
            totalPengeluaran += parseInt(arrayKasKeluar[i][1]);
//            localStorage.setItem("total_pengeluaran", totalPengeluaran);
//            console.log("Total Penjualan: " + localStorage.getItem("total_penjualan"));
//            console.log("Total Komisi: " + localStorage.getItem("total_komisi"));
//            console.log("Total Pengeluaran: " + localStorage.getItem("total_pengeluaran"));
            var tamp = arrayKasKeluar[i][0] + "";
            if (tamp.substr(0, 4) == "Gaji") {
                $.ajax({
                    url: base_url + "index.php/Laporan/insert_bayar_gaji",
                    type: 'POST',
                    data: {
                        "IDPenjualan": IDPenjualan,
                        "keterangan": arrayKasKeluar[i][0],
                        "nominal": arrayKasKeluar[i][1],
                        "IDSales": arrayKasKeluar[i][2]
                    },
                    success: function (data, textStatus, jqXHR) {
//                        alert("gaji");
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert(JSON.stringify(jqXHR));
                    }
                });
            } else {
                /* Makan, Tol, Parkir, Bensin , lain2() */
                $.ajax({
                    url: base_url + "index.php/Laporan/insert_pengeluaran",
                    type: 'POST',
                    data: {
                        "IDPenjualan": IDPenjualan,
                        "keterangan": arrayKasKeluar[i][0],
                        "nominal": arrayKasKeluar[i][1]
                    },
                    success: function (data, textStatus, jqXHR) {
//                        alert("bensin");
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert(JSON.stringify(jqXHR));
                    }
                });
            }


            /*alert sukses*/
//            $.ajax({
//                url: "",
//                data: {},
//                success: function(data, textStatus, jqXHR) {
//
//                }
//            });
//            if (i == arrayKasKeluar.length) {
//                alert(i);
//            }

//            location.assign(base_url + "index.php/Laporan/cetaklaporan/" + IDPenjualan);
        }
    }
//    alert("other");
//    hitung();

//    $("#add_success").show();


//    alert(totalPengeluaran);
}
/*----------------------*/

function print_laporan() {
    var IDPenjualan = $("#IDPenjualan").val();
    location.assign(base_url + "index.php/Laporan/cetaklaporan/" + IDPenjualan);
}

/**/
/* SISTEMATIK */
/**/
function deleteRow(rowid) {
    var row = document.getElementById("baris" + rowid);
    arrayPendapatan[rowid][0] = "0";
    row.parentNode.removeChild(row);
}
function deleteRowKasKeluar(rowid) {
    var row = document.getElementById("baris_keluar" + rowid);
    arrayKasKeluar[rowid][0] = "0";
    row.parentNode.removeChild(row);
}
function get_current_gaji(IDSelect) {
    var IDSales = $("#" + IDSelect).val();
    $.ajax({
        url: base_url + "index.php/Laporan/get_current_gaji_sales",
        type: "POST",
        data: {
            "IDSaless": IDSales
        },
        success: function (data, textStatus, jqXHR) {
            $("#gaji_label").html("Rp. " + data);
        }
    });
}
function get_komisi() {
    var nama_sales = ($('#sales').find('option:selected').text());
    if (document.getElementById("sales").value == 0) {
        $("#komisi").val(0);
        $("#komisi_hidden").val(0);
        $("#nama_sales").val(nama_sales);
        $("#bayar").val(0);
    } else {
        $.ajax({
            url: base_url + "index.php/komisi/get_komisi_sales",
            type: 'POST',
            data: {
                "IDSales": document.getElementById("sales").value
            },
            success: function (data, textStatus, jqXHR) {
                $("#komisi").val(data);
                $("#komisi_hidden").val(data);
                $("#nama_sales").val(nama_sales);
                $("#bayar").val(data);
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(JSON.stringify(jqXHR));
            }
        });
    }
}

function get_gaji_sales() {
    var nama_sales = ($('#sales').find('option:selected').text());
    if (nama_sales == "--- Pilih Sales ---") {
        $("#gaji").val("---");
        $("#bayar").val("---");
    } else {
        $.ajax({
            url: base_url + "index.php/laporan/get_gaji_sales",
            type: 'POST',
            data: {
                "IDSaless": document.getElementById("sales").value
            },
            success: function (data, textStatus, jqXHR) {
                $("#gaji").val(data);
                $("#gaji_hidden").val(data);
                $("#bayar").val(data);
                $("#nama_sales").val(nama_sales);
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(JSON.stringify(jqXHR));
            }
        });
    }
}

function edit_lokasi() {
    var IDLokasi = document.getElementById("lokasi").value;
    if (IDLokasi) {
        $.ajax({
            url: base_url + "index.php/Laporan/get_detail_lokasi",
            type: 'POST',
            data: {
                "IDLokasi": IDLokasi
            }, success: function (data, textStatus, jqXHR) {
                var hasil = JSON.parse(data);
                $("#IDLokasi_edit").val(hasil.id_lokasi + "");
                $("#daerah_edit").val(hasil.desa + "");
                $("#kecamatan_edit").val(hasil.kecamatan + "");
                $("#wilayah_edit").val(hasil.wilayah + "");
                $("#kabupaten_edit").val(hasil.kabupaten + "");
                $("#provinsi_edit").val(hasil.provinsi);
                $("#btn_edit").attr("class", "btn btn-primary");
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(JSON.stringify(jqXHR));
            }
        });
    } else {
        $("#edit_dissmis_button").click();
        $("#btn_edit").attr("class", "btn btn-primary disabled");
        alert("No Data Selected!");
    }
}

function refreshEditPage() {
    alert("Refreshing Page... (^.^)v");
    var deferred = new $.Deferred();
    // now, delay the resolution of the deferred:
    setTimeout(function () {
        window.location.href = current_url;
    }, 1);
    return deferred.promise();
}

function tempAlert(msg, duration)
{
    var el = document.createElement("div");
    el.setAttribute("style", "background-color: #31708F;position: absolute;top:0; bottom: 0; left: 0; right: 0; margin: auto;padding: 20px; height: 75px; width: 400px; color: white; font-size: 18px; border-radius: 6px;");
    el.innerHTML = msg;
    setTimeout(function () {
        el.parentNode.removeChild(el);
        window.location.href = current_url;
    }, duration);
    document.body.appendChild(el);
}

function simpan_lokasi() {
    $.ajax({
        url: base_url + "index.php/lokasi/edit_cabang",
        type: 'POST',
        data: {
            "IDLokasi": $("#IDLokasi_edit").val(),
            "kecamatan": $("#kecamatan_edit").val(),
            "kabupaten": $("#kabupaten_edit").val(),
            "wilayah": $("#wilayah_edit").val(),
            "daerah": $("#daerah_edit").val()
        }, success: function (data, textStatus, jqXHR) {
            $('#myModalEdit').modal('hide');
            $("#daerah_edit").val("");
            $("#kecamatan_edit").val("");
            $("#wilayah_edit").val("");
            $("#kabupaten_edit").val("");
            $("#provinsi_edit").val("");

            tempAlert('Memproses Data ...', 1000);

//            refreshEditPage();
//            refreshEditPage().then(function(result) {});

//            $("#load_lokasi").load(base_url + "index.php/Laporan/get_lokasi");
//            var lokasi = JSON.parse(data);
//
//            $("#kecamatan").html("<p style='margin: 0px; margin-top: 8px;'>" + lokasi.kecamatan + "</p>");
//            $("#kabupaten").html("<p style='margin: 0px; margin-top: 8px;'>" + lokasi.kabupaten + "</p>");
//            $("#wilayah").html("<p style='margin: 0px; margin-top: 8px;'>" + lokasi.wilayah + "</p>");
//            $("#provinsi").html("<p style='margin: 0px; margin-top: 8px;'>" + lokasi.provinsi + "</p>");
        }, error: function (jqXHR, textStatus, errorThrown) {
            alert(JSON.stringify(jqXHR));
        }
    });
}

function edit_kolom(param) {
    tamp = "<div class='form-group'>";
    tamp += "<label class='col-sm-3 control-label'>Lain-lain: </label>";
    tamp += "<div class='col-sm-9'>";
    tamp += "<input type='text' class='form-control siku' name='lain[]' value='' placeholder='Keterangan'/> ";
    tamp += "</div>";
    tamp += "<br>";
    tamp += "<label  class='col-sm-3 control-label'></label>";
    tamp += "<div class='col-sm-9'>";
    tamp += "<input type='text' class='form-control siku' name='lain[]' value='' placeholder='Nominal'/> ";
    tamp += "</div>";
    tamp += "<br>";
    tamp += "</div>";
    if (param == 0) { /* Reset */
        $(".kas-lain-lain").html("");
    } else { /* Tambah Kolom */
        $(".kas-lain-lain").append(tamp);
    }
}
function changeLokasi(IDSelect) {
    var IDLokasi = document.getElementById(IDSelect).value;
    $.ajax({
        url: base_url + "index.php/lokasi/get_detail_lokasi",
        type: 'POST',
        data: {
            "IDLokasi": IDLokasi
        }, success: function (data, textStatus, jqXHR) {
            var lokasi = JSON.parse(data);
            $("#kecamatan").html("<p style='margin: 0px; margin-top: 8px;'>" + lokasi.kecamatan + "</p>");
            $("#kabupaten").html("<p style='margin: 0px; margin-top: 8px;'>" + lokasi.kabupaten + "</p>");
            $("#wilayah").html("<p style='margin: 0px; margin-top: 8px;'>" + lokasi.wilayah + "</p>");
            $("#provinsi").html("<p style='margin: 0px; margin-top: 8px;'>" + lokasi.provinsi + "</p>");
        }, error: function (jqXHR, textStatus, errorThrown) {
            alert(JSON.stringify(jqXHR));
        }
    });
}
function update_lokasi_laporan() {
    id_lokasi = $("#lokasi").val();
    $.ajax({
        url: base_url + "index.php/Laporan/get_lokasi_info",
        type: "POST",
        data: {
            "IDLokasi": id_lokasi
        },
        success: function (data, textStatus, jqXHR) {
            tampString = data;
            str = tampString.split("-");
            $("#kecamatan").html(str[0]);
            $("#wilayah").html(str[1]);
            $("#provinsi").html(str[2]);
            $("#kabupaten").html(str[3]);
        }
    });
}
function update_kas_keluar() {
    keterangan_kas_keluar = $(".kas_keluar").val();
    var tamp = "";
    if (keterangan_kas_keluar == "lain-lain") {
        $("#kas-lain-lain").show();
        $("#bayar_gaji").hide();
        tamp = "<label class='col-sm-4 control-label'>Keterangan: </label>";
        tamp += "<div class='col-sm-8'>";
        tamp += "<input type='text' class='form-control siku' id='keterangan_kas' value='' placeholder='Keterangan' name='keterangan_lainnya'/> ";
        tamp += "</div>";
    }
    else {

    }
//    else if (keterangan_kas_keluar == "Gaji") {
//        $("#kas-lain-lain").hide();
//        $("#bayar_gaji").show();
//    }
//    else {
//        $("#kas-lain-lain").show();
//        $("#bayar_gaji").hide();
//        tamp = "<label class='col-sm-4 control-label'>Nominal: </label>";
//        tamp += "<div class='col-sm-8'>";
//        tamp += "<input type='text' class='form-control siku' id='nominal' value='' placeholder='Nominal'/> ";
//        tamp += "</div>";
//    }
    $("#kas-lain-lain").html(tamp);
}
function tambah_lokasi() {
    $.ajax({
        url: base_url + "index.php/lokasi/tambah_cabang",
        type: 'POST',
        data: {
            "kecamatan": $("#kecamatan_input").val(),
            "kabupaten": $("#kabupaten_input").val(),
            "wilayah": $("#wilayah_input").val(),
            "daerah": $("#daerah_input").val()
        }, success: function (data, textStatus, jqXHR) {
            $('#myModal').modal('hide');
            $("#daerah_input").val("");
            $("#kecamatan_input").val("");
            $("#wilayah_input").val("");
            $("#kabupaten_input").val("");
            $("#provinsi_input").val("");

            //$("#load_lokasi").load(base_url + "index.php/Laporan/get_lokasi");
            window.location.href = current_url;
        }, error: function (jqXHR, textStatus, errorThrown) {
            alert(JSON.stringify(jqXHR));
        }
    });
}
function dismiss_modal() {
    $('#myModal').modal('hide');
    $("#daerah_input").val("");
    $("#kecamatan_input").val("");
    $("#wilayah_input").val("");
    $("#kabupaten_input").val("");
    $("#provinsi_input").val("");
}

function tampil_foto() {
    var IDSales = document.getElementById("salesnya_admin").value;
    $.ajax({
        url: base_url + "index.php/Laporan/get_sales/" + IDSales,
        type: 'POST',
        success: function (data, textStatus, jqXHR) {
            var sales = JSON.parse(data);
//            alert(sales.foto);
            $("#foto_image").attr("src", base_url + "uploads/" + sales.foto);
            if ($("#datepicker").val()) {
                $("#tanggal_tampung").val($("#datepicker").val());
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            alert(JSON.stringify(jqXHR));
        }
    });
}

function change_kas(IDSelect) {
    var IDKas = document.getElementById(IDSelect).value;
    if (IDKas == 2) {
        $.ajax({
            url: base_url + "index.php/laporan/get_saldo_kas_kantor",
            type: 'POST',
            data: {
            }, success: function (data, textStatus, jqXHR) {
                $("#saldo_1").val(data);
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(JSON.stringify(jqXHR));
            }
        });
    } else {
        $.ajax({
            url: base_url + "index.php/laporan/get_saldo_kas_bank",
            type: 'POST',
            data: {
            }, success: function (data, textStatus, jqXHR) {
                $("#saldo_1").val(data);
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(JSON.stringify(jqXHR));
            }
        });
    }
}
//function get_saldo() {
//    var cabang = document.getElementById("cabang").value;
//    if (cabang == 0) {
//        $("#saldo").html("Rp.0,-");
//    } else {
//        $.ajax({
//            url: base_url + "index.php/Laporan/get_cabang_saldo",
//            type: 'POST',
//            data: {
//                'cabang': cabang
//            },
//            success: function (data, textStatus, jqXHR) {
//                $("#saldo").html(data);
//            }, error: function (jqXHR, textStatus, errorThrown) {
//                alert(JSON.stringify(jqXHR));
//            }
//        });
//    }
//}