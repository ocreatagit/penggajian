<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Toko extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Admin_model');
        $this->load->model('Sales_model');
        $this->load->model('Barang_model');
        $this->load->model('Lokasi_model');
        $this->load->model('Laporan_model');
        $this->load->model('Toko_model');
        $this->load->helper('url');
        $this->load->library("cart");
        $this->load->library("form_validation");
    }

    public function barang() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        if ($this->input->post('nama_barang')) {

            $this->Admin_model->start_trans();
            $this->Barang_model->tambah_barang_baru();
            $this->Toko_model->insert_barang();
            $this->Admin_model->end_trans();
        }

        $data['status'] = $this->session->flashdata('status');
        $data['barangs'] = $this->Toko_model->get_all_barang();

        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_barang_mt', $data);
    }

    public function edit_barang($IDBarang = FALSE) {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        if (!$IDBarang) {
            redirect('toko/barang');
        }

        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');

        if ($this->input->post("hid")) { // button 'ok' ditekan 
            if ($this->form_validation->run() == TRUE) {
                $this->Admin_model->start_trans();
                $id = $this->Toko_model->edit_barang($IDBarang);
                $this->Admin_model->end_trans();
                redirect("toko/barang");
            }
        }

        $data["status_barang"] = $this->Toko_model->get_status_barang($IDBarang);
        $data["barang"] = $this->Toko_model->get_all_barang($IDBarang);
        $data['status'] = $this->session->flashdata("status");
        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_edit_barang_mt', $data);
        $this->load->view('v_foot');
    }

    public function delete_barang($IDBarang = FALSE) {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        if (!$IDBarang) {
            redirect('toko/barang');
        }

        $this->Toko_model->delete_barang($IDBarang);

        redirect('toko/barang');
    }

    public function toko() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        if ($this->input->post('nama_toko')) {
            $this->Toko_model->insert_toko();
        }

        $data['status'] = $this->session->flashdata('status');
        $data['tokos'] = $data['level'] == 0 ? $this->Toko_model->get_all_toko() : $this->Toko_model->get_all_toko($data['IDCabang']);

        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_data_toko', $data);
    }

    public function delete_toko($IDToko = FALSE) {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        if (!$IDToko) {
            redirect('toko/toko');
        }

        $this->Toko_model->delete_toko($IDToko);

        redirect('toko/toko');
    }

    public function edit_toko($IDToko = FALSE) {
//        print_r($IDToko);exit;
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        if (!$IDToko) {
            redirect('toko/toko');
        }

        $this->form_validation->set_rules('nama_toko', 'Nama Toko', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');

        if ($this->input->post("hid")) { // button 'ok' ditekan 
            if ($this->form_validation->run() == TRUE) {

                $id = $this->Toko_model->edit_toko($IDToko);
                redirect("toko/toko");
            }
        }

        $data['toko'] = $this->Toko_model->get_detail_toko($IDToko);
        $data["status_barang"] = $this->Toko_model->get_status_toko($IDToko);
        $data['status'] = $this->session->flashdata('status');

        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_edit_toko', $data);
    }

    //status : belum
    public function laporan_spg() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        $data['status'] = $this->session->flashdata('status');
//        $data["cabangs"] = $this->Admin_model->get_all_cabang();
//        $data['selectCabang'] = "";
        $data['laporan_alls'] = $this->Toko_model->laporan_spg();
        $data['laporans'] = $this->Toko_model->get_sales_laporan_spg();
        $data['tokos'] = $this->Toko_model->get_all_toko();
        $data['data'] = "SEMUA BARANG BULAN INI";
        if ($this->input->post('btn_pilih')) {
            $awal = $this->input->post('tanggal_awal');
            $akhir = $this->input->post('tanggal_akhir');
            $IDToko = $this->input->post('filter');
            $data['laporan_alls'] = $this->Toko_model->laporan_spg($awal, $akhir, ($IDToko == 0 ? FALSE : $IDToko));
            $data['laporans'] = $this->Toko_model->get_sales_laporan_spg();
            $data['data'] = ($IDToko == 0 ? "SEMUA BARANG" : $this->Toko_model->get_detail_toko($IDToko)->nama ) . " Periode " . ($awal && $akhir ? "$awal sampai $akhir" : "Bulan ini" );
        }
//        print_r($data['laporan_alls']);exit;
        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_laporan_spg_mt', $data);
    }

    public function spg_mt() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        $data['status'] = $this->session->flashdata('status');
        $data["cabangs"] = $this->Admin_model->get_all_cabang();

        $data['spg_mt'] = $this->Toko_model->get_spg_mt();
        if ($this->input->post('btn_pilih')) {
            $data['spg_mt'] = $this->Toko_model->get_spg_mt($this->input->post('cabang'));
        }

        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_daftar_spg_mt', $data);
    }

    public function tambah_spg_mt($IDSpg = FALSE) {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        $data['status'] = $this->session->flashdata('status');
        $data['data_sales'] = NULL;

        if (!$IDSpg) {
            if ($this->input->post("btn_submit")) {
                $config['upload_path'] = "./uploads";
                $config['allowed_types'] = 'jpg|png|gif';
                $config['max_size'] = 0;
                $config['encrypt_name'] = true;
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload("foto_sales")) {
                    echo $response = $this->upload->display_errors();
                    exit;
                } else {
                    $response = $this->upload->data();
                    $this->Toko_model->insert_spg_mt($response['file_name']);
                    redirect("Toko/spg_mt");
                }
            }
        }

        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_tambah_spg_mt', $data);
    }

    public function penjualan() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('nama_toko', 'Nama Toko', 'required');
        $this->form_validation->set_rules('nama_spg', 'Nama SPG', 'required');
        $this->form_validation->set_rules('nama_barang', 'Barang', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required');
//        $this->cart->destroy();
        if ($this->input->post('hid')) {
            if ($this->form_validation->run() == TRUE) {
                $ke = 1;
                if ($this->cart->total_items() > 0) {
                    foreach ($this->cart->contents() as $items) {
                        if (strpos($items["id"], "barangmt") !== FALSE) {
                            $ke++;
                        }
                    }
                }
                $rowid = "";
                foreach ($this->cart->contents() as $items) {
                    if (strpos($items["id"], "barangmt") !== FALSE) {
                        if ($items["options"]['IDToko'] == $this->input->post("nama_toko") && $items["options"]['IDSales'] == $this->input->post("nama_spg") && $items["options"]['IDBarang'] == $this->input->post("nama_barang")) {
                            $rowid = $items["rowid"];
                            break;
                        }
                    }
                }

                if ($rowid == "") {
                    $this->session->set_userdata("tanggal_jual_mt", $this->input->post("tanggal"));
                    $data = array(
                        'id' => 'barangmt_' . $ke,
                        'qty' => $this->input->post('jumlah'),
                        'price' => 0,
                        'name' => 'Barangmt',
                        'options' => array('IDSales' => $this->input->post('nama_spg'),
                            'IDBarang' => $this->input->post('nama_barang'),
                            'IDToko' => $this->input->post('nama_toko'),
                            'NamaToko' => $this->Toko_model->get_detail_toko($this->input->post('nama_toko'))->nama,
                            'NamaSales' => $this->Toko_model->get_sales($this->input->post('nama_spg'))->nama,
                            'NamaBarang' => $this->Toko_model->get_barang($this->input->post('nama_barang'))->nama,
                            'tanggal' => $this->input->post("tanggal"),
                            'combo_index' => $this->input->post("combo_index"),
                            'tanggal' => $this->input->post("tanggal")
                        )
                    );
                    $this->cart->insert($data);
                } else {
                    $this->session->set_flashdata("status_mt", "Data sudah pernah diinputkan!");
                }
                redirect("toko/penjualan");
            }
        }

        $data["status"] = $this->session->flashdata("status_mt");
        $data["tokos"] = $this->Toko_model->get_all_toko($data['IDCabang']);
        $data["spgs"] = $this->Toko_model->get_spg_mt($data['IDCabang']);
        $data["barangs"] = $this->Toko_model->get_all_barang();

        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_penjualan_mt', $data);
    }

    function delete_cart_mt($id = FALSE) {
        if (!$id) {
            redirect("toko/penjualan");
        }
        $rowid = "";
        if ($this->cart->total_items() > 0) {
            foreach ($this->cart->contents() as $items) {
                if ($items["id"] == $id) {
                    $rowid = $items["rowid"];
                    break;
                }
            }

            $data = array(
                'rowid' => $rowid,
                'qty' => 0
            );
            $this->cart->update($data);

            $this->session->set_flashdata("status_mt", "Data Telah Dihapus!");
        }
        redirect("toko/penjualan");
    }

    function edit_cart_mt() {
        $rowid = "";
        if ($this->cart->total_items() > 0) {
            $data = array(
                'rowid' => $this->input->post('rowid'),
                'qty' => 0
            );
            $this->cart->update($data);

            $data = array(
                'id' => $this->input->post('id'),
                'qty' => $this->input->post('jumlah'),
                'price' => 0,
                'name' => $this->input->post("name"),
                'options' => array('IDSales' => $this->input->post('IDSales'),
                    'IDBarang' => $this->input->post('IDBarang'),
                    'IDToko' => $this->input->post('IDToko'),
                    'NamaSales' => $this->input->post('NamaSales'),
                    'NamaBarang' => $this->input->post('NamaBarang'),
                    'NamaToko' => $this->input->post('NamaToko'),
                    'combo_index' => $this->input->post("combo_index"),
                    'tanggal' => $this->input->post("tanggal")
                )
            );
            $this->cart->insert($data);
        }
        /*
         * "id": $("#id" + i).val(),
          "jumlah": $("#jumlah" + i).val(),
          "name": $("#name" + i).val(),
          "IDSales": $("#IDSales" + i).val(),
          "IDBarang": $("#IDBarang" + i).val(),
          "IDToko": $("#IDLokasi" + i).val(),
          "NamaSales": $("#NamaSales" + i).val(),
          "NamaBarang": $("#NamaBarang" + i).val(),
          "NamaToko": $("#N" + i).val(),
          "combo_index": $("#combo_index" + i).val()
         */
    }

    function refresh_total() {
        $total = 0;
        foreach ($this->cart->contents() as $items) {
            if (strpos($items["id"], "barangmt") !== FALSE) {
                $total += $items["qty"];
            }
        }
//        echo '<td class="" colspan="4" align="right" style="color: white"><h4>Total</h4></td>
//            <td class="" colspan="" style="color: white"><h4>' . $total . '</h4></td>
//            <td class="" colspan="" align="center"><button class="btn btn-default" id="btn_save" type="button"><i class="fa fa-save"></i> Save</button></td>';
        echo number_format($total, 0, ",", ".");
    }

    function insert_penjualan_mt() {
        if ($this->cart->total_items() > 0) {
            $this->Toko_model->insert_penjualan_mt();
            $this->Toko_model->insert_kehadiran_mt();
            $this->session->set_flashdata("status_mt", "Data Telah Disimpan!");
        } else {
            $this->session->set_flashdata("status_mt", "Tidak Terdapat Data yang Diinputkan!");
        }
        redirect("toko/penjualan");
    }

    function laporan_penjualan() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        $data["cabangs"] = $this->Admin_model->get_all_cabang();
        $data['status'] = $this->session->flashdata('status');
        $data['tokos'] = $this->Toko_model->get_all_toko();
        $data['barangs'] = $this->Toko_model->get_all_barang();
        $data['spgs'] = $this->Toko_model->get_spg_mt($data['IDCabang']);
        $data['data'] = "SEMUA BARANG BULAN INI";
        $data['selectCabang'] = "";
        if ($data['level'] == 0) {
            $data['laporans'] = $this->Toko_model->get_laporan_penjualan();
            $data['totals'] = $this->Toko_model->get_total_penjualan();
        } else {
            $data['laporans'] = $this->Toko_model->get_laporan_penjualan($data['IDCabang']);
            $data['totals'] = $this->Toko_model->get_total_penjualan($data['IDCabang']);
        }
        if ($this->input->post('btn_pilih') || $this->input->post('btn_print') || $this->input->post('btn_print_2') || $this->input->post('btn_email')) {
            $awal = $this->input->post('tanggal_awal');
            $akhir = $this->input->post('tanggal_akhir');
            $IDCabang = $this->input->post('cabang');
            $data['selectCabang'] = $IDCabang;
            $IDToko = $this->input->post('filterToko');
            $IDBarang = $this->input->post('filterBarang');
            $IDSpg = $this->input->post('filterSPG');
            $data['laporans'] = $this->Toko_model->get_laporan_penjualan(($IDCabang != 0 ? $IDCabang : FALSE), $awal = FALSE, $akhir = FALSE, ($IDSpg != 0 ? $IDSpg : FALSE), ($IDBarang != 0 ? $IDBarang : FALSE), ($IDToko != 0 ? $IDToko : FALSE));
            $data['totals'] = $this->Toko_model->get_total_penjualan(($IDCabang != 0 ? $IDCabang : FALSE), $awal = FALSE, $akhir = FALSE, ($IDSpg != 0 ? $IDSpg : FALSE), ($IDBarang != 0 ? $IDBarang : FALSE), ($IDToko != 0 ? $IDToko : FALSE));
            $data['data'] = ($IDToko == 0 ? "SEMUA BARANG" : $this->Toko_model->get_detail_toko($IDToko)->nama ) . " Periode " . ($awal && $akhir ? "$awal sampai $akhir" : "Bulan ini" );
            if ($this->input->post('btn_print')) {
                $awal = $this->input->post('tanggal_awal');
                $akhir = $this->input->post('tanggal_akhir');
                $data['data_tanggal'] = ($awal && $akhir ? "$awal sampai $akhir" : date('F Y') );
                $data['data_cabang'] = ($IDCabang ? $this->Admin_model->get_detail_cabang($IDCabang)->kabupaten : FALSE);
                $data['data_toko'] = ($IDToko ? $this->Toko_model->get_detail_toko($IDToko)->nama : FALSE);
                $data['data_barang'] = ($IDToko ? $this->Toko_model->get_barang($IDBarang)->nama : FALSE);
                $data['data_spg'] = ($IDToko ? $this->Toko_model->get_sales($IDSpg)->nama : FALSE);
                $this->xls_penjualan($data, $this->input->post('btn_print'));
            }
        }

        if ($this->input->post("btn_email")) {
            $this->form_validation->set_rules('email', 'email', 'required');
            if ($this->form_validation->run() == TRUE) {
                $filename = $this->xls_penjualan($data, $this->input->post('btn_email'));
                // RRyner email - 19/12/2015
                $this->email_header("babylonindografika@gmail.com", "indografika01");
                $this->email_detail("babylonindografika@gmail.com", "Admin Indografika Notification", $this->input->post("email"), "Laporan Penjualan SPG MT Excel", "");
                $this->attach_email_files("xls", $filename);
                $this->email_send();

                $this->session->set_flashdata('status_laporan_penjualan_mt', '<i class="fa fa-check-circle"> Email Sent!</i>');
            } else {
                
            }
        }

        if ($this->input->post('btn_print_2')) {
            $this->load->view('v_head', $data);
            $this->load->view('v_navigation', $data);
            $this->load->view('v_cetak_laporan_penjualan_spg_mt', $data);
        } else {
            $this->load->view('v_head', $data);
            $this->load->view('v_navigation', $data);
            $this->load->view('v_laporan_penjualan_spg_mt', $data);
        }
    }

    function xls_penjualan($data, $post) {
        $this->load->library('custom_excel');
        $excel = $this->custom_excel;
        $excel->declare_excel();
        $row = 1;
        /* begin */
        $excel->add_cell("Laporan Penjualan SPG MT", "A", $row++)->font(20)->merge(array(0, 4))->alignment('center');
        if ($data['level'] == 0) {
            if ($data['data_cabang']) {
                $excel->add_cell("Cabang", "A", $row)->alignment('right')->font(16);
                $excel->add_cell($data['data_cabang'], "B", $row++)->alignment('Center')->font(16);
            }
        }
        if ($data['data_tanggal']) {
            $excel->add_cell("Periode", "A", $row)->alignment('right')->font(16);
            $excel->add_cell($data['data_tanggal'], "B", $row++)->alignment('Center')->font(16)->merge(array(0, 1));
        }
        if ($data['data_spg']) {
            $excel->add_cell("SPG", "A", $row)->alignment('right')->font(16);
            $excel->add_cell($data['data_spg'], "B", $row++)->alignment('Center')->font(16);
        }
        if ($data['data_toko']) {
            $excel->add_cell("Toko", "A", $row)->alignment('right')->font(16);
            $excel->add_cell($data['data_toko'], "B", $row++)->alignment('Center')->font(16);
        }
        if ($data['data_barang']) {
            $excel->add_cell("Barang", "A", $row)->alignment('right')->font(16);
            $excel->add_cell($data['data_barang'], "B", $row++)->alignment('Center')->font(16);
        }
        $row++;
        $excel->add_cell('Nama Barang', 'A', $row)->alignment('center')->border()->autoWidth()->font(16);
        $excel->add_cell('Jumlah', 'B', $row++)->alignment('center')->border()->autoWidth()->font(16);
        foreach ($data['totals'] as $laporan):
            $excel->add_cell($laporan->barang, "A", $row)->border();
            $excel->add_cell($laporan->jumlah, "B", $row)->border();
            $row++;
        endforeach;
        $row++;
        $excel->add_cell('Tanggal', 'A', $row)->alignment('center')->border()->autoWidth()->font(16);
        $excel->add_cell('Nama SPG', 'B', $row)->alignment('center')->border()->autoWidth()->font(16);
        $excel->add_cell('Nama Barang', 'C', $row)->alignment('center')->border()->autoWidth()->font(16);
        $excel->add_cell('Jumlah', 'D', $row)->alignment('center')->border()->autoWidth()->font(16);
        $excel->add_cell('Nama Toko', 'E', $row++)->alignment('center')->border()->autoWidth()->font(16);

        foreach ($data['laporans'] as $laporan):
            $excel->add_cell(strftime("%d-%m-%Y", strtotime($laporan->tanggal)), "A", $row)->border();
            $excel->add_cell($laporan->sales, "B", $row)->border();
            $excel->add_cell($laporan->barang, "C", $row)->border();
            $excel->add_cell($laporan->jumlah, "D", $row)->border();
            $excel->add_cell($laporan->toko, "E", $row)->border();
            $row++;
        endforeach;
        /* end */
        $excel->end_excel("laporan penjualan SPG MT", $post);
        return $excel->get_filename();
    }

    function laporan_kehadiran_mt() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        if ($this->session->userdata("Level") == 0) {
            $data["cabangs"] = $this->Admin_model->get_all_cabang();
            $data['selectCabang'] = $this->input->post('cabang');
        }

        $data["periode"] = "Laporan Bulan Ini";
        $data['selectSeles'] = $this->input->post('cabang');
        $data['datasales'] = $this->Toko_model->get_all_sales($data['IDCabang']);
        $data['kehadirans'] = $this->Toko_model->get_kehadiran_mt();

        if ($this->input->post('btn_pilih') || $this->input->post('btn_export')) {
            $awal = $this->input->post('tanggal_awal');
            $akhir = $this->input->post('tanggal_akhir');
            $data['tanggal'] = ($awal ? $awal : "--") . " s/d " . ($akhir ? $akhir : "--");
            $data['kehadirans'] = $this->Toko_model->get_kehadiran_mt($this->input->post('tanggal_awal'), $this->input->post('tanggal_akhir'), $this->input->post('filter'));
            $data['periode'] = strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_awal'))) . " s/d " . strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_akhir')));
            if ($this->input->post('btn_export')) {
                $data['data_cabang'] = $data['data_sales'] = FALSE;
                if (!$akhir && !$akhir) {
                    $data['periode'] = date('F Y');
                }
                if ($this->session->userdata('Level') == 0) {
                    if ($this->input->post('cabang') != 0) {
                        $data['data_cabang'] = $this->Admin_model->get_detail_cabang($this->input->post('cabang'))->kabupaten;
                    }
                } else {
                    $data['data_cabang'] = $this->Admin_model->get_detail_cabang($this->session->userdata('IDCabang'))->kabupaten;
                }
                if ($this->input->post('filter')) {
                    $data['data_sales'] = $this->Toko_model->get_sales($this->input->post('filter'))->nama;
                }
                $this->excel_kehadiran($data, $this->input->post('btn_export'));
            }
        }

        if ($this->input->post('btn_print')) {
            $data['selectCabang'] = $this->input->post('cabang');
            $awal = $this->input->post('tanggal_awal');
            $akhir = $this->input->post('tanggal_akhir');
//            echo $akhir; exit;
            $data['tanggal'] = ($awal ? $awal : "--") . " s/d " . ($akhir ? $akhir : "--");
            $data['kehadirans'] = $this->Toko_model->get_kehadiran_mt($this->input->post('tanggal_awal'), $this->input->post('tanggal_akhir'), $this->input->post('filter'));
            if (!$akhir && !$akhir) {
                $data['periode'] = date('F Y');
            } else {
                $data['periode'] = strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_awal'))) . " s/d " . strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_akhir')));
            }
            $data['print'] = $this->input->post('btn_print');

