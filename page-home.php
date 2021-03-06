<?php
/**
 * Template Name: Home
 * The start page
 *
 * @package Lauch
 */

get_header();
?>


<?php
while ( have_posts() ) :
the_post();
endwhile;
?>

<header class="c-page-home-header pb-10 c-index-header p-r">
  <div class="c-index-wrapper">
    <h1 class="c-index-title">Mit <span id="revolving-claims">Code</span> die<br> Welt verbessern</h1>
    <div class="c-page-content"><?php the_content(); ?></div>
    <div class="c-index-illu">
      <?php render_svg("/images/index/Illustration-Mashup-Start-02-1200-02.svg") ?>
    </div>
  </div>
</header>



<section class="c-page-section white pt-10">
  <div class="c-page-2col jc-sb c-index-events">
    <div class="col-s">
      <div class="float-box float-box--softblue float-box--index">
        <div class="c-flag softblue mini points-bottom float-box-head"><?php the_field('left_box_flag'); ?></div>
        <img src="<?php echo get_the_post_thumbnail_url(get_field('left_box_post')[0]->ID, 'events-teaser-highdpi') ?>" alt="">
        <h2 class="float-box-title"><?php the_field('left_box_title'); ?></h2>
        <p><?php the_field('left_box_text'); ?> </p>
        <a href="<?php echo get_permalink(get_field('left_box_post')[0]->ID); ?>" class="button button--simple button--softblue"><?php the_field('left_box_button'); ?></a>
      </div>

    </div>
    <div class="col-l fg">
      <h2 class="c-index-subtitle mt-1"><?php echo __('Die nächsten Termine', 'lauch'); ?></h2>

      <div class="c-toc c-toc--horizontal">
        <ul class="c-toc-nav">
          <li><a href="#events" class="hover-line-trigger">
            <span class="hover-line"><?php echo __('Events', 'lauch'); ?></span></a>
          </li>
          <li><a href="#labs"  class="hover-line-trigger">
            <span class="hover-line"><?php echo __('Labs', 'lauch'); ?></span></a></li>
        </ul>
        <div class="c-toc-content c-events-list">
          <section id="events">
            <?php
            $eventp = get_posts(array('post_type' => 'page',
                                     'meta_query' => array(
                                       array('key' => '_wp_page_template',
                                             'value' => 'event-overview.php'))))[0];

            $args = array('post_type' => 'page',
                          'post_parent' => $eventp->ID,
                          'meta_key' => 'is_active',
                          'meta_value' => 1,
                          'posts_per_page' => 4
                          );
            $the_query = new WP_Query( $args ); ?>

            <?php if ( $the_query->have_posts() ) : ?>
              <ul>
                <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                  <?php $event = get_field('next_event')[0]; ?>
                  <li>
                  <div class="event-teaser-list-item">
                    <div class="d-f ai-s">
                      <picture class="events-list-image">
                        <source srcset="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'events-teaser-s'); ?>"
                                media="(max-width: 1900px)">
                        <source srcset="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'events-teaser-highdpi'); ?>"
                                media="(min-resolution: 192dpi)">
                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'events-teaser-m'); ?>" width="90" loading="lazy">
                      </picture>
                      <div class="">
                        <div class="d-f">
                        <h3 class="events-list-title mb-0 mt-0"><?php the_title() ?></h3>
                        <time class="events-list-date" datetime="">
                          <?php the_field('datum', $event->ID); ?></time>
                        </div>
                        <div class="events-list-actions active">
                          <a
                            href="<?php the_permalink() ?>"
                            title="Mehr Infos zu Jugend hackt in <?php the_title() ?>">Mehr Infos</a>
                          <a href="<?php the_field('anmeldungslink', $event->ID); ?>"
                             title=Anmeldung für Jugend hackt in <?php the_title() ?>">Anmelden</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  </li>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
              </ul>
            <?php endif; ?>

          </section>
          <section id="labs">

            <?php
            $all_dates = array();
            $args = array('post_type' => 'lab');
            $the_query = new WP_Query( $args ); ?>

            <?php if ( $the_query->have_posts() ) :
              while ( $the_query->have_posts() ) : $the_query->the_post();
                while( have_rows('lab_events') ): the_row();
                  $info = array('lab' => $post->post_title,
                               'link' => get_post_permalink(),
                                'img' => get_sub_field('image'),
                               'title' => get_sub_field('title'),
                               'date_technical' => get_sub_field('date_technical'),
                               'date' => get_sub_field('date'));
                  array_push($all_dates, $info);
                endwhile;
              endwhile;
            wp_reset_postdata();
            endif;

            usort($all_dates, function($a, $b) {
              return $a['date_technical'] <=> $b['date_technical'];
            });
            $all_dates = array_slice($all_dates, 0, 3);

            foreach ($all_dates as $d) : ?>
            <div class="event-teaser-list-item no-hover">
              <a href="<?php echo $d['link']?>" title="Zur Seite von Lab: <?php echo $d['lab']; ?>">
              <div class="d-f ai-s">
                <picture class="events-list-image-2">
                <img src="<?php echo wp_get_attachment_image_src($d['img']['ID'], 'lab-event-teaser')[0] ?>" alt="" width="90">
                </picture>
                <div class="event-teaser-list-meta fg">
                  <div class="c-uppercase-title mb-1">Lab: <?php echo $d['lab']; ?></div>
                  <h3 class="events-list-title mb-0 mt-0"><?php echo $d['title']; ?></h3>
                  <p class="mt-1 fw-b">
                    <time datetime="<?php echo DateTime::createFromFormat('j/m/Y', $d['date_technical'])->format('Y-m-d'); ?>">
                      <?php echo $d['date']; ?>
                      <time>
                  </p>
                </div>
              </div>
              </a>
            </div>
            <?php endforeach; ?>
          </section>
        </div>
      </div>
    </div>
</section>

<section class="c-page-section c-blog-list is-grid">
  <h2 class="c-flag mini softblue points-bottom upper mb-3"><?php echo __('Aus dem Blog', 'lauch'); ?></h2>
  <?php
  $args2 = array('posts_per_page' => 2);
  $the_query2 = new WP_Query( $args2 ); ?>

  <?php if ( $the_query2->have_posts() ) : ?>
    <ul>
      <?php while ( $the_query2->have_posts() ) : $the_query2->the_post(); ?>
        <li>
          <?php get_template_part( 'template-parts/content', 'teaser'); ?>
        </li>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    </ul>
  <?php endif; ?>
  <div class="ta-c">
    <a href="<?php echo get_permalink( get_option( 'page_for_posts' )) ?>" class="button event-button"><?php echo __('Alle Blogposts', 'lauch') ?></a></div>
</section>

<section class="c-page-section">
  <?php
  $loc_term = get_field('term_location');
  $year_term = get_field('term_year');
  $topic_term = get_field('term_topics');
  $tech_term = get_field('term_tech');

  $out = "[vuevideo type='project-presentation' color='white' ";
  if ($loc_term) {
    $out .= "location='". $loc_term->slug ."' ";
  }
  if ($year_term) {
    $out .= "year='". $year_term->slug ."' ";
  }
  if ($tech_term) {
    $out .= "tech='". $tech_term->slug ."' ";
  }
  if ($topic_term) {
    $out .= "topics='". $topic_term->slug ."' ";
  }
  $out .= "]";

  echo do_shortcode($out); ?>
</section>


<!--
<section class="c-page-section">
  <h2><?php echo __('So war es in', 'lauch'); ?></h2>
</section>
-->

<section class="">
  <?php get_template_part( 'template-parts/support-cta', get_post_type() ); ?>
</section>

<?php
get_footer();
