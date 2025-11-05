<?php

    session_start();
    require 'koneksi/koneksi.php';
    include 'header.php';
    include 'progress_bar.php';
    if(empty($_SESSION['USER']))
    {
        echo '<script>alert("Harap login !");window.location="index.php"</script>';
    }
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if (empty($id)) {
        echo '<script>alert("ID mobil tidak ditemukan!");window.location="blog.php"</script>';
        exit();
    }
    // Validate that id is numeric
    if (!is_numeric($id)) {
        echo '<script>alert("ID mobil tidak valid!");window.location="blog.php"</script>';
        exit();
    }
    $isi = $koneksi->query("SELECT * FROM mobil WHERE id_mobil = '$id'")->fetch();
    if (!$isi) {
        echo '<script>alert("Mobil tidak ditemukan!");window.location="blog.php"</script>';
        exit();
    }
?>
<br>
<br>
<div class="container">
<div class="row">
    <div class="col-sm-4">
        <div class="card">
            <img src="assets/image/<?php echo $isi['gambar'];?>" class="card-img-top" style="height:200px;">
            <div class="card-body" style="background:#ddd">
            <h5 class="card-title"><?php echo $isi['merk'];?></h5>
            </div>
            <ul class="list-group list-group-flush">

            <?php if($isi['status'] == 'Tersedia'){?>
                <li class="list-group-item bg-primary text-white">
                    <i class="fa fa-check"></i> Available
                </li>
            <?php }else{?>
                <li class="list-group-item bg-danger text-white">
                    <i class="fa fa-close"></i> Not Available
                </li>
            <?php }?>
            
            <li class="list-group-item bg-dark text-white">
                <i class="fa fa-money"></i> Rp. <?php echo number_format($isi['harga']);?>/ day
            </li>
            </ul>
            <?php if(!empty($isi['keunggulan'])){ ?>
            <div class="card-body" style="background:#ddd">
                <h5 class="card-title">Keunggulan Paket</h5>
            </div>
            <ul class="list-group list-group-flush">
                <?php
                $keunggulan = explode('||', $isi['keunggulan']);
                foreach ($keunggulan as $item) {
                    if (!empty($item)) {
                ?>
                        <li class="list-group-item">
                            <i class="fa fa-check text-success"></i> <?= htmlspecialchars($item) ?>
                        </li>
                <?php
                    }
                }
                ?>
            </ul>
            <?php } ?>
        </div>
    </div>
    <div class="col-sm-8">
         <div class="card">
           <div class="card-body">
               <form method="post" action="koneksi/proses.php?id=booking">
                    <div class="form-group">
                      <label for="">KTP</label>
                      <input type="text" name="ktp" id="" required class="form-control" placeholder="KTP / NIK Anda">
                    </div> 
                    <div class="form-group">
                      <label for="">Nama</label>
                      <input type="text" name="nama" id="" required class="form-control" placeholder="Nama Anda">
                    </div> 
                    <div class="form-group">
                      <label for="">Alamat</label>
                      <input type="text" name="alamat" id="" required class="form-control" placeholder="Alamat">
                    </div> 
                    <div class="form-group">
                      <label for="">Telepon</label>
                      <input type="text" name="no_tlp" id="" required class="form-control" placeholder="Telepon">
                    </div> 
                    <div class="form-group">
                      <label for="">Tanggal Sewa</label>
                      <input type="date" name="tanggal" id="" required class="form-control" placeholder="Nama Anda">
                    </div> 
                    <div class="form-group">
                      <label for="">Lama Sewa</label>
                      <input type="number" name="lama_sewa" id="lama_sewa" required class="form-control" placeholder="Lama Sewa">
                    </div>
                    <div class="form-group">
                      <label for="">Pilih Supir (Opsional)</label>
                      <div class="d-flex">
                        <a href="supir.php?id=<?= $isi['id_mobil']; ?>" class="btn btn-info mr-2">Pilih Supir</a>
                        <button type="button" id="hapus_supir" class="btn btn-secondary" style="display:none;">Hapus Supir</button>
                      </div>
                      <div id="supir_info" class="mt-2" style="display:none;">
                        <small class="text-muted">Supir dipilih: <span id="nama_supir"></span> - Rp<span id="harga_supir_display"></span>/hari</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="">Total Harga</label>
                      <input type="text" id="total_harga_display" class="form-control" readonly value="Rp<?= number_format($isi['harga']); ?>">
                    </div>
                    <input type="hidden" value="<?php echo $_SESSION['USER']['id_login'];?>" name="id_login">
                    <input type="hidden" value="<?php echo $isi['id_mobil'];?>" name="id_mobil">
                    <input type="hidden" value="<?php echo $isi['harga'];?>" name="harga_mobil">
                    <input type="hidden" id="harga_supir" name="harga_supir" value="0">
                    <input type="hidden" id="id_supir" name="id_supir" value="">
                    <input type="hidden" value="" name="total_harga" id="total_harga">
                    <hr/>
                    <?php if($isi['status'] == 'Tersedia'){?>
                        <button type="submit" class="btn float-right" style="background-color:#FF6B35;">Booking Now</button>
                    <?php }else{?>
                        <button type="submit" class="btn btn-danger float-right" disabled>Booking Now</button>
                    <?php }?>
               </form>
           </div>
         </div> 
    </div>
</div>
</div>

<br>

<br>

<br>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cek apakah ada supir yang dipilih dari sessionStorage
    const selectedSupir = sessionStorage.getItem('selectedSupir');
    if (selectedSupir) {
        const supir = JSON.parse(selectedSupir);
        document.getElementById('id_supir').value = supir.id;
        document.getElementById('harga_supir').value = supir.harga;
        document.getElementById('nama_supir').textContent = supir.nama;
        document.getElementById('harga_supir_display').textContent = supir.harga.toLocaleString();
        document.getElementById('supir_info').style.display = 'block';
        document.getElementById('hapus_supir').style.display = 'inline-block';
        updateTotalHarga();
        sessionStorage.removeItem('selectedSupir'); // Hapus dari sessionStorage setelah digunakan
    }

    // Event listener untuk tombol hapus supir
    document.getElementById('hapus_supir').addEventListener('click', function() {
        document.getElementById('id_supir').value = '';
        document.getElementById('harga_supir').value = '0';
        document.getElementById('supir_info').style.display = 'none';
        document.getElementById('hapus_supir').style.display = 'none';
        updateTotalHarga();
    });

    // Event listener untuk perubahan lama sewa
    document.getElementById('lama_sewa').addEventListener('input', updateTotalHarga);

    function updateTotalHarga() {
        const hargaMobil = parseInt('<?= $isi['harga']; ?>');
        const hargaSupir = parseInt(document.getElementById('harga_supir').value) || 0;
        const lamaSewa = parseInt(document.getElementById('lama_sewa').value) || 0;
        const total = (hargaMobil + hargaSupir) * lamaSewa;
        document.getElementById('total_harga_display').value = 'Rp' + total.toLocaleString();
        document.getElementById('total_harga').value = total;
    }
});
</script>

<?php include 'footer.php';?>
