<?php
// FILE: petugas/tarik/add_tarik.php

// 1. Logika untuk memproses form saat disubmit
if (isset($_POST['Simpan'])) {
    // 2. Ambil dan bersihkan data dari form
    $nis = $_POST['nis'];
    $tarik = (int)preg_replace("/[^0-9]/", "", $_POST['tarik']); // Ambil angka saja
    $tanggal = date("Y-m-d");
    $petugas = $_SESSION["ses_nama"];

    // 3. Validasi awal
    if ($tarik <= 0) {
        echo "<script>
            Swal.fire({title: 'Transaksi Gagal', text: 'Jumlah penarikan harus lebih dari 0.', icon: 'warning', confirmButtonText: 'OK'});
        </script>";
    } else {
        // 4. Mulai DATABASE TRANSACTION untuk menjaga konsistensi data
        $koneksi->begin_transaction();

        try {
            // 5. Kunci baris data siswa dan ambil saldo terbaru untuk mencegah race condition
            $stmt_saldo = $koneksi->prepare("SELECT saldo FROM tb_siswa WHERE nis = ? FOR UPDATE");
            $stmt_saldo->bind_param('s', $nis);
            $stmt_saldo->execute();
            $result_saldo = $stmt_saldo->get_result();
            
            if ($result_saldo->num_rows === 0) {
                throw new Exception("Siswa dengan NIS tersebut tidak ditemukan.");
            }
            
            $data_siswa = $result_saldo->fetch_assoc();
            $saldo_awal = (int)$data_siswa['saldo'];
            $stmt_saldo->close();

            // 6. Validasi saldo
            if ($saldo_awal < $tarik) {
                throw new Exception("Saldo siswa tidak mencukupi untuk melakukan penarikan!");
            }

            // 7. Hitung saldo akhir
            $saldo_akhir = $saldo_awal - $tarik;

            // 8. Insert ke tabel tabungan dengan Prepared Statement
            $stmt_simpan = $koneksi->prepare(
                "INSERT INTO tb_tabungan (nis, setor, tarik, saldo_awal, saldo_akhir, tgl, jenis, petugas) 
                 VALUES (?, 0, ?, ?, ?, ?, 'TR', ?)"
            );
            $stmt_simpan->bind_param('siisss', $nis, $tarik, $saldo_awal, $saldo_akhir, $tanggal, $petugas);
            $query_simpan = $stmt_simpan->execute();
            $stmt_simpan->close();

            // 9. Update saldo di tabel siswa dengan Prepared Statement
            $stmt_update = $koneksi->prepare("UPDATE tb_siswa SET saldo = ? WHERE nis = ?");
            $stmt_update->bind_param('is', $saldo_akhir, $nis);
            $query_update = $stmt_update->execute();
            $stmt_update->close();

            // 10. Jika semua query berhasil, commit transaksi
            if ($query_simpan && $query_update) {
                $koneksi->commit();
                echo "<script>
                    Swal.fire({title: 'Penarikan Berhasil', text: 'Transaksi telah berhasil dicatat.', icon: 'success', confirmButtonText: 'OK'})
                    .then((result) => { if (result.value) { window.location = 'index.php?page=data_tarik'; } });
                </script>";
            } else {
                throw new Exception("Gagal menyimpan data transaksi atau memperbarui saldo siswa.");
            }
        } catch (Exception $e) {
            // Jika terjadi kesalahan, batalkan semua query (rollback)
            $koneksi->rollback();
            echo "<script>
                Swal.fire({title: 'Transaksi Gagal', text: '" . addslashes($e->getMessage()) . "', icon: 'error', confirmButtonText: 'OK'});
            </script>";
        }
    }
}
?>

<section class="content-header">
    <h1>
        Transaksi
        <small>Tarik Tunai</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i> <b>Bank Mini</b></a></li>
        <li><a href="?page=data_tarik">Data Tarik</a></li>
        <li class="active">Tambah</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Tambah Penarikan</h3>
        </div>
        <form action="" method="post">
            <div class="box-body">
                <div class="form-group">
                    <label>Siswa</label>
                    <select name="nis" id="nis" class="form-control select2" style="width: 100%;" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php
                        // Query untuk mengisi dropdown siswa
                        $query_siswa = "SELECT nis, nama_siswa FROM tb_siswa WHERE status='Aktif' ORDER BY nama_siswa ASC";
                        $hasil_siswa = $koneksi->query($query_siswa);
                        while ($row_siswa = $hasil_siswa->fetch_assoc()) {
                        ?>
                        <option value="<?= htmlspecialchars($row_siswa['nis']) ?>">
                            <?= htmlspecialchars($row_siswa['nis']) ?> - <?= htmlspecialchars($row_siswa['nama_siswa']) ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Saldo Saat Ini</label>
                    <input type="text" id="saldo" class="form-control" placeholder="Pilih siswa untuk melihat saldo..." readonly>
                </div>

                <div class="form-group">
                    <label>Jumlah Penarikan (Rp)</label>
                    <input type="text" name="tarik" id="tarik" class="form-control" placeholder="Masukkan jumlah penarikan" required>
                </div>
            </div>
            <div class="box-footer">
                <input type="submit" name="Simpan" value="Simpan Transaksi" class="btn btn-primary">
                <a href="?page=data_tarik" class="btn btn-default">Batal</a>
            </div>
        </form>
    </div>
</section>

<script>
    // Fungsi untuk memformat angka menjadi format Rupiah
    function formatRupiah(angka, prefix) {
        var number_string = String(angka).replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }

    // Event listener untuk input jumlah penarikan
    document.getElementById('tarik').addEventListener('keyup', function(e) {
        this.value = formatRupiah(this.value);
    });

    // Event listener untuk dropdown siswa (menggunakan jQuery karena select2)
    $(document).ready(function() {
        $('#nis').on('change', function() {
            var nis = $(this).val();
            if (nis) {
                // Menggunakan AJAX untuk mengambil saldo siswa secara real-time
                $.ajax({
                    url: "plugins/proses-ajax.php", // Pastikan path ini benar
                    method: "POST",
                    data: { nis_siswa: nis, action: 'get_saldo_siswa' },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#saldo').val(formatRupiah(response.saldo, 'Rp '));
                        } else {
                            $('#saldo').val('Gagal memuat saldo.');
                        }
                    },
                    error: function() {
                        $('#saldo').val('Terjadi error koneksi.');
                    }
                });
            } else {
                $('#saldo').val('Pilih siswa untuk melihat saldo...');
            }
        });
    });
</script>