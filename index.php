
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to GiveBright</title>
    <style>
        /* General Styling */
        body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 0;
        background-color: #f4f4f9;
        color: #333;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    

        background-image: url('uploads/banner.jpg'); /* Replace with the path to your image */
        background-size: cover; 
        background-position: center; 
        background-repeat: no-repeat;
}

        .container {
            width: 400px; 
            height: 100px;
            margin: auto;
            text-align: center;
            flex: 1;
			background:transparent;
			justify-content: center; 
            align-items: center;
			
            background: transparent;
            border: 2px solid rgba(255, 255, 255, .2);
            backdrop-filter: blur(20px);
            box-shadow: 0 0 10px rgba(0,0,0,.1);
           color: #fff;
           border-radius: 10px;
           padding: 30px 40px;
        }
		.co {
            width: 400px; 
            height: 500px;
            margin: auto;
            text-align: center;
            flex: 1;
			justify-content: center;
            align-items: center;
        }
		}

        h1 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        a {
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
            transition: color 0.3s;
        }

        a:hover {
            color: #1abc9c;
        }
		a:active {
            opacity:0.4;
        }
		        footer {
            text-align: center;
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
        }
		footer {
            text-align: center;
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <div class="co">
    </div>
    <div class="container">
        <h1>Welcome to GiveBright</h1>
        <p><a href="signin.php">Sign In</a> | <a href="register.php">Register</a> | <a href="admin_dashboard.php">Admin Login</a></p> 
        <p>Make a difference with your donation today!</p>
        <p><a href="home.php">Donate Now</a></p>
    </div>
	<div class="co">
    </div>
    <footer>
        <p>&copy; 2025 GiveBright. All rights reserved.</p>
    </footer>
</body>
</html>