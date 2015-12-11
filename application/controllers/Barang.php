<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('barang_model');
        $this->load->model('Admin_model');
        $this->load->model('Lokasi_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    public function tambah_barang() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');

        $namafile = '';
        if ($this->input->post("hid")) { // button 'ok' ditekan 
            if ($this->form_validation->run() == TRUE) {
                $id = $this->barang_model->tambah_barang_baru();
                if ($id != 0) {
                    $config['upload_path'] = "./barangs";
                    $config['allowed_types'] = 'jpg|png|gif';
                    $config['max_size'] = 0;
                    $config['file_name'] = $id;
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload("foto_barang")) {
//                        $response = $this->upload->display_errors();
                        $this->barang_model->delete_barang($id);
                        $this->session->set_flashdata("status", "Mohon Pilih Foto Untuk Barang yang Ditambahkan!");
                    } else {
                        $response = $this->upload->data();
                    }
                    redirect("barang/tambah_barang");
                }
            }
        }

        $data['all_barang'] = $this->barang_model->get_all_barang();
        $data['harga_satuan'] = $this->barang_model->get_harga_satuan();
        $data['konv_satuan'] = $this->barang_model->get_satuan();
        $data['status'] = $this->session->flashdata("status");
        $data['refresh'] = $this->session->flashdata("status_refresh");
        $this->load->model("Laporan_model");
        if ($this->session->userdata("Level") != 0) {
            $data["stok_cabang"] = $this->Laporan_model->get_stok_cabang($data['username']);
        }

        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_tambah_barang', $data);
        $this->load->view('v_foot');
    }

    public function barang_edit($IDBarang = FALSE) {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
        $this->load->helper("file");

        if ($this->input->post("hid")) { // button 'ok' ditekan 
            if ($this->form_validation->run() == TRUE) {
                $namafile = $_FILES["foto_barang"]["name"];
//                print_r($_FILES["foto_barang"]); exit;
//                $this->barang_model->edit_barang();
                if ($namafile == '')
                    $IDBarang = 0;

                if ($IDBarang != 0) {
                    $array = get_filenames("./barangs");
                    foreach ($array as $key => $value) {
                        if (strpos($value, $IDBarang) !== FALSE) {
                            if (!unlink("./barangs/" . $value)) {
//                                echo "gagal";
                            }
                            break;
                        }
                    }
                    $config['upload_path'] = "./barangs";
                    $config['allowed_types'] = 'jpg|png|gif';
                    $config['max_size'] = 0;
                    $config['file_name'] = $IDBarang;
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload("foto_barang")) {
//                        echo $response = $this->upload->display_errors();
//                        exit;
                        $this->session->set_flashdata("status", "Mohon Pilih Foto Untuk Barang yang Ditambahkan!");
                    } else {
                        $response = $this->upload->data();
                    }
                    $this->session->set_flashdata("status", "Barang telah diubah!");
                    $this->session->set_flashdata("status_refresh", "Please Refresh Page!");
                }
                redirect("barang/tambah_barang");
            }
        }

        $data["gambar_barang"] = "";

        $gambar = get_filenames("./barangs");
        foreach ($gambar as $key => $value) {
            if (strpos($value, $IDBarang) !== FALSE) {
                $data["gambar_barang"] = $value;
                break;
            }
        }

        $data["status_barang"] = $this->barang_model->get_status($IDBarang);
        $data["barang"] = $this->barang_model->get_detail_barang($IDBarang);
        $data['status'] = $this->session->flashdata("status");
        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_edit_barang');
        $this->load->view('v_foot');
    }

    public function barang_delete($IDBarang = FALSE) {
        if ($IDBarang == FALSE) {
            redirect("barang/tambah_barang");
        }
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        if ($this->barang_model->get_status_barang($IDBarang) == 1) {
            $this->load->helper("file");
            $array = get_filenames("./barangs");
            foreach ($array as $key => $value) {
                if (strpos($value, $IDBarang) !== FALSE) {
                    if (!unlink("./barangs/" . $value)) {
                        echo "gagal";
                    }
                    break;
                }
            }
            $this->barang_model->delete_barang($IDBarang);
        } else {
            $this->session->set_flashdata("status", "<b>Barang Tidak Dapat DiHapus! </b> Barang ini sudah digunakan Laporan Harian");
        }
        redirect("barang/tambah_barang");
    }

    public function tambah_barang_lokasi($IDCabang = NULL, $Cabang = NULL) {
        $data = array();
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }
        $this->load->model('barang_model');
        if ($this->input->post("btn_submit")) {
            $kode_lokasi = $this->input->post('kode_lokasi');
            $kode_barang = $this->input->post('kode_barang');
            $jumlah = $this->input->post('jumlah_barang');
            $this->barang_model->tambah_barang_lokasi($kode_lokasi, $kode_barang, $jumlah);
            redirect('barang/tambah_barang_lokasi/' . $kode_lokasi);
        }
        $data['username'] = $this->session->userdata("Username");
        $data['all_barang'] = $this->barang_model->get_all_barang('namaBarang');
        $data['IDCabang'] = $IDCabang;
        $data['Cabang'] = urldecode($Cabang);
        $data['barang_lokasi'] = $this->barang_model->get_barang_cabang($IDCabang);

        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_tambah_barang_lokasi', $data);
    }

    public function ubah_stok() {
        $this->load->model('barang_model');
        $kode_lokasi = $this->input->post('IDCabang');
        $kode_barang = $this->input->post('IDBarang');
        $jumlah = $this->input->post('jumlah_barang');
        $lokasi = $this->input->post('lokasi');
        $this->barang_model->update_barang_lokasi($kode_lokasi, $kode_barang, $jumlah);
        redirect('barang/tambah_barang_lokasi/' . $kode_lokasi . '/' . $lokasi);
    }

    public function super_admin_input_data() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }
        $data["status"] = $this->session->flashdata("status");
        $data["username"] = $this->session->userdata("Username");
        $data['status'] = $this->session->flashdata('status');
        $data["lokasi"] = $this->Lokasi_model->get_lokasi();

        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_super_admin_stok', $data);
    }

    public function harga_satuan($IDBarang = FALSE) {
        if ($IDBarang) {
            if ($this->input->post('btn')) {
                $this->barang_model->insert_harga_satuan($IDBarang);
                redirect('Barang/tambah_barang');
            }
        }
    }

    public function konversi_satuan($IDBarang = FALSE) {
        if ($IDBarang) {
            if ($this->input->post('btnSimpan')) {
                $this->barang_model->insert_satuan($IDBarang);
                redirect('Barang/tambah_barang');
            }
        }
    }

}
