class ColorBlindTest {
    constructor() {
        this.currentQuestion = 0;
        this.totalQuestions = 10;
        this.score = 0;
        this.testImages = [];
        this.userAnswers = [];
        this.correctAnswers = [];
        this.selectedAnswer = null;
        this.isTestActive = false;
        this.currentDisplayValue = '';
        this.skippedCount = 0;
        this.examResults = null; // Store exam results if coming from exam
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.generateRandomImages();
        this.loadExamResults();
        this.hidePreloader();
    }
    
    loadExamResults() {
        // Check if exam results are stored in sessionStorage
        const examResultsJson = sessionStorage.getItem('examResults');
        if (examResultsJson) {
            try {
                this.examResults = JSON.parse(examResultsJson);
                this.displayExamPreview();
            } catch (e) {
                console.error('Error parsing exam results:', e);
            }
        }
    }
    
    displayExamPreview() {
        if (!this.examResults) return;
        
        // Update the full preview elements
        const examScoreDisplay = document.getElementById('examScoreDisplay');
        const examPercentageDisplay = document.getElementById('examPercentageDisplay');
        const examCorrectDisplay = document.getElementById('examCorrectDisplay');
        const examWrongDisplay = document.getElementById('examWrongDisplay');
        const examUnansweredDisplay = document.getElementById('examUnansweredDisplay');
        const examStatusBadge = document.getElementById('examStatusBadge');
        const quickScore = document.getElementById('quickScore');
        
        if (examScoreDisplay) examScoreDisplay.textContent = this.examResults.totalMarks || '--';
        if (examPercentageDisplay) examPercentageDisplay.textContent = (this.examResults.percentage || '--') + '%';
        if (examCorrectDisplay) examCorrectDisplay.textContent = this.examResults.correctAnswers || '--';
        if (examWrongDisplay) examWrongDisplay.textContent = this.examResults.wrongAnswers || '--';
        if (examUnansweredDisplay) examUnansweredDisplay.textContent = this.examResults.unanswered || '--';
        if (quickScore) quickScore.textContent = (this.examResults.totalMarks || '--') + ' pts';
        
        // Update status badge
        if (examStatusBadge) {
            if (this.examResults.isPassed) {
                examStatusBadge.textContent = 'PASSED';
                examStatusBadge.className = 'badge bg-success';
            } else {
                examStatusBadge.textContent = 'FAILED';
                examStatusBadge.className = 'badge bg-danger';
            }
        }
        
        // Update section performance if available
        const readingSection = document.getElementById('readingSection');
        const listeningSection = document.getElementById('listeningSection');
        const sectionPerformance = document.getElementById('examSectionPerformance');
        
        let hasSection = false;
        
        if (this.examResults.readingTotal > 0) {
            hasSection = true;
            if (readingSection) {
                readingSection.style.display = 'block';
                const readingScore = document.getElementById('readingScore');
                const readingPercentage = document.getElementById('readingPercentage');
                if (readingScore) readingScore.textContent = `${this.examResults.readingCorrect}/${this.examResults.readingTotal}`;
                if (readingPercentage) readingPercentage.textContent = `${((this.examResults.readingCorrect / this.examResults.readingTotal) * 100).toFixed(1)}%`;
            }
        }
        
        if (this.examResults.listeningTotal > 0) {
            hasSection = true;
            if (listeningSection) {
                listeningSection.style.display = 'block';
                const listeningScore = document.getElementById('listeningScore');
                const listeningPercentage = document.getElementById('listeningPercentage');
                if (listeningScore) listeningScore.textContent = `${this.examResults.listeningCorrect}/${this.examResults.listeningTotal}`;
                if (listeningPercentage) listeningPercentage.textContent = `${((this.examResults.listeningCorrect / this.examResults.listeningTotal) * 100).toFixed(1)}%`;
            }
        }
        
        if (sectionPerformance && hasSection) {
            sectionPerformance.style.display = 'block';
        }
    }
    
