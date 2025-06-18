<?php
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
        <small>Setoran</small>
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
                    <h3 class="box-title">Tambah Setoran</h3>
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
                            <select name="nis" id="nis" class="form-control select2" style="width: 100%;" required>
                                <option selected="selected">-- Pilih --</option>
                                <?php
                                $query = "select nis, nama_siswa from tb_siswa where status='Aktif' ORDER BY nama_siswa ASC";
                                $hasil = mysqli_query($koneksi, $query);
                                while ($row = mysqli_fetch_array($hasil)) {
                                ?>
                                <option value="<?php echo $row['nis'] ?>">
                                    <?php echo $row['nis'] ?>
                                    -
                                    <?php echo $row['nama_siswa'] ?>
                                </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Saldo Saat Ini (Rp.)</label>
                            <input type="text" id="saldo_saat_ini_display" class="form-control" placeholder="Saldo Saat Ini" readonly>
                            <small class="form-text text-muted">Saldo siswa sebelum setoran.</small>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Setoran (Rp.)</label>
                            <input type="text" name="setor" id="setor" class="form-control" placeholder="Jumlah setoran" required>
                        </div>

                        <div class="form-group">
                            <label>Estimasi Saldo Akhir (Rp.)</label>
                            <input type="text" id="estimasi_saldo_akhir_display" class="form-control" placeholder="Estimasi Saldo Akhir" readonly>
                            <small class="form-text text-muted">Perkiraan saldo setelah setoran.</small>
                        </div>

                    </div>
                    <div class="box-footer">
                        <input type="submit" name="Simpan" value="Setor" class="btn btn-primary">
                        <a href="?page=data_setor" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
            </section>

<?php
    if (isset ($_POST['Simpan'])){
        $setor = $_POST['setor'];
        $setor_bersih = (int)preg_replace("/[^0-9]/", "", $setor); // Konversi ke integer

        $nis_siswa_post = (int)mysqli_real_escape_string($koneksi, $_POST['nis']);

        $sql_get_siswa_data = "SELECT saldo FROM tb_siswa WHERE nis = '$nis_siswa_post'";
        $query_get_siswa_data = mysqli_query($koneksi, $sql_get_siswa_data);

        if ($query_get_siswa_data && mysqli_num_rows($query_get_siswa_data) > 0) {
            $data_siswa_db = mysqli_fetch_assoc($query_get_siswa_data);
            $saldo_sekarang = $data_siswa_db['saldo'];

            $saldo_baru = $saldo_sekarang + $setor_bersih;

            $sql_simpan_tabungan = "INSERT INTO tb_tabungan (nis,setor,tarik,tgl,jenis,petugas) VALUES (
                '$nis_siswa_post',
                '$setor_bersih',
                '0',
                '$tanggal',
                'ST',
                '".mysqli_real_escape_string($koneksi, $data_nama)."')";
            $query_simpan_tabungan = mysqli_query($koneksi, $sql_simpan_tabungan);

            if ($query_simpan_tabungan) {
                $sql_update_saldo_siswa = "UPDATE tb_siswa SET saldo = '$saldo_baru' WHERE nis = '$nis_siswa_post'";
                $query_update_saldo_siswa = mysqli_query($koneksi, $sql_update_saldo_siswa);

                if ($query_update_saldo_siswa) {
                    echo "<script>
                    Swal.fire({title: 'Setoran Berhasil',text: 'Saldo siswa telah diperbarui.',icon: 'success',confirmButtonText: 'OK'
                    }).then((result) => {if (result.value)
                        {window.location = 'index.php?page=data_setor';}
                    })</script>";
                } else {
                    echo "<script>
                    Swal.fire({title: 'Setoran Gagal',text: 'Terjadi kesalahan saat memperbarui saldo siswa: " . mysqli_error($koneksi) . "',icon: 'error',confirmButtonText: 'OK'
                    }).then((result) => {if (result.value)
                        {window.location = 'index.php?page=add_setor';}
                    })</script>";
                }
            } else {
                echo "<script>
                Swal.fire({title: 'Setoran Gagal',text: 'Terjadi kesalahan saat menyimpan transaksi setoran: " . mysqli_error($koneksi) . "',icon: 'error',confirmButtonText: 'OK'
                }).then((result) => {if (result.value)
                    {window.location = 'index.php?page=add_setor';}
                    })</script>";
            }
        } else {
            echo "<script>
            Swal.fire({title: 'Siswa Tidak Ditemukan',text: 'NIS Siswa tidak valid atau tidak aktif.',icon: 'error',confirmButtonText: 'OK'
            }).then((result) => {if (result.value)
                {window.location = 'index.php?page=add_setor';}
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
        function getSaldoSiswa(nis_siswa) {
            if (nis_siswa) {
                $.ajax({
                    url: '../../plugins/proses-ajax.php', // PERBAIKI JALUR UNTUK AJAX
                    type: 'POST',
                    data: {
                        action: 'get_saldo_siswa',
                        nis_siswa: nis_siswa
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#saldo_saat_ini_display').val(formatRupiah(response.saldo));
                            hitungEstimasiSaldo();
                        } else {
                            $('#saldo_saat_ini_display').val('0');
                            $('#estimasi_saldo_akhir_display').val('0');
                            console.error('Error fetching saldo: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + ', ' + error + ' - Response: ' + xhr.responseText);
                        $('#saldo_saat_ini_display').val('Error');
                        $('#estimasi_saldo_akhir_display').val('Error');
                    }
                });
            } else {
                $('#saldo_saat_ini_display').val('');
                $('#estimasi_saldo_akhir_display').val('');
            }
        }

        function hitungEstimasiSaldo() {
            var saldoSekarangText = $('#saldo_saat_ini_display').val();
            var saldoSekarang = parseInt(saldoSekarangText.replace(/[^0-9]/g,"")) || 0;
            var jumlahSetor = parseInt($('#setor').val().replace(/[^0-9]/g,"")) || 0;
            var estimasiSaldoAkhir = saldoSekarang + jumlahSetor;
            $('#estimasi_saldo_akhir_display').val(formatRupiah(estimasiSaldoAkhir));
        }

        // Event listener untuk memformat input saat mengetik
        $('#setor').on('keyup', function() {
            var val = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(formatRupiah(val).replace('Rp. ', ''));
            hitungEstimasiSaldo();
        });


        $('#nis').on('change', function() {
            var nisSiswaTerpilih = $(this).val();
            getSaldoSiswa(nisSiswaTerpilih);
        });

        // Fungsi format rupiah (harus konsisten dengan rupiah.php)
        function formatRupiah(angka){
            var number_string = angka.toString(),
                sisa     		= number_string.length % 3,
                rupiah     		= number_string.substr(0, sisa),
                ribuan     		= number_string.substr(sisa).match(/\d{3}/g);

            if(ribuan){
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return 'Rp. ' + rupiah;
        }

        if ($('#nis').val() && $('#nis').val() !== '-- Pilih --') {
            getSaldoSiswa($('#nis').val());
        }
    });
</script>