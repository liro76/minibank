<?php

$sql_cek = mysqli_query($koneksi, "SELECT saldo FROM tb_siswa WHERE nis='$nis'");
$data_cek = mysqli_fetch_array($sql_cek);
$saldo_sekarang = $data_cek['saldo'];

if ($saldo_sekarang < $tarik) {
    // Saldo tidak cukup, tampilkan pesan error dan hentikan proses.
    echo "<script>alert('Saldo tidak mencukupi untuk melakukan penarikan!');window.location = 'index.php?page=add_tarik';</script>";
} else {
// Pastikan koneksi.php sudah di-include di index.php utama atau di awal file ini jika diakses langsung.
// Contoh: include '../inc/koneksi.php';
// Pastikan juga rupiah.php sudah di-include atau fungsi rupiah tersedia.
// Contoh: include '../inc/rupiah.php';

$data_nama = $_SESSION["ses_nama"];

date_default_timezone_set("Asia/Jakarta");
$tanggal = date("Y-m-d");
?>

<section class="content-header">
    <h1>
        Transaksi
        <small>Tarikan</small>
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
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Tarikan</h3>
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
                            <label>Siswa</label>
                            <select name="nis" id="nis_tarik" class="form-control select2" style="width: 100%;" required>
                                <option selected="selected">-- Pilih --</option>
                                <?php
                                $query = "select nis, nama_siswa from tb_siswa where status='Aktif' ORDER BY nama_siswa ASC";
                                $hasil = mysqli_query($koneksi, $query);
                                while ($row = mysqli_fetch_array($hasil)) {
                                ?>
                                <option value="<?php echo $row['nis'] ?>">
                                    <?php echo $row['nis'] ?> - <?php echo $row['nama_siswa'] ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Saldo Saat Ini (Rp.)</label>
                            <input type="text" id="saldo_saat_ini_display_tarik" class="form-control" placeholder="Saldo Saat Ini" readonly>
                            <small class="form-text text-muted">Saldo siswa sebelum penarikan.</small>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Tarikan (Rp.)</label>
                            <input type="text" name="tarik" id="tarik" class="form-control" placeholder="Jumlah tarikan" required>
                        </div>

                        <div class="form-group">
                            <label>Estimasi Saldo Akhir (Rp.)</label>
                            <input type="text" id="estimasi_saldo_akhir_display_tarik" class="form-control" placeholder="Estimasi Saldo Akhir" readonly>
                            <small class="form-text text-muted">Perkiraan saldo setelah penarikan.</small>
                        </div>

                    </div>
                    <div class="box-footer">
                        <input type="submit" name="Simpan" value="Tarik" class="btn btn-success">
                        <a href="?page=data_tarik" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
if (isset ($_POST['Simpan'])){

    $nis = (int)mysqli_real_escape_string($koneksi, $_POST['nis']);
    $tarik_input = $_POST['tarik'];
    $tarik_bersih = (int)preg_replace("/[^0-9]/", "", $tarik_input); // Sanitasi dan konversi ke integer

    $sql_get_saldo = "SELECT saldo FROM tb_siswa WHERE nis='$nis' FOR UPDATE";
    $query_get_saldo = mysqli_query($koneksi, $sql_get_saldo);

    if ($query_get_saldo && mysqli_num_rows($query_get_saldo) > 0) {
        $data_siswa_db = mysqli_fetch_assoc($query_get_saldo);
        $saldo_sekarang = (int)$data_siswa_db['saldo']; // Pastikan saldo juga di cast ke int

        if($saldo_sekarang >= $tarik_bersih){
            $saldo_baru = $saldo_sekarang - $tarik_bersih;

            $sql_simpan_tabungan = "INSERT INTO tb_tabungan (nis,setor,tarik,tgl,jenis,petugas) VALUES (
                '$nis',
                '0',
                '$tarik_bersih',
                '$tanggal',
                'TR',
                '".mysqli_real_escape_string($koneksi, $data_nama)."')";
            $query_simpan_tabungan = mysqli_query($koneksi, $sql_simpan_tabungan);

            if ($query_simpan_tabungan) {
                $sql_update_saldo_siswa = "UPDATE tb_siswa SET saldo = '$saldo_baru' WHERE nis = '$nis'";
                $query_update_saldo_siswa = mysqli_query($koneksi, $sql_update_saldo_siswa);

                if ($query_update_saldo_siswa) {
                    echo "<script>
                    Swal.fire({title: 'Tarikan Berhasil',text: 'Saldo siswa telah diperbarui.',icon: 'success',confirmButtonText: 'OK'
                    }).then((result) => {if (result.value)
                        {window.location = 'index.php?page=data_tarik';}
                    })</script>";
                } else {
                    echo "<script>
                    Swal.fire({title: 'Tarikan Gagal',text: 'Terjadi kesalahan saat memperbarui saldo siswa: " . mysqli_error($koneksi) . "',icon: 'error',confirmButtonText: 'OK'
                    }).then((result) => {if (result.value)
                        {window.location = 'index.php?page=add_tarik';}
                    })</script>";
                }
            } else {
                echo "<script>
                Swal.fire({title: 'Tarikan Gagal',text: 'Terjadi kesalahan saat menyimpan transaksi penarikan: " . mysqli_error($koneksi) . "',icon: 'error',confirmButtonText: 'OK'
                }).then((result) => {if (result.value)
                    {window.location = 'index.php?page=add_tarik';}
                    })</script>";
            }
        } else {
            echo "<script>
            Swal.fire({title: 'Tarikan Gagal',text: 'Saldo tidak mencukupi! Saldo saat ini: " . rupiah($saldo_sekarang) . "',icon: 'error',confirmButtonText: 'OK'
            }).then((result) => {if (result.value)
                {window.location = 'index.php?page=add_tarik';}
            })</script>";
        }
    } else {
        echo "<script>
        Swal.fire({title: 'Siswa Tidak Ditemukan',text: 'NIS Siswa tidak valid atau tidak aktif.',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {if (result.value)
            {window.location = 'index.php?page=add_tarik';}
        })</script>";
    }
}
?>

<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../../plugins/select2/select2.full.min.js"></script>
<link href="../../plugins/select2/select2.min.css" rel="stylesheet" />
<script>
    $(function () {
        $('.select2').select2();
    });
</script>

<script>
    $(document).ready(function(){
        function getSaldoSiswaTarik(nis_siswa) {
            if (nis_siswa) {
                $.ajax({
                    url: '../../plugins/proses-ajax.php', // Perbaikan jalur untuk AJAX
                    type: 'POST',
                    data: {
                        action: 'get_saldo_siswa',
                        nis_siswa: nis_siswa
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#saldo_saat_ini_display_tarik').val(formatRupiah(response.saldo));
                            hitungEstimasiSaldoTarik();
                        } else {
                            $('#saldo_saat_ini_display_tarik').val('0');
                            $('#estimasi_saldo_akhir_display_tarik').val('0');
                            console.error('Error fetching saldo: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + ', ' + error + ' - Response: ' + xhr.responseText);
                        $('#saldo_saat_ini_display_tarik').val('Error');
                        $('#estimasi_saldo_akhir_display_tarik').val('Error');
                    }
                });
            } else {
                $('#saldo_saat_ini_display_tarik').val('');
                $('#estimasi_saldo_akhir_display_tarik').val('');
            }
        }

        function hitungEstimasiSaldoTarik() {
            var saldoSekarangText = <span class="math-inline">\('\#saldo\_saat\_ini\_display\_tarik'\)\.val\(\);
var saldoSekarang \= parseInt\(saldoSekarangText\.replace\(/\[^0\-9\]/g,""\)\) \|\| 0;
var jumlahTarik \= parseInt\(</span>('#tarik').val().replace(/[^0-9]/g,"")) || 0;
            var estimasiSaldoAkhir = saldoSekarang - jumlahTarik;
            $('#estimasi_saldo_akhir_display_tarik').val(formatRupiah(estimasiSaldoAkhir));
        }

        // Event listener untuk memformat input saat mengetik
        $('#tarik').on('keyup', function() {
            var val = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(formatRupiah(val).replace('Rp. ', ''));
            hitungEstimasiSaldoTarik();
        });


        $('#nis_tarik').on('change', function() {
            var nisSiswaTerpilih = <span class="math-inline">\(this\)\.val\(\);
getSaldoSiswaTarik\(nisSiswaTerpilih\);
\}\);
<411\>function formatRupiah\(angka\)\{
var number\_string \= angka\.<413\>toString\(\),
sisa     		\= number\_string\.length % 3,
rupiah     		\= number\_string\.substr\(0, sisa\),
ribuan     		\= number\_string\.substr\(sisa\)\.match\(/\\d\{3\}/g\);</411\>
if\(ribuan\)\{
separator \= sisa ? '\.' \: '';
rupiah \+\= separator \+ ribuan\.join\('\.'\);
\}
return 'Rp\. ' \+ rupiah;</413\>
\}
if \(</span>('#nis_tarik').val() && <span class="math-inline">\('\#nis\_tarik'\)\.val\(\) \!\=\= '\-\- Pilih \-\-'\) \{
getSaldoSiswaTarik\(</span>('#nis_tarik').val());
        }
    });
</script>
    }
