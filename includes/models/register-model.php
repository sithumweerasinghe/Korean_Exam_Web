<?php
require __DIR__ . "/../../vendor/autoload.php";

$client = new Google\Client();
$client->setClientId("836318542204-q1007spij3smievkk77ar7aau37etiiv.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("https://topiksir.com/api/client/auth/google/callback.php");

$client->addScope("email");
$client->addScope("profile");

$url = $client->createAuthUrl();
?>

<!-- Start Register Modal -->
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
</svg>
<div class="modal fade ed-auth__modal" id="registerModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="ed-auth__modal-content modal-content">
            <button type="button" class="ed-auth__modal-close" data-bs-dismiss="modal" aria-label="Close">
                <i class="fi-rr-cross"></i>
            </button>

            <!-- Auth Head  -->
            <div class="ed-auth__modal-head">
                <a href="index.html" class="ed-auth__modal-logo">
                    <img src="assets/images/logo.png" alt="logo" />
                </a>
                <h3 class="ed-auth__modal-title">ලියාපදිංචි වන්න</h3>
                <p class="ed-auth__modal-text">
                    දැනටමත් ගිණුමක් තිබේද?
                    <button type="button" data-bs-toggle="modal" data-bs-target="#loginModal">
                        ඇතුල් වන්න
                    </button>
                </p>
            </div>

            <!-- Auth Body  -->
            <div class="ed-auth__modal-body">
                <div class="ed-auth__modal-form">
                    <div id="SignUpAlert" class="alert alert-danger d-none d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="20" height="20" role="img" aria-label="Danger:">
                            <use xlink:href="#exclamation-triangle-fill" />
                        </svg>
                        <div id="SignUpAlertMessage" class=" fs-6"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" id="register_name" placeholder="නම ඇතුලත් කරන්න" required />
                    </div>

                    <div class="form-group">
                        <input type="email" id="register_email" placeholder="විද්‍යුත් තැපෑල ඇතුලත් කරන්න" required />
                    </div>

                    <div class="form-group">
                        <input type="password" id="register_password" placeholder="මුරපදය ඇතුළත් කරන්න" required />
                    </div>

                    <div class="form-group">
                        <input type="password" id="c_password" placeholder="මුරපදය තහවුරු කරන්න" required />
                    </div>

                    <div class="ed-auth__form-btn">
                        <button class="ed-btn" onclick="SignUp();">
                            ලියාපදිංචි වන්න<i class="fi fi-rr-arrow-small-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Auth Footer  -->
            <div class="ed-auth__modal-footer">
                <div class="ed-auth__modal-third-party">
                    <p>නැතහොත් ලියාපදිංචි වන්න</p>
                    <ul class="ed-auth__modal-third-party-list">
                        <li>
                            <a class="google-login d-flex align-items-center justify-content-center" href="<?= $url ?>"><img
                                    src="assets/images/icons/icon-color-google.svg" alt="icon-color-google" class="me-2"/>Google හරහා ලියාපදිංචි වන්න</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Register Modal -->