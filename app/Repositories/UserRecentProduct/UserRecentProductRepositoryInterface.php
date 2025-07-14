<?php

namespace App\Repositories\UserRecentProduct;

interface UserRecentProductRepositoryInterface
{
    public function mergeRecentViewed($userId, $sessionId);
}
