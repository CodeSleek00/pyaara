document.addEventListener('DOMContentLoaded', () => {
    // Live Discount Percentage Calculation for add_product.php
    const originalPriceInput = document.getElementById('original_price');
    const discountPriceInput = document.getElementById('discount_price');
    const discountPercentSpan = document.getElementById('discount_percent_display');

    if (originalPriceInput && discountPriceInput && discountPercentSpan) {
        const calculateDiscount = () => {
            const originalPrice = parseFloat(originalPriceInput.value);
            const discountPrice = parseFloat(discountPriceInput.value);

            if (!isNaN(originalPrice) && !isNaN(discountPrice) && originalPrice > 0) {
                const discount = ((originalPrice - discountPrice) / originalPrice) * 100;
                discountPercentSpan.textContent = ` (${discount.toFixed(2)}% Off)`;
            } else {
                discountPercentSpan.textContent = '';
            }
        };

        originalPriceInput.addEventListener('input', calculateDiscount);
        discountPriceInput.addEventListener('input', calculateDiscount);
    }

    // Client-side validation for checkout form
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', (event) => {
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const phoneNumber = document.getElementById('phone_number').value.trim();
            const shippingAddress = document.getElementById('shipping_address').value.trim();
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');

            let isValid = true;
            let errorMessage = '';

            if (firstName === '' || lastName === '' || phoneNumber === '' || shippingAddress === '') {
                errorMessage += 'Please fill in all personal and shipping details.\n';
                isValid = false;
            }

            if (!paymentMethod) {
                errorMessage += 'Please select a payment method (Razorpay or COD).\n';
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault(); // Stop form submission
                alert(errorMessage);
            }
            // For Razorpay, the actual payment logic would be initiated here
            // before the form is submitted, often within this event listener.
            // This would involve Razorpay's JS SDK.
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productActionForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const action = document.activeElement.value;
            if (action === 'buy_now') {
                // You might want to add some visual feedback here
            }
        });
    }
});