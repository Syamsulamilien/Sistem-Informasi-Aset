/**
 * SIASET Interactive Table Column Sorting Engine
 * Enables modern, high-speed sorting with up-down (▲▼) header arrows
 * Supports Currency (Rp), Numbers, Indonesian Dates, Alphanumeric, and Status Badges.
 */

const ID_MONTHS = {
    'jan': 0, 'januari': 0,
    'feb': 1, 'februari': 1,
    'mar': 2, 'maret': 2,
    'apr': 3, 'april': 3,
    'mei': 4, 'may': 4,
    'jun': 5, 'juni': 5,
    'jul': 6, 'juli': 6,
    'agu': 7, 'ags': 7, 'agustus': 7, 'aug': 7,
    'sep': 8, 'september': 8,
    'okt': 9, 'oktober': 9, 'oct': 9,
    'nov': 10, 'november': 10,
    'des': 11, 'desember': 11, 'dec': 11
};

/**
 * Parse cell text content into a typed representation for accurate sorting.
 */
function parseCellValue(rawText) {
    const text = (rawText || '').trim();
    if (!text) return { type: 'empty', val: '', raw: '' };

    // 1. Currency parser (e.g. "Rp 15.000.000", "Rp 25.500,00", "Rp. 50000")
    if (/^Rp\.?\s*[\d\.,]+/i.test(text) || /[\d\.,]+\s*Rp/i.test(text)) {
        const cleaned = text.replace(/[^0-9,-]/g, '').replace(',', '.');
        const num = parseFloat(cleaned);
        if (!isNaN(num)) {
            return { type: 'number', val: num, raw: text };
        }
    }

    // 2. Pure Number parser (e.g. "120", "45.5", "1,000")
    const cleanNumber = text.replace(/\./g, '').replace(',', '.');
    if (/^-?\d+(\.\d+)?$/.test(cleanNumber)) {
        const num = parseFloat(cleanNumber);
        if (!isNaN(num)) {
            return { type: 'number', val: num, raw: text };
        }
    }

    // 3. Number with unit parser (e.g. "15 hari", "240 unit", "5 pcs")
    const unitMatch = text.match(/^(-?\d+[\.,]?\d*)\s*[a-zA-Z!]+/);
    if (unitMatch) {
        const num = parseFloat(unitMatch[1].replace(',', '.'));
        if (!isNaN(num)) {
            return { type: 'number', val: num, raw: text };
        }
    }

    // 4. Indonesian / English Date parser (e.g. "13 Okt 2026", "25 Desember 2025")
    const textDateMatch = text.match(/^(\d{1,2})\s+([a-zA-Z]{3,9})\s+(\d{4})/i);
    if (textDateMatch) {
        const day = parseInt(textDateMatch[1], 10);
        const monthKey = textDateMatch[2].toLowerCase();
        const year = parseInt(textDateMatch[3], 10);
        if (ID_MONTHS[monthKey] !== undefined) {
            const date = new Date(year, ID_MONTHS[monthKey], day);
            return { type: 'date', val: date.getTime(), raw: text };
        }
    }

    // 5. ISO or standard Date parser (e.g. "2026-10-13", "13/10/2026")
    if (/^\d{4}-\d{2}-\d{2}/.test(text) || /^\d{2}\/\d{2}\/\d{4}/.test(text)) {
        const parsed = Date.parse(text);
        if (!isNaN(parsed)) {
            return { type: 'date', val: parsed, raw: text };
        }
    }

    // 6. Default Alphanumeric / String
    return { type: 'string', val: text.toLowerCase(), raw: text };
}

/**
 * Comparator function for two parsed cell values.
 */
function compareValues(a, b, direction) {
    if (a.type === 'empty' && b.type !== 'empty') return 1;
    if (b.type === 'empty' && a.type !== 'empty') return -1;
    if (a.type === 'empty' && b.type === 'empty') return 0;

    let res = 0;
    if (a.type === 'number' && b.type === 'number') {
        res = a.val - b.val;
    } else if (a.type === 'date' && b.type === 'date') {
        res = a.val - b.val;
    } else {
        res = String(a.raw).localeCompare(String(b.raw), 'id', {
            numeric: true,
            sensitivity: 'base'
        });
    }

    return direction === 'asc' ? res : -res;
}

/**
 * Initialize sorting on a single table element.
 */
