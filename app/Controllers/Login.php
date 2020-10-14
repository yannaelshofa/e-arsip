<?php

namespace App\Controllers;

use App\Models\LoginModel;

class Login extends BaseController
{
  public function __construct()
  {
    helper('form');
    $this->loginModel = new LoginModel();
  }


  public function index()
  {
    $data = array(
      'title' => 'Login',

    );
    return view('v_login', $data);
  }

  public function login()
  {
    if ($this->validate([
      'email' => [
        'label'  => 'E mail',
        'rules'  => 'required',
        'errors' => [
          'required' => '{field} Wajib diisi'
        ]
      ],
      'password' => [
        'label'  => 'Password',
        'rules'  => 'required',
        'errors' => [
          'required' => '{field} Wajib diisi'
        ]
      ]
    ])) {
      // jika valid
      // dd($this->request->getVar());
      $email = $this->request->getVar('email');
      $password = $this->request->getVar('password');
      $cek = $this->loginModel->login($email, $password);
      if ($cek) {
        session()->set('log', true);
        session()->set('namaUser', $cek['namaUser']);
        session()->set('email', $cek['email']);
        session()->set('level', $cek['level']);
        return redirect()->to(base_url('home/index'));
      } else {
        session()->setFlashdata('pesan', 'Login gagal');
        return redirect()->to(base_url('login/index'));
      }
    } else {
      //tidak valid
      session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
      return redirect()->to(base_url('login/index'));
    }
  }
  public function logout()
  {
    session()->destroy();
    return redirect()->to('/login');
  }
}
