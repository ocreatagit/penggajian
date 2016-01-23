<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//echo "\nDatabase error: ",
//	$heading,
//	"\n\n",
//	$message,
//	"\n\n";
$this->load->database();
$data = array(
    'heading' => $heading,
    'message' => $message,
    'date' => date('Y-m-d H:i:s')
);
$this->db->insert("logs", $data);