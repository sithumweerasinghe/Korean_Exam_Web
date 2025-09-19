/*
 * MIXED PROGRESSION EXAM MODE ENABLED
 * ===================================
 * Different behaviors for reading vs listening sections:
 * 
 * READING QUESTIONS:
 * - ✅ Next button ENABLED (manual progression)
 * - ❌ NO countdown timers
 * - ❌ NO auto-next functionality  
 * - ✅ Manual navigation allowed
 * - ✅ Complete 20 questions to unlock listening section
 * 
 * LISTENING QUESTIONS:
 * - ❌ Next button DISABLED 
 * - ✅ Countdown timers for each question
 * - ✅ Auto-next when timer expires
 * - ❌ No manual navigation during questions
 * - ✅ Auto progression through all listening questions
 * 
 * SECTION TRANSITION:
 * - Reading → 60-second instruction period → Listening
 * - Automatic unlock of listening section after reading completion
 * - Question map access control maintained
 */

let questions = [];
let currentQuestionIndex = 0;
let timer;
let timeLeft;
const answers = [];
let currentAudio = null;
let isAudioPlaying = false;

// Guard to avoid binding next/prev handlers multiple times
let navHandlersBound = false;
// Short re-entry lock to prevent double navigation
let navLock = false;

// Helper functions for mobile audio handling
function showAudioPlayButton(audio) {
  // Remove existing play button if any
  removeAudioPlayButton();
  
  const playButton = document.createElement('button');
  playButton.id = 'audio-play-button';
  playButton.className = 'btn btn-primary btn-lg mt-3';
  playButton.innerHTML = '<i class="fa fa-play me-2"></i>Play Audio to Continue';
  playButton.style.cssText = `
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10000;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    border-radius: 8px;
    padding: 15px 25px;
    font-size: 16px;
  `;
  
  playButton.onclick = function() {
    const playPromise = audio.play();
    if (playPromise !== undefined) {
      playPromise.then(() => {
        isAudioPlaying = true;
        document.getElementById("next-btn").disabled = true;
        removeAudioPlayButton();
      }).catch(error => {
        console.error('Manual audio play failed:', error);
        showAudioError();
      });
    }
  };
  
  document.body.appendChild(playButton);
}

function removeAudioPlayButton() {
  const playButton = document.getElementById('audio-play-button');
  if (playButton) {
    playButton.remove();
  }
}

function showAudioError() {
  if (typeof Toastify !== 'undefined') {
    Toastify({
      text: "Audio playback failed. You can proceed to the next question.",
      duration: 5000,
      gravity: "top",
      position: "center",
      backgroundColor: "#dc3545",
      stopOnFocus: true
    }).showToast();
  }
  
  // Allow proceeding even if audio fails
  document.getElementById("next-btn").disabled = false;
}

// Function to update volume for all audio elements
function updateAllAudioVolume(volume) {
  const audioElements = document.querySelectorAll('audio');
  audioElements.forEach(audio => {
    audio.volume = volume / 100;
  });
  
  // Update current playing audio
  if (currentAudio) {
    currentAudio.volume = volume / 100;
  }
  
  // Update mobile audio player
  if (mobileAudioElement) {
    mobileAudioElement.volume = volume / 100;
  }
}

