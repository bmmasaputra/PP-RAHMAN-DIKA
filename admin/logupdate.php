<?php
include 'koneksi.php';

if (isset($_POST['id_bantuan_sosial'])) {
    $id = $_POST['id_bantuan_sosial'];
    $nama_bantuan = $_POST['nama_bantuan'];
    $id_jenis_bantuan = $_POST['id_jenis_bantuan'];
    $syarat_pendaftaran = $_POST['syarat_pendaftaran'];
    $mekanisme_pendaftaran = $_POST['mekanisme_pendaftaran'];
    $deskripsi_bantuan = $_POST['deskripsi_bantuan'];
    $id_kota = $_POST['id_kota'];
    $tanggal_pelaksanaan = $_POST['tanggal_pelaksanaan'];
    $tanggal_berakhir = $_POST['tanggal_berakhir'];
    $status_bantuan = $_POST['status_bantuan'];

    if (!empty($_FILES['gambar']['tmp_name'])) {
        $gambar = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
        $query = "UPDATE tb_bantuan_sosial SET 
                    nama_bantuan='$nama_bantuan', 
                    id_jenis_bantuan='$id_jenis_bantuan', 
                    syarat_pendaftaran='$syarat_pendaftaran',
                    mekanisme_pendaftaran='$mekanisme_pendaftaran',
                    deskripsi_bantuan='$deskripsi_bantuan',
                    id_kota='$id_kota',
                    tanggal_pelaksanaan='$tanggal_pelaksanaan',
                    tanggal_berakhir='$tanggal_berakhir',
                    status_bantuan='$status_bantuan',
                    gambar='$gambar' 
                  WHERE id_bantuan_sosial='$id'";
    } else {
        $query = "UPDATE tb_bantuan_sosial SET 
                    nama_bantuan='$nama_bantuan', 
                    id_jenis_bantuan='$id_jenis_bantuan', 
                    syarat_pendaftaran='$syarat_pendaftaran',
                    mekanisme_pendaftaran='$mekanisme_pendaftaran',
                    deskripsi_bantuan='$deskripsi_bantuan',
                    id_kota='$id_kota',
                    tanggal_pelaksanaan='$tanggal_pelaksanaan',
                    tanggal_berakhir='$tanggal_berakhir',
                    status_bantuan='$status_bantuan' 
                  WHERE id_bantuan_sosial='$id'";
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        // Sukses: Tampilkan notifikasi dan redirect
        echo '
        <html>
        <head>
          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
          <script>
            Swal.fire({
              icon: "success",
              title: "Data Berhasil Diupdate!",
              text: "",
              timer: 2000,
              showConfirmButton: false
            }).then(() => {
              window.location.href = "dash_admin.php";
            });
          </script>
        </body>
        </html>';
    } else {
        // Gagal: Tampilkan error
        echo '
        <html>
        <head>
          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
          <script>
            Swal.fire({
              icon: "error",
              title: "Gagal Mengupdate Data",
              text: "' . mysqli_error($conn) . '"
            }).then(() => {
              window.history.back();
            });
          </script>
        </body>
        </html>';
    }
} else {
    echo "ID tidak ditemukan!";
}
?>
