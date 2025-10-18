
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#userTable tbody tr");

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}

function updateStatus(userId, newStatus) {
    fetch('update_user_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${userId}&status=${newStatus}`
    })
    .then(response => response.text())
    .then(result => {
        // Show result in a modal
        document.getElementById('statusMessage').textContent = result;
        document.getElementById('statusModal').style.display = 'block';
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('statusMessage').textContent = 'Failed to update status.';
        document.getElementById('statusModal').style.display = 'block';
    });
}

function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#userTable tbody tr");

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}

function openCancelModal(userId) {
    document.getElementById('cancelConfirmModal').style.display = 'block';
    document.getElementById('appointmentId').value = userId;
}

function confirmCancel() {
    const id = document.getElementById('appointmentId').value;
    window.location.href = `delete_user.php?id=${id}`;
}

function closeModalById(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'none';
}
