<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThesisLookup | Admin Dashboard</title>
    <link href="/ws1-jamer/public/tailwind.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body class="bg-gradient-to-r from-gray-100 via-white to-gray-100 font-sans text-gray-800">

    <!-- Header -->
    <header class="flex items-center justify-between px-8 py-6 bg-white shadow-md">
        <div class="text-2xl font-bold">Thesis<span class="text-blue-500">Lookup</span> Admin</div>
        <nav>
            <ul class="flex space-x-6 text-lg">
                <li><a href="/ws1-jamer/server/logout.php" class="hover:text-blue-500">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="px-8 py-6">
        <h1 class="text-3xl font-extrabold text-center text-indigo-600 mb-8">Admin Dashboard</h1>
        <!-- Thesis Add Section -->
        <section class="bg-white p-6 rounded-lg shadow-lg max-w-3xl mx-auto mb-8">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Add a New Thesis</h2>
            <form id="thesis-form" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" id="title" name="title" class="w-full p-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label for="university" class="block text-sm font-medium text-gray-700">University</label>
                        <input type="text" id="university" name="university" class="w-full p-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label for="year_of_submission" class="block text-sm font-medium text-gray-700">Year of Submission</label>
                        <input type="number" id="year_of_submission" name="year_of_submission" class="w-full p-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label for="type_of_text" class="block text-sm font-medium text-gray-700">Type</label>
                        <select id="type_of_text" name="type_of_text" class="w-full p-2 border rounded-lg" required>
                            <option value="Dissertation">Dissertation</option>
                            <option value="MA Thesis">MA Thesis</option>
                            <option value="Capstone">Capstone</option>
                            <option value="Research Paper">Research Paper</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full p-2 border rounded-lg"></textarea>
                </div>
                <div>
                    <label for="doi" class="block text-sm font-medium text-gray-700">DOI</label>
                    <input type="text" id="doi" name="doi" class="w-full p-2 border rounded-lg">
                </div>
                <div>
                    <label for="pdf_file" class="block text-sm font-medium text-gray-700">Upload PDF</label>
                    <input type="file" id="pdf_file" name="pdf_file" class="w-full p-2 border rounded-lg">
                </div>
                <div>
                    <label for="published_date" class="block text-sm font-medium text-gray-700">Published Date</label>
                    <input type="date" id="published_date" name="published_date" class="w-full p-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Authors</label>
                    <div id="author-fields" class="space-y-2">
                        <input type="text" name="authors[]" class="w-full p-2 border rounded-lg" placeholder="Enter author name" required>
                    </div>
                    <button type="button" onclick="addAuthorField()" class="mt-2 text-indigo-600 text-sm">+ Add Another Author</button>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tags</label>
                    <div id="tag-fields" class="space-y-2">
                        <input type="text" name="tags[]" class="w-full p-2 border rounded-lg" placeholder="Enter tag" required>
                    </div>
                    <button type="button" onclick="addTagField()" class="mt-2 text-indigo-600 text-sm">+ Add Another Tag</button>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700">Submit Thesis</button>
            </form>
        </section>

        <!-- View Theses Section -->
        <section class="bg-white p-6 rounded-lg shadow-lg h-[50rem] overflow-y-scroll">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">All Theses</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 text-left text-sm">
                    <thead>
                        <tr class="bg-indigo-600 text-white">
                            <th class="p-4">#</th>
                            <th class="p-4">Title</th>
                            <th class="p-4">Author</th>
                            <th class="p-4">Year</th>
                            <th class="p-4">University</th>
                            <th class="p-4">DOI</th>
                            <th class="p-4">Download PDF</th>
                            <th class="p-4">Published Date</th>
                            <th class="p-4">Tags</th>
                            <th class="p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-blue-100 text-gray-600 text-sm py-4 text-center">
        <p>&copy; 2024 ThesisLookup | All Rights Reserved.</p>
    </footer>

    <!-- Modal for Delete Confirmation -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h2 class="text-lg font-semibold mb-4">Are you sure you want to delete this thesis?</h2>
            <div class="flex justify-end space-x-4">
                <button id="cancel-delete" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                <button id="confirm-delete" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
            </div>
        </div>
    </div>

    <div id="edit-thesis-modal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-3xl">
            <form id="edit-thesis-form" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="id" id="edit-id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" id="edit-title" name="title" class="w-full p-2 border rounded-lg">
                    </div>
                    <div>
                        <label for="university" class="block text-sm font-medium text-gray-700">University</label>
                        <input type="text" id="edit-university" name="university" class="w-full p-2 border rounded-lg">
                    </div>
                    <div>
                        <label for="year_of_submission" class="block text-sm font-medium text-gray-700">Year</label>
                        <input type="number" id="edit-year_of_submission" name="year_of_submission" class="w-full p-2 border rounded-lg">
                    </div>
                    <div>
                        <label for="type_of_text" class="block text-sm font-medium text-gray-700">Type</label>
                        <select id="edit-type_of_text" name="type_of_text" class="w-full p-2 border rounded-lg">
                            <option value="Dissertation">Dissertation</option>
                            <option value="MA Thesis">MA Thesis</option>
                            <option value="Capstone">Capstone</option>
                            <option value="Research Paper">Research Paper</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="edit-description" name="description" rows="3" class="w-full p-2 border rounded-lg"></textarea>
                </div>
                <div>
                    <label for="doi" class="block text-sm font-medium text-gray-700">DOI</label>
                    <input type="text" id="edit-doi" name="doi" class="w-full p-2 border rounded-lg">
                </div>
                <div>
                    <label for="pdf_file" class="block text-sm font-medium text-gray-700">Upload PDF</label>
                    <input type="file" id="edit-pdf_file" name="pdf_file" class="w-full p-2 border rounded-lg">
                    <small class="text-gray-500" id="current-pdf-path"></small>
                </div>
                <div>
                    <label for="published_date" class="block text-sm font-medium text-gray-700">Published Date</label>
                    <input type="date" id="edit-published_date" name="published_date" class="w-full p-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Authors</label>
                    <div id="edit-author-fields" class="space-y-2">
                    </div>
                    <button type="button" id="add-author" class="mt-2 text-indigo-600 text-sm">+ Add Another Author</button>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tags</label>
                    <div id="edit-tag-fields" class="space-y-2">
                    </div>
                    <button type="button" id="add-tag" class="mt-2 text-indigo-600 text-sm">+ Add Another Tag</button>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700">Update Thesis</button>
            </form>
        </div>
    </div>

    <script src="/ws1-jamer/src/js/admin_post_thesis_scripts.js"></script>
    <script src="/ws1-jamer/src/js/admin_get_edit_delete_thesis_scripts.js"></script>
</body>

</html>