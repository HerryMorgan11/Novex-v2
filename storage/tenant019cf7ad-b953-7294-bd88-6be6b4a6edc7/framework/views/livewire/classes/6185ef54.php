<?php

use Livewire\Component;
use App\Models\User;

return new class extends Component
{
    public $section = "home";
    public $users = [];

    public function mount()
    {
        $this->users = User::all();
    }

    public function changeSection($section)
    {
        $this->section = $section;
    }

    protected function view($data = [])
    {
        return app('view')->file('/var/www/html/storage/tenant019cf7ad-b953-7294-bd88-6be6b4a6edc7/framework/views/livewire/views/6185ef54.blade.php', $data);
    }
};

