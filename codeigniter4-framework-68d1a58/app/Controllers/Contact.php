<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * Contrôleur pour la page de contact
 */
class Contact extends Controller
{
    public function index(): string
    {
        return view('contact');
    }

    public function send()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'name' => 'required|min_length[2]',
            'email' => 'required|valid_email',
            'subject' => 'required|min_length[3]',
            'message' => 'required|min_length[10]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Ici on pourrait envoyer un email, sauvegarder en BDD, etc.
        // Pour l'instant on simule juste l'envoi
        
        return redirect()->to('/contact')->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
    }
}
