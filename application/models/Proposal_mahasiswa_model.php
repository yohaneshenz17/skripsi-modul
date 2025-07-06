<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Proposal_mahasiswa_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Email_model', 'emailm');
    }

    protected $table = "proposal_mahasiswa";

    public function get($input)
    {
        $kondisi = [];
        if ($input['dosen_id']) {
            $kondisi['proposal_mahasiswa.dosen_id'] = $input['dosen_id'];
        }

        if ($input['status']) {
            $kondisi['status'] = $input['status'];
        }

        if ($input['mahasiswa_id']) {
            $kondisi['mahasiswa_id'] = $input['mahasiswa_id'];
        }

        $this->db->select("*");
        if ($kondisi) {
            $this->db->where($kondisi);
        }
        $proposal_mahasiswa = $this->db->get($this->table)->result_array();

        $hasil['error'] = false;
        $hasil['message'] = ($proposal_mahasiswa) ? "data berhasil ditemukan" : "data tidak tersedia";
        $hasil['data'] = $proposal_mahasiswa;

        foreach ($proposal_mahasiswa as $key => $item) {
            $hasil['data'][$key]['mahasiswa'] = $this->db->get_where('mahasiswa_v', ['mahasiswa_v.id' => $item['mahasiswa_id']])->row_array();
            $hasil['data'][$key]['pembimbing'] = $this->db->get_where('dosen', ['dosen.id' => $item['dosen_id']])->row_array();
            $hasil['data'][$key]['pembimbing2'] = $this->db->get_where('dosen', ['dosen.id' => $item['dosen2_id']])->row_array();
        }

        return $hasil;
    }

    public function create($input)
    {
        $data = [
            'mahasiswa_id' => $input['mahasiswa_id'],
            'judul' => $input['judul'],
            'ringkasan' => $input['ringkasan'],
            'dosen_id' => $input['dosen_id'],
            'dosen2_id' => $input['dosen2_id'],
            'dosen_penguji_id' => $input['dosen_penguji_id']
        ];

        $validate = $this->app->validate($data);

        if ($validate === true) {
            $this->db->insert($this->table, $data);
            $data_id = $this->db->insert_id();
            $hasil = [
                'error' => false,
                'message' => "data berhasil ditambah",
                'data_id' => $data_id
            ];
        } else {
            $hasil = $validate;
        }

        return $hasil;
    }

    public function update($input, $id)
    {
        $data = [
            'mahasiswa_id' => $input['mahasiswa_id'],
            'judul' => $input['judul'],
            'ringkasan' => $input['ringkasan'],
            'dosen_id' => $input['dosen_id'],
            'dosen2_id' => $input['dosen2_id'],
            'dosen_penguji_id' => $input['dosen_penguji_id']
        ];

        $kondisi = ['proposal_mahasiswa.id' => $id];
        $cek = $this->db->get_where($this->table, $kondisi)->num_rows();

        if ($cek > 0) {
            $validate = $this->app->validate($data);

            if ($validate === true) {
                $this->db->update($this->table, $data, $kondisi);
                $hasil = [
                    'error' => false,
                    'message' => "data berhasil diedit"
                ];
            } else {
                $hasil = $validate;
            }
        } else {
            $hasil = [
                'error' => true,
                'message' => "data tidak ditemukan"
            ];
        }

        return $hasil;
    }

    public function destroy($id)
    {
        $kondisi = ['proposal_mahasiswa.id' => $id];
        $cek = $this->db->get_where($this->table, $kondisi)->num_rows();

        if ($cek > 0) {
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

    public function agree($id, $deadline)
    {
        $kondisi = ['proposal_mahasiswa.id' => $id];
        $cek = $this->db->get_where($this->table, $kondisi);

        if ($cek > 00) {
            $dataUpdate = array(
                'status' => '1',
                'deadline' => $deadline
            );

            $email = '';
            $dProposal = $this->db->get_where('proposal_mahasiswa_v', array('id' => $id))->result();
            foreach ($dProposal as $dp) {
                $email = $dp->email;
            }

            if ($this->db->update($this->table, $dataUpdate, $kondisi)) {
                $isi_email = '
                    <p>Usulan proposal anda telah disetujui, silahkan lanjut ke tahap berikutnya.</p>
                    ';
                $this->emailm->send('Usulan Proposal Disetujui', $email, $isi_email);

                $hasil = [
                    'error' => false,
                    'message' => "proposal berhasil disetujui",
                ];
            }
        } else {
            $hasil = [
                'error' => true,
                'message' => "data tidak ditemukan"
            ];
        }

        return $hasil;
    }

    public function disagree($id)
    {
        $kondisi = ['proposal_mahasiswa.id' => $id];
        $cek = $this->db->get_where($this->table, $kondisi);

        if ($cek > 00) {

            $email = '';
            $dProposal = $this->db->get_where('proposal_mahasiswa_v', array('id' => $id))->result();
            foreach ($dProposal as $dp) {
                $email = $dp->email;
            }

            if ($this->db->update($this->table, ['status' => "0", 'deadline' => null], $kondisi)) {

                $isi_email = '
                    <p>Usulan proposal anda tidak disetujui, silahkan membenarkan usulan proposal anda.</p>
                    ';
                $this->emailm->send('Usulan Proposal Tidak Disetujui', $email, $isi_email);

                $hasil = [
                    'error' => false,
                    'message' => "proposal batal disetujui"
                ];
            }
        } else {
            $hasil = [
                'error' => true,
                'message' => "data tidak ditemukan"
            ];
        }

        return $hasil;
    }
}

/* End of file Proposal_mahasiswa_model.php */
