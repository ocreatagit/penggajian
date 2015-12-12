<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Administrator</title>   

        <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet">     

        <link href="<?php echo base_url(); ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <link href="<?php echo base_url(); ?>jquery-ui/jquery-ui.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>Datatable/css/jquery.dataTables.css" rel="stylesheet">

        <link href="<?php echo base_url(); ?>Datatable/css/jquery.dataTables.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>Datatable/css/jquery.dataTables.css" rel="stylesheet">

        <!-- include the style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>alertify/css/alertify.min.css" />
        <!-- include a theme -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>alertify/css/themes/default.min.css" />



        <style type="text/css">
            body{
                font-family: Tahoma;
                background: #F9F6F1 none repeat scroll 0% 0%;
            }
            #kiri, #kanan{
                height: 600px;
                margin-top: -20px;
            }
            #kiri{
                background-color: yellow;
            }
            #kanan{
                background-color: black;
            }
            .siku{
                border-radius: 0px;
            }
            /*
            .navbar-default .navbar-nav > li > a{
                color: white;
            }
            .navbar-default .navbar-nav > .active > a, 
            .navbar-default .navbar-nav > .active > a:hover, 
            .navbar-default .navbar-nav > .active > a:focus {
                color: black;
                background: transparent;
                box-shadow: none !important;
                -webkit-box-shadow: none !important;
                box-shadow: 0px 3px 9px rgba(0, 0, 0, 0.075) inset;
            }
            .navbar-default .navbar-nav > .active > a, 
            .navbar-default .navbar-nav > .open > a{
                color: black;
                background: transparent;
                box-shadow: none !important;
                -webkit-box-shadow: none !important; 
            }
            .navbar-default .navbar-nav > .open > a, 
            .navbar-default .navbar-nav > .open > a:focus, 
            .navbar-default .navbar-nav > .open > a:hover,
            .navbar-default .navbar-nav > .open > a:checked{
                background-color: transparent;    
            }
            .navbar-default .navbar-nav > .active{ 
                padding-left: 15px;
                color: #333;
                text-decoration: none;
                background-color: transparent;
                background-image: url(<?php echo base_url(); ?>images/lineactive.png);
                background-repeat: repeat-x;
            }
            ul.nav > li > a:hover {
                color: #333;
                text-decoration: none;
                background-color: transparent;
                background-image: url(<?php echo base_url(); ?>images/line.png);
                background-repeat: repeat-x;
                                                background-color: transparent;
                                                color: black;
                                                border-style: none;
            }

            ul.dropdown-menu > li > a:hover{ 
                color: #333;
                text-decoration: none;
                background-color: transparent;
                background-image: url(<?php echo base_url(); ?>images/line30.png);
                background-repeat: repeat-x;
                box-shadow: none !important;
                -webkit-box-shadow: none !important;
            }*/

            /* Kategori Produk */
            .panel-heading{
                border-color: transparent;
                background-color: red;
            }
            /*            .list-group-item.active, .list-group-item.active:focus, .list-group-item.active:hover{
                            
                        }*/

            // Ronald Tambah

            .marginBottom-0 {margin-bottom:0;}
            .dropdown-submenu{position:relative;}
            .dropdown-submenu>.dropdown-menu{
                top:0;
                left:100%;
                margin-top:-6px;margin-left:-1px;-webkit-border-radius: 0;-moz-border-radius: 0;border-radius: 0px;
            }
            .dropdown-submenu>a:after{display:block;content:" ";float:right;width:0;height:0;border-color:transparent;border-style:solid;border-width:5px 0 5px 5px;border-left-color:#cccccc;margin-top:5px;margin-right:-10px;}
            .dropdown-submenu:hover>a:after{border-left-color:#555;}
            .dropdown-submenu.pull-left{float:none;}.dropdown-submenu.pull-left>.dropdown-menu{left:-100%;margin-left:10px;-webkit-border-radius:6px 0 6px 6px;-moz-border-radius:6px 0 6px 6px;border-radius:6px 0 6px 6px;}


            // END Ronald Tambah

            .warna{
                color: red;
            }

            body a, p, label, h3 {
                font-size: 18px;
            }

            body label{
                font-weight: normal;
            }

            #tengah{
                text-align: center;
            }

            table{
                width: 100%;
            }
            table, td, th {
                border: 1px solid black;
            }
            th{
                padding: 5px;
                background-color: silver;
            }
            td {
                padding: 5px;
            }
            .datatable_header th{
                background-color: #2B669A;
                color: white;
            }
            #datatable thead tr td{
                color: white;
            }
            
/*            .dropdown-menu li:hover{
                background-color: cyan;
            }
            .nav li ul li:hover{
                background-color: cyan;
            }*/
        </style>
        <script src="<?php echo base_url(); ?>bootstrap/js/jquery.js"></script>
    </head>
    <body style="background-color: whitesmoke;">