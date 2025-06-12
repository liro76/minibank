<?php 
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
                            <select name="nis" id="nis" class="form-control select2" style="width: 100%;">
                                <option selected="selected">-- Pilih --</option>
                                <?php
                                $query = "select * from tb_siswa where status='Aktif'";
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
                            <label>Saldo Tabungan</label>
                            <input type="text" name="saldo" id="saldo" class="form-control" placeholder="Saldo" readonly>
                        </div>

                        <div class="form-group">
                            <label>Tarikan</label>
                            <input type="text" name="tarik" id="tarik" class="form-control" placeholder="Jumlah tarikan" required>
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

    $nis = $_POST['nis'];
    $tarik = $_POST['tarik'];
    $tarik_hasil = preg_replace("/[^0-9]/", "", $tarik);

    // Ambil saldo terbaru langsung dari database
    $sql_saldo = $koneksi->query("SELECT saldo FROM tb_siswa WHERE nis='$nis' FOR UPDATE");
    $row_saldo = $sql_saldo->fetch_assoc();
    $saldo_terbaru = $row_saldo ? (int)$row_saldo['saldo'] : 0;

    if($saldo_terbaru >= $tarik_hasil){
        // Proses insert tarikan
        $sql_simpan = "INSERT INTO tb_tabungan (nis,setor,tarik,tgl,jenis,petugas) VALUES (
            '$nis',
            '0',
            '$tarik_hasil',
            '$tanggal',
            'TR',
            '$data_nama')";
        $query_simpan = mysqli_query($koneksi, $sql_simpan);

        // Update saldo siswa, hanya jika saldo masih cukup (double check)
        $sql_update_saldo = "UPDATE tb_siswa SET saldo = saldo - $tarik_hasil WHERE nis = '$nis' AND saldo >= $tarik_hasil";
        $query_update = mysqli_query($koneksi, $sql_update_saldo);

        if ($query_simpan && mysqli_affected_rows($koneksi) > 0) {
            echo "<script>
            Swal.fire({title: 'Tarikan Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value) {
                    window.location = 'index.php?page=data_tarik';
                }
            })</script>";
        } else {
            // Jika gagal update saldo (karena saldo tidak cukup), rollback tarikan
            mysqli_query($koneksi, "DELETE FROM tb_tabungan WHERE nis='$nis' AND tarik='$tarik_hasil' AND tgl='$tanggal' AND jenis='TR' ORDER BY id_tabungan DESC LIMIT 1");
            echo "<script>
            Swal.fire({title: 'Gagal, Saldo tidak cukup',text: '',icon: 'error',confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value) {
                    window.location = 'index.php?page=add_tarik';
                }
            })</script>";
        }

        mysqli_close($koneksi);

    } else {
        echo "<script>
        Swal.fire({title: 'Gagal, Saldo tidak cukup',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=add_tarik';
            }
        })</script>";
    }
}
?>

<script src="././bootstrap/lookup.js"></script>  
<script>
    $(document).ready(function(){  
        $('#nis').change(function(){  
            var nis = $(this).val();  
            $.ajax({  
                url:"plugins/proses-ajax.php",  
                method:"POST",  
                data:{nis:nis},  
                success:function(data){  
                    $('#saldo').val(data);  
                }  
            });  
        });  
    }); 
</script>

<script type="text/javascript">
    var tarik = document.getElementById('tarik');
    tarik.addEventListener('keyup', function (e) {
        tarik.value = formattarik(this.value, 'Rp ');
    });

    // Validasi agar nilai tarikan tidak melebihi saldo
    tarik.addEventListener('input', function () {
        var saldo = document.getElementById('saldo').value.replace(/[^0-9]/g, '');
        var tarikVal = this.value.replace(/[^0-9]/g, '');
        if (saldo && tarikVal && parseInt(tarikVal) > parseInt(saldo)) {
            alert('Jumlah tarikan tidak boleh melebihi saldo!');
            this.value = '';
        }
    });

    function formattarik(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            tarik = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            tarik += separator + ribuan.join('.');
        }

        tarik = split[1] != undefined ? tarik + ',' + split[1] : tarik;
        return prefix == undefined ? tarik : (tarik ? 'Rp ' + tarik : '');
    }
</script>