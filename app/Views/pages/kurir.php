<?= $this->extend('layout/tem_kurir'); ?>

<?= $this->section('content'); ?>

<div class="wrapper">
    <div class="container">
        <form action="/checkout/buatPesanan" method="post" onsubmit="return confirm('Apakah data sudah benar?');" enctype="multipart/form-data">
            <h1>
                <i class="fas fa-check-circle"></i>
                Verifikasi Pesanan
            </h1>
            <div class="name">
                <div>
                    <label for="f-name">Masukkan kode transaksi</label>
                    <input type="text" name="namaPenerima">
                </div>
            </div>
            <div class="name">
                <div>
                    <label for="city">Bukti Struk</label>
                    <input type="file" id="file" class="custom-file-input">
                </div>
            </div>
            <div class="btns">
                <button type="submit" id="submit">Verifikasi</button>
                <button><a href="/daftar_belanja">Kembali</a></button>
            </div>
        </form>
    </div>
</div>


<?= $this->endSection(); ?>