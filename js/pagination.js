function paginate(tableBodySelector, rowsPerPage = 5) {
    const tbody = document.querySelector(tableBodySelector);
    if (!tbody) return;

    const rows = Array.from(tbody.querySelectorAll('tr'));
    const totalRows = rows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    let currentPage = 1;

    const paginationContainer = document.createElement('div');
    paginationContainer.className = 'pagination-controls mt-3 d-flex justify-content-center gap-2';

    function showPage(page) {
        currentPage = page;
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach((row, i) => {
            row.style.display = i >= start && i < end ? '' : 'none';
        });

        renderPagination();
    }

    function renderPagination() {
        paginationContainer.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}`;
            btn.addEventListener('click', () => showPage(i));
            paginationContainer.appendChild(btn);
        }
    }

    // Insert pagination after the table
    tbody.closest('table').after(paginationContainer);

    showPage(1);
}
