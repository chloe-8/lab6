

function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase(); // Convert input to lowercase
    const rows = document.querySelectorAll("#salesTable tbody tr"); // Get all rows in the tbody

    rows.forEach(row => {
        const productName = row.querySelector('td:nth-child(1)').textContent.toLowerCase(); // Get product name from the first column

        if (productName.includes(filter)) { // If the product name includes the search term
            row.style.display = ""; // Show the row
        } else {
            row.style.display = "none"; // Hide the row
        }
    });
}

const rows = document.querySelectorAll(".salesRow");
const rowsPerPage = 10;
let currentPage = 1;

function paginate() {
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    rows.forEach((row, index) => {
        if (index >= start && index < end) {
            row.style.display = "";
            row.querySelector('.rowNumber').textContent = index + 1;
        } else {
            row.style.display = "none";
        }
    });

    renderPaginationControls(totalPages);
}

function renderPaginationControls(totalPages) {
    let pagination = document.getElementById("pagination");
    pagination.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        btn.className = (i === currentPage) ? "active-page" : "";
        btn.onclick = () => {
            currentPage = i;
            paginate();
        };
        pagination.appendChild(btn);
    }
}

window.addEventListener("DOMContentLoaded", paginate);

function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    let visibleRows = [];

    rows.forEach(row => {
        const productName = row.cells[1].textContent.toLowerCase();
        if (productName.includes(input)) {
            row.style.display = "";
            visibleRows.push(row);
        } else {
            row.style.display = "none";
        }
    });

    // Reapply pagination to filtered rows
    currentPage = 1;
    if (input === "") {
        paginate();
    } else {
        visibleRows.forEach((row, index) => {
            row.style.display = "";
            row.querySelector('.rowNumber').textContent = index + 1;
        });
        document.getElementById("pagination").innerHTML = "";
    }
}