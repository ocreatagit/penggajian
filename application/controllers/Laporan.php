<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('Sales_model');
        $this->load->model('Barang_model');
        $this->load->model('Lokasi_model');
        $this->load->model('Laporan_model');
        $this->load->model('Jurnal_model');
        $this->load->library('cart');
//        $this->load->library('session');
//        $this->load->helper('file');
//        $this->load->library('ftp');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->database();
    }

    public function Harian() {
//            $data['username'] = $this->session->userdata('Username');
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');

            $data['laporans'] = $this->Laporan_model->select_laporan();
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

        if ($this->input->post('logout')) {
            $this->session->unset_userdata('Username');
            redirect('welcome/index');
        }

        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_laporan_harian', $data);
//        $this->load->view('v_foot');
    }

    public function HarianPenjualan() {
//        $this->cart->destroy(); exit; 
        $this->form_validation->set_rules('lokasi', 'Lokasi Penjualan', 'required');
        $this->form_validation->set_rules('team_leader', 'Team Leader', 'required');
        $this->form_validation->set_rules('salesnya_admin', 'Sales', 'required');
        $this->form_validation->set_rules('nama_produk', 'Barang', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('pendapatan_SPG', 'Total Penjualan', 'required');

//        print_r($this->cart->contents());
//        exit;

        if ($this->input->post('btn_submit')) {
            if ($this->form_validation->run() == TRUE) {
                $ke = 1;
                if ($this->cart->total_items() > 0) {
                    foreach ($this->cart->contents() as $items) {
                        if (strpos($items["id"], "Jual") !== FALSE) {
                            $ke++;
                        }
                    }
                }
                $rowid = "";
                foreach ($this->cart->contents() as $items) {
                    if (strpos($items["id"], "Jual") !== FALSE) {
                        if ($items["options"]['IDSales'] == $this->input->post("salesnya_admin") && $items["options"]['IDBarang'] == $this->input->post("nama_produk") && $items["options"]['IDTeamLeader'] == $this->input->post("team_leader")) {
                            $rowid = $items["rowid"];
                            break;
                        }
                    }
                }

                if ($rowid == "") {
                    $this->session->set_userdata("tanggal_jual", $this->input->post("tanggal_tampung"));
                    $this->session->set_userdata("keterangan", $this->input->post("keterangan"));
                    $komisi = $this->input->post('jumlah') * $this->get_komisi($this->input->post('salesnya_admin'), $this->input->post('nama_produk'));
                    $data = array(
                        'id' => 'Jual_' . $ke,
                        'qty' => $this->input->post('jumlah'),
                        'price' => $this->input->post('pendapatan_SPG'),
                        'name' => 'Jual',
                        'options' => array('IDSales' => $this->input->post('salesnya_admin'),
                            'IDBarang' => $this->input->post('nama_produk'),
                            'IDLokasi' => $this->input->post('lokasi'),
                            'NamaSales' => $this->Laporan_model->get_sales($this->input->post('salesnya_admin'))->nama,
                            'NamaBarang' => $this->Laporan_model->get_barang($this->input->post('nama_produk'))->namaBarang,
                            'Daerah' => $this->Laporan_model->get_lokasi($this->input->post('lokasi'))->desa,
                            'komisi' => $komisi,
                            'IDTeamLeader' => $this->input->post('team_leader'),
                            'NamaTeamLeader' => $this->Laporan_model->get_sales($this->input->post('team_leader'))->nama,
                            'index_combo' => $this->input->post("index_combo")
                        )
                    );
                    $this->cart->insert($data);
                    $this->session->set_flashdata("status_2", "Data telah ditambahkan!");

                    $this->session->set_userdata("cbo_team", $this->input->post('team_leader'));
                    $this->session->set_userdata("cbo_lokasi", $this->input->post('lokasi'));
                } else {
                    $this->session->set_flashdata("status", "Data sudah pernah diinputkan!");
                }
                redirect("laporan/HarianPenjualan");
            }
        }

        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }
        if ($this->input->post('logout')) {
            $this->session->unset_userdata('Username');
            redirect('welcome/index');
        }

        $data["stok_cabang"] = $this->Laporan_model->get_stok_cabang($data['username']);
        $data['cabang'] = $this->Admin_model->get_cabang($data['username']);
        $data['info_cabangs'] = $this->Admin_model->get_provinsi_kabupaten($data['username']);
        $data['info_lokasis'] = $this->Admin_model->get_kecamatan_desa($data['username']);
        if (count($data['info_lokasis']) > 0) {
            if ($this->session->userdata("cbo_lokasi")) {
                $id_new = $this->session->userdata("cbo_lokasi");
            } else {
                $id_new = $data['info_lokasis'][0]->id_lokasi;
            }
            $data['info_lokasi_details'] = $this->Lokasi_model->get_detail_cabang_lokasi($id_new);
        }
        $data['info_saleses'] = $this->Sales_model->get_sales_tiap_admin($data['username']);
        $data['info_team_leaders'] = $this->Sales_model->get_team_leader_tiap_admin($data['username']);
        $data['info_barang'] = $this->Barang_model->get_barang();
        $data['saldo'] = $this->Admin_model->get_saldo($data['username']);
        $data["penjualan_sales"] = $this->Sales_model->get_penjualan_sales();
        $data["komisi"] = $this->Sales_model->get_komisi($data["penjualan_sales"]);
        $data["konversi_satuan"] = $this->Barang_model->get_satuan();
        $data["harga_satuan"] = $this->Barang_model->get_harga_satuan();

        $array_cart = $this->cart->contents();
        $data["array_cart"] = $this->msort($array_cart, array('IDTeamLeader', 'IDBarang'), SORT_REGULAR, 'Jual');
        $data["status"] = $this->session->flashdata("status_2");
        $data["status_tanggal"] = $this->session->flashdata("status");
//        $data["info_total_per_team_leader"] = $this->Barang_model->total_per_team_leader($this->cart->contents());
//        print_r($data["harga_satuan"]);exit;

        $this->load->view('v_head', $data);
        $this->load->view('v_navigation', $data);
        $this->load->view('v_laporan_harian_penjualan', $data);
    }

    function refresh_total_penjualan_per_leader() {
        $array_cart = $this->msort($this->cart->contents(), array('IDTeamLeader', 'IDBarang'), SORT_REGULAR, 'Jual');
        $array = array();
        $no = 0;
        $total_item = 0;
        $leader = '';

        foreach ($array_cart as $items) {
            if (strpos($items["id"], "Jual") !== FALSE) {
                if ($leader == '') {
                    $leader = $items["options"]["NamaTeamLeader"];
                    $total_item = $items["qty"];
                    $nama_barang = $items["options"]["NamaBarang"];
                    $total = $items["price"];

                    $no++;
                } else {
                    if ($leader == $items["options"]["NamaTeamLeader"]) {
                        if ($nama_barang == $items["options"]["NamaBarang"]) {
                            $total_item += $items["qty"];
                            $total += $items["price"];
                        } else {
                            array_push($array, array("NamaTeamLeader" => $leader, "NamaBarang" => $nama_barang, "Jumlah" => $total_item, "Subtotal" => $total));
                            $total_item = $items["qty"];
                            $nama_barang = $items["options"]["NamaBarang"];
                            $total = $items["price"];
                        }

                        if (count($this->cart->contents()) == $no) {
                            array_push($array, array("NamaTeamLeader" => $leader, "NamaBarang" => $nama_barang, "Jumlah" => $total_item, "Subtotal" => $total));
                        }
                        $no++;
                    } else {
                        array_push($array, array("NamaTeamLeader" => $leader, "NamaBarang" => $nama_barang, "Jumlah" => $total_item, "Subtotal" => $total));
                        $leader = $items["options"]["NamaTeamLeader"];
                        $total_item = $items["qty"];
                        $nama_barang = $items["options"]["NamaBarang"];
                        $total = $items["price"];
                        $no++;
                    }
                }

                if (count($this->cart->contents()) == $no) {
                    array_push($array, array("NamaTeamLeader" => $leader, "NamaBarang" => $nama_barang, "Jumlah" => $total_item, "Subtotal" => $total));
                }
            }
        }

        if (count($array) > 0) {
            foreach ($array as $items):
                echo '<tr>
                                            <td>' . $items["NamaBarang"] . '</td>
                                            <td>' . $items["NamaTeamLeader"] . '</td>
                                            <td align="right" id="tengah">' . $items["Jumlah"] . '</td>
                                            <td align="right" id="tengah">Rp ' . number_format($items["Subtotal"], 0, ',', '.') . ',-</td>
                                        </tr>';
            endforeach;
        }
    }

    function get_komisi_sales() {
        $IDSales = $this->input->post("IDSales");
        $IDBarang = $this->input->post("IDBarang");
        echo $this->Sales_model->get_komisi_sales_barang($IDSales, $IDBarang);
    }

    function test_error() {
        $this->load->model('admin_model');
        try {
            $this->admin_model->test_error();
        } catch (Exception $ex) {
            var_dump($ex->getMessage());
        }
    }

    // --------------------- SORT ---------------------------- //
    function msort($array, $key, $sort_flags = SORT_REGULAR, $id) {
        $array_new = array();
        foreach ($array as $items) {
            if (strpos($items["id"], $id) !== FALSE) {
                $array_new[$items["rowid"]] = $items;
            }
        }

//        print_r($array_new); exit;

        if (is_array($array) && count($array) > 0) {
            if (!empty($key)) {
                $mapping = array();
                foreach ($array_new as $k => $v) {
                    $sort_key = '';
                    if (!is_array($key)) {
                        $sort_key = $v["options"][$key];
                    } else {
                        // @TODO This should be fixed, now it will be sorted as string
                        foreach ($key as $key_key) {
                            $sort_key .= $v["options"][$key_key];
                        }
                        $sort_flags = SORT_ASC;
                    }
                    $mapping[$k] = $sort_key;
                }
                asort($mapping, $sort_flags);

                $sorted = array();
                foreach ($mapping as $k => $v) {
                    $sorted[] = $array[$k];
                }
                return $sorted;
            }
        }
        return $array;
    }

    function delete_cart_jual($id = FALSE) {
        if ($id == FALSE) {
            redirect("laporan/HarianPenjualan");
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

            $this->session->set_flashdata("status", "Data Telah Dihapus!");
        }
        redirect("laporan/HarianPenjualan");
    }

    function edit_cart_jual() {
        $rowid = "";
        if ($this->cart->total_items() > 0) {
//            foreach ($this->cart->contents() as $items) {
//                if ($items["id"] == $this->input->post("rowid")) {
//                    $rowid = $items["rowid"];
//                    break;
//                }
//            }

            $data = array(
                'rowid' => $this->input->post('rowid'),
                'qty' => 0
            );
            $this->cart->update($data);

            $data = array(
                'id' => $this->input->post('id'),
                'qty' => $this->input->post('jumlah'),
                'price' => $this->input->post('harga'),
                'name' => 'Jual',
                'options' => array('IDSales' => $this->input->post('IDSales'),
                    'IDBarang' => $this->input->post('IDBarang'),
                    'IDLokasi' => $this->input->post('IDLokasi'),
                    'NamaSales' => $this->input->post('NamaSales'),
                    'NamaBarang' => $this->input->post('NamaBarang'),
                    'Daerah' => $this->input->post('Daerah'),
                    'komisi' => $this->input->post('komisi'),
                    'IDTeamLeader' => $this->input->post('IDTeamLeader'),
                    'NamaTeamLeader' => $this->input->post('NamaTeamLeader'),
                    'index_combo' => $this->input->post("index_combo")
                )
            );
            $this->cart->insert($data);

            echo $this->input->post('harga');
        }
    }

    public function HarianPengeluaran() {
//        print_r($this->cart->contents()); exit;
        $this->form_validation->set_rules('nominal', 'Nominal', 'required');
        if ($this->input->post('btn_tambah')) {
            if ($this->form_validation->run() == TRUE) {
                $ke = 1;
                if ($this->cart->total_items() > 0) {
                    foreach ($this->cart->contents() as $items) {
                        if (strpos($items["id"], "KasKeluar") !== FALSE) {
                            $ke++;
                        }
                    }
                }

                $rowid = "";
                foreach ($this->cart->contents() as $items) {
                    if ($items["options"]['Keterangan'] == $this->input->post('jenis_pengeluaran')) {
                        $rowid = $items["rowid"];
                        break;
                    }
                }

                if ($rowid == "") {
                    $nominal = $this->input->post('nominal');
                    $jenis_kas = $this->input->post('jenis_pengeluaran');
                    $tamp = "";
                    if ($jenis_kas == "Gaji") {
                        $sales = $this->Laporan_model->get_sales($this->input->post("gaji_sales"));
                        $data = array(
                            'id' => 'KasKeluarGaji_' . $ke,
                            'qty' => 1,
                            'price' => $nominal,
                            'name' => "Gaji " . $sales->nama,
                            'options' => array('Keterangan' => 'Gaji', 'IDSales' => $sales->IDSales, 'NamaSales' => $sales->nama)
                        );
                    } else if ($jenis_kas == "lain-lain") {
                        $data = array(
                            'id' => 'KasKeluarNonGaji_' . $ke,
                            'qty' => 1,
                            'price' => $nominal,
                            'name' => $jenis_kas,
                            'options' => array('Keterangan' => $jenis_kas, 'Keterangan_lainnya' => $this->input->post('keterangan_lainnya'))
                        );
                    } else {
                        $data = array(
                            'id' => 'KasKeluarNonGaji_' . $ke,
                            'qty' => 1,
                            'price' => $nominal,
                            'name' => $jenis_kas,
                            'options' => array('Keterangan' => $jenis_kas)
                        );
                    }
                    $this->session->set_userdata("tanggal_jual", $this->input->post("tanggal_keluar"));
                    $this->cart->insert($data);

                    $this->session->set_flashdata("status", "Data Telah Di Tambahkan!");
//                print_r($this->cart->contents());
//                exit;
                } else {
                    $this->session->set_flashdata("status", "Data sudah pernah diinputkan!");
                }
            }
        }

        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }
        if ($this->input->post('logout')) {
            $this->session->unset_userdata('Username');
            redirect('welcome/index');
        }
        $data['info_salesess'] = $this->Sales_model->get_sales_tiap_admin($data['username']);
        $data['saldo'] = $this->Admin_model->get_saldo($data['username']);
        $data["penjualan_sales"] = $this->Sales_model->get_penjualan_sales();
        $data["komisi"] = $this->Sales_model->get_komisi($data["penjualan_sales"]);
        $data["status"] = $this->session->userdata("status");

