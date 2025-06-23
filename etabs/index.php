<?php
    //Mulai Sesi
    session_start();
    if (isset($_SESSION["ses_username"])==""){
        header("location: login.php");
    
    }else{
      $data_id = $_SESSION["ses_id"];
      $data_nama = $_SESSION["ses_nama"];
      $data_user = $_SESSION["ses_username"];
      $data_level = $_SESSION["ses_level"];
    }

    //KONEKSI DB
    include "inc/koneksi.php";
    //FUNGSI RUPIAH
    include "inc/rupiah.php";
    //Profil Sekolah
    $sql = $koneksi->query("SELECT * from tb_profil");
    $nama_sekolah = "Bank Mini"; // Default name
    while ($data= $sql->fetch_assoc()) {
        $nama_sekolah = $data['nama_sekolah'];
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>BANK MINI</title>
    <link rel="icon" href="dist/img/logo.png">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="plugins/select2/select2.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/select2/select2.full.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="dist/js/app.min.js"></script>
    </head>

<body class="hold-transition skin-red sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <a href="index.php" class="logo">
                <span class="logo-lg">
                    <img src="dist/img/logo.png" width="45px">
                    <b>BANK MINI</b>
                </span>
            </a>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown messages-menu">
                            <a class="dropdown-toggle" data-toggle="dropdown">
                                <span>
                                    <b>
                                        <?php echo $nama_sekolah; ?>
                                    </b>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="dist/img/avatar.png" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>
                            <?php echo $data_nama; ?>
                        </p>
                        <span class="label label-success">
                            <?php echo $data_level; ?>
                        </span>
                    </div>
                </div>
                <br>
                <ul class="sidebar-menu">
                    <li class="header">MAIN NAVIGATION</li>

                    <?php
                        if ($data_level=="Administrator"){
                    ?>

                    <li>
                        <a href="?page=admin">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-folder"></i> <span>Master Data</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="?page=MyApp/data_siswa"><i class="fa fa-users"></i>Siswa</a></li>
                            <li><a href="?page=MyApp/data_kelas"><i class="fa fa-feed"></i>Kelas</a></li>
                        </ul>
                    </li>

                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-exchange"></i> <span>Transaksi</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="?page=data_setor"><i class="fa fa-arrow-circle-o-down"></i>Setoran</a></li>
                            <li><a href="?page=data_tarik"><i class="fa fa-arrow-circle-o-up"></i>Penarikan</a></li>
                            <li><a href="?page=view_kas"><i class="fa fa-pie-chart"></i>Info Kas</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="?page=data_tabungan">
                            <i class="fa fa-book"></i> <span>Buku Tabungan</span>
                        </a>
                    </li>
                    
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-files-o"></i> <span>Laporan</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="?page=laporan"><i class="fa fa-file-text-o"></i>Laporan Rekap</a></li>
                            <li><a href="?page=laporan1"><i class="fa fa-file-text"></i>Laporan Detail</a></li>
                            <li><a href="?page=laporan_harian"><i class="fa fa-calendar-check-o"></i>Laporan Harian</a></li>
                        </ul>
                    </li>
                                        
                    <li class="header">SETTING</li>

                    <li>
                        <a href="?page=MyApp/data_pengguna">
                            <i class="fa fa-user"></i> <span>Pengguna Sistem</span>
                        </a>
                    </li>
                    <li>
                        <a href="?page=MyApp/data_profil">
                            <i class="fa fa-bank"></i> <span>Profil Sekolah</span>
                        </a>
                    </li>

                    <?php
                        } elseif($data_level=="Petugas"){
                    ?>

                    <li>
                        <a href="?page=petugas">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-folder"></i> <span>Master Data</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="?page=MyApp/data_siswa1"><i class="fa fa-users"></i>Siswa</a></li>
                        </ul>
                    </li>
                    
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-exchange"></i> <span>Transaksi</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="?page=data_setor"><i class="fa fa-arrow-circle-o-down"></i>Setoran</a></li>
                            <li><a href="?page=data_tarik"><i class="fa fa-arrow-circle-o-up"></i>Penarikan</a></li>
                            <li><a href="?page=view_kas"><i class="fa fa-pie-chart"></i>Info Kas</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="?page=data_tabungan">
                            <i class="fa fa-book"></i> <span>Buku Tabungan</span>
                        </a>
                    </li>

                    <li>
                        <a href="?page=laporan_harian">
                            <i class="fa fa-file-o"></i> <span>Laporan Harian</span>
                        </a>
                    </li>

                    <?php
                        }
                    ?>

                    <li>
                        <a href="logout.php" onclick="return confirm('Anda yakin keluar dari aplikasi ?')">
                            <i class="fa fa-sign-out"></i>
                            <span>Logout</span>
                        </a>
                    </li>

                </ul>
            </section>
            </aside>

        <div class="content-wrapper">
            <section class="content">
                <?php 
                    if(isset($_GET['page'])){
                        $hal = $_GET['page'];
                
                        switch ($hal) {
                            // Halaman Home
                            case 'admin': include "home/admin.php"; break;
                            case 'petugas': include "home/petugas.php"; break;
                        
                            // Administrator
                            case 'MyApp/data_pengguna': include "admin/pengguna/data_pengguna.php"; break;
                            case 'MyApp/add_pengguna': include "admin/pengguna/add_pengguna.php"; break;
                            case 'MyApp/edit_pengguna': include "admin/pengguna/edit_pengguna.php"; break;
                            case 'MyApp/del_pengguna': include "admin/pengguna/del_pengguna.php"; break;
                            case 'MyApp/data_profil': include "admin/profil/data_profil.php"; break;
                            case 'MyApp/edit_profil': include "admin/profil/edit_profil.php"; break;
                            case 'MyApp/data_kelas': include "admin/kelas/data_kelas.php"; break;
                            case 'MyApp/add_kelas': include "admin/kelas/add_kelas.php"; break;
                            case 'MyApp/edit_kelas': include "admin/kelas/edit_kelas.php"; break;
                            case 'MyApp/del_kelas': include "admin/kelas/del_kelas.php"; break;
                            case 'MyApp/data_siswa': include "admin/siswa/data_siswa.php"; break;
                            case 'MyApp/add_siswa': include "admin/siswa/add_siswa.php"; break;
                            case 'MyApp/edit_siswa': include "admin/siswa/edit_siswa.php"; break;
                            case 'MyApp/del_siswa': include "admin/siswa/del_siswa.php"; break;
                            
                            // Petugas
                            case 'MyApp/data_siswa1': include "petugas/siswa/data_siswa1.php"; break;
                            case 'MyApp/add_siswa1': include "petugas/siswa/add_siswa1.php"; break;
                            case 'MyApp/edit_siswa1': include "petugas/siswa/edit_siswa1.php"; break;
                            case 'MyApp/del_siswa1': include "petugas/siswa/del_siswa1.php"; break;  
                            case 'data_setor': include "petugas/setor/data_setor.php"; break;
                            case 'add_setor': include "petugas/setor/add_setor.php"; break;
                            case 'edit_setor': include "petugas/setor/edit_setor.php"; break;
                            case 'del_setor': include "petugas/setor/del_setor.php"; break;
                            case 'data_tarik': include "petugas/tarik/data_tarik.php"; break;
                            case 'add_tarik': include "petugas/tarik/add_tarik.php"; break;
                            case 'edit_tarik': include "petugas/tarik/edit_tarik.php"; break;
                            case 'del_tarik': include "petugas/tarik/del_tarik.php"; break;
                            case 'data_tabungan': include "petugas/tabungan/data_tabungan.php"; break;
                            case 'view_tabungan': include "petugas/tabungan/view_tabungan.php"; break;
                            case 'view_kas': include "petugas/kas/view_kas.php"; break;
                            case 'laporan': include "petugas/laporan/view_laporan.php"; break;
                            case 'laporan1': include "petugas/laporan/view_laporan1.php"; break;
                            case 'laporan_harian': include "petugas/laporan/view_laporan_harian.php"; break;
                    
                            // Default
                            default:
                                echo "<center><br><br><br><h1> Halaman tidak ditemukan !</h1></center>";
                                break;    
                        }
                    } else {
                        // Halaman default sesuai level
                        if($data_level=="Administrator"){
                            include "home/admin.php";
                        } elseif($data_level=="Petugas"){
                            include "home/petugas.php";
                        }
                    }
                ?>
            </section>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; <?php echo date("Y"); ?> BANK MINI SMKN 18 JAKARTA</strong>
        </footer>
        <div class="control-sidebar-bg"></div>

    </div>
    <script>
        $(function() {
            // Inisialisasi DataTable
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });

            // Inisialisasi Select2
            $(".select2").select2();
        });
    </script>
</body>
</html>