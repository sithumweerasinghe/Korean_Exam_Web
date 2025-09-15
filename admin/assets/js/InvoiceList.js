document.querySelector('input[name="#0"]').addEventListener('input', function () {
    let searchTerm = this.value.toLowerCase();
    filterData(searchTerm);
});
function filterData(searchTerm = '') {
    const rows = document.querySelectorAll('table tbody tr');
    rows.forEach(row => {
        const invoice_no = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const mobile = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const name = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
        let match = true;
        if (searchTerm && !(mobile.includes(searchTerm) || name.includes(searchTerm) || invoice_no.includes(searchTerm))) {
            match = false;
        }
        if (match) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
document.querySelectorAll(".edit-btn").forEach((button) => {
    button.addEventListener("click", function () {
        const invoiceId = this.dataset.id;
        const currentStatus = this.dataset.status;
        const token = this.dataset.token;
        document.getElementById("invoiceId").value = invoiceId;
        document.getElementById("paymentStatus").value = currentStatus;
        const modal = new bootstrap.Modal(document.getElementById("editPaymentStatusModal"));
        modal.show();
    });
});

document.getElementById("editPaymentStatusForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    const invoiceId = document.getElementById("invoiceId").value;
    const paymentStatus = document.getElementById("paymentStatus").value;
    const button = document.getElementById('save-button');
    const csrfToken = button.getAttribute('data-token');
    const payload = {
        invoice_id: invoiceId,
        payment_status: paymentStatus,
        csrf_token: csrfToken,
    };
    try {
        const response = await fetch("../api/admin/edit_payment_status.php", {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
        });
        const result = await response.json();
        console.log(result)
        if (response.ok) {
            const modalInstance = bootstrap.Modal.getInstance(document.getElementById("editPaymentStatusModal"));
            modalInstance.hide();
            location.reload();
            showToast("success", result.message);
        } else {
            showToast("error", result.message || "An error occurred while updating the payment status.");
        }
    } catch (error) {
        console.error("Error:", error);
        showToast("error", 'Error occurred: ' + error.message);
    }
});
