<h1 class="mt-4">Peminjaman Buku</h1>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <a href="?page=peminjaman_tambah" target="_blank" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Peminjaman</a>
                <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Buku</th>
                        <th>Gambar</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Status Peminjaman</th>
                        <th>Link</th> <!-- Tambahkan kolom Link -->
                    </tr>
                    <?php
                    $i = 1;
                    $query = mysqli_query($koneksi, "SELECT * FROM peminjaman LEFT JOIN user ON user.id_user = peminjaman.id_user LEFT JOIN buku ON buku.id_buku = peminjaman.id_buku WHERE peminjaman.id_user = " . $_SESSION['user']['id_user']);
                    while($data = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $data['nama']; ?></td>
                            <td><?php echo $data['judul']; ?></td>
                            <td><img src="gambar/<?php echo $data['gambar']; ?>" style="max-width: 100px; max-height: 100px;" alt="Gambar Buku"></td>
                            <td><?php echo $data['tanggal_peminjaman']; ?></td>
                            <td><?php echo $data['tanggal_pengembalian']; ?></td>
                            <td><?php echo $data['status_peminjaman']; ?></td>
                            <td>
                                <?php 
                                // Tentukan URL link berdasarkan buku yang dipinjam
                                $url_lengkap =  $data['link'];
                                // Tampilkan link hanya jika status peminjaman adalah 'dipinjam'
                                if ($data['status_peminjaman'] == 'dipinjam') {
                                    // Tampilkan link hanya untuk user yang meminjam buku
                                    if ($_SESSION['user']['id_user'] == $data['id_user']) {
                                        echo '<a href="' . $url_lengkap . '" target="_blank">Klik Disini</a>';
                                    } else {
                                        // echo '';
                                    }
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
