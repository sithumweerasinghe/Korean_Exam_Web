const deleteQuestionGroup = (id) => {
    const token = document.getElementById("deleteGroup").getAttribute("data-token");
    const confirm = window.confirm("Are you sure you want to delete this question Group?");
    if (confirm) {
        fetch(`../api/admin/delete_question_groups.php`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                csrf_token: token,
                group_id: id
            })
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showToast("success", 'Question group deleted successfully.');
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    showToast("error", data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }
}
