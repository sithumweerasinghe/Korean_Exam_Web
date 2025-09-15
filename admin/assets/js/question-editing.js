document.addEventListener("DOMContentLoaded", () => {
    const editAudioFileInput = document.getElementById("editAudioFile");
    const editImageFileInput = document.getElementById("editImageFile");
    const editQuestionTimeInput = document.getElementById("editQuestionTime");
    const editAnswersContainer = document.getElementById("editAnswersContainer");
    const addEditAnswerBtn = document.getElementById("addEditAnswerBtn");
    editAudioFileInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (file) {
            const audio = new Audio(URL.createObjectURL(file));
            audio.addEventListener("loadedmetadata", () => {
                editQuestionTimeInput.value = Math.round(audio.duration);
            });

            const audioControls = document.getElementById("editAudioControls");
            audioControls.classList.add("d-block");
            audioControls.innerHTML = `
        <audio controls>
            <source src="${URL.createObjectURL(file)}" type="${file.type}">
            Your browser does not support the audio element.
        </audio>
    `;
        }
    });
    editImageFileInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (file) {
            const previewContainer = document.getElementById("editImagePreview");
            const previewImage = document.getElementById("editPreviewImage");
            previewImage.src = URL.createObjectURL(file);
            previewContainer.style.display = "flex";
        }
    });
    addEditAnswerBtn.addEventListener("click", () => {
        const answerTypeSelect = document.getElementById("EditAnswerTypeSelect");
        const editAnswersContainer = document.getElementById("editAnswersContainer");
        const newIndex = editAnswersContainer.children.length;
        const answerDiv = document.createElement("div");

        answerDiv.classList.add("edit-answer-details", "d-flex", "align-items-center", "mb-3");
        answerDiv.dataset.index = newIndex;
        answerDiv.style.gap = "10px";

        if (answerTypeSelect.value === "1") {
            answerDiv.innerHTML = `
            <div>
                <label class="form-label">Add Image</label>
                <input type="file" class="form-control" id="editImageUpload-${newIndex}" accept="image/*" onchange="previewEditImage(this, ${newIndex})" />
                <label for="editImageUpload-${newIndex}" class="add-image-btn">Add Image</label>
                <div id="editPreviewContainer-${newIndex}" style="border: 1px solid #ddd; width: 100px; height: 100px; margin-top: 10px; display: none;">
                    <img id="editPreviewImage-${newIndex}" src="" style="max-width: 100%; max-height: 100%;" />
                </div>
            </div>
            <div>
                <input type="checkbox" class="form-check-input" id="editCorrectAnswer-${newIndex}" />
                <label for="editCorrectAnswer-${newIndex}" class="form-check-label">Correct</label>
            </div>
            <div>
                <button type="button" class="btn btn-danger remove-answer-btn" onclick="removeEditAnswer(${newIndex})">Remove Answer</button>
            </div>`;
        } else if (answerTypeSelect.value === "0") {
            answerDiv.innerHTML = `
            <div class="col-6">
                <input type="text" class="form-control" placeholder="Enter Answer" />
            </div>
            <div>
                <input type="checkbox" class="form-check-input" id="editCorrectAnswer-${newIndex}" />
                <label for="editCorrectAnswer-${newIndex}" class="form-check-label">Correct</label>
            </div>
            <div>
                <button type="button" class="btn btn-danger remove-answer-btn" onclick="removeEditAnswer(${newIndex})">Remove Answer</button>
            </div>`;
        } else {
            alert("Please select an answer type before adding an answer.");
            return;
        }
        editAnswersContainer.appendChild(answerDiv);
    });
});

function removeEditAnswer(index, answer_id) {
    const answerElement = document.querySelector(`.edit-answer-details[data-index='${index}']`);
    if (answerElement) {
        answerElement.remove();
    }
    fetch('../api/admin/delete_answer.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ answer_id: answer_id }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Answer deleted successfully');
                showToast('success', 'Answer deleted successfully');
            } else {
                console.error('Failed to delete answer:', data.message);
                showToast('error', 'Failed to delete answer');
            }
        })
        .catch(error => {
            console.error('Error deleting answer:', error);
            showToast('error', 'An error occurred while deleting the answer');
        });
}

function previewEditImage(input, index) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewContainer = document.getElementById(`editPreviewContainer-${index}`);
            const previewImage = document.getElementById(`editPreviewImage-${index}`);
            previewContainer.style.display = 'block';
            previewImage.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
const sendEditQuestionRequest = (paper_id) => {
    const formData = new FormData();
    const questionType = document.getElementById('editQuestionType').value;
    const questionCategory = document.getElementById('editQuestionCategory').value;
    const questionText = document.getElementById('editQuestion').value;
    const questionTime = document.getElementById('editQuestionTime').value;
    const questionMarks = document.getElementById('editQuestionMarks').value;
    const EditAnswerTypeSelect = document.getElementById('EditAnswerTypeSelect').value;


    formData.append('questionType', questionType);
    formData.append('questionCategory', questionCategory);
    formData.append('questionText', questionText);
    formData.append('questionTime', questionTime);
    formData.append('questionMarks', questionMarks);

    const audioFile = document.getElementById('editAudioFile').files[0];
    if (audioFile) {
        formData.append('audioFile', audioFile);
    }

    const imageFile = document.getElementById('editImageFile').files[0];
    if (imageFile) {
        formData.append('imageFile', imageFile);
    }

    const answerItems = document.querySelectorAll('.edit-answer-details');
    if (answerItems.length === 0) {
        formData.append('answers', JSON.stringify([]));
    } else {
        answerItems.forEach((item, index) => {
            const answerText = item.querySelector('input[type="text"]')?.value;
            const answerImageFile = item.querySelector('input[type="file"]')?.files[0];
            const correctAnswerInput = item.querySelector(`#editCorrectAnswer-${index}`);
            const correctAnswer = correctAnswerInput.checked ? 1 : 0;
            const correctAnswerId = correctAnswerInput.checked ? correctAnswerInput.dataset.id : null;
            const answerId = item.querySelector(`input[type="file"]`)?.dataset.answerId;

            if (answerText) {
                formData.append(`answers[${index}][text]`, answerText);
            }
            if (answerImageFile) {
                formData.append(`answers[${index}][file]`, answerImageFile);
            }
            formData.append("is_media", EditAnswerTypeSelect);
            formData.append(`answers[${index}][id]`, answerId);

            formData.append(`answers[${index}][isCorrect]`, correctAnswer);
            formData.append(`answers[${index}][correctAnswerId]`, correctAnswerId);
        });
    }

    fetch(`../api/admin/edit_questions.php?question_id=${paper_id}`, {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                showToast("success", 'Question edited successfully.');
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                showToast("error", 'Failed to edit question.');
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        })
        .catch(error => console.error('Error:', error));
};
