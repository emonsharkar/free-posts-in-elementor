<?php
/**
 * Single Post Template (Free Posts in Elementor)
 *
 * Fast, safe markup for a styled single post page.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

while ( have_posts() ) :
    the_post();

    $post_id   = get_the_ID();
    $title     = get_the_title();
    $author    = get_the_author();
    $date      = get_the_date();
    $permalink = get_permalink( $post_id );

    $share_url   = rawurlencode( $permalink );
    $share_title = rawurlencode( wp_strip_all_tags( $title ) );

    $fb  = 'https://www.facebook.com/sharer/sharer.php?u=' . $share_url;
    $ln  = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $share_url;
    $tw  = 'https://twitter.com/intent/tweet?url=' . $share_url . '&text=' . $share_title;
    $eml = 'mailto:?subject=' . $share_title . '&body=' . $share_url;

    $categories = wp_get_post_categories( $post_id );

    ?>

    <main id="primary" class="fpe-single" role="main">

        <header class="fpe-hero">
            <div class="fpe-hero__inner">
                <h1 class="fpe-title"><?php echo esc_html( $title ); ?></h1>

                <div class="fpe-meta">
                    <span class="fpe-meta__author"><?php echo esc_html( $author ); ?></span>
                    <span class="fpe-meta__sep" aria-hidden="true">|</span>
                    <time class="fpe-meta__date" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( $date ); ?></time>
                </div>

                <?php if ( has_post_thumbnail() ) : ?>
                    <figure class="fpe-featured">
                        <?php the_post_thumbnail( 'large', [ 'class' => 'fpe-featured__img', 'loading' => 'lazy' ] ); ?>
                    </figure>
                <?php endif; ?>
            </div>
        </header>

        <section class="fpe-container">

            <div class="fpe-share">
                <div class="fpe-share__label"><?php echo esc_html__( 'Share This Post', 'free-posts-in-elementor' ); ?></div>
                <div class="fpe-share__links">
                    <a class="fpe-share__link" href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener noreferrer">Facebook</a>
                    <a class="fpe-share__link" href="<?php echo esc_url( $ln ); ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a>
                    <a class="fpe-share__link" href="<?php echo esc_url( $tw ); ?>" target="_blank" rel="noopener noreferrer">X</a>
                    <a class="fpe-share__link" href="<?php echo esc_url( $eml ); ?>">Email</a>
                </div>
            </div>

            <article class="fpe-content">
                <?php
                the_content();
                wp_link_pages(
                    [
                        'before' => '<nav class="page-links">',
                        'after'  => '</nav>',
                    ]
                );
                ?>
            </article>

            <section class="fpe-newsletter" aria-label="Newsletter">
                <div class="fpe-newsletter__box">
                    <h2 class="fpe-newsletter__title"><?php echo esc_html__( 'Subscribe To Our Newsletter', 'free-posts-in-elementor' ); ?></h2>
                    <p class="fpe-newsletter__subtitle"><?php echo esc_html__( 'Get updates and learn from the best', 'free-posts-in-elementor' ); ?></p>
                    <form class="fpe-newsletter__form" method="post" action="#" onsubmit="return false;">
                        <label class="screen-reader-text" for="fpe-email"><?php echo esc_html__( 'Email', 'free-posts-in-elementor' ); ?></label>
                        <input id="fpe-email" class="fpe-newsletter__input" type="email" placeholder="<?php echo esc_attr__( 'Enter your email', 'free-posts-in-elementor' ); ?>" />
                        <button class="fpe-newsletter__btn" type="button"><?php echo esc_html__( 'Send', 'free-posts-in-elementor' ); ?></button>
                    </form>
                </div>
            </section>

            <nav class="fpe-post-nav" aria-label="Post">
                <div class="fpe-post-nav__col">
                    <?php
                    $prev = get_previous_post();
                    if ( $prev ) :
                        ?>
                        <a class="fpe-post-nav__link" href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>">
                            <span class="fpe-post-nav__kicker"><?php echo esc_html__( 'Previous', 'free-posts-in-elementor' ); ?></span>
                            <span class="fpe-post-nav__title"><?php echo esc_html( get_the_title( $prev->ID ) ); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="fpe-post-nav__col fpe-post-nav__col--right">
                    <?php
                    $next = get_next_post();
                    if ( $next ) :
                        ?>
                        <a class="fpe-post-nav__link" href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>">
                            <span class="fpe-post-nav__kicker"><?php echo esc_html__( 'Next', 'free-posts-in-elementor' ); ?></span>
                            <span class="fpe-post-nav__title"><?php echo esc_html( get_the_title( $next->ID ) ); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>

            <?php
            $related_args = [
                'post_type'           => 'post',
                'posts_per_page'      => 2,
                'post__not_in'        => [ $post_id ],
                'ignore_sticky_posts' => true,
            ];

            if ( ! empty( $categories ) ) {
                $related_args['category__in'] = $categories;
            }

            $related = new WP_Query( $related_args );

            if ( $related->have_posts() ) :
                ?>
                <section class="fpe-related" aria-label="Related posts">
                    <h2 class="fpe-related__title"><?php echo esc_html__( 'More To Explore', 'free-posts-in-elementor' ); ?></h2>
                    <div class="fpe-related__grid">
                        <?php
                        while ( $related->have_posts() ) :
                            $related->the_post();
                            ?>
                            <a class="fpe-related__card" href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="fpe-related__thumb">
                                        <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy' ] ); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="fpe-related__body">
                                    <h3 class="fpe-related__cardtitle"><?php the_title(); ?></h3>
                                    <div class="fpe-related__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></div>
                                </div>
                            </a>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </section>
            <?php endif; ?>

        </section>

    </main>

    <?php
endwhile;

get_footer();
