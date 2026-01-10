/**
 * Athletes Table Component
 * Client-side search, filter, and sorting functionality
 */
(function() {
    'use strict';

    // Initialize all athlete tables on the page
    function init() {
        const tables = document.querySelectorAll('[data-athletes-table]');
        tables.forEach(table => new AthletesTable(table));
    }

    class AthletesTable {
        constructor(container) {
            this.container = container;
            this.view = container.dataset.view || 'table';
            this.rows = container.querySelectorAll('[data-athlete-row]');
            this.searchInput = container.querySelector('[data-athletes-search]');
            this.filterSelects = container.querySelectorAll('[data-athletes-filter]');
            this.sortButtons = container.querySelectorAll('[data-athletes-sort]');

            this.currentSort = { field: null, direction: 'asc' };
            this.currentFilters = {};

            this.bindEvents();
        }

        bindEvents() {
            // Search
            if (this.searchInput) {
                this.searchInput.addEventListener('input',
                    this.debounce(() => this.applyFilters(), 200)
                );
            }

            // Filters
            this.filterSelects.forEach(select => {
                select.addEventListener('change', () => this.applyFilters());
            });

            // Sorting
            this.sortButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const field = button.dataset.athletesSort;
                    this.sort(field);
                });
            });
        }

        applyFilters() {
            const searchTerm = this.searchInput ? this.searchInput.value.toLowerCase().trim() : '';

            // Gather filter values
            const filters = {};
            this.filterSelects.forEach(select => {
                const filterType = select.dataset.athletesFilter;
                const value = select.value.toLowerCase();
                if (value) {
                    filters[filterType] = value;
                }
            });

            let visibleCount = 0;

            this.rows.forEach(row => {
                let visible = true;

                // Search filter - searches across name, position, school
                if (searchTerm) {
                    const name = row.dataset.name || '';
                    const position = row.dataset.position || '';
                    const school = row.dataset.school || '';
                    const searchableText = `${name} ${position} ${school}`;

                    if (!searchableText.includes(searchTerm)) {
                        visible = false;
                    }
                }

                // Apply dropdown filters
                for (const [filterType, filterValue] of Object.entries(filters)) {
                    const rowValue = row.dataset[filterType] || '';
                    if (!rowValue.includes(filterValue)) {
                        visible = false;
                        break;
                    }
                }

                // Show/hide row
                row.style.display = visible ? '' : 'none';
                if (visible) visibleCount++;
            });

            // Show "no results" message if needed
            this.updateNoResultsMessage(visibleCount === 0);
        }

        sort(field) {
            // Toggle direction if same field
            if (this.currentSort.field === field) {
                this.currentSort.direction = this.currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                this.currentSort.field = field;
                this.currentSort.direction = 'asc';
            }

            const direction = this.currentSort.direction;
            const rowsArray = Array.from(this.rows);

            rowsArray.sort((a, b) => {
                let aVal = a.dataset[field] || '';
                let bVal = b.dataset[field] || '';

                // Handle numeric values (like NIL valuation)
                if (field === 'nil') {
                    aVal = parseFloat(aVal) || 0;
                    bVal = parseFloat(bVal) || 0;
                    return direction === 'asc' ? aVal - bVal : bVal - aVal;
                }

                // String comparison
                aVal = aVal.toLowerCase();
                bVal = bVal.toLowerCase();

                if (aVal < bVal) return direction === 'asc' ? -1 : 1;
                if (aVal > bVal) return direction === 'asc' ? 1 : -1;
                return 0;
            });

            // Re-append rows in sorted order
            const parent = this.rows[0]?.parentNode;
            if (parent) {
                rowsArray.forEach(row => parent.appendChild(row));
            }

            // Update sort button states
            this.updateSortButtons(field);
        }

        updateSortButtons(activeField) {
            this.sortButtons.forEach(button => {
                const field = button.dataset.athletesSort;
                const isActive = field === activeField;

                // Remove existing indicators
                button.classList.remove('sort-asc', 'sort-desc');

                if (isActive) {
                    button.classList.add(`sort-${this.currentSort.direction}`);
                }
            });
        }

        updateNoResultsMessage(show) {
            let noResults = this.container.querySelector('.athletes-no-results');

            if (show) {
                if (!noResults) {
                    noResults = document.createElement('div');
                    noResults.className = 'athletes-no-results text-center py-8 text-gray-500';
                    noResults.textContent = 'No athletes found matching your search.';

                    // Find the table/list container and append after it
                    const listContainer = this.container.querySelector('.space-y-4, table, .grid');
                    if (listContainer) {
                        listContainer.parentNode.insertBefore(noResults, listContainer.nextSibling);
                    }
                }
                noResults.style.display = '';
            } else if (noResults) {
                noResults.style.display = 'none';
            }
        }

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
