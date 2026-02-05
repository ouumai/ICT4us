<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    /**
     * Check if user is logged in and optionally check for role.
     *
     * @param RequestInterface $request
     * @param array|null $arguments Optional roles allowed: ['admin', 'user']
     * @return \CodeIgniter\HTTP\RedirectResponse|null
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $loggedIn = $session->get('logged_in');
        $userRole = $session->get('role');

        if (!$loggedIn) {
            // Redirect to welcome page if not logged in
            return redirect()->to('/')->with('error', 'Sila login untuk akses halaman ini.');
        }

        // Role-based access control
        if ($arguments && is_array($arguments)) {
            if (!in_array($userRole, $arguments)) {
                // Unauthorized access
                if ($userRole === 'admin') {
                    return redirect()->to('/dashboard')->with('error', 'Anda tiada akses ke halaman ini.');
                } else {
                    return redirect()->to('/user')->with('error', 'Anda tiada akses ke halaman ini.');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}
