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
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.generateRandomImages();
        this.hidePreloader();
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
        
        // Generate detailed results
        this.generateDetailedResults();
        
        this.showToast('Test completed! Check your results below.', 'success');
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

// Initialize the test when the page loads
document.addEventListener('DOMContentLoaded', function() {
    window.colorBlindTest = new ColorBlindTest();
});