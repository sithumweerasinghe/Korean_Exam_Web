function savePackageInvoice() {
    const userSelect = document.getElementById('seachable-select-3');
    const packageSelect = document.getElementById('seachable-select-4');
    const csrfToken = document.getElementById('package-invoice-save-button').dataset.token;

    const userId = userSelect.value;
    const packageId = packageSelect.value;
    if (!userId || userId === "Select") {
        showToast("error", "Please select a user.");
        return;
    }

    if (!packageId || packageId === "Select") {
        showToast("error", "Please select a package.");
        return;
    }
    const params = new URLSearchParams({
        user_id: userId,
        package_id: packageId
    }).toString();
    fetch(`../api/admin/add_package_invoice.php?${params}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast("success", 'Invoice added successfully');
            $('#addPackageInvoiceModel').modal('hide'); 
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            showToast("error", data.message);
            setTimeout(() => {
                location.reload();
            }, 500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast("error", "An error occurred. Please try again.");
    });
}
