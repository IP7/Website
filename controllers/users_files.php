<?php

function serve_user_file() {
    $id = intval(params('id'));

    $f = FileQuery::create()
            ->filterByDeleted(false)
            ->findOneById($id);

    return _serve_user_file($f);
}

function serve_user_file_by_id_and_name() {
    $id = intval(params('id'));
    // underscores are escaped, since they're MySQL wildcards,
    // so we need to replace '\_' with '_'
    $name = preg_replace('/\\\\_/', '_', params('name'));
    $f = FileQuery::create()
            ->filterByDeleted(false)
            ->findOneById($id);

    if ($f) {
        $encoded_filename = name_encode($f->getTitle());

        if ($encoded_filename !== $name) {
            redirect_to(
                '/file/'.$id.'/'.$encoded_filename,
                array('status' => HTTP_MOVED_PERMANENTLY)
            );
        }
    }
    return _serve_user_file($f);
}

// helper
function _serve_user_file($f) {

    if (!$f) {
        halt(NOT_FOUND);
    }

    $user_rights = is_connected() ? user()->getRights() : 0;

    if ($user_rights < $f->getAccessRights()) {
        return halt(HTTP_FORBIDDEN);
    }

    $path = Config::$app_dir.'/../data/usersfiles/'.$f->getPath();
    // get absolute path
    $path = preg_replace('@/([^/]+)/\.\./@', '/', $path);

    if (!file_exists($path)) {
        halt(NOT_FOUND);
    }

    return render_file($path, true);
}

