<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Solstice Secondary School Portal</title>
    <link rel="stylesheet" href="sec.css">
    <style>
        optgroup { font-weight: bold; background: #f4f4f4; }
        option { background: #fff; font-weight: normal; }
        .info-box {
            background: #e8f4f8;
            border-left: 4px solid #0A2342;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .info-box h4 { margin-top: 0; color: #0A2342; }
        .info-box p { margin: 5px 0; font-size: 14px; }
        body{
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
</head>
<body class="portal-body" >

    <div class="container" id="login-form">
        <div><img src="logo" alt="Logo of Solstice Secondary School" style="width: auto; height: 110px; display: block; margin: auto;"></div>
        <h2>Solstice Secondary School</h2>
        <h3>System Login</h3>
        
        <?php 
        if(isset($_SESSION['error'])) { 
            echo "<p style='color:red; text-align:center; font-weight:bold;'>".$_SESSION['error']."</p>"; 
            unset($_SESSION['error']); 
        } 
        if(isset($_SESSION['success'])) { 
            echo "<p style='color:green; text-align:center; font-weight:bold;'>".$_SESSION['success']."</p>"; 
            unset($_SESSION['success']); 
        } 
        ?>
        
        <form action="login_register.php" method="POST">
            <input type="hidden" name="action" value="login">
            <input type="email" name="email" placeholder="Email (e.g., student001@solstice.com)" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="toggle-link" onclick="toggleForms()">Need an account? Register here</div>
    </div>

    <div class="container" id="register-form" style="display:none;">
        <h2> Registration</h2>
        
        <div class="info-box">
            <h4> Email Format Guide:</h4>
            <p><strong>Students:</strong> studentXXX@solstice.com (e.g., student001@solstice.com)</p>
            <p><strong>Teachers:</strong> Contact admin for registration</p>
        </div>
        
        <form action="login_register.php" method="POST">
            <input type="hidden" name="action" value="register">
            
            <input type="text" name="name" placeholder="Full Name" required>
            
            <input type="email" id="regEmail" name="email" placeholder="Email (student001@solstice.com)" required>
            
            <input type="password" name="password" placeholder="Password (min 6 characters)" required minlength="6">
            
            

            <select name="district" required>
                <option value="">Select District of Origin</option>
                <optgroup label="Northern Region">
                    <option value="Chitipa">Chitipa</option>
                    <option value="Karonga">Karonga</option>
                    <option value="Likoma">Likoma</option>
                    <option value="Mzimba">Mzimba</option>
                    <option value="Nkhata Bay">Nkhata Bay</option>
                    <option value="Rumphi">Rumphi</option>
                </optgroup>
                <optgroup label="Central Region">
                    <option value="Dedza">Dedza</option>
                    <option value="Dowa">Dowa</option>
                    <option value="Kasungu">Kasungu</option>
                    <option value="Lilongwe">Lilongwe</option>
                    <option value="Mchinji">Mchinji</option>
                    <option value="Nkhotakota">Nkhotakota</option>
                    <option value="Ntcheu">Ntcheu</option>
                    <option value="Ntchisi">Ntchisi</option>
                    <option value="Salima">Salima</option>
                </optgroup>
                <optgroup label="Southern Region">
                    <option value="Balaka">Balaka</option>
                    <option value="Blantyre">Blantyre</option>
                    <option value="Chikwawa">Chikwawa</option>
                    <option value="Chiradzulu">Chiradzulu</option>
                    <option value="Machinga">Machinga</option>
                    <option value="Mangochi">Mangochi</option>
                    <option value="Mulanje">Mulanje</option>
                    <option value="Mwanza">Mwanza</option>
                    <option value="Neno">Neno</option>
                    <option value="Nsanje">Nsanje</option>
                    <option value="Phalombe">Phalombe</option>
                    <option value="Thyolo">Thyolo</option>
                    <option value="Zomba">Zomba</option>
                </optgroup>
            </select>

            <p>Gender </p><br>

<input type="radio" id="male" name="gender" value="Male" required>
<label for="male">Male</label>

<input type="radio" id="female" name="gender" value="Female" required>
<label for="female">Female</label>

            <button type="submit" class="btn">Register </button>
        </form>
        <div class="toggle-link" onclick="toggleForms()">Already have an account? Login</div>
    </div>

    <script>
        function toggleForms() {
            const login = document.getElementById('login-form');
            const reg = document.getElementById('register-form');
            if (login.style.display === 'none') {
                login.style.display = 'block';
                reg.style.display = 'none';
            } else {
                login.style.display = 'none';
                reg.style.display = 'block';
            }
        }
    </script>
</body>
</html>