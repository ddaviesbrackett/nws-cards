<?php

namespace App\Utilities;

use App\Models\SchoolClass;

class OrderUtilities
{
    public function choosableBuckets()
    {
        $c = SchoolClass::choosable();
        return $c->map(function (SchoolClass $item, int $k) {
            return $item["bucketname"];
        });
    }

    public function idFromBucket($bucket)
    {
        return SchoolClass::where('bucketname', $bucket)->first()->id;
    }
}
