<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';

class HomeController extends Controller
{
    /**
     * Display home page
     */
    public function index(): void
    {
        // Get current user if logged in
        $user = auth();

        $this->view('public.home', [
            'user' => $user
        ]);
    }
}
