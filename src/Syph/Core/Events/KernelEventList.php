<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 12/10/2016
 * Time: 01:24
 */

namespace Syph\Core\Events;


final class KernelEventList
{
    const REQUEST_HANDLE = 'request.handle';
    const KERNEL_BOOTED = 'kernel.boot';
    const REQUEST_FINISH = 'request.finish';
}