<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Pyaara</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #FF6B6B;
            --secondary: #4ECDC4;
            --dark: #292F36;
            --light: #F7FFF7;
            --accent: #FFE66D;
        }
        
        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }

        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 5%;
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
        }

        .back-button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background-color: var(--dark);
            transform: translateX(-3px);
        }

        main {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 5%;
        }

        h1 {
            color: var(--primary);
            font-size: 2.5rem;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }

        h1:after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 60px;
            height: 4px;
            background-color: var(--secondary);
        }

        .tagline {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 30px;
        }

        .hero {
            display: flex;
            gap: 30px;
            align-items: center;
            margin-bottom: 40px;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .hero-text {
            flex: 1;
        }

        .hero-image {
            flex: 1;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .hero-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        section {
            margin-bottom: 40px;
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        section:hover {
            transform: translateY(-3px);
        }

        h2 {
            color: var(--dark);
            font-size: 1.5rem;
            margin-top: 0;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h2 i {
            color: var(--secondary);
        }

        ul {
            padding-left: 20px;
        }

        li {
            margin-bottom: 8px;
            position: relative;
            padding-left: 15px;
        }

        li:before {
            content: '•';
            color: var(--secondary);
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        .highlight {
            color: var(--primary);
            font-weight: 600;
        }

        .team {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .team-member {
            flex: 1;
            min-width: 250px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .team-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px;
            border: 3px solid var(--secondary);
        }

        .cta {
            text-align: center;
            padding: 30px;
            background-color: var(--primary);
            color: white;
            border-radius: 8px;
            margin-top: 40px;
        }

        .cta-button {
            display: inline-block;
            background-color: white;
            color: var(--primary);
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            background-color: var(--dark);
            color: white;
            transform: translateY(-3px);
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            color: var(--dark);
            background-color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background-color: var(--secondary);
            color: white;
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
            
            .hero {
                flex-direction: column;
            }
            
            section {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            header {
                padding: 15px;
            }
            
            .back-button span {
                display: none;
            }
            
            .back-button {
                padding: 8px 12px;
            }
            
            .team-member {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Pyaara</div>
        <button class="back-button" onclick="history.back()">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </button>
    </header>

    <main>
        <h1>About Pyaara</h1>
        <p class="tagline">Where Style Meets Comfort</p>

        <div class="hero">
            <div class="hero-text">
                <h2>Our Story</h2>
                <p>Pyaara was born from a simple idea: <span class="highlight">T-shirts should be as unique as you are.</span> Founded in 2025, we started with a passion for art, self-expression, and sustainable fashion. Our mission? To create affordable, high-quality tees that let your personality shine—whether you're into bold graphics, minimalist vibes, or quirky slogans.</p>
                <p><em>(Fun fact: "Pyaara" means "lovely" in Hindi—just like our designs!)</em></p>
            </div>
            <div class="hero-image">
                <!-- Replace with your actual image -->
                <img src="https://images.unsplash.com/photo-1529374255404-311a2a4f1fd9?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Pyaara T-shirt designs">
            </div>
        </div>

        <section>
            <h2><i class="fas fa-star"></i>Why Choose Pyaara?</h2>
            <ul>
                <li><strong>Unique Designs:</strong> Every tee is handpicked or created by indie artists. No mass-produced boredom here!</li>
                <li><strong>Premium Comfort:</strong> 100% cotton or eco-friendly fabrics—soft, breathable, and built to last.</li>
                <li><strong>For Everyone:</strong> Inclusive sizing and styles that suit every vibe, from casual to statement-making.</li>
                <li><strong>Ethically Made:</strong> We partner with ethical manufacturers and use sustainable packaging.</li>
            </ul>
        </section>
        <section>
            <h2><i class="fas fa-heart"></i>Our Promise to You</h2>
            <p>We're not just selling T-shirts—we're building a community. That's why we guarantee:</p>
            <ul>
                <li><strong>Hassle-free returns</strong> (because we've all had sizing woes)</li>
                <li><strong>Lightning-fast shipping</strong> (no one likes waiting forever)</li>
                <li><strong>Customer love</strong> (hit us up anytime—we're real humans!)</li>
            </ul>
        </section>

        <div class="cta">
            <h2>Ready to Find Your Perfect Tee?</h2>
            <p>Explore our latest collections and join the Pyaara family today!</p>
            <a href="../index.php" class="cta-button">SHOP NOW</a>
            
         
        </div>
    </main>
</body>
</html>