export function initTable(table) {
    if (!table || table.dataset.sortInitialized === 'true') return;
    if (table.hasAttribute('data-no-sort')) return;

    const thead = table.querySelector('thead');
    const tbody = table.querySelector('tbody');
    if (!thead || !tbody) return;

    const headerRow = thead.querySelector('tr:last-child') || thead.querySelector('tr');
    if (!headerRow) return;

    const ths = Array.from(headerRow.querySelectorAll('th'));
    if (ths.length === 0) return;

    table.dataset.sortInitialized = 'true';

    ths.forEach((th, colIndex) => {
        const text = (th.textContent || '').trim().toLowerCase();

        // Skip non-sortable columns like "Aksi", "Action", "Foto", or checkbox headers
        if (
            th.hasAttribute('data-no-sort') ||
            th.getAttribute('data-sortable') === 'false' ||
            text === 'aksi' ||
            text === 'action' ||
            text === 'tindakan' ||
            text === 'foto' ||
            th.querySelector('input[type="checkbox"]')
        ) {
            return;
        }

        th.classList.add('sortable-header');
        th.setAttribute('title', 'Klik untuk mengurutkan (Atas: A-Z / Terkecil, Bawah: Z-A / Terbesar)');

        // Wrap existing content and append modern Up-Down SVG arrows if not present
        if (!th.querySelector('.sort-arrows-wrapper')) {
            const originalHtml = th.innerHTML.trim();
            const isCenter = th.classList.contains('text-center');
            const isRight = th.classList.contains('text-right');
            const justifyClass = isCenter ? 'justify-center' : (isRight ? 'justify-end' : 'justify-between');

            th.innerHTML = `
                <div class="inline-flex items-center gap-1.5 w-full cursor-pointer select-none group/sort ${justifyClass}">
                    <span class="sort-title">${originalHtml}</span>
                    <span class="sort-arrows-wrapper inline-flex flex-col items-center justify-center ml-1.5 w-3.5 h-3.5 text-gray-400 group-hover/sort:text-blue-500 flex-shrink-0 transition-colors pointer-events-none">
                        <svg class="sort-asc w-2 h-2 -mb-0.5 text-gray-400 transition-all duration-150" viewBox="0 0 10 6" fill="currentColor">
                            <path d="M5 0.5L9.5 5.5H0.5L5 0.5Z"/>
                        </svg>
                        <svg class="sort-desc w-2 h-2 text-gray-400 transition-all duration-150" viewBox="0 0 10 6" fill="currentColor">
                            <path d="M5 5.5L0.5 0.5H9.5L5 5.5Z"/>
                        </svg>
                    </span>
                </div>
            `;
        }

        // Click handler to sort table
        th.addEventListener('click', (e) => {
            // Prevent interference if a click happened on an interactive element inside th
            if (e.target.closest('a, button, input, select')) return;

            const rows = Array.from(tbody.querySelectorAll('tr'));
            // If table has empty state row (usually has colspan), do nothing
            const validRows = rows.filter(row => !row.querySelector('td[colspan]'));
            if (validRows.length <= 1) return;

            const isAsc = th.classList.contains('sorted-asc');
            const direction = isAsc ? 'desc' : 'asc';

            // Reset other headers in this table
            ths.forEach(otherTh => {
                otherTh.classList.remove('sorted-asc', 'sorted-desc');
            });

            // Set state on clicked header
            th.classList.add(direction === 'asc' ? 'sorted-asc' : 'sorted-desc');

            // Sort rows in memory
            validRows.sort((rowA, rowB) => {
                const cellA = rowA.children[colIndex];
                const cellB = rowB.children[colIndex];
                if (!cellA || !cellB) return 0;

                const valA = parseCellValue(cellA.innerText || cellA.textContent);
                const valB = parseCellValue(cellB.innerText || cellB.textContent);
                return compareValues(valA, valB, direction);
            });

            // Re-attach sorted rows into tbody
            const fragment = document.createDocumentFragment();
            validRows.forEach(row => fragment.appendChild(row));
            tbody.appendChild(fragment);

            // Re-index sequential 'No' column if present at column 0
            const firstHeader = ths[0]?.textContent.trim().toLowerCase();
            if (firstHeader === 'no' || firstHeader === '#') {
                validRows.forEach((row, idx) => {
                    const firstCell = row.children[0];
                    if (firstCell && /^\d+$/.test(firstCell.textContent.trim())) {
                        firstCell.textContent = idx + 1;
                    }
                });
            }
        });
    });
}

/**
 * Global initialization for all tables on the page.
 */
export function initAllSortableTables() {
    document.querySelectorAll('table').forEach(table => {
        initTable(table);
    });
}

// Auto-run on DOM ready
if (typeof document !== 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllSortableTables);
    } else {
        initAllSortableTables();
    }
}
