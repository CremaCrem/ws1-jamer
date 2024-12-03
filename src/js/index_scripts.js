const featureCards = document.querySelectorAll('.hover\\:scale-105');
featureCards.forEach(card => {
    card.addEventListener('mouseover', () => {
        card.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.2)';
    });
    card.addEventListener('mouseout', () => {
        card.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
    });
});

function fetchSuggestions(query) {
    const suggestionsBox = document.getElementById('suggestions-box');
    suggestionsBox.innerHTML = ''; 

    if (query.length < 2) return;

    fetch(`/ws1-jamer/server/get_suggestions.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const maxSuggestions = 3; 
                const limitedSuggestions = data.suggestions.slice(0, maxSuggestions);

                limitedSuggestions.forEach(item => {
                    const suggestion = document.createElement('div');
                    suggestion.className = 'py-4 px-6 hover:bg-gray-200 cursor-pointer border-b border-gray-300';

                    suggestion.innerHTML = `
                        <div class="font-bold text-blue-600">${item.title}</div>
                        <div class="text-gray-500 text-sm">
                            Authors: ${item.authors || 'N/A'}
                        </div>
                        <div class="text-gray-500 text-sm">
                            Tags: ${item.tags || 'N/A'}
                        </div>
                        <div class="text-gray-500 text-sm">
                            Published on: ${item.publish_date || 'N/A'}
                        </div>
                    `;

                    suggestion.onclick = () => {
                        window.location.href = `/ws1-jamer/src/components/guest_thesis_detail.php?id=${item.id}`; 
                    };

                    suggestionsBox.appendChild(suggestion);
                });
            }
        });
}

document.addEventListener('click', (event) => {
    const searchContainer = document.getElementById('search-container');
    const suggestionsBox = document.getElementById('suggestions-box');

    if (!searchContainer.contains(event.target)) {
        suggestionsBox.innerHTML = ''; 
    }
});

document.getElementById('search-input').addEventListener('click', (event) => {
    event.stopPropagation();
});

const searchForm = document.querySelector('form');

searchForm.addEventListener('submit', (event) => {
    const searchQuery = document.getElementById('search-input').value.trim();

    if (searchQuery.length === 0) {
        event.preventDefault();
        alert('Please enter a search term.');
    }
});
