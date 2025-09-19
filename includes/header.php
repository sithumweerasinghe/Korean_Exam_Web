<?php
require_once "api/config/dbconnection.php";
?>

<!-- Mobile responsive logo sizing -->
<style>
/* Mobile responsive header logo styling */
@media (max-width: 767px) {
    /* Main header logo for mobile */
    .ed-topbar__logo img {
        max-width: 80px !important;
        height: 50px !important;
        object-fit: contain;
    }
    
    /* Mobile menu logo for mobile */
    .offcanvas-logo img {
        max-width: 80px !important;
        height: 50px !important;
        object-fit: contain;
    }
}
</style>

<div class="modal mobile-menu-modal offcanvas-modal fade" id="offcanvas-modal">
    <div class="modal-dialog offcanvas-dialog">
        <div class="modal-content">
            <div class="modal-header offcanvas-header">
                <div class="offcanvas-logo">
                    <a href="./"><img src="assets/images/logo.png" alt="topik-sir-logo" /></a>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fi fi-ss-cross"></i>
                </button>
            </div>
            <div class="mobile-menu-modal-main-body">
                <nav class="offcanvas__menu">
                    <ul class="offcanvas__menu_ul">
                        <li class="offcanvas__menu_li">
                            <a class="offcanvas__menu_item <?php echo $home ?>" href="./"> <?= $translations['home']; ?></a>
                        </li>
                        <li class="offcanvas__menu_li">
                            <a class="offcanvas__menu_item <?php echo $papers ?>" href="papers"> <?= $translations['papers']; ?></a>
                        </li>
                        <li class="offcanvas__menu_li">
                            <a class="offcanvas__menu_item <?php echo $leadboard ?>" href="leadboard"> <?= $translations['leadboard']; ?></a>
                        </li>
                        <li class="offcanvas__menu_li">
                            <a class="offcanvas__menu_item <?php echo $about ?>" href="about"> <?= $translations['about']; ?></a>
                        </li>
                        <li class="offcanvas__menu_li">
                            <a class="offcanvas__menu_item <?php echo $contact ?>" href="contact"> <?= $translations['contact']; ?></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<header class="ed-header ed-header--style2 ed-header--style4">
    <div style="padding-left: 5%;padding-right: 5%;">
        <div class="ed-header__inner">
            <div class="ed-header__left--style2">
                <div class="ed-header__left-widget--style2">
                    <div class="ed-topbar__logo">
                        <a href="./">
                            <img src="assets/images/logo.png" alt="logo" />
                        </a>
                    </div>
                </div>
            </div>
            <nav class="ed-header__navigation ed-header__center">
                <ul class="ed-header__menu">
                    <li class="<?php echo $home ?>">
                        <a href="./"><?= $translations['home']; ?></a>
                    </li>
                    <li class="<?php echo $papers ?>" style="padding-left: 20px;">
                        <a href="papers"><?= $translations['papers']; ?></a>
                    </li>
                    <li class="<?php echo $leadboard ?>" style="padding-left: 20px;">
                        <a href="leadboard"><?= $translations['leadboard']; ?></a>
                    </li>
                    <li class="<?php echo $about ?>" style="padding-left: 20px;">
                        <a href="about"><?= $translations['about']; ?></a>
                    </li>
                    <li class="<?php echo $contact ?>" style="padding-left: 20px;">
                        <a href="contact"><?= $translations['contact']; ?></a>
                    </li>

                </ul>
            </nav>
            <div class="ed-header__right">
                <div class="ed-header__action">
                    <div class="d-flex justify-content-center align-items-center pt-3">
                        <select id="language-select" class="form-select" onchange="changeLanguage(this.value)">
                            <option value="en" <?php echo ($_SESSION['lang'] ?? 'en') == 'en' ? 'selected' : ''; ?>>English</option>
                            <option value="si" <?php echo ($_SESSION['lang'] ?? 'si') == 'si' ? 'selected' : ''; ?>>සිංහල</option>
                            <option value="ko" <?php echo ($_SESSION['lang'] ?? 'ko') == 'ko' ? 'selected' : ''; ?>>한국어</option>
                        </select>
                    </div>

                    <?php
                    include("api/client/services/userService.php");
                    $userService = new UserService();
                    $userArray = $userService->validateUserLoggedIn();

                    $userId = $userArray["userId"];
                    $firstName = $userArray["fname"];
                    $lastName = $userArray["lname"];
                    $mobile = $userArray["mobile"];
                    $fullName = $userArray["fullname"];
                    $profileImage = $userArray["profile"];
                    $email = $userArray["email"];
                    $address = $userArray["address"];
                    $city = $userArray["city"];
                    $isGoogleUser = $userArray["isGoogleUser"];

                    if ($fullName) {
                    ?>
                        <label style="font-size: 14px;" class="text-black fw-medium d-none d-md-inline"><?= $firstName . " " . $lastName; ?></label>
                        <div class="d-inline-block position-relative overflow-hidden rounded-circle" style="width: 50px; height: 50px;" onclick="window.location = 'profile'">
                            <img src="<?= $profileImage ?>" alt="profile_img" class="w-100 h-100 object-fit-cover">
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="ed-topbar__info-buttons">
                            <button type="button" class="register-btn w-auto d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#registerModal">
                                <?= $translations['register']; ?>
                            </button>
                            <button type="button" class="login-btn" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <?= $translations['login']; ?>
                            </button>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <button type="button" class="mobile-menu-offcanvas-toggler" data-bs-toggle="modal" data-bs-target="#offcanvas-modal">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </button>
            </div>
        </div>
    </div>
</header>