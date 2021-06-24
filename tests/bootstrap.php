<?php

use JSiefer\ClassMocker\ClassMocker;
use JSiefer\MageMock\MagentoMock;

$magentoFramework = new MagentoMock();

$classMocker = new ClassMocker();

$classMocker->setGenerationDir('./var/generation');
$classMocker->mockFramework($magentoFramework);
$classMocker->enable();
