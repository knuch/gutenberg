<?php

namespace App;

foreach (glob(dirname(__FILE__) . '/cpt/*.php') as $filename) {
  include $filename;
}