    hidePreloader() {
        setTimeout(() => {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.style.display = 'none';
            }
        }, 1000);
    }
    
    bindEvents() {
        // Start button
        document.getElementById('startBtn').addEventListener('click', () => {
            this.startTest();
        });
        
        // Submit button
        document.getElementById('submitBtn').addEventListener('click', () => {
            this.submitAnswer();
        });
        
        // Number buttons (0-9)
        document.querySelectorAll('.number-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.addNumber(e.target.dataset.number);
            });
        });
        
        // Clear button
        document.getElementById('clearBtn').addEventListener('click', () => {
            this.clearDisplay();
        });
        
        // Backspace button
        document.getElementById('backspaceBtn').addEventListener('click', () => {
            this.backspace();
        });
        
        // Skip button
        document.getElementById('skipBtn').addEventListener('click', () => {
            this.skipQuestion();
        });
    }
    
    generateRandomImages() {
        // Generate array of numbers 1-100
        const allNumbers = Array.from({length: 100}, (_, i) => i + 1);
        
        // Shuffle and pick 10 random numbers
        this.testImages = this.shuffleArray(allNumbers).slice(0, this.totalQuestions);
        
        // Store correct answers
        this.correctAnswers = [...this.testImages];
        
        console.log('Generated test images:', this.testImages);
    }
    
    shuffleArray(array) {
        const shuffled = [...array];
        for (let i = shuffled.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
        }
        return shuffled;
    }
    
    startTest() {
        this.isTestActive = true;
        this.currentQuestion = 0;
        this.score = 0;
        this.userAnswers = [];
        this.skippedCount = 0;
        this.currentDisplayValue = '';
        
        // Hide start button, show test elements
        document.getElementById('startBtn').style.display = 'none';
        document.getElementById('answerSection').style.display = 'block';
        document.getElementById('loadingText').style.display = 'none';
        
        this.showQuestion();
        this.showToast('Test started! Look carefully at each image and identify the number you see.', 'info');
    }
    
    showQuestion() {
        if (this.currentQuestion >= this.totalQuestions) {
            this.showResults();
            return;
        }
        
        const imageNumber = this.testImages[this.currentQuestion];
        const imagePath = `assets/images/numbers/${imageNumber}.png`;
        
        // Update UI
        this.updateQuestionCounter();
        this.updateProgressBar();
        this.clearDisplay();
        
        // Load and display image
        const testImage = document.getElementById('testImage');
        testImage.onload = () => {
            testImage.style.display = 'block';
            this.showToast(`Question ${this.currentQuestion + 1}: What number do you see in this image?`, 'info');
        };
        
        testImage.onerror = () => {
            console.error('Failed to load image:', imagePath);
            this.showToast('Error loading image. Skipping to next question.', 'error');
            this.skipQuestion();
        };
        
        testImage.src = imagePath;
    }
    
    updateQuestionCounter() {
        document.getElementById('questionCounter').textContent = 
            `Question ${this.currentQuestion + 1} of ${this.totalQuestions}`;
    }
    
    updateProgressBar() {
        const progress = (this.currentQuestion / this.totalQuestions) * 100;
        document.getElementById('progressFill').style.width = `${progress}%`;
    }
    
    
    addNumber(digit) {
        const currentValue = this.currentDisplayValue;
        const newValue = currentValue + digit;
        
        // Check if new value is within range (0-100)
        const numValue = parseInt(newValue);
        if (numValue <= 100) {
            this.currentDisplayValue = newValue;
            this.updateDisplay();
            this.validateInput();
        }
    }
    
    backspace() {
        if (this.currentDisplayValue.length > 0) {
            this.currentDisplayValue = this.currentDisplayValue.slice(0, -1);
            this.updateDisplay();
            this.validateInput();
        }
    }
    
    clearDisplay() {
        this.currentDisplayValue = '';
        this.updateDisplay();
        this.validateInput();
    }
    
    updateDisplay() {
        const displayElement = document.getElementById('displayValue');
        displayElement.textContent = this.currentDisplayValue || '0';
    }
    
    validateInput() {
        const feedback = document.getElementById('inputFeedback');
        const submitBtn = document.getElementById('submitBtn');
        
        if (this.currentDisplayValue === '') {
            feedback.textContent = 'Enter the number you see (0-100)';
            feedback.className = 'input-feedback';
            this.selectedAnswer = null;
            submitBtn.disabled = true;
            return;
        }
        
        const number = parseInt(this.currentDisplayValue);
        
        if (isNaN(number)) {
            feedback.textContent = 'Please enter a valid number';
            feedback.className = 'input-feedback invalid';
            this.selectedAnswer = null;
            submitBtn.disabled = true;
        } else if (number < 0 || number > 100) {
            feedback.textContent = 'Number must be between 0 and 100';
            feedback.className = 'input-feedback invalid';
            this.selectedAnswer = null;
            submitBtn.disabled = true;
        } else {
            feedback.textContent = `✓ Ready to submit: ${number}`;
            feedback.className = 'input-feedback valid';
            this.selectedAnswer = number.toString();
            submitBtn.disabled = false;
        }
    }
    
    skipQuestion() {
        this.selectedAnswer = 'skipped';
        this.skippedCount++;
        this.submitAnswer();
    }
    
    submitAnswer() {
        if (!this.isTestActive) return;
        
        const correctAnswer = this.correctAnswers[this.currentQuestion];
        const userAnswer = this.selectedAnswer;
        
        // Store the answer
        this.userAnswers.push({
            question: this.currentQuestion + 1,
            correctAnswer: correctAnswer,
            userAnswer: userAnswer,
            isCorrect: userAnswer == correctAnswer && userAnswer !== 'skipped'
        });
        
        // Update score
        if (userAnswer == correctAnswer && userAnswer !== 'skipped') {
            this.score++;
            this.showToast('Correct! ✓', 'success');
        } else if (userAnswer === 'skipped') {
            this.showToast('Question skipped', 'warning');
        } else {
            this.showToast(`Incorrect. The answer was ${correctAnswer}`, 'error');
        }
        
        // Move to next question
        this.currentQuestion++;
        
        // Show next question or results after delay
        if (this.currentQuestion >= this.totalQuestions) {
            setTimeout(() => {
                this.showResults();
            }, 2000);
        } else {
            setTimeout(() => {
                this.showQuestion();
            }, 1500);
        }
    }
    
    showResults() {
        this.isTestActive = false;
        
        // Hide test area, show results
        document.getElementById('testArea').style.display = 'none';
        document.getElementById('resultsArea').style.display = 'block';
        
        // Calculate results
        const percentage = Math.round((this.score / this.totalQuestions) * 100);
        const correctCount = this.score;
        const wrongCount = this.totalQuestions - this.score - this.skippedCount;
        
        // Update result display
        document.getElementById('scoreText').textContent = `${this.score}/${this.totalQuestions}`;
        document.getElementById('correctCount').textContent = correctCount;
        document.getElementById('wrongCount').textContent = wrongCount;
        document.getElementById('skippedCount').textContent = this.skippedCount;
        
        // Set score circle color and message
        const scoreCircle = document.getElementById('scoreCircle');
        const resultMessage = document.getElementById('resultMessage');
        const resultDescription = document.getElementById('resultDescription');
        
        if (percentage >= 90) {
            resultMessage.textContent = 'Excellent!';
            resultDescription.textContent = 'Your color vision appears to be excellent. You correctly identified most or all of the numbers.';
        } else if (percentage >= 70) {
            resultMessage.textContent = 'Good';
            resultDescription.textContent = 'Your color vision appears to be good. You correctly identified most of the numbers.';
        } else if (percentage >= 50) {
            resultMessage.textContent = 'Fair';
            resultDescription.textContent = 'You may have some difficulty with color vision. Consider consulting an eye care professional.';
        } else {
            resultMessage.textContent = 'Needs Attention';
            resultDescription.textContent = 'You may have significant color vision deficiency. We recommend consulting an eye care professional.';
        }
        
        // If exam results are available, show combined results
        if (this.examResults) {
            this.showCombinedResults();
        }
        
        // Generate detailed results
        this.generateDetailedResults();
        
        this.showToast('Test completed! Check your results below.', 'success');
    }
    
    showCombinedResults() {
        // Add combined results section after the color blind test results
        const resultsContainer = document.querySelector('#resultsArea .results-container');
        
        const combinedResultsHtml = `
            <div class="mt-5">
                <div class="card border-success">
                    <div class="card-header text-white text-center py-3" style="background: linear-gradient(135deg, #28a745, #20c997);">
                        <h4 class="mb-0">
                            <i class="fa fa-graduation-cap me-2"></i>
                            Complete Assessment Results
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Exam Results Column -->
                            <div class="col-md-6">
                                <div class="card border-primary h-100">
                                    <div class="card-header text-center" style="background-color: #007bff; color: white;">
                                        <h5 class="mb-0">
                                            <i class="fa fa-book me-2"></i>Exam Results
                                        </h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="score-circle mb-3" style="width: 100px; height: 100px; background: linear-gradient(45deg, #007bff, #0056b3);">
                                            <div class="score-inner">
                                                <span class="score-number" style="font-size: 20px;">${this.examResults.totalMarks}</span>
                                                <div class="score-label" style="font-size: 12px;">Score</div>
                                            </div>
                                        </div>
                                        <h5 class="text-primary">${this.examResults.percentage}%</h5>
                                        <p class="mb-2">
                                            <span class="badge ${this.examResults.isPassed ? 'bg-success' : 'bg-danger'}">
                                                ${this.examResults.isPassed ? 'PASSED' : 'FAILED'}
                                            </span>
                                        </p>
                                        <div class="row text-center mt-3">
                                            <div class="col-6">
                                                <small class="text-muted">Correct</small>
                                                <div class="fw-bold text-success">${this.examResults.correctAnswers}</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Wrong</small>
                                                <div class="fw-bold text-danger">${this.examResults.wrongAnswers}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Color Blind Test Results Column -->
                            <div class="col-md-6">
                                <div class="card border-info h-100">
                                    <div class="card-header text-center" style="background-color: #17a2b8; color: white;">
                                        <h5 class="mb-0">
                                            <i class="fa fa-eye me-2"></i>Color Vision Test
                                        </h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="score-circle mb-3" style="width: 100px; height: 100px; background: linear-gradient(45deg, #17a2b8, #138496);">
                                            <div class="score-inner">
                                                <span class="score-number" style="font-size: 20px;">${this.score}/10</span>
                                                <div class="score-label" style="font-size: 12px;">Score</div>
                                            </div>
                                        </div>
                                        <h5 class="text-info">${Math.round((this.score / this.totalQuestions) * 100)}%</h5>
                                        <p class="mb-2">
                                            <span class="badge bg-info">
                                                ${this.score >= 7 ? 'Good Vision' : this.score >= 5 ? 'Fair Vision' : 'Needs Attention'}
                                            </span>
                                        </p>
                                        <div class="row text-center mt-3">
                                            <div class="col-6">
                                                <small class="text-muted">Correct</small>
                                                <div class="fw-bold text-success">${this.score}</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Skipped</small>
                                                <div class="fw-bold text-warning">${this.skippedCount}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Overall Summary -->
                        <div class="mt-4 text-center">
                            <div class="alert alert-success">
                                <h5 class="alert-heading">
                                    <i class="fa fa-trophy me-2"></i>Assessment Complete!
                                </h5>
                                <p class="mb-0">
                                    You have successfully completed both the exam and color vision test. 
                                    Your results have been recorded.
                                </p>
                            </div>
                            
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <a href="index.php" class="btn btn-primary">
                                    <i class="fa fa-home me-1"></i>Back to Home
                                </a>
                                <button class="btn btn-outline-secondary" onclick="window.print()">
                                    <i class="fa fa-print me-1"></i>Print Results
                                </button>
                                <button class="btn btn-outline-info" onclick="downloadResults()">
                                    <i class="fa fa-download me-1"></i>Download Results
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Insert the combined results before the action buttons
        const actionButtons = resultsContainer.querySelector('.action-buttons');
        actionButtons.insertAdjacentHTML('beforebegin', combinedResultsHtml);
        
        // Hide the original action buttons since we have new ones
        actionButtons.style.display = 'none';
    }
    
    generateDetailedResults() {
        const resultsList = document.getElementById('resultsList');
        let html = '<div class="table-responsive"><table class="table table-striped">';
        html += '<thead><tr><th>Question</th><th>Image</th><th>Correct Answer</th><th>Your Answer</th><th>Result</th></tr></thead><tbody>';
        
        this.userAnswers.forEach((answer, index) => {
            const resultIcon = answer.isCorrect ? '✅' : '❌';
            const resultClass = answer.isCorrect ? 'table-success' : 'table-danger';
            const userAnswerText = answer.userAnswer === 'skipped' ? 'Skipped' : answer.userAnswer;
            
            html += `<tr class="${resultClass}">
                <td>${answer.question}</td>
                <td><img src="assets/images/numbers/${answer.correctAnswer}.png" style="width: 50px; height: 50px; object-fit: contain;" alt="Image ${answer.correctAnswer}"></td>
                <td><strong>${answer.correctAnswer}</strong></td>
                <td>${userAnswerText}</td>
                <td>${resultIcon}</td>
            </tr>`;
        });
        
        html += '</tbody></table></div>';
        resultsList.innerHTML = html;
    }
    
    showToast(message, type = 'info') {
        const colors = {
            success: '#28a745',
            error: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8'
        };
        
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            style: {
                background: colors[type] || colors.info,
                color: '#fff',
                borderRadius: '8px',
                fontSize: '14px'
            }
        }).showToast();
    }
}

// Global functions for UI interactions
function restartTest() {
    location.reload();
}

function toggleDetailedResults() {
    const detailedResults = document.getElementById('detailedResults');
    const toggleText = document.getElementById('toggleText');
    
    if (detailedResults.style.display === 'none') {
        detailedResults.style.display = 'block';
        toggleText.textContent = 'Hide Details';
    } else {
        detailedResults.style.display = 'none';
        toggleText.textContent = 'Show Details';
    }
}

function toggleExamPreview() {
    const previewDiv = document.getElementById('examResultsFullPreview');
    const toggleText = document.getElementById('previewToggleText');
    const toggleIcon = document.getElementById('previewToggleIcon');
    
    if (previewDiv.style.display === 'none' || previewDiv.style.display === '') {
        previewDiv.style.display = 'block';
        toggleText.textContent = 'Hide Details';
        if (toggleIcon) {
            toggleIcon.className = 'fa fa-chevron-up ms-1';
        }
    } else {
        previewDiv.style.display = 'none';
        toggleText.textContent = 'Show Details';
        if (toggleIcon) {
            toggleIcon.className = 'fa fa-chevron-down ms-1';
        }
    }
}

function downloadResults() {
    const examResults = JSON.parse(sessionStorage.getItem('examResults') || '{}');
    const colorBlindTest = window.colorBlindTest;
    
    const results = {
        examResults: examResults,
        colorBlindResults: {
            score: colorBlindTest.score,
            totalQuestions: colorBlindTest.totalQuestions,
            percentage: Math.round((colorBlindTest.score / colorBlindTest.totalQuestions) * 100),
            correct: colorBlindTest.score,
            wrong: colorBlindTest.totalQuestions - colorBlindTest.score - colorBlindTest.skippedCount,
            skipped: colorBlindTest.skippedCount,
            answers: colorBlindTest.userAnswers
        },
        timestamp: new Date().toISOString()
    };
    
    const dataStr = JSON.stringify(results, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    const url = URL.createObjectURL(dataBlob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `assessment_results_${new Date().toISOString().split('T')[0]}.json`;
    link.click();
    URL.revokeObjectURL(url);
}

// Initialize the test when the page loads
document.addEventListener('DOMContentLoaded', function() {
    window.colorBlindTest = new ColorBlindTest();
});