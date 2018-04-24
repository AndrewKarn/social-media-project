<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 3/30/18
 * Time: 4:15 PM
 */
$mongodate = new \MongoDB\BSON\UTCDateTime();
echo $mongodate->toDateTime()->format('c');