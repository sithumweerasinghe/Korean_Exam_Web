let questions = [];
let currentQuestionIndex = 0;
let timer;
let timeLeft;
const answers = [];
let currentAudio = null;
let isAudioPlaying = false;

let totalReadingTime = 0;
let totalListeningTime = 0;
let readingTimer;
let listeningTimer;
let readingTimeLeft;
let listeningTimeLeft;

let paperId = ''
let examId = ''

let readingQuestions;

let isListeningStarted = false;
let isTransitioningToListening = false;

let isSamplePaper = false;
let isExamPaper = false;
let isReadingCompleted = false;


function loadQuestions(questions_array, isSample, isExam, paper_id, exam_id) {
  try {
    console.log('loadQuestions called with:', { questions_array: questions_array.length, isSample, isExam, paper_id, exam_id });
    
    isExamPaper = isExam;
    isSamplePaper = isSample;
    paperId = paper_id;
    examId = exam_id;

    console.log('Filtering questions...');
    readingQuestions = questions_array.filter(q => q.question_category === "reading");
    const listeningQuestions = questions_array.filter(q => q.question_category === "listening");
    console.log('Questions filtered:', { reading: readingQuestions.length, listening: listeningQuestions.length });

    console.log('Calculating time...');
    totalReadingTime = readingQuestions.reduce((sum, q) => sum + Number(q.timeLimit), 0);
    totalListeningTime = listeningQuestions.reduce((sum, q) => sum + Number(q.timeLimit), 0);
    console.log('Total times:', { reading: totalReadingTime, listening: totalListeningTime });

    console.log('Formatting time...');
    readingTimeLeft = formatTime(totalReadingTime);
    listeningTimeLeft = formatTime(totalListeningTime);
    console.log('Formatted times:', { reading: readingTimeLeft, listening: listeningTimeLeft });

    console.log('Updating DOM elements...');
    $("#reading-remaining").html(readingTimeLeft);
    $("#listening-remaining").html(listeningTimeLeft);
    console.log('DOM elements updated');

    console.log('Rendering categories...');
    let currentQuestionNumber = 1;
    currentQuestionNumber = renderCategory("reading", readingQuestions, currentQuestionNumber);
    currentQuestionNumber = renderCategory("listening", listeningQuestions, currentQuestionNumber);
    console.log('Categories rendered');

    console.log('Setting up questions and display...');
    questions = questions_array;
    displayQuestion(currentQuestionIndex);
    console.log('Question displayed');

    console.log('Starting timers...');
    startSectionTimers();
    console.log('loadQuestions completed successfully');
  } catch (error) {
    console.error('Error in loadQuestions:', error);
    throw error;
  }
}

