<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Core/CSRF.php';
require_once BASE_PATH . '/app/Models/Notification.php';

class NotificationController extends Controller
{
    /**
     * Mark all notifications as read
     */
    public function markAllRead(): void
    {
        if (!CSRF::verify()) {
            flash('error', 'Invalid security token.');
            $this->redirect(route('renter.portal'));
            return;
        }

        $user = auth();
        if (!$user || !isset($user['id'])) {
            flash('error', 'Unauthorized');
            $this->redirect(route('login'));
            return;
        }

        Notification::markAllRead((int) $user['id']);

        flash('success', 'All notifications marked as read.');
        $this->back();
    }

    /**
     * Mark a single notification as read (AJAX or redirect)
     */
    public function markRead(): void
    {
        $user = auth();
        if (!$user || !isset($user['id'])) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $notifId = (int) ($_POST['notification_id'] ?? $_GET['id'] ?? 0);
        if ($notifId <= 0) {
            flash('error', 'Invalid notification.');
            $this->back();
            return;
        }

        $notification = Notification::find($notifId);
        if (!$notification || (int)($notification['user_id'] ?? 0) !== (int)$user['id']) {
            flash('error', 'Notification not found.');
            $this->back();
            return;
        }

        Notification::markRead($notifId);

        // If there's a link, redirect to it
        $link = $notification['link'] ?? '';
        if (!empty($link)) {
            $this->redirect($link);
        } else {
            $this->back();
        }
    }
}
