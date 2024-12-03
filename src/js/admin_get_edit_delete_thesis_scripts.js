let thesisToDelete = null;

document.addEventListener("DOMContentLoaded", () => {
    fetchTheses();

    async function fetchTheses() {
        try {
            const response = await fetch('/ws1-jamer/server/get_thesis.php');
            const result = await response.json();
            if (result.success) {
                renderTheses(result.data);
            } else {
                console.error("Error fetching theses:", result.message);
                showToast("Failed to load theses.", "error");
            }
        } catch (error) {
            console.error("Fetch error:", error);
            showToast("Something went wrong. Please try again later.", "error");
        }
    }

    function renderTheses(theses) {
        const tbody = document.querySelector("table tbody");
        tbody.innerHTML = "";

        if (theses.length === 0) {
            tbody.innerHTML = `<tr><td colspan="10" class="text-center p-4">No theses found.</td></tr>`;
            return;
        }

        theses.forEach((thesis, index) => {
            const tags = typeof thesis.tags === 'string' && thesis.tags.length > 0 ? thesis.tags : "N/A";

            const row = `
                <tr class="odd:bg-white even:bg-gray-100">
                    <td class="p-4">${index + 1}</td>
                    <td class="p-4">${thesis.title}</td>
                    <td class="p-4">${thesis.authors || "N/A"}</td>
                    <td class="p-4">${thesis.year_of_submission}</td>
                    <td class="p-4">${thesis.university || "N/A"}</td>
                    <td class="p-4">${thesis.doi || "N/A"}</td>
                    <td class="p-4">
                        <a href="/ws1-jamer/server/${thesis.pdf_file_path}" class="text-blue-600 hover:underline" download>Download PDF</a>
                    </td>
                    <td class="p-4">${thesis.publish_date || "N/A"}</td>
                    <td class="p-4">${tags}</td> <!-- Tags column -->
                    <td class="p-4 space-x-4">
                        <button class="text-yellow-500 hover:text-yellow-600 hover:underline" onclick="editThesis(${thesis.id})">Edit</button>
                        <button class="text-red-600 hover:text-red-700 hover:underline" onclick="openDeleteModal(${thesis.id})">Delete</button>
                    </td>
                </tr>`;
            tbody.insertAdjacentHTML("beforeend", row);
        });
    }

    // Function to show the delete modal
    window.openDeleteModal = function(id) {
        thesisToDelete = id;
        document.getElementById('delete-modal').classList.remove('hidden');
    };

    // Function to confirm deletion
    document.getElementById('confirm-delete').addEventListener('click', async () => {
        try {
            const response = await fetch('/ws1-jamer/server/delete_thesis.php', {
                method: 'POST',
                body: JSON.stringify({ id: thesisToDelete }),
                headers: { 'Content-Type': 'application/json' }
            });

            const result = await response.json();

            if (result.success) {
                showToast("Thesis deleted successfully.", "success");
                fetchTheses(); 
            } else {
                showToast("Failed to delete thesis.", "error");
            }
        } catch (error) {
            console.error("Error deleting thesis:", error);
            showToast("Failed to delete thesis. Please try again later.", "error");
        }

        closeModal();
    });

    // Function to cancel deletion
    document.getElementById('cancel-delete').addEventListener('click', closeModal);

    // Function to close the modal
    function closeModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }

    function showToast(message, type = "info") {
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: type === "error" ? "#e3342f" : "#38c172",
        }).showToast();
    }

    window.editThesis = async function (id) {
        try {
            const response = await fetch(`/ws1-jamer/server/get_thesis.php?id=${id}`);
            const result = await response.json();
            if (result.success) {
                const thesis = result.data[0];
    
                // Populate the form with existing thesis data
                document.getElementById('edit-id').value = thesis.id;
                document.getElementById('edit-title').value = thesis.title;
                document.getElementById('edit-university').value = thesis.university || ''; 
                document.getElementById('edit-year_of_submission').value = thesis.year_of_submission || '';
                document.getElementById('edit-type_of_text').value = thesis.type_of_text || '';
                document.getElementById('edit-description').value = thesis.description || '';
                document.getElementById('edit-doi').value = thesis.doi || '';
                document.getElementById('edit-published_date').value = thesis.publish_date || '';
    
                // Populate authors
                const authorFields = document.getElementById('edit-author-fields');
                authorFields.innerHTML = ''; // Clear existing authors
                const authors = thesis.authors.split(',').map(author => author.trim());
                authors.forEach(author => {
                    const authorInput = document.createElement('input');
                    authorInput.type = 'text';
                    authorInput.name = 'authors[]';
                    authorInput.className = 'w-full p-2 border rounded-lg';
                    authorInput.value = author;
                    authorFields.appendChild(authorInput);
                });
    
                // Populate tags
                const tagFields = document.getElementById('edit-tag-fields');
                tagFields.innerHTML = ''; // Clear existing tags
                const tags = thesis.tags.split(',').map(tag => tag.trim());
                tags.forEach(tag => {
                    const tagInput = document.createElement('input');
                    tagInput.type = 'text';
                    tagInput.name = 'tags[]';
                    tagInput.className = 'w-full p-2 border rounded-lg';
                    tagInput.value = tag;
                    tagFields.appendChild(tagInput);
                });
    
                // Attach custom handlers for add buttons in the edit modal
                document.getElementById('add-author').addEventListener('click', function() {
                    const container = document.getElementById('edit-author-fields');
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'authors[]';
                    input.className = 'w-full p-2 border rounded-lg';
                    input.placeholder = 'Enter author name';
                    container.appendChild(input);
                });
    
                document.getElementById('add-tag').addEventListener('click', function() {
                    const container = document.getElementById('edit-tag-fields');
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'tags[]';
                    input.className = 'w-full p-2 border rounded-lg';
                    input.placeholder = 'Enter tag';
                    container.appendChild(input);
                });
    
                document.getElementById('edit-thesis-modal').classList.remove('hidden');
            } else {
                alert('Failed to fetch thesis details.');
            }
        } catch (error) {
            console.error('Error fetching thesis:', error);
            alert('Error fetching thesis details.');
        }
    };
    
    // Form submission to update thesis
    document.getElementById('edit-thesis-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
    
        try {
            const response = await fetch('/ws1-jamer/server/edit_thesis.php?id=' + formData.get('id'), {
                method: 'POST',
                body: formData
            });
    
            const result = await response.json();
            if (result.success) {
                alert('Thesis updated successfully.');
                location.reload(); 
                closeEditModal();
            } else {
                alert('Failed to update thesis.');
            }
        } catch (error) {
            console.error('Error updating thesis:', error);
            alert('Error updating thesis.');
        }
    });
    
    function closeEditModal() {
        document.getElementById('edit-thesis-modal').classList.add('hidden');
    }
});
