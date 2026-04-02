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

// ========== FILTERS ==========
function applyReportFilters() {
    const dateRange = document.getElementById('reportDateRange').value;
    const property = document.getElementById('reportProperty').value;
    const reportType = document.getElementById('reportType').value;

    // Build URL with query params and reload page
    const params = new URLSearchParams();
    params.set('date_range', dateRange);
    if (property !== 'all') params.set('property', property);
    if (reportType !== 'summary') params.set('report_type', reportType);

    if (dateRange === 'custom') {
        const from = document.getElementById('dateFrom').value;
        const to = document.getElementById('dateTo').value;
        if (from) params.set('date_from', from);
        if (to) params.set('date_to', to);
    }

    Swal.fire({
        title: 'Applying Filters...',
        html: 'Refreshing report data',
        timer: 1200,
        timerProgressBar: true,
        didOpen: () => Swal.showLoading(),
        allowOutsideClick: false
    }).then(() => {
        window.location.href = '<?= route("admin.reports") ?>?' + params.toString();
    });
}

function resetReportFilters() {
    document.getElementById('reportDateRange').value = 'this-month';
    document.getElementById('reportProperty').value = 'all';
    document.getElementById('reportType').value = 'summary';
    document.getElementById('customDateRange').style.display = 'none';

    Swal.fire({
        icon: 'success',
        title: 'Filters Reset',
        text: 'All filters have been reset to defaults.',
        confirmButtonColor: '#2c5aa0',
        timer: 1500,
        timerProgressBar: true
    }).then(() => {
        window.location.href = '<?= route("admin.reports") ?>';
    });
}

// ========== QUICK REPORT CARDS ==========
function viewFinancialReport() {
    Swal.fire({
        icon: 'info',
        title: 'Financial Report',
        text: 'Scrolling to the detailed financial summary below.',
        confirmButtonColor: '#2c5aa0',
        timer: 1500,
        timerProgressBar: true
    });
    document.getElementById('reportPreview').scrollIntoView({ behavior: 'smooth' });
}

function viewOccupancyReport() {
    Swal.fire({
        icon: 'info',
        title: 'Occupancy Report',
        text: 'Scrolling to occupancy chart.',
        confirmButtonColor: '#2c5aa0',
        timer: 1500,
        timerProgressBar: true
    });
    document.getElementById('occupancyChart').scrollIntoView({ behavior: 'smooth' });
}

function viewMaintenanceReport() {
    Swal.fire({
        icon: 'info',
        title: 'Maintenance Report',
        text: 'Scrolling to maintenance costs breakdown.',
        confirmButtonColor: '#2c5aa0',
        timer: 1500,
        timerProgressBar: true
    });
    document.getElementById('maintenanceCostChart').scrollIntoView({ behavior: 'smooth' });
}

function viewRenterReport() {
    Swal.fire({
        icon: 'info',
        title: 'Renter Report',
        html: `<p><strong>Active Renters:</strong> <?= count($revenueByProperty) ?></p>
               <p><strong>Collection Rate:</strong> <?= e((string)$kpiData['payment_collection']) ?>%</p>
               <p><strong>Satisfaction:</strong> <?= number_format($kpiData['renter_satisfaction'], 1) ?>/5</p>`,
        confirmButtonColor: '#2c5aa0'
    });
}

// ========== REPORT DATA FOR EXPORTS ==========
const reportTableData = <?= json_encode($revenueByProperty ?? []) ?>;
const reportKPI = <?= json_encode($kpiData ?? []) ?>;

