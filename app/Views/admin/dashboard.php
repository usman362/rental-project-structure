<?php
$title = 'Admin Dashboard';
$active = 'dashboard';
ob_start();
?>

<div class="content-header">
    <h1>Dashboard Overview</h1>
    <div class="content-actions">
        <a href="<?= route('admin.renters') ?>" class="btn btn-secondary">
            <i class="fas fa-users"></i> View Renters
        </a>
        <a href="<?= route('admin.applications') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Application
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <!-- Total Renters -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Total Renters</span>
            <div class="stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-value"><?= e((string)$counts['renters']) ?></div>
        <div class="stat-change">+<?= e((string)$counts['renters']) ?> active</div>
    </div>

    <!-- Active Properties -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Active Properties</span>
            <div class="stat-icon green">
                <i class="fas fa-home"></i>
            </div>
        </div>
        <div class="stat-value"><?= e((string)$counts['properties']) ?></div>
        <div class="stat-change">Managed properties</div>
    </div>

    <!-- Pending Applications -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Pending Applications</span>
            <div class="stat-icon orange">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
        <div class="stat-value"><?= e((string)$counts['applications']) ?></div>
        <div class="stat-change">Awaiting review</div>
    </div>

    <!-- Revenue This Month -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Revenue This Month</span>
            <div class="stat-icon red">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <div class="stat-value">$<?= number_format($paymentSummary['total_collected'] ?? 0, 0) ?></div>
        <div class="stat-change"><?= ($paymentSummary['total_collected'] ?? 0) > 0 ? 'Collected' : 'No payments' ?></div>
    </div>

    <!-- Active Maintenance -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Active Maintenance</span>
            <div class="stat-icon purple">
                <i class="fas fa-tools"></i>
            </div>
        </div>
        <div class="stat-value"><?= e((string)$counts['maintenance']) ?></div>
        <div class="stat-change">Open requests</div>
    </div>

    <!-- Occupancy Rate -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Occupancy Rate</span>
            <div class="stat-icon blue">
                <i class="fas fa-percentage"></i>
            </div>
        </div>
        <div class="stat-value"><?= e((string)$occupancyRate) ?>%</div>
        <div class="stat-change"><?= e((string)$propertyBreakdown['occupied']) ?>/<?= e((string)$totalProperties) ?> occupied</div>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid">
    <!-- Monthly Revenue Chart -->
    <div class="chart-card">
        <h3 class="chart-title">Monthly Revenue</h3>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Property Occupancy Chart -->
    <div class="chart-card">
        <h3 class="chart-title">Property Occupancy</h3>
        <div class="chart-container">
            <canvas id="occupancyChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="activity-card">
    <h3 class="chart-title">Recent Applications</h3>
    <?php if (!empty($recentApplications)): ?>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #eaeaea;">
                    <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">ID</th>
                    <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">Applicant</th>
                    <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">Property</th>
                    <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">Status</th>
                    <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentApplications as $app): ?>
                    <tr style="border-bottom: 1px solid #eaeaea;">
                        <td style="padding: 1rem;">#<?= e((string)$app['id']) ?></td>
                        <td style="padding: 1rem;">
                            <strong><?= e($app['first_name'] ?? '') ?> <?= e($app['last_name'] ?? '') ?></strong><br>
                            <small style="color: #666;"><?= e($app['email'] ?? '') ?></small>
                        </td>
                        <td style="padding: 1rem;"><?= e($app['property_name'] ?? 'N/A') ?></td>
                        <td style="padding: 1rem;">
                            <span style="padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 12px; font-weight: 600;
                                <?php
                                    $statusColor = match($app['status'] ?? 'pending') {
                                        'approved' => 'background: #d1fae5; color: #065f46;',
                                        'rejected' => 'background: #fee2e2; color: #7f1d1d;',
                                        'pending' => 'background: #fef3c7; color: #92400e;',
                                        default => 'background: #e5e7eb; color: #374151;'
                                    };
                                    echo $statusColor;
                                ?>">
                                <?= e(ucfirst($app['status'] ?? 'pending')) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; color: #666; font-size: 14px;">
                            <?php
                                if (isset($app['submitted_at'])) {
                                    $date = new DateTime($app['submitted_at']);
                                    echo e($date->format('M d, Y'));
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="padding: 2rem; text-align: center; color: #666;">No recent applications.</p>
    <?php endif; ?>
</div>

<script>
// Revenue data for chart
const revenueLabels = <?= json_encode($revenueData['labels'] ?? []) ?>;
const revenueValues = <?= json_encode($revenueData['values'] ?? []) ?>;

// Property breakdown data
const propertyOccupied = <?= json_encode($propertyBreakdown['occupied']) ?>;
const propertyAvailable = <?= json_encode($propertyBreakdown['available']) ?>;
const propertyMaintenance = <?= json_encode($propertyBreakdown['maintenance']) ?>;

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueLabels.length > 0 ? revenueLabels : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue ($)',
                data: revenueValues.length > 0 ? revenueValues : [0, 0, 0, 0, 0, 0],
                borderColor: '#2c5aa0',
                backgroundColor: 'rgba(44, 90, 160, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });

    // Occupancy Chart
    const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
    new Chart(occupancyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Occupied', 'Available', 'Maintenance'],
            datasets: [{
                data: [propertyOccupied, propertyAvailable, propertyMaintenance],
                backgroundColor: [
                    '#10b981',
                    '#2c5aa0',
                    '#f59e0b'
                ],
                borderWidth: 1,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/admin.php';
?>
