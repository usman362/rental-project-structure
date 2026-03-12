<?php
$title = 'Reports & Analytics';
$active = 'reports';
ob_start();
?>

<div class="content-header">
    <h1>Reports & Analytics</h1>
    <div class="content-actions">
        <button class="btn btn-secondary" onclick="scheduleReport()">
            <i class="fas fa-calendar-plus"></i> Schedule Report
        </button>
        <button class="btn btn-primary" onclick="generateCustomReport()">
            <i class="fas fa-plus"></i> Custom Report
        </button>
    </div>
</div>

<!-- Export Options -->
<div class="export-options">
    <button class="export-btn" onclick="exportReport('pdf')">
        <i class="fas fa-file-pdf"></i> Export as PDF
    </button>
    <button class="export-btn" onclick="exportReport('excel')">
        <i class="fas fa-file-excel"></i> Export as Excel
    </button>
    <button class="export-btn" onclick="exportReport('csv')">
        <i class="fas fa-file-csv"></i> Export as CSV
    </button>
    <button class="export-btn" onclick="printReport()">
        <i class="fas fa-print"></i> Print Report
    </button>
</div>

<!-- Quick Reports -->
<div class="reports-grid">
    <div class="report-card" onclick="viewFinancialReport()">
        <div class="report-icon blue">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <h3 class="report-title">Financial Report</h3>
        <p class="report-description">Revenue, expenses, profit margins, and financial performance analysis.</p>
        <div style="color: #2c5aa0; font-size: 14px; font-weight: 500;">
            <i class="fas fa-arrow-right"></i> View Report
        </div>
    </div>

    <div class="report-card" onclick="viewOccupancyReport()">
        <div class="report-icon green">
            <i class="fas fa-home"></i>
        </div>
        <h3 class="report-title">Occupancy Report</h3>
        <p class="report-description">Property occupancy rates, vacancy analysis, and turnover statistics.</p>
        <div style="color: #2c5aa0; font-size: 14px; font-weight: 500;">
            <i class="fas fa-arrow-right"></i> View Report
        </div>
    </div>

    <div class="report-card" onclick="viewMaintenanceReport()">
        <div class="report-icon orange">
            <i class="fas fa-tools"></i>
        </div>
        <h3 class="report-title">Maintenance Report</h3>
        <p class="report-description">Maintenance costs, response times, vendor performance, and issue trends.</p>
        <div style="color: #2c5aa0; font-size: 14px; font-weight: 500;">
            <i class="fas fa-arrow-right"></i> View Report
        </div>
    </div>

    <div class="report-card" onclick="viewRenterReport()">
        <div class="report-icon purple">
            <i class="fas fa-users"></i>
        </div>
        <h3 class="report-title">Renter Report</h3>
        <p class="report-description">Renter demographics, retention rates, payment history, and satisfaction.</p>
        <div style="color: #2c5aa0; font-size: 14px; font-weight: 500;">
            <i class="fas fa-arrow-right"></i> View Report
        </div>
    </div>
</div>

