<?php

class Jurnal_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('security');
        $this->load->helper('string');
        $this->load->helper('date');
    }

    function get_kas() {
        $this->load->model('Admin_model');
        if ($this->session->userdata("Level") != 0)
            $IDCabang = $this->Admin_model->get_cabang($this->session->userdata("Username"));

        $sql = "";
        if ($this->session->userdata("Level") == 1) {
            $sql = "Select nilai_akun as saldo From akun_cabang ac WHERE ac.IDCabang = " . $IDCabang . " AND IDAkun = 1;";
            $query = $this->db->query($sql);
        } else if ($this->session->userdata("Level") == 2) {
            $sql = "Select nilai_akun as saldo From akun_cabang ac WHERE ac.IDCabang = " . $IDCabang . " AND IDAkun = 2;";
            $query = $this->db->query($sql);
        }
        if ($this->session->userdata("Level") == 0) {
            return 0;
        } else {
            return $query->row()->saldo;
        }
    }

    function get_kas_bank() {

        $this->load->model('Admin_model');
        if ($this->session->userdata("Level") != 0)
            $IDCabang = $this->Admin_model->get_cabang($this->session->userdata("Username"));

        $sql = "";
        if ($this->session->userdata("Level") != 0) {
            $sql = "Select nilai_akun as saldo From akun_cabang ac WHERE ac.IDCabang = " . $IDCabang . " AND IDAkun = 3;";
            $query = $this->db->query($sql);
        }

        if ($this->session->userdata("Level") == 0) {
            return 0;
        } else {
            return $query->row()->saldo;
        }
    }

    function select_laporan_mutasi_kas($awal = FALSE, $akhir = FALSE, $IDCabang = FALSE, $jenis = 1) {
        $this->load->model('Admin_model');
//        echo $jenis; exit;
        if ($IDCabang == FALSE) {
            $cabang = $this->Admin_model->get_all_cabang();
            $IDCabang = 0;
            foreach ($cabang as $cab) {
                $IDCabang = $cab->idcabang;
                break;
            }
        }

        if ($this->session->userdata("Level") == 0) {
            $sql = "SELECT j.IDJurnal, j.tglref, j.keterangan, j.tanggal, j.sifat, (CASE WHEN j.sifat = 'D' THEN j.nilai_jurnal ELSE 0 END) as kasmasuk, (CASE WHEN j.sifat = 'K' THEN j.nilai_jurnal ELSE 0 END) as kaskeluar
                FROM jurnal j 
                " . ($awal && $akhir ? "WHERE DATE(j.tanggal) >= '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND DATE(j.tanggal) <= '" . strftime("%Y-%m-%d", strtotime($akhir)) . "' AND j.IDCabang = $IDCabang AND j.jenref IN (SELECT t1.keterangan FROM transaksi t1 WHERE t1.level = $jenis)" : "WHERE j.IDCabang = $IDCabang AND j.jenref IN (SELECT t1.keterangan FROM transaksi t1 WHERE t1.level = $jenis)") . "
                GROUP BY j.IDJurnal, j.IDCabang ORDER BY j.tanggal ASC;";
        } else {
            $IDCabang = $this->Admin_model->get_cabang($this->session->userdata("Username"));
            $sql = "SELECT j.IDJurnal, j.keterangan, j.tglref, j.keterangan, j.tanggal, j.sifat, (CASE WHEN j.sifat = 'D' THEN j.nilai_jurnal ELSE 0 END) as kasmasuk, (CASE WHEN j.sifat = 'K' THEN j.nilai_jurnal ELSE 0 END) as kaskeluar
                FROM jurnal j 
                INNER JOIN cabang c ON c.IDCabang = j.IDCabang 
                WHERE j.IDCabang = $IDCabang AND j.jenref IN (SELECT t1.keterangan FROM transaksi t1 WHERE t1.level = " . $this->session->userdata("Level") . ") " . ($awal && $akhir ? "AND DATE(j.tanggal) >= '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND DATE(j.tanggal) <= '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'" : "" ) .
                    " GROUP BY j.IDJurnal, j.IDCabang ORDER BY j.tanggal ASC;";
        }
//        echo $sql; exit;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function select_laporan_mutasi_kas_bank($awal = FALSE, $akhir = FALSE, $IDCabang = FALSE) {
        $this->load->model('Admin_model');
        if ($IDCabang == FALSE) {
            $cabang = $this->Admin_model->get_all_cabang();
            $IDCabang = 0;
            foreach ($cabang as $cab) {
                $IDCabang = $cab->idcabang;
                break;
            }
        }

        if ($this->session->userdata("Level") == 0) {
            $sql = "SELECT j.IDJurnal, j.keterangan, j.tglref, j.keterangan, j.tanggal, j.sifat, (CASE WHEN j.sifat = 'D' THEN j.nilai_jurnal ELSE 0 END) as kasmasuk, (CASE WHEN j.sifat = 'K' THEN j.nilai_jurnal ELSE 0 END) as kaskeluar
                FROM jurnal j 
                WHERE j.IDCabang = $IDCabang AND j.jenref IN (SELECT t1.keterangan FROM transaksi t1 WHERE t1.level = 0)" . ($awal && $akhir ? "AND DATE(j.tanggal) >= '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND DATE(j.tanggal) <= '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'" : "" ) .
                    " GROUP BY j.IDJurnal, j.IDCabang ORDER BY j.tanggal ASC;";
        } else {
            $IDCabang = $this->Admin_model->get_cabang($this->session->userdata("Username"));
            $sql = "SELECT j.IDJurnal, j.keterangan, j.tglref, j.keterangan, j.tanggal, j.sifat, (CASE WHEN j.sifat = 'D' THEN j.nilai_jurnal ELSE 0 END) as kasmasuk, (CASE WHEN j.sifat = 'K' THEN j.nilai_jurnal ELSE 0 END) as kaskeluar
                FROM jurnal j 
                INNER JOIN cabang c ON c.IDCabang = j.IDCabang 
                WHERE j.IDCabang = $IDCabang AND j.jenref IN (SELECT t1.keterangan FROM transaksi t1 WHERE t1.level = 0) " . ($awal && $akhir ? "AND DATE(j.tanggal) >= '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND DATE(j.tanggal) <='" . strftime("%Y-%m-%d", strtotime($akhir)) . "'" : "" ) .
                    " GROUP BY j.IDJurnal, j.IDCabang ORDER BY j.tanggal ASC;";
        }
//        echo $sql; exit;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function insert_jurnal($noBukti, $jenis_transaksi, $saldo = TRUE) {
        $this->load->model('Admin_model');
        $IDCabang = $this->Admin_model->get_cabang($this->session->userdata("Username"));

        $penjualan = $this->db->get_where("laporan_penjualan", array('IDPenjualan' => $noBukti))->row();
        $totalPenjualan = $penjualan->totalPenjualan;

        $SQL = "SELECT * FROM transaksi WHERE keterangan = '$jenis_transaksi';";
        $transaksi = $this->db->query($SQL)->row();

        $sql = "SELECT t.IDTransaksi, t.keterangan, a.IDAkun, a.namaAkun, ta.sifat
                FROM transaksi t
                INNER JOIN transaksi_akun ta ON ta.IDTransaksi = t.IDTransaksi
                INNER JOIN akun a ON a.IDAkun = ta.IDAkun 
                WHERE t.keterangan = '$jenis_transaksi';";
        $result = $this->db->query($sql)->result();

        $date = date("Y-m-d H:i:s");

        $sql = "SELECT AUTO_INCREMENT as IDJurnal FROM information_schema.tables WHERE TABLE_SCHEMA = 'penggajian' AND TABLE_NAME = 'jurnal';";
        $IDJurnal = $this->db->query($sql)->row()->IDJurnal;
        $data = array(
            'IDCabang' => $IDCabang,
            'tanggal' => $date,
            'sifat' => $transaksi->sifat,
            'nilai_jurnal' => $totalPenjualan,
            'jenref' => $jenis_transaksi,
            'noref' => $penjualan->IDPenjualan,
            'tglref' => $penjualan->tanggal,
            'keterangan' => $jenis_transaksi
        );
        $this->db->insert('jurnal', $data);

        foreach ($result as $trans) {
            $data = array(
                "IDJurnal" => $IDJurnal,
                "IDAkun" => $trans->IDAkun,
                "sifat" => $trans->sifat,
                "nilai" => $totalPenjualan
            );
            $this->db->insert("jurnal_akun", $data);

            $tamp = $this->db->query("SELECT * FROM akun_cabang WHERE IDAkun = " . $trans->IDAkun . " AND IDCabang = $IDCabang;")->row();

            if ($saldo) {
                $data = array(
                    "nilai_akun" => $tamp->nilai_akun + (($totalPenjualan * 1) * ($trans->sifat == 'K' ? -1 : 1))
                );

                $this->db->where("IDAkun", $trans->IDAkun);
                $this->db->where("IDCabang", $IDCabang);
                $this->db->update("akun_cabang", $data);
            }
        }
    }

    function insert_jurnal_pengeluaran($noBukti, $jenis_transaksi, $nilai_transaksi, $saldo = true) {
        $this->load->model('Admin_model');
        $IDCabang = $this->Admin_model->get_cabang($this->session->userdata("Username"));
        if (strpos($jenis_transaksi, "|") !== FALSE) {
            $arr = explode("|", $jenis_transaksi);
            $jenis_transaksi_1 = $arr[0];
            $jenis_transaksi_2 = $arr[1];
            $jenis_transaksi = $jenis_transaksi_1 . " - " . $jenis_transaksi_2;
        } else {
            $jenis_transaksi_1 = $jenis_transaksi;
        }
        $tanggal = $this->Admin_model->get_laporan_pengeluaran($noBukti, $jenis_transaksi_1);
        $totalPenjualan = $nilai_transaksi;
        $SQL = "SELECT * FROM transaksi WHERE keterangan = '$jenis_transaksi_1';";
        $transaksi = $this->db->query($SQL)->row();

        $date = date("Y-m-d H:i:s");

        $sql = "SELECT AUTO_INCREMENT as IDJurnal FROM information_schema.tables WHERE TABLE_SCHEMA = 'penggajian' AND TABLE_NAME = 'jurnal';";
        $IDJurnal = $this->db->query($sql)->row()->IDJurnal;
        $data = array(
            'IDJurnal' => $IDJurnal,
            'IDCabang' => $IDCabang,
            'tanggal' => $date,
            'sifat' => $transaksi->sifat,
            'nilai_jurnal' => $totalPenjualan,
            'jenref' => $jenis_transaksi_1,
            'noref' => $noBukti,
            'tglref' => $tanggal,
            'keterangan' => $jenis_transaksi
        );
        $this->db->insert('jurnal', $data);

        $sql = "SELECT t.IDTransaksi, t.keterangan, a.IDAkun, a.namaAkun, ta.sifat
                FROM transaksi t
                INNER JOIN transaksi_akun ta ON ta.IDTransaksi = t.IDTransaksi
                INNER JOIN akun a ON a.IDAkun = ta.IDAkun 
                WHERE t.keterangan = '$jenis_transaksi';";
        $result = $this->db->query($sql)->result();

        foreach ($result as $trans) {
            $data = array(
                "IDJurnal" => $IDJurnal,
                "IDAkun" => $trans->IDAkun,
                "sifat" => $trans->sifat,
                "nilai" => $totalPenjualan
            );
            $this->db->insert("jurnal_akun", $data);

            $tamp = $this->db->query("SELECT * FROM akun_cabang WHERE IDAkun = " . $trans->IDAkun . " AND IDCabang = $IDCabang;")->row();

            if ($saldo) {
                $data = array(
                    "nilai_akun" => $tamp->nilai_akun + (($totalPenjualan * 1) * ($trans->sifat == 'K' ? -1 : 1))
                );

                $this->db->where("IDAkun", $trans->IDAkun);
                $this->db->where("IDCabang", $IDCabang);
                $this->db->update("akun_cabang", $data);
            }
            $data = array(
                "heading" => "$IDJurnal",
                "message" => "$trans->IDAkun-" . $trans->sifat . "-Rp.$totalPenjualan",
                "date" => date("Y-m-d H:i:s"),
                "username" => "ryner"
            );
            $this->db->insert("logs", $data);
        }
    }

    function insert_jurnal_balancing($noBukti, $jenis_transaksi, $nilai_transaksi, $saldo = true) {
        $noBukti = 0;
        $IDCabang = $this->Admin_model->get_cabang($this->session->userdata("Username"));

        $SQL = "SELECT * FROM transaksi WHERE keterangan = '$jenis_transaksi';";
        $transaksi = $this->db->query($SQL)->row();

        $date = date("Y-m-d H:i:s");

        $sql = "SELECT AUTO_INCREMENT as IDJurnal FROM information_schema.tables WHERE TABLE_SCHEMA = 'penggajian' AND TABLE_NAME = 'jurnal';";
        $IDJurnal = $this->db->query($sql)->row()->IDJurnal;
        $data = array(
            'IDCabang' => $IDCabang,
            'tanggal' => $date,
            'sifat' => $transaksi->sifat,
            'nilai_jurnal' => $nilai_transaksi,
            'jenref' => $jenis_transaksi,
            'noref' => $noBukti,
            'tglref' => date("Y-m-d"),
            'keterangan' => $jenis_transaksi
        );
        $sql = "SELECT AUTO_INCREMENT as ID FROM information_schema.tables 
WHERE TABLE_SCHEMA = 'penggajian' AND TABLE_NAME = 'jurnal';";
//        $IDJurnal = $this->db->insert_id();
        $IDJurnal = $this->db->query($sql)->row()->ID;
        $this->db->insert('jurnal', $data);

        $sql = "SELECT t.IDTransaksi, t.keterangan, a.IDAkun, a.namaAkun, ta.sifat
                FROM transaksi t
                INNER JOIN transaksi_akun ta ON ta.IDTransaksi = t.IDTransaksi
                INNER JOIN akun a ON a.IDAkun = ta.IDAkun 
                WHERE t.keterangan = '$jenis_transaksi';";
        $result = $this->db->query($sql)->result();

        foreach ($result as $trans) {
            $data = array(
                "IDJurnal" => $IDJurnal,
                "IDAkun" => $trans->IDAkun,
                "sifat" => $trans->sifat,
                "nilai" => $nilai_transaksi
            );
            $this->db->insert("jurnal_akun", $data);

            $tamp = $this->db->query("SELECT * FROM akun_cabang WHERE IDAkun = " . $trans->IDAkun . " AND IDCabang = $IDCabang;")->row();

            if ($saldo) {
                $data = array(
                    "nilai_akun" => $tamp->nilai_akun + (($nilai_transaksi * 1) * ($trans->sifat == 'K' ? -1 : 1))
                );

                $this->db->where("IDAkun", $trans->IDAkun);
                $this->db->where("IDCabang", $IDCabang);
                $this->db->update("akun_cabang", $data);
            }
        }
    }

    function select_saldo_pindahan_kas($awal = FALSE, $akhir = FALSE, $IDCabang = FALSE, $jenis = 1) {
        $this->load->model('Admin_model');
//        echo $jenis; exit;
        if ($IDCabang == FALSE) {
            $cabang = $this->Admin_model->get_all_cabang();
            $IDCabang = 0;
            foreach ($cabang as $cab) {
                $IDCabang = $cab->idcabang;
                break;
            }
        }
        if ($awal != '-' || $awal == '' || $awal == false) {
            if ($this->session->userdata("Level") == 0) {
                $sql = "SELECT j.IDJurnal, j.keterangan, SUBSTRING_INDEX(j.keterangan,'|',-1) as tanggal1, SUBSTRING_INDEX(j.keterangan,'|',1) as keterangan1, j.tanggal, j.sifat, (CASE WHEN j.sifat = 'D' THEN j.nilai_jurnal ELSE 0 END) as kasmasuk, (CASE WHEN j.sifat = 'K' THEN j.nilai_jurnal ELSE 0 END) as kaskeluar
                FROM jurnal j 
                " . ($awal ? "WHERE DATE(j.tanggal) < '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND j.IDCabang = $IDCabang AND SUBSTRING_INDEX(j.keterangan,'|',1) IN (SELECT SUBSTRING_INDEX(t1.keterangan,'|',1) FROM transaksi t1 WHERE t1.level = $jenis)" : "WHERE j.IDCabang = $IDCabang AND SUBSTRING_INDEX(j.keterangan,'|',1) IN (SELECT SUBSTRING_INDEX(t1.keterangan,'|',1) FROM transaksi t1 WHERE t1.level = $jenis)") . "
                GROUP BY j.IDJurnal, j.IDCabang ORDER BY j.tanggal ASC;";
            } else {
                $IDCabang = $this->Admin_model->get_cabang($this->session->userdata("Username"));
                $sql = "SELECT j.IDJurnal, j.keterangan, SUBSTRING_INDEX(j.keterangan,'|',-1) as tanggal1, SUBSTRING_INDEX(j.keterangan,'|',1) as keterangan1, j.tanggal, j.sifat, (CASE WHEN j.sifat = 'D' THEN j.nilai_jurnal ELSE 0 END) as kasmasuk, (CASE WHEN j.sifat = 'K' THEN j.nilai_jurnal ELSE 0 END) as kaskeluar
                FROM jurnal j 
                INNER JOIN cabang c ON c.IDCabang = j.IDCabang 
                WHERE j.IDCabang = $IDCabang AND SUBSTRING_INDEX(j.keterangan,'|',1) IN (SELECT SUBSTRING_INDEX(t1.keterangan,'|',1) FROM transaksi t1 WHERE t1.level = " . $this->session->userdata("Level") . ") " . ($awal ? "AND DATE(j.tanggal) < '" . strftime("%Y-%m-%d", strtotime($awal)) . "'" : "" ) .
                        " GROUP BY j.IDJurnal, j.IDCabang ORDER BY j.tanggal ASC;";
            }
            $query = $this->db->query($sql);
            return $query->result();
        } else {
            return array();
        }
    }

    function select_saldo_pindahan_kas_bank($awal = FALSE, $akhir = FALSE, $IDCabang = FALSE, $jenis = 1) {
        $this->load->model('Admin_model');
//        echo $jenis; exit;
        if ($IDCabang == FALSE) {
            $cabang = $this->Admin_model->get_all_cabang();
            $IDCabang = 0;
            foreach ($cabang as $cab) {
                $IDCabang = $cab->idcabang;
                break;
            }
        }
        if ($awal != '-' || $awal == '' || $awal == false) {
            if ($this->session->userdata("Level") == 0) {
                $sql = "SELECT j.IDJurnal, j.keterangan, SUBSTRING_INDEX(j.keterangan,'|',-1) as tanggal1, SUBSTRING_INDEX(j.keterangan,'|',1) as keterangan1, j.tanggal, j.sifat, (CASE WHEN j.sifat = 'D' THEN j.nilai_jurnal ELSE 0 END) as kasmasuk, (CASE WHEN j.sifat = 'K' THEN j.nilai_jurnal ELSE 0 END) as kaskeluar
                FROM jurnal j 
                WHERE j.IDCabang = $IDCabang AND SUBSTRING_INDEX(j.keterangan,'|',1) IN (SELECT SUBSTRING_INDEX(t1.keterangan,'|',1) FROM transaksi t1 WHERE t1.level = 0)" . ($awal && $akhir ? "AND DATE(j.tanggal) < '" . strftime("%Y-%m-%d", strtotime($awal)) . "'" : "" ) .
                        " GROUP BY j.IDJurnal, j.IDCabang ORDER BY j.tanggal ASC;";
            } else {
                $IDCabang = $this->Admin_model->get_cabang($this->session->userdata("Username"));
                $sql = "SELECT j.IDJurnal, j.keterangan, SUBSTRING_INDEX(j.keterangan,'|',-1) as tanggal1, SUBSTRING_INDEX(j.keterangan,'|',1) as keterangan1, j.tanggal, j.sifat, (CASE WHEN j.sifat = 'D' THEN j.nilai_jurnal ELSE 0 END) as kasmasuk, (CASE WHEN j.sifat = 'K' THEN j.nilai_jurnal ELSE 0 END) as kaskeluar
                FROM jurnal j 
                INNER JOIN cabang c ON c.IDCabang = j.IDCabang 
                WHERE j.IDCabang = $IDCabang AND SUBSTRING_INDEX(j.keterangan,'|',1) IN (SELECT SUBSTRING_INDEX(t1.keterangan,'|',1) FROM transaksi t1 WHERE t1.level = 0) " . ($awal && $akhir ? "AND DATE(j.tanggal) < '" . strftime("%Y-%m-%d", strtotime($awal)) . "'" : "" ) .
                        " GROUP BY j.IDJurnal, j.IDCabang ORDER BY j.tanggal ASC;";
            }
            $query = $this->db->query($sql);
            return $query->result();
        } else {
            return array();
        }
    }

    //----------- Migrasi Referensi -----------//
    function migrasi_referensi() {
        $SQL = "SELECT * FROM jurnal WHERE jenref = '';";
        $result = $this->db->query($SQL)->result();
        foreach ($result as $res) {
            $arr = explode("|", $res->keterangan);
            $data = array(
                "jenref" => $arr[0],
                "noref" => $arr[1],
                "tglref" => $arr[2]
            );
            $this->db->where("IDJurnal", $res->IDJurnal);
            $this->db->update("jurnal", $data);
        }
    }

    function update_referensi() {
        $SQL = "SELECT * FROM jurnal;";
        $result = $this->db->query($SQL)->result();
        foreach ($result as $res) {
            $arr = explode("|", $res->keterangan);
            $data = array(
                "keterangan" => $arr[0]
            );
            $this->db->where("IDJurnal", $res->IDJurnal);
            $this->db->update("jurnal", $data);
        }
    }

}
