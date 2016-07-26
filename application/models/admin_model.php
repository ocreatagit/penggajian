<?php

class Admin_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('security');
        $this->load->helper('string');
        $this->load->helper('date');
    }

    function cek_login($username, $password) {
        $query = $this->db->get_where("admin", array("username" => $username));
        if ($query->num_rows() == 0) {
            $this->session->set_flashdata("status", "Username Tidak Terdaftar Dalam Sistem!");
            return false;
        } else {
            $query = $this->db->query("SELECT * FROM admin WHERE LOWER(username) = LOWER('$username') and LOWER(password) = LOWER('$password')");
            if ($query->num_rows() > 0) {
                return true;
            } else {
                $this->session->set_flashdata("status", "Password yang Diinputkan Salah!");
                return false;
            }
        }
    }

    function cek_level_login($username) {
        $query = $this->db->query("Select level From admin Where username = '$username'");
        return $query->row();
    }

    function get_admin_edit($IDAdmin) {
        $sql = "SELECT * FROM admin WHERE IDAdmin = " . $IDAdmin;
        $res = $this->db->query($sql);
        if ($res->num_rows() == 0) {
            $this->session->set_flashdata("status", "Admin Tidak Ditemukan!");
            return -1;
        } else {
            return $res->row();
        }
    }

    function delete_admin_set_increment($IDAdmin, $set = TRUE) {
        $this->db->where("IDAdmin", $IDAdmin);
        $this->db->delete("admin");

        if ($set) {
            $sql = "ALTER TABLE admin AUTO_INCREMENT=" . $IDAdmin;
            $this->db->query($sql);
        }
    }

    function get_provinsi_kabupaten($username) {
        $query = $this->db->query("SELECT provinsi, kabupaten
                                    FROM cabang c
                                    INNER JOIN admin a ON c.IDAdmin = a.IDAdmin
                                    WHERE a.username = '$username'");
        return $query->row();
    }

    function get_kecamatan_desa($username) {
        $query = $this->db->query("SELECT l.IDLokasi as id_lokasi, l.desa, l.kecamatan as lokasi
                                FROM cabang c
                                INNER JOIN lokasi l ON l.IDCabang = c.IDCabang
                                INNER JOIN admin a ON c.IDAdmin = a.IDAdmin
                                WHERE a.Username = '$username' ORDER BY l.desa");
        return $query->result();
    }

    function insert_admin() {
        $data = array(
            "username" => $this->input->post("username"),
            "password" => $this->input->post("password"),
            "level" => $this->input->post("level"),
            "nama" => $this->input->post("nama"),
            "email" => $this->input->post("email")
        );
        $this->db->insert("admin", $data);

        return $this->db->insert_id();
    }

    function update_admin() {
        $SQL = "SELECT * FROM admin WHERE nama = '" . $this->input->post("nama") . "' AND level != 0 AND IDAdmin != " . $this->input->post("IDAdmin") . ";";
        $res = $this->db->query($SQL);
        if ($res->num_rows() == 0) {
            $data = array(
                "nama" => $this->input->post("nama"),
                "email" => $this->input->post("email")
            );
            $this->db->where("IDAdmin", $this->input->post("IDAdmin"));
            $this->db->update("admin", $data);
            $this->session->set_flashdata("status_admin", "Admin Telah Diubah!");
        } else {
            $this->session->set_flashdata("status_admin", "Nama atau Email Sudah Digunakan!");
        }
    }

    function get_admin($username, $password) {
        return $this->db->get_where("admin", array("username" => $username, "password" => $password))->row();
    }

    function select_admin() {
        $query = $this->db->get('admin');
        return $query->result();
    }

    function get_admin_not_in_cabang() {
        $sql = "SELECT * FROM admin WHERE IDAdmin NOT IN (SELECT IDAdmin FROM cabang) AND level = 1;";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_admin_kantor_not_in_cabang() {
        $sql = "SELECT * FROM admin WHERE IDAdmin NOT IN (SELECT IDAdmin_kantor FROM cabang) AND level = 2;";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_saldo($username) {
        $sql = "SELECT level FROM admin WHERE username = '$username'";
        $level = $this->db->query($sql)->row()->level;
        if ($level == 1) {
            $sql = "Select c.saldo as saldo
                    From cabang c
                    INNER JOIN admin a ON a.IDAdmin = c.IDAdmin
                    WHERE a.username = '$username'";
        } else {
            $sql = "Select c.saldo as saldo
                    From cabang c
                    INNER JOIN admin a ON a.IDAdmin = c.IDAdmin_kantor
                    WHERE a.username = '$username'";
        }
        if ($level == 0) {
            return 0;
        }
        $query = $this->db->query($sql);
        return $query->row()->saldo;
    }

    function get_saldo_bank($IDCabang) {
        if ($this->session->userdata("Level") != 0) {
//            $SQL = "SELECT nilai_akun FROM akun_cabang WHERE IDAkun = 3 AND IDCabang = " . $IDCabang . ";";
//            $res = $this->db->query($SQL)->row();
//            return $res->nilai_akun;
            $this->load->model("Jurnal_model");
            $data = $this->Jurnal_model->select_laporan_mutasi_kas_bank(FALSE, FALSE, $IDCabang, FALSE);
            $saldo_mutasi = 0;
            foreach ($data as $laporan):
                $laporan->sifat == 'K' ? $saldo_mutasi -= $laporan->kaskeluar : $saldo_mutasi += $laporan->kasmasuk;
            endforeach;
            return $saldo_mutasi;
        } else {
            return 0;
        }
    }

    function get_saldo_cabang($IDCabang) {
        if ($this->session->userdata("Level") != 0) {
//            $sql = "Select c.saldo as saldo From cabang c WHERE c.IDCabang = " . $IDCabang . ";";
//            $query = $this->db->query($sql);
//            return $query->row()->saldo;
            $this->load->model("Jurnal_model");
            $data = $this->Jurnal_model->select_laporan_mutasi_kas(FALSE, FALSE, $IDCabang, $this->session->userdata("Level"));
            $saldo_mutasi = 0;
            foreach ($data as $laporan):
                $laporan->sifat == 'K' ? $saldo_mutasi -= $laporan->kaskeluar : $saldo_mutasi += $laporan->kasmasuk;
            endforeach;
            return $saldo_mutasi;
        } else {
            return 0;
        }
    }

    /* Cek cek Penggajian */

//    function set_tanggal_gaji() {
//        $data = array(
//            "tanggalGaji" => strftime("%Y-%m-%d", strtotime("now"))
//        );
//        $this->db->update('sales', $data);
//    }
//
//    function cek_penggajian() {
//        $query = $this->db->query(" Select tanggalGaji as tanggal_gaji from sales");
//        return $query->row()->tanggal_gaji;
//    }


    /* 13/10/2015 */
    function get_cabang($username) {
        $sql = "SELECT level FROM admin WHERE username = '$username'";
        $level = $this->db->query($sql)->row()->level;
        if ($level == 1) {
            $sql = "SELECT IDCabang as idcabang
                FROM cabang c
                INNER JOIN admin a ON c.IDAdmin = a.IDAdmin
                WHERE a.username = '$username'";
        } else if ($level == 2) {
            $sql = "SELECT IDCabang as idcabang
                FROM cabang c
                INNER JOIN admin a ON c.IDAdmin_kantor = a.IDAdmin
                WHERE a.username = '$username'";
        } else if ($level == 0) {
            
        }
        $query = $this->db->query("$sql");
        return $query->row()->idcabang;
    }

    function get_all_cabang() {
        $sql = "SELECT IDCabang as idcabang, provinsi, kabupaten
                FROM cabang c;";
        $query = $this->db->query("$sql");
        return $query->result();
    }

    function get_detail_cabang($IDCabang) {
        if ($IDCabang == 0) {
            $array = array(
                'IDCabang' => 0,
                'IDAdmin' => 0,
                'IDAdmin_kantor' => 0,
                'provinsi' => 'Semua Cabang',
                'kabupaten' => 'Semua Cabang',
                'saldo' => 0,
                'last_updated' => date('Y-m-d')
            );
            return (object) $array;
        } else {
            return $this->db->get_where("cabang", array("IDCabang" => $IDCabang))->row();
        }
    }

    function insert_pendapatan() {
        $this->load->model("Sales_model");
        $this->load->model("Jurnal_model");

        $this->start_trans();
        $sql = "SELECT AUTO_INCREMENT as IDPenjualan FROM information_schema.tables WHERE TABLE_SCHEMA = 'penggajian' AND TABLE_NAME = 'laporan_penjualan';";
        $IDPenjualan = $this->db->query($sql)->row()->IDPenjualan;
        $data = array(
            "tanggal" => strftime("%Y-%m-%d", strtotime($this->session->userdata("tanggal_jual"))),
            "keterangan" => $this->session->userdata("keterangan"),
            "IDCabang" => $this->Admin_model->get_cabang($this->session->userdata('Username'))
        );
        $this->db->insert("laporan_penjualan", $data);

        foreach ($this->cart->contents() as $items) {
            if (strpos($items["id"], "Jual") !== FALSE) {
                /* Kurangi Stoknya belum */
                $this->insert_detail_pendapatan(
                        $IDPenjualan, $items['options']['IDTeamLeader'], $items['options']['IDSales'], $items['options']['IDBarang'], $items['options']['IDLokasi'], $items['qty'], $items['price'], $this->session->userdata('Username')
                );


                $this->Sales_model->tambah_gaji_dan_komisi_sales(
                        $IDPenjualan, $items['options']['IDSales'], $items['options']['komisi']
                );

                $data = array('rowid' => $items['rowid'], 'qty' => 0);
                $this->cart->update($data);
            }
        }
        $this->Sales_model->insert_kehadiran_sales();
        // Jurnal
        $this->Jurnal_model->insert_jurnal($IDPenjualan, 'Penjualan Barang');

        $this->end_trans("insert_laporan_penjualan()");
    }

    function check_penjualan($tanggal, $IDCabang) {
        if ($tanggal == "") {
            $this->session->set_userdata("status_tanggal", "Tanggal Kosong!");
        } else {
            $sql = "SELECT * FROM laporan_penjualan WHERE tanggal = '" . strftime("%Y-%m-%d", strtotime($tanggal)) . "' AND IDCabang = " . $IDCabang . " AND laporan_penjualan.IDPenjualan NOT IN (SELECT laporan_pembatalan_penjualan.IDPenjualan FROM `laporan_pembatalan_penjualan`);";
//            $res = $this->db->get_where("laporan_penjualan", array("tanggal" => strftime("%Y-%m-%d", strtotime($tanggal)), "IDCabang" => $IDCabang));
            $res = $this->db->query($sql);

            if ($res->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function insert_detail_pendapatan($IDPenjualan, $IDTeamLeader, $IDSales, $IDBarang, $IDLokasi, $qty, $price, $username) {
        $IDCabang = $this->get_cabang($username);

        $data = array(
            "IDPenjualan" => $IDPenjualan,
            "IDTeamLeader" => $IDTeamLeader,
            "IDSales" => $IDSales,
            "IDBarang" => $IDBarang,
            "IDLokasi" => $IDLokasi,
            "jumlah" => $qty,
            "hargaJual" => $price
        );
        $this->db->insert("jual", $data);


        /* TAMBAHIN TOTAL PENJUALAN */
        $query = "SELECT totalPenjualan FROM laporan_penjualan WHERE IDPenjualan = " . $IDPenjualan;
        $result = $this->db->query($query)->row();
        $current_penjualan = $result->totalPenjualan;

        $current_penjualan += ($price + 0);

        $data = array("totalPenjualan" => ($current_penjualan));
        $this->db->where("IDPenjualan", $IDPenjualan);
        $this->db->update('laporan_penjualan', $data);



        /* Kurangi STOK */

        $query = $this->db->query("SELECT jumlah FROM cabang_barang WHERE IDCabang = $IDCabang AND IDBarang = $IDBarang");
        if ($query->num_rows() > 0) {
            $query = "SELECT jumlah FROM cabang_barang WHERE IDCabang = " . $IDCabang . " AND IDBarang = " . $IDBarang;
            $result = $this->db->query($query)->row();
            $current_stok = 0 + ($result->jumlah - $qty);

            $data = array("jumlah" => ($current_stok));
            $this->db->where("IDCabang", $IDCabang);
            $this->db->where("IDBarang", $IDBarang);
            $this->db->update('cabang_barang', $data);
        } else {
            $current_stok = (0 - $qty);
            $data = array(
                "IDCabang" => $IDCabang,
                "IDBarang" => $IDBarang,
                "jumlah" => $current_stok
            );
            $this->db->insert("cabang_barang", $data);
        }
        return "0";
    }

    function get_autoinc_laporan_pengeluaran() {
        $sql = "SELECT AUTO_INCREMENT as IDPengeluaran FROM information_schema.tables WHERE TABLE_SCHEMA = 'penggajian' AND TABLE_NAME = 'laporan_pengeluaran';";
        $IDPengeluaran = $this->db->query($sql)->row()->IDPengeluaran;
        return $IDPengeluaran;
    }

    function insert_laporan_pengeluaran($IDPengeluaran) {
        $data = array(
            'KodePengeluaran' => "PL/$IDPengeluaran/" . date("d") . "/" . date("m") . "/" . date("y"),
            "tanggal" => strftime("%Y-%m-%d", strtotime($this->session->userdata("tanggal_jual"))),
            "totalPengeluaran" => 0,
            "IDCabang" => $this->Admin_model->get_cabang($this->session->userdata('Username'))
        );
        $this->db->insert("laporan_pengeluaran", $data);
    }

    function get_laporan_pengeluaran($IDPengeluaran, $jenis) {
        $res = array();
        if (strpos($jenis, "Biaya") !== FALSE) {
            $res = $this->db->get_where("laporan_pengeluaran", array("IDPengeluaran" => $IDPengeluaran))->row();
        } else if (strpos($jenis, "Bayar Gaji") !== FALSE || strpos($jenis, "Bayar Komisi") !== FALSE) {
            $res = $this->db->get_where("laporan_penggajian", array("IDPenggajian" => $IDPengeluaran))->row();
        } else if (strpos($jenis, "Setor Kas") !== FALSE || strpos($jenis, "Terima Setoran") !== FALSE) {
            $res = $this->db->get_where("setoran_bank", array("IDSetoran" => $IDPengeluaran))->row();
        } else if (strpos($jenis, "Tarik Kas Bank") !== FALSE || strpos($jenis, "Terima Penarikan") !== FALSE) {
            $res = $this->db->get_where("tarik_kas_bank", array("IDTarikKas" => $IDPengeluaran))->row();
        } else if (strpos($jenis, "Batal Pengeluaran gaji") !== FALSE || strpos($jenis, "Batal Pengeluaran komisi") !== FALSE) {
            $res = $this->db->get_where("laporan_penggajian", array("IDPenggajian" => $IDPengeluaran))->row();
        } else if (strpos($jenis, "Batal Pengeluaran Bensin") !== FALSE || strpos($jenis, "Batal Pengeluaran Makan") !== FALSE || strpos($jenis, "Batal Pengeluaran Parkir") !== FALSE || strpos($jenis, "Batal Pengeluaran Tol") !== FALSE || strpos($jenis, "Batal Pengeluaran lain-lain") !== FALSE) {
            $res = $this->db->get_where("laporan_pengeluaran", array("IDPengeluaran" => $IDPengeluaran))->row();
        }
        return $res->tanggal;
    }

    function insert_pengeluaran($IDPengeluaran, $keterangan, $nominal, $keterangan_lainnya) {

        $data = array(
            "keterangan" => $keterangan,
            "total_pengeluaran" => $nominal,
            "IDPengeluaran" => $IDPengeluaran,
            'keterangan_lanjut' => $keterangan_lainnya
        );
        $this->db->insert("detail_pengeluaran", $data);

        /* Get Date of report dan Catat Total Pengeluaran */
        $sql = "SELECT totalPengeluaran FROM laporan_pengeluaran Where IDPengeluaran = $IDPengeluaran";
        $result = $this->db->query($sql)->row();

        $current_pengeluaran = $result->totalPengeluaran;

        $current_pengeluaran += ($nominal + 0);

//        var_dump($nominal); exit;

        $data = array("totalPengeluaran" => ($current_pengeluaran));
        $this->db->where("IDPengeluaran", $IDPengeluaran);
        $this->db->update('laporan_pengeluaran', $data);

        $IDCabang = $this->Admin_model->get_cabang($this->session->userdata('Username'));
        $cabang = $this->db->get_where("cabang", array("IDCabang" => $IDCabang))->row();

//        echo $cabang->saldo; exit;
        $saldo = $cabang->saldo + (-$nominal);

        $data = array(
            "saldo" => $saldo
        );
        $this->db->where("IDCabang", $IDCabang);
        $this->db->update("cabang", $data);
        /* Get Date of report dan Catat Total Pengeluaran */
    }

    function insert_bayar_gaji($IDPenjualan, $IDSales, $nominal) {
        date_default_timezone_set('Asia/Jakarta');
//        $IDPenjualan = $this->input->post("IDPenjualan");
//        $IDSales = $this->input->post("IDSales");
//        $nominal = $this->input->post("nominal");

        /* Insert ke tabel bayar_gaji */
        $data = array(
            "jumlah" => $nominal,
            "IDPenjualan" => $IDPenjualan,
            "IDSales" => $IDSales
        );
        $this->db->insert("bayar_gaji", $data);

        /* Get Info Sales */
        $sales = $this->db->get_where("sales", array("IDSales" => $IDSales))->row();

        /* Get Date of report dan Catat Total Pengeluaran */
        $sql = "SELECT tanggal, totalPengeluaran FROM laporan_penjualan Where IDPenjualan = $IDPenjualan";
        $result = $this->db->query($sql)->row();
        $tanggal_laporan = $result->tanggal;

        $current_pengeluaran = $result->totalPengeluaran;

        $current_pengeluaran += ($nominal + 0);

        $data = array("totalPengeluaran" => ($current_pengeluaran));
        $this->db->where("IDPenjualan", $IDPenjualan);
        $this->db->update('laporan_penjualan', $data);
        /* Get Date of report dan Catat Total Pengeluaran */



        /* Update Total Saldo Gaji Sales */
        $data = array(
            "totalGaji" => ($sales->totalGaji - $nominal)
        );
        $this->db->where("IDSales", $IDSales);
        $this->db->update("sales", $data);

        /* History Gaji */
        $data = array(
            "IDSales" => $IDSales,
            "Nominal" => $nominal,
            "Tanggal" => $tanggal_laporan,
            "Keterangan" => "Gaji diambil"
        );
        $this->db->insert("historygaji", $data);
    }

    function hitung_saldo($IDPenjualan, $username) {
        /* Get Date of report dan Catat Total Pengeluaran */
        $sql = "SELECT totalPenjualan, totalKomisi FROM laporan_penjualan Where IDPenjualan = $IDPenjualan";
        $result = $this->db->query($sql)->row();
        $total_penjualan = $result->totalPenjualan;
        $total_totalKomisi = $result->totalKomisi;

        $IDCabang = $this->get_cabang($username);

        /* Get Date of report dan Catat Total Pengeluaran */
        $query = "SELECT saldo FROM cabang Where IDCabang = '$IDCabang'";
        $result = $this->db->query($query)->row();
        $saldo = $result->saldo;

        $saldo_terakhir = (($saldo + $total_penjualan));

        /* Update Total Saldo Gaji Sales */
        $data = array(
            "saldo" => $saldo_terakhir
        );
        $this->db->where("IDCabang", $IDCabang);
        $this->db->update("cabang", $data);

        /* Update Total Saldo Gaji Sales */
        $data = array(
            "status_kas" => 1
        );
        $this->db->where("IDPenjualan", $IDPenjualan);
        $this->db->update("laporan_penjualan", $data);

        $this->session->set_flashdata("status", "Data Telah Dimasukkan ke dalam Kas!");
    }

    function batal_saldo($IDPenjualan, $username) {
        /* Get Date of report dan Catat Total Pengeluaran */
        $sql = "SELECT totalPenjualan, totalKomisi FROM laporan_penjualan Where IDPenjualan = $IDPenjualan";
        $result = $this->db->query($sql)->row();
        $total_penjualan = $result->totalPenjualan;
        $total_totalKomisi = $result->totalKomisi;

        $IDCabang = $this->get_cabang($username);

        /* Get Date of report dan Catat Total Pengeluaran */
        $query = "SELECT saldo FROM cabang Where IDCabang = '$IDCabang'";
        $result = $this->db->query($query)->row();
        $saldo = $result->saldo;

        $saldo_terakhir = (($saldo - $total_penjualan));

        /* Update Total Saldo Gaji Sales */
        $data = array(
            "saldo" => $saldo_terakhir
        );
        $this->db->where("IDCabang", $IDCabang);
        $this->db->update("cabang", $data);

        /* Update Total Saldo Gaji Sales */
        $data = array(
            "status_kas" => 0
        );
        $this->db->where("IDPenjualan", $IDPenjualan);
        $this->db->update("laporan_penjualan", $data);

        $this->session->set_flashdata("status", "Kas Telah DiBatalkan!");
    }

    /* DANIEL */

    function change_data() {
        $data = array(
            'password' => $this->input->post('password')
                //'email' => $this->input->post('email')
        );

        $this->db->where('username', $this->session->userdata('Username'));
        $this->db->update('admin', $data);

        $this->session->set_flashdata("status", "Password telah di ubah!");
    }

    /* DANIEL */

    function insert_penjualan_gaji() {
        $sql = "SELECT AUTO_INCREMENT as ID FROM information_schema.tables WHERE TABLE_SCHEMA = 'penggajian' AND TABLE_NAME = 'laporan_penggajian';";
        $id = $this->db->query($sql)->row()->ID;
        $data = array(
            "KodePenggajian" => "GJ/$id/" . date("d") . "/" . date("m") . "/" . date("y"),
            "IDCabang" => $this->Admin_model->get_cabang($this->session->userdata('Username')),
            "tanggal" => date("Y-m-d"),
            "totalPenggajian" => 0,
            "keterangan" => "gaji"
        );
        $this->db->insert("laporan_penggajian", $data);
        return $id;
    }

    function insert_detail_penggajian($IDPenggajian, $IDSales, $tanggal, $gaji_diambil) {
        $data = array(
            "IDPenggajian" => $IDPenggajian,
            "IDSales" => $IDSales,
            "tanggal" => strftime("%Y-%m-%d", strtotime($tanggal)),
            "total_gaji" => $gaji_diambil
        );
        $this->db->insert("detail_penggajian", $data);

        $penggajian = $this->db->get_where("laporan_penggajian", array("IDPenggajian" => $IDPenggajian))->row();
        $data = array(
            "totalPenggajian" => ($penggajian->totalPenggajian + $gaji_diambil)
        );
        $this->db->where("IDPenggajian", $IDPenggajian);
        $this->db->update("laporan_penggajian", $data);

        $admin = $this->db->query("SELECT c.IDCabang, c.saldo FROM admin a INNER JOIN cabang c ON c.IDAdmin_kantor = a.IDAdmin WHERE a.username = '" . $this->session->userdata('Username') . "'")->row();
        $saldo = $admin->saldo - $gaji_diambil;
        $data = array(
            'saldo' => $saldo
        );
        $this->db->where("IDCabang", $admin->IDCabang);
        $this->db->update("cabang", $data);

        $sales = $this->db->get_where("sales", array("IDSales" => $IDSales))->row();
        $data = array("totalGaji" => ($sales->totalGaji - $gaji_diambil));
        $this->db->where("IDSales", $IDSales);
        $this->db->update("sales", $data);
    }

    function setor_bank() {
        $IDCabang = $this->input->post("IDCabang");
        $saldo = $this->input->post("saldo_admin");
        $total_setor = $this->input->post("total_setor");
        $keterangan = $this->input->post("keterangan");
        $tanggal = $this->input->post("tanggal");

        $data = array(
            "tanggal" => strftime("%Y-%m-%d", strtotime($tanggal)),
            "jumlah" => $total_setor,
            "keterangan" => $keterangan,
            "IDCabang" => $IDCabang
        );
        $sql = "SELECT AUTO_INCREMENT as ID FROM information_schema.tables WHERE TABLE_SCHEMA = 'penggajian' AND TABLE_NAME = 'setoran_bank';";
        $IDJurnal = $this->db->query($sql)->row()->ID;
        $this->db->insert("setoran_bank", $data);

        $this->load->model('Jurnal_model');
        $array = array(
            "saldo" => ($saldo - $total_setor)
        );
        $this->db->where("IDCabang", $IDCabang);
        $this->db->update('cabang', $array);

        $this->Jurnal_model->insert_jurnal_pengeluaran($IDJurnal, 'Setor Kas Bank', $total_setor);
        $this->Jurnal_model->insert_jurnal_pengeluaran($IDJurnal, 'Terima Setoran Bank', $total_setor, FALSE);

        $this->session->set_userdata("tanggal_setoran", $tanggal);
    }

    function tarik_kas_bank() {
        $IDCabang = $this->input->post("IDCabang");
        $saldo = $this->input->post("saldo_admin");
        $saldo_cabang = $this->db->get_where("cabang", array("IDCabang" => $IDCabang))->row()->saldo;
        $total_setor = $this->input->post("total_setor");
        $keterangan = $this->input->post("keterangan");
        $tanggal = $this->input->post("tanggal");

        $data = array(
            "tanggal" => strftime("%Y-%m-%d", strtotime($tanggal)),
            "jumlah" => $total_setor,
            "keterangan" => $keterangan,
            "IDCabang" => $IDCabang
        );
        $sql = "SELECT AUTO_INCREMENT as ID FROM information_schema.tables 
WHERE TABLE_SCHEMA = 'penggajian' AND TABLE_NAME = 'tarik_kas_bank';";
        $IDJurnal = $this->db->query($sql)->row()->ID;
        $this->db->insert("tarik_kas_bank", $data);


        $this->load->model('Jurnal_model');
        $array = array(
            "saldo" => ($saldo_cabang + $total_setor)
        );
        $this->db->where("IDCabang", $IDCabang);
        $this->db->update('cabang', $array);

        $this->Jurnal_model->insert_jurnal_pengeluaran($IDJurnal, 'Tarik Kas Bank', $total_setor);
        $this->Jurnal_model->insert_jurnal_pengeluaran($IDJurnal, 'Terima Penarikan Kas Bank', $total_setor, FALSE);
    }

    function buat_pembatalan_nota() {
        $IDCabang = $this->session->userdata("IDCabang");
        $penjualan = $this->db->get_where("laporan_penjualan", array("IDPenjualan" => $this->input->post("IDPenjualan")))->row();

        $data = array(
            "tanggal" => strftime("%Y-%m-%d", strtotime($this->input->post("tanggal"))),
            "total" => $penjualan->totalPenjualan,
            "keterangan" => $this->input->post("keterangan"),
            "IDPenjualan" => $penjualan->IDPenjualan
        );
        $sql = "SELECT AUTO_INCREMENT as ID FROM information_schema.tables 
                WHERE TABLE_SCHEMA = 'penggajian' AND TABLE_NAME = 'laporan_pembatalan_penjualan';";
        $IDJurnal = $this->db->query($sql)->row()->ID;

        $this->db->insert("laporan_pembatalan_penjualan", $data);


        $juals = $this->db->get_where("jual", array("IDPenjualan" => $penjualan->IDPenjualan))->result();
//        print_r($juals); exit;
        foreach ($juals as $jual) {
            $sql = "SELECT * FROM cabang_barang WHERE IDCabang = $IDCabang AND IDBarang = $jual->IDBarang;";
            $cabang_barang = $this->db->query($sql)->row();
            $data = array(
                "jumlah" => ($cabang_barang->jumlah + $jual->jumlah)
            );
            $this->db->where("IDCabang", $IDCabang);
            $this->db->where("IDBarang", $jual->IDBarang);
            $this->db->update("cabang_barang", $data);

            $sql = "SELECT * FROM sales WHERE IDSales = " . $jual->IDSales . ";";
            $sales = $this->db->query($sql)->row();

            $sql = "SELECT * FROM komisi WHERE IDSales = " . $jual->IDSales . " AND IDBarang = " . $jual->IDBarang . ";";
            $komisi = $this->db->query($sql)->row();

            $data = array(
                "totalKomisi" => ($sales->totalKomisi - ($jual->jumlah * $komisi->komisi))
            );
//            print_r($data); exit;

            $this->db->where("IDSales", $jual->IDSales);
            $this->db->update("sales", $data);

            // kehadiran - Daniel
            $this->db->where("tanggal", $penjualan->tanggal);
            $this->db->where("IDSales", $jual->IDSales);
            $this->db->delete("kehadiran");
        }

        $SQL = "SELECT DISTINCT IDSales FROM jual WHERE IDPenjualan = " . $penjualan->IDPenjualan . ";";
        $juals = $this->db->query($SQL)->result();
        foreach ($juals as $jual) {
            $sql = "SELECT * FROM sales WHERE IDSales = " . $jual->IDSales . ";";
            $sales = $this->db->query($sql)->row();
            
            $data = array(
                "totalGaji" => ($sales->totalGaji - $sales->gaji)
            );

            $this->db->where("IDSales", $jual->IDSales);
            $this->db->update("sales", $data);
            
            // Hapus Gaji
            $this->db->where("IDSales", $jual->IDSales);
            $this->db->where("Tanggal", $penjualan->tanggal);
            $this->db->delete("historygaji");
            
            // Hapus Komisi
            $this->db->where("IDSales", $jual->IDSales);
            $this->db->where("DATE(Tanggal)", date("Y-m-d", strtotime($penjualan->tanggal)));
            $this->db->delete("historykomisi");
            
        }

        /*
          $cabang = $this->db->get_where("cabang", array("IDCabang" => $IDCabang))->row();
          $data = array(
          "saldo" => ($cabang->saldo - $penjualan->totalPenjualan)
          );
          $this->db->where("IDCabang", $IDCabang);
          $this->db->update("cabang", $data);
         */
        $this->Jurnal_model->insert_jurnal($penjualan->IDPenjualan, 'Pembatalan Penjualan');
    }

    function buat_pembatalan_pengeluaran() {
        // Masih ERROR
        $IDCabang = $this->session->userdata("IDCabang");
        if ($this->input->post("tipe") == 1) {
            $pengeluaran = $this->db->get_where("laporan_pengeluaran", array("IDPengeluaran" => $this->input->post("IDPengeluaran")))->row();
        } else {
            $penggajian = $this->db->get_where("laporan_penggajian", array("IDPenggajian" => $this->input->post("IDPengeluaran")))->row();
        }

        $sql = "SELECT AUTO_INCREMENT as ID FROM information_schema.tables WHERE TABLE_SCHEMA = 'penggajian' AND TABLE_NAME = 'laporan_pembatalan_pengeluaran';";
        $IDJurnal = $this->db->query($sql)->row()->ID;
        $data = array(
            "tanggal" => strftime("%Y-%m-%d", strtotime($this->input->post("tanggal"))),
            "tipe" => $this->input->post("tipe"),
            "total" => $this->input->post("tipe") == 1 ? $pengeluaran->totalPengeluaran : $penggajian->totalPenggajian,
            "keterangan" => $this->input->post("keterangan"),
            "IDPengeluaran" => $this->input->post("tipe") == 1 ? $pengeluaran->IDPengeluaran : $penggajian->IDPenggajian
        );

        $this->db->insert("laporan_pembatalan_pengeluaran", $data);

        if ($this->input->post("tipe") == 1) {
            $detail_pengeluaran = $this->db->get_where("detail_pengeluaran", array("IDPengeluaran" => $this->input->post("IDPengeluaran")))->result();
            foreach ($detail_pengeluaran as $row) {
                $sql = "UPDATE cabang SET saldo = saldo + " . $row->total_pengeluaran;
                $this->db->query($sql);

                $this->Jurnal_model->insert_jurnal_pengeluaran($this->input->post("IDPengeluaran"), "Batal Pengeluaran " . $row->keterangan, $row->total_pengeluaran, true);
            }
        } else {
            $penggajian = $this->db->get_where("laporan_penggajian", array("IDPenggajian" => $this->input->post("IDPengeluaran")))->row();
            $detail_penggajian = $this->db->get_where("detail_penggajian", array("IDPenggajian" => $this->input->post("IDPengeluaran")))->result();
//            print_r($detail_penggajian); exit;
            foreach ($detail_penggajian as $row) {
                $sql = "UPDATE cabang SET saldo = saldo + " . $row->total_gaji . " WHERE IDCabang = " . $IDCabang . ";";
                $this->db->query($sql);

                if ($penggajian->keterangan == "gaji") {
                    $sql = "UPDATE sales SET totalGaji = totalGaji + " . $row->total_gaji . " WHERE IDSales = " . $row->IDSales . ";";
                } else {
                    $sql = "UPDATE sales SET totalKomisi = totalKomisi + " . $row->total_gaji . " WHERE IDSales = " . $row->IDSales . ";";
                }
                $this->db->query($sql);

                $this->Jurnal_model->insert_jurnal_pengeluaran($this->input->post("IDPengeluaran"), "Batal Pengeluaran " . $penggajian->keterangan, $row->total_gaji, true);
            }
        }
    }

    function get_pembatalan_pengeluaran() {
        $SQL = "SELECT tanggal, tipe, total, CASE WHEN tipe = '1' THEN (SELECT KodePengeluaran FROM laporan_pengeluaran WHERE laporan_pengeluaran.IDPengeluaran = laporan_pembatalan_pengeluaran.IDPengeluaran) ELSE (SELECT KodePenggajian FROM laporan_penggajian WHERE laporan_penggajian.IDPenggajian = laporan_pembatalan_pengeluaran.IDPengeluaran) END as KodePengeluaran FROM laporan_pembatalan_pengeluaran";
        return $this->db->query($SQL)->result();
    }

    function start_trans() {
        $this->db->trans_begin();
        $this->db->trans_strict(FALSE);
    }

    function end_trans($heading) {
        // DB Transaction
        $error = $this->db->error();
        if ($error['message'] != "") {
            // generate an error... or use the log_message() function to log your error
            $this->db->trans_rollback();
            $data = array(
                'username' => $this->session->userdata('Username'),
                'heading' => 'Error at ' . $heading,
                'message' => $error['message'],
                'date' => date('Y-m-d H:i:s')
            );
            $this->db->insert("logs", $data);
            $this->session->set_flashdata("status", "Error Found! segera hubungi MyOcreata");
            $this->session->set_flashdata("status_mt", "Error Found! segera hubungi MyOcreata");
        } else {
            $this->db->trans_commit();
        }
    }

}
