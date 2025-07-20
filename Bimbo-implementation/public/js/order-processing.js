window.onerror = function(message, source, lineno, colno, error) {
    // alert("JS Error: " + message + "\nSource: " + source + "\nLine: " + lineno + ", Column: " + colno);
    return false;
};

document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.querySelector('[data-supplier-order-route], [data-supplier-orders-route]');
    if (!wrapper) return;
    const supplierOrderRoute = wrapper.dataset.supplierOrderRoute;
    const supplierOrdersRoute = wrapper.dataset.supplierOrdersRoute || '/order-processing/supplier-orders';
    const retailerOrdersRoute = wrapper.dataset.retailerOrdersRoute;

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
                        fetchAndRenderSupplierOrders(); // Refresh supplier orders table immediately
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
                        let statusOptions = ['pending', 'processing', 'shipped', 'received'];
                        let dropdown = `<select class='order-status-dropdown border rounded px-2 py-1' data-order-id='${order.id}'>`;
                        statusOptions.forEach(opt => {
                            dropdown += `<option value='${opt}' ${order.status === opt ? 'selected' : ''}>${opt.charAt(0).toUpperCase() + opt.slice(1)}</option>`;
                        });
                        dropdown += `</select>`;
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

    // --- Supplier Orders AJAX ---
    function fetchAndRenderSupplierOrders() {
        fetch(supplierOrdersRoute)
            .then(res => res.json())
            .then(data => {
                updateSupplierOrdersTable(data.orders);
            })
            .catch(err => {
                // alert("AJAX error: " + err);
            });
    }

    function updateSupplierOrdersTable(orders) {
        const tableBody = document.getElementById('supplierOrdersTableBody');
        if (!tableBody) return;
        tableBody.innerHTML = '';
        orders.forEach(order => {
            console.log(order); // Debug output
            if (!order.product || typeof order.product !== 'object') return;
            let total = '-';
            let unitPrice = null;
            if (order.product.unit_price !== undefined && order.product.unit_price !== null && !isNaN(parseFloat(order.product.unit_price))) {
                unitPrice = parseFloat(order.product.unit_price);
            } else if (order.product.price !== undefined && order.product.price !== null && !isNaN(parseFloat(order.product.price))) {
                unitPrice = parseFloat(order.product.price);
            }
            if (order.quantity && unitPrice !== null) {
                total = (order.quantity * unitPrice).toFixed(2);
            }
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${order.id}</td>
                <td>${order.product.name || ''}</td>
                <td>${order.quantity || ''}</td>
                <td>${order.status}</td>
                <td>${total}</td>
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
});