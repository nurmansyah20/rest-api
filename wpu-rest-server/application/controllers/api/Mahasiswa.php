<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Mahasiswa extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mahasiswa_model', 'mahasiswa');

        //limit key
        $this->methods['index_get']['limit'] = 5; //limit 5x akses dalam 1 jam
    }
    public function index_get()
    {
        $id = $this->get('id');
        if ($id === null)
        {
            $mahasiswa = $this->mahasiswa->getMahasiswa();
        }
        else
        {
         $mahasiswa = $this->mahasiswa->getMahasiswa($id);   
        }   
        //var_dump($mahasiswa); ( kenapa ga bisa pake var_dump )
        //echo var_export($mahasiswa);

        if ($mahasiswa)
        {
            $this->response([
                'status' => true,
                'data' => $mahasiswa,
            ], REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND); 
        }
        
    }

    public function index_delete()
    {
        $id = $this->delete('id');

        if ($id === null)
        {
            $this->response([
                'status' => false,
                'message' => 'Silahkan masukan ID'
            ], REST_Controller::HTTP_BAD_REQUEST); 
        }
        else{
            if ($this->mahasiswa->deleteMahasiswa($id) > 0){
                //berhasil
                $this->response([
                    'status' => true,
                    'id' => $id,
                    'message' => 'Data berhasil dihapus'
                ], REST_Controller::HTTP_OK);
            }
            else{
                //gagal
                $this->response([
                    'status' => false,
                    'message' => 'Data gagal dihapus'
                ], REST_Controller::HTTP_BAD_REQUEST); 
            }
        } 
    }

    public function index_post()
    {
        $data = [
            'nrp' => $this->post('nrp'),
            'nama' => $this->post('nama'),
            'email' => $this->post('email'),
            'jurusan' => $this->post('jurusan')
        ];

        if ($this->mahasiswa->createMahasiswa($data) > 0){
            //berhasil
            $this->response([
                'status' => true,
                'message' => 'Data berhasil ditambahkan'
            ], REST_Controller::HTTP_CREATED);
        }
        else{
             //gagal
             $this->response([
                'status' => false,
                'message' => 'Gagal menambahkan data'
            ], REST_Controller::HTTP_BAD_REQUEST); 
        }
    }

    public function index_put()
    {
        $id = $this->put('id');
        $data = [
            'nrp' => $this->put('nrp'),
            'nama' => $this->put('nama'),
            'email' => $this->put('email'),
            'jurusan' => $this->put('jurusan')
        ];

        if ($this->mahasiswa->updateMahasiswa($data, $id) > 0){
            //berhasil
            $this->response([
                'status' => true,
                'message' => 'Data berhasil diubah'
            ], REST_Controller::HTTP_OK);
        }
        else{
             //gagal
             $this->response([
                'status' => false,
                'message' => 'Gagal mengubah data'
            ], REST_Controller::HTTP_BAD_REQUEST); 
        }
    }

}