function displayQuestion(index) {
  const question = questions[index];
  let isSampleQuestion = question.questionIsSample;

  // Stop any ongoing audio
  if (currentAudio) {
    currentAudio.pause();
    currentAudio.currentTime = 0;
  }

  // Play audio if available
  if (question.audio) {
    currentAudio = new Audio(question.audio);
    currentAudio.play();
    isAudioPlaying = true;
    document.getElementById("next-btn").disabled = true;
    currentAudio.onended = () => {
      isAudioPlaying = false;
      document.getElementById("next-btn").disabled = false;
    };
  }

  if (question.question_category === "listening") {
    startListeningTimer();
  }

  const options = question.options;
  const currentGroup = question.question_group_name;
  const previousGroup = index > 0 ? questions[index - 1].question_group_name : null;
  const showGroupName = currentGroup !== previousGroup;

  const questionContainer = document.getElementById("question-container");

  let questionNumber = 1;
  for (let i = 0; i < index; i++) {
    if (!questions[i].questionIsSample) {
      questionNumber++;
    }
  }

  questionContainer.innerHTML = `
  <div class=" quiz-card p-5" >
    ${showGroupName ? `<h5>${currentGroup ?? ""}</h5>` : ""}
    ${isSampleQuestion ? `<p class="text-warning">This is a Sample Question</p>` : ""}
    <h6>
      ${isSampleQuestion ? "" : `<span class="question-number">${questionNumber}.</span>`}
      ${question.question ? question.question.replace(/\n/g, '<br>') : ''}
    </h6>
    ${question.image ? `<div class="col-lg-12 mt-3 mb-3">
            <img src="${question.image}" width="350px" height="350px" alt="Image" class="img-fluid">
          </div>` : ""}
    <form id="question-form" class="mt-4 ${options.some(opt => opt.isMedia == 1) ? 'd-flex flex-wrap row-gap-3' : 'd-flex flex-column row-gap-3'}">
  ${options.map((option, i) => `
    <div class="form-check ${option.isMedia && option.isMedia == 1 ? 'd-inline-flex align-items-center gap-2' : 'd-flex align-items-center gap-2'}" 
         style="${option.isMedia && option.isMedia == 1 ? 'width: 45%; margin-right: 10px;' : ''}">
      <input class="form-check-input" type="radio" name="question" id="option${i}" value="${i}"
        ${answers[index] === i ? "checked" : ""} 
        ${isSampleQuestion ? "disabled" : ""} 
        ${isSampleQuestion && option.correctAnswer ? "checked" : ""}>
      <label class="form-check-label" for="option${i}">
        ${option.isMedia && option.isMedia == 1 
          ? `<img src="${option.answer}" alt="media" width="100px" />` 
          : `${i + 1}) ${option.answer}`}
      </label>
    </div>
    ${option.isMedia && option.isMedia == 1 && (i + 1) % 2 === 0 ? `<div class="w-100"></div>` : ""}
  `).join('')}
</form>


  </div>
`;

  document.getElementById("prev-btn").style.display = index > 0 ? "inline-block" : "none";

  const radioButtons = document.querySelectorAll('input[name="question"]');
  radioButtons.forEach((radio) => {
    radio.addEventListener("change", () => {
      saveAnswer();
    });
  });

  if (question.question_category === "listening") {
    setupTimer(Number(question.timeLimit));
  } else if (timer) {
    clearInterval(timer); // Stop any running timers for non-listening questions
  }
}


function renderCategory(category, questions, startNumber) {
  const container = document.getElementById(`${category}-container`);
  container.innerHTML = "";

  let currentQuestionNumber = startNumber;

  questions.forEach((question, index) => {
    if (question.questionIsSample) return;

    const questionNumber = currentQuestionNumber++;
    const questionDiv = document.createElement("div");
    questionDiv.classList.add("row");

    let selectedAnswer;
    if (category === "reading") {
      selectedAnswer = answers.find((ans, i) => i === index && ans !== null);
    } else if (category === "listening") {
      const listeningAnswers = answers.slice(readingQuestions.length);
      selectedAnswer = listeningAnswers.find((ans, i) => i === index && ans !== null);
    }

    questionDiv.innerHTML = `
    <div id="${category}-clickDiv-${index}"  class="col-3 d-flex justify-content-center align-items-center" style="height: 24px; cursor: pointer;">
      <p class="text-black fw-medium fw-normal" style="font-size: 14px">${questionNumber})</p>
    </div>
    <div class="col-4 d-flex justify-content-center align-items-center border-top  ${index === questions.length - 1 ? "border - bottom" : ""} border-start border-end border-black" style="height: 24px;">
        <div id="red-dot" class="rounded-circle ${selectedAnswer !== undefined ? "bg-danger" : "d-none"}" style="width: 12px; height:12px;"></div>
    </div> 
    <div  class="col-3 d-none border-top border-start border-end border-black ${index === questions.length - 1 ? "border - bottom" : ""}" style = "height: 24px; cursor: pointer;" >
        <p class="fw-normal text-dark" style="font-size: 14px">${selectedAnswer !== undefined ? selectedAnswer + 1 : ""}</p>
    </div >
    `
      ;
    container.appendChild(questionDiv);

    const clickDiv = document.getElementById(`${category}-clickDiv-${index}`);
    clickDiv.addEventListener("click", () => {
      const questionIndex = questions.indexOf(question);
      if (questionIndex !== -1) {
        if (isListeningStarted) {
          showToast("warning", "Questions cannot be accessed after starting Listening.");
          return;
        }

        if (isReadingCompleted) {
          showToast("warning", "Questions cannot be accessed after completing Reading.");
          return;
        }

        if (category === "listening") {
          showToast("warning", "Listening questions cannot be accessed.");
          return;
        }

        if (category === "reading") {
          currentQuestionIndex = questionIndex;
          displayQuestion(questionIndex);
        }
      }
    });
  });

  return currentQuestionNumber;
}


