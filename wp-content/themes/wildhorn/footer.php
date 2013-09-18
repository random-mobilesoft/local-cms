<?php 
global $is_ajax;

if($is_ajax):

    wp_footer();

else: ?>

    </div> <!-- /content -->
    </div> <!-- /container -->
<?php get_sidebar(); ?>
    </div> <!-- /wrapper -->
    <section id="loader" data-region="loader"></section>
    <script type="text/javascript" id="head-js-vars">
        mthemes_pagesReload = <?php echo (get_field('no_pages_reload', 'options'))? 'false' : 'true';  ?>;
        mthemes_baseUrl     = '<?php echo home_url("/") ?>';
    </script>
    <?php  wp_footer(); ?>
    </body>
    </html>
    <?php

endif;