<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case CREATED = "created";
    case IN_PROGRESS = "in progress";
    case ON_HOLD = "on hold";
    case NEEDS_REVISION = "needs revision";
    case IN_REVIEW = "in review";
    case COMPLETION_APPROVED = "completion approved";
}
