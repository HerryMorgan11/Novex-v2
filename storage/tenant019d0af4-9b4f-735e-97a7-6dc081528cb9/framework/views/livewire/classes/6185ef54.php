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
        return app('view')->file('/var/www/html/storage/tenant019d0af4-9b4f-735e-97a7-6dc081528cb9/framework/views/livewire/views/6185ef54.blade.php', $data);
    }
};

