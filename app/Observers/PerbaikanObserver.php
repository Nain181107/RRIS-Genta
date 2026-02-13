<?php

namespace App\Observers;

use App\Events\DataChanged;

class PerbaikanObserver
{
    public function created($model)
    {
        broadcast(new DataChanged('perbaikan'))->toOthers();
    }

    public function updated($model)
    {
        broadcast(new DataChanged('perbaikan'))->toOthers();
    }

    public function deleted($model)
    {
        broadcast(new DataChanged('perbaikan'))->toOthers();
    }
}
