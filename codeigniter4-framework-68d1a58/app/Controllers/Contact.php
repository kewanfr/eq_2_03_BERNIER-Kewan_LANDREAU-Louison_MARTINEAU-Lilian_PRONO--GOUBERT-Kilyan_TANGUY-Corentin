<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * Contrôleur pour la page de contact
 */
class Contact extends Controller
{
    /**
     * Affiche la page de contact
     * @return string
     */
    public function index(): string
    {
        // Affiche la vue contact (formulaire + infos)
        return view('contact');
    }

    /**
     * Traite l'envoi du formulaire de contact
     */
    public function send()
    {
        // Récupère le service de validation de CodeIgniter
        $validation = \Config\Services::validation();
        
        // Définit les règles de validation pour chaque champ du formulaire
        $validation->setRules([
            'name' => 'required|min_length[2]', // nom obligatoire, min 2 caractères
            'email' => 'required|valid_email',   // email obligatoire et valide
            'subject' => 'required|min_length[3]', // sujet obligatoire, min 3 caractères
            'message' => 'required|min_length[10]' // message obligatoire, min 10 caractères
        ]);

        // Si la validation échoue, on retourne à la page précédente avec les erreurs et les anciennes valeurs
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Ici, on pourrait envoyer un email ou sauvegarder le message en base de données
        // Pour l'instant, on simule juste l'envoi et on affiche un message de succès
        return redirect()->to('/contact')->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
    }
}
