/**
 * Enhanced Face Verification Module for Korean Exam Web
 * - Checks for profile photo before verification
 * - Uses enhanced face detection API
 * - Implements 80% similarity threshold
 * - Provides detailed feedback and retry mechanism
 */

class EnhancedFaceVerification {
    constructor() {
        this.stream = null;
        this.video = null;
        this.canvas = null;
        this.ctx = null;
        this.isVerifying = false;
        this.verificationResult = null;
        this.profileImageUrl = null;
        this.hasProfilePhoto = false;
        this.requiredSimilarity = 80; // Enhanced 80% threshold
        this.lastNotificationTime = 0;
        this.notificationCooldown = 3000;
        this.verificationInterval = null;
        this.retryCount = 0;
        this.maxRetries = 3;
        
        // Make this instance available globally
        window.enhancedFaceVerification = this;
        
        this.init();
    }

    showNotification(message, type = 'info', duration = 5000) {
        const now = Date.now();
        if (now - this.lastNotificationTime < this.notificationCooldown) {
            console.log('⏳ Notification skipped (cooldown active)');
            return;
        }
        this.lastNotificationTime = now;

        if (typeof Toastify !== 'undefined') {
            const existingToasts = document.querySelectorAll('.toastify');
            existingToasts.forEach(toast => {
                if (toast.style.opacity !== '0') {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }
            });

            const colors = {
                'success': '#28a745',
                'error': '#dc3545',
                'warning': '#ffc107',
                'info': '#17a2b8'
            };

            Toastify({
                text: message,
                duration: duration,
                gravity: "top",
                position: "right",
                backgroundColor: colors[type] || colors['info'],
                stopOnFocus: true
            }).showToast();
        }
    }

    async init() {
        console.log('🚀 Initializing Enhanced Face Verification System');
        
        // Check for profile photo first
        await this.checkProfilePhoto();
        
        this.createVerificationModal();
        this.bindEvents();
    }

