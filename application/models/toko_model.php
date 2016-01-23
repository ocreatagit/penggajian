<?php

class Toko_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('security');
        $this->load->helper('string');
        $this->load->helper('date');
    }

    function insert_spg_mt($fotopath) {
        $data = array(
            "nama" => $this->input->post('nama'),
            "tempatLahir" => $this->input->post('tempatLahir'),
            "tanggalLahir" => date("Y-m-d", strtotime(str_replace('/', '-', $this->input->post("tanggalLahir")))),
            "alamat" => $this->input->post('alamat'),
            "noHP" => $this->input->post('noHP'),
            "foto" => $fotopath,
            "gaji" => $this->input->post('gaji'),
            "keterangan" => $this->input->post('keterangan'),
            "IDCabang" => $this->session->userdata('IDCabang')
        );
        $this->db->insert("sales_mt", $data);
        $this->session->set_flashdata("status", "Sales Telah Ditambahkan!");
    }

    function get_spg_mt($IDCabang = false) {
        $this->db->select('*');
        $this->db->from('sales_mt');
        if (!$IDCabang && $IDCabang != 0) {
            $this->db->where('IDCabang', $IDCabang);
        }
        return $this->db->get()->result();
    }

    function insert_toko() {
        $data = array(
            "lower(nama)" => strtolower($this->input->post('nama_toko')),
            "lower(alamat)" => strtolower($this->input->post('alamat_toko')),
            "IDCabang" => $this->session->userdata('IDCabang')
        );
        $query = $this->db->get_where("toko", $data);
        if ($query->num_rows() == 0) {
            $data = array(
                "nama" => $this->input->post('nama_toko'),
                "alamat" => $this->input->post('alamat_toko'),
                "IDCabang" => $this->session->userdata('IDCabang')
            );
            $this->db->insert("toko", $data);
            $this->session->set_flashdata("status", "<b>Toko Telah Ditambahkan!</b>");
        } else {
            $this->session->set_flashdata("status", "<b>Data toko telah digunakan!</b>");
        }
    }

    function get_all_toko($IDCabang = false) {
        if ($IDCabang == false) {
            return $this->db->get('toko')->result();
        } else {
            return $this->db->get_where("toko", array("IDCabang" => $IDCabang))->result();
        }
    }

    function get_all_sales($IDCabang = false) {
        if ($IDCabang == false) {
            return $this->db->get('sales_mt')->result();
        } else {
            return $this->db->get_where("sales_mt", array("IDCabang" => $IDCabang))->result();
        }
    }

    function get_detail_toko($IDToko) {
        return $this->db->get_where('toko', array("IDToko" => $IDToko))->row();
    }

    function delete_toko($IDToko) {
        if ($this->db->get_where("laporan_penjualan_mt", array("IDToko", $IDToko))->num_rows() > 0) {
            $this->session->set_flashdata("status", "<b> Data Toko Tidak Dapat Dihapus</b> Toko tersebut sudah digunakan di dalam laporan");
        } else {
            $this->db->delete('toko', array('IDToko' => $IDToko));
            $this->session->set_flashdata("status", "<b> Data Toko Berhasil Dihapus</b>");
        }
    }

    function insert_barang() {
        if ($this->db->get_where("barang_mt", array("lower(nama)" => strtolower($this->input->post('nama_barang'))))->num_rows() == 0) {
            $data = array(
                "nama" => $this->input->post('nama_barang')
            );
            $this->db->insert("barang_mt", $data);
            $this->session->set_flashdata("status", "<b>Barang Telah Ditambahkan!</b>");
        } else {
            $this->session->set_flashdata("status", "<b>Nama barang sudah digunakan</b>");
        }
    }

    function edit_barang($IDBarang) {
        $sql = "SELECT * FROM `barang_mt` WHERE lower(nama) = '" . strtolower($this->input->post("nama_barang")) . "' AND IDBarangMT != " . $IDBarang;
        if ($this->db->query($sql)->num_rows() == 0) {
            $this->db->update('barang_mt', array("nama" => $this->input->post("nama_barang"), "nilai_karton" => $this->input->post("satuan")), array('IDBarangMT' => $IDBarang));
            $this->session->set_flashdata("status", "<b>Barang Telah Diubah!</b>");
        } else {
            $this->session->set_flashdata("status", "<b>Nama barang sudah digunakan</b>");
        }
    }

    function edit_toko($IDToko) {
        $sql = "SELECT * FROM `toko` WHERE lower(nama) = '" . strtolower($this->input->post("nama_toko")) . "' AND IDToko != " . $IDToko . " AND lower(alamat) = '" . strtolower($this->input->post("nama_toko")) . "'";
        if ($this->db->query($sql)->num_rows() == 0) {
            $this->db->update('toko', array(
                "nama" => $this->input->post("nama_toko"),
                "alamat" => $this->input->post("alamat")
                    ), array('IDToko' => $IDToko));
            $this->session->set_flashdata("status", "<b>Data Toko Telah Diubah!</b>");
        } else {
            $this->session->set_flashdata("status", "<b>Data Toko sudah digunakan</b>");
        }
    }

    function get_all_barang($IDbarang = FALSE) {
        if ($IDbarang) {
            return $this->db->get_where('barang_mt', array('IDBarangMT' => $IDbarang))->row();
        } else {
            return $this->db->get('barang_mt')->result();
        }
    }

    function get_status_barang($IDbarang) {
//        print_r($IDbarang);exit;
        return $this->db->get_where('laporan_barang_mt', array("IDBarangMT" => $IDbarang))->num_rows();
    }

    function get_status_Toko($IDToko) {
//        print_r($IDbarang);exit;
        return $this->db->get_where('laporan_penjualan_mt', array("IDTokoMT" => $IDToko))->num_rows();
    }

    function delete_barang($IDBarang) {
        if ($this->db->get_where("laporan_barang_mt", array("IDBarangMT", $IDBarang))->num_rows() > 0) {
            $this->session->set_flashdata("status", "<b> Data Barang Tidak Dapat Dihapus</b> Toko tersebut sudah digunakan di dalam laporan");
        } else {
            $this->db->delete('barang_mt', array('IDBarangMT' => $IDBarang));
            $this->session->set_flashdata("status", "<b> Data Barang Berhasil Dihapus</b>");
        }
    }

    function laporan_spg($awal = FALSE, $akhir = FALSE, $IDToko = FALSE) {
        $awal = $this->input->post('tanggal_awal');
        $akhir = $this->input->post('tanggal_akhir');
        $IDToko = $this->input->post('filter');
//        print_r($IDToko);exit;
        $sql = "SELECT sales_mt.IDSalesMT, SUM(laporan_barang_mt.jumlah) as jumlah, barang_mt.nama, barang_mt.nilai_karton as nilai_karton
                FROM laporan_barang_mt
                INNER JOIN sales_mt on sales_mt.IDSalesMT = laporan_barang_mt.IDSalesMT 
                INNER JOIN laporan_penjualan_mt on laporan_penjualan_mt.IDLaporan = laporan_barang_mt.IDLaporanMT 
                INNER JOIN barang_mt on barang_mt.IDBarangMT = laporan_barang_mt.IDBarangMT ";
        if ($awal && $akhir) {
            $sql.=" WHERE laporan_penjualan_mt.tanggal BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'  ";
        } else {
            $sql.=" WHERE month(laporan_penjualan_mt.tanggal) = month(now()) AND year(laporan_penjualan_mt.tanggal) = year(now()) ";
        }

        if ($IDToko) {
            $sql .= " AND laporan_barang_mt.IDToko = " . $IDToko;
        }

        if ($this->session->userdata('Level') != 0) {
            if ($this->session->userdata('IDCabang') != 0) {
                $sql .= " AND sales_mt.IDCabang = " . $this->session->userdata('IDCabang');
            }
        }

        $sql .= " GROUP BY laporan_barang_mt.IDSalesMT, barang_mt.IDBarangMT";
//        print_r($sql);exit;
        return $this->db->query($sql)->result();
    }

    function get_sales_laporan_spg() {
        $sql = "SELECT DISTINCT(sales_mt.IDSalesMT), sales_mt.nama, sales_mt.foto FROM laporan_barang_mt
                INNER JOIN sales_mt on laporan_barang_mt.IDSalesMT = sales_mt.IDSalesMT
                INNER JOIN laporan_penjualan_mt on laporan_barang_mt.IDLaporanMT = laporan_penjualan_mt.IDLaporan ";
        $awal = $this->input->post('tanggal_awal');
        $akhir = $this->input->post('tanggal_akhir');
        $IDToko = $this->input->post('filter');
        if ($awal && $akhir) {
            $sql.=" WHERE laporan_penjualan_mt.tanggal BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'  ";
        } else {
            $sql.=" WHERE month(laporan_penjualan_mt.tanggal) = month(now()) AND year(laporan_penjualan_mt.tanggal) = year(now()) ";
        }

        if ($IDToko) {
            $sql .= " AND laporan_barang_mt.IDToko = " . $IDToko;
        }

        if ($this->session->userdata('Level') != 0) {
            if ($this->session->userdata('IDCabang') != 0) {
                $sql .= " AND sales_mt.IDCabang = " . $this->session->userdata('IDCabang');
            }
        }
//        print_r($sql);exit;
        return $this->db->query($sql)->result();
    }

    function get_sales($IDSales) {
        return $this->db->get_where("sales_mt", array("IDSalesMT" => $IDSales))->row();
    }

    function get_barang($IDBarang) {
        return $this->db->get_where("barang_mt", array("IDBarangMT" => $IDBarang))->row();
    }

    function insert_penjualan_mt() {
        $data = array(
            'tanggal' => strftime('%Y-%m-%d', strtotime($this->input->post('tanggal'))),
            'keterangan' => "Laporan Penjualan MT",
            'IDTokoMT' => 0
        );
        $this->db->insert('laporan_penjualan_mt', $data);

        $IDLaporan = $this->db->insert_id();

        foreach ($this->cart->contents() as $items) {
            if (strpos($items["id"], 'barangmt') !== FALSE) {
                $data = array(
                    'IDLaporanMT' => $IDLaporan,
                    'IDToko' => $items["options"]["IDToko"],
                    'IDBarangMT' => $items["options"]["IDBarang"],
                    'IDSalesMT' => $items["options"]["IDSales"],
                    'jumlah' => $items['qty']
                );
                $this->db->insert('laporan_barang_mt', $data);

                $dataX = array(
                    "rowid" => $items["rowid"],
                    "qty" => 0
                );
                $this->cart->update($dataX);
            }
        }
    }

    function get_laporan_penjualan($IDCabang = FALSE, $awal = FALSE, $akhir = FALSE, $SPG = FALSE, $barang = FALSE, $toko = FALSE) {
        $sql = "SELECT laporan_penjualan_mt.tanggal, sales_mt.nama as sales, barang_mt.nama as barang, laporan_barang_mt.jumlah, toko.nama as toko, barang_mt.nilai_karton as nilai_karton
                FROM laporan_barang_mt
                INNER JOIN laporan_penjualan_mt on laporan_penjualan_mt.IDLaporan = laporan_barang_mt.IDLaporanMT
                INNER JOIN sales_mt on sales_mt.IDSalesMT = laporan_barang_mt.IDSalesMT
                INNER JOIN barang_mt on barang_mt.IDBarangMT = laporan_barang_mt.IDBarangMT
                INNER JOIN toko on toko.IDToko = laporan_barang_mt.IDToko";
        if ($awal && $akhir) {
            $sql.=" WHERE laporan_penjualan_mt.tanggal BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'  ";
        } else {
            $sql.=" WHERE month(laporan_penjualan_mt.tanggal) = month(now()) AND year(laporan_penjualan_mt.tanggal) = year(now()) ";
        }
        if ($SPG) {
            $sql .= " AND sales_mt.IDSalesMT = " . $SPG;
        }
        if ($barang) {
            $sql .= " AND barang_mt.IDBarangMT = " . $barang;
        }
        if ($toko) {
            $sql .= " AND toko.IDToko = " . $toko;
        }
        if ($IDCabang) {
            $sql .= " AND toko.IDCabang = " . $IDCabang;
        }
        $sql .= " ORDER BY tanggal, toko, barang ";
        return $this->db->query($sql)->result();
    }

    function get_total_penjualan($IDCabang = FALSE, $awal = FALSE, $akhir = FALSE, $SPG = FALSE, $barang = FALSE, $toko = FALSE) {
        $sql = "SELECT barang_mt.nama as barang, sum(laporan_barang_mt.jumlah) as jumlah, toko.nama as toko, barang_mt.nilai_karton FROM laporan_barang_mt 
                INNER JOIN laporan_penjualan_mt on laporan_penjualan_mt.IDLaporan = laporan_barang_mt.IDLaporanMT 
                INNER JOIN barang_mt on barang_mt.IDBarangMT = laporan_barang_mt.IDBarangMT
                INNER JOIN toko on toko.IDToko = laporan_barang_mt.IDToko";
        if ($awal && $akhir) {
            $sql.=" WHERE laporan_penjualan_mt.tanggal BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'  ";
        } else {
            $sql.=" WHERE month(laporan_penjualan_mt.tanggal) = month(now()) AND year(laporan_penjualan_mt.tanggal) = year(now()) ";
        }
        if ($SPG) {
            $sql .= " AND laporan_barang_mt.IDSalesMT = " . $SPG;
        }
        if ($barang) {
            $sql .= " AND laporan_barang_mt.IDBarangMT = " . $barang;
        }
        if ($toko) {
            $sql .= " AND laporan_barang_mt.IDToko = " . $toko;
        }
        if ($IDCabang) {
            $sql .= " AND toko.IDCabang = " . $IDCabang;
        }
        $sql .= " GROUP BY barang_mt.nama ORDER BY barang ";
        return $this->db->query($sql)->result();
    }

    function insert_kehadiran_mt() {
        $sql = "SELECT laporan_barang_mt.IDSalesMT FROM laporan_barang_mt
                INNER JOIN laporan_penjualan_mt ON laporan_penjualan_mt.IDLaporan = laporan_barang_mt.IDLaporanMT
                WHERE laporan_penjualan_mt.tanggal = '" . strftime('%Y-%m-%d', strtotime($this->input->post('tanggal'))) . "'";
        $spgmt_hadir = $this->db->query($sql)->result();
        $sql = "SELECT IDSalesMT FROM sales_mt
                WHERE IDSalesMT NOT IN ( 
                    SELECT laporan_barang_mt.IDSalesMT FROM laporan_barang_mt 
                    INNER JOIN laporan_penjualan_mt ON laporan_penjualan_mt.IDLaporan = laporan_barang_mt.IDLaporanMT 
                    WHERE laporan_penjualan_mt.tanggal = '" . strftime('%Y-%m-%d', strtotime($this->input->post('tanggal'))) . "' 
                ) ";
        $spgmt_absen = $this->db->query($sql)->result();
        foreach ($spgmt_hadir as $spg) {
            if ($this->db->get_where('kehadiran_mt', array("IDSalesMT" => $spg->IDSalesMT, "tanggal" => strftime('%Y-%m-%d', strtotime($this->input->post('tanggal')))))->num_rows() == 0) {
                $data = array(
                    "IDSalesMT" => $spg->IDSalesMT,
                    "tanggal" => strftime('%Y-%m-%d', strtotime($this->input->post('tanggal'))),
                    "status" => "H"
                );
                $this->db->insert('kehadiran_mt', $data);
            }
        }
        foreach ($spgmt_absen as $spg) {
            if ($this->db->get_where('kehadiran_mt', array("IDSalesMT" => $spg->IDSalesMT, "tanggal" => strftime('%Y-%m-%d', strtotime($this->input->post('tanggal')))))->num_rows() == 0) {
                $data = array(
                    "IDSalesMT" => $spg->IDSalesMT,
                    "tanggal" => strftime('%Y-%m-%d', strtotime($this->input->post('tanggal'))),
                    "status" => "A"
                );
                $this->db->insert('kehadiran_mt', $data);
            }
        }
    }

    function get_kehadiran_mt($awal = FALSE, $akhir = FALSE, $IDSales = FALSE) {
        $sql = "SELECT sales_mt.gaji, sales_mt.IDSalesMT as IDSales, sales_mt.foto, sales_mt.nama, COUNT(IF( kehadiran_mt.status = 'H', kehadiran_mt.status, NULL )) as hadir, COUNT(IF( kehadiran_mt.status = 'A', kehadiran_mt.status, NULL )) as absen
                FROM sales_mt
                INNER JOIN kehadiran_mt on kehadiran_mt.IDSalesMT = sales_mt.IDSalesMT";
        if ($awal && $akhir) {
            $sql.=" WHERE tanggal BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'  ";
        } else {
            $sql.=" WHERE month(tanggal) = month(now()) AND year(tanggal) = year(now()) ";
        }
        if ($IDSales) {
            $sql .= " AND sales_mt.IDSalesMT = " . $IDSales;
        }
        if ($this->session->userdata('Level') == 0) {
            if ($this->input->post('cabang') != 0) {
                $sql .= " AND sales_mt.IDCabang = " . $this->input->post('cabang');
            }
        } else {
            $sql .= " AND sales_mt.IDCabang = " . $this->session->userdata('IDCabang');
        }
        $sql .= " GROUP BY sales_mt.IDSalesMT";
        return $this->db->query($sql)->result();
    }

}
