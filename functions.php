<?php 
/*
Khai bao hang gia tri
    @ THEME_URI = lay duong dan thu muc theme
    @ CORE = lay duong dan thu muc /core
*/
define( 'THEME_URI', get_stylesheet_directory() );
define( 'CORE', THEME_URI . "/core" );

/*
@ Nhung file /core/init.php
*/
require_once( CORE . "/init.php" );

/*
@ Thiet lap chieu rong noi dung
*/ 
if ( !isset( $content_with ) ){
    $content_with = 620;
}

/*
@ Khai bao chuc nang cua theme
*/ 
if ( ! function_exists( 'phungdat_theme_setup' ) ) {
    function phungdat_theme_setup() {

        /* Thiet lap textdomain */ 
        $language_folder = THEME_URI . '/language';
        load_theme_textdomain( 'phungdat', $language_folder );

        /* Tu dong them link RSS len <header> */
        add_theme_support( 'automatic-feed-links' );

        /* Them post thumbnail */
        add_theme_support( 'post-thumbnails' );

        /* Post formats */
        add_theme_support( 'post-formats' , array(
            'image',
            'gallery',
            'video',
            'quote',
            'link'
        ) );

        /* Them title-tag */
        add_theme_support( 'title-tag' );

        /* Them custom background */
        $default_background = array(
            'default_background' => '#e8e8e8',
        );
        add_theme_support( 'custom-background', $default_background );

    }

    add_action( 'after_setup_theme', 'phungdat_theme_setup' );
}

/* Them menu */
function register_my_menus() {
  register_nav_menus(
    array('primary-menu' => __( 'Primary Menu', 'phungdat' ) )
  );
}
add_action( 'init', 'register_my_menus' );

/* --------------Template function--------------------------- */

if ( ! function_exists( 'phungdat_header' ) ){
    function phungdat_header() { ?>
        <div class="site-name">
            <?php 
                if ( is_home() ){
                    printf( 
                        '<h1> <a href="%1$s" title="%2$s"> %3$s </a></h1>',
                        get_bloginfo( 'url' ),
                        get_bloginfo( 'description' ),
                        get_bloginfo( 'sitename' )
                    );
                } else {
                    printf( 
                        '<p> <a href="%1$s" title="%2$s"> %3$s </a> </p>',
                        get_bloginfo( 'url' ),
                        get_bloginfo( 'description' ),
                        get_bloginfo( 'sitename' )
                    );
                }
            ?>
        </div>
        <div class="site-description">
            <?php bloginfo( 'description' ); ?>
        </div> <?php
    }
}

/*-----------------Thiet lap menu ------------------------------*/

if ( ! function_exists( 'phungdat_menu' ) ) {
    function phungdat_menu( $menu ) {
        $menu = array(
            'theme_location'  => $menu,
            'container'       => 'nav',
            'container_class' => $menu,
            'items_wrap'=> ' <ul id="%1$s" class="%2$s sf-menu"> %3$s </ul> '
        );
        wp_nav_menu( $menu );
    }
}

/*Ham tao phan trang don gian*/
if ( ! function_exists( 'phungdat_pagination' ) ) {
    function phungdat_pagination() {
        if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
            return '' ;
        } ?>
        <nav class="pagination" role="navigation">
            <?php if ( get_next_post_link() ) : ?>
                <div class="prev"> <?php next_posts_link( 'Older Posts', 'phungdat' ); ?> </div>
            <?php endif; ?>
            <?php if ( get_previous_posts_link() ) : ?>
                <div class="next"> <?php previous_posts_link( __( 'Newest Posts' ) , 'phungdat' ); ?> </div>
            <?php endif; ?>               
        </nav>
        <?php
    }
}

/*Ham hien thi thumbnail*/
if ( !function_exists( 'phungdat_thumbnail' ) ) {
    function phungdat_thumbnail( $size ) {
        if ( !is_single() && has_post_thumbnail() && post_password_required() || has_post_format( 'image' ) ) : ?>
        <figure class="post_thumbnail"> <?php the_post_thumbnail( $size ); ?> </figure>
        <?php endif; ?>
    <?php }
}

/*phungdat_entry_header = hien thi tieu de post*/
if ( ! function_exists( 'phungdat_entry_header' ) ) {
    function phungdat_entry_header() { ?>
        <?php if ( is_single() ) : ?>
            <h1 class="entry_header"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" > <?php the_title(); ?> </a></h1>
        <?php else: ?>
            <h2 class="entry_header"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" > <?php the_title(); ?> </a></h2>
        <?php endif; ?>
    <?php }
}