// ========== EXPORT FUNCTIONS ==========
function buildReportCSV() {
    const headers = ['Property', 'Monthly Rent', 'Collected', 'Expenses', 'Net Profit', 'Profit Margin', 'Occupancy'];
    const rows = reportTableData.map(item => {
        const netProfit = parseFloat(item.collected || 0) - parseFloat(item.expenses || 0);
        const margin = item.collected > 0 ? ((netProfit / item.collected) * 100).toFixed(1) + '%' : '0%';
        return [
            item.property_name || '',
            parseFloat(item.monthly_rent || 0).toFixed(2),
            parseFloat(item.collected || 0).toFixed(2),
            parseFloat(item.expenses || 0).toFixed(2),
            netProfit.toFixed(2),
            margin,
            item.occupancy || '0%'
        ];
    });

    // Totals row
    const totalRent = reportTableData.reduce((s, i) => s + parseFloat(i.monthly_rent || 0), 0);
    const totalCollected = reportTableData.reduce((s, i) => s + parseFloat(i.collected || 0), 0);
    const totalExpenses = reportTableData.reduce((s, i) => s + parseFloat(i.expenses || 0), 0);
    rows.push(['TOTAL', totalRent.toFixed(2), totalCollected.toFixed(2), totalExpenses.toFixed(2), (totalCollected - totalExpenses).toFixed(2), '', '']);

    // KPI section
    rows.unshift([]);
    rows.unshift(['KPI', 'Value']);
    rows.unshift(['Occupancy Rate', reportKPI.occupancy_rate + '%']);
    rows.unshift(['Monthly Revenue', '$' + parseFloat(reportKPI.monthly_revenue || 0).toFixed(2)]);
    rows.unshift(['Payment Collection', reportKPI.payment_collection + '%']);
    rows.unshift(['Renter Satisfaction', parseFloat(reportKPI.renter_satisfaction || 0).toFixed(1) + '/5']);
    rows.unshift([]);
    rows.unshift(['Sotelo Management LLC - Financial Report', new Date().toLocaleDateString()]);

    return [[''], ...rows.map(r => r)].map(row =>
        (Array.isArray(row) ? row : [row]).map(field => {
            const str = String(field);
            if (str.includes(',') || str.includes('"') || str.includes('\n')) {
                return '"' + str.replace(/"/g, '""') + '"';
            }
            return str;
        }).join(',')
    ).join('\n');
}

function downloadFile(content, filename, mimeType) {
    const blob = new Blob(['\uFEFF' + content], { type: mimeType });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}

function exportReport(format) {
    if (format === 'csv') {
        const csv = buildReportCSV();
        downloadFile(csv, 'financial_report_' + new Date().toISOString().slice(0, 10) + '.csv', 'text/csv;charset=utf-8;');
        Swal.fire({ icon: 'success', title: 'CSV Downloaded!', text: 'Financial report exported as CSV.', confirmButtonColor: '#2c5aa0', timer: 2500, timerProgressBar: true });
    } else if (format === 'excel') {
        // Generate a tab-separated file that Excel opens natively
        const csv = buildReportCSV().replace(/,/g, '\t');
        downloadFile(csv, 'financial_report_' + new Date().toISOString().slice(0, 10) + '.xls', 'application/vnd.ms-excel;charset=utf-8;');
        Swal.fire({ icon: 'success', title: 'Excel Downloaded!', text: 'Financial report exported as Excel.', confirmButtonColor: '#2c5aa0', timer: 2500, timerProgressBar: true });
    } else if (format === 'pdf') {
        // Open a print-friendly version of the report preview
        printReportPreview();
    }
}

function printReportPreview() {
    const preview = document.getElementById('reportPreview');
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html><head><title>Sotelo Management - Financial Report</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 2rem; color: #333; }
            h3 { color: #2c5aa0; }
            table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
            th, td { padding: 0.75rem; border: 1px solid #ddd; text-align: left; }
            th { background: #f8fafc; font-weight: 600; }
            .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; text-align: center; margin: 1rem 0; }
            .kpi-box { padding: 1rem; background: #f8fafc; border-radius: 8px; }
            .kpi-val { font-size: 1.5rem; font-weight: 700; color: #2c5aa0; }
            .kpi-lbl { font-size: 12px; color: #666; }
            @media print { body { padding: 0; } }
        </style></head><body>
        <h2 style="color:#2c5aa0;">SOTELO MANAGEMENT LLC</h2>
        <div style="text-align:right;margin-top:-2rem;"><strong>MONTHLY FINANCIAL REPORT</strong><br>${new Date().toLocaleDateString()}</div>
        <hr style="border-color:#2c5aa0;">
        <h3>Key Performance Indicators</h3>
        <div class="kpi-grid">
            <div class="kpi-box"><div class="kpi-val">${reportKPI.occupancy_rate}%</div><div class="kpi-lbl">Occupancy Rate</div></div>
            <div class="kpi-box"><div class="kpi-val">$${parseFloat(reportKPI.monthly_revenue||0).toLocaleString()}</div><div class="kpi-lbl">Monthly Revenue</div></div>
            <div class="kpi-box"><div class="kpi-val">${reportKPI.payment_collection}%</div><div class="kpi-lbl">Collection Rate</div></div>
            <div class="kpi-box"><div class="kpi-val">${parseFloat(reportKPI.renter_satisfaction||0).toFixed(1)}/5</div><div class="kpi-lbl">Satisfaction</div></div>
        </div>
        <h3>Revenue Breakdown</h3>
        <table>
            <thead><tr><th>Property</th><th style="text-align:right">Monthly Rent</th><th style="text-align:right">Collected</th><th style="text-align:right">Expenses</th><th style="text-align:right">Net Profit</th><th style="text-align:center">Occupancy</th></tr></thead>
            <tbody>
            ${reportTableData.map(i => {
                const net = parseFloat(i.collected||0) - parseFloat(i.expenses||0);
                return '<tr><td>'+i.property_name+'</td><td style="text-align:right">$'+parseFloat(i.monthly_rent||0).toLocaleString()+'</td><td style="text-align:right;color:#10b981">$'+parseFloat(i.collected||0).toLocaleString()+'</td><td style="text-align:right;color:#ef4444">$'+parseFloat(i.expenses||0).toLocaleString()+'</td><td style="text-align:right;font-weight:600">$'+net.toLocaleString()+'</td><td style="text-align:center">'+i.occupancy+'</td></tr>';
            }).join('')}
            </tbody>
        </table>
        <p style="color:#999;font-size:12px;margin-top:2rem;">Generated by Sotelo Management LLC on ${new Date().toLocaleString()}</p>
        </body></html>
    `);
    printWindow.document.close();
    setTimeout(() => { printWindow.print(); }, 500);
}

function printReport() {
    printReportPreview();
}

// ========== SCHEDULE REPORT ==========
function scheduleReport() {
    Swal.fire({
        title: 'Schedule New Report',
        html: `
            <div style="text-align:left;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-weight:600; margin-bottom:0.3rem; font-size:14px;">Report Type</label>
                    <select id="swal_report_type" class="swal2-select" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;">
                        <option value="financial">Financial Report</option>
                        <option value="occupancy">Occupancy Report</option>
                        <option value="maintenance">Maintenance Report</option>
                        <option value="renter">Renter Report</option>
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-weight:600; margin-bottom:0.3rem; font-size:14px;">Recipient Email</label>
                    <input type="email" id="swal_email" class="swal2-input" placeholder="email@example.com" style="width:100%; margin:0; padding:8px; border:1px solid #ddd; border-radius:6px;">
                </div>
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:0.3rem; font-size:14px;">Frequency</label>
                    <select id="swal_frequency" class="swal2-select" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly" selected>Monthly</option>
                        <option value="quarterly">Quarterly</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-calendar-check"></i> Schedule',
        confirmButtonColor: '#2c5aa0',
        cancelButtonColor: '#6c757d',
        preConfirm: () => {
            const email = document.getElementById('swal_email').value;
            if (!email) {
                Swal.showValidationMessage('Please enter a recipient email');
                return false;
            }
            return {
                type: document.getElementById('swal_report_type').value,
                email: email,
                frequency: document.getElementById('swal_frequency').value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const d = result.value;
            // Add to the scheduled reports section dynamically
            const container = document.querySelector('.scheduled-reports');
            const newItem = document.createElement('div');
            newItem.className = 'schedule-item';
            newItem.innerHTML = `
                <div class="schedule-info">
                    <h4>${d.type.charAt(0).toUpperCase() + d.type.slice(1)} Report</h4>
                    <div class="schedule-details">
                        Sent to: ${d.email} • Frequency: ${d.frequency.charAt(0).toUpperCase() + d.frequency.slice(1)}
                    </div>
                </div>
                <div>
                    <button class="btn-small" onclick="this.closest('.schedule-item').remove(); Swal.fire({icon:'success',title:'Removed',timer:1000,timerProgressBar:true,confirmButtonColor:'#2c5aa0'})">Delete</button>
                </div>
            `;
            container.appendChild(newItem);

            Swal.fire({
                icon: 'success',
                title: 'Report Scheduled!',
                html: `<strong>${d.type.charAt(0).toUpperCase() + d.type.slice(1)} Report</strong> will be sent <strong>${d.frequency}</strong> to <strong>${d.email}</strong>`,
                confirmButtonColor: '#2c5aa0'
            });
        }
    });
}

function generateCustomReport() {
    Swal.fire({
        title: 'Custom Report Builder',
        html: `
            <div style="text-align:left;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-weight:600; margin-bottom:0.3rem; font-size:14px;">Report Name</label>
                    <input type="text" id="swal_custom_name" class="swal2-input" placeholder="My Custom Report" style="width:100%; margin:0; padding:8px; border:1px solid #ddd; border-radius:6px;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-weight:600; margin-bottom:0.3rem; font-size:14px;">Include Sections</label>
                    <div style="display:flex; flex-direction:column; gap:0.5rem; padding:0.5rem;">
                        <label><input type="checkbox" checked> Financial Summary</label>
                        <label><input type="checkbox" checked> Revenue by Property</label>
                        <label><input type="checkbox"> Maintenance Breakdown</label>
                        <label><input type="checkbox"> Renter Details</label>
                        <label><input type="checkbox" checked> KPI Overview</label>
                    </div>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-chart-bar"></i> Generate',
        confirmButtonColor: '#2c5aa0',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Generating Report...',
                didOpen: () => Swal.showLoading(),
                timer: 2000,
                timerProgressBar: true,
                allowOutsideClick: false
            }).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Report Generated!',
                    text: 'Your custom report is ready. Scroll down to view the preview.',
                    confirmButtonColor: '#2c5aa0'
                });
                document.getElementById('reportPreview').scrollIntoView({ behavior: 'smooth' });
            });
        }
    });
}

// ========== SCHEDULED REPORTS EDIT/DELETE ==========
function editSchedule(id) {
    const item = event.target.closest('.schedule-item');
    const title = item.querySelector('h4').textContent;
    const details = item.querySelector('.schedule-details').textContent;

    Swal.fire({
        title: 'Edit Scheduled Report',
        html: `
            <div style="text-align:left;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-weight:600; margin-bottom:0.3rem; font-size:14px;">Report Name</label>
                    <input type="text" id="swal_edit_name" value="${title}" class="swal2-input" style="width:100%; margin:0; padding:8px; border:1px solid #ddd; border-radius:6px;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-weight:600; margin-bottom:0.3rem; font-size:14px;">Frequency</label>
                    <select id="swal_edit_freq" class="swal2-select" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;">
                        <option value="Daily">Daily</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Quarterly">Quarterly</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-save"></i> Save Changes',
        confirmButtonColor: '#2c5aa0',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            const newName = document.getElementById('swal_edit_name').value;
            const newFreq = document.getElementById('swal_edit_freq').value;
            item.querySelector('h4').textContent = newName;
            item.querySelector('.schedule-details').textContent =
                item.querySelector('.schedule-details').textContent.replace(/Frequency: \w+/, 'Frequency: ' + newFreq);

            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Scheduled report has been updated.',
                confirmButtonColor: '#2c5aa0',
                timer: 1500,
                timerProgressBar: true
            });
        }
    });
}

function deleteSchedule(id) {
    const item = event.target.closest('.schedule-item');

    Swal.fire({
        title: 'Delete Scheduled Report?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash"></i> Delete',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            item.style.transition = 'opacity 0.3s, transform 0.3s';
            item.style.opacity = '0';
            item.style.transform = 'translateX(20px)';
            setTimeout(() => item.remove(), 300);

            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: 'Scheduled report has been removed.',
                confirmButtonColor: '#2c5aa0',
                timer: 1500,
                timerProgressBar: true
            });
        }
    });
}
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/admin.php';
?>
