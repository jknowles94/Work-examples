<?php
query_posts(array(
  'post_type' => 'club',
  'orderby' => 'title',
  'order'=> ASC,
  'posts_per_page'=>-1
));
?>
<style type="text/css">
  .club.filterout a {
    color: #e6e6e6;
  }
</style>
<script type="text/javascript">
  values = {};
  scale = {};
  clubs = {};
  <?php while (have_posts()): ?>
  <?php the_post();?>
  clubs["<?php echo get_post_meta(get_the_ID(), "mapregion", true); ?>"] = {
    "url":        "<?php the_permalink()?>",
    "label":      "<?php the_title();?>",
    "slug":       "<?php echo $post->post_name; ?>",
    "region":     "<?php echo get_post_meta(get_the_ID(), "mapregion", true); ?>",
    "line1":      "<?php echo get_post_meta(get_the_ID(), "line1", true); ?>",
    "line2":      "<?php echo get_post_meta(get_the_ID(), "line2", true); ?>",
    "line3":      "<?php echo get_post_meta(get_the_ID(), "line3", true); ?>",
    "latitude":   <?php echo get_post_meta(get_the_ID(), "latitude", true); ?>,
    "longitude":  <?php echo get_post_meta(get_the_ID(), "longitude", true); ?>,
    "mapregion":  "<?php echo get_post_meta(get_the_ID(), "mapregion", true); ?>",
    "postcode":   "<?php echo get_post_meta(get_the_ID(), "postcode", true); ?>",
    "town":       "<?php echo get_post_meta(get_the_ID(), "town", true); ?>",
  }
  values["<?php echo get_post_meta(get_the_ID(), "mapregion", true); ?>"] = "<?php echo $post->post_name; ?>";
  scale["<?php echo $post->post_name; ?>"] = "<?php echo get_post_meta(get_the_ID(), "mapcolor", true); ?>";
  <?php endwhile;?>
</script>
<?php wp_reset_query(); ?>
<?php /* Template Name: Wheelchair Club Map */get_header();?>

  <section class="header-img">
  <?php $headerImg = get_field('header_image');?>
  <?php if (!empty($headerImg)): ?>
    <img src="<?php echo $headerImg; ?>" class="img-responsive"/>
  <?php else: ?>
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/header-img.png" class="img-responsive"/>
  <?php endif;?>
  </section>

  <section class="content-container">
    <div class="side-nav">
      <?php html5blank_nav();?>
    </div>
    <div class="main-content">
      <div class="content-title">
        <h1><?php the_title();?></h1>
        <a href="#" class="nav-mobile-toggle sb-toggle-left">
          <i class="icon-navicon"></i>
        </a>
      </div>
      <div class="content-body">
        <div class="body-container">
          <?php if (have_posts()): ?>
            <?php while (have_posts()): ?>
              <?php the_post();?>
              <?php the_content();?>
            <?php endwhile;?>
          <?php endif;?>
          <div class="row">
            <div class="col-md-4">
              <div id="sidebar">
                <div id="filters">
                  <input id="filter-input" placeholder="Search by postcode..." />
                  <button id="filter-button" class="btn">Search</button>
                </div>
                <div id="clubs">
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div id="map-container">
                <div id="woodenspoon-maps" style="height:800px;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php get_footer();?>