// Face Detection Popup System
function showFaceDetectionPopup() {
  // Increment popup count
  faceDetectionPopupCount++;
  
  console.log(`Face detection popup ${faceDetectionPopupCount} of ${faceDetectionMaxPopups}`);
  
  // Create popup overlay
  const popup = document.createElement('div');
  popup.id = 'face-detection-popup';
  popup.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease-in-out;
  `;
  
  // Create popup content
  const popupContent = document.createElement('div');
  popupContent.style.cssText = `
    background-color: #dc3545;
    color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    max-width: 300px;
    margin: 20px;
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
    animation: slideIn 0.3s ease-out;
    position: relative;
  `;
  
  popupContent.innerHTML = `
    <button onclick="closeFaceDetectionPopup(this)" style="
      position: absolute;
      top: 8px;
      right: 8px;
      background: rgba(255,255,255,0.2);
      border: none;
      color: white;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      cursor: pointer;
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s;
    " onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">×</button>
    <div style="font-size: 32px; margin-bottom: 10px;">⚠️</div>
    <h4 style="margin: 0 0 8px 0; font-weight: bold; font-size: 16px;">Face Not Detected</h4>
    <p style="margin: 0; font-size: 14px; line-height: 1.4;">
      Please ensure your face is in front of the camera
    </p>
  `;
  
  popup.appendChild(popupContent);
  document.body.appendChild(popup);
  
  // Add CSS animations if not already added
  if (!document.getElementById('face-detection-styles')) {
    const style = document.createElement('style');
    style.id = 'face-detection-styles';
    style.textContent = `
      @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
      }
      
      @keyframes slideIn {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
      }
      
      @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
      }
    `;
    document.head.appendChild(style);
  }
  
  // Auto-dismiss popup after 3 seconds
  setTimeout(() => {
    popup.style.animation = 'fadeOut 0.3s ease-in-out';
    setTimeout(() => {
      if (popup.parentNode) {
        popup.parentNode.removeChild(popup);
      }
    }, 300);
  }, 3000);
}

// Close face detection popup manually
function closeFaceDetectionPopup(button) {
  const popup = button.closest('#face-detection-popup');
  if (popup) {
    popup.style.animation = 'fadeOut 0.3s ease-in-out';
    setTimeout(() => {
      if (popup.parentNode) {
        popup.parentNode.removeChild(popup);
      }
    }, 300);
  }
}

// Random Timing System for Face Detection
function initializeFaceDetectionSystem() {
  // Clear any existing timers
  clearFaceDetectionTimers();
  
  // Reset counter
  faceDetectionPopupCount = 0;
  faceDetectionActive = true;
  
  console.log('Initializing face detection system...');
  
  // Calculate total exam duration (reading + listening time in seconds)
  const readingMinutes = parseInt(readingTimeLeft.split(':')[0]) || 0;
  const readingSeconds = parseInt(readingTimeLeft.split(':')[1]) || 0;
  const listeningMinutes = parseInt(listeningTimeLeft.split(':')[0]) || 0;
  const listeningSecondsLeft = parseInt(listeningTimeLeft.split(':')[1]) || 0;
  
  const totalExamSeconds = (readingMinutes * 60 + readingSeconds) + (listeningMinutes * 60 + listeningSecondsLeft);
  
  console.log(`Total exam duration: ${totalExamSeconds} seconds`);
  
  // Don't initialize if exam is too short
  if (totalExamSeconds < 300) { // Less than 5 minutes
    console.log('Exam too short for face detection system');
    return;
  }
  
  // Generate 4 random intervals throughout the exam
  const intervals = [];
  const minInterval = 60; // Minimum 1 minute between popups
  const maxInterval = Math.floor(totalExamSeconds / 4); // Divide exam into 4 quarters
  
  for (let i = 0; i < faceDetectionMaxPopups; i++) {
    // Create random intervals in different quarters of the exam
    const quarterStart = Math.floor((totalExamSeconds / faceDetectionMaxPopups) * i);
    const quarterEnd = Math.floor((totalExamSeconds / faceDetectionMaxPopups) * (i + 1));
    
    // Random time within this quarter (but not too close to start/end)
    const randomTime = quarterStart + minInterval + Math.random() * (quarterEnd - quarterStart - minInterval * 2);
    intervals.push(Math.floor(randomTime) * 1000); // Convert to milliseconds
  }
  
  console.log('Face detection intervals (seconds):', intervals.map(i => i / 1000));
  
  // Schedule the popups
  intervals.forEach((interval, index) => {
    const timeout = setTimeout(() => {
      if (faceDetectionActive && faceDetectionPopupCount < faceDetectionMaxPopups) {
        showFaceDetectionPopup();
      }
    }, interval);
    
    faceDetectionTimeouts.push(timeout);
  });
}

function clearFaceDetectionTimers() {
  // Clear all existing timeouts
  faceDetectionTimeouts.forEach(timeout => clearTimeout(timeout));
  faceDetectionTimeouts = [];
  
  // Clear any intervals if used
  faceDetectionIntervals.forEach(interval => clearInterval(interval));
  faceDetectionIntervals = [];
  
  console.log('Face detection timers cleared');
}

function stopFaceDetectionSystem() {
  faceDetectionActive = false;
  clearFaceDetectionTimers();
  console.log('Face detection system stopped');
}

let totalReadingTime = 0;
let totalListeningTime = 0;
let readingTimer;
let listeningTimer;
let readingTimeLeft;
let listeningTimeLeft;
// DISABLED FOR TESTING - Time tracking variables set to 0
let readingTimeSpent = 0; // Was tracking reading time
let listeningTimeSpent = 0; // Was tracking listening time  
let totalTimeSpent = 0; // Was tracking total time
let examStartTime = Date.now(); // Keep for compatibility but not used

// Face Detection System Variables
let faceDetectionActive = false;
let faceDetectionPopupCount = 0;
let faceDetectionMaxPopups = 4;
let faceDetectionIntervals = [];
let faceDetectionTimeouts = [];

let isListeningStarted = false;
let isTransitioningToListening = false;

let isSamplePaper = false;
let isExamPaper = false;
let isReadingCompleted = false;

// Auto progression control variables
let autoProgressionEnabled = true;
let readingQuestionLimit = 20;
let listeningQuestionLimit = 20;
let questionTimeLimit = 30; // Default 30 seconds per question
let autoNextEnabled = true;
let readingAutoNextDisabled = true; // Disable auto-next for reading questions


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
    // Re-enabled section timers
    startSectionTimers();
    
    // Initialize face detection system for exam papers only
    if (isExamPaper) {
        console.log('Initializing face detection system for exam...');
        initializeFaceDetectionSystem();
    }
    
    console.log('Initializing question buttons...');
    initializeQuestionButtons();
    
    console.log('loadQuestions completed successfully');
  } catch (error) {
    console.error('Error in loadQuestions:', error);
    throw error;
  }
        updateMobileNavState();
}

// Helper function to detect if we should use desktop layout
function isDesktopLayout() {
  return window.innerWidth >= 769;
}

// Helper function to render question in desktop two-column layout
function renderDesktopLayout(question, isSampleQuestion, questionNumber, currentGroup, showGroupName, options, index) {
  const hasMedia = question.image || question.audio;
  
  if (hasMedia) {
    // Two-column layout with media on left, content on right
    return `
      <div class="question-desktop-layout">
        <!-- Left side - Media content -->
        <div class="question-media-side" style=" display: flex; flex-direction: column; align-items: center; justify-content: center; min-width: 250px;">
          ${question.audio ? `
            <div class="audio-player-container" style=" webkit-fill-available; width: 100%;">
              <audio controls style="width: 200px;">
                <source src="${question.audio}" type="audio/mpeg">
                Your browser does not support the audio element.
              </audio>
              <p class="mt-2 text-center text-muted">
              </p>
            </div>
          ` : ''}
          ${question.image ? `
            <img src="${question.image}" alt="Question Image" class="clickable-image" onclick="openImageModal('${question.image}')" title="Click to zoom" />
          ` : ''}
          
        </div>
        
        <!-- Right side - Question content -->
        <div class="question-content-side">
          <div class="question-text-area">
            ${showGroupName ? `<h5 class="mb-3 text-success">${currentGroup ?? ""}</h5>` : ""}
            ${isSampleQuestion ? `<div class="alert alert-warning mb-3">
              <i class="fa fa-info-circle me-2"></i>This is a Sample Question
            </div>` : ""}
            <h6 class="mb-3">
              ${question.question ? question.question.replace(/\n/g, '<br>') : ''}
            </h6>
          </div>
          
          <div class="question-answers-area">
            <form id="question-form">
              ${options.map((option, i) => `
                <div class="answer-option ${answers[index] === i ? 'selected' : ''}" onclick="selectAnswer(${i}, this)">
                  <input class="form-check-input" type="radio" name="question" id="option${i}" value="${i}"
                    ${answers[index] === i ? "checked" : ""} 
                    ${isSampleQuestion ? "disabled" : ""} 
                    ${isSampleQuestion && option.correctAnswer ? "checked" : ""}>
                  <label class="form-check-label" for="option${i}">
                    ${option.isMedia && option.isMedia == 1 
                      ? `<img src="${option.answer}" alt="media" style="max-width: 80px; border-radius: 8px;" class="clickable-image" onclick="openImageModal('${option.answer}')" title="Click to zoom" />` 
                      : `${String.fromCharCode(65 + i)}) ${option.answer}`}
                  </label>
                </div>
              `).join('')}
            </form>
          </div>
        </div>
      </div>
    `;
  } else {
    // Single column layout for questions without media
    return `
      <div class="question-single-column">
        ${showGroupName ? `<h5 class="mb-4 text-success">${currentGroup ?? ""}</h5>` : ""}
        ${isSampleQuestion ? `<div class="alert alert-warning mb-4">
          <i class="fa fa-info-circle me-2"></i>This is a Sample Question
        </div>` : ""}
        
        <h6 class="mb-4" style="font-size: 1.1rem; line-height: 1.6;">
          ${question.question ? question.question.replace(/\n/g, '<br>') : ''}
        </h6>
        
        <form id="question-form">
          <div class="question-answers-area">
            ${options.map((option, i) => `
              <div class="answer-option ${answers[index] === i ? 'selected' : ''}" onclick="selectAnswer(${i}, this)">
                <input class="form-check-input" type="radio" name="question" id="option${i}" value="${i}"
                  ${answers[index] === i ? "checked" : ""} 
                  ${isSampleQuestion ? "disabled" : ""} 
                  ${isSampleQuestion && option.correctAnswer ? "checked" : ""}>
                <label class="form-check-label" for="option${i}">
                  ${option.isMedia && option.isMedia == 1 
                    ? `<img src="${option.answer}" alt="media" style="max-width: 100px; border-radius: 8px;" class="clickable-image" onclick="openImageModal('${option.answer}')" title="Click to zoom" />` 
                    : `${String.fromCharCode(65 + i)}) ${option.answer}`}
                </label>
              </div>
            `).join('')}
          </div>
        </form>
      </div>
    `;
  }
}

// Helper function to render question in mobile layout
function renderMobileLayout(question, isSampleQuestion, questionNumber, currentGroup, showGroupName, options, index) {
  return `
    <div class="quiz-card p-4">
      ${showGroupName ? `<h5>${currentGroup ?? ""}</h5>` : ""}
      ${isSampleQuestion ? `<p class="text-warning">This is a Sample Question</p>` : ""}
      <h6>
        ${isSampleQuestion ? "" : `<span class="question-number">${questionNumber}.</span>`}
        ${question.question ? question.question.replace(/\n/g, '<br>') : ''}
      </h6>
      ${question.audio ? `
        <div class="mobile-audio-control mt-3 mb-3">
          <button id="mobile-audio-btn" class="btn btn-primary mobile-audio-btn" onclick="toggleMobileAudio()" type="button">
            <i class="fa fa-play me-2" id="mobile-audio-icon"></i>
            <span id="mobile-audio-text">Play Audio</span>
          </button>
          <audio id="mobile-audio-player" src="${question.audio}" preload="auto" style="display: none;"></audio>
        </div>` : ""}
      ${question.image ? `<div class="col-lg-12 mt-3 mb-3">
              <img src="${question.image}" width="100%" style="max-width: 350px;" alt="Image" class="img-fluid clickable-image" onclick="openImageModal('${question.image}')" title="Click to zoom">
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
                ? `<img src="${option.answer}" alt="media" width="100px" class="clickable-image" onclick="openImageModal('${option.answer}')" title="Click to zoom" />` 
                : `${i + 1}) ${option.answer}`}
            </label>
          </div>
          ${option.isMedia && option.isMedia == 1 && (i + 1) % 2 === 0 ? `<div class="w-100"></div>` : ""}
        `).join('')}
      </form>
    </div>
  `;
}

// Function to handle answer selection in desktop layout
function selectAnswer(optionIndex, element) {
  // Remove selected class from all options
  document.querySelectorAll('.answer-option').forEach(opt => opt.classList.remove('selected'));
  
  // Add selected class to clicked option
  element.classList.add('selected');
  
  // Check the radio button
  const radio = element.querySelector('input[type="radio"]');
  if (radio) {
    radio.checked = true;
    
    // Save the answer
    saveAnswer();
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

  // Clean up mobile audio controls
  cleanupMobileAudio();

  // For mobile layout with audio, we'll use the new audio control button
  // No auto-play, user must manually trigger audio playback
  if (question.audio && !isDesktopLayout()) {
    // Audio will be handled by the mobile audio control button
    // No automatic playback
    console.log('Mobile audio question detected, controls will be available in UI');
  } else if (question.audio && isDesktopLayout()) {
    // Desktop layout - keep existing behavior if needed
    currentAudio = new Audio(question.audio);
    
    // Apply current volume setting
    const savedVolume = localStorage.getItem('examVolume') || '50';
    currentAudio.volume = parseInt(savedVolume) / 100;

    // Desktop may have different audio handling
    currentAudio.onended = () => {
      isAudioPlaying = false;
      updateMobileNavState();
    };

    currentAudio.onerror = (error) => {
      console.error('Desktop audio playback error:', error);
      isAudioPlaying = false;
      showAudioError();
      updateMobileNavState();
    };
  }

  if (question.question_category === "listening") {
    // Re-enabled listening timer
    startListeningTimer();
    updateMobileNavState();
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

  // Use appropriate layout based on screen size
  if (isDesktopLayout()) {
    questionContainer.innerHTML = renderDesktopLayout(
      question, 
      isSampleQuestion, 
      questionNumber, 
      currentGroup, 
      showGroupName, 
      options,
      index
    );
  } else {
    questionContainer.innerHTML = renderMobileLayout(
      question, 
      isSampleQuestion, 
      questionNumber, 
      currentGroup, 
      showGroupName, 
      options,
      index
    );
  }

  document.getElementById("prev-btn").style.display = index > 0 ? "inline-block" : "none";
  
  // Check if this is the last question (excluding sample questions)
  const nonSampleQuestions = questions.filter(q => !q.questionIsSample);
  const currentNonSampleIndex = questions.slice(0, index + 1).filter(q => !q.questionIsSample).length;
  const isLastQuestion = currentNonSampleIndex >= nonSampleQuestions.length;
  
  // Show/hide next button and submit button based on last question
  const nextBtn = document.getElementById("next-btn");
  const submitBtns = document.querySelectorAll('.submit-answers-btn, [onclick*="submitAnswers"]');
  
  if (isLastQuestion) {
    if (nextBtn) nextBtn.style.display = "none";
    submitBtns.forEach(btn => btn.style.display = "inline-block");
  } else {
    if (nextBtn) nextBtn.style.display = "inline-block";
    submitBtns.forEach(btn => btn.style.display = "none");
  }
  
  updateMobileNavState();

  // Update question counter (exclude samples)
  updateQuestionCounter();

  const radioButtons = document.querySelectorAll('input[name="question"]');
  radioButtons.forEach((radio) => {
    radio.addEventListener("change", () => {
      saveAnswer();
    });
  });

  if (question.question_category === "listening") {
    // Re-enabled individual question timer for listening
    setupTimer(Number(question.timeLimit));
  } else if (question.question_category === "reading") {
    // For reading questions: NO timer, enable next button
    if (timer) {
      clearInterval(timer);
    }
    // Enable next button for reading questions
    const nextBtn = document.getElementById("next-btn");
    if (nextBtn) {
      nextBtn.disabled = false;
      nextBtn.style.opacity = '1';
    }
    // Hide timer display for reading questions
    const timerDisplay = document.getElementById("question-timer");
    if (timerDisplay) {
      timerDisplay.style.display = 'none';
    }
  } else if (timer) {
    // Re-enabled timer clearing for other types
    clearInterval(timer);
  }
  
  // Only disable next button for listening questions in auto progression mode
  if (autoProgressionEnabled && question.question_category === "listening") {
    const nextBtn = document.getElementById("next-btn");
    if (nextBtn) {
      nextBtn.disabled = true;
      nextBtn.style.opacity = '0.5';
    }
  }
  
  // Setup audio controls for desktop layout
  if (isDesktopLayout() && question.audio) {
    setupDesktopAudioControls(question.audio);
  }
}

// Function to setup desktop audio controls
function setupDesktopAudioControls(audioSrc) {
  const audioElement = document.querySelector('audio');
  if (audioElement) {
    // Apply current volume setting
    const savedVolume = localStorage.getItem('examVolume') || '50';
    audioElement.volume = parseInt(savedVolume) / 100;
    
    // Store reference to current audio
    currentAudio = audioElement;
    
    // Add event listeners for audio controls
    audioElement.addEventListener('play', () => {
      isAudioPlaying = true;
      document.getElementById("next-btn").disabled = true;
      updateMobileNavState();
    });
    
    audioElement.addEventListener('ended', () => {
      isAudioPlaying = false;
      document.getElementById("next-btn").disabled = false;
      updateMobileNavState();
    });
    
    audioElement.addEventListener('pause', () => {
      isAudioPlaying = false;
      document.getElementById("next-btn").disabled = false;
      updateMobileNavState();
    });
    
    // Handle audio errors
    audioElement.addEventListener('error', (error) => {
      console.error('Audio playback error:', error);
      isAudioPlaying = false;
      document.getElementById("next-btn").disabled = false;
      showAudioError();
      updateMobileNavState();
    });
  }
}

// Add window resize listener to switch layouts dynamically
window.addEventListener('resize', () => {
  // Redisplay current question if layout should change
  if (questions.length > 0 && currentQuestionIndex < questions.length) {
    const wasPlaying = isAudioPlaying;
    displayQuestion(currentQuestionIndex);
    
    // Restore audio state if it was playing
    if (wasPlaying && currentAudio) {
      currentAudio.play().catch(error => console.warn('Resume audio failed:', error));
    }
  }
});


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
        // DISABLED FOR TESTING - Access restrictions commented out
        /*
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
        */
        
        // TESTING MODE - Allow navigation to any question
        cleanupMobileAudio(); // Clean up audio before navigation
        currentQuestionIndex = questionIndex;
        displayQuestion(questionIndex);
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
  // Re-enabled section timer functionality
  // Clear previous timers if they exist
  if (readingTimer) clearInterval(readingTimer);
  if (listeningTimer) clearInterval(listeningTimer);

  // Start Reading Timer
  readingTimer = setInterval(() => {
    const [minutes, seconds] = readingTimeLeft.split(":").map(Number);
    let totalSeconds = minutes * 60 + seconds;
    if (totalSeconds > 0) {
      readingTimeLeft = subtractTime(readingTimeLeft, 1);
      readingTimeSpent++; // Track time spent
      $("#reading-remaining").html(readingTimeLeft);
    } else {
      clearInterval(readingTimer);
      isReadingCompleted = true;
      showListeningInstructions()
    }
  }, 1000);
}

function startListeningTimer() {
  // Re-enabled listening timer functionality
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
        listeningTimeSpent++; // Track time spent
        $("#listening-remaining").html(listeningTimeLeft);
      } else {
        clearInterval(listeningTimer);
        // Stop face detection when listening time is up
        stopFaceDetectionSystem();
      }
    }, 1000);
  }
}

function setupTimer(duration) {
  // Re-enabled timer functionality for listening questions only
  timeLeft = duration;
  
  // Get appropriate timer display element
  let timerDisplay = document.getElementById("listening-timer-single");
  
  // If listening timer doesn't exist, create or use a general timer display
  if (!timerDisplay) {
    timerDisplay = document.getElementById("question-timer") || createTimerDisplay();
  }
  
  if (timerDisplay) {
    timerDisplay.innerHTML = formatTime(timeLeft);
    timerDisplay.style.display = 'block'; // Show timer for listening questions
  }

  if (timer) clearInterval(timer);
  timer = setInterval(() => {
    timeLeft--;
    if (timerDisplay) {
      timerDisplay.textContent = formatTime(timeLeft);
    }
    
    if (timeLeft <= 0) {
      clearInterval(timer);
      // Only auto-advance for listening questions
      showToast("info", "Time's up! Moving to next question...");
      setTimeout(() => {
        autoNextQuestion();
      }, 1000);
    }
  }, 1000);
}

// Helper function to create timer display if it doesn't exist
function createTimerDisplay() {
  const existingTimer = document.getElementById("question-timer");
  if (existingTimer) return existingTimer;
  
  const timerElement = document.createElement("div");
  timerElement.id = "question-timer";
  timerElement.className = "timer-display";
  timerElement.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: #2ca347;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    font-weight: bold;
    font-size: 18px;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
  `;
  document.body.appendChild(timerElement);
  return timerElement;
}