function subtractTime(readingTimeLeft, questionTimeLimit) {
  const [minutes, seconds] = readingTimeLeft.split(":").map(Number);
  let totalSeconds = minutes * 60 + seconds;
  totalSeconds -= Number(questionTimeLimit);
  totalSeconds = Math.max(totalSeconds, 0);
  return formatTime(totalSeconds);
}

function startSectionTimers() {
  // Clear previous timers if they exist
  if (readingTimer) clearInterval(readingTimer);
  if (listeningTimer) clearInterval(listeningTimer);

  // Start Reading Timer
  readingTimer = setInterval(() => {
    const [minutes, seconds] = readingTimeLeft.split(":").map(Number);
    let totalSeconds = minutes * 60 + seconds;
    if (totalSeconds > 0) {
      readingTimeLeft = subtractTime(readingTimeLeft, 1);
      $("#reading-remaining").html(readingTimeLeft);
    } else {
      clearInterval(readingTimer);
      isReadingCompleted = true;
      showListeningInstructions()
    }
  }, 1000);
}

function startListeningTimer() {
  if (!isListeningStarted) {
    isListeningStarted = true;
    // $("#next-btn").prop("disabled", true);
    // $("#prev-btn").prop("disabled", true);
    $("#next-btn").hide();
    $("#prev-btn").hide();

    if (readingTimer) {
      clearInterval(readingTimer);
    }

    listeningTimer = setInterval(() => {
      const [minutes, seconds] = listeningTimeLeft.split(":").map(Number);
      let totalSeconds = minutes * 60 + seconds;
      if (totalSeconds > 0) {
        listeningTimeLeft = subtractTime(listeningTimeLeft, 1);
        $("#listening-remaining").html(listeningTimeLeft);
      } else {
        clearInterval(listeningTimer);
      }
    }, 1000);
  }
}

function setupTimer(duration) {
  timeLeft = duration;
  const timerDisplay = document.getElementById("listening-timer-single");
  timerDisplay.innerHTML = formatTime(timeLeft);

  if (timer) clearInterval(timer);
  timer = setInterval(() => {
    timeLeft--;
    timerDisplay.textContent = formatTime(timeLeft);
    if (timeLeft <= 0) {
      clearInterval(timer);
      showToast("info", "Time's up!");
      nextQuestion();
    }
  }, 1000);
}

function formatTime(seconds) {
  const minutes = Math.floor(seconds / 60);
  const remainingSeconds = seconds % 60;
  return `${minutes.toString().padStart(2, "0")}:${remainingSeconds.toString().padStart(2, "0")}`;
}

document.getElementById("next-btn").addEventListener("click", async () => {
  saveAnswer();
  nextQuestion();
});

document.getElementById("prev-btn").addEventListener("click", () => {
  saveAnswer();
  previousQuestion();
});


function saveAnswer() {
  const selectedOption = document.querySelector("input[name='question']:checked");
  const question = questions[currentQuestionIndex];
  console.log(answers)
  if (selectedOption && !question.questionIsSample) {
    answers[currentQuestionIndex] = parseInt(selectedOption.value);
  } else if (question.questionIsSample) {
    answers[currentQuestionIndex] = "sample";
  } else {
    answers[currentQuestionIndex] = null;
  }

  // Re-render the category to update the red-dot and selected answer
  const category = questions[currentQuestionIndex].question_category;
  const categoryQuestions = questions.filter(q => q.question_category === category);
  renderCategory(category, categoryQuestions, category === "reading" ? 1 : readingQuestions.length + 1);
}

function nextQuestion() {
  saveAnswer();

  if (
    !isTransitioningToListening &&
    currentQuestionIndex === readingQuestions.length - 1
  ) {
    showToast("info", "You must complete the reading section before visiting the listening section.")
    return;
  }


  if (currentQuestionIndex < questions.length - 1) {
    currentQuestionIndex++;
    displayQuestion(currentQuestionIndex);
  } else {
    submitAnswers()
  }
}

function previousQuestion() {
  if (currentQuestionIndex > 0) {
    currentQuestionIndex--;
    displayQuestion(currentQuestionIndex);
  }
}

