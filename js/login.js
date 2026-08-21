// js/login.js
document.addEventListener('DOMContentLoaded', () => {
    // If already logged in, redirect to dashboard
    if (localStorage.getItem('agriflow_auth') === 'true') {
        window.location.href = 'index.html';
    }

    const loginForm = document.getElementById('login-form');
    const errorMessage = document.getElementById('error-message');

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        try {
            const response = await fetch('api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            });

            const data = await response.json();

            if (data.success) {
                // Save auth state
                localStorage.setItem('agriflow_auth', 'true');
                localStorage.setItem('agriflow_username', data.username);

                // Redirect to dashboard
                window.location.href = 'index.html';
            } else {
                errorMessage.textContent = data.message || 'Invalid username or password';
                errorMessage.style.display = 'block';
            }
        } catch (error) {
            console.error('Login error:', error);
            errorMessage.textContent = 'A network error occurred. Please try again.';
            errorMessage.style.display = 'block';
        }
    });
});
