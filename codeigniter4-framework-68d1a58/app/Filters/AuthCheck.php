<?php

use CodeIgniter\Filters\FilterInterface;

class AuthCheck implements FilterInterface
{
    public function before(\CodeIgniter\HTTP\RequestInterface $request, $arguments = null)
    {
        $auth = service('auth');

        if (! $auth->check()) {
            return redirect()->to('/login');
        }

        $user = $auth->user();
        $request->user = $user;
    }

    public function after(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, $arguments = null)
    {
        // TODO: Implement after() method.
    }
}