<?php

// Tests/unit/EtudiantTest.php
require_once dirname(__FILE__).'/../bootstrap/unit.php';
 
$t = new lime_test(1);
 
$t->is(Etudiant::setPrenom('souleymane'), 'souleymane');

