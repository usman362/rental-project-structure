<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Models/Property.php';
require_once BASE_PATH . '/app/Models/Application.php';
require_once BASE_PATH . '/app/Models/Renter.php';
require_once BASE_PATH . '/app/Models/Payment.php';
require_once BASE_PATH . '/app/Models/MaintenanceRequest.php';

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with statistics and charts
     */
    public function index(): void
    {
        // Get counts for statistics
        $counts = [
            'renters' => Renter::count(),
            'properties' => Property::count(),
            'applications' => Application::count(['status' => 'pending']),
            'maintenance' => MaintenanceRequest::count(['status' => 'open'])
        ];

        // Get payment summary for this month
        $paymentSummary = Payment::monthlySummary(date('Y-m-d', strtotime('first day of this month')), date('Y-m-d'));

        // Get recent applications (limit 8)
        $recentApplications = Application::all(['limit' => 8, 'order' => 'submitted_at DESC']);

        // Get property status breakdown for chart
        $properties = Property::all();
        $propertyBreakdown = [
            'occupied' => 0,
            'available' => 0,
            'maintenance' => 0
        ];

        foreach ($properties as $property) {
            if ($property['status'] === 'occupied') {
                $propertyBreakdown['occupied']++;
            } elseif ($property['status'] === 'maintenance') {
                $propertyBreakdown['maintenance']++;
            } else {
                $propertyBreakdown['available']++;
            }
        }

        // Calculate occupancy rate
        $totalProperties = count($properties);
        $occupancyRate = $totalProperties > 0 ? (int) (($propertyBreakdown['occupied'] / $totalProperties) * 100) : 0;

        // Get revenue data for the last 6 months for chart
        $revenueData = Payment::lastSixMonthsRevenue();

        // Pass to view
        $this->view('admin.dashboard', [
            'counts' => $counts,
            'paymentSummary' => $paymentSummary,
            'recentApplications' => $recentApplications,
            'propertyBreakdown' => $propertyBreakdown,
            'occupancyRate' => $occupancyRate,
            'totalProperties' => $totalProperties,
            'revenueData' => $revenueData,
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'user' => auth()
        ]);
    }
}
