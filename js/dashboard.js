// js/dashboard.js

async function loadDashboardData() {
    const data = await fetchAPI('dashboard.php');
    if (!data) return;

    // Update counts
    document.getElementById('products-count').textContent = data.productsCount || 0;
    document.getElementById('agri-inputs-count').textContent = data.agriInputsCount || 0;
    document.getElementById('perishables-count').textContent = data.perishablesCount || 0;
    document.getElementById('post-harvest-count').textContent = data.postHarvestCount || 0;

    // Update table
    const tbody = document.getElementById('market-data-table');
    tbody.innerHTML = '';

    if (data.marketData && data.marketData.length > 0) {
        data.marketData.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.market}</td>
                <td>${row.product}</td>
                <td>${row.price_per_unit}</td>
                <td>${row.date}</td>
            `;
            tbody.appendChild(tr);
        });
    } else {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">No records found</td></tr>';
    }
}

// Initial load
document.addEventListener('DOMContentLoaded', loadDashboardData);