//        if ($this->session->userdata('Username')) {
//            $data['username'] = $this->session->userdata('Username');
//
////            $data['info_cabang'] = $this->Admin_model->get_provinsi_kabupaten($data['username']);
//            $levelLogin = $this->Admin_model->cek_level_login($data['username']);
//            if ($levelLogin->level == 1) { /* Admin Login */
//
//                $data['info_salesess'] = $this->Sales_model->get_sales_tiap_admin($data['username']);
//                $data['IDPenjualan'] = $kodepenjualan;
//                $data['saldo'] = $this->Admin_model->get_saldo($data['username']);
//                $data["penjualan_sales"] = $this->Sales_model->get_penjualan_sales();
//                $data["komisi"] = $this->Sales_model->get_komisi($data["penjualan_sales"]);
//            } else {
////                echo 'asd';
//            }
//        } else {
//            redirect('welcome/index');
//        }
//        if ($this->input->post('logout')) {
//            $this->session->unset_userdata('Username');
//            redirect('welcome/index');
//        }
        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_laporan_harian_pengeluaran', $data);
//        $this->load->view('v_foot');
    }

    function hapus_cart_pengeluaran($id = FALSE) {
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

            $this->session->set_flashdata("status", "Data Telah Dihapus!");
        }
        redirect("laporan/HarianPengeluaran");
    }

    public function cetaklaporan($kodepenjualan) {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');

//            $data['info_cabang'] = $this->Admin_model->get_provinsi_kabupaten($data['username']);
            $levelLogin = $this->Admin_model->cek_level_login($data['username']);
//            if ($levelLogin->level != 0) { /* Admin Login */

            $data['info_penjualans'] = $this->Sales_model->report_tabel_penjualan($kodepenjualan);
            $data['laporan_penjualan'] = $this->Sales_model->get_laporan_penjualan($kodepenjualan);
            $data['info_pengeluarans'] = $this->Sales_model->get_laporan_pengeluaran($kodepenjualan);
            $data['info_total_per_team_leader'] = $this->Sales_model->get_detail_stok_team_leader($kodepenjualan);
            $data['IDPenjualan'] = $kodepenjualan;
            $data['pembatalan'] = $this->Sales_model->get_status_pembatalan($kodepenjualan);
        } else {
            redirect('welcome/index');
        }
        if ($this->input->post('logout')) {
            $this->session->unset_userdata('Username');
            redirect('welcome/index');
        }
        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_laporan_harian_cetak', $data);
