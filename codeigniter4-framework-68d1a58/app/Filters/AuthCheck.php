<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;

/**
 * Filtre s'exécutant à chaques pages, permettant de vérifier que l'utilisateur est bel et bien connecté à son compte sur cette session.
 */
class AuthCheck implements FilterInterface
{
    /**
     * Fonction permettant de vérifier que l'utilisateur est connecté à son compte, s'exécute avant le chargement d'une page (non-exclue)
     *
     * @param \CodeIgniter\HTTP\RequestInterface $request
     * @param $arguments
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\RequestInterface|\CodeIgniter\HTTP\ResponseInterface|string|null
     */
    public function before(\CodeIgniter\HTTP\RequestInterface $request, $arguments = null)
    {
        $auth = service('auth');

        if (! $auth->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = $auth->user();
        $request->user = $user;

        return $request;
    }

    public function after(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, $arguments = null)
    {
        // TODO: Implement after() method.
    }
}