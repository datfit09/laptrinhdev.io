<article id="post- <?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-thumbnail">
        <?php phungdat_thumbnail( 'thumbnail' ); ?>
    </div>

    <div class="entry-header">
        <?php phungdat_entry_header(); ?>
        <?php phungdat_entry_meta(); ?>
    </div>
    <div class="entry-content">
        <?php phungdat_entry_content(); ?>
        <?php (is_single() ? phungdat_entry_tag() : '' ); ?>
    </div>
    
</article>