/*phungdat_entry_meta = lay du lieu post*/
if ( !function_exists( 'phungdat_entry_meta' ) ) {
    function phungdat_entry_meta() { ?>
        <?php if ( ! is_page() ) : ?>
            <div class="entry_meta">
                <?php
                    printf(  __( '<span class="author"> Posted by %1$s' , 'phungdat' ),
                        get_the_author() );

                    printf( __( '<span class="date_published"> at %1$s' , 'phungdat' ),
                        get_the_date() );

                    printf( __( '<span class="category"> in %1$s ' , 'phungdat'  ),
                        get_the_category_list() );

                    if ( comments_open() ):
                        echo '<span class="meta-reply">';
                            comments_popup_link(
                                __( 'Leave a comment', 'phungdat' ),
                                __( 'One comment', "phungdat" ),
                                __( '% comments', 'phungdat' ),
                                __( 'Read all comments', 'phungdat' )
                            );
                        echo '</span>';
                    endif;
                ?>
            </div>
        <?php endif; ?>
    <?php }
}

/* Thêm chữ Read More vào excerpt*/
function phungdat_readmore() {
    return '<a class="read-more" href="'. get_permalink( get_the_ID() ) . '">;' . __(' ...[Read More]', 'phungdat') . '</a>;';
}
add_filter( 'excerpt_more', 'phungdat_readmore' );
/**   @ Hàm hiển thị nội dung của post type
  @ Hàm này sẽ hiển thị đoạn rút gọn của post ngoài trang chủ (the_excerpt)
  @ Nhưng nó sẽ hiển thị toàn bộ nội dung của post ở trang single (the_content)
  @ phungdat_entry_content()**/
if ( ! function_exists( 'phungdat_entry_content' ) ) {
    function phungdat_entry_content() {
    if ( ! is_single() && ! is_page() ) {
        the_excerpt();
    } else {
        the_content();
        /** Code hiển thị phân trang trong post type*/
        $link_pages = array(
          'before'           => __( ' <p>Page: ' , 'phungdat' ),
          'after'            => '</p>;',
          'nextpagelink'     => __( 'Next page' , 'phungdat' ),
          'previouspagelink' => __( 'Previous page' , 'phungdat' )
        );
        wp_link_pages( $link_pages );
        }
      }
}
/**
@ Hàm hiển thị tag của post
@ phungdat_entry_tag()
**/
if ( ! function_exists( 'phungdat_entry_tag' ) ) {
  function phungdat_entry_tag() {
    if ( has_tag() ) :
      echo '<div class="entry-tag">';
      printf( __( 'Tagged in %1$s', 'phungdat'), get_the_tag_list( '', ' ,' ) );
      echo '</div>';
    endif;
  }
}

/**
@ Chèn CSS và Javascript vào theme
@ sử dụng hook wp_enqueue_scripts() để hiển thị nó ra ngoài front-end
**/
function phungdat_styles() {
    /*
     * Hàm get_stylesheet_uri() sẽ trả về giá trị dẫn đến file style.css của theme
     * Nếu sử dụng child theme, thì file style.css này vẫn load ra từ theme mẹ
     */
    wp_register_style(
        'main-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style( 'main-style' );

    /* Reset css */
    wp_register_style( 'reset-style', get_template_directory_uri() . '/reset.css', 'all' );
    wp_enqueue_style( 'reset-style' );

    /*Superfish Menu*/
    wp_register_style( 'superfish-style' , get_template_directory_uri() . '/superfish.css', 'all' );
    wp_enqueue_style( 'superfish-style' );
    wp_register_script( 'superfish-script' , get_template_directory_uri() . '/superfish.js' , array('jquery') );
    wp_enqueue_script( 'superfish-script' );

    /* Custom script */
    wp_register_script( 'custom-script' , get_template_directory_uri() . '/custom.js' , array('jquery') );
    wp_enqueue_script( 'custom-script' );
}
add_action( 'wp_enqueue_scripts', 'phungdat_styles' );


add_action( 'widgets_init', 'theme_slug_widgets_init' );
function theme_slug_widgets_init() {
    register_sidebar(
        array(
            'name'          => __( 'Main Sidebar', 'phungdat' ),
            'id'            => 'main-sidebar',
            'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'phungdat' ),
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget'  => '</li>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        )
    );
}
