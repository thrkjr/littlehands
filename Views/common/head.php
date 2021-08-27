<?php
require_once(ROOT_PATH .'Views/common/session.php');
$s = session::getInstance();
$s->start();
// $_SERVERにリファラ情報が無い場合、セッション情報を全削除
if (empty( $_SERVER['HTTP_REFERER'] ) || !isset( $_SERVER['HTTP_REFERER'])) {
    $s->allClear();
}

require_once(ROOT_PATH .'Views/common/function.php');
$s->setUrl(getUrl());

require_once(ROOT_PATH .'Controllers/LittlehandController.php');
require_once(ROOT_PATH .'Controllers/DynamicProperty.php');

$littlehand = new LittlehandController();
$results = array();

$user = $s->isExist('user') ? $s->get('user') : array();
$hand = $s->isExist('hand') ? $s->get('hand') : array();
$errors = $s->isExist('errors') ? $s->get('errors') : array();
$authenticateStatus = $s->checkAuthenticateStatus() ? $s->getAuthenticateStatus() : false;
$u1_id = isset($user) && isset($user['u1_id'])? $user['u1_id'] : '';
$h1_id = isset($hand) && isset($hand['h1_id'])? $hand['h1_id'] : '';
$a1_id = isset($hand) && isset($hand['a1_id'])? $hand['a1_id'] : '';
?>