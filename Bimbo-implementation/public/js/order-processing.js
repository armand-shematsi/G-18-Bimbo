// Order Processing JS extracted from Blade

// Wait for DOM to load
// eslint-disable-next-line no-undef
// (If using a linter, ensure 'window' is allowed for global functions)
document.addEventListener('DOMContentLoaded', function() {
    // Get the wrapper div with data attributes
    const wrapper = document.querySelector('[data-supplier-order-route]');
    if (!wrapper) return;
    const supplierOrderRoute = wrapper.dataset.supplierOrderRoute;
    const retailerOrdersRoute = wrapper.dataset.retailerOrdersRoute;
    const receiveOrderBaseUrl = wrapper.dataset.receiveOrderBaseUrl;
    const csrfToken = document.querySelector('meta[name=csrf-token]').content;

    // --- Supplier Order Form AJAX ---
    const supplierOrderForm = document.getElementById('supplierOrderForm');
    if (supplierOrderForm) {
        supplierOrderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const msg = document.getElementById('supplierOrderMsg');
            msg.textContent = '';
            fetch(supplierOrderRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        product_id: form.product_id.value,
                        quantity: form.quantity.value,
                        supplier_id: form.supplier_id.value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        msg.textContent = 'Order placed successfully!';
                        msg.className = 'mt-2 text-green-600';
                        form.reset();
                    } else {
                        msg.textContent = 'Failed to place order.';
                        msg.className = 'mt-2 text-red-600';
                    }
                })
                .catch(() => {
                    msg.textContent = 'Error placing order.';
                    msg.className = 'mt-2 text-red-600';
                });
        });
    }

    // --- Retailer Orders AJAX ---
    function fetchRetailerOrders() {
        const tbody = document.getElementById('retailerOrdersTbody');
        if (!tbody) return;
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-gray-400">Loading...</td></tr>';
        fetch(retailerOrdersRoute)
            .then(res => res.json())
            .then(data => {
                tbody.innerHTML = '';
                if (!data.orders || data.orders.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-gray-400">No retailer orders found.</td></tr>';
                } else {
                    data.orders.forEach(order => {
                        let statusClass = order.status === 'received' ? 'text-green-700 font-bold' : 'text-yellow-700 font-bold';
                        let btn = order.status === 'pending' ? `<button data-order-id='${order.id}' class='bg-green-500 text-white px-2 py-1 rounded text-xs mark-received-btn'>Mark Received</button>` : '';
                        // Get first item (if exists)
                        let firstItem = order.items && order.items.length > 0 ? order.items[0] : null;
                        let productName = firstItem && firstItem.product ? firstItem.product.name : (firstItem ? firstItem.product_name : 'N/A');
                        let quantity = firstItem ? firstItem.quantity : 'N/A';
                        let retailerName = order.user && order.user.name ? order.user.name : '-';
                        tbody.innerHTML += `<tr>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${retailerName}</td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${productName}</td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${quantity}</td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm ${statusClass}'>${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</td>
                            <td>${btn}</td>
                        </tr>`;
                    });
                }
            });
    }

    // Event delegation for Mark Received buttons
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('mark-received-btn')) {
            const btn = e.target;
            const orderId = btn.getAttribute('data-order-id');
            if (!orderId) return;
            btn.disabled = true;
            fetch(`${receiveOrderBaseUrl}/${orderId}/receive`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) fetchRetailerOrders();
            });
        }
    });

    fetchRetailerOrders();
    // setInterval(fetchRetailerOrders, 60000); // polling disabled
}); 