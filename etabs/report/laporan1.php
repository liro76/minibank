<?php
    include "../inc/koneksi.php";
    //FUNGSI RUPIAH
    include "../inc/rupiah.php";

    $dt1 = $_POST["tgl_1"];
    $dt2 = $_POST["tgl_2"];
?>

<?php
  $sql = $koneksi->query("SELECT SUM(setor) as tot_masuk  from tb_tabungan where jenis='ST' and tgl BETWEEN '$dt1' AND '$dt2'");
  while ($data= $sql->fetch_assoc()) {
    $masuk=$data['tot_masuk'];
  }

  $sql = $koneksi->query("SELECT SUM(tarik) as tot_keluar  from tb_tabungan where jenis='TR' and tgl BETWEEN '$dt1' AND '$dt2'");
  while ($data= $sql->fetch_assoc()) {
    $keluar=$data['tot_keluar'];
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bank Mini - Laporan Tabungan</title>
</head>

<body>
    <center>
        <h2>Laporan Tabungan Siswa</h2>
        <h3>Sekolah</h3>
        <p>Periode :
            <?php $a=$dt1; echo date("d-M-Y", strtotime($a))?>
            s/d
            <?php $b=$dt2; echo date("d-M-Y", strtotime($b))?>
            <p>_________________________________________________________________________________________</p>

            <table border="1" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Petugas</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Setor</th>
                        <th>Tarik</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

        if(isset($_POST["btnCetak"])){
           
            $sql_tampil = "select * from tb_tabungan INNER JOIN tb_siswa ON tb_tabungan.nis=tb_siswa.nis INNER JOIN tb_kelas ON tb_siswa.id_kelas=tb_kelas.id_kelas where tgl BETWEEN '$dt1' AND '$dt2' order by tgl asc";
            }
            $query_tampil = mysqli_query($koneksi, $sql_tampil);
            $no=1;
            while ($data = mysqli_fetch_array($query_tampil,MYSQLI_BOTH)) {
        ?>
                    <tr>
                        <td>
                            <?php echo $no; ?>
                        </td>
                        <td>
                            <?php  $tgl = $data['tgl']; echo date("d/M/Y", strtotime($tgl))?>
                            &emsp;&emsp;&emsp;
                        </td>
                        <td>
                            <?php echo $data['petugas']; ?>
                            &emsp;&emsp;&emsp;
                        </td>
                        <td>
                            <?php echo $data['nama_siswa']; ?>
                            &emsp;&emsp;&emsp;
                        </td>
                        <td>
                            <?php echo $data['kelas']; ?>
                            &emsp;&emsp;&emsp;
                        </td>
                        <td align="right">
                            &emsp;&emsp;&emsp;
                            <?php echo rupiah($data['setor']); ?>
                        </td>
                        <td align="right">
                            &emsp;&emsp;&emsp;
                            <?php echo rupiah($data['tarik']); ?>
                        </td>
                    </tr>
                    <?php
            $no++;
            }
        ?>
                </tbody>
                <tr>
                    <td colspan="5">Total Setoran</td>
                    <td colspan="4">
                        <?php echo rupiah($masuk); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">Total Penarikan</td>
                    <td colspan="3">
                        <?php echo rupiah($keluar); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">Total Kas :</td>
                    <td colspan="4">
                        <?php
                        // Total Kas = jumlah setoran - jumlah tarik pada periode
                        echo rupiah($masuk - $keluar);
                        ?>
                    </td>
                </tr>
            </table>
    </center>

    <script>
        window.print();
    </script>
</body>

</html>