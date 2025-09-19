function showToast(type, message) {
    let background;
    let icon;

    switch (type) {
        case "success":
            background = "linear-gradient(to right, #96c93d, #639c02) ";
            icon = "https://img.icons8.com/ios-filled/50/FFFFFF/checkmark.png";
            break;
        case "warning":
            background = "linear-gradient(to right, #f39c12, #f1c40f)";
            icon = "https://img.icons8.com/ios-filled/50/FFFFFF/error.png";
            break;
        case "error":
            background = "linear-gradient(to right, #e74c3c, #c0392b)";
            icon = "https://img.icons8.com/ios-filled/50/FFFFFF/cancel.png";
            break;
        default:
            background = "linear-gradient(to right,  #56CCF2, #2F80ED)";
            icon = "https://img.icons8.com/ios-filled/50/FFFFFF/info.png";
            break;
    }

    Toastify({
        text: message,
        avatar: icon,
        style: { background: background, },
        duration: 3000,
        gravity: "top",
        position: "right",
    }).showToast();
}

// Mobile menu enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Close mobile menu when clicking on a menu item
    const mobileMenuItems = document.querySelectorAll('.offcanvas__menu_item');
    mobileMenuItems.forEach(function(item) {
        item.addEventListener('click', function() {
            const mobileModal = document.getElementById('offcanvas-modal');
            if (mobileModal) {
                const modal = bootstrap.Modal.getInstance(mobileModal);
                if (modal) {
                    modal.hide();
                }
            }
        });
    });
    
    // Improve mobile touch experience
    const mobileToggler = document.querySelector('.mobile-menu-offcanvas-toggler');
    if (mobileToggler) {
        mobileToggler.addEventListener('touchstart', function(e) {
            e.preventDefault();
            this.click();
        });
    }
});

function SignUp() {
    var name = $("#register_name").val().trim();
    var email = $("#register_email").val().trim();
    var password = $("#register_password").val().trim();
    var c_password = $("#c_password").val().trim();

    if (!name || !email || !password || !c_password) {
        showSignUpError("සියලුම පිරවීම් අවශ්‍ය වේ.");
        return false;
    }

    var nameParts = name.split(" ").filter(part => part);
    if (nameParts.length < 2) {
        showSignUpError("මුල් නම සහ අවසන් නම ඇතුළත් කරන්න.");
        return false;
    }

    var fname = nameParts.slice(0, -1).join(" ");
    var lname = nameParts.slice(-1)[0];

    if (fname.length > 50 || lname.length > 50) {
        showSignUpError("මුල් නම සහ අවසන් නම වන්නට 50 අකුරු ඉක්මවා නොයා යුතුය.");
        return false;
    }

    var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailRegex.test(email)) {
        showSignUpError("නිවැරදි විද්‍යුත් තැපැල් ලිපිනයක් ඇතුළත් කරන්න.");
        return false;
    }

    var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=]).{8,}$/;
    if (!passwordRegex.test(password)) {
        showSignUpError("මුරපදය අකුරින් 8ක් වත් සහ එක අකුරු ලොකුක්, කුඩාක්, එකක් අංකයක්, විශේෂ ලක්ෂණයක් (@#$%^&+=) අඩංගු විය යුතුය.");
        return false;
    }

    if (password !== c_password) {
        showSignUpError("මුරපද සමාන නොවේ.");
        return false;
    }

    fetch('api/client/signup.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            first_name: fname,
            last_name: lname,
            email: email,
            password: password
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $("#SignUpAlert").addClass('d-none');
                window.location = "profile.php"
            } else {
                showError(data.message)
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        })
}

function showSignUpError(message) {
    $("#SignUpAlertMessage").text(message);
    $("#SignUpAlert").removeClass('d-none');
}
function showSignInError(message) {
    $("#SignInAlertMessage").text(message);
    $("#SignInAlert").removeClass('d-none');
}

function SignIn() {
    var email = $("#login_email").val().trim();
    var password = $("#login_password").val().trim();
    var remember_me = $("#remember_me").prop("checked");

    if (!email || !password) {
        showSignInError("සියලුම පිරවීම් අවශ්‍ය වේ.");
        return false;
    }

    var bodyData = {
        email: email,
        password: password,
    };

    if (remember_me) {
        bodyData.remember_me = true;
    }

    fetch('api/client/signin.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(bodyData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $("#SignInAlert").addClass('d-none');
                window.location = "profile.php"
            } else {
                showSignInError(data.message)
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        })
}

