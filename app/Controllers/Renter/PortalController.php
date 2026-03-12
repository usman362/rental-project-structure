<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Models/Renter.php';
require_once BASE_PATH . '/app/Models/Payment.php';
require_once BASE_PATH . '/app/Models/MaintenanceRequest.php';
require_once BASE_PATH . '/app/Models/Property.php';

class PortalController extends Controller
{
    /**
     * Display the renter portal with dashboard, payments, maintenance, documents, and messages
     */
    public function index(): void
    {
        // Get logged-in user
        $user = auth();

        if (!$user || !isset($user['id'])) {
            $this->redirect(route('login'));
            return;
        }

        $userId = (int) $user['id'];

        // Get renter information
        $renter = Renter::findByUserId($userId);

        if (!$renter) {
            // Renter record not found - should not happen with proper middleware
            $this->view('renter.portal', [
                'title' => 'Renter Portal',
                'active' => 'portal',
                'user' => $user,
                'renter' => null,
                'property' => null,
                'payments' => [],
                'paymentStats' => [
                    'totalPaid' => 0,
                    'pending' => 0,
                    'overdue' => 0,
                    'nextDue' => null,
                    'nextAmount' => 0
                ],
                'maintenanceRequests' => [],
                'recentActivity' => []
            ]);
            return;
        }

        $renterId = (int) $renter['id'];

        // Get payments for this renter
        $payments = Payment::forRenter($renterId);

        // Calculate payment statistics
        $paymentStats = $this->calculatePaymentStats($payments);

        // Get maintenance requests for this renter
        $maintenanceRequests = MaintenanceRequest::forRenter($renterId);

        // Build recent activity feed from payments and maintenance requests
        $recentActivity = $this->buildRecentActivity($payments, $maintenanceRequests);

        // Pass data to view
        $this->view('renter.portal', [
            'title' => 'Renter Portal',
            'active' => 'portal',
            'user' => $user,
            'renter' => $renter,
            'property' => $renter,
            'payments' => $payments,
            'paymentStats' => $paymentStats,
            'maintenanceRequests' => $maintenanceRequests,
            'recentActivity' => $recentActivity
        ]);
    }

    /**
     * Calculate payment statistics for the renter
     */
    private function calculatePaymentStats(array $payments): array
    {
        $totalPaid = 0;
        $pending = 0;
        $overdue = 0;
        $nextDue = null;
        $nextAmount = 0;

        $today = date('Y-m-d');

        foreach ($payments as $payment) {
            $status = $payment['status'] ?? 'pending';
            $dueDate = $payment['due_date'] ?? '';
            $amount = (float) ($payment['amount'] ?? 0);

            if ($status === 'paid') {
                $totalPaid += $amount;
            } elseif ($status === 'pending') {
                if ($dueDate < $today) {
                    $overdue += $amount;
                } else {
                    $pending += $amount;
                }

                // Track next due payment
                if ($nextDue === null || $dueDate < $nextDue) {
                    $nextDue = $dueDate;
                    $nextAmount = $amount;
                }
            }
        }

        return [
            'totalPaid' => $totalPaid,
            'pending' => $pending,
            'overdue' => $overdue,
            'nextDue' => $nextDue,
            'nextAmount' => $nextAmount
        ];
    }

    /**
     * Build recent activity feed combining payments and maintenance requests
     */
    private function buildRecentActivity(array $payments, array $maintenanceRequests): array
    {
        $activity = [];

        // Add recent payments
        foreach (array_slice($payments, 0, 5) as $payment) {
            $activity[] = [
                'type' => 'payment',
                'title' => 'Payment Made',
                'description' => 'Payment of $' . number_format((float)($payment['amount'] ?? 0), 2) . ' recorded',
                'date' => $payment['paid_date'] ?? $payment['due_date'] ?? '',
                'status' => $payment['status'] ?? 'pending',
                'data' => $payment
            ];
        }

        // Add recent maintenance requests
        foreach (array_slice($maintenanceRequests, 0, 5) as $request) {
            $activity[] = [
                'type' => 'maintenance',
                'title' => $request['title'] ?? 'Maintenance Request',
                'description' => $request['description'] ?? 'Maintenance request submitted',
                'date' => $request['created_at'] ?? '',
                'status' => $request['status'] ?? 'open',
                'data' => $request
            ];
        }

        // Sort by date descending
        usort($activity, function($a, $b) {
            $dateA = strtotime($a['date'] ?? '0000-00-00');
            $dateB = strtotime($b['date'] ?? '0000-00-00');
            return $dateB - $dateA;
        });

        return array_slice($activity, 0, 10);
    }
}
