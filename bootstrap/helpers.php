<?php

/**
 * Flash messages helper
 *
 * @return string
 */
function getFlashMessages() {
    $flashes = false;
    $msg_html = '<div class="container flash-container"> <div class="row"> <div class="col-sm-12">';
    foreach (['error', 'warning', 'success', 'info', 'message'] as $type) {
        if (Session::has($type)) {
            $flashes = true;
            $message = Session::get($type);

            $type = ($type == 'message') ? 'info' : $type;
            $type = ($type == 'error') ? 'danger' : $type;

            $msg_html .= sprintf('<div class="alert alert-%s">%s</div>', $type, $message);
        }
    }
    $msg_html .= '</div ></div ></div >';
    if(!$flashes){
        $msg_html = '';
    }
    return $msg_html;
}

/**
 * Generate classes for body tag
 *
 * @return string
 */
function bodyClass() {
    $body_classes = [];
    $class = "";

    foreach (Request::segments() as $segment) {
        if (is_numeric($segment) || empty($segment)) {
            continue;
        }
        $class .= !empty($class) ? "-" . $segment : $segment;
        array_push($body_classes, $class);
    }

    return !empty($body_classes) ? implode(' ', $body_classes) : 'front';
}

/**
 * Checks if user following
 *
 * @param $user
 * @return bool
 */
function isUserFollowing($user) {
    $isUserFollowing = false;

    foreach ($user->followers as $follower) {
        if(Auth::check() && $follower->follower_id === Auth::user()->id){
            $isUserFollowing = true;
            break;
        }
    }
    return $isUserFollowing;
}



/**
 * Converts number to "10k, 10mil" format
 *
 * @param $num
 * @return integer
 */
function thousandsCurrencyFormat($num) {
    if($num>1000) {
        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('k', 'm', 'b', 't');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];
        return $x_display;
    }
    return $num;
}
