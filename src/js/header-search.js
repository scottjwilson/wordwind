/**
 * Header Search Autocomplete
 * Filters preloaded athlete data as user types
 */
(function () {
  "use strict";

  const DEBOUNCE_MS = 150;
  const MIN_CHARS = 2;
  const MAX_RESULTS = 8;

  class HeaderSearch {
    constructor(container) {
      this.container = container;
      this.input = container.querySelector("[data-header-search-input]");

      if (!this.input) {
        return;
      }

      // Create dropdown in body to avoid layout issues with sticky header
      this.dropdown = document.createElement("div");
      this.dropdown.className =
        "hidden fixed bg-white border border-gray-200 rounded-md shadow-lg z-[9999] max-h-96 overflow-y-auto";
      this.dropdown.setAttribute("data-header-search-dropdown", "");

      this.resultsList = document.createElement("div");
      this.resultsList.setAttribute("data-header-search-results", "");
      this.dropdown.appendChild(this.resultsList);
      document.body.appendChild(this.dropdown);

      this.athletes = wordwindSearch.athletes || [];
      this.isOpen = false;
      this.selectedIndex = -1;

      this.bindEvents();
    }

    bindEvents() {
      this.input.addEventListener(
        "input",
        this.debounce(() => this.onInput(), DEBOUNCE_MS),
      );
      this.input.addEventListener("focus", () => this.onFocus());
      this.input.addEventListener("keydown", (e) => this.onKeydown(e));

      // Close dropdown when clicking outside
      document.addEventListener("click", (e) => {
        if (!this.container.contains(e.target)) {
          this.closeDropdown();
        }
      });

      // Close on escape
      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
          this.closeDropdown();
          this.input.blur();
        }
      });
    }

    onInput() {
      const query = this.input.value.trim().toLowerCase();

      if (query.length < MIN_CHARS) {
        this.closeDropdown();
        return;
      }

      this.search(query);
    }

    onFocus() {
      const query = this.input.value.trim();
      if (query.length >= MIN_CHARS && this.resultsList.children.length > 0) {
        this.openDropdown();
      }
    }

    onKeydown(e) {
      if (!this.isOpen) {
        return;
      }

      const items = this.resultsList.querySelectorAll("[data-search-result]");

      switch (e.key) {
        case "ArrowDown":
          e.preventDefault();
          this.selectedIndex = Math.min(
            this.selectedIndex + 1,
            items.length - 1,
          );
          this.updateSelection(items);
          break;

        case "ArrowUp":
          e.preventDefault();
          this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
          this.updateSelection(items);
          break;

        case "Enter":
          e.preventDefault();
          if (this.selectedIndex >= 0 && items[this.selectedIndex]) {
            const link = items[this.selectedIndex].querySelector("a");
            if (link) {
              window.location.href = link.href;
            }
          } else {
            // Submit the form for full search
            this.container.closest("form")?.submit();
          }
          break;
      }
    }

    updateSelection(items) {
      items.forEach((item, index) => {
        if (index === this.selectedIndex) {
          item.classList.add("bg-gray-100");
        } else {
          item.classList.remove("bg-gray-100");
        }
      });

      // Scroll selected item into view within dropdown only
      if (this.selectedIndex >= 0 && items[this.selectedIndex]) {
        const item = items[this.selectedIndex];
        const dropdown = this.dropdown;
        const itemTop = item.offsetTop;
        const itemBottom = itemTop + item.offsetHeight;
        const scrollTop = dropdown.scrollTop;
        const dropdownHeight = dropdown.clientHeight;

        if (itemTop < scrollTop) {
          dropdown.scrollTop = itemTop;
        } else if (itemBottom > scrollTop + dropdownHeight) {
          dropdown.scrollTop = itemBottom - dropdownHeight;
        }
      }
    }

    search(query) {
      // Filter athletes locally
      const results = this.athletes
        .filter((athlete) => {
          const searchableText = [
            athlete.name,
            athlete.position,
            athlete.school,
          ]
            .join(" ")
            .toLowerCase();

          return searchableText.includes(query);
        })
        .slice(0, MAX_RESULTS);

      this.renderResults(results, query);
    }

    renderResults(athletes, query) {
      this.selectedIndex = -1;
      this.resultsList.innerHTML = "";

      if (athletes.length === 0) {
        this.resultsList.innerHTML = `
                    <div class="px-4 py-3 text-sm text-gray-500">
                        No athletes found for "${this.escapeHtml(query)}"
                    </div>
                `;
        this.openDropdown();
        return;
      }

      const fragment = document.createDocumentFragment();

      athletes.forEach((athlete) => {
        const item = document.createElement("div");
        item.setAttribute("data-search-result", "");
        item.className = "hover:bg-gray-100 transition-colors";

        item.innerHTML = `
                    <a href="${this.escapeHtml(athlete.url)}" class="flex items-center gap-3 px-4 py-3">
                        <div class="flex-shrink-0">
                            ${
                              athlete.thumbnail
                                ? `<img src="${this.escapeHtml(athlete.thumbnail)}" alt="" class="w-10 h-10 rounded-full object-cover">`
                                : `<div class="w-10 h-10 rounded-full bg-gradient-to-br from-bsj-navy to-blue-900 flex items-center justify-center">
                                     <span class="text-white text-xs font-bold">${this.escapeHtml(athlete.name.substring(0, 2).toUpperCase())}</span>
                                   </div>`
                            }
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-gray-900 truncate">${this.escapeHtml(athlete.name)}</div>
                            <div class="text-xs text-gray-500">
                                ${athlete.position ? this.escapeHtml(athlete.position) : ""}
                                ${athlete.position && athlete.school ? " · " : ""}
                                ${athlete.school ? this.escapeHtml(athlete.school) : ""}
                            </div>
                        </div>
                        ${
                          athlete.nil_valuation
                            ? `<div class="flex-shrink-0 text-sm font-bold text-bsj-navy">${this.escapeHtml(athlete.nil_valuation)}</div>`
                            : ""
                        }
                    </a>
                `;

        fragment.appendChild(item);
      });

      this.resultsList.appendChild(fragment);
      this.openDropdown();
    }

    openDropdown() {
      // Position dropdown below input using fixed positioning
      const inputRect = this.input.getBoundingClientRect();
      this.dropdown.style.top = `${inputRect.bottom + 4}px`;
      this.dropdown.style.left = `${inputRect.left}px`;
      this.dropdown.style.width = `${this.container.offsetWidth}px`;

      this.dropdown.classList.remove("hidden");
      this.isOpen = true;
    }

    closeDropdown() {
      this.dropdown.classList.add("hidden");
      this.isOpen = false;
      this.selectedIndex = -1;
    }

    escapeHtml(text) {
      const div = document.createElement("div");
      div.textContent = text;
      return div.innerHTML;
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

  // Initialize
  function init() {
    const searchContainers = document.querySelectorAll("[data-header-search]");
    searchContainers.forEach((container) => new HeaderSearch(container));
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
