
document.getElementById('thesis-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
        const response = await fetch('/ws1-jamer/server/post_thesis.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.success) {
            Toastify({
                text: result.message,
                backgroundColor: "green",
                className: "info",
            }).showToast();
            this.reset(); 
            location.reload(); 
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        Toastify({
            text: `Error: ${error.message}`,
            backgroundColor: "red",
            className: "error",
        }).showToast();
    }
});

function addAuthorField() {
    const container = document.getElementById('author-fields');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'authors[]';
    input.className = 'w-full p-2 border rounded-lg';
    input.placeholder = 'Enter author name';
    container.appendChild(input);
}

function addTagField() {
    const container = document.getElementById('tag-fields');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'tags[]';
    input.className = 'w-full p-2 border rounded-lg';
    input.placeholder = 'Enter tag';
    container.appendChild(input);
}

