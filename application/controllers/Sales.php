<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Sales_model');
        $this->load->model('Admin_model');
        $this->load->model('Laporan_model');
//        $this->load->library('session');
//        $this->load->helper('file');
//        $this->load->library('ftp');
        $this->load->helper('url');
    }

    public function daftar_sales_tiap_admin() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }
        $data['admins'] = $this->Sales_model->get_sales_tiap_admin();

        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_daftar_admin', $data);
        $this->load->view('v_foot');
    }

    public function daftar_sales() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        if ($this->session->userdata("Level") == 0) {
            $data["cabangs"] = $this->Admin_model->get_all_cabang();
        }
        $data["filter"] = "";
        if ($this->input->post("btn_submit")) {
            $data["filter"] = $this->Laporan_model->get_cabang_id($this->input->post("cabang"));
        }
        $data['username'] = $this->session->userdata('Username');
        $this->Sales_model->get_idcabang();
        $data['saless'] = $this->Sales_model->select_sales();

        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_daftar_sales', $data);
        $this->load->view('v_foot');
    }

    public function update_sales($IDSales) {
        $this->Admin_model->start_trans();
        $this->Sales_model->aktif_sales($IDSales);
        $this->Admin_model->end_trans('update_sales()');
        redirect('sales/daftar_sales');
    }

    public function tambah_sales($IDSales = NULL) {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }
        $data['status'] = $this->session->flashdata('status');
        $data['username'] = $this->session->userdata('Username');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model("barang_model");
        $data['barangs'] = $this->barang_model->get_barang();
        $data['data_sales'] = NULL;
        if ($IDSales == NULL) {
            if ($this->input->post("btn_submit")) {
                $this->load->model("Sales_model");
                $this->Admin_model->start_trans();
                $dataController = $this->Sales_model->insert_sales();
                if (count($data['barangs']) > 0) {
                    $this->Sales_model->insert_komisi($dataController[1], $data['barangs']);
                }
$this->Admin_model->end_trans('update_sales()');
                $config['upload_path'] = "./uploads";
                $config['allowed_types'] = 'jpg|png|gif';
                $config['max_size'] = 0;
                $config['file_name'] = $dataController[0];
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload("foto_sales")) {
                    echo $response = $this->upload->display_errors();
                    exit;
                } else {
                    $response = $this->upload->data();
                    redirect("Sales/daftar_sales");
                }
            } else {
                $this->load->view('v_head');
                $this->load->view('v_navigation', $data);
                $this->load->view('v_tambah_sales', $data);
            }
        } else {
            $this->load->model("Sales_model");
            $data['data_sales'] = $this->Sales_model->select_sales($IDSales);
            $data['komisi_sales'] = $this->Sales_model->select_komisi($IDSales);
            if ($this->input->post("btn_submit")) {

                $this->load->model("Sales_model");
                $this->Admin_model->start_trans();
                $namafile = $this->Sales_model->update_sales($IDSales);
                if (count($data['barangs']) > 0) {
                    $this->Sales_model->ganti_komisi($IDSales, $data['barangs']);
                }
$this->Admin_model->end_trans('update_sales()');
                if (!empty($_FILES['foto_sales']['name'])) {
                    $config['upload_path'] = "./uploads";
                    $config['allowed_types'] = 'jpg|png|gif';
                    $config['max_size'] = 0;
                    $config['file_name'] = $namafile;
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload("foto_sales")) {
                        echo $response = $this->upload->display_errors();
                        exit;
                    } else {
                        $response = $this->upload->data();
                    }
                }
                redirect("Sales/daftar_sales");
            }

            $this->load->view('v_head');
            $this->load->view('v_navigation', $data);
            $this->load->view('v_tambah_sales', $data);
        }
    }

    public function kehadiran_sales() {
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
        $data['datasales'] = $this->Sales_model->get_sales_tiap_admin($data['username']);
        $data['kehadirans'] = $this->Sales_model->get_kehadiran();

        if ($this->input->post('btn_pilih') || $this->input->post('btn_export') || $this->input->post('btn_email') ) {
            $awal = $this->input->post('tanggal_awal');
            $akhir = $this->input->post('tanggal_akhir');
            $data['tanggal'] = ($awal ? $awal : "--") . " s/d " . ($akhir ? $akhir : "--");
            $data['kehadirans'] = $this->Sales_model->get_kehadiran($this->input->post('tanggal_awal'), $this->input->post('tanggal_akhir'), $this->input->post('filter'));
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
                $data['data_sales'] = false;
                if($this->input->post('filter')){
                    $data['data_sales'] = $this->Sales_model->get_detail_sales($this->input->post('filter'))->nama;
                }
                $this->excel_kehadiran($data, $this->input->post('btn_export'));
            }
        }
        
        if ($this->input->post('btn_print')) {
            $data['selectCabang'] = $this->input->post('filter');
            $awal = $this->input->post('tanggal_awal');
            $akhir = $this->input->post('tanggal_akhir');
//            echo $akhir; exit;
            $data['tanggal'] = ($awal ? $awal : "--") . " s/d " . ($akhir ? $akhir : "--");
            $data['kehadirans'] = $this->Sales_model->get_kehadiran($this->input->post('tanggal_awal'), $this->input->post('tanggal_akhir'), $this->input->post('filter'));
            if (!$akhir && !$akhir) {
                $data['periode'] = ' - S/D - ';
            } else {
                $data['periode'] = strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_awal'))) . " s/d " . strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_akhir')));
            }
            $data['print'] = $this->input->post('btn_print');

