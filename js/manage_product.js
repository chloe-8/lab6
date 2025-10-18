document.addEventListener('DOMContentLoaded', function () {
    // Handle the edit button click
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            window.location.href = `edit_product.php?id=${productId}`;
        });
    });

    // Handle the delete button click using modal
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            openCancelModal(productId); // Show custom confirmation modal
        });
    });
});

// Filter search table
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll("#productTable tbody tr");

    rows.forEach(row => {
        const rowText = row.innerText.toLowerCase();
        if (rowText.includes(filter)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

// Open delete confirmation modal
function openCancelModal(productId) {
    document.getElementById('cancelConfirmModal').classList.remove('hidden');
    document.getElementById('productId').value = productId;
}

// Handle confirm delete
function confirmCancel() {
    const id = document.getElementById('productId').value;
    window.location.href = `delete_product.php?id=${id}`;
}

// Close modal by ID
function closeModalById(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('hidden');
}
