<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Seminar_model extends CI_Model
{

	protected $table = "seminar";

	public function index($input)
	{
		$this->db->select('
			seminar.id,
			seminar.proposal_mahasiswa_id,
			seminar.tanggal,
			seminar.jam,
			seminar.tempat,
			seminar.file_proposal,
			seminar.bukti_konsultasi,
			seminar.persetujuan,
			seminar.sk_tim,
			proposal_mahasiswa_v.judul as proposal_mahasiswa_judul,
			proposal_mahasiswa_v.nama_mahasiswa,
			proposal_mahasiswa_v.nim,
			proposal_mahasiswa_v.nama_prodi,
			hasil_seminar.status as hasil_seminar_status
		');

		$this->db->from($this->table);
		$this->db->join('hasil_seminar', 'hasil_seminar.seminar_id = seminar.id', 'left');
		$this->db->join('proposal_mahasiswa_v', 'proposal_mahasiswa_v.id = seminar.proposal_mahasiswa_id', 'left');

		if ($input['mahasiswa_id']) {
			$this->db->where('proposal_mahasiswa_v.mahasiswa_id', $input['mahasiswa_id']);
		}

		$seminar = $this->db->get()->result_array();

		$hasil = [
			'error' => false,
			'message' => ($seminar) ? "data berhasil ditemukan" : "data tidak tersedia",
			'data' => $seminar,
		];

		return $hasil;
	}

	public function create($input)
	{
		$data = [
			'proposal_mahasiswa_id' => $input['proposal_mahasiswa_id'],
			'tanggal' => $input['tanggal'],
			'jam' => $input['jam'],
			'tempat' => $input['tempat'],
			'file_proposal' => $input['file_proposal'],
			'sk_tim' => $input['sk_tim'],
			'persetujuan' => $input['persetujuan'],
			'bukti_konsultasi' => $input['bukti_konsultasi'],
		];

		$validation = $this->app->validate($data);

		if ($validation === true) {
			$file_nama = date('Ymdhis') . '.pdf';

			// upload base64 file_proposal
			$file_proposal_file = explode(';base64,', $data['file_proposal'])[1];
			file_put_contents(FCPATH . 'cdn/vendor/file_proposal/' . $file_nama, base64_decode($file_proposal_file));
			$data['file_proposal'] = $file_nama;

			// upload sk_tim
			$sk_tim_file = explode(';base64,', $data['sk_tim'])[1];
			file_put_contents(FCPATH . 'cdn/vendor/sk_tim/' . $file_nama, base64_decode($sk_tim_file));
			$data['sk_tim'] = $file_nama;

			$bukti_konsultasi_file = explode(';base64,', $data['bukti_konsultasi'])[1];
			file_put_contents(FCPATH . 'cdn/vendor/bukti_konsultasi/' . $file_nama, base64_decode($bukti_konsultasi_file));
			$data['bukti_konsultasi'] = $file_nama;

			$persetujuan_file = explode(';base64,', $data['persetujuan'])[1];
			file_put_contents(FCPATH . 'cdn/vendor/persetujuan/' . $file_nama, base64_decode($persetujuan_file));
			$data['persetujuan'] = $file_nama;

			$this->db->insert($this->table, $data);
			$data_id = $this->db->insert_id();
			$this->db->insert("hasil_seminar", [
				'seminar_id' => $data_id,
				'berita_acara' => "",
				'masukan' => "",
				'status' => '3'
			]);

			$hasil = [
				'error' => false,
				'message' => "data berhasil ditambahkan",
				'data_id' => $data_id
			];
		} else {
			$hasil = $validation;
		}

		return $hasil;
	}

	public function details($id)
	{
		$this->db->select('
			seminar.id,
			seminar.proposal_mahasiswa_id,
			seminar.tanggal,
			seminar.jam,
			seminar.tempat,
			seminar.persetujuan,
			seminar.file_proposal,
			seminar.sk_tim,
			seminar.bukti_konsultasi,
			proposal_mahasiswa.judul as proposal_mahasiswa_judul,
			mahasiswa.id as mahasiswa_id,
			mahasiswa.nama as mahasiswa_nama,
			mahasiswa.email
		');

		$this->db->from($this->table);
		$this->db->join('proposal_mahasiswa', 'proposal_mahasiswa.id = seminar.proposal_mahasiswa_id', 'left');
		$this->db->join('mahasiswa', 'mahasiswa.id = proposal_mahasiswa.mahasiswa_id', 'left');
		$this->db->where('seminar.id', $id);

		$seminar = $this->db->get()->row_array();

		$hasil = [
			'error' => ($seminar) ? false : true,
			'message' => ($seminar) ? "data berhasil ditemukan" : "data tidak tersedia",
			'data' => $seminar
		];

		if ($hasil['data']) {
			$hasil['data']['hasil'] = $this->db->get_where('hasil_seminar', ['seminar_id' => $hasil['data']['id']])->row_array();
		}

		return $hasil;
	}

	public function destroy($id)
	{
		$kondisi = [
			'id' => $id
		];

		$seminar = $this->db->get_where($this->table, $kondisi)->row_array();

		if ($seminar) {
			$hasil_seminar = $this->db->get_where('hasil_seminar', ['seminar_id' => $id])->result_array();
			foreach ($hasil_seminar as $key => $item) {
				if ($item['berita_acara']) {
					unlink(FCPATH . 'cdn/vendor/berita_acara/' . $item['berita_acara']);
				}
				if ($item['masukan']) {
					unlink(FCPATH . 'cdn/vendor/masukan/' . $item['masukan']);
				}
			}
			unlink(FCPATH . 'cdn/vendor/file_proposal/' . $seminar['file_proposal']);
			unlink(FCPATH . 'cdn/vendor/sk_tim/' . $seminar['sk_tim']);
			$this->db->delete("hasil_seminar", ['seminar_id' => $id]);
			$this->db->delete($this->table, $kondisi);
			$hasil = [
				'error' => false,
				'message' => "data berhasil dihapus"
			];
		} else {
			$hasil = [
				'error' => true,
				'message' => "data tidak ditemukan"
			];
		}

		return $hasil;
	}
}

/* End of file Seminar_model.php */
/* Location: ./application/models/Seminar_model.php */