//            print_r($data["laporan_penjualan"]); exit;

            $this->load->view('v_head');
            $this->load->view('v_navigation', $data);
            $this->load->view('v_cetak_kehadiran', $data);
            return;
        }
        
        if ($this->input->post("btn_email")) {
            $this->form_validation->set_rules('email', 'email', 'required');
            if ($this->form_validation->run() == TRUE) {
                $filename = $this->excel_kehadiran($data, $this->input->post("btn_email"));
//                $filename = $this->excel_pengeluaran($data,  $this->input->post('btn_email'));
                // RRyner email - 19/12/2015
                $this->email_header("babylonindografika@gmail.com", "indografika01");
                $this->email_detail("babylonindografika@gmail.com", "Admin Indografika Notification", $this->input->post("email"), "Laporan Kehadiran Excel", "");
                $this->attach_email_files("xls", $filename);
                $this->email_send();

                $this->session->set_flashdata('status_laporan_kehadiran', '<i class="fa fa-check-circle"> Email Sent!</i>');
            } else {
            }
        }

        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_kehadiran', $data);
        $this->load->view('v_foot');
    }

    public function excel_kehadiran($data, $post) {
        $this->load->library('custom_excel');
        $excel = $this->custom_excel;
        $excel->declare_excel();
        $row = 1;
        /* array = merge(berapa baris, berapa kolom) */
        $excel->add_cell("Daftar Kehadiran", 'A', $row++)->font(20)->merge(array(0, 2))->alignment('center');
        if ($data['data_cabang']) {
            $excel->add_cell("Cabang :", 'A', $row)->alignment('right');
            $excel->add_cell($data['data_cabang'], 'B', $row++)->merge(array(0, 1))->alignment('left');
        }
        $excel->add_cell("Periode :", 'A', $row)->alignment('right');
        $excel->add_cell($data['periode'], 'B', $row++)->merge(array(0, 1))->alignment('left');
        if($data['data_sales']){
            $excel->add_cell("SPG :", 'A', $row)->alignment('right');
            $excel->add_cell($data['data_sales'], 'B', $row++)->merge(array(0, 1))->alignment('left');
        }
        $row++;

        $excel->add_cell('Nama SPG', 'A', $row)->alignment('center')->border()->autoWidth()->font(14);
        $excel->add_cell('Hadir', 'B', $row)->alignment('center')->border()->autoWidth()->font(14);
        $excel->add_cell('Absen', 'C', $row++)->alignment('center')->border()->autoWidth()->font(14);

        foreach ($data['kehadirans'] as $kehadiran) {
            $excel->add_cell($kehadiran->nama, 'A', $row)->border()->autoWidth();
            $excel->add_cell($kehadiran->hadir, 'B', $row)->alignment('center')->border()->autoWidth();
            $excel->add_cell($kehadiran->absen, 'C', $row++)->alignment('center')->border()->autoWidth();
        }
        $excel->end_excel("laporan_kehadiran", $post);
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
