<?php

/**
 * console_log
 *
 * @param mixed $data
 *
 * @return void
 */
function console_log($data)
{
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}

/**
 * phpAlert
 *
 * @param mixed $msg
 *
 * @return void
 */
function phpAlert($msg)
{
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}