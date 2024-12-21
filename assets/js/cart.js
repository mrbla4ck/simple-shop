document.addEventListener('DOMContentLoaded', function() {
    // Add to cart button click handler
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            
            // Show loading state with 3D animation
            SimpleShopAlert.show({
                title: 'Adding to Cart',
                text: 'Please wait...',
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Ajax call to add to cart
            // After success:
            SimpleShopAlert.success('Product added to cart!');
        });
    });
});