// Auto progression function
function autoNextQuestion() {
  const currentQuestion = questions[currentQuestionIndex];
  
  // Save current answer
  saveAnswer();
  
  // Check section progression rules
  if (currentQuestion.question_category === "reading") {
    // For reading questions, this should only be called manually via next button
    // Check if we've completed the reading section
    if (currentQuestionIndex + 1 >= readingQuestionLimit) {
      // Transition to listening section
      isReadingCompleted = true;
      showListeningInstructions();
      return;
    }
  } else if (currentQuestion.question_category === "listening") {
    // For listening questions, this is auto-called by timer
    // Continue normal progression
  }
  
  // Regular progression
  if (currentQuestionIndex < questions.length - 1) {
    currentQuestionIndex++;
    displayQuestion(currentQuestionIndex);
    // Update question map to reflect new position
    renderQuestionMap(false);
  } else {
    // End of quiz
    endQuiz();
  }
}

// Check if user can access a specific question
function canAccessQuestion(questionIndex) {
  const question = questions[questionIndex];
  
  if (!question) return false;
  
  // If reading section is not completed, can only access reading questions
  if (!isReadingCompleted && question.question_category === "listening") {
    return false;
  }
  
  // If in reading section, can only access up to current progress or completed questions
  if (question.question_category === "reading") {
    return questionIndex <= currentQuestionIndex || questionIndex < readingQuestionLimit;
  }
  
  // If in listening section, can access if reading is completed
  if (question.question_category === "listening" && isReadingCompleted) {
    return true;
  }
  
  return true;
}

