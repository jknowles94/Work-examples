<?php
$mainVideo = isset($slide['videos'][0]) ? $slide['videos'][0] : false;
$video1 = isset($slide['videos'][1]) ? $slide['videos'][1] : false;
$video2 = isset($slide['videos'][2]) ? $slide['videos'][2] : false;
$newsArticle1 = isset($slide['news_article_1']) ? $slide['news_article_1'] : false;
$newsArticle2 = isset($slide['news_article_2']) ? $slide['news_article_2'] : false;

$characterCount = get_field('homepage_carousel_news_article_character_count');

// Vimeo Videos for Carousel
$vimeo = new \CPFCMembers\Vimeo();
$mainVideoInfo = $vimeo->fetchVideoInfoFromCache($mainVideo['video_id']);
$video1Info = $vimeo->fetchVideoInfoFromCache($video1['video_id']);
$video2Info = $vimeo->fetchVideoInfoFromCache($video2['video_id']); ?>

<div class="item">
    <div class="row">
        <div class="col-md-6">
        <?php
        if ($mainVideo) { ?>
            <div class="visible-xs visible-sm">
                <div class="videoWrapper">
                    <iframe src="//player.vimeo.com/video/<?php echo $mainVideo['video_id']; ?>" width="100%" height="258" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div>
            </div>

            <div class="hidden-xs hidden-sm">
                <a href="<?php echo cpfc_home_url(CPFC_MEMBERS_VIEW_CAROUSEL_VIDEO_URL); ?>?id=<?php echo $mainVideo['video_id']; ?>" data-video-id="<?php echo esc_attr($mainVideo['video_id']); ?>" data-heading="<?php echo esc_attr($mainVideoInfo['title']); ?>" data-description="<?php echo esc_attr($mainVideo['description']); ?>" class="view-carousel-video">
                    <span>
                        <img src="<?php echo $mainVideoInfo['localImage']; ?>" class="img-responsive"/>
                        <i class="icon-play-circled2"></i>
                    </span>
                </a>
            </div>
        <?php
        } ?>
        </div>
        <div class="col-md-6">
            <div class="slider-article">
                <div class="row">
                    <div class="col-md-6 col-xs-6">
                    <?php
                    if ($video1) { ?>
                        <div class="visible-xs visible-sm">
                            <div class="videoWrapper">
                                <iframe src="//player.vimeo.com/video/<?php echo $video1['video_id']; ?>" width="100%" height="121" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </div>
                        </div>

                        <div class="hidden-xs hidden-sm">
                            <a href="<?php echo cpfc_home_url(CPFC_MEMBERS_VIEW_CAROUSEL_VIDEO_URL); ?>?id=<?php echo $video1['video_id']; ?>" data-video-id="<?php echo esc_attr($video1['video_id']); ?>" data-heading="<?php echo esc_attr($video1Info['title']); ?>" data-description="<?php echo esc_attr($video1['description']); ?>" class="view-carousel-video">
                                <span>
                                    <img src="<?php echo $video1Info['localImage']; ?>" class="img-responsive"/>
                                    <i class="icon-play-circled2"></i>
                                </span>
                            </a>
                        </div>
                    <?php
                    } ?>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <div class="item hidden-xs hidden-sm">
                            <?php
                            if ($newsArticle1) { ?>
                            <h4><a href="<?php echo get_the_permalink($newsArticle1->ID); ?>"><?php echo $newsArticle1->post_title; ?></a></h4>
                            <p><?php echo cpfc_truncate_copy($newsArticle1->post_content, $characterCount); ?></p>
                            <?php
                            } ?>
                        </div>
                        <div class="visible-xs visible-sm">
                            <div class="videoWrapper">
                                <iframe src="//player.vimeo.com/video/<?php echo $video2['video_id']; ?>" width="100%" height="121" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider-article">
                <div class="row">

                    <div class="col-md-6 col-sm-12">
                        <?php
                        if ($newsArticle2) { ?>
                        <div class="item">
                            <h4><a href="<?php echo get_the_permalink($newsArticle2->ID); ?>"><?php echo $newsArticle2->post_title; ?></a></h4>
                            <p><?php echo cpfc_truncate_copy($newsArticle2->post_content, $characterCount); ?></p>
                        </div>
                        <?php
                        } ?>
                    </div>
                    <div class="col-md-6 col-sm-12">
                    <?php
                    if ($video2) { ?>

                        <div class="hidden-sm hidden-xs">
                            <span>
                                <a href="<?php echo cpfc_home_url(CPFC_MEMBERS_VIEW_CAROUSEL_VIDEO_URL); ?>?id=<?php echo $video2['video_id']; ?>" data-video-id="<?php echo esc_attr($video2['video_id']); ?>" data-heading="<?php echo esc_attr($video2Info['title']); ?>" data-description="<?php echo esc_attr($video2['description']); ?>" class="view-carousel-video">
                                    <img src="<?php echo $video2Info['localImage']; ?>" class="img-responsive"/>
                                    <i class="icon-play-circled2"></i>
                                </a>
                            </span>
                        </div>
                        <?php
                        if ($newsArticle1) { ?>
                        <div class="item visible-xs visible-sm">
                            <h4><a href="<?php echo get_the_permalink($newsArticle1->ID); ?>"><?php echo $newsArticle1->post_title; ?></a></h4>
                            <p><?php echo cpfc_truncate_copy($newsArticle1->post_content, $characterCount); ?></p>
                        </div>
                        <?php
                        } ?>
                    <?php
                    } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>