<!-- Report Filters -->
<div class="report-filters">
    <h3 style="color: #2c5aa0; margin-bottom: 1rem;">Report Filters</h3>
    <div class="filter-group-row">
        <div class="filter-group">
            <label>Date Range</label>
            <select id="reportDateRange">
                <option value="this-month">This Month</option>
                <option value="last-month">Last Month</option>
                <option value="this-quarter">This Quarter</option>
                <option value="last-quarter">Last Quarter</option>
                <option value="this-year">This Year</option>
                <option value="custom">Custom Range</option>
            </select>
        </div>
        <div class="filter-group" id="customDateRange" style="display: none;">
            <label>Custom Range</label>
            <div style="display: flex; gap: 0.5rem;">
                <input type="date" id="dateFrom" style="flex: 1;">
                <input type="date" id="dateTo" style="flex: 1;">
            </div>
        </div>
        <div class="filter-group">
            <label>Property</label>
            <select id="reportProperty">
                <option value="all">All Properties</option>
                <?php foreach ($properties as $property): ?>
                    <option value="<?= e((string)$property['id']) ?>"><?= e($property['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label>Report Type</label>
            <select id="reportType">
                <option value="summary">Summary Report</option>
                <option value="detailed">Detailed Report</option>
                <option value="comparison">Comparison Report</option>
            </select>
        </div>
    </div>
    <button class="btn btn-primary" onclick="applyReportFilters()">
        <i class="fas fa-filter"></i> Apply Filters
    </button>
    <button class="btn" onclick="resetReportFilters()" style="margin-left: 1rem;">
        Reset
    </button>
</div>

<!-- KPI Cards -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-value"><?= e((string)$kpiData['occupancy_rate']) ?>%</div>
        <div class="kpi-label">Occupancy Rate</div>
        <div class="kpi-change">+2% from last month</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-value">$<?= number_format((int)$kpiData['monthly_revenue'], 0) ?></div>
        <div class="kpi-label">Monthly Revenue</div>
        <div class="kpi-change">+12% from last month</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-value"><?= e((string)$kpiData['payment_collection']) ?>%</div>
        <div class="kpi-label">Payment Collection</div>
        <div class="kpi-change">+1% from last month</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-value"><?= number_format($kpiData['renter_satisfaction'], 1) ?>/5</div>
        <div class="kpi-label">Renter Satisfaction</div>
        <div class="kpi-change">+0.3 from last quarter</div>
    </div>
</div>

<!-- Revenue Trend Chart -->
<div class="chart-section">
    <h3 class="chart-title">Monthly Revenue Trends</h3>
    <div class="chart-container">
        <canvas id="revenueTrendChart"></canvas>
    </div>
</div>

<!-- Occupancy Chart -->
<div class="chart-section">
    <h3 class="chart-title">Property Occupancy</h3>
    <div class="chart-container">
        <canvas id="occupancyChart"></canvas>
    </div>
</div>

<!-- Maintenance Cost Chart -->
<div class="chart-section">
    <h3 class="chart-title">Maintenance Costs by Category</h3>
    <div class="chart-container">
        <canvas id="maintenanceCostChart"></canvas>
    </div>
</div>

<!-- Financial Summary Table -->
<div class="report-table">
    <h3 style="color: #2c5aa0; margin-bottom: 1rem;">Detailed Financial Summary</h3>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8fafc;">
                <th style="padding: 1rem; text-align: left;">Property</th>
                <th style="padding: 1rem; text-align: right;">Monthly Rent</th>
                <th style="padding: 1rem; text-align: right;">Collected</th>
                <th style="padding: 1rem; text-align: right;">Expenses</th>
                <th style="padding: 1rem; text-align: right;">Net Profit</th>
                <th style="padding: 1rem; text-align: center;">Occupancy</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalRent = 0;
            $totalCollected = 0;
            $totalExpenses = 0;
            ?>
            <?php foreach ($revenueByProperty as $item): ?>
                <?php
                $totalRent += $item['monthly_rent'];
                $totalCollected += $item['collected'];
                $totalExpenses += $item['expenses'];
                $netProfit = $item['collected'] - $item['expenses'];
                $profitMargin = $item['collected'] > 0 ? ((int)(($netProfit / $item['collected']) * 100)) : 0;
                ?>
                <tr style="border-bottom: 1px solid #eaeaea;">
                    <td style="padding: 1rem;"><?= e($item['property_name']) ?></td>
                    <td style="padding: 1rem; text-align: right;">$<?= number_format((int)$item['monthly_rent'], 0) ?></td>
                    <td style="padding: 1rem; text-align: right; color: #10b981;">$<?= number_format((int)$item['collected'], 0) ?></td>
                    <td style="padding: 1rem; text-align: right; color: #ef4444;">$<?= number_format((int)$item['expenses'], 0) ?></td>
                    <td style="padding: 1rem; text-align: right; font-weight: 600;">
                        $<?= number_format((int)$netProfit, 0) ?> (<?= e((string)$profitMargin) ?>%)
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        <span style="padding: 0.25rem 0.75rem; background: #d1fae5; color: #065f46; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <?= e($item['occupancy']) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr style="font-weight: 600; background: #f8fafc; border-top: 2px solid #eaeaea;">
                <td style="padding: 1rem;">TOTAL</td>
                <td style="padding: 1rem; text-align: right;">$<?= number_format((int)$totalRent, 0) ?></td>
                <td style="padding: 1rem; text-align: right;">$<?= number_format((int)$totalCollected, 0) ?></td>
                <td style="padding: 1rem; text-align: right;">$<?= number_format((int)$totalExpenses, 0) ?></td>
                <td style="padding: 1rem; text-align: right;">
                    $<?= number_format((int)($totalCollected - $totalExpenses), 0) ?>
                </td>
                <td style="padding: 1rem; text-align: center;">-</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Scheduled Reports -->
<div class="scheduled-reports">
    <h3 style="color: #2c5aa0; margin-bottom: 1rem;">Scheduled Reports</h3>
    <div class="schedule-item">
        <div class="schedule-info">
            <h4>Monthly Financial Report</h4>
            <div class="schedule-details">
                Sent to: admin@sotelomanage.com • Next: Feb 1, 2026 • Frequency: Monthly
            </div>
        </div>
        <div>
            <button class="btn-small" onclick="editSchedule(1)">Edit</button>
            <button class="btn-small" style="margin-left: 0.5rem;" onclick="deleteSchedule(1)">Delete</button>
        </div>
    </div>
    <div class="schedule-item">
        <div class="schedule-info">
            <h4>Weekly Occupancy Report</h4>
            <div class="schedule-details">
                Sent to: team@sotelomanage.com • Next: Mar 17, 2026 • Frequency: Weekly
            </div>
        </div>
        <div>
            <button class="btn-small" onclick="editSchedule(2)">Edit</button>
            <button class="btn-small" style="margin-left: 0.5rem;" onclick="deleteSchedule(2)">Delete</button>
        </div>
    </div>
</div>

<!-- Report Preview -->
<div class="report-preview" id="reportPreview">
    <div class="preview-header">
        <div class="company-logo">SOTELO MANAGEMENT LLC</div>
        <div class="report-date">
            <div style="font-weight: 600;">MONTHLY FINANCIAL REPORT</div>
            <div><?= e(date('M d') . ' - ' . date('M t, Y')) ?></div>
            <div>Generated: <?= e(date('M d, Y')) ?></div>
        </div>
    </div>

    <div style="margin-bottom: 2rem;">
        <h3 style="color: #2c5aa0; margin-bottom: 1rem;">Executive Summary</h3>
        <p style="color: #666; line-height: 1.6;">
            This report summarizes the financial performance for <?= e(date('F Y')) ?>.
            Total revenue reached $<?= number_format((int)$totalRevenue, 0) ?> with a collection rate of <?= e((string)$kpiData['payment_collection']) ?>%.
            Property occupancy remains strong at <?= e((string)$kpiData['occupancy_rate']) ?>% across all managed properties.
            Maintenance costs totaled $<?= number_format((int)$totalMaintenanceCost, 0) ?>, and renter satisfaction scores remain excellent.
        </p>
    </div>

    <div style="margin-bottom: 2rem;">
        <h3 style="color: #2c5aa0; margin-bottom: 1rem;">Key Performance Indicators</h3>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; text-align: center;">
            <div style="padding: 1rem; background: #f8fafc; border-radius: 8px;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #2c5aa0;"><?= e((string)$kpiData['occupancy_rate']) ?>%</div>
                <div style="font-size: 12px; color: #666;">Occupancy Rate</div>
            </div>
            <div style="padding: 1rem; background: #f8fafc; border-radius: 8px;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #2c5aa0;">$<?= number_format((int)$kpiData['monthly_revenue'], 0) ?></div>
                <div style="font-size: 12px; color: #666;">Monthly Revenue</div>
            </div>
            <div style="padding: 1rem; background: #f8fafc; border-radius: 8px;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #2c5aa0;"><?= e((string)$kpiData['payment_collection']) ?>%</div>
                <div style="font-size: 12px; color: #666;">Collection Rate</div>
            </div>
            <div style="padding: 1rem; background: #f8fafc; border-radius: 8px;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #2c5aa0;"><?= number_format($kpiData['renter_satisfaction'], 1) ?>/5</div>
                <div style="font-size: 12px; color: #666;">Renter Satisfaction</div>
            </div>
        </div>
    </div>
</div>

<script>
// Chart data from PHP
const revenueLabels = <?= json_encode($chartData['revenue_trend']['labels'] ?? []) ?>;
const revenueValues = <?= json_encode($chartData['revenue_trend']['values'] ?? []) ?>;

const occupancyLabels = <?= json_encode($chartData['occupancy']['labels'] ?? []) ?>;
const occupancyValues = <?= json_encode($chartData['occupancy']['data'] ?? []) ?>;

const maintenanceLabels = <?= json_encode($chartData['maintenance_costs']['labels'] ?? []) ?>;
const maintenanceValues = <?= json_encode($chartData['maintenance_costs']['data'] ?? []) ?>;

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();

    // Date range toggle
    document.getElementById('reportDateRange').addEventListener('change', function() {
        const customRange = document.getElementById('customDateRange');
        if (this.value === 'custom') {
            customRange.style.display = 'flex';
        } else {
            customRange.style.display = 'none';
        }
    });
});

