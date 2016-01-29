<?php

class Laporan_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('security');
        $this->load->helper('string');
        $this->load->helper('date');
    }

    function get_cabang_id($idCabang) {
        if ($idCabang == 0) {
            return "Semua Cabang";
        } else {
            $res = $this->db->get_where("cabang", array('IDCabang' => $idCabang))->row();
            return $res->provinsi . " - " . $res->kabupaten;
        }
    }

    function select_laporan() {
        $sql = "";
        if ($this->session->userdata("Level") == 0) {
            $sql = "SELECT lp.IDPenjualan as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.keterangan as keterangan, a.username as username, lp.totalPenjualan as totalPenjualan, lp.totalKomisi as totalKomisi, lp.status_kas, IFNULL(lb.IDPembatalan, 0) as IDPembatalan
                FROM cabang c
                INNER JOIN laporan_penjualan lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin 
                LEFT JOIN laporan_pembatalan_penjualan lb ON lb.IDPenjualan = lp.IDPenjualan WHERE lb.IDPembatalan IS NULL  AND (lp.tanggal) >= '" . date("Y-m-1") . "' AND (lp.tanggal <= '" . date("Y-m-t") . "')";
            if ($this->input->post("btn_submit")) {
                if ($this->input->post("cabang") != 0) {
                    $sql.= " AND c.IDCabang = " . $this->input->post("cabang") . ";";
                }
            }
        } else if ($this->session->userdata("Level") == 1) {
            $sql = "SELECT lp.IDPenjualan as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.keterangan as keterangan, a.username as username, lp.totalPenjualan as totalPenjualan, lp.totalKomisi as totalKomisi, lp.status_kas, IFNULL(lb.IDPembatalan, 0) as IDPembatalan
                FROM cabang c
                INNER JOIN laporan_penjualan lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin 
                LEFT JOIN laporan_pembatalan_penjualan lb ON lb.IDPenjualan = lp.IDPenjualan 
                WHERE a.username = '" . $this->session->userdata("Username") . "' AND (lp.tanggal BETWEEN '" . date("Y-m-1") . "' AND '" . date("Y-m-t") . "')";
        } else if ($this->session->userdata("Level") == 2) {
            $sql = "SELECT lp.IDPenjualan as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.keterangan as keterangan, a.username as username, lp.totalPenjualan as totalPenjualan, lp.totalKomisi as totalKomisi, lp.status_kas, IFNULL(lb.IDPembatalan, 0) as IDPembatalan
                FROM cabang c
                INNER JOIN laporan_penjualan lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin_kantor 
                LEFT JOIN laporan_pembatalan_penjualan lb ON lb.IDPenjualan = lp.IDPenjualan WHERE lb.IDPembatalan IS NULL AND
                a.username = '" . $this->session->userdata("Username") . "'";
//                a.username = '" . $this->session->userdata("Username") . "' AND (lp.tanggal BETWEEN '" . date("Y-m-1") . "' AND '" . date("Y-m-t") . "')";
        } else {
            $sql = "SELECT lp.IDPenjualan as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.keterangan as keterangan, a.username as username, lp.totalPenjualan as totalPenjualan, lp.totalKomisi as totalKomisi, lp.status_kas, IFNULL(lb.IDPembatalan, 0) as IDPembatalan
                FROM cabang c
                INNER JOIN laporan_penjualan lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin 
                LEFT JOIN laporan_pembatalan_penjualan lb ON lb.IDPenjualan = lp.IDPenjualan WHERE lb.IDPembatalan IS NULL AND
                (lp.tanggal BETWEEN '" . date("Y-m-1") . "' AND '" . date("Y-m-t") . "')";
        }
//        echo $sql; exit;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function select_laporan_pembatalan() {
        $sql = "";
        if ($this->session->userdata("Level") == 0) {
            $sql = "SELECT lp.IDPenjualan as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.keterangan as keterangan, a.username as username, lp.totalPenjualan as totalPenjualan, lp.totalKomisi as totalKomisi, lp.status_kas
                FROM cabang c
                INNER JOIN laporan_penjualan lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin";
            if ($this->input->post("btn_pilih")) {
                if ($this->input->post("cabang") != 0) {
                    $sql.= " WHERE c.IDCabang = " . $this->input->post("cabang") . " AND lp.status_kas = 0 AND AND lp.IDPenjualan NOT IN (SELECT lb.IDPenjualan FROM laporan_pembatalan_penjualan lb);";
                } else {
                    $sql .= "WHERE lp.status_kas = 0 AND lp.IDPenjualan NOT IN (SELECT lb.IDPenjualan FROM laporan_pembatalan_penjualan lb);";
                }
            } else {
                $sql .= "WHERE lp.status_kas = 0 AND lp.IDPenjualan NOT IN (SELECT lb.IDPenjualan FROM laporan_pembatalan_penjualan lb);";
            }
        } else if ($this->session->userdata("Level") == 1) {
            $sql = "SELECT lp.IDPenjualan as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.keterangan as keterangan, a.username as username, lp.totalPenjualan as totalPenjualan, lp.totalKomisi as totalKomisi, lp.status_kas
                FROM cabang c
                INNER JOIN laporan_penjualan lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin WHERE a.username = '" . $this->session->userdata("Username") . "' AND lp.status_kas = 0 AND lp.IDPenjualan NOT IN (SELECT lb.IDPenjualan FROM laporan_pembatalan_penjualan lb) AND (lp.tanggal BETWEEN '" . date("Y-m-1") . "' AND '" . date("Y-m-t") . "');";
        } else if ($this->session->userdata("Level") == 2) {
            $sql = "SELECT lp.IDPenjualan as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.keterangan as keterangan, a.username as username, lp.totalPenjualan as totalPenjualan, lp.totalKomisi as totalKomisi, lp.status_kas
                FROM cabang c
                INNER JOIN laporan_penjualan lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin_kantor WHERE a.username = '" . $this->session->userdata("Username") . "' AND lp.status_kas = 0 AND lp.IDPenjualan NOT IN (SELECT lb.IDPenjualan FROM laporan_pembatalan_penjualan lb) AND (lp.tanggal BETWEEN '" . date("Y-m-1") . "' AND '" . date("Y-m-t") . "');";
        }
//        echo $sql; exit;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function select_laporan_pembatalan_pengeluaran() {
        $sql = "";
        if ($this->session->userdata("Level") == 0) {
            $sql = "SELECT lp.IDPengeluaran as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.KodePengeluaran, a.username as username, lp.totalPengeluaran as totalPengeluaran
                FROM cabang c
                INNER JOIN laporan_pengeluaran lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin";
            if ($this->input->post("btn_pilih")) {
                if ($this->input->post("cabang") != 0) {
                    $sql.= " WHERE c.IDCabang = " . $this->input->post("cabang") . " AND lp.IDPengeluaran NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 1);";
                } else {
                    $sql .= "WHERE lp.IDPengeluaran NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 1);";
                }
            } else {
                $sql .= "WHERE lp.IDPengeluaran NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 1);";
            }
        } else if ($this->session->userdata("Level") == 1) {
            $sql = "SELECT lp.IDPengeluaran as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.KodePengeluaran, a.username as username, lp.totalPengeluaran as totalPengeluaran
                FROM cabang c
                INNER JOIN laporan_pengeluaran lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin WHERE a.username = '" . $this->session->userdata("Username") . "' AND lp.IDPengeluaran NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 1) AND (lp.tanggal BETWEEN '" . date("Y-m-1") . "' AND '" . date("Y-m-t") . "');";
        } else if ($this->session->userdata("Level") == 2) {
            $sql = "SELECT lp.IDPengeluaran as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.KodePengeluaran, a.username as username, lp.totalPengeluaran as totalPengeluaran
                FROM cabang c
                INNER JOIN laporan_pengeluaran lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin_kantor WHERE a.username = '" . $this->session->userdata("Username") . "'  AND lp.IDPengeluaran NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 1);";
        }
