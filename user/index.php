<?php
session_start();
include '../admin/koneksi.php';

// Hapus variabel search karena tidak digunakan lagi
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
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Portal Informasi</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../admin/vendors/feather/feather.css">
  <link rel="stylesheet" href="../admin/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../admin/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="../admin/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../admin/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="../admin/js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../admin/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../admin/images/kalteng.png" />

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <style>

html, body, .content-wrapper {
  overflow: visible !important;
  height: auto !important;
}


    /* HEADER BANNER */
    .header-banner {
      width: 100%;
      max-height: 200px;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .header-banner:hover {
      transform: scale(1.05);
      filter: brightness(90%);
    }

    /* NAVBAR STICKY */
    .sticky-navbar {
      position: sticky;
      top: 0;
      z-index: 1000;
      background-color: #fff;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      font-weight: bold;
      font-size: 14px;
    }

    .navbar-brand img {
      height: 40px;
      margin-right: 10px;
    }

    .form-inline .form-control,
    .form-inline .custom-select {
      margin-right: 10px;
      transition: all 0.3s ease;
    }

    .form-inline .form-control:focus,
    .form-inline .custom-select:focus {
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .search-button:hover {
      transform: scale(1.05);
    }

    @media (max-width: 767px) {
      .form-inline {
        flex-direction: column;
        align-items: flex-start;
      }

      .form-inline .form-control,
      .form-inline .custom-select,
      .form-inline .search-button {
        margin-right: 0;
        width: 100%;
        margin-bottom: 10px;
      }

      .input-group {
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <!-- HEADER BANNER -->
  <div class="position-relative">
    <img src="https://cdn.simda.net/kalteng-go-id/header/20250220140045_12356.jpg" class="header-banner" alt="Kalimantan Tengah">
  </div>

  <!-- STICKY NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-light sticky-navbar">
    <div class="container">
      <!-- LOGO -->
      <a class="navbar-brand" href="index.php">
        <img src="../admin/images/kalteng.png" alt="Logo">
        <span>PORTAL INFORMASI<br>Bantuan Sosial Kalimantan Tengah</span>
      </a>

      <!-- Toggle Mobile -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSearchFilter" aria-controls="navbarSearchFilter" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- FILTER FORM -->
      <div class="collapse navbar-collapse" id="navbarSearchFilter">
      <form class="form-inline ml-auto" method="GET" action="index.php" id="filterForm">

      <label class="mr-2 mb-2 font-weight-bold text-dark">CARI BERDASARKAN:</label>
  <!-- Filter Kota -->
  <select name="filter_kota" id="filterKota" class="custom-select mr-2 mb-2" data-toggle="tooltip" title="Pilih Kota">

    <option value="">Kabupaten/Kota</option>
    <?php
      $kotaQuery = mysqli_query($conn, "SELECT * FROM tb_kabupaten_kota ORDER BY nama_kota ASC");
      while ($kota = mysqli_fetch_assoc($kotaQuery)) {
          $selected = ($filter_kota == $kota['id_kota']) ? 'selected' : '';
          echo "<option value='{$kota['id_kota']}' $selected>{$kota['nama_kota']}</option>";
      }
    ?>
  </select>

  <!-- Filter Jenis Bantuan -->
  <select name="filter_jenis" id="filterJenis" class="custom-select mb-2" onchange="document.getElementById('filterForm').submit()" data-toggle="tooltip" title="Pilih Jenis Bantuan">
    <option value="">Jenis Bantuan</option>
    <?php
      $jenisQuery = mysqli_query($conn, "SELECT * FROM tb_jenis_bantuan ORDER BY jenis_bantuan ASC");
      while ($jenis = mysqli_fetch_assoc($jenisQuery)) {
          $selected = ($filter_jenis == $jenis['id_jenis_bantuan']) ? 'selected' : '';
          echo "<option value='{$jenis['id_jenis_bantuan']}' $selected>{$jenis['jenis_bantuan']}</option>";
      }
    ?>
  </select>
</form>

      </div>
    </div>
  </nav>

      
      <!-- Content -->
      <div class="content-wrapper">
        <div class="row">
          <?php
          $found = false;
          while ($row = mysqli_fetch_assoc($result)) {
            $found = true;
            ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
              <div class="card h-100">
                <a href="detail_bantuan_user.php?id=<?= $row['id_bantuan_sosial'] ?>" style="text-decoration: none; color: inherit;">
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
            <?php
          }

          if (!$found) {
            echo '<div class="col-12 text-center mt-5">';
            echo '<i class="ti-search" style="font-size: 48px; color: #ccc;"></i>';
            echo '<h4 class="mt-3">Data Bantuan Tidak Ditemukan</h4>';
            echo '<p>Tidak ada data bantuan yang sesuai dengan filter yang Anda pilih. Silakan coba pilih jenis atau kota lainnya.</p>';
            echo '</div>';
          }
          ?>
        </div>
      </div>
      
      <!-- Footer -->
      <footer class="footer mt-5">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
          <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
            © <?= date('Y') ?> Portal Informasi Bantuan Pemerintah Kalimantan Tengah
          </span>
        </div>
      </footer>
    </div>
  </div>

  <!-- plugins:js -->
  <script src="../admin/vendors/js/vendor.bundle.base.js"></script>
  <!-- Plugin js for this page -->
  <script src="../admin/vendors/chart.js/Chart.min.js"></script>
  <script src="../admin/vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="../admin/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../admin/js/off-canvas.js"></script>
  <script src="../admin/js/hoverable-collapse.js"></script>
  <script src="../admin/js/template.js"></script>
  <!-- Custom js for this page-->
   
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    // Tooltip Bootstrap
    if (typeof $.fn.tooltip === 'function') {
      $('[data-toggle="tooltip"]').tooltip();
    }

    // Reset filter jenis ketika filter kota berubah
    const kotaSelect = document.getElementById('filterKota');
    const jenisSelect = document.getElementById('filterJenis');
    const form = document.getElementById('filterForm');

    kotaSelect.addEventListener('change', function () {
      // Reset filter jenis ke opsi default
      jenisSelect.selectedIndex = 0;
      form.submit();
    });

    // Submit otomatis saat jenis diubah (tanpa reset kota)
    jenisSelect.addEventListener('change', function () {
      form.submit();
    });
  });
</script>


</body>
</html>