<?php $this->app->extend('template/dosen') ?>

<?php $this->app->setVar('title', 'Dosen') ?>

<?php $this->app->section() ?>
<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-md-9">
				<div class="row p-2">
					<div class="col-6">NIP</div>
					<div class="col-6"><strong class="nip">NIP</strong></div>
				</div>
				<div class="row p-2">
					<div class="col-6">Nama</div>
					<div class="col-6"><strong class="nama">Nama Dosen</strong></div>
				</div>
				<div class="row p-2">
					<div class="col-6">Email</div>
					<div class="col-6"><strong class="email">Email Dosen</strong></div>
				</div>
				<div class="row p-2 mb-5">
					<div class="col-6">Nomor Telepon</div>
					<div class="col-6"><strong class="nomor_telepon">Nomor Telepon Dosen</strong></div>
				</div>
				<div>
					<a href="<?= base_url() ?>dosen/profil" class="btn btn-primary">
						<i class="fa fa-edit"></i> Edit Data
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
<script>
	base_url = '<?= base_url(); ?>'
	$.ajax({
		url: base_url + 'api/dosen/get_byid',
		type: 'post',
		dataType: 'json',
		data: {
			id: <?= $this->session->userdata('id'); ?>
		},
		success: function(res) {
			$.each(res.data, function(i, item) {
				$(".nip").html(item.nip)
				$(".nama").html(item.nama)
				$(".email").html(item.email)
				$(".nomor_telepon").html(item.nomor_telepon)
			})
		}
	})
</script>
<?php $this->app->endSection('script') ?>

<?php $this->app->init() ?>