    async checkProfilePhoto() {
        try {
            console.log('📸 Checking profile photo availability...');
            
            const response = await fetch('api/client/check_profile_photo.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();
            
            if (result.success) {
                this.hasProfilePhoto = result.hasProfilePhoto;
                this.profileImageUrl = result.profileImageUrl;
                
                console.log('📷 Profile photo check result:', {
                    hasPhoto: this.hasProfilePhoto,
                    url: this.profileImageUrl ? 'Available' : 'None'
                });
                
                if (!this.hasProfilePhoto) {
                    console.warn('⚠️ No profile photo found');
                }
            } else {
                console.error('❌ Profile photo check failed:', result.message);
                this.hasProfilePhoto = false;
            }
        } catch (error) {
            console.error('❌ Error checking profile photo:', error);
            this.hasProfilePhoto = false;
        }
    }

    createVerificationModal() {
        const existingModal = document.getElementById('enhancedFaceVerificationModal');
        if (existingModal) {
            existingModal.remove();
        }

        const modalHTML = `
            <div class="modal fade" id="enhancedFaceVerificationModal" tabindex="-1" aria-labelledby="enhancedFaceVerificationModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content" style="border-radius: 8px;">
                        <div class="modal-header text-white py-2" style="background-color: #2ca347;">
                            <h6 class="modal-title fw-bold mb-0" id="enhancedFaceVerificationModalLabel">
                                <i class="fa fa-shield me-2"></i>Enhanced Face Verification
                            </h6>
                            <button type="button" id="enhancedFvCloseBtn" class="btn btn-sm text-white ms-auto" title="Cancel" aria-label="Cancel" style="line-height:1;">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        <div class="modal-body p-3">
                            <!-- Profile Photo Warning (shown if no photo) -->
                            <div id="profilePhotoWarning" class="alert alert-warning d-none" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-exclamation-triangle me-2" style="font-size: 20px;"></i>
                                    <div>
                                        <h6 class="mb-1"><strong>Profile Photo Required</strong></h6>
                                        <p class="mb-1">Face verification requires a profile photo for comparison. Please upload your profile photo first.</p>
                                        <button class="btn btn-primary btn-sm" onclick="window.location.href='profile.php'">
                                            <i class="fa fa-upload me-1"></i>Upload Profile Photo
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Instructions -->
                            <div class="p-2 text-white bg-dark text-center mb-3" style="font-size: 13px; border-radius: 5px;">
                                <i class="fa fa-info-circle me-2"></i>
                                <span class="verification-instruction">Position your face clearly in the frame - 80% similarity required</span>
                            </div>
                            
                            <!-- Camera and Profile Comparison -->
                            <div class="row align-items-center g-3" id="verificationInterface">
                                <!-- Profile Image -->
                                <div class="col-6 text-center">
                                    <h6 class="fw-bold mb-2" style="font-size: 12px;">📸 Your Profile Photo</h6>
                                    <div class="position-relative d-inline-block">
                                        <img id="enhancedProfileImageRef" src="" alt="Profile Image" 
                                             class="profile-image square-frame shadow border border-2 border-success" 
                                             style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;">
                                        <div class="position-absolute verification-badge bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="top: -5px; right: -5px; width: 20px; height: 20px; font-size: 10px;">
                                            <i class="fa fa-check"></i>
                                        </div>
                                    </div>
                                    <div class="mt-1">
                                        <span class="badge bg-success px-2 py-1" style="font-size: 10px;">✓ Reference</span>
                                    </div>
                                </div>
                                
                                <!-- Camera Feed -->
                                <div class="col-6 text-center">
                                    <h6 class="fw-bold mb-2" style="font-size: 12px;">🎥 Live Camera</h6>
                                    <div class="position-relative d-inline-block camera-container">
                                        <video id="enhancedCameraVideo" autoplay muted playsinline
                                               class="camera-video square-frame shadow border border-2 border-primary" 
                                               style="width: 120px; height: 120px; object-fit: cover; transform: scaleX(-1); border-radius: 8px;">
                                        </video>
                                        <canvas id="enhancedCaptureCanvas" style="display: none;"></canvas>
                                        
                                        <!-- Verification Status Overlay -->
                                        <div id="enhancedVerificationStatus" class="position-absolute verification-status rounded-circle d-flex align-items-center justify-content-center" 
                                             style="top: -5px; right: -5px; width: 20px; height: 20px; display: none; font-size: 10px;">
                                            <i class="fa fa-check text-white d-none" id="enhancedSuccessIcon"></i>
                                            <i class="fa fa-times text-white d-none" id="enhancedFailIcon"></i>
                                            <i class="fa fa-spinner fa-spin text-white d-none" id="enhancedLoadingIcon"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Camera Status -->
                                    <div class="mt-1" id="enhancedCameraStatus">
                                        <span class="badge bg-secondary px-2 py-1" style="font-size: 10px;">📷 Not Started</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Camera Controls -->
                            <div class="mt-3 text-center" id="cameraControls">
                                <div class="alert alert-info mx-auto p-2 mb-3" style="max-width: 350px; font-size: 11px;">
                                    <i class="fa fa-lightbulb-o me-1"></i>
                                    <strong>Tips:</strong> Good lighting, clear face view, remove glasses if possible
                                </div>
                                
                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    <button id="enhancedStartCameraBtn" class="btn btn-success btn-sm">
                                        <i class="fa fa-camera me-1"></i>Enable Camera
                                    </button>
                                    <button id="enhancedVerifyBtn" class="btn btn-primary btn-sm" disabled>
                                        <i class="fa fa-shield me-1"></i>Start Verification
                                    </button>
                                    <button id="enhancedRetryBtn" class="btn btn-warning btn-sm d-none">
                                        <i class="fa fa-refresh me-1"></i>Retry
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Live Verification Status -->
                            <div class="mt-3" id="enhancedLiveStatus">
                                <div class="text-center">
                                    <div class="row g-1 align-items-center" style="font-size: 11px;">
                                        <div class="col-4">
                                            <small class="text-muted d-block">Match Score:</small>
                                            <strong class="text-primary" id="enhancedRealTimeSimilarity">0%</strong>
                                        </div>
                                        <div class="col-4">
                                            <div class="progress" style="height: 8px;">
                                                <div id="enhancedLiveProgressBar" class="progress-bar bg-secondary" role="progressbar" style="width: 0%"></div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <small class="text-muted">Required: <strong>80%+</strong></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Verification Results -->
                            <div id="enhancedVerificationResults" class="mt-3 d-none">
                                <div class="alert p-3" role="alert">
                                    <div class="d-flex align-items-start">
                                        <div class="me-2">
                                            <i class="fa fa-shield" style="font-size: 20px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-2 fw-bold" id="enhancedResultTitle"></h6>
                                            <div id="enhancedResultMessage" class="mb-2"></div>
                                            <div class="mb-2">
                                                <strong>Final Score: <span id="enhancedSimilarityScore">0</span>%</strong>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div id="enhancedSimilarityBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                                </div>
                                            </div>
                                            <div id="enhancedSuggestions" class="d-none">
                                                <small class="text-muted">
                                                    <strong>Suggestions for improvement:</strong>
                                                    <ul id="enhancedSuggestionsList" class="mb-0 mt-1"></ul>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Retry Information -->
                            <div id="retryInfo" class="mt-2 d-none">
                                <div class="text-center">
                                    <small class="text-muted">
                                        Attempt <span id="currentRetry">1</span> of <span id="maxRetryCount">${this.maxRetries}</span>
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Navigation Buttons -->
                            <div class="text-center mt-3">
                                <button id="enhancedProceedBtn" class="btn btn-success d-none" onclick="proceedToNotice()">
                                    <i class="fa fa-arrow-right me-1"></i>Continue to Exam
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    bindEvents() {
        console.log('🔗 Binding enhanced face verification events');
        
        // Close button
        const closeBtn = document.getElementById('enhancedFvCloseBtn');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.closeModal());
        }

        // Start camera button
        const startCameraBtn = document.getElementById('enhancedStartCameraBtn');
        if (startCameraBtn) {
            startCameraBtn.addEventListener('click', () => this.requestCameraAccess());
        }

        // Verify button
        const verifyBtn = document.getElementById('enhancedVerifyBtn');
        if (verifyBtn) {
            verifyBtn.addEventListener('click', () => this.startVerification());
        }

        // Retry button
        const retryBtn = document.getElementById('enhancedRetryBtn');
        if (retryBtn) {
            retryBtn.addEventListener('click', () => this.retryVerification());
        }
    }

    async showModal() {
        console.log('📱 Showing enhanced face verification modal');
        
        // Check profile photo again
        await this.checkProfilePhoto();
        
        const modal = document.getElementById('enhancedFaceVerificationModal');
        const profileWarning = document.getElementById('profilePhotoWarning');
        const verificationInterface = document.getElementById('verificationInterface');
        const cameraControls = document.getElementById('cameraControls');
        
        if (!this.hasProfilePhoto) {
            // Show warning and hide verification interface
            profileWarning.classList.remove('d-none');
            verificationInterface.classList.add('d-none');
            cameraControls.classList.add('d-none');
            
            this.showNotification('Please upload a profile photo before using face verification', 'warning', 6000);
        } else {
            // Hide warning and show verification interface
            profileWarning.classList.add('d-none');
            verificationInterface.classList.remove('d-none');
            cameraControls.classList.remove('d-none');
            
            // Set profile image
            const profileImg = document.getElementById('enhancedProfileImageRef');
            if (profileImg && this.profileImageUrl) {
                profileImg.src = this.profileImageUrl;
            }
        }
        
        // Show modal
        if (modal && typeof bootstrap !== 'undefined') {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    }

    closeModal() {
        console.log('❌ Closing enhanced face verification modal');
        
        // Stop camera and verification
        this.stopVerification();
        this.stopCamera();
        
        const modal = document.getElementById('enhancedFaceVerificationModal');
        if (modal && typeof bootstrap !== 'undefined') {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        }
    }

    async requestCameraAccess() {
        if (!this.hasProfilePhoto) {
            this.showNotification('Profile photo required for face verification', 'error');
            return;
        }

        console.log('📷 Requesting camera access...');
        
        const startBtn = document.getElementById('enhancedStartCameraBtn');
        const verifyBtn = document.getElementById('enhancedVerifyBtn');
        
        try {
            startBtn.disabled = true;
            startBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Starting Camera...';
            
            const constraints = {
                video: {
                    width: { ideal: 640 },
                    height: { ideal: 480 },
                    facingMode: 'user'
                }
            };

            this.stream = await navigator.mediaDevices.getUserMedia(constraints);
            this.video = document.getElementById('enhancedCameraVideo');
            this.canvas = document.getElementById('enhancedCaptureCanvas');
            
            if (!this.video || !this.canvas) {
                throw new Error('Video or canvas element not found');
            }

            this.video.srcObject = this.stream;
            this.ctx = this.canvas.getContext('2d');
            
            // Wait for video to be ready
            await new Promise((resolve) => {
                this.video.onloadedmetadata = () => {
                    this.canvas.width = this.video.videoWidth;
                    this.canvas.height = this.video.videoHeight;
                    resolve();
                };
            });

            // Update UI
            startBtn.innerHTML = '<i class="fa fa-check me-1"></i>Camera Ready';
            startBtn.classList.remove('btn-success');
            startBtn.classList.add('btn-outline-success');
            
            const cameraStatus = document.getElementById('enhancedCameraStatus');
            if (cameraStatus) {
                cameraStatus.innerHTML = '<span class="badge bg-success px-2 py-1" style="font-size: 10px;">✅ Camera Active</span>';
            }
            
            // Enable verify button
            verifyBtn.disabled = false;
            
            this.showNotification('Camera started successfully', 'success');
            console.log('✅ Camera access granted');

        } catch (error) {
            console.error('❌ Camera access failed:', error);
            startBtn.disabled = false;
            startBtn.innerHTML = '<i class="fa fa-camera me-1"></i>Enable Camera';
            
            this.showNotification('Camera access failed: ' + error.message, 'error');
        }
    }

    async startVerification() {
        if (!this.hasProfilePhoto) {
            this.showNotification('Profile photo required for verification', 'error');
            return;
        }

        if (!this.video || !this.canvas || !this.ctx) {
            this.showNotification('Camera not ready. Please enable camera first.', 'error');
            return;
        }

        console.log('🔍 Starting enhanced face verification...');
        
        this.isVerifying = true;
        this.retryCount++;
        
        // Update UI
        const verifyBtn = document.getElementById('enhancedVerifyBtn');
        const retryBtn = document.getElementById('enhancedRetryBtn');
        const retryInfo = document.getElementById('retryInfo');
        const currentRetrySpan = document.getElementById('currentRetry');
        
        verifyBtn.disabled = true;
        verifyBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Verifying...';
        retryBtn.classList.add('d-none');
        
        // Show retry info
        if (retryInfo && currentRetrySpan) {
            retryInfo.classList.remove('d-none');
            currentRetrySpan.textContent = this.retryCount;
        }
        
        // Show loading status
        this.showVerificationStatus('loading');
        
        // Update camera status
        const cameraStatus = document.getElementById('enhancedCameraStatus');
        if (cameraStatus) {
            cameraStatus.innerHTML = '<span class="badge bg-warning px-2 py-1" style="font-size: 10px;">🔍 Analyzing Face...</span>';
        }

        try {
            // Capture image from video
            this.ctx.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);
            const imageBlob = await this.canvasToBlob(this.canvas);
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.getElementById('csrf_token')?.value || '';
            
            // Prepare form data
            const formData = new FormData();
            formData.append('captured_image', imageBlob, 'captured.jpg');
            formData.append('csrf_token', csrfToken);
            formData.append('live_mode', 'false');
            
            // Call enhanced API
            console.log('📡 Sending to enhanced face verification API...');
            const response = await fetch('api/client/face_verification_enhanced.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            console.log('📋 Enhanced verification result:', result);
            
            if (result.success) {
                this.handleVerificationResult(result);
            } else {
                throw new Error(result.message || 'Verification failed');
            }

        } catch (error) {
            console.error('❌ Enhanced verification error:', error);
            this.handleVerificationError(error.message);
        }
    }

    handleVerificationResult(result) {
        const similarity = parseFloat(result.similarity || 0);
        const passed = result.passed || false;
        const suggestions = result.suggestions || [];
        
        console.log(`📊 Verification result: ${similarity.toFixed(1)}% similarity, ${passed ? 'PASSED' : 'FAILED'}`);
        
        this.verificationResult = {
            similarity: similarity,
            passed: passed,
            timestamp: new Date().toISOString(),
            suggestions: suggestions
        };

        // Update UI elements
        this.updateVerificationUI(result);
        this.showVerificationResults(result);
        this.showVerificationStatus(passed ? 'success' : 'failed');
        
        // Update camera status
        const cameraStatus = document.getElementById('enhancedCameraStatus');
        if (cameraStatus) {
            if (passed) {
                cameraStatus.innerHTML = '<span class="badge bg-success px-2 py-1" style="font-size: 10px;">✅ Verification Passed</span>';
            } else {
                cameraStatus.innerHTML = '<span class="badge bg-danger px-2 py-1" style="font-size: 10px;">❌ Verification Failed</span>';
            }
        }
        
        // Stop verification
        this.isVerifying = false;
        
        if (passed) {
            this.showNotification('🎉 Face verification successful! You can proceed to the exam.', 'success', 8000);
            
            // Show proceed button
            const proceedBtn = document.getElementById('enhancedProceedBtn');
            if (proceedBtn) {
                proceedBtn.classList.remove('d-none');
            }
        } else {
            if (this.retryCount < this.maxRetries) {
                this.showNotification(`Verification failed (${similarity.toFixed(1)}%). Please try again.`, 'warning', 6000);
                this.enableRetry();
            } else {
                this.showNotification('Maximum retry attempts reached. Please contact support if needed.', 'error', 8000);
                this.disableRetry();
            }
        }
    }

    handleVerificationError(errorMessage) {
        console.error('❌ Verification error:', errorMessage);
        
        this.isVerifying = false;
        this.showVerificationStatus('failed');
        
        const verifyBtn = document.getElementById('enhancedVerifyBtn');
        if (verifyBtn) {
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = '<i class="fa fa-shield me-1"></i>Start Verification';
        }
        
        // Show error message with retry option
        this.showNotification(`Verification failed: ${errorMessage}`, 'error', 6000);
        
        if (this.retryCount < this.maxRetries) {
            this.enableRetry();
        } else {
            this.disableRetry();
        }
    }

    updateVerificationUI(result) {
        const similarity = parseFloat(result.similarity || 0);
        
        // Update real-time similarity display
        const scoreDisplay = document.getElementById('enhancedRealTimeSimilarity');
        if (scoreDisplay) {
            scoreDisplay.textContent = similarity.toFixed(1) + '%';
        }

        // Update progress bar
        const progressBar = document.getElementById('enhancedLiveProgressBar');
        if (progressBar) {
            progressBar.style.width = similarity + '%';
            
            if (similarity >= 80) {
                progressBar.className = 'progress-bar bg-success';
            } else if (similarity >= 60) {
                progressBar.className = 'progress-bar bg-warning';
            } else {
                progressBar.className = 'progress-bar bg-danger';
            }
        }
    }

    showVerificationResults(result) {
        const resultsDiv = document.getElementById('enhancedVerificationResults');
        const titleElement = document.getElementById('enhancedResultTitle');
        const messageElement = document.getElementById('enhancedResultMessage');
        const scoreElement = document.getElementById('enhancedSimilarityScore');
        const barElement = document.getElementById('enhancedSimilarityBar');
        const suggestionsDiv = document.getElementById('enhancedSuggestions');
        const suggestionsList = document.getElementById('enhancedSuggestionsList');
        
        if (!resultsDiv) return;
        
        const similarity = parseFloat(result.similarity || 0);
        const passed = result.passed || false;
        const message = result.message || '';
        const suggestions = result.suggestions || [];
        
        // Update title and message
        if (titleElement) {
            titleElement.textContent = passed ? '✅ Verification Successful' : '❌ Verification Failed';
        }
        
        if (messageElement) {
            messageElement.textContent = message;
        }
        
        // Update score
        if (scoreElement) {
            scoreElement.textContent = similarity.toFixed(1);
        }
        
        // Update progress bar
        if (barElement) {
            barElement.style.width = similarity + '%';
            barElement.className = passed ? 'progress-bar bg-success' : 'progress-bar bg-danger';
        }
        
        // Update suggestions
        if (suggestions.length > 0 && suggestionsDiv && suggestionsList) {
            suggestionsList.innerHTML = '';
            suggestions.forEach(suggestion => {
                const li = document.createElement('li');
                li.textContent = suggestion;
                suggestionsList.appendChild(li);
            });
            suggestionsDiv.classList.remove('d-none');
        } else if (suggestionsDiv) {
            suggestionsDiv.classList.add('d-none');
        }
        
        // Set alert style
        const alertDiv = resultsDiv.querySelector('.alert');
        if (alertDiv) {
            alertDiv.className = passed ? 'alert alert-success p-3' : 'alert alert-danger p-3';
        }
        
        // Show results
        resultsDiv.classList.remove('d-none');
    }

    showVerificationStatus(status) {
        const statusDiv = document.getElementById('enhancedVerificationStatus');
        const successIcon = document.getElementById('enhancedSuccessIcon');
        const failIcon = document.getElementById('enhancedFailIcon');
        const loadingIcon = document.getElementById('enhancedLoadingIcon');
        
        if (!statusDiv) return;
        
        // Hide all icons
        [successIcon, failIcon, loadingIcon].forEach(icon => {
            if (icon) icon.classList.add('d-none');
        });
        
        // Show appropriate icon and style
        switch (status) {
            case 'success':
                if (successIcon) successIcon.classList.remove('d-none');
                statusDiv.style.backgroundColor = '#28a745';
                statusDiv.style.display = 'flex';
                break;
            case 'failed':
                if (failIcon) failIcon.classList.remove('d-none');
                statusDiv.style.backgroundColor = '#dc3545';
                statusDiv.style.display = 'flex';
                break;
            case 'loading':
                if (loadingIcon) loadingIcon.classList.remove('d-none');
                statusDiv.style.backgroundColor = '#ffc107';
                statusDiv.style.display = 'flex';
                break;
            default:
                statusDiv.style.display = 'none';
        }
    }

    enableRetry() {
        const verifyBtn = document.getElementById('enhancedVerifyBtn');
        const retryBtn = document.getElementById('enhancedRetryBtn');
        
        if (verifyBtn) {
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = '<i class="fa fa-shield me-1"></i>Start Verification';
        }
        
        if (retryBtn) {
            retryBtn.classList.remove('d-none');
        }
    }

    disableRetry() {
        const verifyBtn = document.getElementById('enhancedVerifyBtn');
        const retryBtn = document.getElementById('enhancedRetryBtn');
        
        if (verifyBtn) {
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<i class="fa fa-ban me-1"></i>Max Attempts Reached';
        }
        
        if (retryBtn) {
            retryBtn.classList.add('d-none');
        }
    }

    retryVerification() {
        console.log('🔄 Retrying face verification...');
        
        // Reset UI
        const resultsDiv = document.getElementById('enhancedVerificationResults');
        const retryBtn = document.getElementById('enhancedRetryBtn');
        
        if (resultsDiv) {
            resultsDiv.classList.add('d-none');
        }
        
        if (retryBtn) {
            retryBtn.classList.add('d-none');
        }
        
        // Reset verification status
        this.showVerificationStatus('');
        
        // Start verification again
        this.startVerification();
    }

    stopVerification() {
        console.log('⏹️ Stopping verification...');
        
        this.isVerifying = false;
        
        if (this.verificationInterval) {
            clearInterval(this.verificationInterval);
            this.verificationInterval = null;
        }
    }

    stopCamera() {
        console.log('📷 Stopping camera...');
        
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }
        
        if (this.video) {
            this.video.srcObject = null;
        }
    }

    canvasToBlob(canvas) {
        return new Promise((resolve) => {
            canvas.toBlob(resolve, 'image/jpeg', 0.8);
        });
    }
}

// Initialize enhanced face verification when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM ready - Initializing Enhanced Face Verification');
    window.enhancedFaceVerification = new EnhancedFaceVerification();
});

// Global function to show enhanced face verification modal
function showEnhancedFaceVerification() {
    if (window.enhancedFaceVerification) {
        window.enhancedFaceVerification.showModal();
    } else {
        console.error('Enhanced face verification not initialized');
    }
}