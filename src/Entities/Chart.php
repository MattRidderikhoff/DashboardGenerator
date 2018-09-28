<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-09-28
 * Time: 1:46 PM
 */

namespace App\Entities;


abstract class Chart extends Node
{
    const X_AXIS_TOKEN = 'X is';
    const Y_AXIS_TOKEN = 'Y is';
    const X_ORDER_TOKEN = 'Order X';
    const Y_ORDER_TOKEN = 'Order Y';
}