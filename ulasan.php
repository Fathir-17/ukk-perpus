<?php
// Periksa apakah pengguna saat ini adalah pemilik ulasan
$query = mysqli_query($koneksi, "SELECT * FROM ulasan LEFT JOIN user ON user.id_user = ulasan.id_user LEFT JOIN buku ON buku.id_buku = ulasan.id_buku");
$is_owner = false; // Inisialisasi variabel untuk menentukan apakah pengguna saat ini adalah pemilik ulasan
while ($data = mysqli_fetch_array($query)) {
    if(isset($_SESSION['user']['id_user'])) {
    if ($_SESSION['user']['id_user'] == $data['id_user']) {
        $is_owner = true;
        break; // Jika pengguna saat ini adalah pemilik ulasan, hentikan loop
    }
}
}

// Periksa status peminjaman pengguna saat ini
$query_status_peminjaman = mysqli_query($koneksi, "SELECT status_peminjaman FROM peminjaman WHERE id_user = " . $_SESSION['user']['id_user']);
$status_peminjaman = null; // Inisialisasi status_peminjaman dengan null

if ($query_status_peminjaman) {
    $data_status_peminjaman = mysqli_fetch_assoc($query_status_peminjaman);
    if ($data_status_peminjaman) {
        $status_peminjaman = $data_status_peminjaman['status_peminjaman'];
    } else {
        // Handle if no data is fetched
        // Optionally, you can set a default value for $status_peminjaman here
    }
} else {
    // Handle if query execution fails
    // Optionally, you can set a default value for $status_peminjaman here
}
?>

<h1 class="mt-4">Ulasan Buku</h1>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php if ($is_owner || $status_peminjaman == 'dikembalikan'): ?>
                    <a href="?page=ulasan_tambah" class="btn btn-primary">+ Tambah Ulasan</a>
                <?php endif; ?>

                <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Sampul</th>
                        <th>Buku</th>
                        <th>Ulasan</th>
                        <th>Rating</th>
                        <?php if ($is_owner || $status_peminjaman == 'dikembalikan'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                    <?php
                    $i = 1;
                    mysqli_data_seek($query, 0); // Mengembalikan pointer ke awal hasil query
                    while ($data = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $data['nama']; ?></td>
                            <td><img src="gambar/<?php echo $data['gambar']; ?>" style="width: 150px; height: 200px;"></td>
                            <td><?php echo $data['judul']; ?></td>
                            <td><?php echo $data['ulasan']; ?></td>
                            <td><?php echo $data['rating']; ?></td>
                            <?php if ($is_owner && $_SESSION['user']['id_user'] == $data['id_user']): ?>
                                <td>
                                    <a href="?page=ulasan_ubah&&id=<?php echo $data['id_ulasan']; ?>" class="btn btn-info">Ubah</a>
                                    <a onclick="return confirm('Apakah anda yakin ?');" href="?page=ulasan_hapus&&id=<?php echo $data['id_ulasan']; ?>" class="btn btn-danger">Hapus</a>
                                </td>
                            <?php elseif (!$is_owner && $status_peminjaman == 'dikembalikan'): ?>
                                <td>
                                    <a href="?page=ulasan_tambah&&id_buku=<?php echo $data['id_buku']; ?>" class="btn btn-primary">Tambah Ulasan</a>
                                </td>
                            <?php else: ?>
                                <td></td> <!-- Jika bukan pemilik dan bukan status peminjaman dikembalikan, tidak menampilkan aksi -->
                            <?php endif; ?>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
