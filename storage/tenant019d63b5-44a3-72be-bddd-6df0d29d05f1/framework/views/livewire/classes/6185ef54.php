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
        return app('view')->file('/var/www/html/storage/tenant019d63b5-44a3-72be-bddd-6df0d29d05f1/framework/views/livewire/views/6185ef54.blade.php', $data);
    }
};

