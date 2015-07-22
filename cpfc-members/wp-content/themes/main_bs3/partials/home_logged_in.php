<?php
get_header();

// Latest YouTube Videos
$latestYouTubeVideosPlaylistId = get_field('youtube_feed_playlist_id', 'option');
$
$latestYouTubeVideos = false;
if ($latestYouTubeVideosPlaylistId) {
    $youTubeFeed = new \CPFCMembers\YouTube($latestYouTubeVideosPlaylistId);
    $latestYouTubeVideos = $youTubeFeed->fetchFromCache(4);
}

// Most Popular YouTube Videos
$popularYouTubeVideosPlaylistId = get_field('youtube_feed_popular_playlist_id', 'option');
$
$popularYouTubeVideos = false;
if ($popularYouTubeVideosPlaylistId) {
    $youTubeFeed = new \CPFCMembers\YouTube($popularYouTubeVideosPlaylistId);
    $popularYouTubeVideos = $youTubeFeed->fetchFromCache(2);
}

// Academy YouTube Videos
$academyYouTubeVideosPlaylistId = get_field('youtube_feed_academy_playlist_id', 'option');
$
$academyYouTubeVideos = false;
if ($academyYouTubeVideosPlaylistId) {
    $youTubeFeed = new \CPFCMembers\YouTube($academyYouTubeVideosPlaylistId);
    $academyYouTubeVideos = $youTubeFeed->fetchFromCache(2);
}

// RSS Feed
$feedUrl = get_field('rss_feed_url', 'option');
$itemCount = get_field('homepage_logged_in_latest_news_item_count');
$rssFeedItems = false;
if ($feedUrl) {
    $rss = new \CPFCMembers\Rss_Feed($feedUrl);
    $rssFeedItems = $rss->fetchFromCache($itemCount);
}

// Vimeo Videos for Carousel
$vimeo = new \CPFCMembers\Vimeo();

$Notification = \CPFCMembers\Notification::getInstance(); ?>