//        $this->load->view('v_foot');
    }

    public function cetaklaporanpengeluaran($kodepenjualan) {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');

            $levelLogin = $this->Admin_model->cek_level_login($data['username']);
            //if ($levelLogin->level != 0) { /* Admin Login */

            $data['laporan_penjualan'] = $this->Sales_model->get_pengeluaran($kodepenjualan);
            $data['info_pengeluarans'] = $this->Sales_model->get_laporan_pengeluaran($kodepenjualan);
            $data['IDPenjualan'] = $kodepenjualan;
        } else {
            redirect('welcome/index');
        }
        if ($this->input->post('logout')) {
            $this->session->unset_userdata('Username');
            redirect('welcome/index');
        }
        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_laporan_harian_pengeluaran_cetak', $data);
    }

    function select_barang() {
        $this->Barang_Model->get_barang();
    }

    function get_barang_sales() {
        echo $return = $this->Sales_model->get_barang_sales();
    }

    function insert_jual() {
        echo $return = $this->Sales_model->insert_sales_jual();
    }

    function refresh_penjualan() {
        $data["penjualan_sales"] = $this->Sales_model->get_penjualan_sales();
        $data["komisi"] = $this->Sales_model->get_komisi($data["penjualan_sales"]);
        $this->load->view("v_refresh_laporan_sales", $data);
    }

    function get_komisi($IDSales, $IDBarang) {
        return $this->Sales_model->get_komisi_sales_barang($IDSales, $IDBarang);
    }

    function get_saldo() {
        echo $return = $this->Admin_model->get_saldo($this->session->userdata('Username'));
    }

//    function get_cabang_saldo($IDCabang) {
//        return $this->Admin_model->get_saldo_cabang($IDCabang);
//    }

    function get_lokasi_info() {
        echo $return = $this->Lokasi_model->get_lokasi_info();
    }

    function get_current_gaji_sales() {
        if ($this->input->post("IDSaless") != "") {
            echo $this->Sales_model->get_gaji_sales();
        }
    }

    function insert_pendapatan() {
        echo $this->Admin_model->insert_pendapatan();
    }

    function tambah_gaji_dan_komisi_sales() {
        $wew = $this->Sales_model->tambah_gaji_dan_komisi_sales();
        echo $wew;
    }

    function insert_detail_pendapatan() {
        echo $this->Admin_model->insert_detail_pendapatan();
    }

    function insert_bayar_gaji() {
        $this->Admin_model->insert_bayar_gaji();
    }

    function get_lokasi() {
        $data["info_lokasis"] = ($this->Admin_model->get_kecamatan_desa($this->session->userdata("Username")));
        $this->load->view("v_load_lokasi", $data);
    }

    function get_detail_lokasi() {
        echo json_encode($this->Lokasi_model->get_detail_lokasi($this->input->post("IDLokasi")));
    }

    function cek_laporan_per_tanggal() {
        echo $this->Laporan_model->cek_laporan_per_tanggal();
    }

    /* Cek cek penggajian */

