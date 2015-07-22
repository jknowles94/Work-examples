<?php
//News Item Template

// Can't access News Items if not logged-in as Member or Administrator
if (!\CPFCMembers\Auth::isLoggedIn() && !\CPFCMembers\Auth::isAdmin()) {
    wp_redirect(cpfc_home_url(CPFC_MEMBERS_HOME_URL, CPFC_MEMBERS_HTTP_MODE), 301);
    exit();
}

get_header();

$homepageId = 8;

$newsItems = get_posts(array(
    'post_type'      => 'news',
    'posts_per_page' => 2,
    'post__not_in'   => array(get_the_ID()),
));

$feedUrl = get_field('rss_feed_url', 'option');
$rssFeedItems = false;
if ($feedUrl) {
    $rss = new \CPFCMembers\Rss_Feed($feedUrl);
    $rssFeedItems = $rss->fetchFromCache(2);
}

$characterCount = (int) get_field('news_item_related_items_preview_character_count', 'option'); ?>

<div id="sb-site" class="bg-gradient">

    <?php
    get_partial('partials/cookie_policy');

    if (\CPFCMembers\Auth::isLoggedIn()) {
        get_partial('partials/header_logged_in');
    } else {
        get_partial('partials/header_logged_out', array('showLogin'=>false));
    } ?>

    <div class="content logged-in">

        <section class="fullwidth news-article">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="header-left-contain">
                            <h1 class="heading header-right"><span><?php the_field('news_item_detail_page_heading_copy', 'option'); ?></span></h1>
                            <hr />
                        </div>
                        <div class="item">
                            <h2><?php the_title(); ?></h2>
                            <span class="published">Published <?php echo time_since_friendly(get_the_time('U')); ?></span>

                        <div class="visible-xs">
                            <?php
                            $articleImage = get_field('news_item_image');
                            $imageUrl = isset($articleImage['sizes']['list-detail']) ? $articleImage['sizes']['list-detail'] : $articleImage['url']; ?>
                            <img src="<?php echo $imageUrl; ?>" alt="<?php echo $articleImage['alt']; ?>" class="img-responsive news-article-img"/>
                        </div>

                            <?php the_content(); ?>

                            <div class="add-this"><h3>Share with friends</h3> <?php get_partial('partials/addthis_widget'); ?></div>
                        </div>


                    </div>
                    <div class="col-sm-4">
                        <div class="hidden-xs">
                            <img src="<?php echo $imageUrl; ?>" alt="<?php echo $articleImage['alt']; ?>" class="img-responsive news-article-img"/>
                        </div>
                        <div class="header-middle-contain bg-grey">
                            <h2 class="heading header-left header-right"><span><?php the_field('homepage_logged_in_social_networks_heading', $homepageId); ?></span></h2>
                            <hr />
                        </div>
                        <div class="bg-grey social-connect">
                            <div class="social-bar">
                                <div class="avatar">
                                    <?php
                                    $avatarImage = get_field('homepage_logged_in_social_networks_image', $homepageId); ?>
                                    <img src="<?php echo isset($avatarImage['url']) ? $avatarImage['url'] : ''; ?>" alt="<?php echo isset($avatarImage['alt']) ? $avatarImage['alt'] : ''; ?>">
                                    <?php the_field('homepage_logged_in_social_networks_copy', $homepageId); ?>
                                </div>

                                <?php
                                $socialNetworkLinks = get_field('homepage_logged_in_social_networks_links', $homepageId);
                                if ($socialNetworkLinks) {
                                    foreach ($socialNetworkLinks as $socialNetworkLink) {
                                        switch ($socialNetworkLink['type']) {
                                            case 'facebook': ?>
                                <a href="<?php echo $socialNetworkLink['url']; ?>" class="connect fb" target="_blank">
                                    <i class="icon-facebook"></i>
                                    <span><?php echo $socialNetworkLink['text']; ?></span>
                                </a>
                                            <?php
                                                break;
                                            case 'twitter': ?>
                                <a href="<?php echo $socialNetworkLink['url']; ?>" class="connect tw" target="_blank">
                                    <i class="icon-twitter"></i>
                                    <span><?php echo $socialNetworkLink['text']; ?></span>
                                </a>
                                            <?php
                                                break;
                                            case 'youtube': ?>
                                <a href="<?php echo $socialNetworkLink['url']; ?>" class="connect yt" target="_blank">
                                    <i class="icon-youtube"></i>
                                    <span><?php echo $socialNetworkLink['text']; ?></span>
                                </a>
                                <?php
                                                break;
                                        }
                                    }
                                } ?>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="fullwidth related-news">
            <div class="container">
                <div class="header-left-contain">
                    <h1 class="heading header-right"><span>Related News</span></h1>
                    <hr />
                </div>
                <div class="row">
                <?php
                if ($newsItems) {
                    foreach ($newsItems as $newsItem) {
                    $articleImage = get_field('news_item_image', $newsItem->ID);
                    $articleImageUrl = isset($articleImage['sizes']['list-detail']) ? $articleImage['sizes']['list-detail'] : $articleImage['url']; ?>
                    <div class="col-sm-6">
                        <div class="item related-item">
                            <div class="row">
                                <div class="col-sm-5">
                                    <a href="<?php echo get_the_permalink($newsItem->ID); ?>"><img src="<?php echo $articleImageUrl; ?>" alt="<?php echo $articleImage['alt']; ?>" class="img-responsive"/></a>
                                </div>
                                <div class="col-sm-7">
                                    <h4><a href="<?php echo get_the_permalink($newsItem->ID); ?>"><?php echo get_the_title($newsItem->ID); ?></a></h4>
                                    <span class="published">Published <?php echo time_since_friendly(get_the_date('U', $newsItem->ID)); ?></span>
                                    <p><?php echo cpfc_truncate_copy($newsItem->post_content, $characterCount); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                } ?>
                </div>
                <div class="row">
                <?php
                if ($rssFeedItems) {
                    foreach ($rssFeedItems as $rssFeedItem) {
                        if ($rssFeedItem['localImage']) {
                            $rssFeedImage = $rssFeedItem['localImage'];
                        } else {
                            $rssFeedPlaceholderImage = get_field('rss_feed_cache_placeholder_image', 'option');
                            $rssFeedImage = $rssFeedPlaceholderImage['url'];
                        } ?>
                    <div class="col-sm-6">
                        <div class="item related-item">
                            <div class="row">
                                <div class="col-sm-5">
                                    <a href="<?php echo $rssFeedItem['url']; ?>" target="_blank"><img src="<?php echo $rssFeedImage; ?>" alt="<?php echo htmlentities($rssFeedItem['title']); ?>" class="img-responsive"/></a>
                                </div>
                                <div class="col-sm-7">
                                    <h4><a href="<?php echo $rssFeedItem['url']; ?>" target="_blank"><?php echo $rssFeedItem['title']; ?></a></h4>
                                    <span class="published">Published <?php echo time_since_friendly(get_the_date('U', $rssFeedItem['timestamp'])); ?></span>
                                    <p><?php echo cpfc_truncate_copy($rssFeedItem['content'], $characterCount); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                } ?>
                </div>
            </div>
        </section>
    </div>

        <?php
        get_partial('partials/partners'); ?>
</div>

<?php get_footer(); ?>