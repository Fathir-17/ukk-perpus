

<h1 class="mt-4">Laporan Peminjaman Buku</h1>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <a href="cetak.php" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Cetak Data</a>
                <form method="get">
                    <div class="row mb-3">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                          
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </form>
                <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Buku</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Status Peminjaman</th>
                    </tr>
                    <?php
                    $i = 1;
                    // Buat kueri SQL dengan filter bulan jika bulan dipilih
                    $sql = "SELECT * FROM peminjaman 
                            LEFT JOIN user ON user.id_user = peminjaman.id_user 
                            LEFT JOIN buku ON buku.id_buku = peminjaman.id_buku";
                    if (!empty($selected_month)) {
                        // Ubah tanggal bulan menjadi dua digit
                        $selected_month = str_pad($selected_month, 2, '0', STR_PAD_LEFT);
                        // Filter berdasarkan bulan pada tanggal pengembalian atau tanggal peminjaman
                        $sql .= " WHERE DATE_FORMAT(tanggal_pengembalian, '%m') = '$selected_month' OR DATE_FORMAT(tanggal_peminjaman, '%m') = '$selected_month'";
                    }
                    $query = mysqli_query($koneksi, $sql);
                    while ($data = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $data['nama']; ?></td>
                            <td><?php echo $data['judul']; ?></td>
                            <td><?php echo $data['tanggal_peminjaman']; ?></td>
                            <td><?php echo $data['tanggal_pengembalian']; ?></td>
                            <td><?php echo $data['status_peminjaman']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
