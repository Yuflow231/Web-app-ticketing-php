
// class that will handle all the table shenanigans
export class TableManager {
    /**
     * Constructor of the class handler
     * @param tableId reference to the table
     * @param rowsPerPage amount of element per page
     */
    constructor(tableId, rowsPerPage = 5) {
        this.tableBody = document.querySelector(`${tableId} tbody`);
        this.allRows = Array.from(this.tableBody.querySelectorAll('tr'));
        // create an independent copy of all the tables' rows
        this.filteredRows = [...this.allRows];

        this.rowsPerPage = rowsPerPage;
        this.currentPage = 1;

        // get filter elements
        this.searchInput = document.getElementById('search');
        this.filterSelects = document.querySelectorAll('select');

        // get UI elements
        this.paginationText = document.querySelectorAll('.page-header span, .page-footer span');
        this.prevBtns = document.querySelectorAll('.fa-angle-left');
        this.nextBtns = document.querySelectorAll('.fa-angle-right');

        this.init();
    }

    /*
     * Initiate all the event and execute the initial render of the table
     */
    init() {
        // Search Event
        this.searchInput?.addEventListener('input', () => this.handleFilter());

        // Filter Dropdowns
        this.filterSelects.forEach(select => {
            select.addEventListener('change', () => this.handleFilter());
        });

        // Pagination Buttons
        this.prevBtns.forEach(btn => btn.parentElement.onclick = () => this.changePage(-1));
        this.nextBtns.forEach(btn => btn.parentElement.onclick = () => this.changePage(1));

        this.render();
    }

    handleFilter() {
        // get the search field input
        const query = this.searchInput.value.toLowerCase();

        // Get all filter available in the current page, and get their value
        const activeFilters = {}; // init a map for later uses
        this.filterSelects.forEach(select => {
            activeFilters[select.id] = select.value;
        });

        // look at the filter and return in "this.filteredRows" the one to render

        // filter() returns the row solely when it gets true as the returned value (callback shenanigans)
        // when getting false, it skips the current element (in this case the row that doesn't match the filters)
        this.filteredRows = this.allRows.filter(row => {
            // check Search filter (checks all text in the row)
            const matchesSearch = row.innerText.toLowerCase().includes(query); // the whole line is considered a single string here

            // check Select filters, running this for each filter found
            const matchesFilters = Object.keys(activeFilters).every(id => {
                // "All" selected, don't check anything
                if (!activeFilters[id]) return true;

                // Find cell that matches the filter category thanks to the data-label
                const cell = row.querySelector(`[data-label*="${id}" i]`); // i -> avoid case sensitivity
                // if a corresponding cell is found, compare its value with what have been saved inside activeFilters
                return cell ? cell.innerText.trim().toLowerCase() === activeFilters[id].toLowerCase() : true;
            });

            return matchesSearch && matchesFilters;
        });

        // Reset to page 1 on new search
        this.currentPage = 1;
        this.render();
    }

    /**
     * This function handle the page change of the table
     * @param direction value to change pages (-1) to go left, (1) to go right
     */
    changePage(direction) {
        // get the maximum of page available
        const maxPage = Math.ceil(this.filteredRows.length / this.rowsPerPage) || 1;
        const newPage = this.currentPage + direction;

        // condition to avoid going out of scope
        if (newPage >= 1 && newPage <= maxPage) {
            this.currentPage = newPage;
            this.render();
        }
    }

    /**
     * Update the visual of the table
     */
    render() {
        // get index of rows to render in the current page
        const start = (this.currentPage - 1) * this.rowsPerPage;
        const end = start + this.rowsPerPage;

        // create a reference to the rows that should be rendered on this page
        const visibleRows = this.filteredRows.slice(start, end);

        // toggle visibility
        this.allRows.forEach(row => {
            // check if the row is within visibility rows, if not -> hide it
            if (visibleRows.includes(row)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Update Pagination Text
        const maxPage = Math.ceil(this.filteredRows.length / this.rowsPerPage) || 1;
        this.paginationText.forEach(el => {
            el.innerText = `Page ${this.currentPage} of ${maxPage}`;
        });
    }
}