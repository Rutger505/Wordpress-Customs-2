<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
    <title><?php wp_title( '|', true, 'right' ); ?><?php bloginfo( 'name' ); ?></title>
    <link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/sass/main.css"/>
	<?php wp_head(); ?>
</head>
<body class="single is-preload">

<!-- Wrapper -->
<div id="wrapper">

	<?php get_header(); ?>

    <!-- Menu -->
    <section id="menu">

        <!-- Search -->
        <section>
            <form class="search" method="get" action="#">
                <input type="text" name="query" placeholder="Search"/>
            </form>
        </section>

        <!-- Links -->
        <section>
            <ul class="links">
                <li>
                    <a href="#">
                        <h3>Lorem ipsum</h3>
                        <p>Feugiat tempus veroeros dolor</p>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <h3>Dolor sit amet</h3>
                        <p>Sed vitae justo condimentum</p>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <h3>Feugiat veroeros</h3>
                        <p>Phasellus sed ultricies mi congue</p>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <h3>Etiam sed consequat</h3>
                        <p>Porta lectus amet ultricies</p>
                    </a>
                </li>
            </ul>
        </section>

        <!-- Actions -->
        <section>
            <ul class="actions stacked">
                <li><a href="#" class="button large fit">Log In</a></li>
            </ul>
        </section>

    </section>

    <!-- Main -->
    <div id="main">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <!-- Post -->
            <article class="post">
                <header>
                    <div class="title">
                        <h2><?php the_title(); ?></h2>
                        <p><?php echo get_the_excerpt(); ?></p>
                    </div>
                    <div class="meta">
                        <time class="published"
                              datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time>
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
                           class="author">
                            <span class="name"><?php the_author(); ?></span>
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 48 ); ?>
                        </a>
                    </div>
                </header>
				<?php if ( has_post_thumbnail() ) : ?>
                    <span class="image featured"><?php the_post_thumbnail( 'large' ); ?></span>
				<?php endif; ?>
				<?php the_content(); ?>
                <footer>
                    <ul class="stats">
                        <li><a href="#"><?php $category = get_the_category();
								if ( ! empty( $category ) ) {
									echo esc_html( $category[0]->name );
								} ?></a></li>
                        <li><a href="<?php the_permalink(); ?>#comments"
                               class="icon solid fa-heart"><?php comments_number( '0', '1', '%' ); ?></a></li>
                        <li><a href="<?php the_permalink(); ?>#comments"
                               class="icon solid fa-comment"><?php comments_number( '0', '1', '%' ); ?></a></li>
                    </ul>
                </footer>
            </article>
		<?php endwhile; endif; ?>
    </div>

    <!-- Footer -->
    <section id="footer">
        <ul class="icons">
            <li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
            <li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
            <li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
            <li><a href="#" class="icon solid fa-rss"><span class="label">RSS</span></a></li>
            <li><a href="#" class="icon solid fa-envelope"><span class="label">Email</span></a></li>
        </ul>
        <p class="copyright">&copy; Untitled.</p>
    </section>

</div>

<?php get_template_part( 'scripts' ); ?>

</body>
</html>
