<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['admin_login'])) {
    header("Location: loginadmin.php");
    exit();
}

// Update status otomatis jika tanggal berakhir telah lewat
$today = date('Y-m-d');

$updateQuery = "UPDATE tb_bantuan_sosial 
                SET status_bantuan = 'ditutup' 
                WHERE tanggal_berakhir < '$today' AND status_bantuan != 'ditutup'";

mysqli_query($conn, $updateQuery);

// Handle filter
$filter_kota = isset($_GET['filter_kota']) ? $_GET['filter_kota'] : '';
$filter_jenis = isset($_GET['filter_jenis']) ? $_GET['filter_jenis'] : '';

$query = "
    SELECT 
        b.id_bantuan_sosial,
        b.nama_bantuan, 
        j.jenis_bantuan, 
        k.nama_kota, 
        b.status_bantuan, 
        b.gambar 
    FROM tb_bantuan_sosial b 
    JOIN tb_jenis_bantuan j ON b.id_jenis_bantuan = j.id_jenis_bantuan 
    JOIN tb_kabupaten_kota k ON b.id_kota = k.id_kota
";

$conditions = [];

if (!empty($filter_kota)) {
    $conditions[] = "k.id_kota = '".mysqli_real_escape_string($conn, $filter_kota)."'";
}
if (!empty($filter_jenis)) {
    $conditions[] = "j.id_jenis_bantuan = '".mysqli_real_escape_string($conn, $filter_jenis)."'";
}
if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" type="text/css" href="js/select.dataTables.min.css">
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="images/kalteng.png" />
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body>
  <div class="container-scroller">
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-3 d-flex align-items-center" href="dash_admin.php">
          <img src="images/kalteng.png" class="mr-2" alt="logo"/>
          <span class="text-left" style="font-size: 10px; font-weight: bold; color: #000;">
            PORTAL INFORMASI<br>Bantuan Sosial Kalimantan Tengah
          </span>
        </a>
        <a class="navbar-brand brand-logo-mini" href="dash_admin.php">
          <img src="images/kalteng.png" alt="logo"/>
        </a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
     <label class="mr-2 mb-2 font-weight-bold text-dark">CARI BERDASARKAN:</label> 
        <form class="d-flex" method="GET" action="dash_admin.php" id="filterForm">

       
          <div class="input-group mx-2">
            <select name="filter_kota" id="filterKota" class="form-control mx-1" onchange="resetJenisAndSubmit()">
              <option value="">kabupaten/Kota</option>
              <?php
              $kotaQuery = mysqli_query($conn, "SELECT * FROM tb_kabupaten_kota ORDER BY nama_kota ASC");
              while ($kota = mysqli_fetch_assoc($kotaQuery)) {
                  $selected = ($filter_kota == $kota['id_kota']) ? 'selected' : '';
                  echo "<option value='{$kota['id_kota']}' $selected>{$kota['nama_kota']}</option>";
              }
              ?>
            </select>

            <select name="filter_jenis" id="filterJenis" class="form-control mx-1" onchange="document.getElementById('filterForm').submit()">
              <option value="">Jenis Bantuan</option>
              <?php
              $jenisQuery = mysqli_query($conn, "SELECT * FROM tb_jenis_bantuan ORDER BY jenis_bantuan ASC");
              while ($jenis = mysqli_fetch_assoc($jenisQuery)) {
                  $selected = ($filter_jenis == $jenis['id_jenis_bantuan']) ? 'selected' : '';
                  echo "<option value='{$jenis['id_jenis_bantuan']}' $selected>{$jenis['jenis_bantuan']}</option>";
              }
              ?>
            </select>
          </div>
        </form>

        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <i class="ti-user" style="font-size: 20px; margin-right: 5px;"></i> Admin
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="logout.php">
                <i class="ti-power-off text-primary"></i> Logout
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>

    <div class="container-fluid page-body-wrapper">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item"><a class="nav-link" href="dash_admin.php"><i class="icon-grid menu-icon"></i><span class="menu-title">Kelola Data Bantuan</span></a></li>
          <li class="nav-item"><a class="nav-link" href="input_data.php"><i class="icon-paper menu-icon"></i><span class="menu-title">Input Data Bantuan</span></a></li>
          <li class="nav-item"><a class="nav-link" href="jumlah_bantuan.php"><i class="icon-grid-2 menu-icon"></i><span class="menu-title">Jumlah Bantuan</span></a></li>
        </ul>
      </nav>

      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <?php
            $found = false;
            while ($row = mysqli_fetch_assoc($result)) {
              $found = true;
            ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
              <div class="card h-100">
                <div class="d-flex justify-content-end p-2">
                  <a href="edit_bantuan.php?id=<?= $row['id_bantuan_sosial'] ?>" class="btn btn-sm btn-primary mr-1" data-toggle="tooltip" title="Edit"><i class="ti-pencil"></i></a>
                  <a href="hapus_bantuan.php?id=<?= $row['id_bantuan_sosial'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')" data-toggle="tooltip" title="Hapus"><i class="ti-trash"></i></a>
                </div>
                <a href="detail_bantuan.php?id=<?= $row['id_bantuan_sosial'] ?>" style="text-decoration: none; color: inherit;">
                  <img src="data:image/jpeg;base64,<?= base64_encode($row['gambar']) ?>" class="card-img-top" alt="Gambar">
                  <div class="card-body">
                    <h5 class="card-title"><?= $row['nama_bantuan'] ?></h5>
                    <p class="card-text"><?= $row['jenis_bantuan'] ?> - <?= $row['nama_kota'] ?></p>
                    <p class="card-text"><strong>Status: </strong>
                      <span class="badge badge-<?= $row['status_bantuan'] == 'dibuka' ? 'success' : 'danger' ?>">
                        <?= ucfirst($row['status_bantuan']) ?>
                      </span>
                    </p>
                  </div>
                </a>
              </div>
            </div>
            <?php } ?>

            <?php if (!$found): ?>
              <div class="col-12 text-center mt-5">
                <i class="ti-search" style="font-size: 48px; color: #ccc;"></i>
                <h4 class="mt-3">Tidak ada bantuan ditemukan</h4>
                <p>Silakan coba pilih jenis atau kota lainnya.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Plugin JS -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/chart.js/Chart.min.js"></script>
  <script src="vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script src="js/dataTables.select.min.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>

  <script>
    function resetJenisAndSubmit() {
      document.getElementById("filterJenis").selectedIndex = 0;
      document.getElementById("filterForm").submit();
    }

    document.addEventListener('DOMContentLoaded', () => {
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
</body>
</html>
