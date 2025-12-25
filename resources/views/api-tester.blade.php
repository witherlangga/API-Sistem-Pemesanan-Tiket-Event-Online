<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Tester - Sistem Pemesanan Tiket Event</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .header h1 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
        }
        
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .card h2 {
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .response {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .response pre {
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-size: 12px;
        }
        
        .token-display {
            background: #fff3cd;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
            word-wrap: break-word;
            font-size: 12px;
        }
        
        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
        }
        
        .error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
        }
        
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .tab {
            padding: 10px 20px;
            background: #e0e0e0;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }
        
        .tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎫 API Tester - Sistem Pemesanan Tiket Event</h1>
            <p>Test API Authentication & User Management</p>
        </div>
        
        <div class="main-content">
            <!-- Left Column - Forms -->
            <div class="card">
                <h2>Test API Endpoints</h2>
                
                <div class="tabs">
                    <button class="tab active" onclick="showTab('register')">Register</button>
                    <button class="tab" onclick="showTab('login')">Login</button>
                    <button class="tab" onclick="showTab('profile')">Profile</button>
                </div>
                
                <!-- Register Form -->
                <div id="register" class="tab-content active">
                    <form id="registerForm">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" placeholder="Nama lengkap" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="email@example.com" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="Min. 8 karakter" required>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="text" name="phone" placeholder="081234567890">
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" required>
                                <option value="customer">Customer</option>
                                <option value="organizer">Organizer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bio</label>
                            <textarea name="bio" rows="3" placeholder="Bio singkat (opsional)"></textarea>
                        </div>
                        <button type="submit">Register</button>
                    </form>
                </div>
                
                <!-- Login Form -->
                <div id="login" class="tab-content">
                    <form id="loginForm">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="email@example.com" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit">Login</button>
                    </form>
                </div>
                
                <!-- Profile Form -->
                <div id="profile" class="tab-content">
                    <div class="form-group">
                        <button onclick="getProfile()">Get My Profile</button>
                    </div>
                    <div class="form-group">
                        <button onclick="logout()" style="background: #dc3545;">Logout</button>
                    </div>
                    
                    <hr style="margin: 20px 0;">
                    
                    <h3 style="margin-bottom: 15px;">Update Profile</h3>
                    <form id="updateProfileForm">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" placeholder="Nama baru">
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="text" name="phone" placeholder="081234567890">
                        </div>
                        <div class="form-group">
                            <label>Bio</label>
                            <textarea name="bio" rows="3" placeholder="Bio singkat"></textarea>
                        </div>
                        <button type="submit">Update Profile</button>
                    </form>
                </div>
            </div>
            
            <!-- Right Column - Response -->
            <div class="card">
                <h2>Response</h2>
                <div id="tokenDisplay" class="token-display" style="display: none;">
                    <strong>Token:</strong>
                    <div id="tokenValue" style="margin-top: 5px;"></div>
                </div>
                <div id="response" class="response">
                    <pre>Response akan muncul di sini...</pre>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const API_URL = '/api';
        let authToken = localStorage.getItem('auth_token') || '';
        
        // Display token if exists
        if (authToken) {
            displayToken(authToken);
        }
        
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }
        
        function displayToken(token) {
            document.getElementById('tokenDisplay').style.display = 'block';
            document.getElementById('tokenValue').textContent = token;
            authToken = token;
            localStorage.setItem('auth_token', token);
        }
        
        function displayResponse(data, isError = false) {
            const responseDiv = document.getElementById('response');
            responseDiv.className = 'response ' + (isError ? 'error' : 'success');
            responseDiv.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
        }
        
        // Register Form
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch(API_URL + '/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                displayResponse(result, !response.ok);
                
                if (response.ok && result.data.token) {
                    displayToken(result.data.token);
                }
            } catch (error) {
                displayResponse({error: error.message}, true);
            }
        });
        
        // Login Form
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch(API_URL + '/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                displayResponse(result, !response.ok);
                
                if (response.ok && result.data.token) {
                    displayToken(result.data.token);
                }
            } catch (error) {
                displayResponse({error: error.message}, true);
            }
        });
        
        // Update Profile Form
        document.getElementById('updateProfileForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!authToken) {
                displayResponse({error: 'Please login first'}, true);
                return;
            }
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch(API_URL + '/user/profile', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + authToken
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                displayResponse(result, !response.ok);
            } catch (error) {
                displayResponse({error: error.message}, true);
            }
        });
        
        // Get Profile
        async function getProfile() {
            if (!authToken) {
                displayResponse({error: 'Please login first'}, true);
                return;
            }
            
            try {
                const response = await fetch(API_URL + '/auth/me', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + authToken
                    }
                });
                
                const result = await response.json();
                displayResponse(result, !response.ok);
            } catch (error) {
                displayResponse({error: error.message}, true);
            }
        }
        
        // Logout
        async function logout() {
            if (!authToken) {
                displayResponse({error: 'Not logged in'}, true);
                return;
            }
            
            try {
                const response = await fetch(API_URL + '/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + authToken
                    }
                });
                
                const result = await response.json();
                displayResponse(result, !response.ok);
                
                if (response.ok) {
                    authToken = '';
                    localStorage.removeItem('auth_token');
                    document.getElementById('tokenDisplay').style.display = 'none';
                }
            } catch (error) {
                displayResponse({error: error.message}, true);
            }
        }
    </script>
</body>
</html>