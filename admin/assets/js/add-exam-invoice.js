function handleSaveButtonClick() {
    const userId = document.getElementById('seachable-select').value;
    const examId = document.getElementById('seachable-select-2').value;
    const csrfToken = document.getElementById('exam-invoice-save-button').getAttribute('data-token');
    if (!userId || !examId || userId === 'Select' || examId === 'Select') {
      alert("Please select both a user and an exam.");
      return;
    }
    const url = `../api/admin/add_exam_invoice.php?user_id=${userId}&exam_id=${examId}`;
    fetch(url, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showToast("success", 'Invoice added successfully');
          $('#addExamInvoiceModel').modal('hide');
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
        showToast("error", error);
        location.reload();
      });
  }