function initializeCharts() {
    // Revenue Trend Chart
    const revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueLabels.length > 0 ? revenueLabels : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Monthly Revenue',
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
                    beginAtZero: false,
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
        type: 'bar',
        data: {
            labels: occupancyLabels.length > 0 ? occupancyLabels : ['Property 1', 'Property 2', 'Property 3'],
            datasets: [{
                label: 'Occupancy Rate (%)',
                data: occupancyValues.length > 0 ? occupancyValues : [0, 0, 0],
                backgroundColor: [
                    '#2c5aa0',
                    '#3a6bc5',
                    '#4a7bcf',
                    '#5a8bd9',
                    '#6a9be3'
                ],
                borderWidth: 0
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
                    max: 100,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
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

    // Maintenance Cost Chart
    const maintenanceCtx = document.getElementById('maintenanceCostChart').getContext('2d');
    new Chart(maintenanceCtx, {
        type: 'doughnut',
        data: {
            labels: maintenanceLabels,
            datasets: [{
                data: maintenanceValues,
                backgroundColor: [
                    '#2c5aa0',
                    '#10b981',
                    '#f59e0b',
                    '#8b5cf6',
                    '#ef4444',
                    '#6b7280'
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
                    position: 'right'
                }
            }
        }
    });
}

function applyReportFilters() {
    const dateRange = document.getElementById('reportDateRange').value;
    const property = document.getElementById('reportProperty').value;
    const reportType = document.getElementById('reportType').value;

    let message = 'Applying filters:\n';
    message += `• Date Range: ${dateRange}\n`;
    message += `• Property: ${property}\n`;
    message += `• Report Type: ${reportType}\n\n`;
    message += 'In a real application, this would filter the report data.';

    alert(message);
}

function resetReportFilters() {
    document.getElementById('reportDateRange').value = 'this-month';
    document.getElementById('reportProperty').value = 'all';
    document.getElementById('reportType').value = 'summary';
    document.getElementById('customDateRange').style.display = 'none';

    alert('Filters reset to default values.');
}

function viewFinancialReport() {
    alert('Loading Financial Report...\n\nThis would open a detailed financial report in a real application.');
    document.getElementById('reportPreview').scrollIntoView({ behavior: 'smooth' });
}

function viewOccupancyReport() {
    alert('Loading Occupancy Report...\n\nThis would open a detailed occupancy report in a real application.');
}

function viewMaintenanceReport() {
    alert('Loading Maintenance Report...\n\nThis would open a detailed maintenance report in a real application.');
}

function viewRenterReport() {
    alert('Loading Renter Report...\n\nThis would open a detailed renter report in a real application.');
}

function exportReport(format) {
    const formats = {
        'pdf': 'PDF',
        'excel': 'Excel',
        'csv': 'CSV'
    };

    alert(`Exporting report as ${formats[format]}...\n\nIn a real application, this would generate and download a ${formats[format]} file.`);
}

function printReport() {
    alert('Printing report...\n\nIn a real application, this would open the print dialog with a formatted report.');
}

function scheduleReport() {
    const reportType = prompt('Enter report type to schedule:');
    const email = prompt('Enter recipient email:');
    const frequency = prompt('Enter frequency (daily, weekly, monthly):');

    if (reportType && email && frequency) {
        alert(`Report scheduled!\n\n• Type: ${reportType}\n• Recipient: ${email}\n• Frequency: ${frequency}\n\nReport will be sent automatically.`);
    }
}

function generateCustomReport() {
    alert('Opening custom report generator...\n\nThis would open a form to create custom reports in a real application.');
}

function editSchedule(id) {
    alert(`Editing scheduled report #${id}...`);
}

function deleteSchedule(id) {
    if (confirm('Are you sure you want to delete this scheduled report?')) {
        alert(`Scheduled report #${id} deleted.`);
    }
}
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/admin.php';
?>
