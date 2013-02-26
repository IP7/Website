<?php

function display_course() {
    $cursus_n = params('cursus');
    $code     = params('course');

    $cursus = CursusQuery::create()->findOneByShortName($cursus_n);

    if ($cursus == null) {
        halt(NOT_FOUND);
    }

    # 'global' is a special keyword used for URLs of cursus' contents
    # (without course)
    if ($code === 'global') {
        redirect_to(cursus_url( $cursus ));
    }

    $course = CourseQuery::create()
                ->filterByCursus($cursus)
                ->filterByDeleted(false)
                ->findOneByShortName($code);

    if ($course == null) {

        bad_url_try_with_course_alias($code);

    }

    $base_uri  = cursus_url($cursus) . '/' . $course->getShortName();

    $moderation_bar = null;

    if (is_connected() && user()->isMember()) {
        $moderation_bar = array(
            array(
                'href' => $base_uri.'/proposer',
                'title' => 'Proposer un contenu'
            )
        );
    }

    $news = get_news($cursus, $course);

    $breadcrumbs = array(
        array(
            'href'  => cursus_url($cursus),
            'title' => $cursus->getName()
        ),
        array(
            'href'  => $base_uri,
            'title' => $course->getShortName()
        )
    );

    $responsable = $cursus->getResponsable();
    $tpl_responsable = null;

    if ( $responsable !== null ) {
        $tpl_responsable = array(

            'name' => $responsable->getPublicName(),
            'href' => user_url($responsable)

        );
    }

    $is_page_admin = (is_connected()
                        && (user()->isAdmin()
                             || user()->isResponsibleFor($cursus)));

    if ($course->getName() === $course->getShortName()) {
        $course_title = $course->getName();
    } else {
        $course_title = $course->getName() . ' (' . $course->getShortName() . ')';
    }

    $course_links = tpl_course_links($course);

    $tpl_course = array(
        'page' => array(
            'title'          => $course_title,
            'breadcrumbs'    => $breadcrumbs,

            'keywords'       => array( $course->getName(), $course->getShortName() ),
            'description'    => truncate_string($course->getDescription()),

            'news'           => tpl_news($news),

            'course'         => array(
                'name'  => $course_title,
                'ects'  => $course->getECTS(),
                'responsable' => $tpl_responsable,
                'intro' => $course->getDescription(),

                'links' => $course_links,

                'id'    => $course->getId(),

                'content_types' => tpl_course_contents($cursus, $course),

                'wiki_url' => '//wiki.infop7.org/index.php?title=' . wiki_url($course->getName())
            ),

            'cursus'         => array(
                'id'    => $cursus->getId()
            ),

            'styles' => array(
                array( 'href' => css_url('simple-course') )
            ),

            'scripts' => array(
                array( 'href'  => js_url( ($is_page_admin ? 'admin' : 'simple') . '-course') )
            ),

            'moderation_bar' => $moderation_bar,

            'feeds' => array(
                'atom' => $base_uri . '/flux.atom',
                'rss2' => $base_uri . '/flux.rss'
            )
        )
    );

    return tpl_render('course.html', $tpl_course);
}

?>
