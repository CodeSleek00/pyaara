<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>
   Profile
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
   /* Custom scrollbar for the left section */
    .scrollbar-thin::-webkit-scrollbar {
      width: 4px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb {
      background-color: #d1d5db;
      border-radius: 9999px;
    }
    
    /* Make entire button clickable */
    .clickable-button {
      display: block;
      width: 100%;
      text-align: left;
    }
    .clickable-button:hover {
      text-decoration: none;
    }
    
    /* Red accent color */
    .text-accent {
      color: #dc2626; /* Tailwind's red-600 */
    }
    .border-accent {
      border-color: #dc2626;
    }
    .hover\:text-accent:hover {
      color: #dc2626;
    }
  </style>
 </head>
 <body class="bg-white font-sans text-gray-700">
  <div class="max-w-md mx-auto min-h-screen flex flex-col">
   <!-- Header -->
  <header class="flex items-center justify-between px-4 py-3 bg-red-600 text-white">
  <a href="index.php" class="hover:text-gray-100">
    <i class="fas fa-arrow-left text-xl"></i>
  </a>
  <h1 class="font-semibold text-base select-none">Profile</h1>
  <div class="w-6"></div>
</header>

   <main class="flex-1 overflow-y-auto scrollbar-thin">
    <!-- Top grid -->
    <div class="grid grid-cols-2 gap-4 p-4 border-b border-gray-200">
  <!-- Login / My Profile -->
  <a href="login.html" class="flex items-center justify-between border border-gray-300 rounded-lg px-4 py-2 hover:shadow-sm clickable-button hover:border-accent">
    <div class="flex items-center space-x-3">
      <i class="fas fa-sign-in-alt text-red-600 text-lg"></i>
      <span class="font-semibold text-gray-800 select-none text-sm">
        <span id="loginLink">Login</span>
        <span id="profileLink" style="display:none;" class="text-accent">My Profile</span>
      </span>
    </div>
    <i class="fas fa-chevron-right text-red-600 text-sm"></i>
  </a>

  <!-- Signup / Logout -->
  <a href="signup.html" class="flex items-center justify-between border border-gray-300 rounded-lg px-4 py-2 hover:shadow-sm clickable-button hover:border-accent">
    <div class="flex items-center space-x-3">
      <i class="fas fa-user-plus text-red-600 text-lg"></i>
      <span class="font-semibold text-gray-800 select-none text-sm">
        <span id="signupLink">Signup</span>
        <span id="logoutLink" style="display:none;" class="text-accent">Logout</span>
      </span>
    </div>
    <i class="fas fa-chevron-right text-red-600 text-sm"></i>
  </a>

  <!-- Help Center -->
  <<a href="mailto:pyaara001@gmail.com" class="flex items-center justify-between border border-gray-300 rounded-lg px-4 py-2 hover:shadow-sm clickable-button hover:border-accent">
    <div class="flex items-center space-x-3">
      <i class="fas fa-question-circle text-red-600 text-lg"></i>
      <span class="font-semibold text-gray-800 select-none text-sm">Help Center</span>
    </div>
    <i class="fas fa-chevron-right text-red-600 text-sm"></i>
  </a>

  <!-- Coupons -->
  <a href="index.php" class="flex items-center justify-between border border-gray-300 rounded-lg px-4 py-2 hover:shadow-sm clickable-button hover:border-accent">
    <div class="flex items-center space-x-3">
      <i class="fas fa-tags text-red-600 text-lg"></i>
      <span class="font-semibold text-gray-800 select-none text-sm">Coupons</span>
    </div>
    <i class="fas fa-chevron-right text-red-600 text-sm"></i>
  </a>
</div>
<ul class="divide-y divide-gray-200">
  <li>
    <a href="policies/privacy_policy.php" class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 clickable-button">
      <div class="flex items-center space-x-3">
        <i class="fas fa-shield-alt text-red-600 text-lg"></i>
        <div class="text-left">
          <p class="font-semibold text-gray-900 select-none flex items-center space-x-2 text-sm">
            <span>Privacy Policy</span>
          </p>
          <p class="text-gray-500 text-xs leading-tight select-text">
            Learn about our privacy practices
          </p>
        </div>
      </div>
      <i class="fas fa-chevron-right text-red-600 text-sm"></i>
    </a>
  </li>

  <li>
    <a href="orders/search_order.php" class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 clickable-button">
      <div class="flex items-center space-x-3">
        <i class="fas fa-shipping-fast text-red-600 text-lg"></i>
        <div class="text-left">
          <p class="font-semibold text-gray-900 select-none text-sm">
            Track Your Orders
          </p>
          <p class="text-gray-500 text-xs leading-tight select-text">
            Information about shipping and Your Returns
          </p>
        </div>
      </div>
      <i class="fas fa-chevron-right text-red-600 text-sm"></i>
    </a>
  </li>

  
  <li>
    <a href="profile.php" class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 clickable-button">
      <div class="flex items-center space-x-3">
        <i class="fas fa-user-cog text-red-600 text-lg"></i>
        <div class="text-left">
          <p class="font-semibold text-gray-900 select-none text-sm">
            Manage Account
          </p>
          <p class="text-gray-500 text-xs leading-tight select-text">
            Manage your account and saved addresses
          </p>
        </div>
      </div>
      <i class="fas fa-chevron-right text-red-600 text-sm"></i>
    </a>
  </li>

  <li>
    <a href="policies/term_&_condition.php" class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 clickable-button">
      <div class="flex items-center space-x-3">
        <i class="fas fa-undo text-red-600 text-lg"></i>
        <div class="text-left">
          <p class="font-semibold text-gray-900 select-none text-sm">
            Refund Policy
          </p>
          <p class="text-gray-500 text-xs leading-tight select-text">
            Learn about our refund process
          </p>
        </div>
      </div>
      <i class="fas fa-chevron-right text-red-600 text-sm"></i>
    </a>
  </li>

  <li>
    <a href="orders/offer.php" class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 clickable-button">
      <div class="flex items-center space-x-3">
        <i class="fas fa-lightbulb text-red-600 text-lg"></i>
        <div class="text-left">
          <p class="font-semibold text-gray-900 select-none text-sm">
            Pyaara Suggests
          </p>
          <p class="text-gray-500 text-xs leading-tight select-text">
            100% personalized feed just for you
          </p>
        </div>
      </div>
      <i class="fas fa-chevron-right text-red-600 text-sm"></i>
    </a>
  </li>

 </ul>
  <script>
    // Check session via cookie
    fetch('session_check.php')
      .then(res => res.json())
      .then(data => {
        if (data.loggedIn) {
          document.getElementById('signupLink').style.display = 'none';
          document.getElementById('loginLink').style.display = 'none';
          document.getElementById('profileLink').style.display = 'block';
          document.getElementById('logoutLink').style.display = 'block';
          
          // Update href for profile link
          const profileButton = document.querySelector('a[href="login.html"]');
          if (profileButton) {
            profileButton.href = 'profile.php';
          }
          
          // Update href for signup/logout link
          const signupButton = document.querySelector('a[href="signup.html"]');
          if (signupButton) {
            signupButton.href = 'logout.php';
          }
        }
      });
  </script>
 </body>
</html>