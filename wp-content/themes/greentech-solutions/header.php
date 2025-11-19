<header id="header">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img
                src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/logo.svg" alt="" class="logo"/></a>
    <nav class="links">
        <ul>
            <li><a href="#">Lorem</a></li>
            <li><a href="#">Ipsum</a></li>
            <li><a href="#">Feugiat</a></li>
            <li><a href="#">Tempus</a></li>
            <li><a href="#">Adipiscing</a></li>
        </ul>
    </nav>
    <nav class="main">
        <ul>
            <li class="search">
                <a class="fa-search" href="#search">Search</a>
                <form id="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="text" name="s" placeholder="Search" value="<?php echo get_search_query(); ?>"/>
                </form>
            </li>
            <li class="menu">
                <a class="fa-bars" href="#menu">Menu</a>
            </li>
        </ul>
    </nav>
</header>

<!-- Side menu -->
<section id="menu">

    <!-- Search -->
    <section>
        <form class="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <input type="text" name="s" placeholder="Search" value="<?php echo get_search_query(); ?>"/>
        </form>
    </section>

    <!-- Links -->
    <section>
        <ul class="links">
			<?php
			// Query for recent posts in side menu
			$menu_posts = new WP_Query( array(
				'posts_per_page' => 4,
				'post_status'    => 'publish',
			) );

			if ( $menu_posts->have_posts() ) :
				while ( $menu_posts->have_posts() ) : $menu_posts->the_post();
					?>
                    <li>
                        <a href="<?php the_permalink(); ?>">
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo wp_trim_words( get_the_excerpt(), 10 ); ?></p>
                        </a>
                    </li>
				<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
        </ul>
    </section>

    <!-- Actions -->
    <section>
        <ul class="actions stacked">
            <li><a href="<?php echo esc_url( admin_url() ); ?>" class="button large fit">Log In</a></li>
        </ul>
    </section>

</section>