function UpdateProfileImage() {
    var file = $("#profile_img_uploader")[0].files[0];
    if (!file) {
        showToast("warning", "No file chosen")
        return false;
    }
    const email = $('#client_email').text().trim();
    const csrf_token = $('#csrf_token').val().trim();
    const formData = new FormData();
    formData.append('email', email);
    formData.append('image', file);
    formData.append('csrf_token', csrf_token);
    fetch('api/client/update_profile_image', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast("success", data.message)
                window.location = "papers"
            } else {
                alert("error", data.message)
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        })
}

function UpdateProfileDetails() {
    const firstName = $('#firstName').val().trim();
    const lastName = $('#lastName').val().trim();
    const mobile = $('#mobile').val().trim();
    const csrf_token = $('#csrf_token').val().trim();
    const email = $('#client_email').text().trim();

    if (firstName.length > 50 || lastName.length > 50) {
        showToast("warning", "First name and last name should not exceed 50 characters.");
        return false;
    }

    if (mobile == "") {
        const mobileRegex = /^[0]{1}[7]{1}[01245678]{1}[0-9]{7}$/;
        if (!mobileRegex.test(mobile)) {
            showToast('error', "Please enter a valid mobile number.");
            return false;
        }
    }

    var bodyData = {
        'first_name': firstName,
        'last_name': lastName,
        'email': email
    }

    if (mobile !== "") {
        bodyData.mobile = mobile;
    }

    fetch('api/client/update_profile_details.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf_token },
        body: JSON.stringify(bodyData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast("success", data.message);
                window.location = "profile"
            } else showToast("error", data.message);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function UpdatePassword() {
    var currentPassword = $("#current_password").val().trim()
    var newPassword = $("#new_password").val().trim()
    var cPassword = $("#c_password").val().trim()
    const csrf_token = $('#csrf_token').val().trim();
    const email = $('#client_email').text().trim();

    if (currentPassword == "" || newPassword == "" || cPassword == "") {
        showToast("error", "Please fill all the fields.");
        return false;
    }

    var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=]).{8,}$/;
    if (!passwordRegex.test(newPassword)) {
        showToast("error", "මුරපදය අකුරින් 8ක් වත් සහ එක අකුරු ලොකුක්, කුඩාක්, එකක් අංකයක්, විශේෂ ලක්ෂණයක් (@#$%^&+=) අඩංගු විය යුතුය.");
        return false;
    }
    if (newPassword !== cPassword) {
        showToast("error", "Password doesn't match");
        return false;
    }

    fetch('api/client/change_password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf_token },
        body: JSON.stringify({
            'email': email,
            'current_password': currentPassword,
            'new_password': newPassword
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast("success", data.message)
                window.location = "papers"
            } else {
                showToast("error", data.message)
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function Logout() {
    fetch('api/client/signout.php', {
        method: 'POST',
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast("success", data.message)
                window.location.href = 'index';
            } else {
                showToast("error", data.message)
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function placeOrder(id, name, price, isExam) {
    console.log(JSON.parse(isExam))
    let fname = $('#fname').val().trim();
    let lname = $('#lname').val().trim();
    let address = $('#address').val().trim();
    let mobile = $('#mobile').val().trim();
    let email = $('#email').val().trim();
    let city = $('#city').val().trim();
    let directPayment = $('#directPayment').prop("checked");
    let paymentGateway = $('#paymentGateway').prop("checked");
    const csrf_token = $('#csrf_token').val().trim();

    if (!fname || !lname || !email || !mobile || !address || !city) {
        showToast("error", "Please fill all the fields.");
        return false;
    }
    if (address.length > 100) {
        showToast("error", "Address should not be more than 100 characters.");
        return false;
    }
    if (!/^[0]{1}[7]{1}[01245678]{1}[0-9]{7}$/.test(mobile)) {
        showToast("error", "Enter a valid 10-digit mobile number.");
        return false;
    }
    if (!directPayment && !paymentGateway) {
        showToast("error", "Please select a payment method.");
        return false;
    }

    fetch('api/client/placeOrder.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf_token
        },
        body: JSON.stringify({
            fname: fname,
            lname: lname,
            address: address,
            mobile: mobile,
            email: email,
            payment_method: paymentGateway ? 1 : 2,
            id: id,
            isExam: JSON.parse(isExam),
            package_name: name,
            package_price: price,
            city: city
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (paymentGateway) {
                    // Put the payment variables here
                    var payment = {
                        merchant_id: data.message.merchant_id,
                        return_url: "https://topiksir.com/",
                        cancel_url: "https://topiksir.com/",
                        notify_url: "http://sample.com/notify",
                        order_id: data.message.order_id,
                        items: data.message.item,
                        amount: data.message.amount,
                        currency: data.message.currency,
                        hash: data.message.hash,
                        first_name: data.message.fname,
                        last_name: data.message.lname,
                        email: data.message.email,
                        phone: data.message.mobile,
                        address: data.message.address,
                        city: data.message.city,
                        country: "Sri Lanka",
                        delivery_address: data.message.address,
                        delivery_city: data.message.city,
                        delivery_country: "Sri Lanka",
                        custom_1: "",
                        custom_2: "",
                    };
                    payhere.startPayment(payment);
                    payhere.onCompleted = function onCompleted(orderId) {
                        updatePaymentStatus(data.message.order_id, csrf_token, isExam);
                    };
                    // Payment window closed
                    payhere.onDismissed = function onDismissed() {
                        window.location = "profile";
                        showToast("error", "Payment dismissed");
                    };
                    // Error occurred
                    payhere.onError = function onError(error) {
                        window.location = "404";
                        showToast("error", "Error:" + error);
                    };

                } else {
                    showToast("success", data.message)
                    window.location.replace("profile")
                }
            } else {
                showToast("error", data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function updatePaymentStatus(invoiceNo, csrf_token, isExam) {
    console.log(isExam)
    fetch('api/client/updatePaymentStatus.php?invoice_no=' + invoiceNo + "&isExam=" + isExam, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrf_token
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success == true) {
                showToast("success", data.message);
                window.location.replace("invoice?invoice_no=" + invoiceNo);
            } else {
                showToast("error", data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function completeOrder(invoiceNo, isExam) {
    const csrf_token = $('#csrf_token').val().trim();
    fetch('api/client/completeOrder.php?invoice_no=' + invoiceNo+"&isExam="+ isExam, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrf_token
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success == true) {
                var payment = {
                    merchant_id: data.message.merchant_id,
                    return_url: "https://topiksir.com/",
                    cancel_url: "https://topiksir.com/",
                    notify_url: "http://sample.com/notify",
                    order_id: data.message.order_id,
                    items: data.message.item,
                    amount: data.message.amount,
                    currency: data.message.currency,
                    hash: data.message.hash,
                    first_name: data.message.fname,
                    last_name: data.message.lname,
                    email: data.message.email,
                    phone: data.message.mobile,
                    address: data.message.address,
                    city: data.message.city,
                    country: "Sri Lanka",
                    delivery_address: data.message.address,
                    delivery_city: data.message.city,
                    delivery_country: "Sri Lanka",
                    custom_1: "",
                    custom_2: "",
                };
                payhere.startPayment(payment);
                payhere.onCompleted = function onCompleted(orderId) {
                    updatePaymentStatus(data.message.order_id, csrf_token, String(isExam));
                };
                // Payment window closed
                payhere.onDismissed = function onDismissed() {
                    window.location = "profile";
                    showToast("error", "Payment dismissed");
                };
                // Error occurred
                payhere.onError = function onError(error) {
                    window.location = "404";
                    showToast("error", "Error:" + error);
                };
            } else {
                showToast("error", data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function prepareForPaper(paper_id, application_no, exam_id, sample) {
    console.log('prepareForPaper called with:', { paper_id, application_no, exam_id, sample });
    
    var prepareContent = $('#prepare-content');
    var examContent = $('#exam-content');
    $('#main-content').html(prepareContent);
    prepareContent.removeClass('d-none');

    const examDetails = exam_id ? `&exam_id=${exam_id}` : '';
    const apiUrl = `api/client/prepareForPaper?paper_id=${paper_id}&application_no=${application_no}&sample=${sample}${examDetails}`;
    
    console.log('Making API call to:', apiUrl);

    fetch(apiUrl, {
        method: 'GET'
    })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('API Response:', data);
            if (data.success === true) {
                $('#main-content').addClass('d-none');
                examContent.removeClass('d-none')

                if (data.questions.length == 0) {
                    console.error('No questions available from API');
                    showToast('error', 'No questions available')
                    setTimeout(() => {
                        window.location = "index"
                    }, 2000);
                } else {
                    // Wait a bit for the DOM to be ready
                    setTimeout(() => {
                        try {
                            loadQuestions(data.questions, data.isSample, data.isExam, paper_id, exam_id);
                            console.log('loadQuestions called successfully');
                        } catch (loadError) {
                            console.error('Error in loadQuestions:', loadError);
                            showToast("error", "Error loading exam questions.");
                        }
                    }, 100);
                }
            } else {
                console.log('API returned error:', data.message);
                showToast("error", data.message);
                setTimeout(() => {
                    window.location = "index"
                }, 2000);
            }
        })
        .catch(error => {
            console.error("Fetch error details:", error);
            showToast("error", "An error occurred while preparing for the paper: " + error.message);
            setTimeout(() => {
                window.location = "index"
            }, 2000);
        });
}


