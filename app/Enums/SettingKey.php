<?php

namespace App\Enums;

enum SettingKey: string
{
    case EventDate = 'event_date';
    case EventLocationName = 'event_location_name';
    case EventLocationAddress = 'event_location_address';
}
