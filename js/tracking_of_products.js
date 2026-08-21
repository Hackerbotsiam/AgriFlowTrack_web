// js/tracking_of_products.js
const endpoint = 'tracking_of_products.php';
let currentData = [];

async function loadData() {
    const data = await fetchAPI(endpoint);
    if (!data) return;

    currentData = data;
    const tbody = document.getElementById('data-table');
    tbody.innerHTML = '';

    if (data.length > 0) {
        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.id}</td>
                <td>${row.product_name}</td>
                <td>${row.current_location || '-'}</td>
                <td>${row.destination || '-'}</td>
                <td>${row.transit_status || '-'}</td>
                <td>${row.dispatch_date || '-'}</td>
                <td class="action-buttons">
                    <button class="btn btn-sm btn-secondary" onclick="editRecord(${row.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteRecord(${row.id})">Delete</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } else {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">No records found</td></tr>';
    }
}

function editRecord(id) {
    const record = currentData.find(r => r.id == id);
    if (record) {
        prefillForm('data-form', record);
    }
}

function deleteRecord(id) {
    handleDelete(id, endpoint, loadData);
}

document.addEventListener('DOMContentLoaded', () => {
    loadData();
    setupTableSearch('search-input', 'data-table-container');

    document.getElementById('data-form').addEventListener('submit', function (e) {
        handleFormSubmit(e, endpoint, 'id', loadData);
    });
});
