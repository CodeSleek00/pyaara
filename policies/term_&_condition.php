<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions | Pyaara</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #FF6B6B;
            --secondary: #4ECDC4;
            --dark: #292F36;
            --light: #F7FFF7;
            --accent: #FFE66D;
            --gray: #6B7280;
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
            color: var(--gray);
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
            margin-bottom: 10px;
            position: relative;
            padding-left: 20px;
        }

        li:before {
            content: '•';
            color: var(--secondary);
            position: absolute;
            left: 0;
            font-weight: bold;
            font-size: 1.2rem;
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
            color: var(--gray);
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 40px;
            font-style: italic;
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
        <h1>Terms & Conditions</h1>
        <span class="effective-date"><strong>Effective Date:</strong> June 10, 2025</span>

        <section>
            <h2><i class="fas fa-info-circle"></i>1. Introduction</h2>
            <p>Welcome to <span class="highlight">Pyaara</span>. These terms and conditions outline the rules and regulations for the use of our website and services.</p>
        </section>

        <section>
            <h2><i class="fas fa-check-circle"></i>2. Acceptance of Terms</h2>
            <p>By accessing or using our website, you agree to comply with and be bound by these terms. If you disagree with any part, you must not use our services.</p>
        </section>

        <section>
            <h2><i class="fas fa-laptop"></i>3. Use of Website</h2>
            <ul>
                <li>You must be at least 18 years old or have parental consent to use our services</li>
                <li>You agree not to misuse the website or its contents for any unlawful purpose</li>
                <li>All purchases must be for personal, non-commercial use unless otherwise authorized</li>
                <li>You are responsible for ensuring the accuracy of information provided</li>
            </ul>
        </section>

        <section>
            <h2><i class="fas fa-copyright"></i>4. Intellectual Property</h2>
            <p>All content, including but not limited to images, logos, text, product designs, and website layout are the intellectual property of <span class="highlight">Pyaara</span> and protected by copyright laws. Unauthorized use, reproduction, or distribution is strictly prohibited.</p>
        </section>

        <section>
            <h2><i class="fas fa-shopping-cart"></i>5. Order & Payment</h2>
            <ul>
                <li>All prices are listed in INR and include applicable taxes</li>
                <li>Prices are subject to change without notice</li>
                <li>Payment must be completed before items are shipped</li>
                <li>We use trusted third-party payment processors (Razorpay, etc.)</li>
                <li>Orders are subject to availability and may be canceled if items are out of stock</li>
            </ul>
        </section>

        <section>
            <h2><i class="fas fa-truck"></i>6. Shipping & Returns</h2>
            <ul>
                <li>Standard shipping times are 3-7 business days</li>
                <li>Shipping charges vary by location and order size</li>
                <li>Returns accepted within 14 days of delivery for unused items</li>
                <li>Certain items may be final sale (clearly marked)</li>
                <li>Refer to our detailed <a href="#" style="color: var(--primary);">Return Policy</a> for complete information</li>
            </ul>
        </section>

        <section>
            <h2><i class="fas fa-user"></i>7. User Accounts</h2>
            <ul>
                <li>You are responsible for maintaining the confidentiality of your account credentials</li>
                <li>Any unauthorized use must be reported immediately</li>
                <li>We reserve the right to suspend or terminate accounts for violations</li>
                <li>You agree to provide accurate and current information</li>
            </ul>
        </section>

        <section>
            <h2><i class="fas fa-exclamation-triangle"></i>8. Limitation of Liability</h2>
            <p>Pyaara shall not be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from:</p>
            <ul>
                <li>Your access to or use of or inability to access or use the service</li>
                <li>Any conduct or content of any third party on the service</li>
                <li>Any content obtained from the service</li>
                <li>Unauthorized access, use or alteration of your transmissions or content</li>
            </ul>
        </section>

        <section>
            <h2><i class="fas fa-sync-alt"></i>9. Changes to Terms</h2>
            <p>We reserve the right to modify these terms at any time. Changes will be posted on this page with an updated effective date. Your continued use of our services after changes constitutes acceptance of the new terms.</p>
        </section>

        <section>
            <h2><i class="fas fa-envelope"></i>10. Contact Us</h2>
            <p>If you have any questions regarding these terms, please contact us at:</p>
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
            <strong>Note:</strong> These Terms & Conditions constitute a legally binding agreement between you and Pyaara. By using our website and services, you acknowledge that you have read, understood, and agree to be bound by these terms. If you do not agree, please discontinue use of our services immediately.
        </p>
    </main>
</body>
</html>