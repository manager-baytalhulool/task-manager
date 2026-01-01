<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case CREATED = "created";
    case IN_PROGRESS = "in progress";
    case COMPLETED = "completed";
}
