<?php

## (get/post) home
dispatch('/',      'display_home');
dispatch_post('/', 'display_home');

## (get) connection page
dispatch('/connexion',      'display_connection');
## (post) connection
dispatch_post('/connexion', 'post_connection');

## forgotten password
dispatch('/oubli',      'display_forgotten_password');
dispatch_post('/oubli', 'post_forgotten_password');

## users' profiles
dispatch('/p/*',           'display_profile_page');
dispatch('/p/*/edit',      'display_edit_profile_page');
dispatch_post('/p/*/edit', 'post_edit_profile_page');

## my profile
if (strpos(LIM_REQUEST_URI, '/profile') === 0) {

    dispatch('/profile',      'display_my_profile_page');
    dispatch_post('/profile', 'display_my_profile_page');
    ## edit my profile
    dispatch('/profile/edit',      'display_edit_my_profile_page');
    dispatch_post('/profile/edit', 'post_edit_my_profile_page');
    dispatch('/profile/init',      'display_init_my_profile_page');
    dispatch_post('/profile/init', 'post_init_my_profile_page');

}

## search
dispatch('/recherche', 'display_search_results');

if (strpos(LIM_REQUEST_URI, '/cursus') === 0) {

    ## cursus
    dispatch('/cursus/:name',      'display_cursus');
    dispatch_post('/cursus/:name', 'display_cursus');
    dispatch('/cursus/:name/edit', 'display_moderation_edit_cursus');

    ## educational paths
    dispatch('/cursus/:cursus/parcours/:path', 'display_educational_path');

    ## cursus feeds
    dispatch('/cursus/:name/flux.rss', 'display_cursus_rss_feed');
    dispatch('/cursus/:name/flux.atom', 'display_cursus_atom_feed');

    ## courses feeds
    dispatch('/cursus/:cursus/:course/flux.rss', 'display_course_rss_feed');
    dispatch('/cursus/:cursus/:course/flux.atom', 'display_course_atom_feed');

    ## course
    dispatch('/cursus/:cursus/:course', 'display_course');
    ## contents (course)
    dispatch('/cursus/:cursus/:course/proposer',      'display_member_proposing_content_form');
    dispatch_post('/cursus/:cursus/:course/proposer', 'display_post_member_proposed_content');
    dispatch_post('/cursus/:cursus/:course/proposer/prévisualiser',
                        'display_post_member_proposed_content_preview');

    # old URLs
    dispatch('/cursus/:cursus/:course/:id',           'display_course_content');
    # new URLs (2013, January)
    dispatch('/cursus/:cursus/:course/:id/:title',     'display_course_content');
    dispatch('/cursus/:cursus/:course/:id/:year/:title', 'display_course_content');

}

## contents' files
# dispatch('/file/:id',       'serve_user_file');
dispatch('/file/:id/:name', 'serve_user_file_by_id_and_name');

## admin home
dispatch('/admin', 'display_admin_home');

## moderation 
dispatch('/admin/moderation',            'display_admin_moderation');
dispatch('/admin/reports',               'display_admin_content_report');
dispatch_post('/admin/reports',          'post_admin_content_report');
dispatch('/admin/content/proposed',      'display_admin_proposed_content');
dispatch_post('/admin/content/proposed', 'post_admin_proposed_content');

## members
dispatch('/admin/membres',          'display_admin_members');
dispatch('/admin/membres/add',      'display_admin_add_member');
dispatch_post('/admin/membres/add', 'post_admin_add_member');

## maintenance
dispatch('/admin/migrate', 'display_admin_migrate_db_page');

## news archives
dispatch('/actus/archives', 'display_news_archives');

## (almost-)static pages
dispatch('/contact',  'display_contact_page');
dispatch('/anciens',  'display_former_students_contact_page');
dispatch('/sitemap',  'display_sitemap_page');
dispatch('/legals',   'display_legals_page');
dispatch('/bug',      'display_bug_report');
dispatch_post('/bug', 'post_bug_report');
dispatch('/a-propos', 'display_apropos_page');
dispatch('/stats',    'display_stats_page');

## API
if (strpos(LIM_REQUEST_URI, '/api') === 0) {

    dispatch('/api/1/users/exists.json',     'json_check_username');
    dispatch('/api/1/search.json',           'json_global_search');
    dispatch('/api/1/contents/last.json',    'json_get_last_contents');

    dispatch('/api/1/cursus/intro.json',      'json_get_cursus_intro');
    dispatch('/api/1/course/intro.json',      'json_get_course_intro');
    dispatch_post('/api/1/cursus/intro.json', 'json_post_cursus_intro');
    dispatch_post('/api/1/course/intro.json', 'json_post_course_intro');

    dispatch('/api/1/news/get_one.json',     'json_get_news_by_id');
    dispatch_post('/api/1/news/update.json', 'json_post_update_news');
    dispatch_post('/api/1/news/delete.json', 'json_post_delete_news');
    dispatch_post('/api/1/news/create.json', 'json_post_create_news');
    dispatch_post('/api/1/news/is_old.json', 'json_post_news_mark_as_old');
    dispatch_post('/api/1/news/is_not_old.json', 'json_post_news_mark_as_not_old');

    dispatch_post('/api/1/files/rename.json', 'json_post_rename_file');
    dispatch_post('/api/1/files/delete.json', 'json_post_delete_file');

}
