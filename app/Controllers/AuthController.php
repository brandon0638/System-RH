<?php

namespace App\Controllers;

use App\Models\EmployeModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('user')) {
            $role = session()->get('user')['role'];
            if ($role === 'admin') return redirect()->to('/admin/dashboard');
            if ($role === 'rh') return redirect()->to('/rh/demandes');
            return redirect()->to('/employe/dashboard');
        }
        
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $model = new EmployeModel();
        $user = $model->where('email', $email)->first();
        
        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Email ou mot de passe incorrect.');
        }
        
        session()->set('user', [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'email' => $user['email'],
            'role' => $user['role'],
            'departement_id' => $user['departement_id']
        ]);
        
        if ($user['role'] === 'admin') {
            return redirect()->to('/admin/dashboard')->with('success', 'Bienvenue Administrateur.');
        } elseif ($user['role'] === 'rh') {
            return redirect()->to('/rh/demandes')->with('success', 'Bienvenue Responsable RH.');
        } else {
            return redirect()->to('/employe/dashboard')->with('success', 'Bienvenue ' . $user['nom']);
        }
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Déconnecté avec succès.');
    }
}