<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shipping Policy | Pyaara</title>
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

    section {
      margin-bottom: 30px;
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

    .delivery-table {
      width: 100%;
      border-collapse: collapse;
      margin: 15px 0;
    }

    .delivery-table th, .delivery-table td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .delivery-table th {
      background-color: var(--secondary);
      color: white;
    }

    .delivery-table tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    @media (max-width: 768px) {
      h1 {
        font-size: 2rem;
      }
      
      section {
        padding: 20px;
      }

      .delivery-table {
        display: block;
        overflow-x: auto;
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
    <h1>Shipping Policy</h1>
    <span class="effective-date"><strong>Effective Date:</strong> June 10, 2025</span>

    <section>
      <h2><i class="fas fa-box-open"></i>1. Order Processing</h2>
      <p>All orders are processed within <span class="highlight">1–2 business days</span> (Monday through Friday, excluding public holidays). Orders placed after 2 PM IST will be processed the next business day.</p>
    </section>

    <section>
      <h2><i class="fas fa-truck"></i>2. Shipping Rates & Delivery Estimates</h2>
      <p>Shipping charges are calculated at checkout based on your location and order weight. Our standard delivery timelines:</p>
      
      <table class="delivery-table">
        <thead>
          <tr>
            <th>Location</th>
            <th>Delivery Time</th>
            <th>Shipping Cost</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Metro Cities</td>
            <td>2–4 business days</td>
            <td>₹49</td>
          </tr>
          <tr>
            <td>Other Urban Areas</td>
            <td>3–5 business days</td>
            <td>₹79</td>
          </tr>
          <tr>
            <td>Remote Locations</td>
            <td>5–8 business days</td>
            <td>₹99</td>
          </tr>
        </tbody>
      </table>
      
      <p><strong>Free shipping</strong> on all orders over ₹999.</p>
    </section>

    <section>
      <h2><i class="fas fa-handshake"></i>3. Delivery Partners</h2>
      <p>We partner with trusted logistics providers to ensure safe delivery:</p>
      <ul>
        <li><strong>Delhivery</strong> - For most metro and urban areas</li>
        <li><strong>Blue Dart</strong> - For premium and express deliveries</li>
        <li><strong>India Post</strong> - For remote locations</li>
      </ul>
    </section>

    <section>
      <h2><i class="fas fa-map-marked-alt"></i>4. Tracking Your Order</h2>
      <p>Once your order ships, you'll receive:</p>
      <ul>
        <li>Email with tracking number and carrier information</li>
        <li>SMS notification with tracking link</li>
        <li>Real-time updates on delivery status</li>
      </ul>
      <p>You can also track your order anytime through your <span class="highlight">Pyaara account</span>.</p>
    </section>

    <section>
      <h2><i class="fas fa-globe"></i>5. International Shipping</h2>
      <p>Currently, we only ship within India. We plan to offer international shipping in the future - sign up for our newsletter to be notified!</p>
    </section>

    <section>
      <h2><i class="fas fa-exclamation-circle"></i>6. Delays & Issues</h2>
      <p>While we strive for timely delivery, occasional delays may occur due to:</p>
      <ul>
        <li>Weather conditions or natural disasters</li>
        <li>Transportation delays</li>
        <li>Customs clearance (if applicable)</li>
        <li>Incorrect shipping address</li>
      </ul>
      <p>If your order is significantly delayed, please contact our support team for assistance.</p>
    </section>

    <section>
      <h2><i class="fas fa-headset"></i>7. Contact Us</h2>
      <p>For any shipping-related questions or concerns:</p>
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
      <strong>Note:</strong> This shipping policy is subject to change without prior notice. During sale periods or special promotions, delivery times may be slightly longer than usual.
    </p>
  </main>
</body>
</html>