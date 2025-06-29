<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns & Refunds Policy | Pyaara</title>
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

        .policy-highlight {
            background-color: #FFF9C4;
            padding: 15px;
            border-left: 4px solid var(--accent);
            margin: 20px 0;
            font-weight: 500;
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
        <h1>Returns & Refunds Policy</h1>
        <span class="effective-date"><strong>Effective Date:</strong> June 10, 2025</span>

        <div class="intro">
            At <span class="highlight">Pyaara</span>, we want you to be completely satisfied with your purchase. Please read this policy carefully to understand our return and refund procedures. This policy applies to all purchases made through <span class="highlight">pyaara.com</span>.
        </div>

        <div class="policy-highlight">
            <i class="fas fa-exclamation-circle"></i> Important: We have a <strong>No Return Policy</strong>. Once an order is placed, it cannot be returned. However, cancellations are permitted within 24 hours of placing the order.
        </div>

        <section>
            <h2><i class="fas fa-ban"></i>1. No Return Policy</h2>
            <p>All sales at Pyaara are final. We do not accept returns or exchanges for any products purchased through our website. Please review your order carefully before completing your purchase.</p>
            <p>Exceptions may be made only in cases where:</p>
            <ul>
                <li>The product received is damaged or defective</li>
                <li>You received a wrong item that differs from what you ordered</li>
            </ul>
            <p>In such cases, please contact us immediately at <span class="highlight">pyaara001@gmail.com</span> with your order details and photos of the issue.</p>
        </section>

        <section>
            <h2><i class="fas fa-money-bill-wave"></i>2. Refund Policy</h2>
            <p>Refunds are only available under the following circumstances:</p>
            <ul>
                <li><strong>Order Cancellation:</strong> You may cancel your order within 24 hours of placement for a full refund.</li>
                <li><strong>Damaged/Defective Items:</strong> If you receive a damaged or defective product, we will either replace the item or issue a refund after verification.</li>
                <li><strong>Non-Delivery:</strong> If your order hasn't arrived within the promised delivery timeframe.</li>
            </ul>
            
            <h3>Razorpay Payment Processing:</h3>
            <p>For payments processed through Razorpay:</p>
            <ul>
                <li>Cancelled orders will be refunded within 24 hours of cancellation</li>
                <li>Refunds will be credited to the original payment method</li>
                <li>Processing time may vary depending on your bank (typically 5-7 business days)</li>
            </ul>
        </section>

        <section>
            <h2><i class="fas fa-clock"></i>3. Cancellation Policy</h2>
            <p>You may cancel your order within 24 hours of placing it:</p>
            <ul>
                <li>Login to your account and go to "My Orders"</li>
                <li>Select the order you wish to cancel</li>
                <li>Click "Cancel Order" and follow the prompts</li>
                <li>You will receive a confirmation email once cancellation is processed</li>
            </ul>
            <p>After 24 hours, orders cannot be cancelled as they enter our processing system.</p>
        </section>

        <section>
            <h2><i class="fas fa-truck"></i>4. Shipping Policy</h2>
            <p>Once an order is placed and processed:</p>
            <ul>
                <li>Orders are typically shipped within 1-2 business days</li>
                <li>You will receive tracking information via email</li>
                <li>Delivery times vary by location (usually 3-7 business days within India)</li>
                <li>Shipping charges are non-refundable</li>
            </ul>
        </section>

        <section>
            <h2><i class="fas fa-exchange-alt"></i>5. Damaged or Defective Items</h2>
            <p>If you receive a damaged or defective item:</p>
            <ul>
                <li>Contact us within 48 hours of delivery at <span class="highlight">pyaara001@gmail.com</span></li>
                <li>Include your order number and clear photos of the damage/defect</li>
                <li>We will review your claim and respond within 2 business days</li>
                <li>If approved, we will arrange for replacement (subject to availability) or issue a refund</li>
            </ul>
            <p>Note: Minor variations in color or texture due to monitor settings or handmade nature of products do not qualify as defects.</p>
        </section>

        <section>
            <h2><i class="fas fa-envelope"></i>6. Contact Us</h2>
            <p>For any questions regarding our Returns & Refunds Policy:</p>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>pyaara001@gmail.com</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>+91 7839460427 (10 AM - 6 PM, Mon-Sat)</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Suraksha Enclave, Udyan-2, Eldeco, Lucknow</span>
                </div>
            </div>
        </section>

        <p class="disclaimer">
            <strong>Note:</strong> Pyaara reserves the right to modify this policy at any time. Any changes will be posted on this page with an updated effective date. For orders placed before the change date, the policy in effect at the time of purchase will apply.
        </p>
    </main>
</body>
</html>