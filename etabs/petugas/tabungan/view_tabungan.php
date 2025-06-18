<?php
// Pastikan koneksi.php sudah di-include di index.php utama atau di awal file ini jika diakses langsung.
// Contoh: include '../inc/koneksi.php';
// Pastikan juga rupiah.php sudah di-include atau fungsi rupiah tersedia.
// Contoh: include '../inc/rupiah.php';
?>

<section class="content-header">
    <h1>
        Tabungan
        <small>Pencarian</small>
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
                    <h3 class="box-title">Cari Siswa</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove">
                            <i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <form action="?page=data_tabungan" method="post" enctype="multipart/form-data">
                    <div class="box-body">

                        <div class="form-group">
                            <label>Siswa</label>
                            <select name="nis" id="nis" class="form-control select2" style="width: 100%;" required>
                                <option selected="selected">-- Pilih --</option>
                                <?php
                                // ambil data dari database
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
                            <label>Saldo Tabungan</label>
                            <input type="text" name="saldo" id="saldo" class="form-control" placeholder="Saldo" readonly>
                        </div>

                    </div>
                    <div class="box-footer">
                        <input type="submit" name="Lihat" value="Lihat" class="btn btn-primary">
                    </div>
                </form>
            </div>
            </section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script>
    $(function () {
        $('.select2').select2();
    });
</script>

<script>
    $(document).ready(function() {
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

        $('#nis').change(function() {
            var nis = $(this).val();
            if (nis) { // Pastikan NIS tidak kosong
                $.ajax({
                    // Jalur disesuaikan: naik dua level dari 'petugas/tabungan/' ke 'etabs/', lalu masuk 'plugins/'
                    url: "../../plugins/proses-ajax.php",
                    method: "POST",
                    data: {
                        nis_siswa: nis, // Kirim NIS dengan nama parameter yang benar
                        action: 'get_saldo_siswa' // PENTING: Tambahkan parameter aksi
                    },
                    dataType: 'json', // Harapkan respons JSON
                    success: function(response) { // Ambil respons JSON
                        if (response.status === 'success') {
                            $('#saldo').val(formatRupiah(response.saldo)); // Ambil 'saldo' dari objek respons
                        } else {
                            $('#saldo').val('Error: ' + (response.message || 'Data tidak ditemukan'));
                            console.error('AJAX Error: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#saldo').val('AJAX Error');
                        console.error('AJAX Request Failed: ' + status + ', ' + error + ', Response: ' + xhr.responseText);
                    }
                });
            } else {
                $('#saldo').val(''); // Kosongkan saldo jika tidak ada siswa terpilih
            }
        });
    });
</script>