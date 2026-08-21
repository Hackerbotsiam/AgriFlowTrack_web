// js/storage_conditions.js
const endpoint = 'storage_conditions.php';
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
                <td>${row.facility_name}</td>
                <td>${row.current_temp || '-'}</td>
                <td>${row.humidity || '-'}</td>
                <td>${row.ventilation_status || '-'}</td>
                <td>${row.last_checked || '-'}</td>
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