function formatTime(seconds) {
  const minutes = Math.floor(seconds / 60);
  const remainingSeconds = seconds % 60;
  return `${minutes.toString().padStart(2, "0")}:${remainingSeconds.toString().padStart(2, "0")}`;
}

// Initialize event listeners when DOM is ready or when buttons are available
function initializeQuestionButtons() {
  if (navHandlersBound) return; // already bound
  const nextBtn = document.getElementById("next-btn");
  if (nextBtn) {
    nextBtn.addEventListener("click", async () => {
      if (navLock) return; navLock = true;
      try {
        saveAnswer();
        nextQuestion();
      } finally {
        setTimeout(() => { navLock = false; }, 150);
      }
    });
  }

  const prevBtn = document.getElementById("prev-btn");
  if (prevBtn) {
    prevBtn.addEventListener("click", () => {
      if (navLock) return; navLock = true;
      try {
        saveAnswer();
        previousQuestion();
      } finally {
        setTimeout(() => { navLock = false; }, 150);
      }
    });
  }
  navHandlersBound = true;
}

// Try to initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initializeQuestionButtons);

// Also provide a way to initialize manually when elements are created dynamically
window.initializeQuestionButtons = initializeQuestionButtons;


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
  // Re-render question map to reflect answered state
  renderQuestionMap(false);
}

