const API_BASE = 'api';

/**
 * Generic fetch wrapper for JSON APIs
 */
async function fetchAPI(endpoint, options = {}) {
    options.headers = {
        'Content-Type': 'application/json',
        ...options.headers
    };
    try {
        const response = await fetch(`${API_BASE}/${endpoint}`, options);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return await response.json();
    } catch (error) {
        console.error("Fetch API Error:", error);
        return null;
    }
}

/**
 * Handle form submission generically
 */
async function handleFormSubmit(event, endpoint, idField, successCallback) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    let method = 'POST';
    if (data[idField]) {
        method = 'PUT'; // If ID exists, it's an update
    }

    const result = await fetchAPI(endpoint, {
        method: method,
        body: JSON.stringify(data)
    });

    if (result) {
        form.reset();
        document.getElementById(idField).value = ''; // Reset ID field
        if (successCallback) successCallback();
    }
}

/**
 * Handle delete generically
 */
async function handleDelete(id, endpoint, successCallback) {
    if (!confirm('Are you sure you want to delete this record?')) return;

    const result = await fetchAPI(endpoint, {
        method: 'DELETE',
        body: JSON.stringify({ id })
    });

    if (result) {
        if (successCallback) successCallback();
    }
}

/**
 * Pre-fill form generic
 */
function prefillForm(formId, data) {
    const form = document.getElementById(formId);
    if (!form) return;

    for (const key in data) {
        if (data.hasOwnProperty(key)) {
            const input = form.elements[key];
            if (input) {
                input.value = data[key];
            }
        }
    }
    // Scroll to form nicely
    form.scrollIntoView({ behavior: 'smooth' });
}

/**
 * Simple search filter for tables
 */
function setupTableSearch(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);

    if (input && table) {
        input.addEventListener('keyup', function () {
            const filter = input.value.toLowerCase();
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) { // Skip header row
                let text = rows[i].textContent || rows[i].innerText;
                if (text.toLowerCase().indexOf(filter) > -1) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        });
    }
}

// Authentication guard and logout handler
document.addEventListener('DOMContentLoaded', () => {
    // Protect pages
    if (!window.location.pathname.endsWith('login.html')) {
        if (localStorage.getItem('agriflow_auth') !== 'true') {
            window.location.href = 'login.html';
            return;
        }
    }

    // Attach logout functionality
    const logoutBtns = document.querySelectorAll('.logout-btn');
    logoutBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            localStorage.removeItem('agriflow_auth');
            localStorage.removeItem('agriflow_username');
            window.location.href = 'login.html';
        });
    });
});
