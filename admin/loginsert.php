<?php
include 'koneksi.php';

if (isset($_POST['nama_bantuan'])) {
    $nama_bantuan = $_POST['nama_bantuan'];
    $id_jenis_bantuan = $_POST['id_jenis_bantuan'];
    $syarat_pendaftaran = $_POST['syarat_pendaftaran'];
    $mekanisme_pendaftaran = $_POST['mekanisme_pendaftaran'];
    $deskripsi_bantuan = $_POST['deskripsi_bantuan'];
    $id_kota = $_POST['id_kota'];
    $tanggal_pelaksanaan = $_POST['tanggal_pelaksanaan'];
    $tanggal_berakhir = $_POST['tanggal_berakhir'];
    $status_bantuan = $_POST['status_bantuan'];

    $gambar = null;
    if (!empty($_FILES['gambar']['tmp_name'])) {
        $gambar = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
    }

    // 1. Generate new ID like the trigger used to
    $query_id = "SELECT MAX(CAST(SUBSTRING(id_bantuan_sosial, 4) AS UNSIGNED)) AS max_id FROM tb_bantuan_sosial";
    $result_id = mysqli_query($conn, $query_id);
    $row = mysqli_fetch_assoc($result_id);
    $next_number = $row['max_id'] + 1;
    $new_id = 'BNT' . str_pad($next_number, 4, '0', STR_PAD_LEFT);

    // 2. Insert with manually generated ID
    $query = "INSERT INTO tb_bantuan_sosial 
        (id_bantuan_sosial, nama_bantuan, id_jenis_bantuan, syarat_pendaftaran, mekanisme_pendaftaran, deskripsi_bantuan, id_kota, tanggal_pelaksanaan, tanggal_berakhir, status_bantuan, gambar) 
        VALUES 
        ('$new_id', '$nama_bantuan', '$id_jenis_bantuan', '$syarat_pendaftaran', '$mekanisme_pendaftaran', '$deskripsi_bantuan', '$id_kota', '$tanggal_pelaksanaan', '$tanggal_berakhir', '$status_bantuan', '$gambar')";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo '
        <html>
        <head><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script></head>
        <body>
        <script>
            Swal.fire({
                icon: "success",
                title: "Data Berhasil Ditambahkan!",
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "dash_admin.php";
            });
        </script>
        </body>
        </html>';
    } else {
        echo '
        <html>
        <head><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script></head>
        <body>
        <script>
            Swal.fire({
                icon: "error",
                title: "Gagal Menambahkan Data",
                text: "' . mysqli_error($conn) . '"
            }).then(() => {
                window.history.back();
            });
        </script>
        </body>
        </html>';
    }
} else {
    echo "Data tidak lengkap!";
}
?>
