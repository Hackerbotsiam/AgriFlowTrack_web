// js/perishable_products.js
const endpoint = 'perishable_products.php';
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
                <td>${row.name}</td>
                <td>${row.batch_number || '-'}</td>
                <td>${row.storage_temp || '-'}</td>
                <td>${row.shelf_life_days || '-'}</td>
                <td>${row.status || '-'}</td>
                <td>${row.added_date || '-'}</td>
                <td class="action-buttons">
                    <button class="btn btn-sm btn-secondary" onclick="editRecord(${row.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteRecord(${row.id})">Delete</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } else {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align: center;">No records found</td></tr>';
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
