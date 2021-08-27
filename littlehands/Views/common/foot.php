<?php
// if(!$s->getAuthenticateStatus()) {
//     $s->clear('user');
// }

print "■■■■■■■　SESSION[user]　■■■■■■■";
print "<pre>";
print_r($_SESSION['user']);
print "</pre>";
print "<br /><br />";

print "■■■■■■■　SESSION[hand]　■■■■■■■";
print "<pre>";
print_r($_SESSION['hand']);
print "</pre>";
print "<br /><br />";

print "■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■　ここでクリア　■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■";
print "<br /><br />";

$s->clear('hand');
$s->clear('errors');
$s->clear('isError');

print "■■■■■■■　SESSION　■■■■■■■";
print "<pre>";
print_r($_SESSION);
print "</pre>";
print "<br /><br />";

print "■■■■■■■　SERVER　■■■■■■■";
print "<pre>";
print_r($_SERVER);
print "</pre>";
print "<br /><br />";

