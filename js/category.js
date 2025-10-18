
function openCancelModal(categoryId) {
    document.getElementById('cancelConfirmModal').style.display = 'block';
    document.getElementById('categoryId').value = categoryId;
}

function confirmCancel() {
    const id = document.getElementById('categoryId').value;
    window.location.href = `category.php?delete=${id}`;
}

function closeModalById(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'none';
}

window.onload = function () {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('deleted') === '1') {
        document.getElementById('successModal').style.display = 'block';

        // Clean URL after showing modal
        setTimeout(() => {
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }, 500);
    }
};
window.onload = function () {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Check for 'added=1' parameter to show success modal for added category
    if (urlParams.get('added') === '1') {
        // Show the success modal
        document.getElementById('successModal').style.display = 'block';

        // Remove the 'added' query parameter from the URL after showing the modal
        setTimeout(() => {
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }, 500);
    }

    // Check for 'deleted=1' parameter to show success modal for deleted category
    if (urlParams.get('deleted') === '1') {
        document.getElementById('successModal').style.display = 'block';
        setTimeout(() => {
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }, 500);
    }
};

function closeModalById(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'none';
}
