<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | Pyaara</title>
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

        .effective-date {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 30px;
            display: block;
        }

        .intro {
            font-size: 1.1rem;
            margin-bottom: 40px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
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

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .contact-item i {
            color: var(--secondary);
            width: 20px;
            text-align: center;
        }

        .disclaimer {
            font-size: 0.85rem;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
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
        <h1>Privacy Policy</h1>
        <span class="effective-date"><strong>Effective Date:</strong> June 10, 2025</span>

        <div class="intro">
            At <span class="highlight">Pyaara</span>, we value your privacy and are committed to protecting your personal information. This privacy policy explains how we collect, use, and share your data when you visit or make a purchase from <span class="highlight">pyaara.com</span>.
        </div>

        <section>
            <h2><i class="fas fa-info-circle"></i>1. Information We Collect</h2>
            <ul>
                <li><strong>Personal Identifiable Information:</strong> Name, email address, phone number, shipping address, billing address, and payment details.</li>
                <li><strong>Account Information:</strong> If you create an account, we store your login credentials securely.</li>
                <li><strong>Order History:</strong> Items purchased, size preferences, and returns.</li>
                <li><strong>Website Usage:</strong> IP address, browser type, pages viewed, and interactions (via cookies).</li>
            </ul>
        </section>

        <section>
            <h2><i class="fas fa-cogs"></i>2. How We Use Your Information</h2>
            <ul>
                <li>Process and fulfill orders</li>
                <li>Communicate with you (order updates, marketing)</li>
                <li>Improve our website and services</li>
                <li>Prevent fraud and maintain security</li>
                <li>Comply with legal obligations</li>
            </ul>
        </section>

        <section>
            <h2><i class="fas fa-cookie-bite"></i>3. Cookies & Tracking Technologies</h2>
            <p>We use cookies to:</p>
            <ul>
                <li>Enable essential site functions</li>
                <li>Analyze website traffic</li>
                <li>Remember your preferences</li>
            </ul>
            <p>You can control cookie settings via your browser.</p>
        </section>

        <section>
            <h2><i class="fas fa-share-alt"></i>4. Sharing Your Information</h2>
            <p>We do not sell your personal data. We only share information with:</p>
            <ul>
                <li>Payment processors (Razorpay)</li>
                <li>Shipping partners (Delivery)</li>
                <li>Marketing tools (Meta Ads)</li>
                <li>Legal authorities when required</li>
            </ul>
        </section>

        <section>
            <h2><i class="fas fa-lock"></i>5. Data Security</h2>
            <p>We implement industry-standard security measures including SSL encryption, secure servers, and regular security audits to protect your data from unauthorized access or disclosure.</p>
        </section>

        <section>
            <h2><i class="fas fa-user-shield"></i>6. Your Rights</h2>
            <p>Depending on your location, you may have rights to:</p>
            <ul>
                <li>Access the personal data we hold</li>
                <li>Correct or update your information</li>
                <li>Request deletion of your data</li>
                <li>Opt-out of marketing emails</li>
                <li>Data portability</li>
            </ul>
        </section>

        <section>
            <h2><i class="fas fa-external-link-alt"></i>7. Third-Party Links</h2>
            <p>Our website may contain links to third-party websites. We are not responsible for their privacy practices. We encourage you to review their privacy policies when visiting these sites.</p>
        </section>

        <section>
            <h2><i class="fas fa-envelope"></i>8. Contact Us</h2>
            <p>If you have any questions or concerns about this policy, contact us at:</p>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>pyaara001@gmail.com</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Suraksha Enclave, Udyan-2, Eldeco, Lucknow</span>
                </div>
            </div>
        </section>

        <p class="disclaimer">
            <strong>Note:</strong> This privacy policy may be updated periodically to reflect changes in our practices or legal requirements. We will notify you of any significant changes by posting the new policy on our website with a revised effective date.
        </p>
    </main>
</body>
</html>