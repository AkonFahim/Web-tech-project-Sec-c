<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meet Our Team</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            text-align: center;
            margin-bottom: 40px;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 22px;
            font-weight: bold;
            color: #6b7280; 
            cursor: pointer;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .close-btn:hover{
            color: #f70909ff; 
        }
        
        h1 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }
        
        h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: #3498db;
        }
        
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .team-member {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .team-member:hover {
            transform: translateY(-5px);
        }
        
        .member-image {
            width: 100%;
            height: 250px;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            font-size: 4rem;
        }
        
        .member-info {
            padding: 20px;
            text-align: center;
        }
        
        .member-name {
            font-size: 1.4rem;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .member-role {
            color: #3498db;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .member-bio {
            color: #7f8c8d;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }
        
        .social-links a {
            color: #7f8c8d;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }
        
        .social-links a:hover {
            color: #3498db;
        }
        
        .attribution {
            text-align: center;
            margin-top: 50px;
            color: #95a5a6;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .team-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            }
            
            h1 {
                font-size: 2rem;
            }
        }

    </style>
</head>
<body>
    <a href="javascript:history.back()" class="close-btn">X</a>
    <div class="container">
        <header>
            <h1>Meet Our Team</h1>
        </header>
        
        <div class="team-grid">
            <!-- Team Member 1 -->
            <div class="team-member">
                <div class="member-image">
                 <img src="https://scontent.fdac138-1.fna.fbcdn.net/v/t39.30808-6/470168250_2050367098727431_5651114203772107331_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=769u5x6VF7IQ7kNvwEQv3A8&_nc_oc=Adk6KiF4-niPTC8ftQm-_SLvS3aXhGOaI74hDhtYPKoNDo4L7yAISoZp1d_cqSYTJfo&_nc_zt=23&_nc_ht=scontent.fdac138-1.fna&_nc_gid=hasVcD7I8CblpR7osC2bsw&oh=00_AfUkMeU8Js_GVED-Ledws80HXexcqCYnNBoVSCNuZe6ZrQ&oe=68B81315" alt="Ss" style="width: 100%; height: 250px; object-fit: cover&__tn__=~H-R" alt="S. M. Mujahid Sourov" style="width: 100%; height: 250px; object-fit: cover;">
                 </div>

                <div class="member-info">
                    <h3 class="member-name">S. M. Mujahid Sourov</h3>
                    <p class="member-role">Student</p>
                    <p class="member-id">American International University</p>
                    <p class="member-Id">22-49679-3</p>

                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Team Member 2 -->
            <div class="team-member">
                <div class="member-image">
                 <img src="https://scontent.fdac138-2.fna.fbcdn.net/v/t39.30808-6/490019131_4003237119994946_4566583476854539800_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=M0D5koBYrkkQ7kNvwHSnUnF&_nc_oc=AdlaW7YYwMGeS0LlrVQ4fIMTDzzuXpkm_CetJGLU2ZqUH-qxFYz_0aMXcwgXlUXDnyY&_nc_zt=23&_nc_ht=scontent.fdac138-2.fna&_nc_gid=kuYunYOF4p9sw7JjbNAhuQ&oh=00_AfVM-N_0XRIX1vDCZR36f6Ckk8Qf2nB3LE63GIdAHxLz9w&oe=68B82216" alt="Soumen Das" style="width: 100%; height: 250px; object-fit: cover;">
                 </div>

                <div class="member-info">
                    <h3 class="member-name">Soumen Das</h3>
                    <p class="member-role">Student</p>
                    <p class="member-ID">American International University</p>
                    <p class="member-Id">22-49531-3</p>

                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Team Member 3 -->
            <div class="team-member">
                <div class="member-image">
                 <img src="#" alt="Fahim Akon" style="width: 100%; height: 250px; object-fit: cover;">
                </div>

                <div class="member-info">
                    <h3 class="member-name">Fahim Akon</h3>
                    <p class="member-role">Student</p>
                    <p class="member-Id">American International University</p>
                    <p class="member-Id">22-46766-1</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Team Member 4 -->
            <div class="team-member">
                <div class="member-image">
                 <img src="#" alt="Israt Jahan Rifa" style="width: 100%; height: 250px; object-fit: cover;">
                </div>

                <div class="member-info">
                    <h3 class="member-name">Israt Jahan Rifa</h3>
                    <p class="member-role">Student</p>
                    <p class="member-Id">American International University</p>
                    <p class="member-Id">22-46794-1</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>