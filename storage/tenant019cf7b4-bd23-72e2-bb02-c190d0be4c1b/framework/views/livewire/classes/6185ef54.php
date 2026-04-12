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
        return app('view')->file('/var/www/html/storage/tenant019cf7b4-bd23-72e2-bb02-c190d0be4c1b/framework/views/livewire/views/6185ef54.blade.php', $data);
    }
};

