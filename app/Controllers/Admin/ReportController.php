<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Models/Property.php';
require_once BASE_PATH . '/app/Models/Payment.php';
require_once BASE_PATH . '/app/Models/MaintenanceRequest.php';
require_once BASE_PATH . '/app/Models/Renter.php';

class ReportController extends Controller
{
    /**
     * Display reports and analytics dashboard
     */
    public function index(): void
    {
        // Get all properties for filter dropdown
        $properties = Property::all();

        // Get payment summary for this month
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        $paymentSummary = Payment::all(['date_from' => $monthStart, 'date_to' => $monthEnd]);

        // Calculate financial metrics
        $totalRevenue = 0;
        $totalCollected = 0;
        $totalExpenses = 0;
        foreach ($paymentSummary as $payment) {
            if ($payment['status'] === 'paid') {
                $totalCollected += (float) $payment['amount'];
            }
        }

        // Get property stats
        $propertyStats = Property::countByStatus();

        // Calculate occupancy rate
        $totalProperties = Property::count();
        $occupiedProperties = $propertyStats['occupied'] ?? 0;
        $occupancyRate = $totalProperties > 0 ? (int) (($occupiedProperties / $totalProperties) * 100) : 0;

        // Get maintenance statistics
        $maintenanceRequests = MaintenanceRequest::all();
        $maintenanceStats = MaintenanceRequest::countByStatus();
        $totalMaintenanceCost = 0;
        foreach ($maintenanceRequests as $request) {
            if (!empty($request['actual_cost'])) {
                $totalMaintenanceCost += (float) $request['actual_cost'];
            }
        }

        // Get payment collection metrics
        $allPayments = Payment::all();
        $paidPayments = array_filter($allPayments, fn($p) => $p['status'] === 'paid');
        $paymentCollectionRate = count($allPayments) > 0 ? (int) ((count($paidPayments) / count($allPayments)) * 100) : 0;

        // Build revenue data by property
        $revenueByProperty = [];
        foreach ($properties as $property) {
            $propertyPayments = array_filter($allPayments, fn($p) => $p['property_id'] == $property['id'] && $p['status'] === 'paid');
            $propertyRevenue = array_sum(array_column($propertyPayments, 'amount'));

            $revenueByProperty[] = [
                'property_id' => $property['id'],
                'property_name' => $property['name'],
                'address' => $property['address'],
                'monthly_rent' => (float) $property['monthly_rent'],
                'collected' => $propertyRevenue,
                'expenses' => 500, // placeholder
                'occupancy' => $propertyStats['occupied'] > 0 ? '100%' : '0%'
            ];
        }

        // Generate revenue trend data (last 6 months)
        $revenueData = $this->generateRevenueData();

        // Build KPI cards data
        $kpiData = [
            'occupancy_rate' => $occupancyRate,
            'monthly_revenue' => $totalCollected,
            'payment_collection' => $paymentCollectionRate,
            'renter_satisfaction' => 4.2 // placeholder
        ];

        // Build chart data
        $chartData = [
            'revenue_trend' => $revenueData,
            'occupancy' => [
                'labels' => array_column($properties, 'name'),
                'data' => array_fill(0, count($properties), $occupancyRate)
            ],
            'maintenance_costs' => [
                'labels' => ['Plumbing', 'Electrical', 'HVAC', 'Appliances', 'Structural', 'Pest Control'],
                'data' => [1200, 850, 2100, 650, 300, 400]
            ]
        ];

        // Pass to view
        $this->view('admin.reports', [
            'properties' => $properties,
            'paymentSummary' => $paymentSummary,
            'revenueByProperty' => $revenueByProperty,
            'propertyStats' => $propertyStats,
            'occupancyRate' => $occupancyRate,
            'maintenanceStats' => $maintenanceStats,
            'totalMaintenanceCost' => $totalMaintenanceCost,
            'kpiData' => $kpiData,
            'chartData' => $chartData,
            'totalRevenue' => $totalCollected,
            'title' => 'Reports & Analytics',
            'active' => 'reports',
            'user' => auth()
        ]);
    }

    /**
     * Generate revenue data for the last 6 months
     */
    private function generateRevenueData(): array
    {
        $labels = [];
        $values = [];

        // Generate last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m-01', strtotime("-$i months"));
            $labels[] = date('M', strtotime($date));

            // In a real app, fetch actual data from Payment model
            $values[] = rand(35000, 52000);
        }

        return [
            'labels' => $labels,
            'values' => $values
        ];
    }
}
