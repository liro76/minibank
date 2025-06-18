<?php
// Pastikan baris error_reporting ini ada di sini saat debugging!
error_reporting(E_ALL);
ini_set('display_errors', 1);

// PERBAIKI JALUR INCLUDE DI SINI
include '../../inc/koneksi.php'; // Sesuaikan jalur jika berbeda
include '../../inc/rupiah.php'; // Sesuaikan jalur jika berbeda

$nis = ''; // Inisialisasi NIS
if (isset($_POST["nis"])) {
    $nis = (int)mysqli_real_escape_string($koneksi, $_POST["nis"]); // Ambil NIS dan konversi ke INT
} else {
    // Jika tidak ada NIS dari POST, mungkin pengguna langsung mengakses halaman ini
    // Bisa redirect kembali ke halaman pencarian atau tampilkan pesan.
    echo "<script>
    Swal.fire({title: 'Peringatan',text: 'Siswa belum dipilih atau akses tidak valid.',icon: 'warning',confirmButtonText: 'OK'
    }).then((result) => {if (result.value)
        {window.location = '?page=view_tabungan';} // Kembali ke halaman pencarian siswa
    })</script>";
    exit; // Hentikan eksekusi script
}
?>

<?php
// Inisialisasi total setoran dan tarikan
$setor = 0;
$tarik = 0;

// Query untuk total setoran
$sql_setor_sum = $koneksi->query("SELECT SUM(setor) as Tsetor FROM tb_tabungan WHERE jenis='ST' AND nis='$nis'");
if ($sql_setor_sum && $data_setor_sum = $sql_setor_sum->fetch_assoc()) {
    $setor = (int)$data_setor_sum['Tsetor']; // Konversi ke INT, akan jadi 0 jika NULL
}

// Query untuk total tarikan
$sql_tarik_sum = $koneksi->query("SELECT SUM(tarik) as Ttarik FROM tb_tabungan WHERE jenis='TR' AND nis='$nis'");
if ($sql_tarik_sum && $data_tarik_sum = $sql_tarik_sum->fetch_assoc()) {
    $tarik = (int)$data_tarik_sum['Ttarik']; // Konversi ke INT, akan jadi 0 jika NULL
}
?>


<section class="content-header">
    <h1>
        Tabungan
        <small>Siswa</small>
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="index.php">
                <i class="fa fa-home"></i>
                <b>Bank Mini</b>
            </a>
        </li>
    </ol>
</section>
<section class="content">

    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4>
            <i class="icon fa fa-info"></i> Info Tabungan</h4>

        <h4>
            Tot. Setoran :
            <?php echo rupiah($setor); ?>
        </h4>
        <h4>
            Tot. Tarikan :
            <?php echo rupiah($tarik); ?>
        </h4>
        <hr>
        <h3>Saldo Tabungan :
            <?php
            $sql_saldo = $koneksi->query("SELECT saldo FROM tb_siswa WHERE nis='$nis'");
            if ($sql_saldo && $data_saldo = $sql_saldo->fetch_assoc()) {
                echo rupiah((int)$data_saldo['saldo']); // Konversi ke INT
            } else {
                echo rupiah(0); // Tampilkan 0 jika siswa tidak ditemukan atau saldo tidak ada
            }
            ?>
        </h3>
    </div>


    <div class="box box-primary">
        <div class="box-header">
            <a href="?page=view_tabungan" class="btn btn-primary">
                <i class="glyphicon glyphicon-arrow-left"></i> Kembali</a>
            <a href="./report/cetak-tabungan.php?nis=<?php echo $nis ?>" target=" _blank"
             class="btn btn-default">
                <i class="glyphicon glyphicon-print"></i> Cetak</a>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove">
                    <i class="fa fa-remove"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Setoran</th>
                            <th>Tarikan</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                    $no = 1;
                    $sql_transaksi = $koneksi->query("SELECT s.nis, s.nama_siswa, t.id_tabungan, t.setor, t.tarik, t.tgl, t.jenis, t.petugas
                    FROM tb_siswa s JOIN tb_tabungan t ON s.nis=t.nis
                    WHERE s.nis ='$nis' ORDER BY t.tgl ASC");

                    if ($sql_transaksi) {
                        while ($data_transaksi = $sql_transaksi->fetch_assoc()) {
                    ?>

                        <tr>
                            <td>
                                <?php echo $no++; ?>
                            </td>
                            <td>
                                <?php echo $data_transaksi['nis']; ?>
                            </td>
                            <td>
                                <?php echo $data_transaksi['nama_siswa']; ?>
                            </td>
                            <td>
                                <?php $tgl = $data_transaksi['tgl']; echo date("d/M/Y", strtotime($tgl))?>
                            </td>
                            <td align="right">
                                <?php echo rupiah($data_transaksi['setor']); ?>
                            </td>
                            <td align="right">
                                <?php echo rupiah($data_transaksi['tarik']); ?>
                            </td>
                            <td>
                                <?php echo $data_transaksi['petugas']; ?>
                            </td>
                        </tr>
                        <?php
                        }
                    } else {
                        echo "<tr><td colspan='7'>Error mengambil data transaksi: " . mysqli_error($koneksi) . "</td></tr>";
                    }
                    ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</section>