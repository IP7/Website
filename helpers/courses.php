<?php

// used to sort contents by title, with uasort()
function cmpTitles($c1, $c2) {

    return strnatcasecmp($c1['title'], $c2['title']);

}

// return an array for contents which will be used
// in the template.
function tpl_course_contents($cursus, $course=null) {

    $user_rights = (is_connected()) ? user()->getRights() : 0;

    $types = ContentTypeQuery::create()
                ->where('Access_Rights <= ?', $user_rights, PDO::PARAM_INT)
                ->find();

    if (!$types) {
        return null;
    }

    $tpl_contents = array();

    foreach($types as $t) {

        $tpl_cts = array();

        $cts = ContentQuery::create()
                ->filterByContentType($t);

        if ($course) { $cts = $cts->filterByCourse($course); }

        $cts = $cts->filterByCourse($course)
                ->filterByValidated(true)
                ->filterByDeleted(false)
                ->where('Access_Rights <= ?', $user_rights, PDO::PARAM_INT)
                ->orderByYear('desc')
                ->find();

        if (count($cts)) {

            $no_year = array();

            foreach ($cts as $c) {
                $year = 0+$c->getYear();

                if ($year < 2000) {
                    $no_year []= array(
                        'href'  => content_url($cursus, $course, $c),
                        'title' => $c->getTitle()
                    );
                    continue;
                }
                if (!array_key_exists($year, $tpl_cts)) {
                    $tpl_cts[$year] = array(
                        'title'    => $year.'/'.($year+1),
                        'contents' => array()
                    );
                }

                $tpl_cts [$year]['contents'] []= array(
                    'href'  => content_url($cursus, $course, $c),
                    'title' => $c->getTitle()
                );
            }

            // sorting
            uasort($no_year, 'cmpTitles');

            foreach($tpl_cts as &$yArray) {

                uasort($yArray['contents'], 'cmpTitles');

            }

            $tpl_contents []= array(
                'short_name' => $t->getShortName(),
                'title'      => Lang\plurial($t->getName()),

                'years'   => $tpl_cts,
                'no_year' => $no_year
            );
        }
    }

    return $tpl_contents;
}

function tpl_course_links($course) {

    $links = CourseUrlQuery::create()
                ->filterByCourse($course)
                ->find();

    $tpl_links = array();

    foreach ($links as $_ => $l) {

        $tpl_links []= array(

            'href' => $l->getUrl(),
            'text' => $l->getText()

        );
    
    }

    return $tpl_links;
}
