<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
  echo "ID bantuan tidak ditemukan.";
  exit;
}

$id_bantuan = $_GET['id'];

$query = "SELECT 
            bs.*,
            kbk.nama_kota,
            jb.jenis_bantuan 
          FROM tb_bantuan_sosial bs
          JOIN tb_kabupaten_kota kbk ON bs.id_kota = kbk.id_kota
          JOIN tb_jenis_bantuan jb ON bs.id_jenis_bantuan = jb.id_jenis_bantuan
          WHERE bs.id_bantuan_sosial = '$id_bantuan'";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
  echo "Data bantuan tidak ditemukan.";
  exit;
}

$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>detail bantuan</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">

  
  <link rel="stylesheet" href="style_tambahan.css">

  <!-- endinject -->
  <link rel="shortcut icon" href="images/kalteng.png" />
</head>


<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="main-panel w-100 documentation">
        <div class="content-wrapper">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12 pt-4">
                <a class="btn btn-primary" href="dash_admin.php"><i class="ti-arrow-left mr-2"></i>Kembali ke Daftar</a>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-8 offset-md-2">
                <div class="card">
                  <img src="data:image/jpeg;base64,<?= base64_encode($data['gambar']) ?>" class="card-img-top" alt="Gambar Bantuan">
                  <div class="card-body">
                    <h3 class="card-title"><?= $data['nama_bantuan'] ?></h3>
                    <p><strong>Jenis Bantuan:</strong> <?= $data['jenis_bantuan'] ?></p>
                    <p><strong>Kota/Kabupaten:</strong> <?= $data['nama_kota'] ?></p>
                    <p><strong>Tanggal Pelaksanaan:</strong> <?= $data['tanggal_pelaksanaan'] ?></p>
                    <p><strong>Tanggal Berakhir:</strong> <?= $data['tanggal_berakhir'] ?></p>
                    <p><strong>Status:</strong> <span class="badge badge-<?= $data['status_bantuan'] == 'dibuka' ? 'success' : 'danger' ?>"><?= ucfirst($data['status_bantuan']) ?></span></p>
                    <hr>
                    <p><strong>Syarat Pendaftaran:</strong></p>
                    <p><?= nl2br($data['syarat_pendaftaran']) ?></p>
                    <p><strong>Mekanisme Pendaftaran:</strong></p>
                    <p><?= nl2br($data['mekanisme_pendaftaran']) ?></p>
                    <p><strong>Deskripsi Bantuan:</strong></p>
                    <p><?= nl2br($data['deskripsi_bantuan']) ?></p>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../../vendors/js/vendor.bundle.base.js"></script>
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../js/settings.js"></script>
  <script src="../../js/todolist.js"></script>
</body>

</html>
