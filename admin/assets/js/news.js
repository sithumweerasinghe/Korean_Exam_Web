document.getElementById('imageFile').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImage = document.getElementById('previewImage');

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
        };

        reader.readAsDataURL(file);
    } else {
        previewImage.src = '';
        previewContainer.style.display = 'none';
    }
});


function selectImage() {
    const file = event.target.files[0];
    const previewImage = document.getElementById('editNewsPreviewImage');
    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            previewImage.src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
}


function deleteNews(newsId, csrfToken) {
    console.log("Deleting news with ID:", newsId);

    fetch('../api/admin/delete_news.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            news_id: newsId,
            csrf_token: csrfToken,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast("success", data.success);
                location.reload();
            } else {
                showToast("error", data.error || 'Failed to delete news.');
            }
        })
        .catch((error) => {
            showToast("error", 'Failed to delete news.');
            console.error(error);
        });
}

function addNews() {
    const formData = new FormData();
    var news_title = $("#news_title").val().trim();
    var news_des = $("#news_des").val().trim();
    var news_tag = $("#news_tag").val().trim();
    var news_valid_days = $("#news_valid_days").val().trim();
    const imageFile = document.getElementById('imageFile').files[0];

    if (!news_title) {
        showToast("error", "News title is required.");
        return;
    }
    if (!news_des) {
        showToast("error", "News description is required.");
        return;
    }
    if (!news_tag) {
        showToast("error", "News tag is required.");
        return;
    }
    if (!news_valid_days || isNaN(news_valid_days) || news_valid_days < 1) {
        showToast("error", "News valid days are required.");
        return;
    }
    if (!imageFile) {
        showToast("error", "An image file is required.");
        return;
    }

    formData.append('news_title', news_title);
    formData.append('news_des', news_des);
    formData.append('news_tag', news_tag);
    formData.append('news_valid_days', news_valid_days);
    formData.append('imageFile', imageFile);

    fetch(`../api/admin/add_news.php`, {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast("success", 'News added successfully.');
                setTimeout(() => {
                    window.location.href = "news";
                }, 500);
            } else {
                showToast("error", 'Faild to add news.');
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        }
        )
        .catch(error => console.error('Error:', error));
}


function updateNews(news_id) {
    const formData = new FormData();
    var news_title = $("#edit_title").val().trim();
    var news_des = $("#edit_des").val().trim();
    var news_tag = $("#edit_tag").val().trim();
    var news_valid_days = $("#edt_valid_days").val().trim();
    const imageFile = document.getElementById('editNewsImageFile').files[0];

    if (!news_title) {
        showToast("error", "News title is required.");
        return;
    }
    if (!news_des) {
        showToast("error", "News description is required.");
        return;
    }
    if (!news_tag) {
        showToast("error", "News tag is required.");
        return;
    }
    if (!news_valid_days || isNaN(news_valid_days) || news_valid_days < 1) {
        showToast("error", "News valid days are required.");
        return;
    }


    formData.append('news_id', news_id);
    formData.append('news_title', news_title);
    formData.append('news_des', news_des);
    formData.append('news_tag', news_tag);
    formData.append('news_valid_days', news_valid_days);
    if (imageFile) {
        formData.append('imageFile', imageFile);
    }

    fetch(`../api/admin/edit_news.php`, {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast("success", 'News updated successfully.');
                setTimeout(() => {
                    window.location.href = "news";
                }, 500);
            } else {
                showToast("error", 'Failed to update news.');
                
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        }
        )
        .catch(error => console.error('Error:', error));


}