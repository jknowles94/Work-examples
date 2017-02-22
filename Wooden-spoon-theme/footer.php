    		<!-- footer -->
    		<footer class="fullwidth-lg footer">
                <div class="container">
                    <h2>Contact</h2>
                    <div class="row">

                        <div class="col-md-3 col-sm-6">
                            <address>
                                Sentinel House<br>
                                Ancells Business Park<br>
                                Harvest Crescent<br>
                                Fleet, Hampshire<br>
                                GU51 2UZ
                            </address>
                            <a href="https://www.google.co.uk/maps/place/Wooden+Spoon+Society/@51.2840339,-0.8405775,17z/data=!3m1!4b1!4m2!3m1!1s0x48742bde3a307207:0x425105a2ee0e7928" target='_blank'>Find us on Google Maps</a>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <address>
                                +44 (0) 1252 773 720<br>
                                <a href="mailto:charity@woodenspoon.org.uk" target="_blank">charity@woodenspoon.org.uk</a>
                            </address>
                            <nav class="nav nav-social">
                                <a href="https://www.facebook.com/WoodenSpoonCharity" target="_blank" class="facebook"><i class="icon-facebook"></i></a>
                                <a href="https://twitter.com/CharitySpoon" class="twitter"><i class="icon-twitter"></i></a>
                                <a href="https://www.youtube.com/user/WoodenSpoonTV" target="_blank" class="youtube"><i class="icon-youtube"></i></a>
                                <a href="https://www.instagram.com/charityspoon/" target="_blank" class="instagram"><i class="icon-instagram"></i></a>
                            </nav>
                        </div>

                        <div class="col-md-2 col-sm-6">
                            <p><a href="/privacy-policy/">Privacy Policy</a></p>
                            <p><a href="/faqs/">FAQs</a></p>
                            <p><a href="/cookie-policy/">Cookie Policy</a></p>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fundraising.png" class="img-responsive fundraising" style="max-width: 96px; margin-top:20px;"/>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/sponsors_wo.png" class="img-responsive"/>
                            
                        </div>

                    </div>
                </div>
            </footer>
            <div style="background: #000; padding:20px 0;">
                <div class="container">
                    <p style="color:#fff;font-size:12px;margin-bottom:5px;">Company No 1847860, Registered in England Registered address: Sentinel House, Ancells Business Park, Harvest Crescent, Fleet, Hampshire, GU51 2UZ</p>
                    <p style="color:#fff;font-size:12px;margin-bottom:5px;">Charity Registration No 326691 (England & Wales) and SC039247 (Scotland)</p>
                </div>
            </div>

        </div>
		<!-- /footer -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../bower_components/jquery/dist/jquery.min.js"><\/script>')</script>

        <!-- build:js assets/scripts/plugins.js -->   
        <!--<script src="<?php echo get_template_directory_uri(); ?>/bower_components/SimpleStateManager/dist/ssm.min.js"></script>

        <script src="<?php echo get_template_directory_uri(); ?>/bower_components/jquery-cycle2/build/jquery.cycle2.min.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/bower_components/jquery-cycle2/build/plugin/jquery.cycle2.swipe.min.js"></script>

        <script src="<?php echo get_template_directory_uri(); ?>/bower_components/bootstrapValidator/dist/js/bootstrapValidator.min.js"></script>

        <script src="<?php echo get_template_directory_uri(); ?>/bower_components/ThumbnailGrid/js/grid.js?ver=1.2"></script>

        <script src="<?php echo get_template_directory_uri(); ?>/bower_components/Slidebars/dist/slidebars.min.js"></script>

        <script src="<?php echo get_template_directory_uri(); ?>/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>

        <script src="<?php echo get_template_directory_uri(); ?>/bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/tab.js"></script>

        <script src="<?php echo get_template_directory_uri(); ?>/bower_components/respond/dest/respond.min.js"></script> 

        <script src="<?php echo get_template_directory_uri(); ?>/bower_components/jquery-jvectormap-2.0.3/jquery-jvectormap-2.0.3.min.js"></script> -->
        
        <!-- endbuild -->
        
        <!--<script src="<?php echo get_template_directory_uri(); ?>/assets/scripts/plugins.js"></script>-->
        <script src="<?php echo get_template_directory_uri(); ?>/assets/scripts/plugins.min.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/assets/scripts/region_map.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/assets/scripts/main.min.js"></script>
        
        <!--<script src="<?php echo get_template_directory_uri(); ?>/assets/scripts/main.js?ver=1.2.1"></script>-->
        <?php if(is_page_template('page-map.php')): ?>
            <script src="<?php echo get_template_directory_uri(); ?>/assets/scripts/map.js"></script>
        <?php endif; ?>

        <?php if(is_page_template('page-local-to-you.php')): ?>
            <script src="<?php echo get_template_directory_uri(); ?>/assets/scripts/region_map_balls.js?ver=1.2"></script>
            <script src="<?php echo get_template_directory_uri(); ?>/assets/scripts/map_events.js?ver=1.4"></script>
        <?php endif; ?>

        <script>
            $(function() {
                Grid.init();
            });
        </script>

        
		<!-- /wrapper -->

		<?php wp_footer(); ?>

	</body>
</html>
