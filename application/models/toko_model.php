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
            $this->db->update('barang_mt', array("nama" => $this->input->post("nama_barang")), array('IDBarangMT' => $IDBarang));
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
        $sql = " SELECT ";        
        $sql .= " SUM(laporan_barang_mt.jumlah) as total_jumlah, laporan_barang_mt.*, sales_mt.*, laporan_penjualan_mt.tanggal FROM laporan_barang_mt INNER JOIN sales_mt on sales_mt.IDSalesMT = laporan_barang_mt.IDSalesMT INNER JOIN laporan_penjualan_mt on laporan_penjualan_mt.IDLaporan = laporan_barang_mt.IDLaporanMT ";

        if ($awal && $akhir) {
            $sql.=" WHERE laporan_penjualan_mt.tanggal BETWEEN '" . strftime("%Y-%m-%d", strtotime($awal)) . "' AND '" . strftime("%Y-%m-%d", strtotime($akhir)) . "'  ";
        } else {
            $sql.=" WHERE month(laporan_penjualan_mt.tanggal) = month(now()) AND year(laporan_penjualan_mt.tanggal) = year(now()) ";
        }

        if ($IDToko) {
            $sql .= " AND laporan_penjualan_mt.IDTokoMT = " . $IDToko;
        }

        $sql .= " GROUP BY laporan_barang_mt.IDSalesMT, IDBarangMT";
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
            'tanggal' => date("Y-m-d"),
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

}
