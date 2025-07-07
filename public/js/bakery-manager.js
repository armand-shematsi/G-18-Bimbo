// Assign Shift Modal logic
// ... existing code from the inline script in bakery-manager.blade.php ...
document.addEventListener('DOMContentLoaded', function() {
    const assignShiftModal = document.getElementById('assignShiftModal');
    const assignShiftForm = document.getElementById('assignShiftForm');
    const staffSelect = assignShiftForm.querySelector('select[name="user_id"]');
    const centerSelect = assignShiftForm.querySelector('select[name="supply_center_id"]');
    staffSelect.addEventListener('change', function() {
        // const centerId = staffCenters[this.value];
        // if (centerId) {
        //     centerSelect.value = centerId;
        // } else {
        //     centerSelect.value = '';
        // }
    });
    assignShiftForm.onsubmit = function(e) {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(assignShiftForm).entries());
        fetch(assignShiftForm.getAttribute('action') || assignShiftForm.dataset.action || "/bakery/workforce/shifts", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const messageDiv = document.getElementById('shift-message');
                    const messageText = document.getElementById('shift-message-text');
                    messageText.textContent = data.message || 'Shift assigned successfully!';
                    messageDiv.classList.remove('hidden');
                    setTimeout(() => messageDiv.classList.add('hidden'), 5000);
                    assignShiftModal.style.display = 'none';
                    assignShiftForm.reset();
                } else {
                    alert('Error assigning shift: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error assigning shift. Please try again.');
            });
    };
});
// ... existing code ... 