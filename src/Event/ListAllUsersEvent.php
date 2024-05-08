<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class ListAllUsersEvent extends Event
{
    const LIST_ALL_USER_EVENT = 'user.list_alls';

    public function __construct(private int $nbUser) {}

    public function getNbUser(): int {
        return $this->nbUser;
    }

}