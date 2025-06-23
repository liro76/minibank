<section class="content-header">
  <h1>
    Transaksi <small>Info Kas</small>
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="index.php">
        <i class="fa fa-home"></i> <b>Bank Mini</b>
      </a>
    </li>
    <li class="active">Info Kas</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- Box Info Kas -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Info Kas per Periode</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
              <i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove">
              <i class="fa fa-remove"></i>
            </button>
          </div>
        </div>

        <!-- Form Filter Periode -->
        <form action="index.php?page=data_kas" method="post" target="_blank">
          <div class="box-body">
            <div class="form-group">
              <label for="tgl_1">Tanggal Awal</label>
              <input
                type="date"
                name="tgl_1"
                id="tgl_1"
                class="form-control"
                value="<?= date('Y-m-01') ?>"
                required
              >
            </div>

            <div class="form-group">
              <label for="tgl_2">Tanggal Akhir</label>
              <input
                type="date"
                name="tgl_2"
                id="tgl_2"
                class="form-control"
                value="<?= date('Y-m-d') ?>"
                required
              >
            </div>
          </div>

          <div class="box-footer">
            <button type="submit" name="btnCetak" class="btn btn-primary">
              Tampilkan Kas
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
