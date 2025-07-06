<?php
$id_user = $this->session->userdata('id');
$verifikasi = '';
$dataUser = $this->db->get_where('mahasiswa', array('id' => $id_user))->result();
foreach ($dataUser as $du) {
    $verifikasi = $du->status;
}
?>
<?php $this->app->extend('template/mahasiswa') ?>

<?php $this->app->setVar('title', "Dashboard") ?>

<?php $this->app->section() ?>
<?php if ($verifikasi == 1) { ?>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title text-uppercase text-muted mb-0">Deadline Proposal Sampai Skripsi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Deadline Skripsi Countdown</th>
                            <th>Tanggal Deadline</th>
                            <th>Judul Proposal</th>
                        </tr>
                    </thead>
                    <tbody id="_tbody">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card card-stats">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Total Proposal</h5>
                    <span class="h2 font-weight-bold mb-0 total-proposal">0</span>
                </div>
                <div class="col-auto">
                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                        <i class="ni ni-active-40"></i>
                    </div>
                </div>
            </div>
            <p class="mt-3 mb-0 text-sm">
                <a href="<?= base_url() ?>mahasiswa/proposal" class="text-success mr-2"><i class="fa fa-arrow-left"></i> Selengkapnya</a>
            </p>
        </div>
    </div>
<?php } ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <img src="<?= base_url() ?>cdn/img/mahasiswa/default.png" class="foto card-img">
            </div>
            <div class="col-md-9">
                <div class="row p-2">
                    <div class="col-6">Nama</div>
                    <div class="col-6"><strong class="nama">Nama Mahasiswa</strong></div>
                </div>
                <div class="row p-2">
                    <div class="col-6">Prodi</div>
                    <div class="col-6"><strong class="prodi_nama">Nama Prodi</strong></div>
                </div>
                <div class="row p-2">
                    <div class="col-6">Fakultas</div>
                    <div class="col-6"><strong class="prodi_fakultas_nama">Nama Fakultas</strong></div>
                </div>
                <div class="row p-2">
                    <div class="col-6">Email</div>
                    <div class="col-6"><strong class="email">Email Mahasiswa</strong></div>
                </div>
                <div class="row p-2 mb-5">
                    <div class="col-6">Nomor Telepon</div>
                    <div class="col-6"><strong class="nomor_telepon">Nomor Telepon Mahasiswa</strong></div>
                </div>
                <div style="position: absolute; bottom: 10px; right: 10px;">
                    <a href="<?= base_url() ?>mahasiswa/profil" class="btn btn-primary btn-sm">
                        Selengkapnya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->app->endSection('content') ?>

<?php $this->app->section() ?>
<link rel="stylesheet" href="<?= base_url() ?>cdn/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<script src="<?= base_url() ?>cdn/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>cdn/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>assets/countdown/jquery.countdown.min.js"></script>
<script>
    $(document).ready(function() {
        call('api/mahasiswa/detail/<?= $this->session->userdata('id') ?>').done(function(req) {
            if (req.data) {
                $('.nama').html(req.data.nama);
                $('.prodi_nama').html(req.data.prodi.nama);
                $('.prodi_fakultas_nama').html(req.data.prodi.fakultas.nama);
                $('.email').html(req.data.email);
                $('.nomor_telepon').html(req.data.nomor_telepon);
                $('img.foto').attr('src', base_url + 'cdn/img/mahasiswa/' + ((req.data.foto) ? req.data.foto : 'default.png'));
                $('.total-proposal').html(req.data.proposal.length);
            }
        })

    })

    $.ajax({
        url: base_url + '/getDeadline',
        data: {
            mahasiswa_id: <?= $this->session->userdata('id') ?>
        },
        type: 'post',
        dataType: 'json',
        success: function(res) {
            no = 1
            $.each(res, function(i, item) {
                if (item.deadline != '') {
                    now = new Date();
                    _x = new Date(item.deadline);
                    if (now > _x) {
                        $.ajax({
                            url: base_url + 'cekdeadline/' + item.mahasiswa_id,
                            dataType: 'json',
                            type: 'get',
                            success: function(res) {
                                if (res == 'waktu habis') {
                                    alert('Waktu habis dan data skripsi tidak ada, akun anda dinon-verifikasi')
                                    location.reload()
                                }
                                if (res == "aman") {
                                    $("#deadline_" + item.id).html('Waktu habis dan data skripsi ada')
                                }
                            }

                        })
                    }
                }

                bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                _tgl = new Date(item.deadline)
                _hari = _tgl.getDay();
                _bulan = _tgl.getMonth();
                _tahun = _tgl.getFullYear();
                _jam = _tgl.getHours();
                _menit = _tgl.getMinutes();


                _tbody =
                    '<tr>+' +
                    '<td>' + no++ + '</td>' +
                    '<td><b><span id="deadline_' + item.id + '"></span><b></td>' +
                    '<td>' + _hari + ' ' + bulanIndo[_bulan] + ' ' + _tahun + ' Pukul ' + _jam + ':' + _menit + '</td>' +
                    '<td>' + item.judul + '</td>' +
                    '</tr>'
                $("#_tbody").append(_tbody)

                $("#deadline_" + item.id)
                    .countdown(item.deadline, function(event) {
                        $(this).text(
                            event.strftime('Waktu Terisisa %D Hari %H Jam %M Menit %S Detik')
                        );
                    }).on('finish.countdown', function() {
                        $("#deadline_" + item.id).html('Waktu Habis')
                        $.ajax({
                            url: base_url + 'cekdeadline/' + item.mahasiswa_id,
                            dataType: 'json',
                            type: 'get',
                            success: function(res) {
                                if (res == 'waktu habis') {
                                    alert('Waktu habis dan data skripsi tidak ada, akun anda dinon-verifikasi')
                                    location.reload()
                                }
                                if (res == "aman") {
                                    $("#deadline_" + item.id).html('Waktu habis dan data skripsi ada')
                                }
                            }

                        })
                    });
            })
            $(".dataTable").dataTable();
        }
    })
</script>
<?php $this->app->endSection('script') ?>

<?php $this->app->init() ?>