const deleteQuestion = (id) => {
    const token = document.getElementById("deleteQuestion").getAttribute("data-token");
    const confirm = window.confirm("Are you sure you want to delete this question?");
    if (confirm) {
        fetch(`../api/admin/delete_question.php`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                csrf_token: token,
                question_id: id
            })
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showToast("success", 'Question deleted successfully.');
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
