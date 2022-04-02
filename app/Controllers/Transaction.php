<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\BarangTransaksiModel;
use CodeIgniter\Validation\Rules;
use CodeIgniter\I18n\Time;

class Transaction extends BaseController
{
    protected $transaksiModel;
    protected $barangTransaksiModel;
    public function __construct()
    {
        $this->transaksiModel = new TransactionModel();
        $this->barangTransaksiModel = new BarangTransaksiModel();
    }

    public function laporan()
    {
        $myTime = Time::now('Asia/Jakarta');
        $date = $myTime->toLocalizedString('d MMM yyyy');
        $data = [
            'date' => $date,
            'transaksi' => $this->transaksiModel->getTransaksi()
        ];

        return view('admin/transaction/laporan', $data);
    }

    public function index()
    {
        // $transaksi = $this->transaksiModel->findAll();
        $currentPage = $this->request->getVar('page_transaksi') ? $this->request->getVar('page_transaksi') : 1;
        // d($this->request->getVar('keyword'));
        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $transaksi = $this->transaksiModel->search($keyword);
        } else {
            $transaksi = $this->transaksiModel;
        }


        $data = [
            'title' => "Admin | Transaki",
            // 'transaksi' => $this->transaksiModel->getTransaksi(),
            'transaksi' => $transaksi->paginate(10, 'transaksi'),
            'pager' => $this->transaksiModel->pager,
            'currentPage' => $currentPage
        ];

        // connect secara manual
        // $db = \Config\Database::connect();
        // $transaksi = $db->query("SELECT * FROM transaksi");
        // dd($transaksi);

