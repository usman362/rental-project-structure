<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';

class SetupController extends Controller
{
    public function initDb(): void
    {
        // Require the database initialization file
        require_once BASE_PATH . '/database/init.php';
    }
}
