document.addEventListener('DOMContentLoaded', function() {
                // Calculate initial total
                calculateTotal();
                
                // Add event listeners to checkboxes
                const checkboxes = document.querySelectorAll('.game-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', calculateTotal);
                });
                
                function calculateTotal() {
                    let total = 0;
                    
                    // Get all checked game items
                    const checkedItems = document.querySelectorAll('.game-checkbox:checked');
                    
                    checkedItems.forEach(checkbox => {
                        const cartItem = checkbox.closest('.cart-item');
                        const priceElement = cartItem.querySelector('.cart-item-price');
                        if (priceElement) {
                            const priceText = priceElement.textContent;
                            // Extract numeric value from price string (e.g., "RM36.60")
                            const price = parseFloat(priceText.replace('RM', '').replace(',', ''));
                            if (!isNaN(price)) {
                                total += price;
                            }
                        }
                    });
                    
                    // Update total display
                    document.getElementById('total-amount').textContent = 'RM' + total.toFixed(2);
                }
            });