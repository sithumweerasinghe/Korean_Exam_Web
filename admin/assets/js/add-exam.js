$(document).on('click', '.edit-btn', function () {
    var examId = $(this).data('id');
    var timeSlotId = $(this).data('time-slot');
    var paperId = $(this).data('paper-id');
    var examDate = $(this).data('exam-date');
    var examprice = $(this).data('exam-price');
    $('#examTimeTableID').val(examId);
    $('#editTimeSlot').val(timeSlotId);
    $('#editExamDate').val(examDate);
    $('#editExamPaper').val(paperId);
    $('#editExamPrice').val(Number(examprice));
});
document.getElementById("save-button").addEventListener("click", function () {
    const timeSlot = document.getElementById("addTimeSlot").value;
    const examDate = document.getElementById("addExamDate").value;
    const examPaper = document.getElementById("addExamPaper").value;
    const examPrice = document.getElementById("addExamPrice").value;
    const examData = {
        timeSlot: timeSlot,
        examDate: examDate,
        examPaper: examPaper,
        examPrice: examPrice,
        csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    fetch('../api/admin/add_exam.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(examData),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast("success", 'Exam scheduled successfully!');
                location.reload();
            } else {
                showToast("error", 'Faild to scheduled Exam.');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast("error", 'Something went wrong');
        });
});
$(document).on('click', '#update-button', function () {
    const id = $('#examTimeTableID').val();
    const timeTableSlot = $('#editTimeSlot').val();
    const examDate = $('#editExamDate').val();
    const examPaper = $('#editExamPaper').val();
    const examPrice = $('#editExamPrice').val();
    const examData = {
        timeSlot: timeTableSlot,
        examDate: examDate,
        examPaper: examPaper,
        examPrice: examPrice,
        csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        examId: id
    };
    fetch('../api/admin/edit_exam.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(examData),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast("success", 'Exam scheduled eduted successfully!');
                location.reload();
            } else {
                showToast("error", 'Error scheduling the exam.');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast("error", 'Something went wrong');
        });
})

