<?php
// koneksi ke database
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "DELETE FROM tb_bantuan_sosial WHERE id_bantuan_sosial = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Notifikasi sukses hapus
        echo '
        <html>
        <head>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "Data Berhasil Dihapus!",
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
        // Notifikasi gagal hapus
        echo '
        <html>
        <head>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Gagal Menghapus Data",
                    text: "' . mysqli_error($conn) . '"
                }).then(() => {
                    window.history.back();
                });
            </script>
        </body>
        </html>';
    }
} else {
    echo "ID bantuan tidak ditemukan.";
}
?>
