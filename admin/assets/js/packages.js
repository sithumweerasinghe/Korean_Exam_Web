document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('addPackageOptionsForm');
    const saveButton = document.getElementById('save-button');
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const optionName = document.getElementById('optionName').value;
        const csrfToken = saveButton.getAttribute('data-token');
        if (!optionName) {
            showToast("error", 'Option Name is required.');
            return;
        }
        const payload = {
            id: optionId,
            name: optionName,
            csrf_token: csrfToken
        };
        fetch('../api/admin/add_package_option.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showToast("success", data.success);
                    location.reload();
                } else {
                    showToast("error", data.error || 'Failed to add package option.');
                }
            })
            .catch((error) => {
                showToast("error", 'Failed to add package option.');
            });
    });
}); 

document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-option-btn");
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    editButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const optionId = this.getAttribute("data-id");
            const optionName = this.getAttribute("data-option-name");
            document.getElementById("optionId").value = optionId;
            console.log(document.getElementById("editOptionName").value)
            document.getElementById("editOptionName").value = optionName;
            console.log(document.getElementById("editOptionName").value)
        });
    });
    document.getElementById("editPackageOptionForm").addEventListener("submit", async function (e) {
        e.preventDefault();
        const optionId = document.getElementById("optionId").value;
        const optionName = document.getElementById("editOptionName").value;

        if (!optionId || !optionName) {
            showToast("error", 'Option name is required.');
            return;
        }
        const requestData = {
            id: optionId,
            name: optionName,
            csrf_token: csrfToken,
        };
        try {
            const response = await fetch("../api/admin/edit_package_option.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(requestData),
            });
            const result = await response.json();
            if (!response.ok) {
                showToast("error", result.error || 'Failed to update package option.');
                return;
            }
            if (result.success) {
                showToast("success", 'Package option updated successfully.');
                location.reload();
            } else {
                showToast("error", result.error);
            }
        } catch (error) {
            console.error("An error occurred while updating the package option:", error);
            alert("An error occurred. Please try again.");
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('addPackageForm');
    const saveButton = document.getElementById('save-button');
    const paperContainer = document.getElementById("paperContainer");
    const addPaperBtn = document.getElementById("addPaperBtn");

    addPaperBtn.addEventListener("click", () => {
        const paperIndex = paperContainer.children.length;

        const paperDiv = document.createElement("div");
        paperDiv.classList.add("paper-details", "d-flex", "align-items-center", "mb-3");
        paperDiv.setAttribute("data-index", paperIndex);
        paperDiv.style.gap = "10px";

        let optionsHTML = '<option value="">Select an Option</option>';
        papers.forEach(paper => {
            optionsHTML += `<option value="${paper.paper_id}">${paper.paper_name}</option>`;
        });

        paperDiv.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <label for="paperOption-${paperIndex}" class="form-label">Select Option</label>
                <select class="form-select" id="paperOption-${paperIndex}">
                    ${optionsHTML}
                </select>
            </div>
            <button type="button" class="btn btn-danger remove-paper-btn">Remove</button>
        `;

        paperContainer.appendChild(paperDiv);

        paperDiv.querySelector(".remove-paper-btn").addEventListener("click", () => {
            paperDiv.remove();
        });
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const packageName = document.getElementById('name').value.trim();
        const packageDescription = document.getElementById('des').value.trim();
        const packagePrice = document.getElementById('price').value.trim();
        const validMonths = document.getElementById('months').value.trim();
        const csrfToken = saveButton.getAttribute('data-token');

        if (!packageName) {
            showToast("error", 'Package Name is required.');
            return;
        }

        if (!packageDescription) {
            showToast("error", 'Package Description is required.');
            return;
        }

        if (!packagePrice || isNaN(packagePrice) || packagePrice <= 0) {
            showToast("error", 'Valid Package Price is required.');
            return;
        }

        if (!validMonths || isNaN(validMonths) || validMonths <= 0) {
            showToast("error", 'Valid Months is required.');
            return;
        }

        const selectedOptions = Array.from(document.querySelectorAll('.form-check-input:checked')).map(input => input.value);
        if (selectedOptions.length === 0) {
            showToast("error", 'At least one package option must be selected.');
            return;
        }

        const paperDetails = [];
        const paperDivs = paperContainer.querySelectorAll(".paper-details");
        for (const paperDiv of paperDivs) {
            const select = paperDiv.querySelector('select');
            const selectedPaperOption = select.value;
            if (!selectedPaperOption) {
                showToast("error", 'All added papers must have a selected option.');
                return;
            }
            paperDetails.push(selectedPaperOption);
        }

        const payload = {
            package_name: packageName,
            package_description: packageDescription,
            package_price: packagePrice,
            valid_months: validMonths,
            package_options: selectedOptions,
            package_papers: paperDetails,
            csrf_token: csrfToken
        };

        fetch('../api/admin/add_package.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showToast("success", data.success);
                    location.reload();
                } else {
                    showToast("error", data.error || 'Failed to add package.');
                }
            })
            .catch((error) => {
                showToast("error", 'Failed to add package.');
                console.error(error);
            });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-btn");
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const editAddPaperBtn = document.getElementById("editAddPaperBtn");
    const papersContainer = document.getElementById("editPackagePapers");

    editAddPaperBtn.addEventListener("click", () => {
        const paperIndex = papersContainer.children.length;

        const paperDiv = document.createElement("div");
        paperDiv.classList.add("d-flex", "align-items-center", "gap-2", "mb-3");

        let optionsHTML = '<option value="">Select an Option</option>';
        papers.forEach(paper => {
            optionsHTML += `<option value="${paper.paper_id}">${paper.paper_name}</option>`;
        });

        paperDiv.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <label for="editPaperOption-${paperIndex}" class="form-label">Select Option</label>
                <select class="form-select" id="editPaperOption-${paperIndex}">
                    ${optionsHTML}
                </select>
            </div>
            <button type="button" class="btn btn-danger remove-paper-btn">Remove</button>
        `;

        papersContainer.appendChild(paperDiv);

        // Event listener for removing paper
        paperDiv.querySelector(".remove-paper-btn").addEventListener("click", () => {
            paperDiv.remove();
        });
    });

    editButtons.forEach((button) => {
        button.addEventListener("click", async function () {
            const packageId = this.getAttribute("data-id");

            // Populate the hidden field and text fields for package details
            const packageData = await getPackageDataById(packageId);
            if (packageData) {
                document.getElementById("packageId").value = packageData.id;
                document.getElementById("editPackageName").value = packageData.package_name;
                document.getElementById("editPackageDescription").value = packageData.package_description;
                document.getElementById("editPackagePrice").value = packageData.package_price;
                document.getElementById("editValidMonths").value = packageData.valid_months;

                // Populate options checkboxes
                const optionsContainer = document.getElementById("editPackageOptions");
                optionsContainer.innerHTML = ''; // Clear previous options
                options.forEach(option => {
                    const optionDiv = document.createElement("div");
                    optionDiv.classList.add("form-check", "style-check", "mb-1", "d-flex", "align-items-center");

                    const checkbox = document.createElement("input");
                    checkbox.classList.add("form-check-input", "border", "border-neutral-300");
                    checkbox.type = "checkbox";
                    checkbox.name = "package_options[]";
                    checkbox.value = option.id;
                    checkbox.id = `option-${option.id}`;
                    // Check if this option is part of the package
                    checkbox.checked = packageData.options.some(pkgOption => pkgOption.package_options_id === option.id);

                    const label = document.createElement("label");
                    label.classList.add("form-check-label");
                    label.setAttribute("for", `option-${option.id}`);
                    label.textContent = option.package_options;

                    optionDiv.appendChild(checkbox);
                    optionDiv.appendChild(label);
                    optionsContainer.appendChild(optionDiv);
                });

                const papersContainer = document.getElementById("editPackagePapers");
                papersContainer.innerHTML = ''; // Clear previous papers

                packageData.papers.forEach((paper, paperIndex) => {
                    const selectHTML = papers && papers.map(allPaper => {
                        console.log(allPaper)
                        const isSelected = allPaper.paper_id === paper.papers_paper_id ? 'selected' : '';
                        return `<option value="${allPaper.paper_id}" ${isSelected}>${allPaper.paper_name}</option>`;
                    }).join("");

                    const paperDiv = document.createElement("div");
                    paperDiv.classList.add("d-flex", "align-items-center", "gap-2");

                    paperDiv.innerHTML = `
        <div class="d-flex align-items-center gap-2">
            <label for="paperOption-${paperIndex}" class="form-label">Select Option</label>
            <select class="form-select" id="paperOption-${paperIndex}">
                ${selectHTML}
            </select>
        </div>
        <button type="button" class="btn btn-danger remove-paper-btn">Remove</button>
    `;
                    papersContainer.appendChild(paperDiv);

                    // Event listener for removing paper
                    const removeButton = paperDiv.querySelector(".remove-paper-btn");
                    removeButton.addEventListener("click", () => {
                        paperDiv.remove();
                    });
                });


                // Open the modal
                const editPackageModal = new bootstrap.Modal(document.getElementById('editPackageModal'));
                editPackageModal.show();
            }
        });
    });

    // Handle form submission
    document.getElementById("editPackageForm").addEventListener("submit", async function (e) {
        e.preventDefault();

        const packageId = document.getElementById("packageId").value;
        const packageName = document.getElementById("editPackageName").value;
        const packageDescription = document.getElementById("editPackageDescription").value;
        const packagePrice = document.getElementById("editPackagePrice").value;
        const validMonths = document.getElementById("editValidMonths").value;
        const selectedOptions = Array.from(document.querySelectorAll('input[name="package_options[]"]:checked')).map(input => input.value);
        const selectedPapers = Array.from(document.querySelectorAll('.form-select')).map(select => select.value);

        const requestData = {
            id: packageId,
            name: packageName,
            description: packageDescription,
            price: packagePrice,
            months: validMonths,
            options: selectedOptions,
            papers: selectedPapers,
            csrf_token: csrfToken,
        };

        try {
            const response = await fetch("../api/admin/edit_package.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(requestData),
            });
            const result = await response.json();
            if (!response.ok) {
                showToast("error", result.error || 'Failed to update package.');
                return;
            }
            if (result.success) {
                showToast("success", 'Package updated successfully.');
                location.reload();
            } else {
                showToast("error", result.error);
            }
        } catch (error) {
            console.error("An error occurred while updating the package:", error);
            alert("An error occurred. Please try again.");
        }
    });
});

async function getPackageDataById(packageId) {
    try {
        const response = await fetch(`../api/admin/get_package_by_id?id=${packageId}`);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error:', error);
        return null;
    }
}