//    function set_tanggal_gaji() {
//        echo $return = $this->Admin_model->set_tanggal_gaji();
//    }
//
//    function cek_penggajian() {
//        echo $return = $this->Admin_model->cek_penggajian();
//    }

    function insert_attrib() {
        $this->session->set_userdata("tanggal_jual", $this->input->post("tanggal"));
        $this->session->set_userdata("keterangan", $this->input->post("keterangan"));
    }

    /* 13/10/2015 */

    function insert_laporan_penjualan() {
        if ($this->Admin_model->check_penjualan($this->session->userdata("tanggal_jual"), $this->Admin_model->get_cabang($this->session->userdata('Username'))) == TRUE) {
            $this->session->set_flashdata("status_tanggal", "Penjualan Pada Tanggal '" . strftime("%d-%m-%Y", strtotime($this->session->userdata("tanggal_jual"))) . "' Telah Dimasukkan! Mohon Pilih Tanggal Lainnya!");
            redirect("laporan/HarianPenjualan");
        } else {
            $isEmpty = 0;
            foreach ($this->cart->contents() as $items) {
                if (strpos($items["id"], "Jual") !== FALSE) {
                    $isEmpty++;
                }
            }

            if ($isEmpty != 0) { /* Jual Ada Isi */
                $this->Admin_model->insert_pendapatan();
            }

            $this->session->unset_userdata('keterangan');
            $this->session->unset_userdata('tanggal_jual');

            redirect("Laporan/harian");
        }

//        redirect("Laporan/HarianPengeluaran/" . $IDPenjualan);
    }

    function insert_pengeluaran() {
        $id_cabang = $this->session->userdata('Username');
        $isEmpty = 0;
        foreach ($this->cart->contents() as $items) {
            if (strpos($items["id"], "KasKeluar") !== FALSE) {
                $isEmpty++;
            }
        }
        if ($isEmpty != 0) {
            $this->Admin_model->start_trans();
            $IDPengeluaran = $this->Admin_model->get_autoinc_laporan_pengeluaran();
            $this->Admin_model->insert_laporan_pengeluaran($IDPengeluaran);
            foreach ($this->cart->contents() as $items) {
                if (strpos($items["id"], "KasKeluarGaji") !== FALSE) {
//                    $this->Admin_model->insert_bayar_gaji($IDPenjualan, $items["options"]["IDSales"], $items["price"]);
                    $data = array('rowid' => $items['rowid'], 'qty' => 0);
                    $this->cart->update($data);
                } elseif (strpos($items["id"], "KasKeluarNonGaji") !== FALSE) {
                    $this->Admin_model->insert_pengeluaran($IDPengeluaran, $items["name"], $items["price"], isset($items["options"]['Keterangan_lainnya']) ? $items["options"]['Keterangan_lainnya'] : '');

                    // Jurnal
                    $this->load->model('Jurnal_model');
                    if (isset($items["options"]['Keterangan_lainnya'])) {
                        $jenis_transaksi = 'Biaya ' . trim($items["name"]) . "|" . $items["options"]['Keterangan_lainnya'];
                    } else {
                        $jenis_transaksi = 'Biaya ' . trim($items["name"]);
                    }
                    $this->Jurnal_model->insert_jurnal_pengeluaran($IDPengeluaran, $jenis_transaksi, $items["price"]);

                    $data = array('rowid' => $items['rowid'], 'qty' => 0);
                    $this->cart->update($data);
                }
            }
//            $this->Admin_model->hitung_saldo($IDPenjualan, $id_cabang);
            $this->session->set_flashdata("status", "Data Telah Di Tambahkan!");
            $this->Admin_model->end_trans("insert_pengeluaran()");
            redirect("Laporan/laporan_pengeluaran");
        }
    }

    function get_sales($IDSales) {
        $this->load->model("Laporan_model");
        echo json_encode($this->Laporan_model->get_sales($IDSales));
    }

    /* Ronald 18-10-15 */

    function kas() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }
        //$data['saldo'] = $this->Admin_model->get_saldo($data['username']);
        $data['saldo'] = $this->Admin_model->get_saldo_cabang($data["IDCabang"]);
        if ($this->session->userdata("Level") == 0) {
            $data["cabangs"] = $this->Admin_model->get_all_cabang();
        }
        if ($this->input->post("btn_submit")) {
            $data["filter"] = $this->Laporan_model->get_cabang_id($this->input->post("cabang"));
            $data['saldo'] = $this->Admin_model->get_saldo_cabang($this->input->post("cabang"));
        }

        $data["filter"] = "";
        if ($this->input->post("btn_submit")) {
            $data["filter"] = $this->Laporan_model->get_cabang_id($this->input->post("cabang"));
        }

        $data["laporans"] = $this->Laporan_model->select_laporan();
        $data['status'] = $this->session->flashdata("status");
        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_kas', $data);
    }

    function hitung_saldo_laporan($IDPenjualan) {
        $this->Admin_model->start_trans();
        $this->Admin_model->hitung_saldo($IDPenjualan, $this->session->userdata('Username'));

        // Jurnal
        $this->load->model('Jurnal_model');
        $this->Jurnal_model->insert_jurnal($IDPenjualan, 'Setor Penjualan');
        $this->Jurnal_model->insert_jurnal($IDPenjualan, 'Terima Setoran Penjualan', FALSE);
        $this->Admin_model->end_trans('hitung_saldo_laporan()');

        redirect("Laporan/kas");
    }

    function batal_saldo_laporan($IDPenjualan) {
        $this->Admin_model->batal_saldo($IDPenjualan, $this->session->userdata('Username'));

        // Jurnal
        $this->load->model('Jurnal_model');
        $this->Jurnal_model->insert_jurnal($IDPenjualan, 'Batal Terima Penjualan');
        $this->Jurnal_model->insert_jurnal($IDPenjualan, 'Terima Pembatalan Kas Penjualan', FALSE);

        redirect("Laporan/kas");
    }

    function stok_cabang() {
        if ($this->session->userdata("Level") != 0) {
            $admin_cabang = $this->Lokasi_model->get_admin_cabang();
            redirect("barang/tambah_barang_lokasi/" . $admin_cabang->IDCabang);
        } else {
            redirect("barang/super_admin_input_data");
        }
    }

    function laporan_gaji() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');

            $data['laporans'] = $this->Laporan_model->get_laporan_gaji();
            $data['saldo'] = $this->Admin_model->get_saldo_cabang($data["IDCabang"]);
        } else {
            redirect('welcome/index');
        }

        if ($this->session->userdata("Level") == 0) {
            $data["cabangs"] = $this->Admin_model->get_all_cabang();
        }

        $data["filter"] = "";
        if ($this->input->post("btn_submit")) {
            $data["filter"] = $this->Laporan_model->get_cabang_id($this->input->post("cabang"));
			$data['saldo'] = $this->Admin_model->get_saldo_cabang($this->input->post("cabang"));
        }

        if ($this->input->post('logout')) {
            $this->session->unset_userdata('Username');
            redirect('welcome/index');
        }

        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_laporan_gaji', $data);
    }

    function detail_gaji($IDLaporan = FALSE) {
        if ($IDLaporan == FALSE) {
            redirect("laporan/laporan_gaji");
        }
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }
        $data["detail_bayars"] = $this->Laporan_model->get_detail_laporan_gaji($IDLaporan);
