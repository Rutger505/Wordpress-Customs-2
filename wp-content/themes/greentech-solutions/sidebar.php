<!-- Sidebar -->
<section id="sidebar">

    <!-- Intro -->
    <section id="intro">
        <header>
            <h2>GreenTech Solutions</h2>
            <p>Lorem ipsum</p>
        </header>
    </section>

    <!-- Mini Posts -->
    <section>
        <div class="mini-posts">

			<?php
			// Query for recent posts
			$recent_posts = new WP_Query( array(
				'posts_per_page' => 4,
				'post_status'    => 'publish',
			) );

			if ( $recent_posts->have_posts() ) :
				while ( $recent_posts->have_posts() ) : $recent_posts->the_post();
					?>
                    <!-- Mini Post -->
                    <article class="mini-post">
                        <header>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <time class="published"
                                  datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time>
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
                               class="author">
								<?php echo get_avatar( get_the_author_meta( 'ID' ), 48 ); ?>
                            </a>
                        </header>
						<?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="image">
								<?php the_post_thumbnail( 'thumbnail' ); ?>
                            </a>
						<?php endif; ?>
                    </article>
				<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>

        </div>
    </section>

    <!-- Posts List -->
    <section>
        <ul class="posts">
			<?php
			// Query for recent posts in list format
			$list_posts = new WP_Query( array(
				'posts_per_page' => 5,
				'post_status'    => 'publish',
			) );

			if ( $list_posts->have_posts() ) :
				while ( $list_posts->have_posts() ) : $list_posts->the_post();
					?>
                    <li>
                        <article>
                            <header>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <time class="published"
                                      datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time>
                            </header>
							<?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" class="image">
									<?php the_post_thumbnail( array( 51, 51 ), array( 'class' => 'image' ) ); ?>
                                </a>
							<?php endif; ?>
                        </article>
                    </li>
				<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
        </ul>
    </section>

    <!-- About -->
    <section class="blurb">
        <h2>About</h2>
        <p>Mauris neque quam, fermentum ut nisl vitae, convallis maximus nisl. Sed mattis nunc id lorem euismod amet
            placerat. Vivamus porttitor magna enim, ac accumsan tortor cursus at phasellus sed ultricies.</p>
        <ul class="actions">
            <li><a href="#" class="button">Learn More</a></li>
        </ul>
    </section>

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

</section>