<div id="sb-site" class="bg-gradient">

    <?php
    get_partial('partials/header_logged_in'); ?>

    <div class="content logged-in">

        <?php
        $welcomeMessageCopy = get_field('homepage_logged_in_welcome_message_copy');
        $showWelcomeMessage = $welcomeMessageCopy && \CPFCMembers\Auth::getUser()->getLoginCount() > 1 && $welcomeMessageCopy;
        // Show section if a notification should be displated of if there is welcome copy
        if ($Notification->notifyUser() || $showWelcomeMessage) { ?>

        <section class="page-alert">

            <?php
            $Notification->displayNotification(); ?>

            <?php
            // Show at all times after the Member's first log in (only if welcome copy exists)
            if ($showWelcomeMessage) { ?>
            <div class="welcome">
                <div class="container">
                    <p><?php echo preg_replace(array('~#name~i'),array('<strong>' . \CPFCMembers\Auth::getUser()->getFirstName() . '</strong>'), $welcomeMessageCopy); ?></p>
                </div>
            </div>
            <?php
            } ?>

        </section>

        <?php
        } ?>

        <!-- Hero Video section -->
        <section class="fullwidth header-panel home-hero">

            <div class="container">

                <div class="header-left-contain">
                    <h1 class="heading header-right"><span><?php the_field('homepage_carousel_heading'); ?></span></h1>
                    <hr />
                </div>

                <a href="#" class="hero-carousel-prev hidden-xs"><i class="icon-left-open"></i></a>
                <a href="#" class="hero-carousel-next hidden-xs"><i class="icon-right-open"></i></a>
                <div class="cycle-slideshow"
                        data-cycle-fx="scrollHorz"
                        data-cycle-slides="> .item"
                        data-cycle-timeout="0"
                        data-cycle-swipe="true"
                        data-cycle-log="<?php echo show_cycle_2_logs(); ?>"
                        data-cycle-prev=".hero-carousel-prev"
                        data-cycle-next=".hero-carousel-next"
                        >

                        <?php
                        $slides = get_field('homepage_carousel_slides');

                        $slideNumber = 0;
                        foreach ($slides as $slide) {
                            if ($slide['slide_enabled']) {
                                get_partial('partials/homepage_carousel/slide_' . (($slideNumber % 2) + 1), array('slide'=>$slide));
                            }
                            $slideNumber++;
                        } ?>

                        <div class="cycle-pager"></div>

                </div>
            </div>
        </section>

        <section class="fullwidth-bg" style="background-image:url(<?php echo get_template_directory_uri(); ?>/assets/images/bg-panel.png)">
            <div class="container">
               <div class="row">
                    <?php
                    $videosCTAs = get_field('homepage_logged_in_exclusive_videos_cta');
                    foreach ($videosCTAs as $videosCTA) { ?>
                    <div class="col-sm-6 col-xs-12">

                        <div class="bg-white promo-block promo">
                                <a href="<?php echo $videosCTA['link_url']; ?>" target="_blank"><img src="<?php echo isset($videosCTA['image']['url']) ? $videosCTA['image']['url'] : ''; ?>" alt="<?php echo isset($videosCTA['image']['alt']) ? $videosCTA['image']['alt'] : ''; ?>"></a>
                            <div class="item">
                                <h3 class="h4"><a href="<?php echo $videosCTA['link_url']; ?>" target="_blank"><?php echo $videosCTA['heading']; ?></a></h3>
                                <?php echo $videosCTA['copy']; ?>
                                <p class="more"><a href="<?php echo $videosCTA['link_url']; ?>" target="_blank"><?php echo $videosCTA['link_text']; ?> <i class="icon-right-1"></i></a></p>

                            </div>

                        </div>
                    </div>
                    <?php
                    } ?>
                    <?php
                     ?>
                </div>
            </div>
        </section>

        <section class="fullwidth five-col load-more-panel">
            <div class="container">

                <div class="header-left-contain">
                    <h1 class="heading header-right"><span><?php the_field('homepage_logged_in_latest_videos_heading'); ?></span></h1>
                    <hr />
                </div>

                <div class="youtube-widget">
                    <script src="https://apis.google.com/js/platform.js"></script>
                    <div class="g-ytsubscribe" data-channel="OfficialCPFC" data-layout="default" data-count="default"></div>
                </div>

                <?php
                if ($latestYouTubeVideos) { ?>
                <div class="row">
                    <?php
                    foreach ($latestYouTubeVideos as $latestYouTubeVideo) { ?>
                    <div class="col-sm-3">
                        <div class="video item">
                            <a href="http://www.youtube.com/embed/<?php echo $latestYouTubeVideo['videoId']; ?>?rel=1&amp;wmode=transparent&amp;enablejsapi=1" class="youtube cboxElement">
                                <span>
                                    <img src="<?php echo $latestYouTubeVideo['localImage']; ?>" alt="<?php echo htmlentities($latestYouTubeVideo['title']); ?>" class="img-responsive" />
                                    <i class="icon-play"></i>
                                </span>
                                <h4 class="h5"><?php echo $latestYouTubeVideo['title']; ?></h4>
                                <span><?php echo time_since_friendly($latestYouTubeVideo['timestamp']); ?></span>
                            </a>
                        </div>
                    </div>
                    <?php
                    } ?>
                </div><!-- end outer row -->
                <?php
                } ?>

                <div class="row load-button">
                    <div class="col-sm-12">
                        <div class="load-more">
                            <a class="btn btn-white" data-source="<?php echo CPFC_MEMBERS_YOUTUBE_LOAD_MORE_URL; ?>" data-playlist="<?php echo $latestYouTubeVideosPlaylistId; ?>" data-page="1" data-count="4" data-item-class="col-sm-3" href="<?php echo CPFC_MEMBERS_YOUTUBE_LOAD_MORE_URL; ?>"><span>Load More</span> <i class="icon-down-open"></i></a>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <section class="fullwidth news-feed">
            <div class="container">
                    <div class="row latest-news">
                        <div class="col-sm-7">
                            <div class="header-left-contain">
                                <h1 class="heading header-right"><span><?php the_field('homepage_logged_in_latest_news_heading'); ?></span></h1>
                                <hr />
                            </div>
                            <p class="more news hidden-xs"><a href="<?php the_field('homepage_logged_in_latest_news_cta_url'); ?>" target="_blank"><?php the_field('homepage_logged_in_latest_news_cta_text'); ?> <i class="icon-right-1"></i></a></p>

                            <?php
                            $characterCount = (int) get_field('homepage_logged_in_latest_news_content_preview_character_count');
                            if ($rssFeedItems) {
                                foreach ($rssFeedItems as $rssFeedItem) {
                                    if ($rssFeedItem['localImage']) {
                                        $rssFeedImage = $rssFeedItem['localImage'];
                                    } else {
                                        $rssFeedPlaceholderImage = get_field('rss_feed_cache_placeholder_image', 'option');
                                        $rssFeedImage = $rssFeedPlaceholderImage['url'];
                                    } ?>
                            <div class="news item">
                                <div class="col-sm-4">
                                    <a href="<?php echo $rssFeedItem['url']; ?>" target="_blank"><img src="<?php echo $rssFeedImage; ?>" alt="<?php echo htmlentities($rssFeedItem['title']); ?>" class="img-responsive" /></a>
                                </div>
                                <div class="col-sm-8">
                                    <h4 class="h5"><a href="<?php echo $rssFeedItem['url']; ?>" target="_blank"><?php echo $rssFeedItem['title']; ?></a></h4>
                                    <span class="published"><?php echo time_since_friendly($rssFeedItem['timestamp']); ?></span>
                                    <p><?php echo substr($rssFeedItem['content'], 0, $characterCount); ?>...</p>
                                </div>
                            </div>
                                <?php
                                }
                            } ?>
                            <p class="more visible-xs"><a href="<?php the_field('homepage_logged_in_latest_news_cta_url'); ?>" target="_blank"><?php the_field('homepage_logged_in_latest_news_cta_text'); ?> <i class="icon-right-1"></i></a></p>
                        </div>

                        <div class="col-sm-5 load-more-panel">

                            <div class="header-left-contain">
                                <h1 class="heading header-right"><span><?php the_field('homepage_logged_in_popular_videos_heading'); ?></span></h1>
                                <hr />
                            </div>
                            <div class="row">
                                <?php
                                if ($popularYouTubeVideos) {
                                    foreach ($popularYouTubeVideos as $popularYouTubeVideo) { ?>
                                <div class="col-sm-6">
                                    <div class="video item">
                                        <a href="http://www.youtube.com/embed/<?php echo $popularYouTubeVideo['videoId']; ?>?rel=1&amp;wmode=transparent&amp;enablejsapi=1" class="youtube cboxElement">
                                            <span>
                                                <img src="<?php echo $popularYouTubeVideo['localImage']; ?>" alt="<?php echo htmlentities($popularYouTubeVideo['title']); ?>" class="img-responsive" />
                                                <i class="icon-play"></i>
                                            </span>
                                            <h4 class="h5"><?php echo $popularYouTubeVideo['title']; ?></h4>
                                            <span><?php echo time_since_friendly($popularYouTubeVideo['timestamp']); ?></span>
                                        </a>
                                    </div>
                                </div>
                                <?php
                                    }
                                } ?>
                            </div>
                            <div class="row load-button">
                                <div class="col-sm-12">
                                    <div class="load-more">
                                        <a class="btn btn-white half-width" data-source="<?php echo CPFC_MEMBERS_YOUTUBE_LOAD_MORE_URL; ?>" data-playlist="<?php echo $popularYouTubeVideosPlaylistId; ?>" data-page="1" data-count="2" data-item-class="col-sm-6" href="<?php echo CPFC_MEMBERS_YOUTUBE_LOAD_MORE_URL; ?>">Load More <i class="icon-down-open"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7 load-more-panel">

                            <div class="header-left-contain">
                                <h1 class="heading header-right"><span><?php the_field('homepage_logged_in_academy_videos_heading'); ?></span></h1>
                                <hr />
                            </div>
                            <div class="row">
                                <?php
                                if ($academyYouTubeVideos) {
                                    foreach ($academyYouTubeVideos as $academyYouTubeVideo) { ?>
                                <div class="col-sm-6">
                                    <div class="video item">
                                        <a href="http://www.youtube.com/embed/<?php echo $academyYouTubeVideo['videoId']; ?>?rel=1&amp;wmode=transparent&amp;enablejsapi=1" class="youtube cboxElement">
                                            <span>
                                                <img src="<?php echo $academyYouTubeVideo['localImage']; ?>" alt="<?php echo htmlentities($academyYouTubeVideo['title']); ?>" class="img-responsive" />
                                                <i class="icon-play"></i>
                                            </span>
                                            <h4 class="h5"><?php echo $academyYouTubeVideo['title']; ?></h4>
                                            <span><?php echo time_since_friendly($academyYouTubeVideo['timestamp']); ?></span>
                                        </a>
                                    </div>
                                </div>
                                <?php
                                    }
                                } ?>
                            </div>
                            <div class="row load-button">
                                <div class="col-sm-12">
                                    <div class="load-more academy-vid">
                                        <a class="btn btn-white half-width" data-source="<?php echo CPFC_MEMBERS_YOUTUBE_LOAD_MORE_URL; ?>" data-playlist="<?php echo $academyYouTubeVideosPlaylistId; ?>" data-page="1" data-count="2" data-item-class="col-sm-6" href="<?php echo CPFC_MEMBERS_YOUTUBE_LOAD_MORE_URL; ?>">Load More <i class="icon-down-open"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-5">

                            <div class="header-middle-contain bg-grey">
                                <h2 class="heading header-left header-right"><span><?php the_field('homepage_logged_in_social_networks_heading'); ?></span></h2>
                                <hr />
                            </div>
                            <div class="bg-grey social-connect">

                                <div class="social-bar">
                                    <div class="avatar">
                                        <?php
                                        $avatarImage = get_field('homepage_logged_in_social_networks_image'); ?>
                                        <img src="<?php echo isset($avatarImage['url']) ? $avatarImage['url'] : ''; ?>" alt="<?php echo isset($avatarImage['alt']) ? $avatarImage['alt'] : ''; ?>">
                                        <?php the_field('homepage_logged_in_social_networks_copy'); ?>
                                    </div>

                                    <?php
                                    $socialNetworkLinks = get_field('homepage_logged_in_social_networks_links');
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
            </div>
        </section>

        <section class="fullwidth">
            <div class="container">
               <div class="row">
                    <?php
                    $footerCTAs = get_field('homepage_logged_in_footer_cta');

                    foreach ($footerCTAs as $footerCTA) { ?>
                    <div class="col-sm-6 col-xs-12">

                        <div class="bg-white promo-block buy">
                            <a href="<?php echo $footerCTA['link_url']; ?>" target="_blank"><img src="<?php echo isset($footerCTA['image']['url']) ? $footerCTA['image']['url'] : ''; ?>" class="img-responsive" alt="<?php echo isset($footerCTA['image']['alt']) ? $footerCTA['image']['alt'] : ''; ?>"></a>

                            <div class="item">
                                <h3 class="h4"><a href="<?php echo $footerCTA['link_url']; ?>" target="_blank"><?php echo $footerCTA['heading']; ?></a></h3>
                                <p><?php echo $footerCTA['copy']; ?></p>
                                <p class="more"><a href="<?php echo $footerCTA['link_url']; ?>" target="_blank"><?php echo $footerCTA['link_text']; ?> <i class="icon-right-1"></i></a></p>
                            </div>

                        </div>
                    </div>
                    <?php
                    } ?>
                </div>
            </div>
        </section>

    </div>

    <?php
    get_partial('partials/partners'); ?>

<?php get_footer(); ?>