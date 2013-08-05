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
if (strpos(LIM_REQUEST_URI, '/p/') === 0) {

    dispatch('/p/*',           'display_profile_page');

    # old URLs (#308)
    dispatch('/p/*/edit',      'display_edit_profile_page');
    dispatch_post('/p/*/edit', 'post_edit_profile_page');

    # new URLs (#308)
    dispatch('/p/*/éditer',      'display_edit_profile_page');
    dispatch_post('/p/*/éditer', 'post_edit_profile_page');

}

## my profile
if (strpos(LIM_REQUEST_URI, '/profil') === 0) {

    # old URLs (#308)
    if (strpos(LIM_REQUEST_URI, '/profile') === 0) {

        function redirect_profile()      { redirect_to('/profil'); }
        function redirect_profile_edit() { redirect_to('/profil/éditer'); }
        function redirect_profile_init() { redirect_to('/profil/créer'); }

        dispatch('/profile',      'redirect_profile');
        dispatch_post('/profile', 'redirect_profile');
        ## edit my profile
        dispatch('/profile/edit',      'redirect_profile_edit');
        dispatch_post('/profile/edit', 'redirect_profile_edit');
        dispatch('/profile/init',      'redirect_profile_init');
        dispatch_post('/profile/init', 'redirect_profile_init');

    }
    # new URLs (#308)
    else {

        dispatch('/profil',      'display_my_profile_page');
        dispatch_post('/profil', 'display_my_profile_page');
        ## edit my profile
        dispatch('/profil/éditer',      'display_edit_my_profile_page');
        dispatch_post('/profil/éditer', 'post_edit_my_profile_page');
        dispatch('/profil/créer',       'display_init_my_profile_page');
        dispatch_post('/profil/créer',  'post_init_my_profile_page');

    }
}

## search
dispatch('/recherche', 'display_search_results');

if (strpos(LIM_REQUEST_URI, '/cursus') === 0) {

    ## cursus
    dispatch('/cursus/:name',      'display_cursus');
    dispatch_post('/cursus/:name', 'display_cursus');

    ## educational paths
    dispatch('/cursus/:cursus/parcours/:path', 'display_educational_path');

    ## cursus feeds
    dispatch('/cursus/:name/flux.rss', 'display_cursus_rss_feed');
    dispatch('/cursus/:name/flux.atom', 'display_cursus_atom_feed');

    ## courses
    dispatch('/cursus/:name/matières', 'display_cursus_courses');

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
    dispatch('/cursus/:cursus/:course/zip',           'display_course_archive_page');

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

## signin
dispatch('/inscription', 'display_signin_form');
dispatch_post('/inscription', 'post_signin_form');

## maintenance
dispatch('/admin/migrate', 'display_admin_migrate_db_page');

## global news
dispatch('/actus/archives', 'display_news_archives');
dispatch('/actus/flux.rss', 'display_global_rss_feed');
dispatch('/actus/flux.atom', 'display_global_atom_feed');

## (almost-)static pages
dispatch('/sitemap',  'display_sitemap_page');
dispatch('/bug',      'display_bug_report');
dispatch_post('/bug', 'post_bug_report');
dispatch('/a-propos', 'display_apropos_page');
dispatch('/stats',    'display_stats_page');

## API
if (strpos(LIM_REQUEST_URI, '/api') === 0) {

    dispatch('/api/1/users/exists.json',     'json_check_username');
    dispatch('/api/1/search.json',           'json_global_search');
    dispatch('/api/1/links_list.json',       'json_get_entities_links_list');

    dispatch('/api/1/contents/last.json',    'json_get_last_contents');

    ## API endpoints for Jeditable
    if (strpos(LIM_REQUEST_URI, '/api/1/edit') === 0) {

        ### Courses
        # Edit intro text
        dispatch('/api/1/edit/course/intro.md', 'api_get_course_intro_markdown');
        dispatch_post('/api/1/edit/course/intro.html', 'api_post_course_intro');
    
    }

    dispatch('/api/1/cursus.json',            'json_get_cursus');
    dispatch('/api/1/course.json',            'json_get_course');

    dispatch('/api/1/news/get_one.json',     'json_get_news_by_id');
    dispatch_post('/api/1/news/update.json', 'json_post_update_news');
    dispatch_post('/api/1/news/delete.json', 'json_post_delete_news');
    dispatch_post('/api/1/news/create.json', 'json_post_create_news');
    dispatch_post('/api/1/news/is_old.json', 'json_post_news_mark_as_old');
    dispatch_post('/api/1/news/is_not_old.json', 'json_post_news_mark_as_not_old');

    dispatch_post('/api/1/files/rename.json', 'json_post_rename_file');
    dispatch_post('/api/1/files/delete.json', 'json_post_delete_file');

    dispatch('/api/1/short_url', 'api_create_short_url');

}

# API for internal usage (AJAX calls)
if (strpos(LIM_REQUEST_URI, '/jsapi') === 0) {

    # Logging
    dispatch_post('/jsapi/log.json', 'json_js_log');

    # Edit pages' texts
    dispatch('/jsapi/edit/page.md',    'jsapi_get_page_markdown');
    dispatch_post('/jsapi/edit/page.html', 'jsapi_post_page');

    # Edit cursus' intros
    dispatch('/jsapi/edit/cursus/intro.md',  'jsapi_get_cursus_intro_markdown');
    dispatch_post('/jsapi/edit/cursus/intro.html', 'jsapi_post_cursus_intro');

    # Edit content's titles
    dispatch_post('/jsapi/edit/content/title.html', 'jsapi_post_change_content_title');
    # Edit content's years
    dispatch_post('/jsapi/edit/content/year.html', 'jsapi_post_change_content_year');
}

# pages
dispatch('/:name/**', 'display_page');