//        echo $sql; exit;        
        $query = $this->db->query($sql);
        return $query->result();
    }

    function select_laporan_pembatalan_gaji() {
        $sql = "";
        if ($this->session->userdata("Level") == 0) {
            $sql = "SELECT lp.IDPenggajian as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.KodePenggajian, a.username as username, lp.totalPenggajian as totalPenggajian
                FROM cabang c
                INNER JOIN laporan_penggajian lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin";
            if ($this->input->post("btn_pilih")) {
                if ($this->input->post("cabang") != 0) {
                    $sql.= " WHERE c.IDCabang = " . $this->input->post("cabang") . " AND lp.IDPenggajian NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 2);";
                } else {
                    $sql .= "WHERE lp.IDPenggajian NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 2);";
                }
            } else {
                $sql .= "WHERE lp.IDPenggajian NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 2);";
            }
        } else if ($this->session->userdata("Level") == 1) {
            $sql = "SELECT lp.IDPenggajian as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.KodePenggajian, a.username as username, lp.totalPenggajian as totalPenggajian
                FROM cabang c
                INNER JOIN laporan_penggajian lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin WHERE a.username = '" . $this->session->userdata("Username") . "' AND lp.IDPenggajian NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 2) AND (lp.tanggal BETWEEN '" . date("Y-m-1") . "' AND '" . date("Y-m-t") . "');";
        } else if ($this->session->userdata("Level") == 2) {
            $sql = "SELECT lp.IDPenggajian as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.KodePenggajian, a.username as username, lp.totalPenggajian as totalPenggajian
                FROM cabang c
                INNER JOIN laporan_penggajian lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin_kantor WHERE a.username = '" . $this->session->userdata("Username") . "'  AND lp.IDPenggajian NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 2);";
        }   
        $query = $this->db->query($sql);
        return $query->result();
    }

    function select_laporan_batal($IDCabang = FALSE, $awal = FALSE, $akhir = FALSE) {
        if ($this->session->userdata("Level") != 0) {
            $sql = "SELECT lb.*,lp.tanggal as tanggal_jual,lp.IDPenjualan, lp.totalPenjualan, lp.IDCabang FROM laporan_pembatalan_penjualan lb INNER JOIN laporan_penjualan lp ON lp.IDPenjualan = lb.IDPenjualan 
                WHERE lp.IDCabang = " . $this->session->userdata("IDCabang");
            if ($awal && $akhir) {
                $sql .=" AND (lp.tanggal BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "')";
            } else {
                $sql .=" AND (lp.tanggal BETWEEN '" . date("Y-m-1") . "' AND '" . date("Y-m-t") . "')";
            }
        } else {
            $sql = "SELECT lb.*,lp.tanggal as tanggal_jual,lp.IDPenjualan, lp.totalPenjualan, lp.IDCabang FROM laporan_pembatalan_penjualan lb INNER JOIN laporan_penjualan lp ON lp.IDPenjualan = lb.IDPenjualan 
                ";
            if ($awal && $akhir) {
                $sql .=" WHERE (lp.tanggal BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "')";
            } else {
                $sql .=" WHERE (lp.tanggal BETWEEN '" . date("Y-m-1") . "' AND '" . date("Y-m-t") . "')";
            }
        }

        if ($IDCabang) {
            $sql.= "AND lp.IDCabang = " . $IDCabang . ";";
        }

        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_saldo_kantor($IDCabang = FALSE) {
        $sql = "SELECT s.*, c.provinsi, c.kabupaten
                FROM setoran_bank s
                INNER JOIN cabang c ON c.IDCabang = s.IDCabang
                WHERE " . ( $IDCabang ? " c.IDCabang = " . $IDCabang . " AND " : "" ) . " (s.tanggal BETWEEN '" . date("Y-m-1") . "' AND '" . date("Y-m-t") . "');";
        return $this->db->query($sql)->result();
    }

    function get_kas_bank($IDCabang = FALSE) {
        
    }

    function get_saldo_kas_bank($IDCabang = FALSE) {
        $sql = "SELECT t.*, c.provinsi, c.kabupaten
                FROM tarik_kas_bank t
                INNER JOIN cabang c ON c.IDCabang = t.IDCabang
                WHERE " . ($IDCabang ? "c.IDCabang = " . $IDCabang . " AND " : "" ) . " (t.tanggal BETWEEN '" . date("Y-m-1") . "' AND '" . date("Y-m-t") . "');";
        return $this->db->query($sql)->result();
    }

    function select_laporan_periode($awal = FALSE, $akhir = FALSE, $sort_asc) {
//        echo $sort_asc; exit;
//        echo $this->input->post("tanggal_awal"); exit;
//        var_dump($sort_asc);exit;
//        $awal = $this->input->post("tanggal_awal");
//        $akhir = $this->input->post("tanggal_akhir");
        if ($this->input->post("cabang")) {
            if ($this->input->post("cabang") == 0) {
                $sql = "SELECT lp.IDPenjualan as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.keterangan as keterangan, a.username as username, lp.totalPenjualan as totalPenjualan, lp.totalKomisi as totalKomisi
                FROM cabang c
                INNER JOIN laporan_penjualan lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin ";
                if ($awal && $akhir) {
                    $sql .= "Where DATE(tanggal) BETWEEN '" . (($awal)) . "' AND '" . (($akhir)) . "' AND lp.IDPenjualan NOT IN (SELECT lb.IDPenjualan FROM laporan_pembatalan_penjualan lb)";
                } else {
                    $sql .= "WHERE lp.IDPenjualan NOT IN (SELECT lb.IDPenjualan FROM laporan_pembatalan_penjualan lb)";
                }
//                print_r($sql);exit;
            } else {
//                $sql = "SELECT lp.IDPenjualan as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.keterangan as keterangan, a.username as username, lp.totalPenjualan as totalPenjualan, lp.totalKomisi as totalKomisi
//                FROM cabang c
//                INNER JOIN laporan_penjualan lp ON c.IDCabang = lp.IDCabang 
//                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin
//                Where lp.IDCabang = " . $this->input->post("cabang");
//                if ($awal && $akhir) {
//                    $sql .= " AND DATE(tanggal) BETWEEN '" . (($awal)) . "' AND '" . (($akhir)) . "' AND lp.IDPenjualan NOT IN (SELECT lb.IDPenjualan FROM laporan_pembatalan_penjualan lb)";
//                } else {
//                    $sql .= " AND lp.IDPenjualan NOT IN (SELECT lb.IDPenjualan FROM laporan_pembatalan_penjualan lb)";
//                }
                $sql = "SELECT lp.IDPenjualan as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.keterangan as keterangan, a.username as username, lp.totalPenjualan as totalPenjualan, lp.totalKomisi as totalKomisi
                FROM cabang c
                INNER JOIN laporan_penjualan lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin WHERE lp.IDPenjualan NOT IN (SELECT lb.IDPenjualan FROM laporan_pembatalan_penjualan lb)";
                if ($awal && $akhir) {
                    $sql .= " AND c.IDCabang =" . $this->input->post("cabang") . " AND DATE(tanggal) BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'";
                }
//                print_r($sql);exit;
            }
        } else {
            if ($this->session->userdata("Level") == 0) {
                $sql = "SELECT lp.IDPenjualan as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.keterangan as keterangan, a.username as username, lp.totalPenjualan as totalPenjualan, lp.totalKomisi as totalKomisi
                FROM cabang c
                INNER JOIN laporan_penjualan lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin WHERE lp.IDPenjualan NOT IN (SELECT lb.IDPenjualan FROM laporan_pembatalan_penjualan lb)";
                if ($awal && $akhir) {
                    $sql .= "AND DATE(tanggal) BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'";
                }
//                print_r($sql);exit;
            } else {
//                echo $this->input->post("tanggal_awal"); exit;
                $sql = "SELECT lp.IDPenjualan as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, lp.keterangan as keterangan, a.username as username, lp.totalPenjualan as totalPenjualan, lp.totalKomisi as totalKomisi
                FROM cabang c
                INNER JOIN laporan_penjualan lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin
                Where c.IDCabang = " . $this->session->userdata("IDCabang") . " AND lp.IDPenjualan NOT IN (SELECT lb.IDPenjualan FROM laporan_pembatalan_penjualan lb) ";
                if ($awal && $akhir) {
                    $sql .= "AND DATE(tanggal) BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'";
                }
            }
        }
        if ($sort_asc) {
            $sql .= " ORDER BY tanggal ASC ";
        } else {
            $sql .= " ORDER BY tanggal DESC ";
        }
        $query = $this->db->query($sql);
        return $query->result();
    }

    function select_laporan_pengeluaran() {
        $sql = "";
        if ($this->session->userdata("Level") == 0) {
            $sql = "SELECT lp.IDPengeluaran as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, a.username as username, lp.totalPengeluaran as totalPengeluaran, lp.KodePengeluaran
                FROM cabang c
                INNER JOIN laporan_pengeluaran lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin";
            if ($this->input->post("btn_pilih")) {
                if ($this->input->post("cabang") != 0) {
                    $sql.= " WHERE c.IDCabang = " . $this->input->post("cabang") . " ";
                } else {
                    $sql.= " WHERE lp.IDPengeluaran NOT IN (SELECT IDPengeluaran FROM laporan_pembatalan_pengeluaran WHERE tipe = 1)";
                }
            } else {
                $sql.= " WHERE lp.IDPengeluaran NOT IN (SELECT IDPengeluaran FROM laporan_pembatalan_pengeluaran WHERE tipe = 1)";
            }
        } else if ($this->session->userdata("Level") == 1) {
            $sql = "SELECT lp.IDPengeluaran as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, a.username as username, lp.totalPengeluaran as totalPengeluaran, lp.KodePengeluaran
                FROM cabang c
                INNER JOIN laporan_pengeluaran lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin WHERE a.username = '" . $this->session->userdata("Username") . "' AND (lp.tanggal BETWEEN '" . date("Y-m-1") . "' AND '" . date("Y-m-t") . "') 
                lp.IDPengeluaran NOT IN (SELECT IDPengeluaran FROM laporan_pembatalan_pengeluaran WHERE tipe = 1)";
            
        } else if ($this->session->userdata("Level") == 2) {
            $sql = "SELECT lp.IDPengeluaran as idlaporan, lp.IDCabang as idcabang, lp.tanggal as tanggal, a.username as username, lp.totalPengeluaran as totalPengeluaran, lp.KodePengeluaran
                FROM cabang c
                INNER JOIN laporan_pengeluaran lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin_kantor WHERE a.username = '" . $this->session->userdata("Username") . "' AND lp.IDPengeluaran NOT IN (SELECT IDPengeluaran FROM laporan_pembatalan_pengeluaran WHERE tipe = 1)";
        }
        $sql.= " ORDER BY lp.tanggal DESC, lp.IDPengeluaran DESC, lp.KodePengeluaran DESC";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_laporan_gaji() {
        if ($this->session->userdata("Level") == 0) {
            $sql = "SELECT lp.*, c.provinsi, c.kabupaten, a.IDAdmin, c.saldo, a.username
                FROM cabang c
                INNER JOIN laporan_penggajian lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin_kantor 
                WHERE lp.keterangan = 'gaji' ";
            if ($this->input->post("btn_submit")) {
                if ($this->input->post("cabang")) {
                    $sql.= "AND c.IDCabang = " . $this->input->post('cabang') . "";
                }
            }
        } else {
            $sql = "SELECT lp.*, c.provinsi, c.kabupaten, a.IDAdmin, c.saldo, a.username
                FROM cabang c
                INNER JOIN laporan_penggajian lp ON c.IDCabang = lp.IDCabang
                INNER JOIN admin a ON a.IDAdmin = c.IDAdmin_kantor 
                WHERE a.username = '" . $this->session->userdata("Username") . "' AND lp.keterangan = 'gaji' AND (lp.tanggal BETWEEN '" . date("Y-m-1") . "' AND '" . date("Y-m-t") . "')";
        }
        $sql .= " ORDER BY lp.tanggal DESC, lp.IDPenggajian DESC, lp.KodePenggajian DESC;";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function cek_laporan_per_tanggal() {
        date_default_timezone_set('Asia/Jakarta');
        $isValue = "0";

        $tanggal_laporan = strftime("%Y-%m-%d", strtotime($this->input->post("tanggal")));

        $sql = "Select * From laporan_penjualan  Where tanggal = '$tanggal_laporan'";
        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $isValue = "1";
        }

        return $isValue;
    }

    function get_laporan_gaji_id($IDLaporan) {
        return $this->db->get_where("laporan_penggajian", array("IDPenggajian" => $IDLaporan))->row();
    }

    function get_detail_laporan_gaji($IDLaporan) {
        $sql = "SELECT dp.*, s.IDSales, s.nama, s.totalGaji
                FROM detail_penggajian dp
                INNER JOIN sales s ON s.IDSales = dp.IDSales
                WHERE dp.IDPenggajian = $IDLaporan;";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_sales($IDSales) {
        return $this->db->get_where("sales", array("IDSales" => $IDSales))->row();
    }

    function get_barang($IDBarang) {
        return $this->db->get_where("barang", array("IDBarang" => $IDBarang))->row();
    }

    function get_lokasi($IDLokasi) {
        return $this->db->query("SELECT l.IDLokasi as id_lokasi, l.desa, l.kecamatan as lokasi
                                FROM cabang c
                                INNER JOIN lokasi l ON l.IDCabang = c.IDCabang 
                                WHERE l.IDLokasi = $IDLokasi")->row();
    }

    function get_stok_cabang($username) {
        $cabang = $this->db->query("SELECT c.IDCabang FROM cabang c INNER JOIN admin a ON a.IDAdmin = c.IDAdmin WHERE a.username = '" . $username . "';")->row();
        $sql = "SELECT b.IDBarang, b.namaBarang, cb.jumlah FROM cabang c INNER JOIN cabang_barang cb ON cb.IDCabang = c.IDCabang INNER JOIN barang b ON b.IDBarang = cb.IDBarang WHERE c.IDCabang = " . $cabang->IDCabang;
        return $this->db->query($sql)->result();
    }

    function select_top_sales() {
        $sql = "SELECT * 
                FROM(Select s.nama as nama, s.foto as foto, (Select SUM(j1.hargaJual) 
			FROM jual as j1
			Where j1.IDSales = j.IDSales) as totalpenjualan
                FROM sales s
                INNER JOIN jual as j ON j.IDSales = s.IDSales
                INNER JOIN laporan_penjualan as lp ON lp.IDPenjualan = j.IDPenjualan
                GROUP BY s.IDSales) as table1 
                ORDER BY table1.totalpenjualan DESC";
//        echo $sql; exit;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function select_top_sales_periode($awal, $akhir) {
        $awal = strftime("%Y-%m-%d", strtotime($awal));
        $akhir = strftime("%Y-%m-%d", strtotime($akhir));
        $sql = "Select s.nama as nama, (Select SUM(j1.hargaJual) 
			FROM jual as j1
			Where j1.IDSales = j.IDSales) as totalpenjualan
                FROM sales s
                INNER JOIN jual as j ON j.IDSales = s.IDSales
                INNER JOIN laporan_penjualan as lp ON lp.IDPenjualan = j.IDPenjualan
                Where DATE(lp.tanggal) BETWEEN '$awal' AND '$akhir'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /* Daniel */

    function select_all_pengeluaran($awal = FALSE, $akhir = FALSE, $bulan = FALSE, $sort_asc) {
//        $sql = "SELECT * 
//                from ((
//                        SELECT laporan_pengeluaran.IDCabang as IDCabang,laporan_pengeluaran.tanggal, detail_pengeluaran.keterangan as keterangan, SUM(detail_pengeluaran.total_pengeluaran) as jumlah, detail_pengeluaran.keterangan_lanjut, admin.username,laporan_pengeluaran.KodePengeluaran as no_laporan
//                        FROM detail_pengeluaran 
//                        INNER JOIN laporan_pengeluaran on laporan_pengeluaran.IDPengeluaran = detail_pengeluaran.IDPengeluaran 
//                        INNER JOIN cabang ON cabang.IDCabang = laporan_pengeluaran.IDCabang 
//                        INNER JOIN admin ON admin.IDAdmin = cabang.IDAdmin_kantor 
//                        GROUP BY keterangan, keterangan_lanjut, laporan_pengeluaran.IDPengeluaran) 
//                        UNION (
//                        SELECT laporan_penggajian.IDCabang as IDCabang, detail_penggajian.Tanggal, CONCAT(laporan_penggajian.keterangan,' ', sales.nama) as keterangan, detail_penggajian.total_gaji as jumlah, '' as keterangan_lanjut, admin.username,laporan_penggajian.KodePenggajian as no_laporan
//                        FROM detail_penggajian 
//                        INNER JOIN sales on sales.IDSales = detail_penggajian.IDSales 
//                        INNER JOIN laporan_penggajian ON laporan_penggajian.IDPenggajian = detail_penggajian.IDPenggajian
//                        INNER JOIN cabang ON cabang.IDCabang = laporan_penggajian.IDCabang 
//                        INNER JOIN admin ON admin.IDAdmin = cabang.IDAdmin_kantor) 
//                        ORDER BY tanggal DESC) as table1 ";
        
        $sql = "SELECT * 
                from ((
                        SELECT laporan_pengeluaran.IDCabang as IDCabang,laporan_pengeluaran.tanggal, detail_pengeluaran.keterangan as keterangan, SUM(detail_pengeluaran.total_pengeluaran) as jumlah, detail_pengeluaran.keterangan_lanjut, admin.username,laporan_pengeluaran.KodePengeluaran as no_laporan
                        FROM detail_pengeluaran 
                        INNER JOIN laporan_pengeluaran on laporan_pengeluaran.IDPengeluaran = detail_pengeluaran.IDPengeluaran 
                        INNER JOIN cabang ON cabang.IDCabang = laporan_pengeluaran.IDCabang 
                        INNER JOIN admin ON admin.IDAdmin = cabang.IDAdmin_kantor 
                        WHERE laporan_pengeluaran.IDPengeluaran NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 1)
                        GROUP BY keterangan, keterangan_lanjut, laporan_pengeluaran.IDPengeluaran) 
                        UNION (
                        SELECT laporan_penggajian.IDCabang as IDCabang, detail_penggajian.Tanggal, CONCAT(laporan_penggajian.keterangan,' ', sales.nama) as keterangan, detail_penggajian.total_gaji as jumlah, '' as keterangan_lanjut, admin.username,laporan_penggajian.KodePenggajian as no_laporan
                        FROM detail_penggajian 
                        INNER JOIN sales on sales.IDSales = detail_penggajian.IDSales 
                        INNER JOIN laporan_penggajian ON laporan_penggajian.IDPenggajian = detail_penggajian.IDPenggajian
                        INNER JOIN cabang ON cabang.IDCabang = laporan_penggajian.IDCabang 
                        INNER JOIN admin ON admin.IDAdmin = cabang.IDAdmin_kantor
                        WHERE laporan_penggajian.IDPenggajian NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 2)) 
                        ORDER BY tanggal DESC) as table1 ";
        if ($awal && $akhir) {
            $sql.=" WHERE table1.tanggal BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'  ";
        } else if ($bulan) {
            $sql.=" WHERE month(table1.tanggal) = $bulan AND year(table1.tanggal) = year(now()) ";
        } else {
            $sql.=" WHERE month(table1.tanggal) = month(now()) AND year(table1.tanggal) = year(now()) ";
        }
        if ($this->input->post("cabang")) {
            if ($this->input->post("cabang") != 0) {
                $sql.=" AND table1.IDCabang = " . $this->input->post("cabang");
            }
        } else if ($this->session->userdata('Level') != 0) {
            $sql.=" AND table1.IDCabang = " . $this->session->userdata('IDCabang') . " ";
        }

        if ($sort_asc) {
            $sql.=" ORDER BY table1.tanggal ASC";
        } else {
            $sql.=" ORDER BY table1.tanggal DESC";
        }
        //print_r($sql);exit;                
        return $this->db->query($sql)->result();
    }

    function select_lain_lain($awal = FALSE, $akhir = FALSE, $bulan = FALSE, $sort_asc) {
        $sql = "SELECT laporan_pengeluaran.tanggal, detail_pengeluaran.keterangan, SUM(detail_pengeluaran.total_pengeluaran) as jumlah, detail_pengeluaran.keterangan_lanjut, laporan_pengeluaran.KodePengeluaran as no_laporan "
                . "FROM detail_pengeluaran INNER JOIN laporan_pengeluaran on laporan_pengeluaran.IDPengeluaran = detail_pengeluaran.IDPengeluaran ";
        if ($awal && $akhir) {
            $sql.=" WHERE tanggal BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'  ";
        } else if ($bulan) {
            $sql.=" WHERE month(tanggal) = $bulan AND year(tanggal) = year(now()) ";
        } else {
            $sql.=" WHERE month(tanggal) = month(now()) AND year(tanggal) = year(now()) ";
        }
        if ($this->input->post("cabang")) {
            if ($this->input->post("cabang") != 0) {
                $sql.=" AND laporan_pengeluaran.IDCabang = " . $this->input->post("cabang");
            }
        } else if ($this->session->userdata('Level') != 0) {
            $sql.=" AND laporan_pengeluaran.IDCabang = " . $this->session->userdata('IDCabang') . " ";
        }
        $sql.= " AND detail_pengeluaran.keterangan NOT IN ('Bensin', 'Makan', 'Parkir', 'Tol') GROUP BY keterangan, laporan_pengeluaran.tanggal, keterangan_lanjut ";

        if ($sort_asc) {
            $sql.=" ORDER BY tanggal ASC";
        } else {
            $sql.=" ORDER BY tanggal DESC";
        }
//        print_r($sql);
//        exit;
        return $this->db->query($sql)->result();
    }

    function select_gaji($jenis, $awal = FALSE, $akhir = FALSE, $bulan = FALSE, $sort_asc) {
        if ($this->input->post("cabang")) {
            $sql = "SELECT '' as keterangan_lanjut, detail_penggajian.Tanggal as tanggal, CONCAT(lp.keterangan, ' ', sales.nama) as keterangan,"
                    . " detail_penggajian.total_gaji as jumlah ,lp.KodePenggajian as no_laporan "
                    . "FROM detail_penggajian INNER JOIN sales on sales.IDSales = detail_penggajian.IDSales "
                    . "INNER JOIN laporan_penggajian lp ON lp.IDPenggajian = detail_penggajian.IDPenggajian ";
        } else {
            $sql = "SELECT '' as keterangan_lanjut, detail_penggajian.Tanggal as tanggal, CONCAT(lp.keterangan, ' ', sales.nama) as keterangan, "
                    . "detail_penggajian.total_gaji as jumlah ,lp.KodePenggajian as no_laporan "
                    . "FROM detail_penggajian INNER JOIN sales on sales.IDSales = detail_penggajian.IDSales "
                    . "INNER JOIN laporan_penggajian lp ON lp.IDPenggajian = detail_penggajian.IDPenggajian ";
        }
        if ($awal && $akhir) {
            $sql.=" WHERE detail_penggajian.tanggal BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'  ";
        } else if ($bulan) {
            $sql.=" WHERE month(detail_penggajian.tanggal) = $bulan AND year(detail_penggajian.tanggal) = year(now()) ";
        } else {
            $sql.=" WHERE month(detail_penggajian.tanggal) = month(now()) AND year(detail_penggajian.tanggal) = year(now()) ";
        }
        if ($this->input->post("cabang")) {
            if ($this->input->post("cabang") != 0) {
                $sql.=" AND lp.IDCabang = " . $this->input->post("cabang");
            }
        } else if ($this->session->userdata('Level') != 0) {
            $sql.=" AND lp.IDCabang = " . $this->session->userdata('IDCabang') . " ";
        }
        $sql.= "AND lp.keterangan = '$jenis' ";

        if ($sort_asc) {
            $sql.=" ORDER BY tanggal ASC";
        } else {
            $sql.=" ORDER BY tanggal DESC";
        }
//        print_r($sql); exit;
        return $this->db->query($sql)->result();
    }

    function select_per_jenis($jenis, $awal = FALSE, $akhir = FALSE, $bulan = FALSE, $sort_asc) {
        $sql = "SELECT laporan_pengeluaran.tanggal, SUM(detail_pengeluaran.total_pengeluaran) as jumlah, '' as keterangan_lanjut, laporan_pengeluaran.KodePengeluaran as no_laporan "
                . "FROM detail_pengeluaran INNER JOIN laporan_pengeluaran on laporan_pengeluaran.IDPengeluaran = detail_pengeluaran.IDPengeluaran ";
        if ($awal && $akhir) {
            $sql.=" WHERE tanggal BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'  ";
        } else if ($bulan) {
            $sql.=" WHERE month(tanggal) = $bulan AND year(tanggal) = year(now()) ";
        } else {
            $sql.=" WHERE month(tanggal) = month(now()) AND year(tanggal) = year(now()) ";
        }
        if ($this->input->post("cabang")) {
            if ($this->input->post("cabang") != 0) {
                $sql.=" AND laporan_pengeluaran.IDCabang = " . $this->input->post("cabang") . " ";
            }
        } else if ($this->session->userdata('Level') != 0) {
            $sql.=" AND laporan_pengeluaran.IDCabang = " . $this->session->userdata('IDCabang') . " ";
        }
        $sql.= "AND detail_pengeluaran.keterangan = '$jenis' GROUP BY tanggal ";

        if ($sort_asc) {
            $sql.=" ORDER BY tanggal ASC";
        } else {
            $sql.=" ORDER BY tanggal DESC";
        }
//        print_r($sql); exit;
        return $this->db->query($sql)->result();
    }

    function select_pengeluaran_kas($awal = FALSE, $akhir = FALSE, $bulan = FALSE) {
        $sql = "SELECT * 
                from ((
                        SELECT laporan_pengeluaran.IDCabang as IDCabang,laporan_pengeluaran.tanggal, detail_pengeluaran.keterangan as keterangan, SUM(detail_pengeluaran.total_pengeluaran) as jumlah, detail_pengeluaran.keterangan_lanjut, admin.username
                        FROM detail_pengeluaran 
                        INNER JOIN laporan_pengeluaran on laporan_pengeluaran.IDPengeluaran = detail_pengeluaran.IDPengeluaran 
                        INNER JOIN cabang on cabang.IDCabang = laporan_pengeluaran.IDCabang 
                        INNER JOIN admin on admin.IDAdmin = cabang.IDAdmin_kantor 
                        WHERE laporan_pengeluaran.IDPengeluaran NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 1)
                        GROUP BY keterangan, keterangan_lanjut, tanggal)
                        UNION (
                        SELECT laporan_penggajian.IDCabang as IDCabang, detail_penggajian.Tanggal, CONCAT(laporan_penggajian.keterangan,' ', sales.nama) as keterangan, detail_penggajian.total_gaji as jumlah, '' as keterangan_lanjut, admin.username
                        FROM detail_penggajian 
                        INNER JOIN sales on sales.IDSales = detail_penggajian.IDSales 
                        INNER JOIN laporan_penggajian ON laporan_penggajian.IDPenggajian = detail_penggajian.IDPenggajian
                        INNER JOIN cabang on cabang.IDCabang = laporan_penggajian.IDCabang 
                        INNER JOIN admin on admin.IDAdmin = cabang.IDAdmin_kantor
                        WHERE laporan_penggajian.IDPenggajian NOT IN (SELECT lb.IDPengeluaran FROM laporan_pembatalan_pengeluaran lb WHERE lb.tipe = 2)) 
                        ORDER BY tanggal DESC) as table1 ";
        if ($awal && $akhir) {
            $sql.=" WHERE table1.tanggal BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'  ";
        } else if ($bulan) {
            $sql.=" WHERE month(table1.tanggal) = $bulan AND year(table1.tanggal) = year(now()) ";
        } else {
            $sql.=" WHERE month(table1.tanggal) = month(now()) AND year(table1.tanggal) = year(now()) ";
        }
        if ($this->input->post("cabang")) {
            if ($this->input->post("cabang") != 0) {
                $sql.=" AND table1.IDCabang = " . $this->input->post("cabang");
            }
        } else if ($this->session->userdata('Level') != 0) {
            $sql.=" AND table1.IDCabang = " . $this->session->userdata('IDCabang') . " ";
        }
//        print_r($sql);exit;                
        return $this->db->query($sql)->result();
    }

    function insert_balancing_saldo() {
        $cabang = $this->db->get_where("cabang", array("IDCabang" => $this->session->userdata("IDCabang")))->row();

        if ($this->input->post("jenis") == 0) {
            if ($this->input->post("jenis_kas") == 2) {
                $sql = "UPDATE cabang SET saldo = " . (intval($cabang->saldo) + intval($this->input->post("nominal"))) . " WHERE IDCabang = " . $cabang->IDCabang . ";";
                $this->db->query($sql);
            }
        } else {
            if ($this->input->post("jenis_kas") == 2) {
                $sql = "UPDATE cabang SET saldo = " . (intval($cabang->saldo) - intval($this->input->post("nominal"))) . " WHERE IDCabang = " . $cabang->IDCabang . ";";
                $this->db->query($sql);
            }
        }

        $this->load->model("Jurnal_model");
        if ($this->input->post("jenis_kas") == 2) {
            if ($this->input->post("jenis") == 0) {
                $this->Jurnal_model->insert_jurnal_balancing(1, 'Balancing Tambah Saldo Kas Admin Kantor', $this->input->post("nominal"), $saldo = true);
            } else {
                $this->Jurnal_model->insert_jurnal_balancing(1, 'Balancing Kurang Saldo Kas Admin Kantor', $this->input->post("nominal"), $saldo = true);
            }
        } else {
            if ($this->input->post("jenis") == 0) {
                $this->Jurnal_model->insert_jurnal_balancing(1, 'Balancing Tambah Saldo Kas Bank', $this->input->post("nominal"), $saldo = true);
            } else {
                $this->Jurnal_model->insert_jurnal_balancing(1, 'Balancing Kurangi Saldo Kas Bank', $this->input->post("nominal"), $saldo = true);
            }
        }
    }

    function get_keterangan_lanjut($arrJurnal) {
        $return_array = array();
        $sql = "SELECT lp.IDPengeluaran, dp.keterangan_lanjut FROM laporan_pengeluaran lp INNER JOIN detail_pengeluaran dp ON dp.IDPengeluaran = lp.IDPengeluaran";
        $res = $this->db->query($sql)->result();
//        print_r($arrJurnal); exit;
        foreach ($arrJurnal as $items) {
            $arr = explode('|', $items->keterangan);
            $bool = FALSE;
            foreach ($res as $keterangan) {
                if ($arr[1] == $keterangan->IDPengeluaran && (strpos($arr[0], 'Biaya lain-lain') !== FALSE)) {
                    $bool = TRUE;
                    $return_array[$items->IDJurnal] = $keterangan->keterangan_lanjut;
//                    echo $keterangan->keterangan_lanjut."<br>";
                }
            }

            if ($bool == FALSE) {
                $return_array[$items->IDJurnal] = '';
            }
        }
//        exit;
//        print_r($arrJurnal); exit;
//        print_r($return_array); exit;
        return $return_array;
    }

}
