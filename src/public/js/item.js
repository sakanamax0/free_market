document.getElementById('like-button').addEventListener('click', function () {
    fetch(`/item/${itemId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.liked) {
                this.classList.add('liked');
            } else {
                this.classList.remove('liked');
            }
            this.querySelector('span').textContent = `❤️ ${data.likesCount}`;
        });
});
