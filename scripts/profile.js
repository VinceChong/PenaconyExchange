function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById("profilePicturePreview");
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleSection(id) {
    document.querySelectorAll('.profileCard.hidden').forEach(el => el.style.display = 'none');
    const target = document.getElementById(id);
    if (target) target.style.display = 'block';
}