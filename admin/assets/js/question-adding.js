let audioElement = null;
document.getElementById('audioFile').addEventListener('change', function () {
    const audioFile = this.files[0];
    if (audioFile) {
        audioElement = new Audio(URL.createObjectURL(audioFile));
        document.getElementById('audioControls').style.display = 'block';
        document.getElementById('playBtn').disabled = false;
        audioElement.onloadedmetadata = function () {
            const duration = audioElement.duration;
            const questionTime = Math.floor(duration);
            document.getElementById('questionTime').value = questionTime;
        };
    }
});
document.getElementById('playBtn').addEventListener('click', function () {
    if (audioElement) {
        audioElement.play();
        document.getElementById('playBtn').disabled = true;
        document.getElementById('pauseBtn').disabled = false;
    }
});
document.getElementById('pauseBtn').addEventListener('click', function () {
    if (audioElement) {
        audioElement.pause();
        document.getElementById('playBtn').disabled = false;
        document.getElementById('pauseBtn').disabled = true;
    }
});
document.getElementById('imageFile').addEventListener('change', function () {
    const file = this.files[0];
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImage = document.getElementById('previewImage');
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'flex';
        };
        reader.readAsDataURL(file);
    } else {
        previewImage.src = '';
        previewContainer.style.display = 'none';
    }
});
const answersContainer = document.getElementById('answersContainer');
const addAnswerBtn = document.getElementById('addAnswerBtn');
const answerTypeSelect = document.getElementById('answerTypeSelect');

let answerIndex = 0;
let selectedAnswerType = '';

function refreshAnswerNumbers() {
    const answerItems = answersContainer.querySelectorAll('.answer-item');
    answerItems.forEach((item, index) => {
        const answerNumber = item.querySelector('.answer-number');
        answerNumber.textContent = `Answer ${index + 1}`;
    });
}

answerTypeSelect.addEventListener('change', () => {
    selectedAnswerType = answerTypeSelect.value;
    if (selectedAnswerType) {
        addAnswerBtn.disabled = false;
    } else {
        addAnswerBtn.disabled = true;
    }
});

addAnswerBtn.addEventListener('click', () => {
    if (!selectedAnswerType) return;

    const answerDiv = document.createElement('div');
    answerDiv.className = 'answer-item mb-4';
    answerDiv.innerHTML = `
        <div class="row gy-3">
            <div class="col-md-12">
                <h6 class="answer-number">Answer ${answerIndex + 1}</h6>
            </div>
        </div>
        <div class="row d-flex justify-content-center gy-3 answer-details mb-3" data-index="${answerIndex}">
            <!-- Dynamic Content Goes Here -->
        </div>
    `;
    answersContainer.appendChild(answerDiv);
    answerIndex++;
    refreshAnswerNumbers();
    addAnswerContent(selectedAnswerType, answerIndex - 1);
});

answersContainer.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-answer-btn')) {
        e.target.closest('.answer-item').remove();
        refreshAnswerNumbers();
    }
});

function addAnswerContent(type, index) {
    const detailsContainer = document.querySelector(`.answer-details[data-index="${index}"]`);

    if (type === 'media') {
        detailsContainer.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Add Image</label>
                <input type="file" class="form-control" id="imageUpload-${index}" accept="image/*" style="display:none;" onchange="previewImage(this, ${index})" />
                <label for="imageUpload-${index}" class="add-image-btn">Add Image</label>
                <div id="previewContainer-${index}" style="border: 1px solid #ddd; width: 100px; height: 100px; margin-top: 10px; display: none;">
                    <img id="previewImage-${index}" style="max-width: 100%; max-height: 100%;" />
                </div>
            </div>
            <div class="col-md-4">
                <input type="checkbox" class="form-check-input" id="correctAnswer-${index}" />
                <label for="correctAnswer-${index}" class="form-check-label">Correct Answer</label>
            </div>
            <div class="col-md-4 d-flex align-items-start">
                <button type="button" class="btn btn-danger remove-answer-btn">Remove Answer</button>
            </div>
        `;
    } else if (type === 'text') {
        detailsContainer.innerHTML = `
            <div class="col-md-4">
                <input type="text" class="form-control" />
            </div>
            <div class="col-md-4">
                <input type="checkbox" class="form-check-input" id="correctAnswer-${index}" />
                <label for="correctAnswer-${index}" class="form-check-label">Correct Answer</label>
            </div>
            <div class="col-md-4 d-flex align-items-start">
                <button type="button" class="btn btn-danger remove-answer-btn">Remove Answer</button>
            </div>
        `;
    }
}


function previewImage(input, index) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewContainer = document.getElementById(`previewContainer-${index}`);
            const previewImage = document.getElementById(`previewImage-${index}`);
            previewContainer.style.display = 'block';
            previewImage.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

const sendAddQuestionRequest = (group_id) => {
    const formData = new FormData();
    const questionType = document.getElementById('questionType').value;
    const questionCategory = document.getElementById('questionCategory').value;
    const questionText = document.getElementById('question').value;
    const questionTime = document.getElementById('questionTime').value;
    const questionMarks = document.getElementById('questionMarks').value;
    const answerTypeSelect = document.getElementById("answerTypeSelect").value;

    if (answerTypeSelect === "no") {
        showToast("error", 'Select Answer Type First');
        return;
    }
    formData.append('questionType', questionType);
    formData.append('questionCategory', questionCategory);
    formData.append('questionText', questionText);
    formData.append('questionTime', questionTime);
    formData.append('questionMarks', questionMarks);

    const audioFile = document.getElementById('audioFile').files[0];
    if (audioFile) {
        formData.append('audioFile', audioFile);
    }

    const imageFile = document.getElementById('imageFile').files[0];
    if (imageFile) {
        formData.append('imageFile', imageFile);
    }

    const answerItems = document.querySelectorAll('.answer-item');
    answerItems.forEach((item, index) => {
        const answerText = item.querySelector('input[type="text"]')?.value;
        const answerImageFile = item.querySelector('input[type="file"]')?.files[0];
        const correctAnswer = item.querySelector(`#correctAnswer-${index}`).checked ? 1 : 0;

        if (answerText) {
            formData.append(`answers[${index}][text]`, answerText);
            formData.append("is_media", 0)
        }
        if (answerImageFile) {
            formData.append(`answers[${index}][file]`, answerImageFile);
            formData.append("is_media", 1)
        }
        formData.append(`answers[${index}][isCorrect]`, correctAnswer);
    });
    fetch(`../api/admin/add_question.php?group_id=${group_id}`, {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast("success", 'Question added successfully.');
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                showToast("error", 'Faild to add question.');
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        }
        )
        .catch(error => console.error('Error:', error));
};