//        print_r($data["detail_bayars"]); exit;
        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_detail_bayar', $data);
    }

    function bayar_gaji() {

        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('bayar', 'Pembayaran', 'required');

        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        if ($this->input->post("btn_submit")) {
            if ($this->form_validation->run() == TRUE) {
                if ($this->input->post("sales") != 0) {
                    if ($this->input->post("bayar") == 0) {
                        $this->session->set_flashdata("status", "Total Bayar tidak boleh nol!");
                    } else {
                        if ($this->input->post("bayar") <= $this->input->post("gaji_hidden")) {
                            $rowid = "";
                            foreach ($this->cart->contents() as $items) {
                                if ($items["name"] == $this->input->post("nama_sales")) {
                                    $rowid = $items["rowid"];
                                    break;
                                }
                            }

                            if ($rowid == "") {
                                $data = array(
                                    'id' => 'gaji_' . ($this->cart->total_items() + 1),
                                    'qty' => 1,
                                    'price' => $this->input->post("bayar"),
                                    'name' => $this->input->post("nama_sales"),
                                    'options' => array(
                                        'IDSales' => $this->input->post("sales"),
                                        'tanggal' => $this->input->post("tanggal"),
                                        'komisi' => $this->input->post("gaji_hidden")
                                    )
                                );
                                $this->cart->insert($data);
                                $this->session->set_userdata('tanggal_gaji', $this->input->post("tanggal"));
                            } else {
                                $this->session->set_flashdata("status", "Gaji Sales Telah Diinputkan!");
                                $rowid = "";
                            }
                        } else {
                            $this->session->set_flashdata("status", "Total Gaji Sales Tidak Mencukupi Pembayaran!");
                        }
                    }
                } else {
                    $this->session->set_flashdata("status", "Mohon Pilih Sales yang Tersedia!");
                }
            }
        }

        $data['username'] = $this->session->userdata('Username');
        $data['level'] = $this->session->userdata('Level');
        $data['IDCabang'] = $this->session->userdata('IDCabang');
        $data['saldo'] = $this->Admin_model->get_saldo($data['username']);
        $data['status'] = $this->session->flashdata("status");
        $data['saless'] = $this->Sales_model->get_sales_tiap_admin($data["username"]);
        $data["cart"] = $this->cart->contents();
        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_penggajian', $data);
    }

    function get_gaji_sales() {
        echo $this->Sales_model->get_gaji_sales();
    }

    function delete_cart_bayar_gaji($id = FALSE) {
        if ($id == FALSE) {
            redirect("laporan/bayar_gaji");
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

            $this->session->set_flashdata("status", "Data Telah Dihapus!");
        }
        redirect("laporan/bayar_gaji");
    }

    function simpan_bayar_gaji() {
        if ($this->cart->total_items() > 0) {
            $this->load->model("Sales_model");
            $this->Admin_model->start_trans();
            $IDPenggajian = $this->Admin_model->insert_penjualan_gaji();
            foreach ($this->cart->contents() as $items) {
                $this->Admin_model->insert_detail_penggajian($IDPenggajian, $items["options"]["IDSales"], $items["options"]["tanggal"], $items["price"]);
                $sales = $this->Sales_model->get_detail_sales($items["options"]["IDSales"]);

                // Jurnal
                $this->load->model('Jurnal_model');
                $this->Jurnal_model->insert_jurnal_pengeluaran($IDPenggajian, 'Bayar Gaji SPG|' . $sales->nama, $items["price"], TRUE);
            }
//            echo "end"; exit;
            $this->Admin_model->end_trans('simpan_bayar_gaji()');
            $this->session->set_flashdata("status", "Gaji telah Diambil!!");
            $this->cart->destroy();
        } else {
            $this->session->set_flashdata("status", "Tidak Terdapat Data yang Dimasukan!");
        }
        redirect("laporan/bayar_gaji");
    }

    function cetak_laporan_gaji($IDLaporan) {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');

            $data['detail_bayar_gajis'] = $this->Laporan_model->get_detail_laporan_gaji($IDLaporan);
            $data['laporan_bayar_gaji'] = $this->Laporan_model->get_laporan_gaji_id($IDLaporan);
        } else {
            redirect('welcome/index');
        }
        if ($this->input->post('logout')) {
            $this->session->unset_userdata('Username');
            redirect('welcome/index');
        }
        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_laporan_gaji_cetak', $data);
    }

    function laporan_pengeluaran() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');

            $data['laporans'] = $this->Laporan_model->select_laporan_pengeluaran();
            $data['saldo'] = $this->Admin_model->get_saldo_cabang($data['IDCabang']);
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

        if ($this->input->post('logout')) {
            $this->session->unset_userdata('Username');
            redirect('welcome/index');
        }

        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_laporan_pengeluaran', $data);
    }

    function pengeluaran() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }
        $data['data'] = "Bulan ini";
        $data['kolom'] = true;
        $data['searchby'] = 'Semua Jenis';
        $data['isi_tabel'] = $this->Laporan_model->select_all_pengeluaran(FALSE, FALSE, FALSE, FALSE);
        if ($this->session->userdata("Level") == 0) {
            $data["cabangs"] = $this->Admin_model->get_all_cabang();
        }

        if ($this->input->post('submit') || $this->input->post('btn_convert') || $this->input->post('btn_email')) {
            $data['searchby'] = $this->input->post('jenis_pengeluaran');
            $awal = $this->input->post('tanggal_awal');
            $akhir = $this->input->post('tanggal_akhir');
            if ($this->input->post('jenis_pengeluaran') != 'Semua Jenis' && $this->input->post('jenis_pengeluaran') != 'Lain-Lain') {
                if ($this->input->post('jenis_pengeluaran') == 'Gaji' || $this->input->post('jenis_pengeluaran') == 'Komisi') {
                    $data['isi_tabel'] = $this->Laporan_model->select_gaji($this->input->post('jenis_pengeluaran'), $awal, $akhir, FALSE, TRUE);
                    $data['kolom'] = true;
                } else {
                    $data['isi_tabel'] = $this->Laporan_model->select_per_jenis($this->input->post('jenis_pengeluaran'), $awal, $akhir, FALSE, TRUE);
                    $data['kolom'] = false;
                }
            } else if ($this->input->post('jenis_pengeluaran') == 'Semua Jenis') {
                $data['isi_tabel'] = $this->Laporan_model->select_all_pengeluaran($awal, $akhir, FALSE, TRUE);
                $data['kolom'] = true;
            } else {
                $data['isi_tabel'] = $this->Laporan_model->select_lain_lain($awal, $akhir, FALSE, TRUE);
                $data['kolom'] = true;
            }
            $data['data'] = ($awal ? $awal : '-- ') . " s/d " . ($akhir ? $akhir : " --");
            if ($this->input->post('btn_convert')) {
                $this->excel_pengeluaran($data, $this->input->post('btn_convert'));
            }
        }

        if ($this->input->post("btn_email")) {
            $this->form_validation->set_rules('email', 'email', 'required');
            if ($this->form_validation->run() == TRUE) {
                $filename = $this->excel_pengeluaran($data, $this->input->post('btn_email'));
                // RRyner email - 19/12/2015
                $this->email_header("babylonindografika@gmail.com", "indografika01");
                $this->email_detail("babylonindografika@gmail.com", "Admin Indografika Notification", $this->input->post("email"), "Laporan Pengeluaran Excel", "");
                $this->attach_email_files("xls", $filename);
                $this->email_send();

                $this->session->set_flashdata('status_laporan_pengeluaran', '<i class="fa fa-check-circle"> Email Sent!</i>');
            } else {
                
            }
        }

        if ($this->input->post("btn_print")) {
            $data['searchby'] = $this->input->post('jenis_pengeluaran');
            $awal = $this->input->post('tanggal_awal');
            $akhir = $this->input->post('tanggal_akhir');
            if ($this->input->post('jenis_pengeluaran') != 'Semua Jenis' && $this->input->post('jenis_pengeluaran') != "Lain-Lain") {
                if ($this->input->post('jenis_pengeluaran') == 'Gaji' || $this->input->post('jenis_pengeluaran') == 'Komisi') {
                    $data['isi_tabel'] = $this->Laporan_model->select_gaji($this->input->post('jenis_pengeluaran'), $awal, $akhir, FALSE, TRUE);
                    $data['kolom'] = true;
                } else {
                    $data['isi_tabel'] = $this->Laporan_model->select_per_jenis($this->input->post('jenis_pengeluaran'), $awal, $akhir, FALSE, TRUE);
                    $data['kolom'] = false;
                }
            } else if ($this->input->post('jenis_pengeluaran') == 'Semua Jenis') {
                $data['isi_tabel'] = $this->Laporan_model->select_all_pengeluaran($awal, $akhir, FALSE, TRUE);
                $data['kolom'] = true;
            } else {
                $data['isi_tabel'] = $this->Laporan_model->select_lain_lain($awal, $akhir, FALSE, TRUE);
                $data['kolom'] = true;
            }
            $data['data'] = ($awal ? $awal : '-- ') . " s/d " . ($akhir ? $akhir : " --");
            $this->load->view('v_head');
            $this->load->view('v_navigation', $data);
            $this->load->view('v_pengeluaran_cetak', $data);
        } else {
            $this->load->view('v_head');
            $this->load->view('v_navigation', $data);
            $this->load->view('v_pengeluaran', $data);
        }
    }

    function laporan_mutasi_kas() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }
        $data["periode"] = "Laporan Bulan Ini";

