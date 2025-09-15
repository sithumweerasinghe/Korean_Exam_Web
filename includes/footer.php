<section class="ed-call-action position-relative d-flex justify-content-center rounded-4">
    <div class="col-lg-6 col-12">
        <div class="shadow-lg">
            <a href="http://topiksir.com">
                <img src="assets/images/footer/banner.jpg" alt="call-action-img">
            </a>
        </div>
    </div>
</section>
<div class="footer-bg position-relative">
    <div class="footer-bg__img mt-4">
        <img src="assets/images/footer/footer-bg.png" alt="footer-bg-img" />
    </div>
    <footer class="ed-footer position-relative">
        <div class="ed-footer__top position-relative">
            <div class="ed-footer__shapes">
                <img class="ed-footer__shape-1" src="assets/images/footer/shape-1.svg"
                    alt="shape-1" />
                <img class="ed-footer__shape-2 rotate-ani" src="assets/images/footer/shape-2.svg"
                    alt="shape-2" />
                <img class="ed-footer__shape-3" src="assets/images/footer/shape-3.svg"
                    alt="shape-3" />
            </div>
            <div class="container ed-container">
                <div class="row g-0">
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="ed-footer__widget ed-footer__about">
                            <a href="https://topiksir.com/" class="ed-footer__logo">
                                <img src="assets/images/logo.png" alt="footer-logo" />
                            </a>
                            <p class="ed-footer__about-text">
                                <?=
                                $translations['footer']['text']
                                ?>
                            </p>
                            <ul class="ed-footer__about-social">
                                <li>
                                    <a href="https://www.facebook.com/profile.php?id=61569272885639" target="_blank"><img
                                            src="assets/images/icons/icon-dark-facebook.svg"
                                            alt="icon-dark-facebook" /></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-12">
                        <div class="ed-footer__widget">
                            <h4 class="ed-footer__widget-title"><?= $translations['footer']['links'] ?></h4>
                            <ul class="ed-footer__widget-links">
                                <li><a href="about"><?= $translations['footer']['about'] ?></a></li>
                                <li><a href="contact"><?= $translations['footer']['contact'] ?></a></li>
                                <li><a href="privacy-policy"></a><?= $translations['footer']['privacy_policy'] ?> </a></li>
                                <li><a href="terms-and-conditions"><?= $translations['footer']['terms_of_service'] ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="ed-footer__widget contact-widget">
                            <h4 class="ed-footer__widget-title"><?= $translations['footer']['contact'] ?></h4>
                            <div class="ed-footer__contact">
                                <div class="ed-footer__contact-icon">
                                    <img src="assets/images/icons/icon-phone-blue.svg"
                                        alt="icon-phone-blue" />
                                </div>
                                <div class="ed-footer__contact-info">
                                    <span><?= $translations['footer']['help'] ?></span>
                                    <a href="https://wa.me/94771444404" target="_blank">+94 77 144 4404</a>
                                </div>
                            </div>
                            <div class="ed-footer__contact">
                                <div class="ed-footer__contact-icon">
                                    <img src="assets/images/icons/icon-location-blue.svg"
                                        alt="icon-location-blue" />
                                </div>
                                <div class="ed-footer__contact-info">
                                    <span><?= $translations['footer']['place'] ?></span>
                                    <a href="#" target="_blank"> 경북 칠곡군 왜관읍 2산업단지3길 97 센템빌 102동</a>
                                </div>
                            </div>
                            <div class="ed-footer__contact">
                                <div class="ed-footer__contact-icon">
                                    <img src="assets/images/icons/icon-envelope-blue.svg"
                                        alt="icon-envelope-blue" />
                                </div>
                                <div class="ed-footer__contact-info">
                                    <span><?= $translations['footer']['mail'] ?></span>
                                    <a href="mailto:aouranetwork@gmail.com" class="text-sm">aouranetwork@gmail.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="ed-footer__widget newsletter-widget">
                            <h4 class="ed-footer__widget-title"><?= $translations['footer']['download_app'] ?></h4>
                            <div class="ed-footer__newsletter">
                                <a href="https://play.google.com/store/apps/details?id=com.aoura.kogo.app">
                                    <img src="./assets/images/footer/Google Play.png" width="230px" alt="apple store image" />
                                </a>
                                <!-- <a href="#" class="mt-2">
                                    <img src="./assets/images/footer/Apple Store.png" width="230px" alt="apple store image" />
                                </a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex align-items-center justify-content-center mb-3 ">
                <img src="assets/images/powered.png" class="powered-by-img" alt="powered_by_img">
            </div>
            <div class="ed-footer__bottom bg-transparent">
                <div class="container ed-container">
                    <div class="row">
                        <div class="col-12">
                            <p class="ed-footer__copyright-text">
                                © <?php echo date("Y"); ?> <a href="https://topiksir.com/">Topik Sir</a>. All Rights Reserved.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>