        return view('admin/transaction/index', $data);
    }

    public function create()
    {
        // session();
        $data = [
            'title' => "Admin | Create Transaksi",
            'validation' => \Config\Services::validation(),
            'transaksi' => $this->transaksiModel->getTransaksi()
        ];
        return view('admin/transaction/create', $data);
    }

    public function save()
    {
        //validation
        if (!$this->validate([
            'nama_penerima' => [
                'rules' => 'required|is_unique[transaksi.nama_penerima]',
                'errors' => [
                    'required' => 'Kolom ini harus diisi!',
                    'is_unique' => 'nama barang sudah terdaftar'
                ]
            ],
            'username' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kolom ini harus diisi!'
                ]
            ],
            'nama_warung' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kolom ini harus diisi!'
                ]
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kolom ini harus diisi!',
                ]
            ],
            'no_hp' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kolom ini harus diisi!',
                ]
            ],
            'email' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kolom ini harus diisi!',
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // return redirect()->to('/barang/create')->withInput()->with('validation', $validation);
            return redirect()->to('admin/transaction/create')->withInput();
        }

        //ambil gambar
        // $fileGambar = $this->request->getFile('foto_barang');

        //generate nama file random
        // $namaGambar = $fileGambar->getRandomName(); //lalu parameter diselepesanan_selesaian ke function move

        //pindahkan file ke folder img
        // $fileGambar->move('img');
        //ambil nama file
        // $namaGambar = $fileGambar->getName();

        // $slug = url_title($this->request->getVar('nama_barang'), '-', true);
        // $faker = \Faker\Factory::create('id_ID');
        $this->transaksiModel->save([
            'username' => $this->request->getVar('username'),
            'kode_transaksi' => 'wr-' . random_int(1, 1000000),
            'nama_penerima' => $this->request->getVar('nama_penerima'),
            'nama_warung' => $this->request->getVar('nama_warung'),
            'alamat' => $this->request->getVar('alamat'),
            'no_hp' => $this->request->getVar('no_hp'),
            'email' => $this->request->getVar('email')
        ]);
        session()->setFlashData('pesan', 'Data berhasil ditambahkan');
        return redirect()->to('admin/transaction');
    }

    public function delete($kode_transaksi)
    {
        // cari gambar berdasarkan kode_transa$kode_transaksi
        // $barang = $this->barangModel->find($kode_transaksi);

        // hapus gambar
        // unlink('img/' . $barang['foto_barang']);

        $this->transaksiModel->delete($kode_transaksi);
        session()->setFlashData('pesan', 'Data berhasil dihapus');
        return redirect()->to('admin/transaction');
    }

    public function edit($kode_transaksi)
    {
        $data = [
            'title' => "Admin | Edit Transaksi",
            'validation' => \Config\Services::validation(),
            'transaksi' => $this->transaksiModel->getTransaksi($kode_transaksi)
        ];
        return view('admin/transaction/edit', $data);
    }

    public function update($kode_transaksi)
    {
        // dd($this->request->getVar());
        //cek nama_barang
        $penerimaLama = $this->transaksiModel->getTransaksi($this->request->getVar('kode_transaksi'));
        if ($penerimaLama['nama_penerima'] == $this->request->getVar('nama_penerima')) {
            $rule_namapenerima = 'required';
        } else {
            $rule_namapenerima = 'required|is_unique[transaksi.nama_penerima]';
        }

        if (!$this->validate([
            'nama_penerima' => [
                'rules' => $rule_namapenerima,
                'errors' => [
                    'required' => 'Kolom ini harus diisi!',
                    'is_unique' => 'nama penerima sudah terdaftar'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            return redirect()->to('/transaction/edit/' . $this->request->getVar('kode_transaksi'))->withInput();
        }

        // $fileGambar = $this->request->getFile('foto_barang');
        // //cek gambar apakah tetap gambar lama
        // if ($fileGambar->getError() == 4) {
        //     $namaGambar = $this->request->getVar('gambarLama');
        // } else {
        //     //pindahkan file ke folder img
        //     $fileGambar->move('img');
        //     //ambil nama file
        //     $namaGambar = $fileGambar->getName();
        //     //hapus file lama
        //     unlink('img/' . $this->request->getVar('gambarLama'));
        // }

        // $slug = url_title($this->request->getVar('nama_barang'), '-', true);
        $this->transaksiModel->save([
            'kode_transaksi' => $kode_transaksi,
            'username' => $this->request->getVar('username'),
            'nama_penerima' => $this->request->getVar('nama_penerima'),
            'nama_warung' => $this->request->getVar('nama_warung'),
            'alamat' => $this->request->getVar('alamat'),
            'no_hp' => $this->request->getVar('no_hp'),
            'email' => $this->request->getVar('email')
        ]);
        session()->setFlashData('pesan', 'Data berhasil diubah');
        return redirect()->to('admin/transaction');
    }

    public function pesanan_masuk()
    {
        $data = [
            'title' => "Admin | Pesanan Masuk",
            'transaksi' => $this->transaksiModel->pesanan_masuk()
        ];
        return view('admin/transaction/pesanan_masuk/index', $data);
    }

    public function update_pending($kode_transaksi)
    {
        $this->transaksiModel->save([
            'kode_transaksi' => $kode_transaksi,
            'status' => 'Proses'
        ]);
        session()->setFlashData('pesan', 'Pesanan berhasil diproses');
        return redirect()->to('admin/transaction/pesanan_masuk');
    }

    public function pesanan_proses()
    {
        $data = [
            'title' => "Admin | Pesanan Proses",
            'transaksi' => $this->transaksiModel->pesanan_proses()
        ];
        return view('admin/transaction/pesanan_proses/index', $data);
    }

    public function update_proses($kode_transaksi)
    {
        $this->transaksiModel->save([
            'kode_transaksi' => $kode_transaksi,
            'status' => 'Dikirim'
        ]);
        session()->setFlashData('pesan', 'Pesanan berhasil diproses');
        return redirect()->to('admin/transaction/pesanan_proses');
    }

    public function pesanan_dikirim()
    {
        $data = [
            'title' => "Admin | Pesanan Dikirim",
            'transaksi' => $this->transaksiModel->pesanan_dikirim()
        ];
        return view('admin/transaction/pesanan_dikirim/index', $data);
    }

    public function update_kirim($kode_transaksi)
    {
        $this->transaksiModel->save([
            'kode_transaksi' => $kode_transaksi,
            'status' => 'Diterima'
        ]);
        session()->setFlashData('pesan', 'Pesanan berhasil diterima');
        return redirect()->to('admin/transaction/pesanan_dikirim');
    }

    public function update_kirimkurir($kode_transaksi)
    {
        $this->transaksiModel->save([
            'kode_transaksi' => $kode_transaksi,
            'status' => 'Diterima'
        ]);
        session()->setFlashData('pesan', 'Pesanan berhasil diterima');
        return redirect()->to('Warungin/tes_tailwind');
    }

    public function pesanan_diterima()
    {
        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $transaksi = $this->transaksiModel->search($keyword);
        } else {
            $transaksi = $this->transaksiModel;
        }

        $data = [
            'title' => "Admin | Pesanan Diterima",
            'transaksi' => $this->transaksiModel->pesanan_diterima()
        ];
        return view('admin/transaction/pesanan_diterima/index', $data);
    }

    public function update_terima($kode_transaksi)
    {
        $myTime = Time::now('Asia/Jakarta');
        $date = $myTime->toLocalizedString('d MMM yyyy');
        $this->transaksiModel->save([
            'kode_transaksi' => $kode_transaksi,
            'status' => 'Selesai',
            'tgl_pembayaran' => $date
        ]);
        session()->setFlashData('pesan', 'Pesanan berhasil diterima');
        return redirect()->to('admin/transaction/pesanan_diterima');
    }

    public function pesanan_selesai()
    {
        $keyword = $this->request->getVar('search');
        if (isset($keyword)) {
            $search = $this->request->getVar('keyword');
            session()->set('keyword', $search);
            redirect()->to('admin/transaction/pesanan_selesai/index');
        } else {
            $search = session()->get('search');
        }

        $transaksi = $search ? $this->transaksiModel->search($keyword) : $this->transaksiModel;
        $data = [
            'title' => "Admin | Pesanan Selesai",
            'transaksi' => $transaksi->pesanan_selesai(),
            'search' => $search
        ];
        return view('admin/transaction/pesanan_selesai/index', $data);
    }
}
