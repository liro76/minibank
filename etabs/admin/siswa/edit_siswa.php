<?php
// Pastikan koneksi.php sudah di-include di index.php utama atau di awal file ini
// Contoh: include '../inc/koneksi.php';
// Pastikan juga rupiah.php sudah di-include atau fungsi rupiah tersedia.
// Contoh: include '../inc/rupiah.php';

    if(isset($_GET['kode'])){
        $nis_siswa_url = mysqli_real_escape_string($koneksi, $_GET['kode']);
        // Bergabung dengan tb_kelas untuk mendapatkan nama kelas
        $sql_cek = "SELECT s.*, k.kelas FROM tb_siswa s JOIN tb_kelas k ON s.id_kelas = k.id_kelas WHERE s.nis='" . $nis_siswa_url . "'";
        $query_cek = mysqli_query($koneksi, $sql_cek);
        $data_cek = mysqli_fetch_array($query_cek,MYSQLI_BOTH);

        // Untuk tujuan tampilan "Saldo Awal" dan "Saldo Saat Ini"
        // Kita akan gunakan kolom 'saldo' dari database.
        // Saldo awal adalah nilai 'saldo' saat data siswa dibuat/diupdate pertama kali.
        // Saldo saat ini adalah nilai 'saldo' yang selalu terupdate.
        $saldo_awal_tampilan = $data_cek['saldo']; // Asumsi: ini adalah saldo awal saat terakhir kali di-input atau di-update manual
        $saldo_saat_ini_tampilan = $data_cek['saldo']; // Ini adalah saldo yang selalu terkini
    }
?>

<section class="content-header">
    <h1>
        Master Data
        <small>siswa</small>
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
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah siswa</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove">
                            <i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="box-body">

                        <div class="form-group">
                            <label>NIS</label>
                            <input type='text' class="form-control" name="nis" value="<?php echo $data_cek['nis']; ?>"
                            readonly>
                        </div>

                        <div class="form-group">
                            <label>Saldo Awal (Rp.)</label>
                            <input type="text" value="<?php echo rupiah($saldo_awal_tampilan); ?>" class="form-control" readonly>
                            <small class="form-text text-muted">Ini adalah saldo saat siswa pertama kali didaftarkan atau terakhir diubah secara manual.</small>
                        </div>

                        <div class="form-group">
                            <label>Saldo Saat Ini (Rp.)</label>
                            <input type="text" value="<?php echo rupiah($saldo_saat_ini_tampilan); ?>" class="form-control" readonly>
                            <small class="form-text text-muted">Saldo ini diperbarui otomatis oleh transaksi (setoran/penarikan).</small>
                        </div>
                        <div class="form-group">
                            <label>Nama siswa</label>
                            <input class="form-control" name="nama_siswa" value="<?php echo $data_cek['nama_siswa']; ?>"
                            required />
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jekel" id="jekel" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <?php
                                if ($data_cek['jekel'] == "LK") echo "<option value='LK' selected>Laki-laki</option>";
                                else echo "<option value='LK'>Laki-laki</option>";
                                
                                if ($data_cek['jekel'] == "PR") echo "<option value='PR' selected>Perempuan</option>";
                                else echo "<option value='PR'>Perempuan</option>";
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Kelas</label>
                            <select name="id_kelas" id="id_kelas" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <?php
                                $query = "select id_kelas, kelas from tb_kelas"; // Ambil id_kelas dan kelas
                                $hasil = mysqli_query($koneksi, $query);
                                while ($row = mysqli_fetch_array($hasil)) {
                                ?>
                                <option value="<?php echo $row['id_kelas'] ?>" <?=$data_cek['id_kelas']==$row[ 'id_kelas'] ? "selected" : null ?>>
                                    <?php echo $row['kelas'] ?>
                                </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Th Masuk</label>
                            <input type="number" class="form-control" name="th_masuk" value="<?php echo $data_cek['th_masuk']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <?php
                                if ($data_cek['status'] == "Aktif") echo "<option value='Aktif' selected>Aktif</option>";
                                else echo "<option value='Aktif'>Aktif</option>";
                                
                                if ($data_cek['status'] == "Lulus") echo "<option value='Lulus' selected>Lulus</option>";
                                else echo "<option value='Lulus'>Lulus</option>";

                                if ($data_cek['status'] == "Pindah") echo "<option value='Pindah' selected>Pindah</option>";
                                else echo "<option value='Pindah'>Pindah</option>";
                                ?>
                            </select>
                        </div>

                    </div>
                    <div class="box-footer">
                        <input type="submit" name="Ubah" value="Ubah" class="btn btn-success">
                        <a href="?page=MyApp/data_siswa" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
            </div>
    </div>
</section>

<?php

if (isset ($_POST['Ubah'])){
    //mulai proses ubah
    $nis_lama = mysqli_real_escape_string($koneksi, $_POST['nis']); // Menggunakan NIS dari readonly input sebagai NIS lama
    $nama_siswa = mysqli_real_escape_string($koneksi, $_POST['nama_siswa']);
    $jekel = mysqli_real_escape_string($koneksi, $_POST['jekel']);
    $id_kelas = mysqli_real_escape_string($koneksi, $_POST['id_kelas']);
    $th_masuk = mysqli_real_escape_string($koneksi, $_POST['th_masuk']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    
    // ** PENTING: Kolom 'saldo' TIDAK di-update dari form edit ini. **
    // Saldo hanya akan di-update melalui transaksi setoran/penarikan.
    $sql_ubah = "UPDATE tb_siswa SET
        nama_siswa='$nama_siswa',
        jekel='$jekel',
        id_kelas='$id_kelas',
        th_masuk='$th_masuk',
        status='$status'
        WHERE nis='$nis_lama'"; // Gunakan NIS sebagai primary key
    $query_ubah = mysqli_query($koneksi, $sql_ubah);

    if ($query_ubah) {
        echo "<script>
        Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_siswa';
            }
        })</script>";
        }else{
        echo "<script>
        Swal.fire({title: 'Ubah Data Gagal',text: 'Terjadi kesalahan: " . mysqli_error($koneksi) . "',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_siswa';
            }
        })</script>";
    }
}
?>