//            print_r($data["laporan_penjualan"]); exit;

            $this->load->view('v_head');
            $this->load->view('v_navigation', $data);
            $this->load->view('v_cetak_kehadiran_mt', $data);
            return;
        }
        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_kehadiran_mt', $data);
        $this->load->view('v_foot');
    }

    public function excel_kehadiran($data, $post) {
        $this->load->library('custom_excel');
        $excel = $this->custom_excel;
        $excel->declare_excel();
        $row = 1;
        /* array = merge(berapa baris, berapa kolom) */
        $excel->add_cell("Daftar Kehadiran SPG MT", 'A', $row++)->font(20)->merge(array(0, 3))->alignment('center');
        if ($data['data_cabang']) {
            $excel->add_cell("Cabang :", 'A', $row)->alignment('right');
            $excel->add_cell($data['data_cabang'], 'B', $row++)->merge(array(0, 2))->alignment('left');
        }
        $excel->add_cell("Periode :", 'A', $row)->alignment('right');
        $excel->add_cell($data['periode'], 'B', $row++)->merge(array(0, 2))->alignment('left');
        if ($data['data_sales']) {
            $excel->add_cell("SPG :", 'A', $row)->alignment('right');
            $excel->add_cell($data['data_sales'], 'B', $row++)->merge(array(0, 2))->alignment('left');
        }
        $row++;

        $excel->add_cell('Nama SPG', 'A', $row)->alignment('center')->border()->autoWidth()->font(14);
        $excel->add_cell('Hadir', 'B', $row)->alignment('center')->border()->autoWidth()->font(14);
        $excel->add_cell('Absen', 'C', $row)->alignment('center')->border()->autoWidth()->font(14);
        $excel->add_cell('Gaji', 'D', $row++)->alignment('center')->border()->autoWidth()->font(14);

        foreach ($data['kehadirans'] as $kehadiran) {
            $excel->add_cell($kehadiran->nama, 'A', $row)->border()->autoWidth();
            $excel->add_cell($kehadiran->hadir, 'B', $row)->alignment('center')->border()->autoWidth();
            $excel->add_cell($kehadiran->absen, 'C', $row)->alignment('center')->border()->autoWidth();
            $hadir = intval($kehadiran->hadir);
            $gajiperhari = intval($kehadiran->gaji);
            $excel->add_cell("Rp. " . number_format($hadir * $gajiperhari, 0, ",", ".") . ",-", 'D', $row++)->alignment('right')->border()->autoWidth();
        }
        $excel->end_excel("laporan_kehadiran_mt", $post);
        return $excel->get_filename();
    }

    //------------ EMAIL ------------/
    function email_header($email_setting, $password) {
        $this->load->library('email');
        $this->email->initialize(array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => $email_setting,
            'smtp_pass' => $password,
            'smtp_port' => 465,
            'crlf' => "\r\n",
            'newline' => "\r\n"
        ));
    }

    function email_detail($from_mail, $from_nick, $to_mail, $subject, $message) {
        $this->email->from($from_mail, $from_nick);
        $this->email->to($to_mail);
        $this->email->set_newline("\r\n");
        $this->email->subject($subject);
        $this->email->message($message);
    }

    function attach_email_files($jenis = 'xls', $filename) {
        $this->email->attach("./$jenis/$filename");
    }

    function email_send() {
        if (!$this->email->send()) {
            $this->session->set_flashdata("status_laporan_penjualan_spg", "Email Error! Please Check Internet Connection!");
//            echo show_error($this->email->print_debugger());
//            exit;
        } else {
            return TRUE;
        }
    }

    //------------ END EMAIL ------------/
}
