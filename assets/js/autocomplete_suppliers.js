document.addEventListener('DOMContentLoaded', () => {
    const supplierInput = document.getElementById('supplier-input');
    const suggestionsBox = document.getElementById('autocomplete-suggestions');

    if (!supplierInput || !suggestionsBox) return;

    supplierInput.addEventListener('input', async () => {
        const query = supplierInput.value.trim();

        if (query.length < 1) {
            suggestionsBox.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`/inventory-system/ajax/search_suppliers.php?q=${encodeURIComponent(query)}`);
            const suggestions = await response.json();

            if (suggestions.length > 0) {
                renderSuggestions(suggestions);
            } else {
                suggestionsBox.style.display = 'none';
            }
        } catch (error) {
            console.error('Error fetching suggestions:', error);
        }
    });

    function renderSuggestions(suggestions) {
        suggestionsBox.innerHTML = '';
        suggestions.forEach(name => {
            const div = document.createElement('div');
            div.textContent = name;
            div.classList.add('suggestion-item');
            div.addEventListener('click', () => {
                supplierInput.value = name;
                suggestionsBox.style.display = 'none';
            });
            suggestionsBox.appendChild(div);
        });
        suggestionsBox.style.display = 'block';
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!suggestionsBox.contains(e.target) && e.target !== supplierInput) {
            suggestionsBox.style.display = 'none';
        }
    });
});
