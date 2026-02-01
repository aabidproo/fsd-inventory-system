// Auto-refresh products table
function refreshProductsTable() {
    const tableContainer = document.querySelector('#products-table');
    if (!tableContainer) return;

    // Absolute path from server root
    fetch('/inventory-system/ajax/get_products_table.php')
        .then(response => {
            if (!response.ok) {
                console.error('Failed to fetch products table:', response.status);
                return null;
            }
            return response.text();
        })
        .then(html => {
            if (html) {
                tableContainer.innerHTML = html;
            }
        })
        .catch(err => {
            console.error('Live update error:', err);
        });
}


if (document.querySelector('#products-table')) {

    setInterval(refreshProductsTable, 15000);
}

document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('.success-message, .error-message');
    alerts.forEach(alert => {

        setTimeout(() => {
            alert.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            

            setTimeout(() => {
                alert.remove();
            }, 600);
        }, 1500);
    });
});