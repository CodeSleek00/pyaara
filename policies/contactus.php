<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Pyaara</title>
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

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.1rem;
        }

        .contact-item i {
            color: var(--primary);
            width: 24px;
            text-align: center;
            font-size: 1rem;
        }

        .contact-item a {
            color: var(--dark);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-item a:hover {
            color: var(--primary);
        }

        .contact-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: 'Outfit', sans-serif;
            transition: border-color 0.3s ease;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--secondary);
        }

        textarea {
            min-height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background-color: var(--dark);
            transform: translateY(-2px);
        }

        .business-hours {
            margin-top: 30px;
        }

        .hours-list {
            list-style: none;
            padding: 0;
            margin: 15px 0 0 0;
        }

        .hours-list li {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .hours-list li:last-child {
            border-bottom: none;
        }

        .day {
            font-weight: 500;
        }

        .time {
            color: #666;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
            
            section {
                padding: 20px;
            }
            
            .contact-form {
                grid-template-columns: 1fr;
            }
            
            .form-group.full-width {
                grid-column: span 1;
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
            
            .contact-item {
                font-size: 1rem;
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
        <h1>Contact Us</h1>

        <div class="intro">
            We'd love to hear from you! Whether you have questions about our products, need assistance with an order, or just want to share feedback, our team is here to help. Reach out through any of the channels below.
        </div>

        <section>
            <h2><i class="fas fa-info-circle"></i>Contact Information</h2>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>Email: <a href="mailto:pyaara001@gmail.com">pyaara001@gmail.com</a></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-headset"></i>
                    <span>Support: <a href="mailto:pyaara001@gmail.com">pyaara001@gmail.com</a></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>Phone: <a href="tel:+917839460427">+91-7839460427</a></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Address: Suraksha Enclave, Udyan-2, Eldeco, Lucknow</span>
                </div>
            </div>
            
            <div class="business-hours">
                <h3>Business Hours</h3>
                <ul class="hours-list">
                    <li>
                        <span class="day">Monday - Friday</span>
                        <span class="time">10:00 AM - 6:00 PM</span>
                    </li>
                    <li>
                        <span class="day">Saturday</span>
                        <span class="time">10:00 AM - 4:00 PM</span>
                    </li>
                    <li>
                        <span class="day">Sunday</span>
                        <span class="time">Closed</span>
                    </li>
                </ul>
            </div>
        </section>

        <section>
            <h2><i class="fas fa-question-circle"></i>Frequently Asked Questions</h2>
            <div class="faq-item">
                <h3>How long does it take to get a response?</h3>
                <p>We typically respond to emails within 24-48 hours during business days. For urgent matters, please call us during business hours.</p>
            </div>
            <div class="faq-item">
                <h3>Can I visit your physical location?</h3>
                <p>Currently, we operate as an online-only store and don't have a physical retail location open to the public.</p>
            </div>
            <div class="faq-item">
                <h3>Do you offer wholesale or bulk orders?</h3>
                <p>Yes! For wholesale inquiries, please email us with details about your business and order requirements.</p>
            </div>
        </section>
    </main>
</body>
</html>