function nextQuestion() {
  saveAnswer();

  // Clean up mobile audio before moving to next question
  cleanupMobileAudio();

  const currentQuestion = questions[currentQuestionIndex];
  
  // Check if we're completing the reading section
  if (currentQuestion.question_category === "reading" && 
      currentQuestionIndex + 1 >= readingQuestionLimit) {
    // Transition to listening section
    isReadingCompleted = true;
    showListeningInstructions();
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
  // Clean up mobile audio before moving to previous question
  cleanupMobileAudio();

  if (currentQuestionIndex > 0) {
    currentQuestionIndex--;
    displayQuestion(currentQuestionIndex);
  }
}

function showListeningInstructions() {
  isTransitioningToListening = true;

  // Pause timers and buttons
  // Re-enabled timer stopping
  stopTimers();
  document.getElementById("next-btn").disabled = true;
  document.getElementById("prev-btn").disabled = true;

  const questionContainer = document.getElementById("question-container");
  questionContainer.innerHTML = `
    <div class="card quiz-card p-5">
      <h5>Instructions for Listening Section</h5>
      <p>Please read the instructions carefully before proceeding to the listening section.</p>
      <div id="listening-instruction-timer" style="font-size: 1.5rem; font-weight: bold; color: #2ca347;">Ready to Start</div>
    </div>
  `;

  // Re-enabled instruction timer
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

      // Find first listening question index
      let firstListeningIndex = 0;
      for (let i = 0; i < questions.length; i++) {
        if (questions[i].question_category === "listening") {
          firstListeningIndex = i;
          break;
        }
      }

      // Re-enable buttons and display the first listening question
      document.getElementById("next-btn").disabled = false;
      document.getElementById("prev-btn").disabled = true; // Disable previous for first listening question
      currentQuestionIndex = firstListeningIndex;
      displayQuestion(currentQuestionIndex);

      // Start Listening timer
      startListeningTimer();
    }
  }, 1000);
}


function calculateResults() {
  let totalMarks = 0;
  let correctAnswers = 0;
  let wrongAnswers = 0;
  let unanswered = 0;
  let totalPossibleMarks = 0;
  let readingCorrect = 0;
  let listeningCorrect = 0;
  let readingTotal = 0;
  let listeningTotal = 0;

  answers.forEach((selectedIndex, index) => {
    const question = questions[index];
    if (question.questionIsSample) return; // Skip sample questions
    
    // FIX: question.options is already an object, no need to parse
    const options = question.options;
    totalPossibleMarks += parseInt(question.marks);
    
    // Count by category
    if (question.question_category === 'Reading') {
      readingTotal++;
    } else if (question.question_category === 'Listening') {
      listeningTotal++;
    }

    if (selectedIndex !== null) {
      if (options[selectedIndex] && options[selectedIndex].correctAnswer === 1) {
        totalMarks += parseInt(question.marks);
        correctAnswers++;
        
        // Count correct by category
        if (question.question_category === 'Reading') {
          readingCorrect++;
        } else if (question.question_category === 'Listening') {
          listeningCorrect++;
        }
      } else {
        wrongAnswers++;
      }
    } else {
      unanswered++;
    }
  });

  const percentage = totalPossibleMarks > 0 ? ((totalMarks / totalPossibleMarks) * 100).toFixed(1) : 0;
  
  return { 
    totalMarks, 
    correctAnswers, 
    wrongAnswers, 
    unanswered, 
    totalPossibleMarks, 
    percentage,
    readingCorrect,
    listeningCorrect,
    readingTotal,
    listeningTotal
  };
}

function stopTimers() {
  // Re-enabled timer stopping functionality
  if (readingTimer) clearInterval(readingTimer);
  if (listeningTimer) clearInterval(listeningTimer);
  if (timer) clearInterval(timer);
}

