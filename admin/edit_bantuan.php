<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
  echo "ID tidak ditemukan.";
  exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM tb_bantuan_sosial WHERE id_bantuan_sosial = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Ambil data dari tabel terkait
$jenis_result = mysqli_query($conn, "SELECT * FROM tb_jenis_bantuan");
$kota_result = mysqli_query($conn, "SELECT * FROM tb_kabupaten_kota");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Bantuan Sosial</title>
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="images/kalteng.png" />
</head>
<?php if (isset($_GET['status']) && $_GET['status'] == 'success') : ?>
<script>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: 'Data berhasil diperbarui.',
    showConfirmButton: false,
    timer: 2000
  });
</script>
<?php endif; ?>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="main-panel w-100 documentation">
        <div class="content-wrapper">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12 pt-5">
                <a class="btn btn-primary" href="dash_admin.php"><i class="ti-home mr-2"></i>Kembali ke Dashboard</a>
              </div>
            </div>

            <div class="row pt-4 justify-content-center">
              <div class="col-md-8">
                <div class="card p-4">
                  <h4 class="text-center">Edit Bantuan Sosial</h4>
                  <form method="POST" action="logupdate.php" enctype="multipart/form-data">
                    <input type="hidden" name="id_bantuan_sosial" value="<?= $data['id_bantuan_sosial'] ?>">

                    <div class="form-group">
                      <label>Nama Bantuan</label>
                      <input type="text" class="form-control" name="nama_bantuan" value="<?= $data['nama_bantuan'] ?>" required>
                    </div>

                    <div class="form-group">
                      <label>Jenis Bantuan</label>
                      <select class="form-control" name="id_jenis_bantuan" required>
                        <?php while ($row = mysqli_fetch_assoc($jenis_result)): ?>
                          <option value="<?= $row['id_jenis_bantuan'] ?>" <?= ($row['id_jenis_bantuan'] == $data['id_jenis_bantuan']) ? 'selected' : '' ?>>
                            <?= $row['jenis_bantuan'] ?>
                          </option>
                        <?php endwhile; ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Syarat Pendaftaran</label>
                      <textarea class="form-control" name="syarat_pendaftaran" required><?= $data['syarat_pendaftaran'] ?></textarea>
                    </div>

                    <div class="form-group">
                      <label>Mekanisme Pendaftaran</label>
                      <textarea class="form-control" name="mekanisme_pendaftaran" required><?= $data['mekanisme_pendaftaran'] ?></textarea>
                    </div>

                    <div class="form-group">
                      <label>Deskripsi Bantuan</label>
                      <textarea class="form-control" name="deskripsi_bantuan" required><?= $data['deskripsi_bantuan'] ?></textarea>
                    </div>

                    <div class="form-group">
                      <label>Kabupaten/Kota</label>
                      <select class="form-control" name="id_kota" required>
                        <?php while ($row = mysqli_fetch_assoc($kota_result)): ?>
                          <option value="<?= $row['id_kota'] ?>" <?= ($row['id_kota'] == $data['id_kota']) ? 'selected' : '' ?>>
                            <?= $row['nama_kota'] ?>
                          </option>
                        <?php endwhile; ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Tanggal Pelaksanaan</label>
                      <input type="date" class="form-control" name="tanggal_pelaksanaan" value="<?= $data['tanggal_pelaksanaan'] ?>" required>
                    </div>

                    <div class="form-group">
                      <label>Tanggal Berakhir</label>
                      <input type="date" class="form-control" name="tanggal_berakhir" value="<?= $data['tanggal_berakhir'] ?>" required>
                    </div>

                    <div class="form-group">
                      <label>Status Bantuan</label>
                      <select class="form-control" name="status_bantuan" required>
                        <option value="dibuka" <?= ($data['status_bantuan'] == 'dibuka') ? 'selected' : '' ?>>Dibuka</option>
                        <option value="ditutup" <?= ($data['status_bantuan'] == 'ditutup') ? 'selected' : '' ?>>Ditutup</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Gambar Baru (Opsional)</label>
                      <input type="file" class="form-control" name="gambar">
                    </div>

                    <div class="form-group text-center">
                      <button type="submit" class="btn btn-success">Update Data</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/template.js"></script>
</body>
</html>