function showListeningInstructions() {
  isTransitioningToListening = true;

  // Pause timers and buttons
  stopTimers();
  document.getElementById("next-btn").disabled = true;
  document.getElementById("prev-btn").disabled = true;

  const questionContainer = document.getElementById("question-container");
  questionContainer.innerHTML = `
    <div class="card quiz-card p-5">
      <h5>Instructions for Listening Section</h5>
      <p>Please read the instructions carefully before proceeding to the listening section.</p>
      <div id="listening-instruction-timer" style="font-size: 1.5rem; font-weight: bold; color: #2ca347;">01:00</div>
    </div>
  `;

  let instructionTime = 60; // 1 minute
  const instructionTimerDisplay = document.getElementById(
    "listening-instruction-timer"
  );

  const instructionTimer = setInterval(() => {
    instructionTime--;
    instructionTimerDisplay.textContent = formatTime(instructionTime);

    if (instructionTime <= 0) {
      clearInterval(instructionTimer);
      isTransitioningToListening = false;

      // Re-enable buttons and display the first listening question
      document.getElementById("next-btn").disabled = false;
      document.getElementById("prev-btn").disabled = true; // Disable previous for first listening question
      currentQuestionIndex = readingQuestions.length; // First listening question index
      displayQuestion(currentQuestionIndex);

      // Start Listening timer
      startListeningTimer();
    }
  }, 1000);
}


function calculateResults() {
  let totalMarks = 0;
  let correctAnswers = 0;

  answers
    .forEach((selectedIndex, index) => {
      if (selectedIndex !== null) {
        const question = questions[index];
        const options = JSON.parse(question.options)

        if (options[selectedIndex] && options[selectedIndex].correctAnswer === 1) {
          totalMarks += question.marks;
          correctAnswers++;
        }
      }
    });

  return { totalMarks, correctAnswers };
}

function stopTimers() {
  if (readingTimer) clearInterval(readingTimer);
  if (listeningTimer) clearInterval(listeningTimer);
  if (timer) clearInterval(timer);
}

function endQuiz() {
  $("#next-btn").addClass('d-none');
  $("#prev-btn").addClass("d-none");
  stopTimers();
  saveAnswer();
  const { totalMarks, correctAnswers } = calculateResults();

  // Open a modal to display results
  const resultModalContent = `
    <div class=" position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50">
        <div class="modal-content text-center rounded shadow w-50 d-flex justify-content-center align-items-center" style="background-image: url('assets/images/section-bg-11.png'); background-size: cover; background-position: center; background-repeat: no-repeat; height: 50%; border: 1px solid rgba(255, 255, 255, 0.3);">
            <h3 style="color:#2ca347">Quiz Finished!</h3>
            <h5>Total Marks: <strong>${totalMarks}</strong></h5>
            <h5>Correct Answers: <strong>${correctAnswers}/${questions.filter((question) => !question.questionIsSample).length}</strong></h5>
            <button type="button" class="exam-button py-3 mb-3 px-4 mt-3" id="finishQuizBtn">Go to Answers</button>
        </div>
    </div>
  `;

  // Append the modal to the body
  $('body').append(resultModalContent);

  // Show the modal
  $('#resultModal').modal('show');

  // Handle the Finish button click event
  $('#finishQuizBtn').on('click', function () {
    $('#resultModal').modal('hide');
    const finalAnswers = answers.filter(answer => answer !== "sample").map(answer => answer === null ? 0 : answer + 1);

    if (isSamplePaper) {
      window.location = "answers?paper_id=" + paperId + "&answers=" + finalAnswers;
    } else {
      const csrf_token = $('#csrf_token').val().trim();
      const examDetails = isExamPaper ? `&exam_id=${examId}` : '';

      fetch('api/client/submitResults.php?paper_id=' + paperId + '&result=' + totalMarks + '&isExam=' + isExamPaper + examDetails, {
        method: 'GET',
        headers: {
          'X-CSRF-TOKEN': csrf_token
        }
      })
        .then(response => response.json())
        .then(data => {
          console.log(data);
          if (data.success) {
            if (isExamPaper) {
              window.location = "answers?paper_id=" + paperId + "&answers=" + finalAnswers + "&exam_id=" + examId;
            } else {
              window.location = "answers?paper_id=" + paperId + "&answers=" + finalAnswers;
            }
          }
        })
        .catch(error => console.error('Error:', error));
    }
  });
}

function submitAnswers() {
  if (questions.length != answers.length) {
    showToast("warning", "Please answer all questions before submitting")
  } else {
    endQuiz();
  }
}