<?php
// auto-generated by sfRoutingConfigHandler
// date: 2009/10/17 21:59:11
return array(
'homepage' => new sfRoute('/', array (
  'module' => 'search',
  'action' => 'index',
), array (
), array (
)),
'default_index' => new sfRoute('/:module', array (
  'action' => 'index',
), array (
), array (
)),
'default' => new sfRoute('/:module/:action/*', array (
), array (
), array (
)),
);