//        $datestring = date("Y-m-d").' first day of last month';
//        $dt = date_create($datestring);
        $data['saldo_pindahan'] = array();

        if ($this->session->userdata("Level") == 0) {
            $data["cabangs"] = $this->Admin_model->get_all_cabang();
        }
        if ($this->input->post("btn_pilih") || $this->input->post("btn_print") || $this->input->post("btn_export") || $this->input->post("btn_email")) {
            $tgl_awal = "-";
            $tgl_akhir = "-";
            if ($this->input->post('tanggal_awal')) {
                $tgl_awal = strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_awal')));
            }
            if ($this->input->post('tanggal_akhir')) {
                $tgl_akhir = strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_akhir')));
            }
            $jenis = $this->input->post("jenis");
//            echo $tgl_awal; exit;
            $data['saldo_pindahan'] = $this->Jurnal_model->select_saldo_pindahan_kas($tgl_awal == "-" ? "-" : $tgl_awal, $tgl_akhir == "-" ? FALSE : $tgl_akhir, $this->input->post("cabang"), $jenis);
            $data['jurnals'] = $this->Jurnal_model->select_laporan_mutasi_kas($tgl_awal == "-" ? FALSE : $tgl_awal, $tgl_akhir == "-" ? FALSE : $tgl_akhir, $this->input->post("cabang"), $jenis);
            $data['periode'] = $tgl_awal . " s/d " . $tgl_akhir;
            if ($tgl_akhir == '-' || $tgl_awal == '-') {
                $data['periode'] = date('F Y');
            }

            $this->session->set_userdata("status_IDCabang", $this->input->post("cabang"));
            $this->session->set_userdata("status_jenis", $this->input->post("jenis"));
        } else {
            $data['jurnals'] = $this->Jurnal_model->select_laporan_mutasi_kas();
            $data['saldo'] = $this->Jurnal_model->get_kas();
        }
        if ($this->input->post("btn_export")) {
            $data['data_jenis'] = FALSE;
            if ($this->session->userdata('Level') == 0) {
                if ($this->input->post('cabang') != 0) {
                    $data['data_cabang'] = $this->Admin_model->get_detail_cabang($this->input->post('cabang'))->kabupaten;
                }
                $data['data_jenis'] = ($this->input->post('cabang') == 1 ? "Admin Lapangan" : "Admin Kantor" );
            } else {
                $data['data_cabang'] = $this->Admin_model->get_detail_cabang($this->session->userdata('IDCabang'))->kabupaten;
            }
            $this->excel_kas($data, "Laporan Mutasi Kas", $this->input->post("btn_export"));
        }
        $data["filter"] = "";
        if ($this->input->post("btn_submit")) {
            $data["filter"] = $this->Laporan_model->get_cabang_id($this->input->post("cabang"));
        }

        if ($this->input->post("btn_email")) {
            $this->form_validation->set_rules('email', 'email', 'required');
            if ($this->form_validation->run() == TRUE) {
				
				$data['data_jenis'] = FALSE;
				if ($this->session->userdata('Level') == 0) {
					if ($this->input->post('cabang') != 0) {
						$data['data_cabang'] = $this->Admin_model->get_detail_cabang($this->input->post('cabang'))->kabupaten;
					}
				} else {
					$data['data_cabang'] = $this->Admin_model->get_detail_cabang($this->session->userdata('IDCabang'))->kabupaten;
				}
				
                $filename = $this->excel_kas($data, "Laporan Mutasi Kas", $this->input->post('btn_email'));
                // RRyner email - 19/12/2015
                $this->email_header("babylonindografika@gmail.com", "indografika01");
                $this->email_detail("babylonindografika@gmail.com", "Admin Indografika Notification", $this->input->post("email"), "Laporan Mutasi Kas Excel", "");
                $this->attach_email_files("xls", $filename);
                $this->email_send();

                $this->session->set_flashdata('status_laporan_mutasi_kas', '<i class="fa fa-check-circle"> Email Sent!</i>');
            } else {
                
            }
        }

        $data["status_IDCabang"] = $this->session->userdata("status_IDCabang");
        $data["status_jenis"] = $this->session->userdata("status_jenis");

        if ($this->input->post("btn_print")) {
            $this->load->view('v_head');
            $this->load->view('v_navigation', $data);
            $this->load->view('v_cetak_laporan_mutasi_kas', $data);
        } else {
            $this->load->view('v_head');
            $this->load->view('v_navigation', $data);
            $this->load->view('v_laporan_mutasi_kas', $data);
        }
    }

    function laporan_mutasi_kas_bank() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }
        $data["periode"] = "Laporan Bulan Ini";
        if ($this->session->userdata("Level") == 0) {
            $data["cabangs"] = $this->Admin_model->get_all_cabang();
        }
        if ($this->input->post("btn_pilih") || $this->input->post("btn_print") || $this->input->post("btn_export") || $this->input->post("btn_email")) {
            $tgl_awal = "-";
            $tgl_akhir = "-";
            if ($this->input->post('tanggal_awal')) {
                $tgl_awal = strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_awal')));
            }
            if ($this->input->post('tanggal_akhir')) {
                $tgl_akhir = strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_akhir')));
            }
            $jenis = $this->input->post("jenis");

            $data['saldo_pindahan'] = $this->Jurnal_model->select_saldo_pindahan_kas_bank($tgl_awal == "-" ? "-" : $tgl_awal, $tgl_akhir == "-" ? FALSE : $tgl_akhir, $this->input->post("cabang"), $jenis);
            $data['jurnals'] = $this->Jurnal_model->select_laporan_mutasi_kas_bank($tgl_awal == "-" ? FALSE : $tgl_awal, $tgl_akhir == "-" ? FALSE : $tgl_akhir, $this->input->post("cabang"), FALSE);
            $data['periode'] = $tgl_awal . " s/d " . $tgl_akhir;
            if ($tgl_akhir == '-' || $tgl_awal == '-') {
                $data['periode'] = date('F Y');
            }
            $this->session->set_userdata("status_IDCabang", $this->input->post("cabang"));
        } else {
            $data['jurnals'] = $this->Jurnal_model->select_laporan_mutasi_kas_bank($this->input->post('tanggal_awal'), $this->input->post('tanggal_akhir'));
            $data['saldo'] = $this->Jurnal_model->get_kas_bank();
        }
        if ($this->input->post("btn_export")) {
            $data['data_jenis'] = FALSE;
            if ($this->session->userdata('Level') == 0) {
                if ($this->input->post('cabang') != 0) {
                    $data['data_cabang'] = $this->Admin_model->get_detail_cabang($this->input->post('cabang'))->kabupaten;
                }
            } else {
                $data['data_cabang'] = $this->Admin_model->get_detail_cabang($this->session->userdata('IDCabang'))->kabupaten;
            }
            $this->excel_kas($data, "Laporan Mutasi Kas  Bank", $this->input->post("btn_export"));
        }

        if ($this->input->post("btn_email")) {
            $this->form_validation->set_rules('email', 'email', 'required');
            if ($this->form_validation->run() == TRUE) {				
				$data['data_jenis'] = FALSE;
				if ($this->session->userdata('Level') == 0) {
					if ($this->input->post('cabang') != 0) {
						$data['data_cabang'] = $this->Admin_model->get_detail_cabang($this->input->post('cabang'))->kabupaten;
					}
				} else {
					$data['data_cabang'] = $this->Admin_model->get_detail_cabang($this->session->userdata('IDCabang'))->kabupaten;
				}
				
                $filename = $this->excel_kas($data, "Laporan Mutasi Kas Bank", $this->input->post('btn_email'));
//                $filename = $this->excel_pengeluaran($data,  $this->input->post('btn_email'));
                // RRyner email - 19/12/2015
                $this->email_header("babylonindografika@gmail.com", "indografika01");
                $this->email_detail("babylonindografika@gmail.com", "Admin Indografika Notification", $this->input->post("email"), "Laporan Mutasi Kas Bank Excel", "");
                $this->attach_email_files("xls", $filename);
                $this->email_send();

                $this->session->set_flashdata('status_laporan_mutasi_kas_bank', '<i class="fa fa-check-circle"> Email Sent!</i>');
            } else {
                
            }
        }

        $data["filter"] = "";
        if ($this->input->post("btn_submit")) {
            $data["filter"] = $this->Laporan_model->get_cabang_id($this->input->post("cabang"));
        }

        $data["status_IDCabang"] = $this->session->userdata("status_IDCabang");
        if ($this->input->post("btn_print")) {
            $this->load->view('v_head');
            $this->load->view('v_navigation', $data);
            $this->load->view('v_cetak_laporan_mutasi_kas_bank', $data);
        } else {
            $this->load->view('v_head');
            $this->load->view('v_navigation', $data);
            $this->load->view('v_laporan_mutasi_kas_bank', $data);
        }
    }

    function daftar_setoran_bank() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        if ($this->input->post('logout')) {
            $this->session->unset_userdata('Username');
            redirect('welcome/index');
        }
        $data["status"] = $this->session->flashdata("status");
        $data["laporans"] = $this->Laporan_model->get_saldo_kantor($data['IDCabang']);
        $data['saldo'] = $this->Admin_model->get_saldo_cabang($data['IDCabang']);
        $data['selectCabang'] = 0;
        if ($this->session->userdata("Level") == 0) {
            $data["cabangs"] = $this->Admin_model->get_all_cabang();
            if ($this->input->post('btn_pilih')) {
                $data['selectCabang'] = $this->input->post('cabang');
                $data["laporans"] = $this->Laporan_model->get_saldo_kantor($data['selectCabang'] == 0 ? FALSE : $data['selectCabang']);
            }
        }
        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_laporan_setoran_bank', $data);
    }

    function daftar_tarik_kas_bank() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        if ($this->input->post('logout')) {
            $this->session->unset_userdata('Username');
            redirect('welcome/index');
        }


        $data["status"] = $this->session->flashdata("status");
        $data["laporans"] = $this->Laporan_model->get_saldo_kas_bank($data['IDCabang']);

        $data['saldo_bank'] = $this->Admin_model->get_saldo_bank($data['IDCabang']);

        $data['selectCabang'] = 0;
        if ($this->session->userdata("Level") == 0) {
            $data["cabangs"] = $this->Admin_model->get_all_cabang();
            if ($this->input->post('btn_pilih')) {
                $data['selectCabang'] = $this->input->post('cabang');
                $data["laporans"] = $this->Laporan_model->get_saldo_kas_bank($data['selectCabang'] == 0 ? FALSE : $data['selectCabang']);
            }
        }

        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_laporan_tarik_bank', $data);
    }

    function tarik_kas_bank() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        $data['saldo_admin'] = $this->Jurnal_model->get_kas_bank();
        $data['IDCabang'] = $this->session->userdata('IDCabang');
        $data["status"] = $this->session->flashdata("status");

        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('total_setor', 'Total Setor', 'required|less_than_equal_to[' . $data['saldo_admin'] . ']');

        if ($this->input->post('IDCabang')) {
            if ($this->form_validation->run() == TRUE) {
                $this->Admin_model->start_trans();
                $this->Admin_model->tarik_kas_bank();
                $this->Admin_model->end_trans('tarik_kas_bank()');
                $this->session->set_flashdata('status', 'Kas Bank Telah Diambil!');
                redirect("laporan/daftar_tarik_kas_bank");
            }
        }

        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_tarik_kas_bank', $data);
    }

    function setoran_bank() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        $data['saldo_admin'] = $this->Jurnal_model->get_kas();
        $data['IDCabang'] = $this->session->userdata('IDCabang');
        $data["status"] = $this->session->flashdata("status");

        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('total_setor', 'Total Setor', 'required|less_than_equal_to[' . $data['saldo_admin'] . ']');

        if ($this->input->post('IDCabang')) {
            if ($this->form_validation->run() == TRUE) {
                $this->Admin_model->start_trans();
                $this->Admin_model->setor_bank();
                $this->Admin_model->end_trans('setoran_bank()');
                $this->session->set_flashdata('status', 'Setoran Telah Dimasukan!');
                redirect("laporan/daftar_setoran_bank");
            }
        }

        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_setoran_bank', $data);
    }

    function pembatalan_nota() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }
        $data["status"] = $this->session->flashdata("status");
        $data["laporans"] = $this->Laporan_model->select_laporan_batal();
        $data['periode'] = "Laporan Bulan Ini";
        $data['selectCabang'] = 0;
        if ($this->session->userdata("Level") == 0) {
            $data["cabangs"] = $this->Admin_model->get_all_cabang();
        }

        if ($this->input->post('btn_pilih')) {
            $data['selectCabang'] = $this->input->post('cabang');
            $awal = $this->input->post('tanggal_awal');
            $akhir = $this->input->post('tanggal_akhir');
            $data['tanggal'] = ($awal ? $awal : "--") . " s/d " . ($akhir ? $akhir : "--");
            $data["laporans"] = $this->Laporan_model->select_laporan_batal($data['selectCabang'], $awal, $akhir);
            if (!$akhir && !$akhir) {
                $data['periode'] = ' - S/D - ';
            } else {
                $data['periode'] = strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_awal'))) . " s/d " . strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_akhir')));
            }

            $data["laporan_penjualan"] = $this->Admin_model->get_detail_cabang($this->input->post("cabang"));
        }

        $data["selectCabang"] = $this->session->userdata("selectCabang");
        if ($this->input->post('btn_print')) {
            $data['selectCabang'] = $this->input->post('cabang');
            $awal = $this->input->post('tanggal_awal');
            $akhir = $this->input->post('tanggal_akhir');
//            echo $akhir; exit;
            $data['tanggal'] = ($awal ? $awal : "--") . " s/d " . ($akhir ? $akhir : "--");
            $data["laporans"] = $this->Laporan_model->select_laporan_batal($data['selectCabang'], $awal, $akhir);
            if (!$akhir && !$akhir) {
                $data['periode'] = ' - S/D - ';
            } else {
                $data['periode'] = strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_awal'))) . " s/d " . strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_akhir')));
            }

            $data["laporan_penjualan"] = $this->Admin_model->get_detail_cabang($this->input->post("cabang"));
            $data['print'] = $this->input->post('btn_print');
