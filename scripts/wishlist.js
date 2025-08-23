// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.wishlist-item');
    
    items.forEach(item => {
        const title = item.querySelector('.game-title').textContent.toLowerCase();
        
        if (title.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
});

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function(e) {
    const checkboxes = document.querySelectorAll('.game-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = e.target.checked;
    });
});

// Update select all checkbox when individual checkboxes change
const checkboxes = document.querySelectorAll('.game-checkbox');
checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const allChecked = document.querySelectorAll('.game-checkbox:checked').length === checkboxes.length;
        document.getElementById('selectAll').checked = allChecked;
    });
});