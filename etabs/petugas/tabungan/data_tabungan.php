<section class="content-header">
    <h1>
        Buku Tabungan
        <small>Pencarian Siswa</small>
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
            <form method="post" id="form-saldo-siswa">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cari Siswa</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="nis">Pilih Siswa</label>
                            <select name="nis" id="nis" class="form-control select2" style="width: 100%;" required aria-label="Pilih siswa aktif">
                                <option value="">-- Pilih Siswa --</option>
                                <?php
                                $query = "SELECT nis, nama_siswa FROM tb_siswa WHERE status='Aktif' ORDER BY nama_siswa ASC";
                                $hasil = mysqli_query($koneksi, $query);
                                while ($row = mysqli_fetch_array($hasil)) {
                                ?>
                                    <option value="<?php echo htmlspecialchars($row['nis']); ?>">
                                        <?php echo htmlspecialchars($row['nis']); ?> - <?php echo htmlspecialchars($row['nama_siswa']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <small id="loading" style="display:none;">Mengambil data saldo...</small>
                        </div>

                        <div class="form-group">
                            <label for="saldo">Saldo Tabungan Saat Ini</label>
                            <input type="text" id="saldo" class="form-control" placeholder="Pilih siswa untuk melihat saldo..." readonly>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a id="lihat-riwayat-btn" href="#" class="btn btn-primary disabled" aria-disabled="true">
                            <i class="fa fa-eye"></i> Lihat Riwayat
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        $('.select2').select2();

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(angka);
        }

        $('#nis').on('change', function () {
            const nis = $(this).val();
            const lihatBtn = $('#lihat-riwayat-btn');
            const saldoInput = $('#saldo');
            const loadingText = $('#loading');

            if (nis) {
                lihatBtn.removeClass('disabled').attr('href', 'index.php?page=view_tabungan&nis=' + nis);
                loadingText.show();

                $.ajax({
                    url: "plugins/proses-ajax.php",
                    method: "POST",
                    data: { nis_siswa: nis, action: 'get_saldo_siswa' },
                    dataType: 'json',
                    success: function (response) {
                        loadingText.hide();
                        if (response.status === 'success') {
                            saldoInput.val(formatRupiah(response.saldo));
                        } else {
                            saldoInput.val('Gagal memuat saldo.');
                        }
                    },
                    error: function () {
                        loadingText.hide();
                        saldoInput.val('Error koneksi AJAX.');
                    }
                });
            } else {
                saldoInput.val('Pilih siswa untuk melihat saldo...');
                lihatBtn.addClass('disabled').attr('href', '#');
            }
        });
    });
</script>
