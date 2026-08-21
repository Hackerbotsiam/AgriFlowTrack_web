// js/agri_inputs.js
const endpoint = 'agri_inputs.php';
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
                <td>${row.item}</td>
                <td>${row.quantity}</td>
                <td>${row.unit}</td>
                <td>${row.date_received || '-'}</td>
                <td>${row.input_type || '-'}</td>
                <td>${row.name}</td>
                <td>${row.stock_level || '-'}</td>
                <td>${row.usage_rate_per_week || '-'}</td>
                <td>${row.procurement_date || '-'}</td>
                <td class="action-buttons">
                    <button class="btn btn-sm btn-secondary" onclick="editRecord(${row.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteRecord(${row.id})">Delete</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } else {
        tbody.innerHTML = '<tr><td colspan="11" style="text-align: center;">No records found</td></tr>';
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
