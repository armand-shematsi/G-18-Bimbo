// Order Processing JS extracted from Blade

// Wait for DOM to load
// eslint-disable-next-line no-undef
// (If using a linter, ensure 'window' is allowed for global functions)
document.addEventListener('DOMContentLoaded', function() {
    // Get the wrapper div with data attributes
    const wrapper = document.querySelector('[data-supplier-order-route], [data-supplier-orders-route]');
    if (!wrapper) return;
    const supplierOrderRoute = wrapper.dataset.supplierOrderRoute;
    const supplierOrdersRoute = wrapper.dataset.supplierOrdersRoute || '/order-processing/supplier-orders';
    const retailerOrdersRoute = wrapper.dataset.retailerOrdersRoute;
    // Use global receiveOrderBaseUrl and csrfToken from Blade, do not redeclare here

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
                        fetchAndRenderSupplierOrders(); // Instead of window.location.reload();
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
                        // Add a status dropdown for all orders
                        let statusOptions = ['pending', 'processing', 'shipped', 'received'];
                        let dropdown = `<select class='order-status-dropdown border rounded px-2 py-1' data-order-id='${order.id}'>`;
                        statusOptions.forEach(opt => {
                            dropdown += `<option value='${opt}' ${order.status === opt ? 'selected' : ''}>${opt.charAt(0).toUpperCase() + opt.slice(1)}</option>`;
                        });
                        dropdown += `</select>`;
                        // Get first item (if exists)
                        let firstItem = order.items && order.items.length > 0 ? order.items[0] : null;
                        let productName = firstItem && firstItem.product ? firstItem.product.name : (firstItem ? firstItem.product_name : 'N/A');
                        let quantity = firstItem ? firstItem.quantity : 'N/A';
                        let retailerName = order.user && order.user.name ? order.user.name : '-';
                        tbody.innerHTML += `<tr>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${retailerName}</td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${productName}</td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${quantity}</td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm ${statusClass} status-cell'>${dropdown}</td>
                            <td></td>
                        </tr>`;
                    });
                }
            });
    }

    // Event delegation for status dropdown
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('order-status-dropdown')) {
            const dropdown = e.target;
            const orderId = dropdown.getAttribute('data-order-id');
            const newStatus = dropdown.value;
            dropdown.disabled = true;
            fetch(`${updateOrderStatusUrl}/${orderId}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(res => res.json().catch(() => ({ success: false, message: 'Invalid JSON response' })))
            .then(data => {
                if (data.success) {
                    // Optionally update the status cell style
                    const row = dropdown.closest('tr');
                    if (row) {
                        const statusCell = row.querySelector('.status-cell');
                        if (statusCell) {
                            statusCell.className = `px-6 py-4 whitespace-nowrap text-sm ${(newStatus === 'received') ? 'text-green-700 font-bold' : 'text-yellow-700 font-bold'} status-cell`;
                        }
                    }
                } else {
                    alert('Failed to update status: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => {
                alert('AJAX error: ' + err);
            })
            .finally(() => {
                dropdown.disabled = false;
            });
        }
    });

    // --- Supplier Orders AJAX ---
    // Use the correct route from Blade if available
    function fetchAndRenderSupplierOrders() {
        fetch(supplierOrdersRoute)
            .then(res => res.json())
            .then(data => {
                updateSupplierOrdersTable(data.orders);
            });
    }

    function updateSupplierOrdersTable(orders) {
        const tableBody = document.getElementById('supplierOrdersTableBody');
        if (!tableBody) return;
        tableBody.innerHTML = '';
        orders.forEach(order => {
            const item = order.items[0];
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${order.id}</td>
                <td>${item ? item.product_name : ''}</td>
                <td>${item ? item.quantity : ''}</td>
                <td>${order.status}</td>
                <td>${order.total}</td>
                <td>${formatDate(order.created_at)}</td>
            `;
            tableBody.appendChild(row);
        });
    }

    function formatDate(dateString) {
        if (!dateString) return '';
        const d = new Date(dateString);
        return d.toLocaleString();
    }

    fetchRetailerOrders();
    fetchAndRenderSupplierOrders();
    // setInterval(fetchRetailerOrders, 60000); // polling disabled
}); 