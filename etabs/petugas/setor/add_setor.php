<?php
// Blok PHP untuk memproses simpan data setoran
if (isset($_POST['Simpan'])) {
    // 1. Ambil dan bersihkan data dari form
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $setor = (int)preg_replace("/[^0-9]/", "", $_POST['setor']);
    $tanggal = date("Y-m-d");
    $petugas = $_SESSION["ses_nama"];

    // Cek jika jumlah setoran valid
    if ($setor <= 0) {
        echo "<script>
            Swal.fire({title: 'Transaksi Gagal', text: 'Jumlah setoran harus lebih dari 0.', icon: 'warning', confirmButtonText: 'OK'});
        </script>";
    } else {
        // 2. Query untuk mengambil saldo terakhir siswa (ini akan menjadi saldo_awal)
        $sql_cek_saldo = "SELECT saldo FROM tb_siswa WHERE nis = '$nis'";
        $query_cek_saldo = mysqli_query($koneksi, $sql_cek_saldo);
        
        if ($query_cek_saldo && mysqli_num_rows($query_cek_saldo) > 0) {
            $data_siswa = mysqli_fetch_assoc($query_cek_saldo);
            
            // 3. Tentukan saldo awal dan akhir
            $saldo_awal = (int)$data_siswa['saldo'];
            $saldo_akhir = $saldo_awal + $setor;

            // 4. Mulai transaksi database untuk memastikan integritas data
            mysqli_begin_transaction($koneksi);

            try {
                // 5. Insert ke tabel tabungan DENGAN menyertakan saldo_awal dan saldo_akhir
                $sql_simpan = "INSERT INTO tb_tabungan (nis, setor, tarik, saldo_awal, saldo_akhir, tgl, jenis, petugas) VALUES (
                    '$nis', '$setor', '0', '$saldo_awal', '$saldo_akhir', '$tanggal', 'ST', '$petugas'
                )";
                $query_simpan = mysqli_query($koneksi, $sql_simpan);

                // 6. Update saldo di tabel siswa dengan saldo_akhir
                $sql_update = "UPDATE tb_siswa SET saldo = '$saldo_akhir' WHERE nis = '$nis'";
                $query_update = mysqli_query($koneksi, $sql_update);

                if ($query_simpan && $query_update) {
                    mysqli_commit($koneksi); // Simpan perubahan jika semua berhasil
                    echo "<script>
                        Swal.fire({title: 'Setoran Berhasil', text: 'Riwayat saldo berhasil dicatat.', icon: 'success', confirmButtonText: 'OK'})
                        .then((result) => { if (result.value) { window.location = 'index.php?page=data_setor'; } });
                    </script>";
                } else {
                    throw new Exception(mysqli_error($koneksi)); // Lemparkan error jika gagal
                }
            } catch (Exception $e) {
                mysqli_rollback($koneksi); // Batalkan semua perubahan jika ada error
                echo "<script>
                    Swal.fire({title: 'Setoran Gagal', text: 'Terjadi kesalahan pada database: " . addslashes($e->getMessage()) . "', icon: 'error', confirmButtonText: 'OK'});
                </script>";
            }
        } else {
            echo "<script>
                Swal.fire({title: 'Siswa Tidak Ditemukan', text: 'NIS Siswa tidak valid.', icon: 'error', confirmButtonText: 'OK'});
            </script>";
        }
    }
}
?>

<section class="content-header">
	<h1>
		Transaksi
		<small>Setoran Tunai</small>
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
			<div class="box box-primary">
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
								<option value="">-- Pilih Siswa --</option>
								<?php
								$query = "SELECT nis, nama_siswa FROM tb_siswa WHERE status='Aktif' ORDER BY nama_siswa ASC";
								$hasil = mysqli_query($koneksi, $query);
								while ($row = mysqli_fetch_array($hasil)) {
								?>
									<option value="<?php echo $row['nis']; ?>">
										<?php echo $row['nis']; ?> - <?php echo $row['nama_siswa']; ?>
									</option>
								<?php
								}
								?>
							</select>
						</div>

						<div class="form-group">
							<label>Saldo Saat Ini</label>
							<input type="text" id="saldo" class="form-control" placeholder="Pilih siswa untuk melihat saldo..." readonly>
						</div>

						<div class="form-group">
							<label>Jumlah Setoran (Rp)</label>
							<input type="text" name="setor" id="setor" class="form-control" placeholder="Jumlah setoran" required>
						</div>
					</div>
					<div class="box-footer">
						<input type="submit" name="Simpan" value="Setor" class="btn btn-primary">
						<a href="?page=data_setor" class="btn btn-warning">Batal</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<script>
    $(document).ready(function() {
        // Fungsi untuk memformat angka menjadi Rupiah
        function formatRupiah(angka) {
            var number_string = String(angka).replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

		// Format input setoran saat diketik
		$('#setor').on('keyup', function() {
			var val = $(this).val().replace(/[^0-9]/g, '');
			$(this).val(formatRupiah(val).replace('Rp ', ''));
		});

        // Event listener saat pilihan siswa berubah
        $('#nis').on('change', function() {
            var nis = $(this).val();
            if (nis) {
                // Lakukan AJAX untuk mengambil saldo siswa
                $.ajax({
                    url: "plugins/proses-ajax.php",
                    method: "POST",
                    data: { nis_siswa: nis, action: 'get_saldo_siswa' },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#saldo').val(formatRupiah(response.saldo));
                        } else { $('#saldo').val('Gagal memuat saldo.'); }
                    },
                    error: function() { $('#saldo').val('Error koneksi AJAX.'); }
                });
            } else {
                $('#saldo').val('Pilih siswa untuk melihat saldo...');
            }
        });
    });
</script>