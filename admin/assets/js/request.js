const signOut = async () => {
    try {
        const response = await fetch('../api/admin/signout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        if (!response.ok) {
            showToast("error", 'Sign out failed. Please try again.');
        }
        const result = await response.json();
        if (result.success) {
            showToast("success", "Successfully signed out.");
            window.location.href = './';
        } else {
            showToast(result.message || "An error occurred during sign-out.", "error");
        }
    } catch (error) {
        console.error('Error:', error);
        showToast("An unexpected error occurred during sign-out.", "error");
    }
}
const sendSignInRequest = () => {
    const email = $('input[type="email"]').val().trim();
    const password = $("#your-password").val().trim();
    const csrfToken = $('input[name="csrf_token"]').val();
    if (!email) {
        showToast("error", "Email is required.");
        return;
    }
    if (!password) {
        showToast("error", "Password is required.");
        return;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showToast("error", "Please enter a valid email address.");
        return;
    }

    const payload = {
        email: email,
        password: password,
        csrf_token: csrfToken,
    };

    fetch("../api/admin/signin", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
    })
        .then((response) => {
            if (response.ok) {
                return response.json();
            }
        })
        .then((data) => {
            if (data.success) {
                showToast("success", "Login successful!");
                window.location.href = "dashboard";
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("error", "Login failed. Please try again.");
        });
};
