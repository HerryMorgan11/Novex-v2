<?php

use Livewire\Component;
use App\Models\User;

return new class extends Component
{
    public $section = "home";
    public $users = [];

    public function mount()
    {
        $currentTenant = tenant();
        
        if ($currentTenant) {
            $this->users = User::whereHas('memberships', function ($query) use ($currentTenant) {
                $query->where('tenant_id', $currentTenant->id);
            })->get();
        }
    }

    public function changeSection($section)
    {
        $this->section = $section;
    }

    protected function view($data = [])
    {
        return app('view')->file('/var/www/html/storage/tenant019cf7b4-4c4a-7391-9123-b1b0b5d28e59/framework/views/livewire/views/6185ef54.blade.php', $data);
    }
};

