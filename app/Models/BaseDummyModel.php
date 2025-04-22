<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseDummyModel extends Model
{
    abstract public static function getEndpoint(): string;
    abstract public static function getNameEntity(): string;

    abstract public static function mapApiData(array $apiData): array;
}