function endQuiz() {
  $("#next-btn").addClass('d-none');
  $("#prev-btn").addClass("d-none");
  // Re-enabled timer stopping
  stopTimers();
  saveAnswer();
  
  // Calculate total time spent
  totalTimeSpent = Math.floor((Date.now() - examStartTime) / 1000);
  
  const { 
    totalMarks, 
    correctAnswers, 
    wrongAnswers, 
    unanswered, 
    totalPossibleMarks, 
    percentage,
    readingCorrect,
    listeningCorrect,
    readingTotal,
    listeningTotal
  } = calculateResults();

  // Get cutoff mark (assuming 60% pass rate if not specified)
  const cutoffPercentage = 60;
  const isPassed = parseFloat(percentage) >= cutoffPercentage;
  const passStatus = isPassed ? 'PASSED' : 'FAILED';
  const passColor = isPassed ? '#28a745' : '#dc3545';
  const totalQuestions = questions.filter((question) => !question.questionIsSample).length;

  // Format time display
  const formatTimeDisplay = (seconds) => {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins}m ${secs}s`;
  };

  // Store exam results in sessionStorage for later use
  const examResults = {
    totalMarks,
    percentage,
    isPassed,
    correctAnswers,
    wrongAnswers,
    unanswered,
    totalQuestions,
    totalPossibleMarks,
    readingCorrect,
    listeningCorrect,
    readingTotal,
    listeningTotal,
    totalTimeSpent,
    readingTimeSpent,
    listeningTimeSpent
  };
  sessionStorage.setItem('examResults', JSON.stringify(examResults));

  // Display results in the same window frame instead of modal
  const questionContainer = document.getElementById("question-container");
  questionContainer.innerHTML = `
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <div class="card shadow-lg border-0" style="border-radius: 15px;">
            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #28a745, #20c997); border-radius: 15px 15px 0 0;">
              <h2 class="text-white mb-0">
                <i class="fa fa-trophy"></i> Exam Completed!
              </h2>
            </div>
            
            <div class="card-body p-5">
              <div class="text-center mb-4">
                <div class="badge badge-lg p-3 mb-3" style="background-color: ${passColor}; color: white; font-size: 1.5em; border-radius: 50px;">
                  ${passStatus}
                </div>
              </div>
              
              <div class="row mb-4">
                <div class="col-md-6">
                  <div class="card border-primary mb-3" style="border-radius: 10px;">
                    <div class="card-body text-center">
                      <h2 style="color: #2ca347; margin: 0; font-size: 3rem;">${totalMarks}</h2>
                      <h5 class="text-muted">Total Score</h5>
                      <small class="text-muted">out of ${totalPossibleMarks}</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card border-info mb-3" style="border-radius: 10px;">
                    <div class="card-body text-center">
                      <h2 style="color: #17a2b8; margin: 0; font-size: 3rem;">${percentage}%</h2>
                      <h5 class="text-muted">Percentage</h5>
                      <small class="text-muted">Pass mark: ${cutoffPercentage}%</small>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mb-4">
                <div class="col-md-4">
                  <div class="text-center p-3" style="background: #d4edda; border-radius: 10px;">
                    <i class="fa fa-check-circle fa-3x text-success mb-2"></i>
                    <h3 class="text-success">${correctAnswers}</h3>
                    <h6 class="text-muted">Correct</h6>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="text-center p-3" style="background: #f8d7da; border-radius: 10px;">
                    <i class="fa fa-times-circle fa-3x text-danger mb-2"></i>
                    <h3 class="text-danger">${wrongAnswers}</h3>
                    <h6 class="text-muted">Wrong</h6>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="text-center p-3" style="background: #fff3cd; border-radius: 10px;">
                    <i class="fa fa-question-circle fa-3x text-warning mb-2"></i>
                    <h3 class="text-warning">${unanswered}</h3>
                    <h6 class="text-muted">Unanswered</h6>
                  </div>
                </div>
              </div>

              ${readingTotal > 0 || listeningTotal > 0 ? `
              <div class="row mb-4">
                <div class="col-12">
                  <h5 class="text-center mb-3">
                    <i class="fa fa-chart-bar"></i> Section Performance
                  </h5>
                  <div class="row">
                    ${readingTotal > 0 ? `
                    <div class="col-md-6">
                      <div class="text-center p-3" style="background: #e3f2fd; border-radius: 10px;">
                        <i class="fa fa-book fa-2x text-primary mb-2"></i>
                        <h5>Reading</h5>
                        <h4 class="text-success">${readingCorrect}/${readingTotal}</h4>
                        <small class="text-muted">${readingTotal > 0 ? ((readingCorrect/readingTotal)*100).toFixed(1) : 0}% accuracy</small>
                        ${readingTimeSpent > 0 ? `<br><small class="text-info">Time: ${formatTimeDisplay(readingTimeSpent)}</small>` : ''}
                      </div>
                    </div>
                    ` : ''}
                    ${listeningTotal > 0 ? `
                    <div class="col-md-6">
                      <div class="text-center p-3" style="background: #e0f2f1; border-radius: 10px;">
                        <i class="fa fa-headphones fa-2x text-info mb-2"></i>
                        <h5>Listening</h5>
                        <h4 class="text-success">${listeningCorrect}/${listeningTotal}</h4>
                        <small class="text-muted">${listeningTotal > 0 ? ((listeningCorrect/listeningTotal)*100).toFixed(1) : 0}% accuracy</small>
                        ${listeningTimeSpent > 0 ? `<br><small class="text-info">Time: ${formatTimeDisplay(listeningTimeSpent)}</small>` : ''}
                      </div>
                    </div>
                    ` : ''}
                  </div>
                </div>
              </div>
              ` : ''}

              <div class="text-center">
                <div class="alert alert-info mb-4" style="border-radius: 10px;">
                  <i class="fa fa-info-circle"></i>
                  <strong>Next Step:</strong> You will now proceed to the Color Blind Test to complete your assessment.
                </div>
                
                <button type="button" class="btn btn-lg px-5 py-3 me-3" id="proceedToColorBlindBtn" 
                        style="background: linear-gradient(135deg, #28a745, #20c997); border: none; color: white; font-weight: bold; border-radius: 50px;">
                  <i class="fa fa-eye me-2"></i>Continue to Color Blind Test
                </button>
                
                <button type="button" class="btn btn-outline-secondary btn-lg px-5 py-3" id="viewAnswersBtn" 
                        style="border-radius: 50px;">
                  <i class="fa fa-list-alt me-2"></i>View Detailed Answers
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;

  // Handle button clicks
  document.getElementById('proceedToColorBlindBtn').addEventListener('click', function() {
    // Stop face detection system when exam is completed
    stopFaceDetectionSystem();
    
    // Save exam answers first
    const finalAnswers = answers.filter(answer => answer !== "sample").map(answer => answer === null ? 0 : answer + 1);
    
    if (!isSamplePaper) {
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
          if (data.success) {
            // Redirect to color blind test
            window.location.href = 'color-blind-test.php?exam_completed=true&paper_id=' + paperId + 
                                 (isExamPaper ? '&exam_id=' + examId : '');
          }
        })
        .catch(error => console.error('Error:', error));
    } else {
      // For sample papers, go directly to color blind test
      window.location.href = 'color-blind-test.php?exam_completed=true&paper_id=' + paperId + '&sample=true';
    }
  });

  document.getElementById('viewAnswersBtn').addEventListener('click', function() {
    const finalAnswers = answers.filter(answer => answer !== "sample").map(answer => answer === null ? 0 : answer + 1);
    
    if (isSamplePaper) {
      window.location = "answers?paper_id=" + paperId + "&answers=" + finalAnswers + "&score=" + totalMarks + "&percentage=" + percentage + "&status=" + (isPassed ? 'passed' : 'failed') + "&time_spent=" + totalTimeSpent + "&reading_time=" + readingTimeSpent + "&listening_time=" + listeningTimeSpent;
    } else {
      if (isExamPaper) {
        window.location = "answers?paper_id=" + paperId + "&answers=" + finalAnswers + "&exam_id=" + examId + "&score=" + totalMarks + "&percentage=" + percentage + "&status=" + (isPassed ? 'passed' : 'failed') + "&time_spent=" + totalTimeSpent + "&reading_time=" + readingTimeSpent + "&listening_time=" + listeningTimeSpent;
      } else {
        window.location = "answers?paper_id=" + paperId + "&answers=" + finalAnswers + "&score=" + totalMarks + "&percentage=" + percentage + "&status=" + (isPassed ? 'passed' : 'failed') + "&time_spent=" + totalTimeSpent + "&reading_time=" + readingTimeSpent + "&listening_time" + listeningTimeSpent;
      }
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

// ---- Question Counter ----
function updateQuestionCounter(){
  const counterEl = document.getElementById('question-counter');
  if (!counterEl || !Array.isArray(questions) || questions.length === 0) return;
  // Compute display index excluding sample questions
  let displayIdx = 0;
  for (let i=0;i<=currentQuestionIndex;i++){
    if (!questions[i].questionIsSample) displayIdx++;
  }
  const totalNonSample = questions.filter(q=>!q.questionIsSample).length;
  counterEl.textContent = `${displayIdx} / ${totalNonSample}`;
}

// Keep mobile floating nav buttons in sync with main prev/next
function updateMobileNavState(){
  try{
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const mobPrev = document.querySelector('.mob-prev');
    const mobNext = document.querySelector('.mob-next');
    if (mobPrev && prevBtn){
      const isPrevVisible = prevBtn && prevBtn.style.display !== 'none';
      mobPrev.style.display = isPrevVisible ? 'flex' : 'none';
    }
    if (mobNext && nextBtn){
      if (nextBtn.disabled){
        mobNext.classList.add('disabled');
      } else {
        mobNext.classList.remove('disabled');
      }
    }
  } catch(e){
    console.warn('updateMobileNavState error:', e);
  }
}

// Sync on viewport changes
window.addEventListener('resize', () => {
  updateMobileNavState();
});

// ---- Question Map Modal ----
function openQuestionMap(){
  renderQuestionMap(true);
}
function closeQuestionMap(){
  const modal = document.getElementById('questionMapModal');
  if (modal) modal.classList.add('d-none');
}
function renderQuestionMap(openIfClosed){
  const gridReading = document.getElementById('qm-grid-reading');
  const gridListening = document.getElementById('qm-grid-listening');
  const modal = document.getElementById('questionMapModal');
  if (!gridReading || !gridListening || !modal || !Array.isArray(questions)) return;

  // Only render if explicitly opening or if modal is already visible
  const isVisible = !modal.classList.contains('d-none');
  if (!openIfClosed && !isVisible) {
    return; // do nothing when hidden unless explicitly opened
  }

  const readingIndices = questions
    .map((q, idx) => ({q, idx}))
    .filter(({q}) => !q.questionIsSample && q.question_category === 'reading');
  const listeningIndices = questions
    .map((q, idx) => ({q, idx}))
    .filter(({q}) => !q.questionIsSample && q.question_category === 'listening');

  // Stats
  const total = readingIndices.length + listeningIndices.length;
  const answeredCount = [...readingIndices, ...listeningIndices].reduce((acc,{idx})=> acc + (answers[idx] !== null && answers[idx] !== undefined && answers[idx] !== 'sample' ? 1 : 0), 0);
  const remaining = total - answeredCount;
  const qmTotal = document.getElementById('qm-total');
  const qmAnswered = document.getElementById('qm-answered');
  const qmRemaining = document.getElementById('qm-remaining');
  const qmReadingCount = document.getElementById('qm-reading-count');
  const qmListeningCount = document.getElementById('qm-listening-count');
  if (qmTotal) qmTotal.textContent = `Total: ${total}`;
  if (qmAnswered) qmAnswered.textContent = `Answered: ${answeredCount}`;
  if (qmRemaining) qmRemaining.textContent = `Remaining: ${remaining}`;
  if (qmReadingCount) qmReadingCount.textContent = readingIndices.length;
  if (qmListeningCount) qmListeningCount.textContent = listeningIndices.length;

  // Build tiles
  gridReading.innerHTML = '';
  gridListening.innerHTML = '';

  const buildTile = (dispNum, idx, isListeningCategory) => {
    const tile = document.createElement('button');
    tile.type = 'button';
    tile.className = 'qtile';
    tile.textContent = dispNum;

    const isCurrent = idx === currentQuestionIndex;
    const isAnswered = answers[idx] !== null && answers[idx] !== undefined && answers[idx] !== 'sample';
    if (isAnswered) tile.classList.add('answered');
    if (isCurrent) tile.classList.add('current');

    // Re-enabled access control with new logic
    const canAccess = canAccessQuestion(idx);
    
    if (!canAccess) {
      tile.classList.add('locked');
      tile.style.opacity = '0.5';
      tile.style.cursor = 'not-allowed';
      tile.title = isListeningCategory ? 'Complete reading section first' : 'Question not accessible yet';
    } else {
      tile.addEventListener('click', () => {
        if (isTransitioningToListening) return;
        if (!canAccessQuestion(idx)) {
          showToast("warning", "Complete the reading section first!");
          return;
        }
        cleanupMobileAudio(); // Clean up audio before navigation
        currentQuestionIndex = idx;
        displayQuestion(currentQuestionIndex);
        closeQuestionMap();
      });
    }

    if (isAnswered){
      const dot = document.createElement('span');
      dot.className = 'dot';
      tile.appendChild(dot);
    }
    return tile;
  };

  // Reading numbering starts at 1
  readingIndices.forEach(({idx}, i) => {
    const tile = buildTile(i + 1, idx, false);
    gridReading.appendChild(tile);
  });
  // Listening numbering continues after reading (e.g., 21..40)
  listeningIndices.forEach(({idx}, i) => {
    const tile = buildTile(readingIndices.length + i + 1, idx, true);
    gridListening.appendChild(tile);
  });

  if (openIfClosed) {
    modal.classList.remove('d-none');
  }
}

// Expose map functions
window.openQuestionMap = openQuestionMap;
window.closeQuestionMap = closeQuestionMap;

// Image modal zoom functionality
let currentZoom = 1;
const zoomStep = 0.2;
const minZoom = 0.5;
const maxZoom = 3;

function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    
    modalImage.src = imageSrc;
    modal.style.display = 'flex';
    
    // Reset zoom when opening
    currentZoom = 1;
    modalImage.style.transform = `scale(${currentZoom})`;
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    
    // Restore body scroll
    document.body.style.overflow = '';
    
    // Reset zoom
    currentZoom = 1;
}

function zoomIn() {
    if (currentZoom < maxZoom) {
        currentZoom += zoomStep;
        updateImageZoom();
    }
}

function zoomOut() {
    if (currentZoom > minZoom) {
        currentZoom -= zoomStep;
        updateImageZoom();
    }
}

function resetZoom() {
    currentZoom = 1;
    updateImageZoom();
}

function updateImageZoom() {
    const modalImage = document.getElementById('modalImage');
    modalImage.style.transform = `scale(${currentZoom})`;
}

// Touch gesture support for mobile devices
let touchStartDistance = 0;
let initialZoom = 1;
let isDragging = false;
let startX, startY, translateX = 0, translateY = 0;

function addTouchGestures() {
    const modalImage = document.getElementById('modalImage');
    
    // Touch start handler
    modalImage.addEventListener('touchstart', function(e) {
        e.preventDefault();
        
        if (e.touches.length === 2) {
            // Two finger pinch gesture
            const touch1 = e.touches[0];
            const touch2 = e.touches[1];
            touchStartDistance = Math.hypot(
                touch2.clientX - touch1.clientX,
                touch2.clientY - touch1.clientY
            );
            initialZoom = currentZoom;
        } else if (e.touches.length === 1) {
            // Single finger drag gesture
            isDragging = true;
            const touch = e.touches[0];
            startX = touch.clientX - translateX;
            startY = touch.clientY - translateY;
        }
    });
    
    // Touch move handler
    modalImage.addEventListener('touchmove', function(e) {
        e.preventDefault();
        
        if (e.touches.length === 2) {
            // Pinch to zoom
            const touch1 = e.touches[0];
            const touch2 = e.touches[1];
            const currentDistance = Math.hypot(
                touch2.clientX - touch1.clientX,
                touch2.clientY - touch1.clientY
            );
            
            const scale = currentDistance / touchStartDistance;
            const newZoom = Math.max(minZoom, Math.min(maxZoom, initialZoom * scale));
            
            if (newZoom !== currentZoom) {
                currentZoom = newZoom;
                updateImageZoomWithTranslation();
            }
        } else if (e.touches.length === 1 && isDragging && currentZoom > 1) {
            // Drag to pan when zoomed
            const touch = e.touches[0];
            translateX = touch.clientX - startX;
            translateY = touch.clientY - startY;
            updateImageZoomWithTranslation();
        }
    });
    
    // Touch end handler
    modalImage.addEventListener('touchend', function(e) {
        e.preventDefault();
        isDragging = false;
        
        if (e.touches.length === 0) {
            // Reset translation if zoom is back to 1
            if (currentZoom <= 1) {
                translateX = 0;
                translateY = 0;
                updateImageZoomWithTranslation();
            }
        }
    });
    
    // Double tap to zoom
    let lastTapTime = 0;
    modalImage.addEventListener('touchend', function(e) {
        const currentTime = new Date().getTime();
        const tapLength = currentTime - lastTapTime;
        
        if (tapLength < 300 && tapLength > 0) {
            // Double tap detected
            if (currentZoom === 1) {
                currentZoom = 2;
            } else {
                currentZoom = 1;
                translateX = 0;
                translateY = 0;
            }
            updateImageZoomWithTranslation();
        }
        lastTapTime = currentTime;
    });
}

function updateImageZoomWithTranslation() {
    const modalImage = document.getElementById('modalImage');
    modalImage.style.transform = `scale(${currentZoom}) translate(${translateX}px, ${translateY}px)`;
}

// Enhanced open modal function with touch gestures
function enhancedOpenImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    
    modalImage.src = imageSrc;
    modal.style.display = 'flex';
    
    // Reset zoom and translation when opening
    currentZoom = 1;
    translateX = 0;
    translateY = 0;
    modalImage.style.transform = `scale(${currentZoom})`;
    
    // Add touch gestures
    addTouchGestures();
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

// Enhanced close modal function
function enhancedCloseImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    
    // Restore body scroll
    document.body.style.overflow = '';
    
    // Reset zoom and translation
    currentZoom = 1;
    translateX = 0;
    translateY = 0;
}

// Mobile Audio Control Functions
let mobileAudioIsPlaying = false;
let mobileAudioElement = null;

function toggleMobileAudio() {
    const audioBtn = document.getElementById('mobile-audio-btn');
    const audioIcon = document.getElementById('mobile-audio-icon');
    const audioText = document.getElementById('mobile-audio-text');
    const audioPlayer = document.getElementById('mobile-audio-player');
    
    if (!audioPlayer) {
        console.error('Mobile audio player not found');
        return;
    }
    
    if (mobileAudioIsPlaying) {
        // Stop audio
        stopMobileAudio();
    } else {
        // Play audio
        playMobileAudio();
    }
}

function playMobileAudio() {
    const audioBtn = document.getElementById('mobile-audio-btn');
    const audioIcon = document.getElementById('mobile-audio-icon');
    const audioText = document.getElementById('mobile-audio-text');
    const audioPlayer = document.getElementById('mobile-audio-player');
    
    if (!audioPlayer) return;
    
    // Set loading state
    audioBtn.classList.add('loading');
    audioBtn.disabled = true;
    audioText.textContent = 'Loading...';
    
    // Apply volume setting
    const savedVolume = localStorage.getItem('examVolume') || '50';
    audioPlayer.volume = parseInt(savedVolume) / 100;
    
    // Set up event listeners
    audioPlayer.onloadstart = function() {
        audioBtn.classList.add('loading');
    };
    
    audioPlayer.oncanplaythrough = function() {
        audioBtn.classList.remove('loading');
    };
    
    audioPlayer.onended = function() {
        stopMobileAudio();
    };
    
    audioPlayer.onerror = function(error) {
        console.error('Mobile audio playback error:', error);
        audioBtn.classList.remove('loading', 'playing');
        audioBtn.disabled = false;
        audioIcon.className = 'fa fa-exclamation-triangle me-2';
        audioText.textContent = 'Audio Error';
        
        if (typeof Toastify !== 'undefined') {
            Toastify({
                text: "Audio playback failed. Please try again.",
                duration: 3000,
                gravity: "top",
                position: "center",
                backgroundColor: "#dc3545",
            }).showToast();
        }
        
        setTimeout(() => {
            audioIcon.className = 'fa fa-play me-2';
            audioText.textContent = 'Play Audio';
        }, 2000);
    };
    
    // Try to play audio
    const playPromise = audioPlayer.play();
    if (playPromise !== undefined) {
        playPromise.then(() => {
            // Audio started successfully
            mobileAudioIsPlaying = true;
            mobileAudioElement = audioPlayer;
            
            audioBtn.classList.remove('loading');
            audioBtn.classList.add('playing');
            audioBtn.disabled = false;
            audioIcon.className = 'fa fa-stop me-2';
            audioText.textContent = 'Stop Audio';
            
            // Disable next button while audio is playing (if required)
            const nextBtn = document.getElementById("next-btn");
            if (nextBtn) {
                nextBtn.disabled = true;
            }
            updateMobileNavState();
            
        }).catch(error => {
            console.error('Mobile audio play failed:', error);
            audioBtn.classList.remove('loading', 'playing');
            audioBtn.disabled = false;
            audioIcon.className = 'fa fa-play me-2';
            audioText.textContent = 'Play Audio';
            
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: "Please tap the play button to start audio.",
                    duration: 3000,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#ffc107",
                }).showToast();
            }
        });
    }
}

