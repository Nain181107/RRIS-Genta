<?php

namespace App\Observers;

use App\Events\DataChanged;

class PengirimanObserver
{
    public function created($model)
    {
        broadcast(new DataChanged('pengiriman'))->toOthers();
    }

    public function updated($model)
    {
        broadcast(new DataChanged('pengiriman'))->toOthers();
    }

    public function deleted($model)
    {
        broadcast(new DataChanged('pengiriman'))->toOthers();
    }
}
