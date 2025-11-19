<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
    <title><?php wp_title( '|', true, 'right' ); ?><?php bloginfo( 'name' ); ?></title>
    <link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/sass/main.css"/>
	<?php wp_head(); ?>
</head>
<body class="is-preload">

<!-- Wrapper -->
<div id="wrapper">

	<?php get_header(); ?>

    <!-- Main -->
    <div id="main">

		<?php
		// Set posts per page for homepage
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args  = array(
			'posts_per_page' => 4,
			'paged'          => $paged,
		);

		// Use custom query if not search, otherwise use default loop
		if ( ! is_search() ) {
			query_posts( $args );
		}
		?>

		<?php if ( is_search() ) : ?>
            <header class="page-header">
                <h1 class="page-title">
					<?php
					printf( esc_html__( 'Search Results for: %s', 'greentech-solutions' ), '<span>' . get_search_query() . '</span>' );
					?>
                </h1>
            </header>
		<?php endif; ?>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <!-- Post -->
            <article class="post">
                <header>
                    <div class="title">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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
                    <a href="<?php the_permalink(); ?>" class="image featured">
						<?php the_post_thumbnail( 'large' ); ?>
                    </a>
				<?php endif; ?>
                <p><?php echo wp_trim_words( get_the_excerpt(), 50 ); ?></p>
                <footer>
                    <ul class="actions">
                        <li><a href="<?php the_permalink(); ?>" class="button large">Continue Reading</a></li>
                    </ul>
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
		<?php endwhile; ?>

            <!-- Pagination -->
            <ul class="actions pagination">
                <li>
					<?php if ( get_previous_posts_link() ) : ?>
						<?php previous_posts_link( '<span class="button large previous">Previous Page</span>' ); ?>
					<?php else : ?>
                        <a href="" class="disabled button large previous">Previous Page</a>
					<?php endif; ?>
                </li>
                <li>
					<?php if ( get_next_posts_link() ) : ?>
						<?php next_posts_link( '<span class="button large next">Next Page</span>' ); ?>
					<?php else : ?>
                        <a href="" class="disabled button large next">Next Page</a>
					<?php endif; ?>
                </li>
            </ul>

		<?php else : ?>
            <article class="post">
                <header>
                    <div class="title">
                        <h2>No Posts Found</h2>
                    </div>
                </header>
                <p>Sorry, no posts were found. Please check back later.</p>
            </article>
		<?php endif; ?>

    </div>

	<?php get_sidebar(); ?>

</div>

<?php get_template_part( 'scripts' ); ?>

</body>
</html>
