<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Toko extends CI_Controller {

    public function __construct() {
        parent::__construct();
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
            $this->Toko_model->insert_barang();
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

                $id = $this->Toko_model->edit_barang($IDBarang);
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

        $data['status'] = $this->session->flashdata('status');
        $data['tokos'] = $this->Toko_model->get_all_toko();
        $data['barangs'] = $this->Toko_model->get_all_barang();
        $data['spgs'] = $this->Toko_model->get_spg_mt($data['IDCabang']);
        $data['data'] = "SEMUA BARANG BULAN INI";
        if ($data['level'] == 0) {
            $data['laporans'] = $this->Toko_model->get_laporan_penjualan();
            $data['totals'] = $this->Toko_model->get_total_penjualan();
        } else {
            $data['laporans'] = $this->Toko_model->get_laporan_penjualan($data['IDCabang']);
            $data['totals'] = $this->Toko_model->get_total_penjualan($data['IDCabang']);
        }
        if ($this->input->post('btn_pilih') || $this->input->post('btn_print')) {
            $awal = $this->input->post('tanggal_awal');
            $akhir = $this->input->post('tanggal_akhir');
            $IDCabang = $this->input->post('cabang');
            $IDToko = $this->input->post('filterToko');
            $IDBarang = $this->input->post('filterBarang');
            $IDSpg = $this->input->post('filterSPG');
            $data['laporans'] = $this->Toko_model->get_laporan_penjualan(($IDCabang != 0 ? $IDCabang : FALSE), $awal = FALSE, $akhir = FALSE, ($IDSpg != 0 ? $IDSpg : FALSE), ($IDBarang != 0 ? $IDBarang : FALSE), ($IDToko != 0 ? $IDToko : FALSE));
            $data['totals'] = $this->Toko_model->get_total_penjualan(($IDCabang != 0 ? $IDCabang : FALSE), $awal = FALSE, $akhir = FALSE, ($IDSpg != 0 ? $IDSpg : FALSE), ($IDBarang != 0 ? $IDBarang : FALSE), ($IDToko != 0 ? $IDToko : FALSE));
            $data['data'] = ($IDToko == 0 ? "SEMUA BARANG" : $this->Toko_model->get_detail_toko($IDToko)->nama ) . " Periode " . ($awal && $akhir ? "$awal sampai $akhir" : "Bulan ini" );
        }
        if ($this->input->post('btn_print')) {
            $this->xls_penjualan($data);
        }

        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_laporan_penjualan_spg_mt', $data);
    }

    function xls_penjualan($data) {
        $this->load->library('custom_excel');
        $excel = $this->custom_excel;
        $excel->declare_excel();
        $row = 1;
        /* begin */
        $excel->add_cell("Laporan Penjualan SPG MT", "A", $row++)->font(20)->merge(array(0, 4))->alignment('center');
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
        $excel->end_excel("laporan penjualan SPG MT");
    }
    
    function laporan_kehadiran_mt(){
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
        $data['selectSeles'] = $this->input->post('filter');
        $data['datasales'] = $this->Toko_model->get_all_sales($data['IDCabang']);
        $data['kehadirans'] = $this->Toko_model->get_kehadiran_mt();

        if ($this->input->post('btn_pilih') || $this->input->post('btn_export')) {
            $awal = $this->input->post('tanggal_awal');
            $akhir = $this->input->post('tanggal_akhir');
            $data['tanggal'] = ($awal ? $awal : "--") . " s/d " . ($akhir ? $akhir : "--");
            $data['kehadirans'] = $this->Toko_model->get_kehadiran_mt($this->input->post('tanggal_awal'), $this->input->post('tanggal_akhir'), $this->input->post('filter'));
            $data['periode'] = strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_awal'))) . " s/d " . strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_akhir')));
        }

        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_kehadiran_mt', $data);
        $this->load->view('v_foot');
    }

}
