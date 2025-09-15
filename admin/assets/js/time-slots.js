document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('addTimeSlotForm');
    const saveButton = document.getElementById('save-button');
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;
        const csrfToken = saveButton.getAttribute('data-token');
        if (!startTime || !endTime) {
            showToast("error", 'Both Start Time and End Time are required.');
            return;
        }
        const payload = {
            start_time: startTime,
            end_time: endTime,
            csrf_token: csrfToken
        };
        fetch('../api/admin/add_time_slot.php', {
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
                    showToast("error", data.error || 'Failed to add time slot.');
                }
            })
            .catch((error) => {
                showToast("error", 'Failed to add time slot.');
            });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-btn");
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    editButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const timeSlotId = this.getAttribute("data-id");
            const startTime = this.getAttribute("data-start-time");
            const endTime = this.getAttribute("data-end-time");
            document.getElementById("timeSlotId").value = timeSlotId;
            document.getElementById("editStartTime").value = startTime;
            document.getElementById("editEndTime").value = endTime;
        });
    });
    document.getElementById("editTimeSlotForm").addEventListener("submit", async function (e) {
        e.preventDefault();
        const timeSlotId = document.getElementById("timeSlotId").value;
        const startTime = document.getElementById("editStartTime").value;
        const endTime = document.getElementById("editEndTime").value;
        if (!timeSlotId || !startTime || !endTime) {
            showToast("error", 'Both Start Time and End Time are required.');
            return;
        }
        const requestData = {
            id: timeSlotId,
            start_time: startTime,
            end_time: endTime,
            csrf_token: csrfToken,
        };
        try {
            const response = await fetch("../api/admin/edit_time_slot.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(requestData),
            });
            const result = await response.json();
            if (!response.ok) {
                showToast("error", result.error || 'Failed to update time slot.');
                return;
            }
            if (result.success) {
                showToast("success", 'Time slot updated successfully.');
                location.reload();
            } else {
                showToast("error", result.error);
            }
        } catch (error) {
            console.error("An error occurred while updating the time slot:", error);
            alert("An error occurred. Please try again.");
        }
    });
});