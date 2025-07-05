<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;

class ClassController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['admin'];
    }
    
    public function classes(): View
    {
        return $this->result();
    }

    private function result($extra = [])
    {
        $classes = SchoolClass::orderby('displayorder', 'asc')->get();
        return view('admin.classes.show', array_merge(['model' => $classes], $extra));
    }
}
