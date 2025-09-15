function saveCutoffMark() {
    const cutoffMark = document.getElementById('cutoffMarkInput').value;
    if (!cutoffMark || cutoffMark <= 0) {
        showToast("error", 'Please enter a valid cutoff mark.');
        return;
    }
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const payload = {
        mark: parseInt(cutoffMark, 10),
        csrf_token: csrfToken
    };
    fetch('../api/admin/add_cutoffmark.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload),
        credentials: 'include' 
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast("success", data.message);
            document.querySelector('.text-red.fw-bolder').innerText = cutoffMark;
            const modal = bootstrap.Modal.getInstance(document.getElementById('cutoffMarkModal'));
            modal.hide();
        } else {
        showToast("error", data.message || 'Failed to add cutoff mark.');

        }
    })
    .catch(error => {
        showToast("error", "An error occurred while adding the cutoff mark.");
    });
}

$('.remove-item-btn').on('click', function () {
    const token = $(this).attr('data-token');
    const id = $(this).attr('data-id');
    const row = $(this).closest('tr');
    fetch('../api/admin/delete_paper.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            paper_id: id,
            csrf_token: token,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast("success", 'Paper deleted successfully.');
                row.addClass('d-none');
            } else {
                showToast("error", "Failed to delete paper");
            }
        })
        .catch((error) => {
            showToast("error", 'Error occurred: ' + error.message);
        });
});
document.querySelectorAll('.edit-paper-btn').forEach(button => {
    button.addEventListener('click', function () {
        const paperId = this.getAttribute('data-id');
        const paperName = this.getAttribute('data-name');
        const paperType = this.getAttribute('data-type');
        const paperStatus = this.getAttribute('data-status');
        const csrfToken = this.getAttribute('data-token');
        document.getElementById('paperId').value = paperId;
        document.getElementById('editPaperName').value = paperName;
        document.getElementById('editPaperType').value = paperType;
        document.getElementById('editPaperStatus').value = paperStatus;
        console.log(paperStatus)
        document.getElementById('editPaperForm').addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            const paperData = {
                paper_id: formData.get('paper_id'),
                title: formData.get('paper_name'),
                is_sample: formData.get('paper_type') === '1' ? 1 : 0,
                status: formData.get('paper_status') === '1' ? 1 : 0,
            };
            fetch('../api/admin/edit_paper.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'CSRF-Token': csrfToken,
                },
                body: JSON.stringify({
                    csrf_token: csrfToken,
                    paper_id: paperData.paper_id,
                    title: paperData.title,
                    is_sample: paperData.is_sample,
                    status: paperData.status,
                }),
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                        showToast("success", result.message);
                    } else {
                        showToast("error", result.message);
                    }
                })
                .catch(error => {
                    showToast("error", "Internal server error.Please try again.");
                });
        });
    });
});
document.getElementById('addPaperForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const csrf_token = document.getElementById("saveChanges").getAttribute("data-token");
    const paperName = document.getElementById("paperName").value;
    const is_sample = document.getElementById("AddPaperType").value;

    const data = {
        title: paperName,
        is_sample: is_sample === '1' ? 1 : 0,
        csrf_token: csrf_token
    };
    fetch('../api/admin/add_paper.php', {
        method: 'POST',
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                showToast("success", "Paper added successfully");
            } else {
                showToast("error", data.message);
            }
        })
        .catch(error => console.error('Error:', error));
});
const sendGroupName = (paper_id) => {
    const groupName = document.getElementById("GroupName").value
    const csrf_token = document.getElementById("addGroupNameBtn").getAttribute("data-token");
    const data = {
        paper_id: paper_id,
        group_name: groupName,
        csrf_token: csrf_token
    };
    fetch('../api/admin/add_question_group.php', {
        method: 'POST',
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                showToast("success", "Group name added successfully");
            } else
                showToast("error", data.message
                );
        })
        .catch(error => console.error('Error:', error));
};
document.querySelectorAll('.edit-question-group-btn').forEach(button => {
    button.addEventListener('click', function () {
        const group_id = this.getAttribute('data-id');
        const group_name = this.getAttribute('data-name');
        const csrfToken = this.getAttribute('data-token');
        document.getElementById('group_id').value = group_id;
        document.getElementById('editQuestionGroupName').value = group_name;
        document.getElementById('edit-question-group-btn').addEventListener('click', function () {
            const data = {
                group_id: $('#group_id').val(),
                new_group_name: $('#editQuestionGroupName').val(),
                csrf_token: csrfToken,
            };
            fetch('../api/admin/edit_question_group.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'CSRF-Token': csrfToken,
                },
                body: JSON.stringify(data),
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                        showToast("success", result.message);
                    } else {
                        showToast("error", result.message);
                    }
                })
                .catch(error => {
                    showToast("error", "Internal server error.Please try again.");
                });
        });
    });
});
