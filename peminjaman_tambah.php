<h1 class="mt-4">Peminjaman Buku</h1>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <form method="post">
                    <?php
                    // Memeriksa apakah tanggal pengembalian telah lewat
                    if (isset($_POST['submit'])) {

                        // Dapatkan ID buku yang dipilih oleh pengguna
                        $id_buku = $_POST['id_buku'];

                        // Memeriksa apakah pengguna sudah meminjam buku dengan ID yang sama dan status "dipinjam"
                        $query_check_borrowed_book = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah_pinjaman FROM peminjaman WHERE id_user = " . $_SESSION['user']['id_user'] . " AND id_buku = $id_buku AND status_peminjaman = 'dipinjam'");
                        $data_check_borrowed_book = mysqli_fetch_assoc($query_check_borrowed_book);
                        $jumlah_pinjaman = $data_check_borrowed_book['jumlah_pinjaman'];

                        if ($jumlah_pinjaman >= 1) {
                            // Jika pengguna sudah meminjam buku dengan ID yang sama dan status "dipinjam", tampilkan pesan kesalahan
                            echo '<script>alert("Maaf, Anda sudah meminjam buku ini.");</script>';
                        } else {
                            // Jika pengguna belum meminjam buku tersebut, izinkan pengguna untuk meminjam buku
                            // Lanjutan kode Anda...
                            $id_user = $_SESSION['user']['id_user'];
                            $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
                            $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
                            $status_peminjaman = isset($_POST['status_peminjaman']) ? $_POST['status_peminjaman'] : ''; // Periksa apakah kunci 'status_peminjaman' ada
                            if (strtotime($tanggal_pengembalian) < time()) {
                                $status_peminjaman = 'dikembalikan';
                            } else if (strtotime($tanggal_pengembalian) > time()) {
                                $status_peminjaman = 'dipinjam';
                            }

                            // Lakukan penambahan data peminjaman
                            // Kode tambahan Anda...
                            $query = mysqli_query($koneksi, "INSERT INTO peminjaman(id_buku,id_user,tanggal_peminjaman,tanggal_pengembalian,status_peminjaman) values('$id_buku','$id_user','$tanggal_peminjaman','$tanggal_pengembalian','$status_peminjaman')");
                            if ($query) {
                                echo '<script>alert("Tambah data berhasil. ");</script>';
                            } else {
                                echo '<script>alert("Tambah data gagal. ");</script>';
                            }
                        }

                    }
                    ?>

                    <div class="row mb-3">
                        <div class="col-md-2">Buku</div>
                        <div class="col-md-8">
                            <select name="id_buku" class="form-control" onchange="updateImageAndDescription()">
                                <?php
                                $buk = mysqli_query($koneksi, "SELECT * FROM buku");
                                while ($buku = mysqli_fetch_array($buk)) {
                                    ?>
                                    <option value="<?php echo $buku['id_buku']; ?>">
                                        <?php echo $buku['judul']; ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">Sampul</div>
                        <div class="col-md-8">
                            <img src="" id="gambar_buku" style="max-width: 200px; max-height: 200px;" alt="Gambar Buku">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-2">Deskripsi</div>
                        <div class="col-md-8">
                            <textarea class="form-control" name="deskripsi_buku" id="deskripsi_buku" rows="4"
                                readonly></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2">Tanggal Peminjaman</div>
                        <div class="col-md-8">
                            <input type="date" class="form-control" name="tanggal_peminjaman" id="tanggal_peminjaman"
                                onchange="hitungTanggalPengembalian()">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2">Tanggal Pengembalian</div>
                        <div class="col-md-8">
                            <input type="date" class="form-control" name="tanggal_pengembalian"
                                id="tanggal_pengembalian" readonly>
                        </div>
                    </div>
                    <script>
                        function hitungTanggalPengembalian() {
                            var tanggal_peminjaman = document.getElementById('tanggal_peminjaman').value;
                            var tanggal_pengembalian = new Date(tanggal_peminjaman);
                            tanggal_pengembalian.setDate(tanggal_pengembalian.getDate() + 10);

                            var year = tanggal_pengembalian.getFullYear();
                            var month = ('0' + (tanggal_pengembalian.getMonth() + 1)).slice(-2);
                            var day = ('0' + tanggal_pengembalian.getDate()).slice(-2);

                            var tanggal = year + '-' + month + '-' + day;
                            document.getElementById('tanggal_pengembalian').value = tanggal;
                        }

                        function updateImageAndDescription() {
                            var selectedBookId = document.getElementsByName('id_buku')[0].value;
                            var buk = <?php echo json_encode(mysqli_fetch_all(mysqli_query($koneksi, "SELECT id_buku, gambar, deskripsi FROM buku"), MYSQLI_ASSOC)); ?>;
                            var selectedBook = buk.find(book => book.id_buku == selectedBookId);
                            if (selectedBook) {
                                document.getElementById('gambar_buku').src = 'gambar/' + selectedBook.gambar;
                                document.getElementById('deskripsi_buku').textContent = selectedBook.deskripsi;
                            } else {
                                document.getElementById('gambar_buku').src = '';
                                document.getElementById('deskripsi_buku').textContent = '';
                            }
                        }
                    </script>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary" name="submit" value="submit">Simpan</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <a href="?page=peminjaman" class="btn btn-danger">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>