function stopMobileAudio() {
    const audioBtn = document.getElementById('mobile-audio-btn');
    const audioIcon = document.getElementById('mobile-audio-icon');
    const audioText = document.getElementById('mobile-audio-text');
    const audioPlayer = document.getElementById('mobile-audio-player');
    
    if (audioPlayer && mobileAudioIsPlaying) {
        audioPlayer.pause();
        audioPlayer.currentTime = 0;
    }
    
    // Reset UI state
    if (audioBtn) {
        audioBtn.classList.remove('loading', 'playing');
        audioBtn.disabled = false;
    }
    if (audioIcon) {
        audioIcon.className = 'fa fa-play me-2';
    }
    if (audioText) {
        audioText.textContent = 'Play Audio';
    }
    
    mobileAudioIsPlaying = false;
    mobileAudioElement = null;
    
    // Re-enable next button
    const nextBtn = document.getElementById("next-btn");
    if (nextBtn) {
        nextBtn.disabled = false;
    }
    updateMobileNavState();
}

// Function to clean up mobile audio when changing questions
function cleanupMobileAudio() {
    if (mobileAudioElement && mobileAudioIsPlaying) {
        stopMobileAudio();
    }
}

// Expose image modal functions globally
window.openImageModal = enhancedOpenImageModal;
window.closeImageModal = enhancedCloseImageModal;
window.zoomIn = zoomIn;
window.zoomOut = zoomOut;
window.resetZoom = resetZoom;