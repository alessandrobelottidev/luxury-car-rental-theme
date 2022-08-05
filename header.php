<!DOCTYPE html5>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title(''); ?> - <?php bloginfo('name'); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <?php wp_head(); ?>
</head>

<body>
    <header>
        <div class="contact-banner">
            <div class="inner-container">
                <div class="contact-banner__info">
                    <i class="fa-solid fa-phone"></i>
                    <a href="tel:+393515662002">CALL US</a>
                </div>
                <div class="contact-banner__info">
                    <i class="fa-solid fa-envelope"></i>
                    <a href="mailto:admin@belottidigital.com">EMAIL US</a>
                </div>
                <div class="contact-banner__info">
                    <u>DISCLAIMER: THIS IS A FICTIONAL WEBSITE</u>
                </div>
            </div>
        </div>

        <nav>
            <div class="inner-container">
                <a href="<?= get_site_url() ?>"><img src="<?= get_stylesheet_directory_uri()  . '/assets/images/logo.png)' ?>" alt="Luxury Car Rent Logo" class="logo"></a>
                <?php wp_nav_menu(array('theme_location' => 'header-menu')); ?>
                <button id="nav-button">MENU &nbsp&nbsp<i class="fa-solid fa-bars"></i></button>
            </div>
        </nav>
    </header>

    <script>
        const navButton = document.getElementById("nav-button");
        let isNavOpen = false;

        navButton.addEventListener('click', () => {
            isNavOpen = !isNavOpen;

            if (isNavOpen) {
                document.getElementById('menu-header-menu').classList.add('active');
                navButton.innerHTML = 'CLOSE &nbsp&nbsp<i class="fa-solid fa-circle-xmark"></i>';
            } else {
                document.getElementById('menu-header-menu').classList.remove('active');
                navButton.innerHTML = 'MENU &nbsp&nbsp<i class="fa-solid fa-bars"></i>';
            }
        });
    </script>