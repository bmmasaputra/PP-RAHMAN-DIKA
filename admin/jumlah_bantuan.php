<?php
include 'koneksi.php';

$query = "
  SELECT 
    k.nama_kota,
    COUNT(b.id_bantuan_sosial) AS jumlah_bantuan
  FROM tb_kabupaten_kota k
  LEFT JOIN tb_bantuan_sosial b ON k.id_kota = b.id_kota
  GROUP BY k.id_kota
  ORDER BY jumlah_bantuan DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Jumlah Bantuan per Kota</title>
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="template/vendors/codemirror/codemirror.css">
  <link rel="stylesheet" href="template/vendors/codemirror/ambiance.css">
  <link rel="stylesheet" href="template/vendors/pwstabs/jquery.pwstabs.min.css">
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="images/kalteng.png" />

  <!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="main-panel w-100 documentation">
        <div class="content-wrapper">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12 pt-5">
                <a class="btn btn-primary" href="dash_admin.php"><i class="ti-home mr-2"></i>Back to home</a>
              </div>
            </div>
            <div class="row pt-5 mt-5">
              <div class="col-12 pt-3">
                <h4 class="text-center mb-4">Data Jumlah Bantuan Sosial per Kota/Kabupaten</h4>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover text-center">
                    <thead class="thead-dark">
                      <tr>
                        <th>No</th>
                        <th>Nama Kota/Kabupaten</th>
                        <th>Jumlah Bantuan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      while ($row = mysqli_fetch_assoc($result)) {
                          echo "<tr>";
                          echo "<td>{$no}</td>";
                          echo "<td>{$row['nama_kota']}</td>";
                          echo "<td>{$row['jumlah_bantuan']}</td>";
                          echo "</tr>";
                          $no++;
                      }
                      ?>
                    </tbody>
                  </table>

                  </div> <!-- end table-responsive -->

<!-- Canvas untuk chart -->
<div class="mt-5">
  <h5 class="text-center mb-3">Visualisasi Jumlah Bantuan Sosial per Kota/Kabupaten</h5>
  <canvas id="bantuanChart" height="100"></canvas>
</div>

                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Footer can be added here if needed -->
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <script src="js/codeEditor.js"></script>
  <script src="js/tabs.js"></script>
  <script src="js/tooltips.js"></script>
  <script src="js/documentation.js"></script>

  <script>
// Ambil data dari PHP
const labels = <?php
  mysqli_data_seek($result, 0); // reset pointer
  $labels = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['nama_kota'];
  }
  echo json_encode($labels);
?>;

const data = <?php
  mysqli_data_seek($result, 0);
  $jumlah = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $jumlah[] = $row['jumlah_bantuan'];
  }
  echo json_encode($jumlah);
?>;

// Inisialisasi Chart.js
const ctx = document.getElementById('bantuanChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: labels,
    datasets: [{
      label: 'Jumlah Bantuan',
      data: data,
      backgroundColor: 'rgba(54, 162, 235, 0.6)',
      borderColor: 'rgba(54, 162, 235, 1)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true,
        title: {
          display: true,
          text: 'Jumlah Bantuan'
        }
      },
      x: {
        ticks: {
          maxRotation: 90,
          minRotation: 45
        }
      }
    }
  }
});
</script>

</body>

</html>
