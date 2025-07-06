<?php $this->app->extend('template/home') ?>

<?php $this->app->setVar('title', "Registrasi") ?>

<?php $this->app->section() ?>
<div class="card">
    <div class="card-header">
        <div class="card-title">Registrasi Mahasiswa</div>
    </div>
    <div class="card-body">
        <span class="text-danger">*</span> Harus Diisi
        <form id="registrasi" style="margin-top: 10px;" onsubmit="loadingBtn()">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>NIM <span class="text-danger">*</span></label>
                        <input id="nim" type="text" name="nim" autocomplete="off" autofocus="true" class="form-control" placeholder="Masukkan NIM ( Kombinasi angka dan huruf 9 digit )" maxlength="9">
                    </div>
                    <div class="form-group">
                        <label>Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" autocomplete="off" class="form-control" placeholder="Masukkan Nama">
                    </div>
                    <div class="form-group">
                        <label>Prodi <span class="text-danger">*</span></label>
                        <select name="prodi_id" class="form-control">
                            <option value="">- Pilih Prodi -</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="">- Pilih Jenis Kelamin -</option>
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" name="tempat_lahir" autocomplete="off" class="form-control" placeholder="Masukkan Tempat Lahir">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_lahir" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="text" name="email" autocomplete="off" class="form-control" placeholder="Masukkan Email">
                    </div>
                    <div class="form-group">
                        <label>Alamat <span class="text-danger">*</span></label>
                        <textarea name="alamat" placeholder="Masukkan Alamat" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_telepon" autocomplete="off" class="form-control" placeholder="Masukkan Nomor Telepon">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Alamat Orang Tua <span class="text-danger">*</span></label>
                        <textarea name="alamat_orang_tua" rows="5" class="form-control" placeholder="Masukkan Alamat Orang Tua"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon Orang Tua <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_telepon_orang_tua" autocomplete="off" class="form-control" placeholder="Masukkan Nomor Telepon Orang Tua">
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon Orang Dekat <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_telepon_orang_dekat" autocomplete="off" class="form-control" placeholder="Masukkan Nomor Telepon Orang Dekat">
                    </div>
                    <div class="form-group">
                        <label>IPK <span class="text-danger">*</span></label>
                        <input type="text" name="ipk" autocomplete="off" class="form-control" placeholder="Masukkan IPK">
                    </div>
                    <div class="form-group">
                        <label>Password <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="password" autocomplete="off" placeholder="Masukkan Password">
                    </div>
                    <div class="form-group">
                        <label>Password (Konfirmasi) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="password_konfirmasi" autocomplete="off" placeholder="Masukkan Password (Konfirmasi)">
                    </div>
                    <div class="form-group">
                        <label>Foto Mahasiswa</label>
                        <div class="custom-file pilih-foto">
                            <input type="file" accept="image/*" class="custom-file-input">
                            <label class="custom-file-label"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="card shadow p-3 text-center" style="height: 300px">
                            <input type="hidden" name="foto">
                            <img src="<?= base_url() ?>cdn/img/mahasiswa/default.png" class="foto foto-fluid">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-right">
                <button class="btn btn-warning" type="reset">Reset</button>
                <button type="submit" class="btn btn-default btn-act">Submit</button>
            </div>
        </form>
    </div>
</div>
<?php $this->app->endSection('content') ?>

<?php $this->app->section() ?>
<script src="<?= base_url() ?>cdn/plugins/canvas-resize/jquery.canvasResize.js"></script>
<script src="<?= base_url() ?>cdn/plugins/canvas-resize/jquery.exif.js"></script>
<script src="<?= base_url() ?>cdn/plugins/canvas-resize/canvasResize.js"></script>
<script src="<?= base_url() ?>cdn/plugins/canvas-resize/exif.js"></script>
<script src="<?= base_url() ?>cdn/plugins/canvas-resize/binaryajax.js"></script>
<script src="<?= base_url() ?>cdn/plugins/canvas-resize/zepto.min.js"></script>
<script>
    function loadingBtn() {
        $(".btn-act").attr('disabled', true).html('Loading...')
    }

    $(document).ready(function() {
        $("#nim").keyup(function() {
            if ($(this).val().length == 9) {
                var inp = $(this).val();
                if (/[a-zA-Z_ ]/.test(inp) && /[0-9-_ ]/.test(inp)) {
                    $(".btn-act").attr('disabled', false)
                } else {
                    notif('Mohon masukkan dengan format yang benar, harus ada angka dan huruf', 'error', true);
                    $(".btn-act").attr('disabled', true)
                }
            } else {
                notif('Silahkan Masukkan 9 digit', 'info', true);
                $(".btn-act").attr('disabled', true)
            }
        })

        call('api/prodi').done(function(req) {
            prodi = '<option value="">- Pilih Prodi -</option>';
            if (req.data) {
                $.each(req.data, function(index, obj) {
                    prodi += '<option value="' + obj.id + '">' + obj.nama + '</option>'
                })
            }
            $('[name=prodi_id]').html(prodi);
        })

        $(document).on('submit', 'form#registrasi', function(e) {
            e.preventDefault();
            if ($('form#registrasi [name=password]').val() == $('form#registrasi [name=password_konfirmasi]').val()) {
                call('api/mahasiswa/create', $(this).serialize()).done(function(req) {
                    if (req.error == true) {
                        notif(req.message, 'error', true);
                        $(".btn-act").attr('disabled', false).html('Submit')
                    } else {
                        $('form#registrasi [name]').val('');
                        $('img.foto').attr('src', '');
                        notif(req.message, 'success');
                        $(".btn-act").attr('disabled', false).html('Submit')
                    }
                })
            } else {
                notif('konfirmasi password harus sama', 'error', true);
                $(".btn-act").attr('disabled', false).html('Submit')
            }
        })

        $(document).on('change', '.pilih-foto [type=file]', function(e) {
            canvasResize(this.files[0], {
                height: 500,
                width: 500,
                crop: true,
                rotate: 0,
                quality: 200,
                callback: function(data) {
                    $('img.foto').attr('src', data);
                    $('[name=foto]').val(data);
                }
            })
        })

    })
</script>
<?php $this->app->endSection('script') ?>

<?php $this->app->init() ?>