//            print_r($data["laporan_penjualan"]); exit;

            $this->load->view('v_head');
            $this->load->view('v_navigation', $data);
            $this->load->view('v_cetak_pembatalan_nota', $data);
        } else {
            $this->load->view('v_head');
            $this->load->view('v_navigation', $data);
            $this->load->view('v_pembatalan_nota', $data);
        }
    }

    function buat_pembatalan_nota() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('IDPenjualan', 'Nota Penjualan', 'required');

        if ($this->input->post("base_url")) {
            if ($this->form_validation->run() == TRUE) {
                $this->Admin_model->start_trans();
                $this->Admin_model->buat_pembatalan_nota();

                $this->Admin_model->end_trans('buat_pembatalan_nota()');
                $this->session->set_flashdata('status', 'Pembatalan Nota Telah Dibuat!');
                redirect("laporan/buat_pembatalan_nota");
            }
        }

        $data['laporans'] = $this->Laporan_model->select_laporan_pembatalan();
        $data["status"] = $this->session->flashdata("status");
        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_buat_pembatalan_nota', $data);
    }

    function cetak_laporan_pembatalan() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        $data["status"] = $this->session->flashdata("status");
        $data["laporans"] = $this->Laporan_model->select_laporan_batal();
        $data['periode'] = "Laporan Bulan Ini";
        $data['selectCabang'] = 0;
        if ($this->session->userdata("Level") == 0) {
            $data["cabangs"] = $this->Admin_model->get_all_cabang();
        }

        $data["selectCabang"] = $this->session->userdata("selectCabang");
        if ($this->input->post('btn_print')) {
            $data['selectCabang'] = $this->input->post('cabang');
            $awal = $this->input->post('tanggal_awal');
            $akhir = $this->input->post('tanggal_akhir');
            $data['tanggal'] = ($awal ? $awal : "--") . " s/d " . ($akhir ? $akhir : "--");
            $data["laporans"] = $this->Laporan_model->select_laporan_batal($data['selectCabang'], $awal, $akhir);
            if (!$akhir && !$akhir) {
                $data['periode'] = ' - S/D - ';
            } else {
                $data['periode'] = strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_awal'))) . " s/d " . strftime('%d-%m-%Y', strtotime($this->input->post('tanggal_akhir')));
            }

            $data["laporan_penjualan"] = $this->Admin_model->get_detail_cabang($this->input->post("cabang"));
            $data['print'] = $this->input->post('btn_print');
        }

        $data["status"] = $this->session->flashdata("status");
        $data["laporans"] = $this->Laporan_model->select_laporan_batal();
        $data["laporan_penjualan"] = $this->Admin_model->get_detail_cabang($data["IDCabang"]);

        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_cetak_pembatalan_nota', $data);
    }

    function balancing_saldo() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        $data['saldo'] = $this->Admin_model->get_saldo_cabang($data["IDCabang"]);
        $this->form_validation->set_rules('nominal', 'Nominal', 'required');

        if ($this->input->post("base_url")) {
            if ($this->form_validation->run() == TRUE) {
                $this->Admin_model->start_trans();
                $this->Laporan_model->insert_balancing_saldo();
                $this->Admin_model->end_trans('balancing_saldo()');
                $this->session->set_flashdata('status', 'Balancing Saldo Telah Dibuat!');
                redirect("laporan/balancing_saldo");
            }
        }

        $data["status"] = $this->session->flashdata("status");

        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_balancing_saldo', $data);
    }

    function get_saldo_kas_kantor() {
        echo 'Rp. ' . number_format($this->Admin_model->get_saldo_cabang($this->session->userdata("IDCabang")), 0, ',', '.') . " ,-";
    }

    function get_saldo_kas_bank() {
        echo 'Rp. ' . number_format($this->Admin_model->get_saldo_bank($this->session->userdata("IDCabang")), 0, ',', '.') . " ,-";
    }

    // Pembatalan Pengeluaran

    function laporan_pembatalan_pengeluaran() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');

            $data['saldo'] = $this->Admin_model->get_saldo_cabang($data['IDCabang']);
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

        if ($this->input->post('logout')) {
            $this->session->unset_userdata('Username');
            redirect('welcome/index');
        }
        $data["laporans"] = $this->Admin_model->get_pembatalan_pengeluaran();

        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_laporan_pembatalan_pengeluaran', $data);
    }

    function pembatalan_pengeluaran() {
        if ($this->session->userdata('Username')) {
            $data['username'] = $this->session->userdata('Username');
            $data['level'] = $this->session->userdata('Level');
            $data['IDCabang'] = $this->session->userdata('IDCabang');
        } else {
            redirect('welcome/index');
        }

        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('IDPengeluaran', 'No Laporan Pengeluaran / Gaji', 'required');

        if ($this->input->post("base_url")) {
            if ($this->form_validation->run() == TRUE) {
                $this->Admin_model->start_trans();
                $this->Admin_model->buat_pembatalan_pengeluaran();
                $this->Admin_model->end_trans('pembatalan_pengeluaran()');
                $this->session->set_flashdata('status', 'Pembatalan Pengeluaran Telah Dibuat!');
                redirect("laporan/laporan_pembatalan_pengeluaran");
            }
        }
        $data['laporan_penggajians'] = $this->Laporan_model->select_laporan_pembatalan_gaji();
        $data['laporans'] = $this->Laporan_model->select_laporan_pembatalan_pengeluaran();
        $data["status"] = $this->session->flashdata("status");
        $this->load->view('v_head');
        $this->load->view('v_navigation', $data);
        $this->load->view('v_buat_pembatalan_pengeluaran', $data);
    }

    // Excel Kas

    function excel_kas($data, $filename, $post) {
        $this->load->library('custom_excel');
        $excel = $this->custom_excel;
        $excel->declare_excel();
        $row = 1;
        /* begin */
        $excel->add_cell($filename, "A", $row++)->font(20)->merge(array(0, 5))->alignment('center');
        if ($data['data_cabang']) {
            $excel->add_cell("Cabang :", 'A', $row)->alignment('right');
            $excel->add_cell($data['data_cabang'], 'B', $row++)->merge(array(0, 4))->alignment('left');
        }
        $excel->add_cell("Periode :", 'A', $row)->alignment('right');
        $excel->add_cell($data['periode'], 'B', $row++)->merge(array(0, 4))->alignment('left');
        if ($data['data_jenis']) {
            $excel->add_cell("Jenis :", 'A', $row)->alignment('right');
            $excel->add_cell($data['data_jenis'], 'B', $row++)->merge(array(0, 4))->alignment('left');
        }

        $row++;
        $excel->add_cell('Tanggal Mutasi', 'A', $row)->alignment('center')->border()->autoWidth()->font(16);
        $excel->add_cell('Tanggal Nota Dibuat', 'B', $row)->alignment('center')->border()->autoWidth()->font(16);
        $excel->add_cell('Keterangan', 'C', $row)->alignment('center')->border()->autoWidth()->font(16);
        $excel->add_cell('Kas Masuk', 'D', $row)->alignment('center')->border()->autoWidth()->font(16);
        $excel->add_cell('Kas Keluar', 'E', $row)->alignment('center')->border()->autoWidth()->font(16);
        $excel->add_cell('Saldo Akhir', 'F', $row++)->alignment('center')->border()->autoWidth()->font(16);

        $saldo_mutasi = 0;

        $saldo_pindahan = 0;

        if ($data['saldo_pindahan'] > 0) {
            foreach ($data['saldo_pindahan'] as $saldo):
                $saldo->sifat == 'K' ? $saldo_pindahan -= $saldo->kaskeluar : $saldo_pindahan += $saldo->kasmasuk;
            endforeach;
        }

        $excel->add_cell("", "A", $row)->border();
        $excel->add_cell("", "B", $row)->border();
        $excel->add_cell("", "C", $row)->border();
        $excel->add_cell("", "D", $row)->border();
        $excel->add_cell("Saldo Sebelumnya", "E", $row)->border();
        $excel->add_cell("Rp. " . number_format($saldo_pindahan, 0, ',', '.') . ",-", "F", $row)->border();
        $row++;

        $saldo_mutasi = $saldo_pindahan;

        foreach ($data['jurnals'] as $laporan):
            $excel->add_cell(strftime("%d-%m-%Y", strtotime($laporan->tanggal)), "A", $row)->border();
            $excel->add_cell(strftime("%d-%m-%Y", strtotime($laporan->tglref)), "B", $row)->border();
            $excel->add_cell($laporan->keterangan, "C", $row)->border();
            $excel->add_cell("Rp. " . number_format($laporan->kasmasuk, 0, ",", ".") . ",-", "D", $row)->border();
            $excel->add_cell("Rp. " . number_format($laporan->kaskeluar, 0, ",", ".") . ",-", "E", $row)->border();
            $excel->add_cell("Rp. " . number_format($laporan->sifat == 'K' ? $saldo_mutasi -= $laporan->kaskeluar : $saldo_mutasi += $laporan->kasmasuk, 0, ',', '.') . ",-", "F", $row)->border();
            $row++;
        endforeach;
        /* end */
        $excel->end_excel($filename, $post);
        return $excel->get_filename();
    }

    function excel_pengeluaran($data, $post) {
// -------------------- convert to excel ------------------------        
        $this->load->library('custom_excel');
        $excel = $this->custom_excel;
        $excel->declare_excel();
        $row = 1;
        $total = 0;
        /* array = merge(berapa baris, berapa kolom) */
        $excel->add_cell("Daftar Pengeluaran", 'A', $row++)->font(20)->merge(array(0, 3))->alignment('center');
        $excel->add_cell("Jenis :", 'A', $row)->alignment('right');
        $excel->add_cell($data['searchby'], 'B', $row++)->merge(array(0, 1))->alignment('center');
        $excel->add_cell("Periode :", 'A', $row)->alignment('right');
        $excel->add_cell($data['data'], 'B', $row++)->merge(array(0, 1))->alignment('center');
        $row++;
        $excel->add_cell('Tanggal', 'A', $row)->alignment('center')->border()->autoWidth();
        $excel->add_cell('No. Laporan', 'B', $row)->alignment('center')->border()->autoWidth();
        if ($data['kolom']) {
            $excel->add_cell("Keterangan", 'C', $row)->border()->autoWidth()->alignment('center');
        }
        $excel->add_cell("Jumlah", !$data['kolom'] ? 'C' : 'D', $row++)->border()->alignment('center');

        // saldo pindahan ronald - 19/12/2015

        foreach ($data['isi_tabel'] as $isi):
            $excel->add_cell(date('d-m-Y', strtotime($isi->tanggal)), "A", $row)->border()->autoWidth();
            $excel->add_cell($isi->no_laporan, "B", $row)->border()->autoWidth();
            if ($data['kolom']) {
                $excel->add_cell($isi->keterangan . ($isi->keterangan_lanjut != "" ? " - " . $isi->keterangan_lanjut : "" ), "C", $row)->border()->autoWidth();
            }
            $excel->add_cell("Rp. " . number_format($isi->jumlah, 0, ',', '.') . ",-", !$data['kolom'] ? 'C' : 'D', $row++)->border();
            $total += intval($isi->jumlah);
        endforeach;
        $excel->add_cell('Total :', !$data['kolom'] ? 'B' : 'C', $row)->alignment('right')->autoWidth();
        $excel->add_cell("Rp. " . number_format($total, 0, ',', '.') . ",-", !$data['kolom'] ? 'C' : 'D', $row++)->border()->autoWidth();

        $excel->end_excel("laporan_pengeluaran", $post);
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

    function migrasi_referensi() {
        //$this->Jurnal_model->migrasi_referensi();
        //$this->Jurnal_model->update_referensi();
    }
}
