<?php $this->app->extend('template/home') ?>

<?php $this->app->setVar('title', 'Login') ?>

<?php $this->app->section() ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <div class="text-center text-muted mb-4">
                    Masukkan data anda!
                </div>
                <form id="login">
                    <div class="form-group mb-3">
                        <div class="input-group input-group-merge input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                            </div>
                            <input class="form-control" name="email" autocomplete="off" placeholder="Email" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-merge input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                            </div>
                            <input class="form-control" name="nip" autocomplete="off" placeholder="Password" type="password">
                        </div>
                    </div>
                    <div class="custom-control custom-control-alternative custom-checkbox">
                        <input class="custom-control-input" id="lihat-password" type="checkbox">
                        <label class="custom-control-label" for="lihat-password">
                            <span class="text-muted">Lihat Password</span>
                        </label>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary my-4">Masuk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->app->endSection('content') ?>

<?php $this->app->section() ?>
<script>
    $(document).ready(function() {

        $(document).on('click', '#lihat-password', function() {
            if ($(this).prop('checked')) {
                $('[type=password]').attr('type', 'passwordText');
            } else {
                $('[type=passwordText]').attr('type', 'password');
            }
        })

        $(document).on('submit', 'form#login', function(e) {
            e.preventDefault();
            call('api/auth/login', $(this).serialize()).done(function(req) {
                if (req.error == true) {
                    notif(req.message, 'error', true);
                } else {
                    $('form#login [name]').val('');
                    notif(req.message, 'success').then(function() {
                        window.location = base_url + 'auth/cek/' + req.data.id + '/' + req.data.level;
                    });
                }
            })
        })

    })
</script>
<?php $this->app->endSection('script') ?>

<?php $this->app->init() ?>